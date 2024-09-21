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
        Schema::create('fields', static function (Blueprint $table): void {
            $table->id();
            $table->string('language', 2)->nullable(false);
            $table->string('entity_type')->nullable(false);
            $table->bigInteger('entity_id')->nullable(false);
            $table->string('attribute', 255)->nullable(false);
            $table->string('value', 5000)->nullable(false);
            $table->timestamps();

            $table->unique(['language', 'entity_type', 'entity_id', 'attribute']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
