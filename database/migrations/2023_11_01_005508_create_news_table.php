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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_type_id')->constrained('event_types');
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_date')->default(\Carbon\Carbon::now());
            $table->dateTime('end_date')->default(\Carbon\Carbon::now());
            $table->json('areas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
