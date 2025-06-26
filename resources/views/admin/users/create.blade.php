@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Cadastrar Novo Usuário</h3>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-2">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4 mb-2">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') }}">
            </div>
            <div class="col-md-4 mb-2">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" class="form-control" id="cargo" name="cargo" value="{{ old('cargo') }}">
            </div>
            <div class="col-md-4 mb-2">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-2">
                <label for="password_confirmation" class="form-label">Confirme a Senha</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="is_admin" name="is_admin" {{ old('is_admin') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_admin">Usuário Administrador</label>
        </div>
        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
