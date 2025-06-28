@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>🔧 Teste de Conectividade CSRF</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6>📱 Informações de Conexão:</h6>
                        <ul class="mb-0">
                            <li><strong>URL Atual:</strong> {{ request()->url() }}</li>
                            <li><strong>IP do Cliente:</strong> {{ request()->ip() }}</li>
                            <li><strong>User Agent:</strong> {{ request()->userAgent() }}</li>
                            <li><strong>CSRF Token:</strong> <code>{{ csrf_token() }}</code></li>
                            <li><strong>Session ID:</strong> <code>{{ session()->getId() }}</code></li>
                            <li><strong>Referer:</strong> {{ request()->header('referer', 'N/A') }}</li>
                        </ul>
                    </div>

                    <!-- Teste de Formulário -->
                    <form action="{{ route('test.csrf') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="teste" class="form-label">Teste de Envio:</label>
                            <input type="text" class="form-control" id="teste" name="teste" value="Teste CSRF OK" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Testar CSRF
                        </button>
                    </form>

                    @if(session('test_result'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> <strong>Sucesso!</strong> {{ session('test_result') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Erro:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Links para teste -->
                    <div class="mt-4">
                        <h6>🔗 Links de Teste:</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Testar Login
                            </a>
                            <a href="{{ route('viagens.create') }}" class="btn btn-outline-success">
                                <i class="bi bi-plus-circle"></i> Testar Nova Viagem
                            </a>
                            <a href="{{ route('viagens.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-list"></i> Testar Lista de Viagens
                            </a>
                        </div>
                    </div>

                    <!-- JavaScript Test -->
                    <div class="mt-4">
                        <h6>⚡ Teste AJAX:</h6>
                        <button type="button" class="btn btn-warning" onclick="testAjax()">
                            <i class="bi bi-lightning"></i> Testar AJAX
                        </button>
                        <div id="ajax-result" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testAjax() {
    const resultDiv = document.getElementById('ajax-result');
    resultDiv.innerHTML = '<div class="alert alert-info">Testando AJAX...</div>';

    fetch('{{ route('test.ajax') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            test: 'AJAX CSRF Test'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> AJAX funcionando: ' + data.message + '</div>';
        } else {
            resultDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Erro AJAX: ' + data.message + '</div>';
        }
    })
    .catch(error => {
        resultDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Erro: ' + error.message + '</div>';
    });
}
</script>
@endsection
