@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Alterar Senha</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('senha.alterar') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="password" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" id="password" name="password" required>
            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirme a Nova Senha</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-success">Alterar Senha</button>
    </form>
</div>
@endsection
