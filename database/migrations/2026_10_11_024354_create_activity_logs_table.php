<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();     // snapshot at log time
            $table->string('role_name')->nullable();     // snapshot at log time
            $table->string('action', 32);                // created|updated|deleted|login|logout|login_failed
            $table->string('module', 64);                // agents|passports|auth|...
            $table->text('description');
            $table->string('subject_type')->nullable();  // e.g. App\Models\Passport
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('changes')->nullable();         // {field: {old, new}} on updates
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();

            $table->index(['module', 'action']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
