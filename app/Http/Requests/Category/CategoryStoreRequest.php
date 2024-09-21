<?php

declare(strict_types=1);

namespace App\Http\Requests\Category;

use App\Enum\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryStoreRequest extends FormRequest
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
            'languages.*' => ['required', Rule::enum(Language::class)],
        ];
        if (is_array($languages = $this->get('languages'))) {
            foreach ($languages as $language) {
                $rules["name.$language"] = ['required', 'string', 'min:1'];
            }
        }

        return $rules;
    }
}
