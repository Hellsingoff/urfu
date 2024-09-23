<?php

declare(strict_types=1);

namespace App\Http\Requests\Vacancy;

use App\Enum\VacancyStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacancyCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'status' => [Rule::enum(VacancyStatus::class)],
            'category_id' => ['integer', 'exists:categories,id'],
            'organization_id' => ['integer', 'exists:organizations,id'],
            'skills' => ['array'],
            'skills.*' => ['integer', 'exists:skills,id'],
            'text' => ['string', 'min:1'],
        ];
    }
}
