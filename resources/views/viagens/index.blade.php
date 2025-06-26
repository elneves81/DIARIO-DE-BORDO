<!-- filepath: resources/views/viagens/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header d-flex flex-column align-items-center justify-content-center" style="background: #111; color: #fff;">
                    <div class="w-100 d-flex flex-column align-items-center">
                        <h4 class="mb-3 text-center w-100" style="color: #fff;">Registros de Viagens</h4>
                        <a href="{{ route('viagens.create') }}" class="btn fw-bold shadow-sm mx-auto" style="background: #111; color: #fff; min-width: 180px;">Nova Viagem</a>
                    </div>
                </div>
                <div class="card-body bg-light">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-center" style="font-size:1.1rem;">
                            <thead class="table-dark">
                                <tr>
                                    <th>Data</th>
                                    <th>Saída (Hora/KM)</th>
                                    <th>Destino</th>
                                    <th>Chegada (Hora/KM)</th>
                                    <th>Condutor</th>
                                    <th style="min-width: 160px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($viagens as $viagem)
                                <tr>
                                    <td class="fw-semibold">{{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</td>
                                    <td>{{ $viagem->hora_saida }}<br><span class="text-muted">{{ $viagem->km_saida }} KM</span></td>
                                    <td class="fw-semibold">{{ $viagem->destino }}</td>
                                    <td>{{ $viagem->hora_chegada }}<br><span class="text-muted">{{ $viagem->km_chegada }} KM</span></td>
                                    <td>{{ $viagem->condutor }}</td>
                                    <td>
                                        <a href="{{ route('viagens.show', $viagem->id) }}" class="btn btn-sm btn-info mb-1">Ver</a>
                                        <a href="{{ route('viagens.edit', $viagem->id) }}" class="btn btn-sm btn-warning mb-1">Editar</a>
                                        @if(auth()->check() && auth()->user()->isAdmin())
                                        <form action="{{ route('viagens.destroy', $viagem->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Tem certeza?')">Excluir</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Nenhuma viagem cadastrada.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection