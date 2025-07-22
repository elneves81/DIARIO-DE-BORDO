<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container-fluid">
        <!-- Logo e Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="/img/logoD.png" alt="Logo" height="32" class="me-2" style="filter: brightness(0) invert(1);">
            <span class="fw-bold d-none d-sm-inline">Diário de Bordo</span>
        </a>

        <!-- Botão Toggle para Mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Menu de Navegação -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>
                        <span class="d-lg-inline">Dashboard</span>
                    </a>
                </li>

                <!-- Viagens -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('viagens.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-car-front me-1"></i>Viagens
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('viagens.create') }}">
                            <i class="bi bi-plus-circle me-2"></i>Nova Viagem</a></li>
                        <li><a class="dropdown-item" href="{{ route('viagens.index') }}">
                            <i class="bi bi-list-ul me-2"></i>Minhas Viagens</a></li>
                    </ul>
                </li>

                <!-- Relatórios -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}" 
                       href="{{ route('relatorios.index') }}">
                        <i class="bi bi-graph-up me-1"></i>
                        <span class="d-lg-inline">Relatórios</span>
                    </a>
                </li>

                <!-- Admin Menu -->
                @if(Auth::check() && Auth::user()->is_admin)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-warning {{ request()->routeIs('admin.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-shield-check me-1"></i>Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-2"></i>Usuários</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.users.create') }}">
                            <i class="bi bi-person-plus me-2"></i>Novo Usuário</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.sugestoes.index') }}">
                            <i class="bi bi-chat-dots me-2"></i>Mensagens
                            @php
                                $totalMensagens = \App\Models\Sugestao::count();
                                $totalContatos = \App\Models\Sugestao::where('tipo','contato')->count();
                            @endphp
                            @if($totalContatos > 0)
                                <span class="badge bg-danger ms-1">{{ $totalContatos }}</span>
                            @endif
                        </a></li>
                    </ul>
                </li>
                @endif
            </ul>

            <!-- User Menu -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(Auth::check() && Auth::user()->foto_perfil)
                            <img src="{{ Storage::url(Auth::user()->foto_perfil) }}" 
                                 alt="Foto" class="rounded-circle me-2" width="32" height="32">
                        @else
                            <i class="bi bi-person-circle me-2 fs-5"></i>
                        @endif
                        <span class="d-none d-md-inline">{{ Auth::check() ? Auth::user()->name : 'Visitante' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @auth
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-gear me-2"></i>Perfil</a></li>
                        <li><a class="dropdown-item" href="{{ route('viagens.create') }}">
                            <i class="bi bi-plus-circle me-2"></i>Nova Viagem</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sair
                                </button>
                            </form>
                        </li>
                        @else
                        <li><a class="dropdown-item" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Entrar</a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-2"></i>Registrar</a></li>
                        @endauth
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation (opcional - para ações rápidas) -->
@auth
<div class="d-md-none fixed-bottom bg-white border-top shadow-sm">
    <div class="row g-0 text-center">
        <div class="col">
            <a href="{{ route('dashboard') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-muted' }}">
                <i class="bi bi-house fs-5 d-block"></i>
                <small>Home</small>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('viagens.create') }}" class="d-block py-2 text-decoration-none text-success">
                <i class="bi bi-plus-circle fs-5 d-block"></i>
                <small>Viagem</small>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('viagens.index') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('viagens.*') ? 'text-primary' : 'text-muted' }}">
                <i class="bi bi-list-ul fs-5 d-block"></i>
                <small>Lista</small>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('relatorios.index') }}" class="d-block py-2 text-decoration-none {{ request()->routeIs('relatorios.*') ? 'text-primary' : 'text-muted' }}">
                <i class="bi bi-graph-up fs-5 d-block"></i>
                <small>Relatórios</small>
            </a>
        </div>
    </div>
</div>

<!-- Espaçamento para bottom navigation em mobile -->
<div class="d-md-none" style="height: 70px;"></div>
@endauth
