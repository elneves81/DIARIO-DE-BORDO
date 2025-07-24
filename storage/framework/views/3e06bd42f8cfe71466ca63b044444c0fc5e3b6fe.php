<nav x-data="{ open: false }" class="executive-navbar">
    <!-- Navbar Executiva Profissional -->
    <div class="navbar-background">
        <div class="navbar-overlay"></div>
        <div class="navbar-content">
            <div class="container-fluid px-4">
                <div class="navbar-inner">
                    <!-- Logo Executivo -->
                    <div class="logo-executive">
                        <a href="<?php echo e(route('dashboard')); ?>" class="logo-link">
                            <div class="logo-container">
                                <img src="/img/logoD.png" alt="Logo" class="logo-image" />
                                <div class="logo-text">
                                    <span class="logo-title">Diário de Bordo</span>
                                    <span class="logo-subtitle">Sistema Executivo</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Seção Executiva - Links Principais -->
                    <div class="nav-links-executive">
                        <!-- Área Executiva -->
                        <div class="nav-section-executive">
                            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link-executive <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" title="Painel Executivo">
                                <div class="nav-icon">
                                    <i class="bi bi-speedometer2"></i>
                                </div>
                                <span class="nav-text">Dashboard</span>
                            </a>

                            <a href="<?php echo e(route('dashboard.analytics')); ?>" class="nav-link-executive <?php echo e(request()->routeIs('dashboard.analytics') ? 'active' : ''); ?>" title="Análises Avançadas">
                                <div class="nav-icon">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                                <span class="nav-text">Analytics</span>
                            </a>

                            <a href="<?php echo e(route('relatorios.index')); ?>" class="nav-link-executive <?php echo e(request()->routeIs('relatorios.*') ? 'active' : ''); ?>" title="Relatórios Gerenciais">
                                <div class="nav-icon">
                                    <i class="bi bi-file-earmark-bar-graph"></i>
                                </div>
                                <span class="nav-text">Relatórios</span>
                            </a>
                        </div>

                        <!-- Área Administrativa (Apenas para Admins) -->
                        <?php if(Auth::check() && Auth::user()->is_admin): ?>
                            <div class="nav-section-divider"></div>
                            <div class="nav-section-admin">
                                <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link-executive <?php echo e(request()->routeIs('admin.users.index') || request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit') ? 'active' : ''); ?>" title="Gestão de Usuários">
                                    <div class="nav-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <span class="nav-text">Usuários</span>
                                </a>
                                
                                <a href="<?php echo e(route('admin.users.create')); ?>" class="nav-link-executive success <?php echo e(request()->routeIs('admin.users.create') ? 'active' : ''); ?>" title="Cadastrar Novo Usuário">
                                    <div class="nav-icon">
                                        <i class="bi bi-person-plus-fill"></i>
                                    </div>
                                    <span class="nav-text">Novo Usuário</span>
                                </a>
                                
                                <a href="<?php echo e(route('admin.sugestoes.index')); ?>" class="nav-link-executive <?php echo e(request()->routeIs('admin.sugestoes.*') ? 'active' : ''); ?>" title="Central de Mensagens">
                                    <div class="nav-icon">
                                        <i class="bi bi-chat-dots-fill"></i>
                                    </div>
                                    <span class="nav-text">Mensagens</span>
                                    <?php
                                        $totalMensagens = \App\Models\Sugestao::count();
                                        $totalContatos = \App\Models\Sugestao::where('tipo','contato')->count();
                                        $mensagensNaoLidas = \App\Models\Sugestao::whereNull('resposta')->count();
                                    ?>
                                    <?php if($totalContatos > 0 || $totalMensagens > 0): ?>
                                        <div class="nav-badges">
                                            <?php if($mensagensNaoLidas > 0): ?>
                                                <span class="nav-badge danger" title="Mensagens não respondidas"><?php echo e($mensagensNaoLidas); ?></span>
                                            <?php endif; ?>
                                            <span class="nav-badge primary" title="Contatos"><?php echo e($totalContatos); ?></span>
                                            <span class="nav-badge secondary" title="Total de mensagens"><?php echo e($totalMensagens); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Controles Profissionais -->
                    <div class="controls-executive">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" type="button" class="control-btn" title="Alternar modo escuro/claro">
                            <div class="control-icon">
                                <svg id="darkModeIcon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </div>
                            <span class="control-text">Tema</span>
                        </button>

                        <!-- Notifications Toggle -->
                        <button id="notificationsToggle" type="button" class="control-btn" title="Configurar notificações do sistema">
                            <div class="control-icon">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                </svg>
                            </div>
                            <span class="control-text">Notificações</span>
                            <span id="notificationIndicator" class="notification-indicator hidden"></span>
                        </button>

                        <!-- Advanced Search Toggle -->
                        <button id="advancedSearchToggle" type="button" class="control-btn" title="Busca avançada no sistema">
                            <div class="control-icon">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="control-text">Buscar</span>
                        </button>

                        <!-- User Profile Dropdown -->
                        <div class="user-dropdown-executive">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown','data' => ['align' => 'right','width' => '48']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => '48']); ?>
                                 <?php $__env->slot('trigger', null, []); ?> 
                                    <button class="user-btn-executive" title="Menu do usuário">
                                        <div class="user-avatar">
                                            <?php if(Auth::check() && Auth::user()->is_admin): ?>
                                                <i class="bi bi-person-fill-gear"></i>
                                            <?php else: ?>
                                                <i class="bi bi-person-circle"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="user-info">
                                            <span class="user-name"><?php echo e(Auth::check() ? Str::limit(Auth::user()->name, 15) : 'Visitante'); ?></span>
                                            <span class="user-role">
                                                <?php if(Auth::check() && Auth::user()->is_admin): ?>
                                                    <i class="bi bi-shield-fill-check me-1"></i>Administrador
                                                <?php else: ?>
                                                    <i class="bi bi-person-badge me-1"></i>Usuário
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="dropdown-arrow">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                 <?php $__env->endSlot(); ?>

                                 <?php $__env->slot('content', null, []); ?> 
                                    <div class="dropdown-header">
                                        <div class="dropdown-user-info">
                                            <strong><?php echo e(Auth::check() ? Auth::user()->name : 'Visitante'); ?></strong>
                                            <small class="text-muted d-block"><?php echo e(Auth::check() && Auth::user()->email ? Auth::user()->email : ''); ?></small>
                                        </div>
                                    </div>
                                    
                                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('profile.edit')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.edit'))]); ?>
                                        <i class="bi bi-person-gear me-2"></i><?php echo e(__('Meu Perfil')); ?>

                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

                                    <?php if(Auth::check() && Auth::user()->is_admin): ?>
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-header">
                                            <small class="text-muted">Área Administrativa</small>
                                        </div>
                                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('admin.users.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.users.index'))]); ?>
                                            <i class="bi bi-people me-2"></i>Gerenciar Usuários
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('admin.sugestoes.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.sugestoes.index'))]); ?>
                                            <i class="bi bi-chat-dots me-2"></i>Central de Mensagens
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                    <?php endif; ?>

                                    <div class="dropdown-divider"></div>
                                    
                                    <!-- Authentication -->
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('logout'),'onclick' => 'event.preventDefault(); this.closest(\'form\').submit();','class' => 'text-danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('logout')),'onclick' => 'event.preventDefault(); this.closest(\'form\').submit();','class' => 'text-danger']); ?>
                                            <i class="bi bi-box-arrow-right me-2"></i><?php echo e(__('Sair do Sistema')); ?>

                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                    </form>
                                 <?php $__env->endSlot(); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        </div>

                        <!-- Mobile Menu Toggle -->
                        <button @click="open = ! open" class="mobile-menu-toggle" title="Menu mobile">
                            <svg stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Mobile Responsivo -->
    <div :class="{'block': open, 'hidden': ! open}" class="mobile-menu-executive">
        <!-- Seção Executiva Mobile -->
        <div class="mobile-section-header">
            <i class="bi bi-speedometer2 me-2"></i>
            <span>Área Executiva</span>
        </div>
        <div class="mobile-nav-links">
            <a href="<?php echo e(route('dashboard')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard Executivo</span>
            </a>

            <a href="<?php echo e(route('dashboard.analytics')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('dashboard.analytics') ? 'active' : ''); ?>">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Analytics Avançado</span>
            </a>

            <a href="<?php echo e(route('relatorios.index')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('relatorios.*') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Relatórios Gerenciais</span>
            </a>
        </div>

        <!-- Seção Administrativa Mobile (Apenas para Admins) -->
        <?php if(Auth::check() && Auth::user()->is_admin): ?>
            <div class="mobile-section-header">
                <i class="bi bi-shield-fill-check me-2"></i>
                <span>Área Administrativa</span>
            </div>
            <div class="mobile-nav-links">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('admin.users.*') && !request()->routeIs('admin.users.create') ? 'active' : ''); ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>Gestão de Usuários</span>
                </a>
                
                <a href="<?php echo e(route('admin.users.create')); ?>" class="mobile-nav-link success <?php echo e(request()->routeIs('admin.users.create') ? 'active' : ''); ?>">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Cadastrar Usuário</span>
                </a>
                
                <a href="<?php echo e(route('admin.sugestoes.index')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('admin.sugestoes.*') ? 'active' : ''); ?>">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Central de Mensagens</span>
                    <?php if($mensagensNaoLidas > 0): ?>
                        <span class="mobile-badge danger"><?php echo e($mensagensNaoLidas); ?></span>
                    <?php endif; ?>
                </a>
            </div>
        <?php endif; ?>

        <!-- Informações do Usuário Mobile -->
        <div class="mobile-user-info">
            <div class="mobile-section-header">
                <i class="bi bi-person-circle me-2"></i>
                <span>Minha Conta</span>
            </div>
            
            <div class="mobile-user-details">
                <div class="mobile-user-avatar">
                    <?php if(Auth::check() && Auth::user()->is_admin): ?>
                        <i class="bi bi-person-fill-gear"></i>
                    <?php else: ?>
                        <i class="bi bi-person-circle"></i>
                    <?php endif; ?>
                </div>
                <div class="mobile-user-text">
                    <div class="mobile-user-name"><?php echo e(Auth::check() ? Auth::user()->name : 'Visitante'); ?></div>
                    <div class="mobile-user-email"><?php echo e(Auth::check() && Auth::user()->email ? Auth::user()->email : ''); ?></div>
                    <div class="mobile-user-role">
                        <?php if(Auth::check() && Auth::user()->is_admin): ?>
                            <i class="bi bi-shield-fill-check me-1"></i>Administrador do Sistema
                        <?php else: ?>
                            <i class="bi bi-person-badge me-1"></i>Usuário do Sistema
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mobile-user-actions">
                <a href="<?php echo e(route('profile.edit')); ?>" class="mobile-action-link">
                    <i class="bi bi-person-gear"></i>
                    <span>Editar Perfil</span>
                </a>

                <!-- Controles Mobile -->
                <button id="darkModeToggleMobile" type="button" class="mobile-action-link">
                    <i class="bi bi-moon-stars"></i>
                    <span>Alternar Tema</span>
                </button>

                <button id="notificationsToggleMobile" type="button" class="mobile-action-link">
                    <i class="bi bi-bell"></i>
                    <span>Notificações</span>
                </button>

                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="mobile-action-link logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sair do Sistema</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH C:\Users\elber\OneDrive\Documentos\GitHub\DIARIO-DE-BORDO\resources\views/layouts/navigation-executive.blade.php ENDPATH**/ ?>