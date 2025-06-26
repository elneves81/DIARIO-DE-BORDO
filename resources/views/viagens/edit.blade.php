<!-- filepath: resources/views/viagens/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar Viagem</h5>
        </div>
        <div class="card-body">
            @if(isset($viagem) && $viagem)
                <form action="{{ route('viagens.update', $viagem->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('viagens.form')
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('viagens.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </form>
            @else
                <div class="alert alert-danger">Viagem não encontrada ou parâmetro ausente.</div>
            @endif
        </div>
    </div>
@endsection