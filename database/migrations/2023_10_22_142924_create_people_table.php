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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->nullable()->constrained('teams');
            $table->foreignId('role_id')->constrained('roles');
            $table->string('name', 30);
            $table->string('lastname', 30);
            $table->string('mother_lastname', 30)->nullable();
            $table->text('description')->nullable();
            $table->string('email', 50);
            $table->string('password');
            $table->string('photo')->nullable();
            $table->date('birthday');
            $table->string('gender')->nullable();
            $table->integer('phone');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
