<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telephone', 20)->nullable()->after('name');
            $table->enum('role', ['admin', 'commercial', 'chef_agence'])->default('commercial')->after('telephone');
            $table->foreignId('agence_id')->nullable()->after('role')->constrained('agences')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['agence_id']);
            $table->dropColumn(['telephone', 'role', 'agence_id']);
        });
    }
};
