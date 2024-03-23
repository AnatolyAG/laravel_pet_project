<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Мы можем определить здесь логику авторизации для доступа к этому эндпоинту
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'ttype' => 'required|in:0,1',
            'description' => 'nullable|string|max:255',
        ];
    }
}
