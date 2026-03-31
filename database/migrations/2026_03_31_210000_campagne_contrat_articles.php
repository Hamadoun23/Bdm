<?php

use App\Models\CampagneContratArticle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campagne_contrat_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('titre', 255);
            $table->text('contenu');
            $table->timestamps();
        });

        foreach (DB::table('campagnes')->pluck('id') as $campagneId) {
            CampagneContratArticle::seedDefaultsIfEmpty((int) $campagneId);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('campagne_contrat_articles');
    }
};
