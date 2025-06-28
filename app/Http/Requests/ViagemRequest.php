<?php

namespace App\Http\Requests;

use App\Rules\StrongPassword;
use App\Services\ViagemService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ViagemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'data' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subDays(7)->format('Y-m-d')
            ],
            'hora_saida' => 'required|date_format:H:i',
            'hora_chegada' => 'nullable|date_format:H:i|after:hora_saida',
            'km_saida' => 'required|integer|min:0|max:999999',
            'km_chegada' => 'nullable|integer|min:0|max:999999|gte:km_saida',
            'destino' => 'required|string|max:255',
            'condutor' => 'required|string|max:255',
            'tipo_veiculo' => 'nullable|string|max:100',
            'placa' => 'nullable|string|max:10',
            'origem' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string|max:1000',
            'anexos' => 'nullable|array|max:5',
            'anexos.*' => 'file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validações de negócio customizadas
            $viagemService = app(ViagemService::class);
            $businessErrors = $viagemService->validateBusinessRules(
                $this->all(),
                $this->route('viagem') // Para edição
            );

            foreach ($businessErrors as $field => $errors) {
                foreach ($errors as $error) {
                    $validator->errors()->add($field, $error);
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'data.required' => 'A data da viagem é obrigatória.',
            'data.before_or_equal' => 'Não é possível registrar viagens futuras.',
            'data.after_or_equal' => 'Não é possível registrar viagens com mais de 7 dias retroativos.',
            'hora_saida.required' => 'A hora de saída é obrigatória.',
            'hora_saida.date_format' => 'Formato de hora inválido (use HH:MM).',
            'hora_chegada.date_format' => 'Formato de hora inválido (use HH:MM).',
            'hora_chegada.after' => 'A hora de chegada deve ser posterior à de saída.',
            'km_saida.required' => 'O KM de saída é obrigatório.',
            'km_saida.integer' => 'O KM de saída deve ser um número.',
            'km_saida.min' => 'O KM de saída não pode ser negativo.',
            'km_chegada.gte' => 'O KM de chegada não pode ser menor que o de saída.',
            'destino.required' => 'O destino é obrigatório.',
            'condutor.required' => 'O condutor é obrigatório.',
            'anexos.max' => 'Máximo de 5 anexos permitidos.',
            'anexos.*.max' => 'Cada anexo deve ter no máximo 2MB.',
            'anexos.*.mimes' => 'Tipo de arquivo não permitido. Use: jpg, jpeg, png, pdf, doc, docx.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'data' => 'data da viagem',
            'hora_saida' => 'hora de saída',
            'hora_chegada' => 'hora de chegada',
            'km_saida' => 'quilometragem de saída',
            'km_chegada' => 'quilometragem de chegada',
            'destino' => 'destino',
            'condutor' => 'condutor',
            'tipo_veiculo' => 'tipo de veículo',
            'placa' => 'placa do veículo',
            'origem' => 'origem',
            'observacoes' => 'observações',
            'anexos' => 'anexos'
        ];
    }
}
