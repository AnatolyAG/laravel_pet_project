<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowUserRequest  extends FormRequest
{
    public function authorize()
    {
        return true; // Можно ли текущему пользователю выполнять этот запрос
    }

    public function rules()
    {
        return [
            'id' => 'required|int|min:1',
        ];
    }
}
