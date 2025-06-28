@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:linear-gradient(135deg,#4e54c8 0%,#8f94fb 100%);color:#fff;">
            <i class="bi bi-person-plus-fill fs-4"></i>
        </div>
        <h3 class="mb-0 fw-bold" style="color:#4e54c8;">Cadastrar Novo Usuário</h3>
    </div>
    <div class="card shadow border-0 rounded-4" style="background: #f8fafd;">
        <div class="card-body">
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
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') }}" maxlength="14" placeholder="000.000.000-00">
                        @error('cpf')<div class="text-danger small">{{ $message }}</div>@enderror
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
                    <label class="form-check-label fw-semibold" for="is_admin"><i class="bi bi-shield-lock me-1"></i> Usuário Administrador</label>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-1 fw-bold"><i class="bi bi-person-plus"></i> Cadastrar</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary d-flex align-items-center gap-1 fw-bold"><i class="bi bi-arrow-left"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Máscara de CPF para os campos de cadastro e edição
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
