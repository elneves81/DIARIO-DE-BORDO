@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Editar Usuário</h3>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label>Cargo</label>
            <input type="text" name="cargo" class="form-control" value="{{ $user->cargo }}">
        </div>
        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" value="{{ $user->telefone }}">
        </div>
        <div class="mb-3">
            <label>Data de Nascimento</label>
            <input type="date" name="data_nascimento" class="form-control" value="{{ $user->data_nascimento }}">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
