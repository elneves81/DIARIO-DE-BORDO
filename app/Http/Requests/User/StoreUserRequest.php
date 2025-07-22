<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'telefone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\(\d{2}\)\s\d{4,5}-\d{4}$|^\d{10,11}$/'
            ],
            'cargo' => [
                'nullable',
                'string',
                'max:100',
                'min:2'
            ],
            'data_nascimento' => [
                'nullable',
                'date',
                'before:today',
                'after:1900-01-01'
            ],
            'cpf' => [
                'nullable',
                'string',
                'size:14',
                'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
                'unique:users,cpf',
                function($attribute, $value, $fail) {
                    if ($value && !$this->validarCPF($value)) {
                        $fail('O CPF informado não é válido.');
                    }
                }
            ],
            'is_admin' => [
                'nullable',
                'boolean'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.regex' => 'O nome deve conter apenas letras e espaços.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está sendo usado por outro usuário.',
            'telefone.regex' => 'O telefone deve estar no formato (11) 99999-9999.',
            'cargo.min' => 'O cargo deve ter pelo menos 2 caracteres.',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'data_nascimento.after' => 'A data de nascimento deve ser posterior a 1900.',
            'cpf.size' => 'O CPF deve ter 14 caracteres (formato: 000.000.000-00).',
            'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',
            'cpf.unique' => 'Este CPF já está sendo usado por outro usuário.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Formatar telefone
        if ($this->telefone) {
            $telefone = preg_replace('/[^0-9]/', '', $this->telefone);
            if (strlen($telefone) === 10) {
                $telefone = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6);
            } elseif (strlen($telefone) === 11) {
                $telefone = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7);
            }
            $this->merge(['telefone' => $telefone]);
        }

        // Formatar CPF
        if ($this->cpf) {
            $cpf = preg_replace('/[^0-9]/', '', $this->cpf);
            if (strlen($cpf) === 11) {
                $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
                $this->merge(['cpf' => $cpf]);
            }
        }

        // Converter is_admin para boolean
        $this->merge([
            'is_admin' => $this->boolean('is_admin')
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'email',
            'telefone' => 'telefone',
            'cargo' => 'cargo',
            'data_nascimento' => 'data de nascimento',
            'cpf' => 'CPF',
            'is_admin' => 'administrador'
        ];
    }

    /**
     * Valida CPF usando algoritmo oficial
     */
    private function validarCPF(string $cpf): bool
    {
        // Remove formatação
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            return false;
        }
        
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Calcula primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Calcula segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Verifica se os dígitos calculados conferem
        return ($cpf[9] == $dv1 && $cpf[10] == $dv2);
    }
}
