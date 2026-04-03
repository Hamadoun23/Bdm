<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'commercial', 'commercial_telephonique', 'direction') NOT NULL DEFAULT 'commercial'");
        }

        Schema::create('telephonique_rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date_rapport');
            $table->unsignedInteger('appels_emis')->default(0);
            $table->unsignedInteger('appels_joignables')->default(0);
            $table->unsignedInteger('appels_non_joignables')->default(0);
            $table->decimal('taux_joignabilite', 6, 2)->nullable();
            $table->unsignedInteger('clients_interesses_nombre')->default(0);
            $table->decimal('clients_interesses_pct', 6, 2)->nullable();
            $table->unsignedInteger('clients_deja_servis_nombre')->default(0);
            $table->decimal('clients_deja_servis_pct', 6, 2)->nullable();
            $table->unsignedInteger('propose_visa')->default(0);
            $table->unsignedInteger('propose_gim')->default(0);
            $table->unsignedInteger('propose_cauris')->default(0);
            $table->unsignedInteger('propose_prepayee')->default(0);
            $table->unsignedInteger('nj_repondeur')->default(0);
            $table->unsignedInteger('nj_numero_errone')->default(0);
            $table->unsignedInteger('nj_hors_reseau')->default(0);
            $table->unsignedInteger('nj_autres_nombre')->default(0);
            $table->string('nj_autres_precision')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'date_rapport']);
        });

        Schema::create('user_login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('logged_in_at');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'logged_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_login_logs');
        Schema::dropIfExists('telephonique_rapports');

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::table('users')->where('role', 'commercial_telephonique')->update(['role' => 'commercial']);
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'commercial', 'direction') NOT NULL DEFAULT 'commercial'");
        }
    }
};
