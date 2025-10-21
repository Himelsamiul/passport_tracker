<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('passport_processings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passport_id')->constrained('passports')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate()->restrictOnDelete(); // picked up by
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->cascadeOnUpdate()->nullOnDelete();
            $table->string('status')->default('PENDING'); // PENDING | IN_PROGRESS | DONE | REJECTED
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('passport_processings')) {
            Schema::table('passport_processings', function (Blueprint $table) {
                $table->dropConstrainedForeignId('passport_id');
                $table->dropConstrainedForeignId('employee_id');
                $table->dropConstrainedForeignId('agency_id');
            });
        }
        Schema::dropIfExists('passport_processings');
    }
};
