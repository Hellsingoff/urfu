<?php

declare(strict_types=1);

namespace App\Http\Requests\VacancyResponse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacancyResponseCommentaryStoreRequest extends FormRequest
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
            'commentary' => ['required', 'string', 'max:255'],
        ];
    }
}
