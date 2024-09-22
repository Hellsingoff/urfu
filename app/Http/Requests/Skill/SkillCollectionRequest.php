<?php

declare(strict_types=1);

namespace App\Http\Requests\Skill;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SkillCollectionRequest extends FormRequest
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
            'without_pagination' => ['boolean'],
        ];
    }
}
