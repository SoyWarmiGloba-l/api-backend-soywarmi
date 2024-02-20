<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeders\ChatConversationsTypeDataDefault;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('type_chat_conversations', function (Blueprint $table) {
            //$table->unsignedBigInteger('id_type_chat_conversations')->primary();
            $table->unsignedBigInteger('id_type_chat_conversations')->autoIncrement();
            $table->string('name');
        });
        DB::table('type_chat_conversations')->insert([
            ['name' => 'p2p'],
            ['name' => 'group'],
        ]);
        //ChatConversationsTypeDataDefault::call();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_chat_conversations');
    }
};
