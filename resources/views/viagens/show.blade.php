<!-- filepath: resources/views/viagens/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detalhes da Viagem</h5>
        </div>
        <div class="card-body">
            {{-- Remover debug após validação --}}
            {{-- <pre class="bg-light p-2 mb-3 border">@php var_dump($viagem->toArray()) @endphp</pre> --}}
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Data:</div>
                <div class="col-md-9">{{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Saída:</div>
                <div class="col-md-9">
                    {{ $viagem->hora_saida ? \Carbon\Carbon::createFromFormat('H:i:s', $viagem->hora_saida)->format('H:i') : '-' }} - {{ $viagem->km_saida ?? '-' }} KM
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Destino:</div>
                <div class="col-md-9">{{ $viagem->destino }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Chegada:</div>
                <div class="col-md-9">
                    {{ $viagem->hora_chegada ? \Carbon\Carbon::createFromFormat('H:i:s', $viagem->hora_chegada)->format('H:i') : '-' }} - {{ $viagem->km_chegada ?? '-' }} KM
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Condutor:</div>
                <div class="col-md-9">
                    {{ $viagem->condutor }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nº Reg. Abastecimento:</div>
                <div class="col-md-9">{{ $viagem->num_registro_abastecimento ?? 'N/A' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Qtd. Abastecida:</div>
                <div class="col-md-9">{{ $viagem->quantidade_abastecida ? $viagem->quantidade_abastecida . ' L' : 'N/A' }}</div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('viagens.index') }}" class="btn btn-primary">Voltar</a>
            </div>
        </div>
    </div>
@endsection