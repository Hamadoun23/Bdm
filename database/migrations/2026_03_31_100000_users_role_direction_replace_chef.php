<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::table('users')->where('role', 'chef_agence')->update(['role' => 'commercial']);

        DB::table('agences')->whereNotNull('chef_id')->update(['chef_id' => null]);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'commercial', 'direction') NOT NULL DEFAULT 'commercial'");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::table('users')->where('role', 'direction')->update(['role' => 'commercial']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'commercial', 'chef_agence') NOT NULL DEFAULT 'commercial'");
    }
};
