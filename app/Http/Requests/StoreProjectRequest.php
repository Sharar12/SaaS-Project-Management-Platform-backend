<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'workspaceId' => ['required', 'string', 'exists:workspaces,id'],
            'name' => ['required', 'string', 'max:160'],
            'key' => ['required', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,planning,on_hold,completed'],
            'color' => ['required', 'string', 'max:20'],
            'startDate' => ['required', 'date'],
            'dueDate' => ['required', 'date'],
            'leadId' => ['nullable', 'string', 'exists:team_members,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:40'],
        ];
    }
}
