<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use mysqli;
use RuntimeException;

/**
 * Remplace toutes les données locales par celles du dump prod (schéma = migrations actuelles).
 * Seuls les comptes utilisateurs « commercial_telephonique » déjà présents en local sont conservés
 * (même id, rôle, mot de passe, etc.) : le reste vient intégralement de la production.
 *
 * Prérequis : même serveur MySQL que .env, compte avec droits CREATE DATABASE.
 *
 * Sans argument : lit **prod_bdm.sql** à la racine du projet (c’est la source des données).
 *
 * Exemples :
 *   php artisan db:merge-prod
 *   php artisan db:merge-prod autre_dump.sql
 */
class MergeProdSqlIntoLocal extends Command
{
    protected $signature = 'db:merge-prod
        {sqlfile? : Fichier dump MySQL (défaut : prod_bdm.sql à la racine du projet)}
        {--tmp-db=bdm_merge_prod_import : Nom de la base temporaire (sera recréée)}
        {--yes : Ne pas demander de confirmation (à utiliser avec prudence)}';

    protected $description = 'Charge les données depuis prod_bdm.sql (ou le .sql indiqué), schéma Laravel à jour, conserve les comptes commercial_telephonique locaux';

    /** @var list<string> */
    private array $copyTables = [
        'types_cartes',
        'agences',
        'users',
        'campagnes',
        'campagne_agence',
        'campagne_remise_type_carte',
        'campagne_commercial_contrat',
        'campagne_aide_beneficiaire',
        'campagne_contrat_articles',
        'campagne_actions',
        'campagne_aide_versements',
        'stocks',
        'clients',
        'ventes',
        'mouvements_stock',
        'primes',
        'reclamations',
        'contrat_prestation_reponses',
    ];

    public function handle(): int
    {
        if (! $this->option('yes') && ! $this->confirm(
            'Toutes les données locales seront supprimées (migrate:fresh), puis remplacées par le contenu du fichier SQL choisi (par défaut prod_bdm.sql). Seuls vos comptes « commercial_telephonique » actuels seront réappliqués (mots de passe / rôle). Continuer ?'
        )) {
            $this->warn('Annulé.');

            return self::SUCCESS;
        }

        $path = $this->resolveSqlFilePath();
        if (! is_readable($path)) {
            $this->error('Fichier SQL introuvable ou illisible : '.$path);
            $this->line('Astuce : placez prod_bdm.sql à la racine du projet (à côté de artisan) ou passez le chemin complet.');

            return self::FAILURE;
        }

        $path = realpath($path) ?: $path;
        $this->info('Source des données (fichier utilisé) : '.$path);

        if (config('database.default') !== 'mysql') {
            $this->error('Cette commande nécessite DB_CONNECTION=mysql dans .env (dump MySQL / MariaDB).');

            return self::FAILURE;
        }

        $cfg = config('database.connections.mysql');
        $host = $cfg['host'] ?? '127.0.0.1';
        $port = (int) ($cfg['port'] ?? 3306);
        $username = $cfg['username'] ?? 'root';
        $password = $cfg['password'] ?? '';
        $database = $cfg['database'] ?? '';
        $socket = trim((string) ($cfg['unix_socket'] ?? ''));

        if ($database === '') {
            $this->error('DB_DATABASE non défini dans .env');

            return self::FAILURE;
        }

        $tmpDb = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $this->option('tmp-db')) ?: 'bdm_merge_prod_import';

        $backupPath = storage_path('app/db_merge_comptes_telephonique_'.date('Ymd_His').'.json');
        $this->backupComptesTelephoniqueUniquement($backupPath);

        $mysqli = $this->connect($host, $port, $username, $password, $socket);

        try {
            $this->importDumpToTemp($mysqli, $tmpDb, $path);
            $this->info('Dump importé dans la base temporaire `'.$tmpDb.'`.');
        } catch (\Throwable $e) {
            $mysqli->close();
            $this->error('Échec import temporaire : '.$e->getMessage());

            return self::FAILURE;
        }

        $mysqli->close();

        $this->warn('Exécution de migrate:fresh…');
        Artisan::call('migrate:fresh', ['--force' => true]);
        $this->line(Artisan::output());

