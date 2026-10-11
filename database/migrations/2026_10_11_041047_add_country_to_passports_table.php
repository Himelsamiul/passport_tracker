<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the searchable Country field to passports and enforce passport
     * number uniqueness at the database level as well (the validation rule
     * already blocked duplicates in the forms). Idempotent: safe to re-run
     * after a partial execution.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('passports', 'country')) {
            Schema::table('passports', function (Blueprint $table) {
                $table->string('country')->nullable()->after('nationality');
            });
        }

        if (! collect(DB::select("SHOW INDEX FROM passports WHERE Key_name = 'passports_passport_number_unique'"))->count()) {
            Schema::table('passports', function (Blueprint $table) {
                $table->unique('passport_number');
            });
        }
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            if (Schema::hasColumn('passports', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
};
