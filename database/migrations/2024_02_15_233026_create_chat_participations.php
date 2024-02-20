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
        Schema::create('chat_participations', function (Blueprint $table) {
            //$table->unsignedBigInteger('id_chat_participation')->primary()->autoIncrement(); 
            $table->unsignedBigInteger('id_chat_participation')->autoIncrement(); 
            $table->unsignedBigInteger('id_chat_conversation')->index();
            $table->foreign('id_chat_conversation')->references('id_chat_conversation')->on('chat_conversations');
            $table->unsignedBigInteger('id_user')->index();
            $table->foreign('id_user')->references('id')->on('people');
            $table->foreignId('id_type_chat_participations')->constrained('type_chat_participations');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_participations');
    }
};
