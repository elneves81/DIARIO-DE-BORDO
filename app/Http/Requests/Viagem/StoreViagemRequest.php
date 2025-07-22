<?php

namespace App\Http\Requests\Viagem;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreViagemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data' => [
                'required',
                'date',
                function($attribute, $value, $fail) {
                    $hoje = Carbon::today();
                    $dataViagem = Carbon::parse($value);
                    
                    if ($dataViagem->isAfter($hoje)) {
                        $fail('Não é possível agendar viagens futuras. A data deve ser hoje.');
                    }
                    
                    if ($dataViagem->isBefore($hoje->subDays(7))) {
                        $fail('Não é possível registrar viagens com mais de 7 dias retroativos.');
                    }
                }
            ],
            'hora_saida' => [
                'required',
                'date_format:H:i'
            ],
            'km_saida' => [
                'required',
                'integer',
                'min:0',
                'max:999999'
            ],
            'destino' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'condutor' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'hora_chegada' => [
                'nullable',
                'date_format:H:i',
                function($attribute, $value, $fail) {
                    if ($value && $this->hora_saida) {
                        if ($value === $this->hora_saida) {
                            $fail('A hora de chegada não pode ser igual à hora de saída.');
                        }
                    }
                }
            ],
            'km_chegada' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
                function($attribute, $value, $fail) {
                    if ($value && $this->km_saida) {
                        if ($value < $this->km_saida) {
                            $fail('O KM de chegada não pode ser menor que o KM de saída.');
                        }
                        
                        $distancia = $value - $this->km_saida;
                        if ($distancia > 1000) {
                            $fail('Distância muito alta (acima de 1000km). Verifique os dados.');
                        }
                    }
                }
            ],
            'num_registro_abastecimento' => [
                'nullable',
                'string',
                'max:100'
            ],
            'quantidade_abastecida' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999.99'
            ],
            'tipo_veiculo' => [
                'nullable',
                'string',
                'max:100'
            ],
            'placa' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$|^[A-Z]{3}[0-9]{4}$/'
            ],
            'checklist' => [
                'nullable',
                'array'
            ],
            'checklist.*' => [
                'string',
                'max:255'
            ],
            'anexos' => [
                'nullable',
                'array',
                'max:5'
            ],
            'anexos.*' => [
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:2048'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'data.required' => 'A data da viagem é obrigatória.',
            'data.date' => 'A data deve estar em um formato válido.',
            'hora_saida.required' => 'A hora de saída é obrigatória.',
            'hora_saida.date_format' => 'A hora de saída deve estar no formato HH:MM.',
            'km_saida.required' => 'O KM de saída é obrigatório.',
            'km_saida.integer' => 'O KM de saída deve ser um número inteiro.',
            'km_saida.min' => 'O KM de saída não pode ser negativo.',
            'destino.required' => 'O destino é obrigatório.',
            'destino.min' => 'O destino deve ter pelo menos 3 caracteres.',
            'condutor.required' => 'O condutor é obrigatório.',
            'condutor.min' => 'O nome do condutor deve ter pelo menos 3 caracteres.',
            'hora_chegada.date_format' => 'A hora de chegada deve estar no formato HH:MM.',
            'km_chegada.integer' => 'O KM de chegada deve ser um número inteiro.',
            'km_chegada.min' => 'O KM de chegada não pode ser negativo.',
            'placa.regex' => 'A placa deve estar no formato ABC1234 ou ABC1D23.',
            'anexos.max' => 'Máximo de 5 anexos permitidos.',
            'anexos.*.file' => 'Cada anexo deve ser um arquivo válido.',
            'anexos.*.mimes' => 'Anexos devem ser: PDF, JPG, JPEG, PNG, DOC ou DOCX.',
            'anexos.*.max' => 'Cada anexo deve ter no máximo 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Adicionar user_id automaticamente
        $this->merge([
            'user_id' => auth()->id()
        ]);

        // Limpar e formatar placa
        if ($this->placa) {
            $this->merge([
                'placa' => strtoupper(preg_replace('/[^A-Z0-9]/', '', $this->placa))
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'data' => 'data da viagem',
            'hora_saida' => 'hora de saída',
            'km_saida' => 'quilometragem de saída',
            'destino' => 'destino',
            'condutor' => 'condutor',
            'hora_chegada' => 'hora de chegada',
            'km_chegada' => 'quilometragem de chegada',
            'num_registro_abastecimento' => 'número do registro de abastecimento',
            'quantidade_abastecida' => 'quantidade abastecida',
            'tipo_veiculo' => 'tipo de veículo',
            'placa' => 'placa do veículo',
            'anexos' => 'anexos'
        ];
    }
}
