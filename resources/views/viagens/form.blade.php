{{-- filepath: resources/views/viagens/form.blade.php --}}
<div class="row mb-3 justify-content-center flex-wrap fade-in">
    <div class="col-12">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary modern-btn mb-3" tabindex="0" aria-label="Voltar para a tela anterior">Voltar</a>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap fade-in">
    <div class="col-12 col-md-3 mb-2">
        <label for="data" class="form-label fw-semibold">Data</label>
        <input type="date" class="form-control modern-input text-center w-100" id="data" name="data" value="{{ old('data', isset($viagem) ? (is_string($viagem->data) ? $viagem->data : $viagem->data->format('Y-m-d')) : '') }}" required>
        @error('data')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="hora_saida" class="form-label fw-semibold text-primary">Hora Saída</label>
        <div class="input-group">
            <input type="time" class="form-control modern-input text-center w-100" id="hora_saida" name="hora_saida" value="{{ old('hora_saida', isset($viagem) ? $viagem->hora_saida : '') }}" required>
        </div>
        @error('hora_saida')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="km_saida" class="form-label fw-semibold text-primary">KM Saída</label>
        <input type="number" class="form-control modern-input text-center w-100" id="km_saida" name="km_saida" value="{{ old('km_saida', isset($viagem) ? $viagem->km_saida : '') }}" required>
        @error('km_saida')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap fade-in">
    <div class="col-12 col-md-6 mb-2">
        <label for="destino" class="form-label fw-semibold">Destino</label>
        <input type="text" class="form-control modern-input text-center w-100" id="destino" name="destino" placeholder="Ex: Centro, Bairro, Cidade" value="{{ old('destino', isset($viagem) ? $viagem->destino : '') }}" required>
        @error('destino')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap fade-in">
    <div class="col-12 col-md-3 mb-2">
        <label for="hora_chegada" class="form-label fw-semibold text-success">Hora Chegada <small class="text-muted">(opcional)</small></label>
        <div class="input-group">
            <input type="time" class="form-control modern-input text-center w-100" id="hora_chegada" name="hora_chegada" value="{{ old('hora_chegada', isset($viagem) ? $viagem->hora_chegada : '') }}">
        </div>
        @error('hora_chegada')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="km_chegada" class="form-label fw-semibold text-success">KM Chegada</label>
        <input type="number" class="form-control modern-input text-center w-100" id="km_chegada" name="km_chegada" value="{{ old('km_chegada', isset($viagem) ? $viagem->km_chegada : '') }}" @if(!isset($viagem)) @else required @endif>
        @error('km_chegada')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap fade-in">
    <div class="col-12 col-md-6 mb-2">
        <label for="condutor" class="form-label fw-semibold">Condutor</label>
        <input type="text" class="form-control modern-input text-center w-100" id="condutor" name="condutor" value="{{ old('condutor', isset($viagem) ? ($viagem->user ? $viagem->user->name : $viagem->condutor) : (Auth::user() ? Auth::user()->name : '')) }}" required readonly>
        <input type="hidden" name="user_id" value="{{ isset($viagem) && $viagem->user ? $viagem->user->id : (Auth::user() ? Auth::user()->id : '') }}">
        @error('condutor')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap fade-in">
    <div class="col-12 col-md-3 mb-2">
        <label for="num_registro_abastecimento" class="form-label fw-semibold">Nº Reg. Abastecimento</label>
        <input type="text" class="form-control modern-input text-center w-100" id="num_registro_abastecimento" name="num_registro_abastecimento" value="{{ old('num_registro_abastecimento', isset($viagem) ? $viagem->num_registro_abastecimento : '') }}">
        @error('num_registro_abastecimento')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="quantidade_abastecida" class="form-label fw-semibold">Qtd. Abastecida (L)</label>
        <input type="number" step="0.01" class="form-control modern-input text-center w-100" id="quantidade_abastecida" name="quantidade_abastecida" value="{{ old('quantidade_abastecida', isset($viagem) ? $viagem->quantidade_abastecida : '') }}">
        @error('quantidade_abastecida')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap fade-in">
    <div class="col-12 col-md-3 mb-2">
        <label for="tipo_veiculo" class="form-label fw-semibold">Tipo de Veículo</label>
        <input type="text" class="form-control modern-input text-center w-100" id="tipo_veiculo" name="tipo_veiculo" value="{{ old('tipo_veiculo', isset($viagem) ? $viagem->tipo_veiculo : '') }}">
        @error('tipo_veiculo')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="placa" class="form-label fw-semibold">Placa</label>
        <input type="text" class="form-control modern-input text-center w-100" id="placa" name="placa" value="{{ old('placa', isset($viagem) ? $viagem->placa : '') }}">
        @error('placa')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-8 mb-2">
        <label class="form-label">Checklist Pré-Viagem</label>
        <div class="d-flex flex-column flex-md-row flex-wrap gap-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checklist[documentos]" id="check_documentos" value="1" {{ old('checklist.documentos') ? 'checked' : '' }}>
                <label class="form-check-label" for="check_documentos">Documentos do veículo em dia</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checklist[manutencao]" id="check_manutencao" value="1" {{ old('checklist.manutencao') ? 'checked' : '' }}>
                <label class="form-check-label" for="check_manutencao">Manutenção preventiva realizada</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checklist[abastecimento]" id="check_abastecimento" value="1" {{ old('checklist.abastecimento') ? 'checked' : '' }}>
                <label class="form-check-label" for="check_abastecimento">Abastecimento suficiente</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checklist[epc]" id="check_epc" value="1" {{ old('checklist.epc') ? 'checked' : '' }}>
                <label class="form-check-label" for="check_epc">Equipamentos de proteção (EPC) conferidos</label>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-8 mb-2">
        <label for="anexos" class="form-label">Anexos (fotos, recibos, comprovantes)</label>
        <input type="file" class="form-control w-100" id="anexos" name="anexos[]" multiple accept="image/*,application/pdf">
        <small class="text-muted">Você pode anexar imagens ou PDFs. Tamanho máximo por arquivo: 2MB.</small>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function autoFormatHora(input) {
        input.addEventListener('input', function (e) {
            let v = input.value.replace(/[^0-9]/g, '');
            if (v.length > 4) v = v.slice(0,4);
            if (v.length >= 3) {
                input.value = v.slice(0,2) + ':' + v.slice(2,4);
            } else if (v.length >= 1) {
                input.value = v;
            }
        });
    }
    const horaSaida = document.getElementById('hora_saida');
    const horaChegada = document.getElementById('hora_chegada');
    if (horaSaida) autoFormatHora(horaSaida);
    if (horaChegada) autoFormatHora(horaChegada);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataInput = document.getElementById('data');
    if (dataInput) {
        const hoje = new Date().toISOString().split('T')[0];
        dataInput.value = hoje;
        dataInput.setAttribute('max', hoje);
        dataInput.setAttribute('min', hoje);
        dataInput.addEventListener('input', function() {
            if (this.value !== hoje) {
                this.setCustomValidity('Você não pode agendar essa viagem. A data precisa ser de Hoje!!');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
</script>
@endpush
<style>
    .modern-input {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    .modern-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    .form-label.fw-semibold {
        font-weight: 600;
        color: #2c3e50;
    }
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>