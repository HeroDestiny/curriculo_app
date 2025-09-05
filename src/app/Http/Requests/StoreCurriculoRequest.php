<?php

namespace App\Http\Requests;

use App\Rules\TelefoneBrasileiroRule;
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normaliza o telefone para o formato correto antes da validação
        if ($this->has('telefone')) {
            $telefone = $this->input('telefone');
            $telefoneNormalizado = $this->normalizarTelefone($telefone);

            $this->merge([
                'telefone' => $telefoneNormalizado,
            ]);
        }
    }

    /**
     * Normaliza o telefone para o formato correto baseado no número de dígitos
     */
    private function normalizarTelefone(string $telefone): string
    {
        // Remove todos os caracteres não numéricos
        $apenasDigitos = preg_replace('/\D/', '', $telefone);

        // Se não tem 10 ou 11 dígitos, retorna como está para falhar na validação
        if (strlen($apenasDigitos) < 10 || strlen($apenasDigitos) > 11) {
            return $telefone;
        }

        $ddd = substr($apenasDigitos, 0, 2);

        if (strlen($apenasDigitos) === 11) {
            // Celular: (XX) XXXXX-XXXX
            $numero = substr($apenasDigitos, 2, 5).'-'.substr($apenasDigitos, 7, 4);

            return "($ddd) $numero";
        } else {
            // Telefone fixo: (XX) XXXX-XXXX
            $numero = substr($apenasDigitos, 2, 4).'-'.substr($apenasDigitos, 6, 4);

            return "($ddd) $numero";
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-ZÀ-ÿ\s\'\-]+$/u',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:curriculos,email',
            ],
            'telefone' => [
                'required',
                'string',
                new TelefoneBrasileiroRule,
            ],
            'cargo_desejado' => [
                'required',
                'string',
                'max:255',
                'min:2',
            ],
            'escolaridade' => [
                'required',
                'in:fundamental_incompleto,fundamental_completo,medio_incompleto,medio_completo,superior_incompleto,superior_completo,pos_graduacao,mestrado,doutorado',
            ],
            'observacoes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'arquivo' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:1024', // 1MB
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
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
            'nome.regex' => 'O nome deve conter apenas letras, acentos, espaços, apostrofos e hífens.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'Este e-mail já foi utilizado para envio de currículo.',

            'telefone.required' => 'O telefone é obrigatório.',

            'cargo_desejado.required' => 'O cargo desejado é obrigatório.',
            'cargo_desejado.min' => 'O cargo desejado deve ter pelo menos 2 caracteres.',
            'cargo_desejado.max' => 'O cargo desejado não pode ter mais de 255 caracteres.',

            'escolaridade.required' => 'A escolaridade é obrigatória.',
            'escolaridade.in' => 'Selecione uma escolaridade válida.',

            'observacoes.max' => 'As observações não podem ter mais de 1000 caracteres.',

            'arquivo.required' => 'O arquivo de currículo é obrigatório.',
            'arquivo.file' => 'Por favor, envie um arquivo válido.',
            'arquivo.mimes' => 'O arquivo deve ser PDF, DOC ou DOCX.',
            'arquivo.max' => 'O arquivo não pode ser maior que 1MB.',
            'arquivo.mimetypes' => 'Tipo de arquivo não permitido. Apenas PDF, DOC e DOCX são aceitos.',
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
