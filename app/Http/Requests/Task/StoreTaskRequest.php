<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Pending,In Progress,Completed',
            'category_id' => 'required|integer|exists:categories,id',
        ];

        // Add validation for author_id only if the authenticated user is an admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            $rules['author_id'] = 'required|integer|exists:users,id';
        }

        return $rules;
    }
}
