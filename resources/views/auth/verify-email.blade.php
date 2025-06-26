<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="fw-bold mb-3" style="color:#0d6efd;">Confirme seu e-mail para acessar o sistema</h2>
        <p class="text-muted">Enviamos um link de confirmação para <b>seu e-mail cadastrado</b>.<br>
        Por favor, acesse sua caixa de entrada e clique no link para ativar sua conta.<br>
        <span style="color:#198754;">Não recebeu?</span> Verifique o spam ou solicite o reenvio abaixo.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success text-center">
            Um novo link de verificação foi enviado para seu e-mail!
        </div>
    @endif

    <div class="mt-4 d-flex justify-content-center gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Reenviar e-mail de verificação</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-danger">Sair</button>
        </form>
    </div>
    <div class="mt-4 text-center text-muted" style="font-size:0.95em;">
        Se precisar de ajuda, entre em contato com o administrador do sistema.
    </div>
</x-guest-layout>
