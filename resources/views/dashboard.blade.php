@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h4>Bem-vindo, {{ Auth::user()->name }}!</h4>
        </div>
    </div>
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0 bg-transparent">
                <div class="card-body">
                    <h5 class="card-title">Nova Viagem</h5>
                    <p class="card-text">Cadastre uma nova viagem rapidamente.</p>
                    <a href="{{ route('viagens.create') }}" class="btn btn-dark w-100">Cadastrar Viagem</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0 bg-transparent">
                <div class="card-body">
                    <h5 class="card-title">Minhas Viagens</h5>
                    <p class="card-text">Veja e gerencie todas as viagens cadastradas.</p>
                    <a href="{{ route('viagens.index') }}" class="btn btn-dark w-100">Ver Viagens</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0 bg-transparent">
                <div class="card-body">
                    <h5 class="card-title">Meu Perfil</h5>
                    <p class="card-text">Atualize seus dados de usuário e senha.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-dark w-100">Editar Perfil</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0 bg-transparent">
                <div class="card-body">
                    <h5 class="card-title">Relatórios</h5>
                    <p class="card-text">Exporte relatórios de viagens em PDF ou Excel.</p>
                    <a href="{{ route('relatorios.index') }}" class="btn btn-primary w-100">Acessar Relatórios</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0 bg-transparent">
                <div class="card-body">
                    <h5 class="card-title">Ajuda</h5>
                    <p class="card-text">Precisa de suporte? Entre em contato com o administrador.</p>
                    <!-- Botão para abrir o modal de contato -->
                    <button type="button" class="btn btn-dark w-100" data-bs-toggle="modal" data-bs-target="#contatoModal">Fale Conosco</button>

                    <!-- Modal de Contato -->
                    <div class="modal fade" id="contatoModal" tabindex="-1" aria-labelledby="contatoModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="contatoModalLabel">Fale Conosco</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                          </div>
                          <form action="{{ route('sugestoes.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                              <input type="hidden" name="tipo" value="contato">
                              <div class="mb-3">
                                <label for="mensagem_contato" class="form-label">Mensagem</label>
                                <textarea name="mensagem" id="mensagem_contato" class="form-control" rows="4" placeholder="Descreva sua dúvida, problema ou sugestão..." required></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              <button type="submit" class="btn btn-dark">Enviar</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 text-center shadow border-0 bg-transparent">
                <div class="card-body">
                    <h5 class="card-title">Configurações</h5>
                    <p class="card-text">Personalize preferências do sistema e notificações.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100">Editar Perfil e Preferências</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 g-md-4">
        <div class="col-12 col-md-6">
            <div class="card h-100 text-center shadow border-0 bg-transparent mb-3 mb-md-0">
                <div class="card-body">
                    <h5 class="card-title">Resumo Rápido</h5>
                    @php
                        $totalViagens = \App\Models\Viagem::where('user_id', Auth::id())->count();
                        $ultimaViagem = \App\Models\Viagem::where('user_id', Auth::id())->orderByDesc('data')->orderByDesc('created_at')->first();
                    @endphp
                    <ul class="list-group mb-3 text-start">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total de viagens cadastradas:</span>
                            <span class="badge bg-dark rounded-pill">{{ $totalViagens }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Última viagem:</strong><br>
                            @if($ultimaViagem)
                                Data: {{ $ultimaViagem->data instanceof \Carbon\Carbon ? $ultimaViagem->data->format('d/m/Y') : $ultimaViagem->data }}<br>
                                Destino: {{ $ultimaViagem->destino }}<br>
                                KM Saída: {{ $ultimaViagem->km_saida }}<br>
                                KM Chegada: {{ $ultimaViagem->km_chegada }}
                                <a href="{{ route('viagens.edit', $ultimaViagem->id) }}" class="btn btn-sm btn-warning ms-2 mt-2 mt-md-0">Editar</a>
                            @else
                                Nenhuma viagem cadastrada ainda.
                            @endif
                        </li>
                    </ul>
                    <a href="{{ route('viagens.index') }}" class="btn btn-outline-dark w-100">Ver Detalhes</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card h-100 text-center shadow border-0 bg-transparent">
                <div class="card-body">
                    <h5 class="card-title">Sugestões</h5>
                    <p class="card-text">Envie ideias para melhorar o sistema.</p>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('sugestoes.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <textarea name="mensagem" class="form-control" rows="3" placeholder="Digite sua sugestão..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Enviar Sugestão</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
