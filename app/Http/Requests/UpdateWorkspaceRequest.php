<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkspaceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:120'],
            'industry' => ['sometimes', 'string', 'max:120'],
            'plan' => ['sometimes', 'in:free,pro,enterprise'],
            'accentColor' => ['sometimes', 'string', 'max:20'],
        ];
    }
}
