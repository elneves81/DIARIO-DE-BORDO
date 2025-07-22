@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:linear-gradient(135deg,#4e54c8 0%,#8f94fb 100%);color:#fff;">
            <i class="bi bi-person-lines-fill fs-4"></i>
        </div>
        <h3 class="mb-0 fw-bold" style="color:#4e54c8;">Editar Usuário</h3>
    </div>
    <div class="card shadow border-0 rounded-4" style="background: #f8fafd;">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" name="cargo" id="cargo" class="form-control" value="{{ $user->cargo }}">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" value="{{ $user->telefone }}">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" name="cpf" id="cpf" class="form-control" value="{{ $user->cpf }}" maxlength="14" placeholder="000.000.000-00">
                        @error('cpf')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" value="{{ $user->data_nascimento }}">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-1 fw-bold shadow-sm"><i class="bi bi-save"></i> Salvar</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary d-flex align-items-center gap-1 fw-bold"><i class="bi bi-arrow-left"></i> Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Máscara de CPF para o campo de edição
function maskCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    return cpf;
}

document.addEventListener('DOMContentLoaded', function() {
    var cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function() {
            this.value = maskCPF(this.value);
        });
    }
});
</script>
@endsection
