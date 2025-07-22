<!-- filepath: resources/views/viagens/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-header">
            <h5 class="mb-0 text-center">Detalhes da Viagem</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-3" tabindex="0" aria-label="Voltar para a tela anterior">Voltar</a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Data:</div>
                <div class="col-7 col-md-9">{{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Saída:</div>
                <div class="col-7 col-md-9">
                    {{ $viagem->hora_saida ? \Carbon\Carbon::createFromFormat('H:i:s', $viagem->hora_saida)->format('h:i A') : '-' }} - {{ $viagem->km_saida ?? '-' }} KM
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Destino:</div>
                <div class="col-7 col-md-9">{{ $viagem->destino }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Chegada:</div>
                <div class="col-7 col-md-9">
                    {{ $viagem->hora_chegada ? \Carbon\Carbon::createFromFormat('H:i:s', $viagem->hora_chegada)->format('h:i A') : '-' }} - {{ $viagem->km_chegada ?? '-' }} KM
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Condutor:</div>
                <div class="col-7 col-md-9">
                    {{ $viagem->condutor }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Nº Reg. Abastecimento:</div>
                <div class="col-7 col-md-9">{{ $viagem->num_registro_abastecimento ?? 'N/A' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Qtd. Abastecida:</div>
                <div class="col-7 col-md-9">{{ $viagem->quantidade_abastecida ? $viagem->quantidade_abastecida . ' L' : 'N/A' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Checklist Pré-Viagem:</div>
                <div class="col-7 col-md-9">
                    @if($viagem->checklist)
                        <ul class="mb-0 ps-3">
                            <li><span class="{{ isset($viagem->checklist['documentos']) ? 'text-success' : 'text-danger' }}">Documentos do veículo em dia</span></li>
                            <li><span class="{{ isset($viagem->checklist['manutencao']) ? 'text-success' : 'text-danger' }}">Manutenção preventiva realizada</span></li>
                            <li><span class="{{ isset($viagem->checklist['abastecimento']) ? 'text-success' : 'text-danger' }}">Abastecimento suficiente</span></li>
                            <li><span class="{{ isset($viagem->checklist['epc']) ? 'text-success' : 'text-danger' }}">Equipamentos de proteção (EPC) conferidos</span></li>
                        </ul>
                    @else
                        <span class="text-muted">Não preenchido</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-5 col-md-3 fw-bold">Anexos:</div>
                <div class="col-7 col-md-9">
                    @if($viagem->anexos && $viagem->anexos->count())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($viagem->anexos as $anexo)
                                <div>
                                    <a href="{{ asset('storage/' . $anexo->caminho) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-paperclip"></i> {{ $anexo->nome }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted">Nenhum anexo</span>
                    @endif
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('viagens.index') }}" class="btn btn-primary w-100 w-md-auto">Voltar</a>
            </div>
        </div>
    </div>
@endsection

@php
    use Illuminate\Support\Str;
@endphp

@section('scripts')
    @parent
    {{-- Google Maps removido --}}
@endsection