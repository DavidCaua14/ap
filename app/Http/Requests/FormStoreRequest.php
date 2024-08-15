<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'descricao' => 'required|string|min:10|max:255', 
            'data_encontrada' => 'required|date|before_or_equal:today', 
            'hora_encontrada' => 'required|date_format:H:i', 
        ];
    }
}
