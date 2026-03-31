<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampagneContratArticle extends Model
{
    protected $table = 'campagne_contrat_articles';

    protected $fillable = [
        'campagne_id', 'sort_order', 'titre', 'contenu',
    ];

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(Campagne::class);
    }

    /** @return list<array{titre: string, contenu: string}> */
    public static function defaultArticlesDefinitions(): array
    {
        return [
            [
                'titre' => 'Article 1 : Objet du contrat',
                'contenu' => 'Le présent contrat a pour objet de définir les conditions dans lesquelles la Prestataire s’engage à assurer, pour le compte de GDA, la commercialisation des cartes bancaires BDM SA dans le cadre d’une campagne pilotée par GDA en partenariat avec la Banque de Développement du Mali (BDM SA).',
            ],
            [
                'titre' => 'Article 2 : Durée de la mission',
                'contenu' => 'La mission du Prestataire est conclue pour une durée déterminée correspondant à la période de la campagne telle qu’enregistrée dans l’application (dates de début et de fin), sauf résiliation anticipée dans les conditions prévues au présent contrat.',
            ],
            [
                'titre' => 'Article 3 : Conditions d’exécution',
                'contenu' => "La Prestataire s’engage notamment à :\n\n- Participer activement à la campagne de commercialisation des cartes BDM SA ;\n- Atteindre les objectifs de vente qui lui seront fixés en début de mission ;\n- Être disponible pendant les heures d’ouverture de la banque dans sa zone d’affectation ;\n- Transmettre chaque lundi au plus tard à 12h un rapport hebdomadaire d’activité ;\n- Intégrer et rester actif(ve) dans le groupe WhatsApp de coordination mis en place par GDA ;\n- Respecter l’éthique commerciale, l’image de marque de GDA et les consignes de la BDM SA.",
            ],
            [
                'titre' => 'Article 5 : Matériel fourni',
                'contenu' => "Un forfait téléphonique hebdomadaire peut être financé par GDA, pour permettre la transmission des rapports et la coordination des actions, selon les versements enregistrés dans l’application.\n\nLa Prestataire recevra de la BDM SA, pour les besoins de la campagne : un tee-shirt et une casquette de campagne, un argumentaire commercial et les outils nécessaires à la prospection.",
            ],
            [
                'titre' => 'Article 6 : Statut du prestataire',
                'contenu' => 'La Prestataire intervient en toute indépendance, en tant que prestataire de services non salarié. Il n’existe entre les parties aucun lien de subordination, ni de relation de travail salarié.',
            ],
            [
                'titre' => 'Article 7 : Résiliation',
                'contenu' => 'Le présent contrat pourra être résilié de plein droit par GDA, sans indemnité, en cas de : non-respect des obligations contractuelles ; résultats commerciaux manifestement insuffisants sans justification ; attitude contraire à l’éthique ou aux règles de la campagne. En cas de résiliation anticipée pour faute du Prestataire, aucun paiement ne sera exigible.',
            ],
            [
                'titre' => 'Article 8 : Confidentialité',
                'contenu' => 'La Prestataire s’engage à garder confidentielles toutes les informations commerciales, stratégiques ou personnelles auxquelles il pourrait avoir accès dans le cadre de sa mission.',
            ],
            [
                'titre' => 'Article 9 : Engagement de présence et reporting',
                'contenu' => 'La Prestataire s’engage à respecter les horaires de présence définis, à tenir un discours conforme aux éléments fournis, et à remonter toute difficulté rencontrée à GDA dans les plus brefs délais.',
            ],
        ];
    }

    public static function seedDefaultsIfEmpty(int $campagneId): void
    {
        if (self::query()->where('campagne_id', $campagneId)->exists()) {
            return;
        }
        foreach (self::defaultArticlesDefinitions() as $i => $row) {
            self::query()->create([
                'campagne_id' => $campagneId,
                'sort_order' => $i,
                'titre' => $row['titre'],
                'contenu' => $row['contenu'],
            ]);
        }
    }
}
