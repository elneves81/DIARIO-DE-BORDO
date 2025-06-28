@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:linear-gradient(135deg,#4e54c8 0%,#8f94fb 100%);color:#fff;">
            <i class="bi bi-people-fill fs-4"></i>
        </div>
        <h3 class="mb-0 fw-bold" style="color:#4e54c8;">Gerenciamento de Usuários</h3>
    </div>
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    <div class="card shadow border-0 rounded-4" style="background: #f8fafd;">
        <div class="card-body">
            <table class="table table-bordered align-middle text-center shadow-sm rounded-3 overflow-hidden" style="background:#fff;">
                <thead style="background:linear-gradient(90deg,#4e54c8 0%,#8f94fb 100%);color:#fff;">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Cargo</th>
                        <th>Telefone</th>
                        <th>CPF</th>
                        <th>Admin</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="background:{{ $loop->even ? '#f8fafd' : '#fff' }};">
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->cargo }}</td>
                        <td>{{ $user->telefone }}</td>
                        <td>{{ maskCpfBlade($user->cpf) }}</td>
                        <td>
                            <button type="button" class="btn btn-sm toggle-admin-btn {{ $user->is_admin ? 'btn-success' : 'btn-secondary' }}" data-user-id="{{ $user->id }}">
                                <i class="bi bi-shield-lock"></i> {{ $user->is_admin ? 'Sim' : 'Não' }}
                            </button>
                        </td>
                        <td class="d-flex flex-wrap gap-2 justify-content-center">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning d-flex align-items-center gap-1 shadow-sm"><i class="bi bi-pencil-square"></i> Editar</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1 shadow-sm" onclick="return confirm('Excluir este usuário?')"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info d-flex align-items-center gap-1 shadow-sm" onclick="return confirm('Redefinir senha deste usuário?')"><i class="bi bi-arrow-repeat"></i> Resetar Senha</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-admin-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            fetch(`/admin/users/${userId}/toggle-admin`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.classList.toggle('btn-success', data.is_admin);
                    this.classList.toggle('btn-secondary', !data.is_admin);
                    this.innerHTML = `<i class='bi bi-shield-lock'></i> ${data.is_admin ? 'Sim' : 'Não'}`;
                } else {
                    alert('Erro ao alterar status de admin.');
                }
            })
            .catch(() => alert('Erro ao comunicar com o servidor.'));
        });
    });
});
</script>
@endpush
