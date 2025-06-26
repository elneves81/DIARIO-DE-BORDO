@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Gerenciamento de Usuários</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Telefone</th>
                <th>Admin</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->cargo }}</td>
                <td>{{ $user->telefone }}</td>
                <td>{{ $user->is_admin ? 'Sim' : 'Não' }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este usuário?')">Excluir</button>
                    </form>
                    <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Redefinir senha deste usuário?')">Resetar Senha</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
