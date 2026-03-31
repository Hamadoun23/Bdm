<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('agences') && Schema::hasColumn('agences', 'chef_id')) {
            DB::table('agences')->whereNotNull('chef_id')->update(['chef_id' => null]);
        }
    }

    public function down(): void
    {
        //
    }
};
