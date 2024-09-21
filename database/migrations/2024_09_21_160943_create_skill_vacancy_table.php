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
        Schema::create('skill_vacancy', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('skill_id')->nullable(false);
            $table->foreignId('vacancy_id')->nullable(false);
            $table->timestamps();

            $table->unique(['skill_id', 'vacancy_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_vacancy');
    }
};
