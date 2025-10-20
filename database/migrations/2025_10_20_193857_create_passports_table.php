<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();

            // Collected from agent
            $table->foreignId('agent_id')->constrained()->cascadeOnDelete();

            // Applicant info
            $table->string('applicant_name');             // name
            $table->string('address')->nullable();        // address
            $table->string('phone', 32)->nullable();      // phone number
            $table->date('date_of_birth')->nullable();    // date of birth

            // Passport info
            $table->string('passport_number', 50)->unique();
            $table->string('nationality')->nullable();
            $table->string('passport_picture')->nullable(); // stored path (storage/app/public/...)
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('nid_number', 50)->nullable();

            // Workflow status (simple for now)
            $table->string('status')->default('RECEIVED_FROM_AGENT');

            $table->timestamps();

            $table->index(['agent_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
