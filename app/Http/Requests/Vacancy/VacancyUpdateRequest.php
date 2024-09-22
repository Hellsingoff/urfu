<?php

declare(strict_types=1);

namespace App\Http\Requests\Vacancy;

use App\Enum\Language;
use App\Enum\VacancyStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacancyUpdateRequest extends FormRequest
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
        $rules = [
            'languages' => ['required', 'array', 'min:1'],
            'languages.*' => [Rule::enum(Language::class)],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'skills' => ['array'],
            'skills.*' => ['integer', 'exists:skills,id'],
            'status' => ['required', Rule::enum(VacancyStatus::class)],
        ];
        if (is_array($languages = $this->get('languages'))) {
            foreach ($languages as $language) {
                $rules["name.$language"] = ['required', 'string', 'min:1'];
                $rules["description.$language"] = ['required', 'string', 'min:10'];
            }
        }

        return $rules;
    }
}
