<?php

use App\Enum\VacancyStatus;
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
        Schema::create('vacancies', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable(false);
            $table->foreignId('organization_id')->nullable(false);
            $table->foreignId('category_id')->nullable(false);
            $table->string('status', 10)->nullable(false)->default(VacancyStatus::Open);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
