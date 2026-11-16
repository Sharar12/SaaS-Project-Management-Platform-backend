<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteMemberRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'workspaceId' => ['required', 'string', 'exists:workspaces,id'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'role' => ['required', 'in:owner,admin,member,viewer'],
            'title' => ['nullable', 'string', 'max:120'],
        ];
    }
}
