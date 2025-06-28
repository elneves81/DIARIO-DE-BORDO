<x-guest-layout>
<div class="w-full sm:max-w-md mx-auto mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
    <h2 class="mb-4 text-center">Criar Conta</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha</label>
            <input id="password" type="password" class="form-control" name="password" required>
            @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirme a Senha</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
        </div>
        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input id="cpf" type="text" class="form-control" name="cpf" value="{{ old('cpf') }}" maxlength="14" placeholder="000.000.000-00" required>
            @error('cpf') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ route('login') }}">Já tem conta? Entrar</a>
        </div>
    </form>
</div>
@section('scripts')
<script>
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
</x-guest-layout>
