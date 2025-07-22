@extends('layouts.app')

@section('content')
    <!-- Overlay de Loading -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2 text-muted">Salvando viagem...</p>
        </div>
    </div>
    <div class="container py-4 fade-in">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="modern-card shadow-lg">
                    <div class="modern-card-header text-center">
                        <i class="bi bi-geo-alt-fill display-5 text-primary mb-2"></i>
                        <h3 class="fw-bold gradient-text mb-0">Nova Viagem</h3>
                        <p class="text-muted mb-0">Preencha os dados abaixo para registrar uma nova viagem</p>
                    </div>
                    <div class="modern-card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger modern-alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success modern-alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif
                        <form id="novaViagemForm" action="{{ route('viagens.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @include('viagens.form')
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('viagens.index') }}" class="btn btn-secondary modern-btn w-100 w-md-auto me-md-2" tabindex="0" aria-label="Cancelar e voltar para listagem">Cancelar</a>
                                <button type="submit" class="btn btn-primary modern-btn w-100 w-md-auto" tabindex="0" aria-label="Salvar nova viagem">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .modern-card {
            background: #fff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.10);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .modern-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 2rem 1rem 1.5rem 1rem;
            border-bottom: none;
        }
        .modern-card-body {
            padding: 2rem 1.5rem;
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .modern-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.95rem;
        }
        .modern-btn:focus {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        .modern-alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }
        .loading-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(255,255,255,0.9);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 768px) {
            .modern-card-body { padding: 1rem; }
            .modern-card-header { padding: 1.5rem 1rem; }
        }
    </style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Loading ao submeter
        const form = document.getElementById('novaViagemForm');
        if (form) {
            form.addEventListener('submit', function() {
                document.getElementById('loadingOverlay').style.display = 'flex';
            });
        }
        // Animação dos botões
        document.querySelectorAll('.modern-btn').forEach(btn => {
            btn.addEventListener('focus', function() { this.style.transform = 'scale(1.02)'; });
            btn.addEventListener('blur', function() { this.style.transform = 'scale(1)'; });
        });
    });
</script>
@endsection