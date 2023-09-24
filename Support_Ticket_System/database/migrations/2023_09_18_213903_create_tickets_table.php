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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('department_id')->nullable();
            $table->integer('user_id');
            $table->string('token_no');
            $table->string('subject', 300)->nullable();
            $table->string('description', 10000)->nullable();
            $table->enum('priority',['low', 'medium', 'high'])->nullable();
            $table->enum('status',['open', 'replied', 'closed', 'pending'])->nullable();
            $table->integer('assigned_to')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
