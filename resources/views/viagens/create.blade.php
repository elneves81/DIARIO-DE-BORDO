<!-- filepath: resources/views/viagens/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white text-center">
                    <h4 class="mb-0">Nova Viagem</h4>
                </div>
                <div class="card-body bg-light">
                    <form action="{{ route('viagens.store') }}" method="POST">
                        @csrf
                        @include('viagens.form')
                        <div class="d-flex justify-content-center gap-3 mt-4">
                            <a href="{{ route('viagens.index') }}" class="btn btn-secondary px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection