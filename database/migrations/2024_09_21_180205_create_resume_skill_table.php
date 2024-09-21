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
        Schema::create('resume_skill', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('resume_id')->nullable(false);
            $table->foreignId('skill_id')->nullable(false);
            $table->timestamps();

            $table->unique(['resume_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_skill');
    }
};
