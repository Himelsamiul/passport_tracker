<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stakeholder fields are now optional on passports: agent_id becomes
     * nullable (employee_id / passport_officer_id already are).
     */
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
        });

        Schema::table('passports', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->change();
        });

        Schema::table('passports', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agents')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
        });

        Schema::table('passports', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->change();
        });

        Schema::table('passports', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('agents')->cascadeOnDelete();
        });
    }
};
