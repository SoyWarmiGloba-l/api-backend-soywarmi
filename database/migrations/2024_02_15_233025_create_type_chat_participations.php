<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeders\ChatParticipationsTypeDataDefault;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('type_chat_participations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        //$this->call(ChatParticipationsTypeDataDefault::class);
        //ChatParticipationsTypeDataDefault::call();
        DB::table('type_chat_participations')->insert([
            ['name' => 'creator'],
            ['name' => 'participant'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_chat_participations');
    }
};
