@extends('layouts.app')

@section('content')
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-header">
            <h5 class="mb-0 text-center">Nova Viagem</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('viagens.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('viagens.form')
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('viagens.index') }}" class="btn btn-secondary w-100 w-md-auto me-md-2" tabindex="0" aria-label="Cancelar e voltar para listagem">Cancelar</a>
                    <button type="submit" class="btn btn-primary w-100 w-md-auto" tabindex="0" aria-label="Salvar nova viagem">Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection