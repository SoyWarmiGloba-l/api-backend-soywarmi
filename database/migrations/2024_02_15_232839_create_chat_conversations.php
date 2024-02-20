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
        Schema::create('chat_conversations', function (Blueprint $table) {
            //$table->unsignedBigInteger('id_chat_conversation')->primary()->autoIncrement();
            $table->unsignedBigInteger('id_chat_conversation')->autoIncrement();
            $table->string("name");
            $table->unsignedBigInteger('id_type_chat_conversations')->index();
            $table->foreign('id_type_chat_conversations')->references('id_type_chat_conversations')->on('type_chat_conversations');            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_conversations');
    }
};
