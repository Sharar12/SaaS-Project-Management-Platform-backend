<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'authorId' => ['required', 'string', 'exists:team_members,id'],
            'content' => ['required', 'string', 'max:2000'],
        ];
    }
}
