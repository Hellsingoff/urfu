<?php

use App\Enum\VacancyResponseStatus;
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
        Schema::create('vacancy_responses', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable(false);
            $table->foreignId('vacancy_id')->nullable(false);
            $table->foreignId('resume_id')->nullable(false);
            $table->boolean('must_notify_vacancy_owner')->default(false);
            $table->boolean('must_notify_response_owner')->default(false);
            $table->string('status', 10)->nullable(false)->default(VacancyResponseStatus::New);
            $table->timestamps();

            $table->unique(['user_id', 'vacancy_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_responses');
    }
};
