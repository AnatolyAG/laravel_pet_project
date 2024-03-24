<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Можно ли текущему пользователю выполнять этот запрос
    }

    public function rules()
    {
        return [
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|string|min:6',
            'descript' => 'sometimes|string',
            'comment'  => 'sometimes|string',
        ];
    }
}
