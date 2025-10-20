<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Agent name
            $table->string('phone', 32)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();  // NEW: address
            $table->timestamps();                   // created_at = create time/date
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
