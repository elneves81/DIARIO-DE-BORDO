{{-- filepath: resources/views/viagens/form.blade.php --}}
<div class="row mb-3 justify-content-center">
    <div class="col-md-3 mb-2">
        <label for="data" class="form-label">Data</label>
        <input type="date" class="form-control text-center" id="data" name="data" value="{{ old('data', isset($viagem) ? (is_string($viagem->data) ? $viagem->data : $viagem->data->format('Y-m-d')) : '') }}" required>
        @error('data')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-2">
        <label for="hora_saida" class="form-label"><span style="color:#0d6efd;"><b>Hora Saída</b></span></label>
        <div class="input-group">
            <input type="text" class="form-control text-center" id="hora_saida" name="hora_saida" placeholder="hh:mm" value="{{ old('hora_saida', isset($viagem) ? (strlen($viagem->hora_saida) > 5 ? substr($viagem->hora_saida, 0, 5) : $viagem->hora_saida) : '') }}" required maxlength="5" pattern="\d{2}:\d{2}">
            <span class="input-group-text">:</span>
        </div>
        @error('hora_saida')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-2">
        <label for="km_saida" class="form-label"><span style="color:#0d6efd;"><b>KM Saída</b></span></label>
        <input type="number" class="form-control text-center" id="km_saida" name="km_saida" value="{{ old('km_saida', isset($viagem) ? $viagem->km_saida : '') }}" required>
        @error('km_saida')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-md-6 mb-2">
        <label for="destino" class="form-label">Destino</label>
        <input type="text" class="form-control text-center" id="destino" name="destino" placeholder="Ex: Centro, Bairro, Cidade" value="{{ old('destino', isset($viagem) ? $viagem->destino : '') }}" required>
        @error('destino')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-md-3 mb-2">
        <label for="hora_chegada" class="form-label"><span style="color:#198754;"><b>Hora Chegada</b></span></label>
        <div class="input-group">
            <input type="text" class="form-control text-center" id="hora_chegada" name="hora_chegada" placeholder="hh:mm" value="{{ old('hora_chegada', isset($viagem) ? (strlen($viagem->hora_chegada) > 5 ? substr($viagem->hora_chegada, 0, 5) : $viagem->hora_chegada) : '') }}" @if(!isset($viagem)) maxlength="5" pattern="\d{2}:\d{2}" @else required maxlength="5" pattern="\d{2}:\d{2}" @endif>
            <span class="input-group-text">:</span>
        </div>
        @error('hora_chegada')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-2">
        <label for="km_chegada" class="form-label"><span style="color:#198754;"><b>KM Chegada</b></span></label>
        <input type="number" class="form-control text-center" id="km_chegada" name="km_chegada" value="{{ old('km_chegada', isset($viagem) ? $viagem->km_chegada : '') }}" @if(!isset($viagem)) @else required @endif>
        @error('km_chegada')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-md-6 mb-2">
        <label for="condutor" class="form-label">Condutor</label>
        <input type="text" class="form-control text-center" id="condutor" name="condutor" value="{{ old('condutor', isset($viagem) ? ($viagem->user ? $viagem->user->name : $viagem->condutor) : (Auth::user() ? Auth::user()->name : '')) }}" required readonly>
        <input type="hidden" name="user_id" value="{{ isset($viagem) && $viagem->user ? $viagem->user->id : (Auth::user() ? Auth::user()->id : '') }}">
        @error('condutor')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-md-3 mb-2">
        <label for="num_registro_abastecimento" class="form-label">Nº Reg. Abastecimento</label>
        <input type="text" class="form-control text-center" id="num_registro_abastecimento" name="num_registro_abastecimento" value="{{ old('num_registro_abastecimento', isset($viagem) ? $viagem->num_registro_abastecimento : '') }}">
        @error('num_registro_abastecimento')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-2">
        <label for="quantidade_abastecida" class="form-label">Qtd. Abastecida (L)</label>
        <input type="number" step="0.01" class="form-control text-center" id="quantidade_abastecida" name="quantidade_abastecida" value="{{ old('quantidade_abastecida', isset($viagem) ? $viagem->quantidade_abastecida : '') }}">
        @error('quantidade_abastecida')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-md-3 mb-2">
        <label for="tipo_veiculo" class="form-label">Tipo de Veículo</label>
        <input type="text" class="form-control text-center" id="tipo_veiculo" name="tipo_veiculo" value="{{ old('tipo_veiculo', isset($viagem) ? $viagem->tipo_veiculo : '') }}">
        @error('tipo_veiculo')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-2">
        <label for="placa" class="form-label">Placa</label>
        <input type="text" class="form-control text-center" id="placa" name="placa" value="{{ old('placa', isset($viagem) ? $viagem->placa : '') }}">
        @error('placa')<div class="text-danger small">{{ $message }}</div>@enderror
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
@endpush