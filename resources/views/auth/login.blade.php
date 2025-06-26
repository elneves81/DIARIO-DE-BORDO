<x-guest-layout>
    <style>
        .login-modern h3 {
            font-family: 'Montserrat', Arial, sans-serif;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 0.2em;
        }
        .login-modern .form-label {
            font-weight: 600;
            color: #222;
        }
        .login-modern .form-control {
            border-radius: 12px;
            font-size: 1.1em;
            padding: 0.7em 1em;
        }
        .login-modern .btn-primary {
            background: linear-gradient(90deg, #0d6efd 60%, #198754 100%);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1em;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 2px 8px rgba(13,110,253,0.08);
        }
        .login-modern .btn-primary:hover {
            background: linear-gradient(90deg, #198754 0%, #0d6efd 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .login-modern .link-primary, .login-modern .link-secondary {
            font-weight: 500;
        }
        @media (max-width: 600px) {
            .login-modern { padding: 1.2rem 0.5rem; }
        }
    </style>
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center" style="background: linear-gradient(135deg, #f8fafc 60%, #e9ecef 100%);">
        <form class="login-modern w-100" style="background: #fff; box-shadow: 0 2px 16px rgba(0,0,0,0.08); border-radius: 18px; max-width: 370px; padding: 2.2rem 1.5rem; margin: 0 auto;" method="POST" action="{{ route('login') }}">
            <div class="text-center mb-4">
                <h3>Diário de Bordo</h3>
                <p class="text-muted mb-0">Acesse sua conta</p>
            </div>
            <x-auth-session-status class="mb-3" :status="session('status')" />
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input id="email" type="email" name="email" class="form-control text-center @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input id="password" type="password" name="password" class="form-control text-center @error('password') is-invalid @enderror" required autocomplete="current-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-3">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">Lembrar-me</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="link-primary">Criar conta</a>
                @endif
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link-secondary">Esqueceu a senha?</a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Entrar</button>
        </form>
    </div>
    <div class="text-center text-muted mt-4" style="font-size: 0.95em;">
        By DITIS- ELN- Todos os Direitos reservados
    </div>
</x-guest-layout>
