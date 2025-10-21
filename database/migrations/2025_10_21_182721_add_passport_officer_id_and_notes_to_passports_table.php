<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            // Add after agent_id to keep logical order
            $table->unsignedBigInteger('passport_officer_id')->nullable()->after('agent_id');
            $table->text('notes')->nullable()->after('passport_picture');

            // FK to passport_officers (null on delete)
            $table->foreign('passport_officer_id')
                  ->references('id')
                  ->on('passport_officers')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            // Drop FK then columns
            if (Schema::hasColumn('passports', 'passport_officer_id')) {
                $table->dropForeign(['passport_officer_id']);
                $table->dropColumn('passport_officer_id');
            }
            if (Schema::hasColumn('passports', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
