<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurriculoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permitir acesso público para envio de currículos
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20|min:8',
            'cargo_desejado' => 'required|string|max:255|min:2',
            'escolaridade' => 'required|in:fundamental_incompleto,fundamental_completo,medio_incompleto,medio_completo,superior_incompleto,superior_completo,pos_graduacao,mestrado,doutorado',
            'observacoes' => 'nullable|string|max:1000',
            'arquivo' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            
            'telefone.required' => 'O telefone é obrigatório.',
            'telefone.min' => 'O telefone deve ter pelo menos 8 caracteres.',
            'telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            
            'cargo_desejado.required' => 'O cargo desejado é obrigatório.',
            'cargo_desejado.min' => 'O cargo desejado deve ter pelo menos 2 caracteres.',
            'cargo_desejado.max' => 'O cargo desejado não pode ter mais de 255 caracteres.',
            
            'escolaridade.required' => 'A escolaridade é obrigatória.',
            'escolaridade.in' => 'Selecione uma escolaridade válida.',
            
            'observacoes.max' => 'As observações não podem ter mais de 1000 caracteres.',
            
            'arquivo.required' => 'O arquivo de currículo é obrigatório.',
            'arquivo.file' => 'Por favor, envie um arquivo válido.',
            'arquivo.mimes' => 'O arquivo deve ser PDF, DOC ou DOCX.',
            'arquivo.max' => 'O arquivo não pode ser maior que 5MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nome' => 'nome',
            'email' => 'e-mail',
            'telefone' => 'telefone',
            'cargo_desejado' => 'cargo desejado',
            'escolaridade' => 'escolaridade',
            'observacoes' => 'observações',
            'arquivo' => 'arquivo',
        ];
    }
}
