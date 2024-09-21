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
        Schema::create('reviews', static function (Blueprint $table): void {
            $table->id();
            $table->smallInteger('grade')->unsigned()->nullable(false);
            $table->string('gradable_type')->nullable(false);
            $table->bigInteger('gradable_id')->nullable(false);
            $table->foreignId('user_id')->nullable(false);
            $table->string('text', 255)->nullable(false);
            $table->boolean('is_approved')->nullable(false)->default(false);
            $table->timestamps();

            $table->unique(['gradable_type', 'gradable_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
