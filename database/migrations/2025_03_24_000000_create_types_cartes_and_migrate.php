<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_cartes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('libelle', 120);
            $table->unsignedBigInteger('prix')->default(0);
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        $now = now();
        foreach ([
            ['code' => 'ADAN', 'libelle' => 'ADAN', 'prix' => 10000, 'ordre' => 1],
            ['code' => 'LAFIA', 'libelle' => 'LAFIA', 'prix' => 15000, 'ordre' => 2],
            ['code' => 'ELITE', 'libelle' => 'ELITE', 'prix' => 25000, 'ordre' => 3],
        ] as $row) {
            DB::table('types_cartes')->insert(array_merge($row, ['created_at' => $now, 'updated_at' => $now]));
        }

        $this->migrateTable('stocks', true);
        $this->migrateTable('ventes', false);
        $this->migrateTable('clients', false);
        $this->migrateTable('mouvements_stock', false);
    }

    private function migrateTable(string $table, bool $hasStockUnique): void
    {
        Schema::table($table, function (Blueprint $tableBlueprint) {
            $tableBlueprint->unsignedBigInteger('type_carte_id')->nullable()->after('id');
        });

        DB::statement("
            UPDATE {$table} t
            INNER JOIN types_cartes tc ON tc.code = t.type_carte
            SET t.type_carte_id = tc.id
        ");

        if ($hasStockUnique) {
            Schema::table($table, function (Blueprint $tableBlueprint) {
                $tableBlueprint->dropUnique(['type_carte', 'agence_id']);
            });
        }

        Schema::table($table, function (Blueprint $tableBlueprint) {
            $tableBlueprint->dropColumn('type_carte');
        });

        DB::statement("ALTER TABLE `{$table}` MODIFY `type_carte_id` BIGINT UNSIGNED NOT NULL");

        Schema::table($table, function (Blueprint $tableBlueprint) use ($hasStockUnique) {
            $tableBlueprint->foreign('type_carte_id')->references('id')->on('types_cartes')->restrictOnDelete();
            if ($hasStockUnique) {
                $tableBlueprint->unique(['type_carte_id', 'agence_id']);
            }
        });
    }

    public function down(): void
    {
        // Rollback non trivial : préférer une sauvegarde avant migration
    }
};
