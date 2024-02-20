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
        Schema::create('chat_mensages_participation', function (Blueprint $table) {
            //$table->unsignedBigInteger('id_chat_mensages_participation')->primary()->autoIncrement(); 
            $table->unsignedBigInteger('id_chat_mensages_participation')->autoIncrement(); 
            $table->unsignedBigInteger('id_chat_participation')->index();
            $table->foreign('id_chat_participation')->references('id_chat_participation')->on('chat_participations');
            $table->text("content");
            $table->json("read_message_participants");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_mensages_participation');
    }
};