        $mysqli = $this->connect($host, $port, $username, $password, $socket);
        try {
            $mysqli->select_db($database);
            $mysqli->query('SET FOREIGN_KEY_CHECKS=0');
            // migrate:fresh laisse des lignes (ex. types_cartes) : vider avant copie pour éviter les doublons de clé primaire.
            foreach (array_reverse($this->copyTables) as $table) {
                $mysqli->query('TRUNCATE TABLE `'.$database.'`.`'.$table.'`');
            }
            foreach ($this->copyTables as $table) {
                $sql = "INSERT INTO `{$database}`.`{$table}` SELECT * FROM `{$tmpDb}`.`{$table}`";
                if (! $mysqli->query($sql)) {
                    throw new RuntimeException('Copie '.$table.' : '.$mysqli->error);
                }
                $this->line('Copié : '.$table);
            }
            $mysqli->query('SET FOREIGN_KEY_CHECKS=1');

            $this->fixAutoIncrements($mysqli, $database);
            $this->restoreComptesTelephonique($backupPath);

            $mysqli->query("DROP DATABASE IF EXISTS `{$tmpDb}`");
            $this->info('Base temporaire supprimée.');
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            $mysqli->close();

            return self::FAILURE;
        }

        $mysqli->close();

        $this->info('Terminé. Copie de secours des comptes téléphonique : '.$backupPath);

        return self::SUCCESS;
    }

    private function resolveSqlFilePath(): string
    {
        $arg = $this->argument('sqlfile');
        if ($arg === null || trim((string) $arg) === '') {
            return base_path('prod_bdm.sql');
        }
        $path = trim((string) $arg);
        if (! preg_match('#^([A-Z]:[\\\\/]|/|\\\\)#i', $path)) {
            $path = base_path($path);
        }

        return $path;
    }

    private function connect(string $host, int $port, string $user, string $pass, string $socket = ''): mysqli
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        if ($socket !== '') {
            $mysqli = new mysqli($host !== '' ? $host : 'localhost', $user, $pass, '', (int) $port, $socket);
        } else {
            $mysqli = new mysqli($host, $user, $pass, '', $port);
        }
        $mysqli->set_charset('utf8mb4');

        return $mysqli;
    }

    private function importDumpToTemp(mysqli $mysqli, string $tmpDb, string $sqlPath): void
    {
        $mysqli->query('DROP DATABASE IF EXISTS `'.$tmpDb.'`');
        $mysqli->query('CREATE DATABASE `'.$tmpDb.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $mysqli->select_db($tmpDb);

        $sql = file_get_contents($sqlPath);
        if ($sql === false) {
            throw new RuntimeException('Lecture du fichier SQL impossible.');
        }

        if ($mysqli->multi_query($sql) === false) {
            throw new RuntimeException($mysqli->error);
        }
        do {
            if ($result = $mysqli->store_result()) {
                $result->free();
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
    }

    private function fixAutoIncrements(mysqli $mysqli, string $database): void
    {
        foreach ($this->copyTables as $table) {
            $res = $mysqli->query("SELECT MAX(`id`) AS m FROM `{$database}`.`{$table}`");
            if (! $res) {
                continue;
            }
            $row = $res->fetch_assoc();
            $res->free();
            $max = isset($row['m']) ? (int) $row['m'] : 0;
            if ($max > 0) {
                $mysqli->query('ALTER TABLE `'.$database.'`.`'.$table.'` AUTO_INCREMENT = '.($max + 1));
            }
        }
    }

    private function backupComptesTelephoniqueUniquement(string $jsonPath): void
    {
        $users = [];
        if (Schema::hasTable('users')) {
            $users = DB::table('users')
                ->where('role', 'commercial_telephonique')
                ->get()
                ->map(fn ($r) => (array) $r)
                ->all();
        }

        if (! is_dir(dirname($jsonPath))) {
            mkdir(dirname($jsonPath), 0755, true);
        }
        file_put_contents($jsonPath, json_encode(['users_telephonique' => $users], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        if ($users === []) {
            $this->warn('Aucun utilisateur « commercial_telephonique » en local : après import, tous les users viendront du dump (rôle commercial pour Nènè / Diahara, etc.).');
        } else {
            $this->info('Comptes à conserver (commercial_telephonique) : '.count($users).' utilisateur(s).');
        }
    }

    private function restoreComptesTelephonique(string $jsonPath): void
    {
        if (! is_readable($jsonPath)) {
            return;
        }
        $raw = file_get_contents($jsonPath);
        if ($raw === false) {
            return;
        }
        /** @var array{users_telephonique?: array<int, array<string, mixed>>} $data */
        $data = json_decode($raw, true);
        if (! is_array($data)) {
            return;
        }

        foreach ($data['users_telephonique'] ?? [] as $row) {
            $id = (int) ($row['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $payload = $row;
            unset($payload['id']);
            if (DB::table('users')->where('id', $id)->exists()) {
                DB::table('users')->where('id', $id)->update($payload);
            } else {
                DB::table('users')->insert($row);
            }
        }

        if (($data['users_telephonique'] ?? []) !== []) {
            $this->info('Comptes commercial_telephonique réappliqués (rôle + mot de passe locaux).');
        }
    }
}
