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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('footer_description', 1000)->nullable();
            $table->string('keywords')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('copyrights')->nullable();
            $table->enum('staff_can_edit', ['yes', 'no'])->nullable();
            $table->enum('client_can_edit', ['yes', 'no'])->nullable();
            $table->string('ticket_email')->nullable();
            $table->string('admin_email')->nullable();
            $table->rememberToken();
            $table->timestamps();        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
