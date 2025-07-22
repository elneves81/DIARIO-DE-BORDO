

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3 px-3">
    <div class="row">
        <div class="col-12">
            <!-- Header moderno -->
            <div class="card shadow border-0 mb-3">
                <div class="card-header bg-primary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ul me-2"></i>Minhas Viagens
                                <span class="badge bg-light text-dark ms-2"><?php echo e($viagens->total()); ?></span>
                            </h5>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo e(route('viagens.create')); ?>" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>Nova Viagem
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros elegantes -->
            <div class="card shadow border-0 mb-3">
                <div class="card-body py-3">
                    <form method="GET" action="" class="row g-3 align-items-end">
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="filtro_destino" class="form-label">Destino</label>
                            <input type="text" 
                                   name="destino" 
                                   id="filtro_destino"
                                   value="<?php echo e(request('destino')); ?>" 
                                   class="form-control" 
                                   placeholder="🔍 Buscar destino...">
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="filtro_data_ini" class="form-label">Data Inicial</label>
                            <input type="date" 
                                   name="data_ini" 
                                   id="filtro_data_ini"
                                   value="<?php echo e(request('data_ini')); ?>" 
                                   class="form-control">
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="filtro_data_fim" class="form-label">Data Final</label>
                            <input type="date" 
                                   name="data_fim" 
                                   id="filtro_data_fim"
                                   value="<?php echo e(request('data_fim')); ?>" 
                                   class="form-control">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="filtro_tipo_veiculo" class="form-label">Tipo de Veículo</label>
                            <select name="tipo_veiculo" id="filtro_tipo_veiculo" class="form-select">
                                <option value="">Todos os veículos</option>
                                <option value="Carro" <?php echo e(request('tipo_veiculo') == 'Carro' ? 'selected' : ''); ?>>Carro</option>
                                <option value="Moto" <?php echo e(request('tipo_veiculo') == 'Moto' ? 'selected' : ''); ?>>Moto</option>
                                <option value="Caminhão" <?php echo e(request('tipo_veiculo') == 'Caminhão' ? 'selected' : ''); ?>>Caminhão</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-2">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="bi bi-search me-1"></i>Filtrar
                                </button>
                                <a href="<?php echo e(route('viagens.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Cards modernos das viagens -->
            <?php $__empty_1 = true; $__currentLoopData = $viagens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $viagem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card shadow-sm border-0 mb-3 hover-card">
                <div class="card-body py-3">
                    <div class="row">
                        <!-- Info Principal -->
                        <div class="col-12 col-lg-8">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 45px; height: 45px;">
                                        <i class="bi bi-geo-alt fs-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2 fw-bold text-primary fs-5"><?php echo e($viagem->destino); ?></h6>
                                    <div class="text-muted">
                                        <div class="row g-2">
                                            <div class="col-12 col-sm-6">
                                                <i class="bi bi-calendar me-1 text-primary"></i>
                                                <strong><?php echo e(\Carbon\Carbon::parse($viagem->data)->format('d/m/Y')); ?></strong>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <i class="bi bi-person me-1 text-info"></i>
                                                <strong><?php echo e($viagem->condutor); ?></strong>
                                            </div>
                                            <?php if($viagem->tipo_veiculo): ?>
                                            <div class="col-12 col-sm-6">
                                                <i class="bi bi-car-front me-1 text-success"></i><?php echo e($viagem->tipo_veiculo); ?>

                                                <?php if($viagem->placa): ?>
                                                    <span class="badge bg-secondary ms-2"><?php echo e($viagem->placa); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                            <div class="col-12 col-sm-6">
                                                <i class="bi bi-clock me-1 text-warning"></i>
                                                <strong><?php echo e($viagem->hora_saida); ?></strong>
                                                <?php if($viagem->hora_chegada): ?>
                                                    <i class="bi bi-arrow-right mx-1"></i><strong><?php echo e($viagem->hora_chegada); ?></strong>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Status coloridos modernos -->
                                    <div class="mt-3">
                                        <?php if($viagem->km_saida && $viagem->km_chegada): ?>
                                            <?php
                                                $distancia = $viagem->km_chegada - $viagem->km_saida;
                                            ?>
                                            <span class="badge bg-success fs-6 px-3 py-2">
                                                <i class="bi bi-check-circle me-2"></i>Concluída - <?php echo e($distancia); ?>km
                                            </span>
                                        <?php elseif($viagem->km_saida): ?>
                                            <span class="badge bg-warning fs-6 px-3 py-2">
                                                <i class="bi bi-clock-history me-2"></i>Em andamento
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-info fs-6 px-3 py-2">
                                                <i class="bi bi-calendar-plus me-2"></i>Agendada
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ações elegantes -->
                        <div class="col-12 col-lg-4">
                            <div class="d-flex gap-2 justify-content-end mt-3 mt-lg-0 align-items-start">
                                <a href="<?php echo e(route('viagens.show', $viagem)); ?>" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                                <a href="<?php echo e(route('viagens.edit', $viagem)); ?>" 
                                   class="btn btn-outline-warning">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                <?php if(auth()->check() && auth()->user()->isAdmin()): ?>
                                <form action="<?php echo e(route('viagens.destroy', $viagem)); ?>" 
                                      method="POST" 
                                      style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="btn btn-outline-danger"
                                            onclick="return confirm('Confirma a exclusão desta viagem?')">
                                        <i class="bi bi-trash me-1"></i>Excluir
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="text-muted mt-3">Nenhuma viagem encontrada</h5>
                    <p class="text-muted">Clique no botão "Nova Viagem" para registrar sua primeira viagem.</p>
                    <a href="<?php echo e(route('viagens.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Registrar Primeira Viagem
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Paginação -->
            <?php if($viagens->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($viagens->appends(request()->query())->links('pagination::bootstrap-4')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.badge {
    font-weight: 500;
}

.text-primary {
    color: #0d6efd !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form em mudança de filtros para tablets/desktop
    if (window.innerWidth > 768) {
        const filtros = document.querySelectorAll('select[name="tipo_veiculo"]');
        filtros.forEach(filtro => {
            filtro.addEventListener('change', function() {
                this.form.submit();
            });
        });
    }
    
    // Swipe actions para mobile (futura implementação)
    if ('ontouchstart' in window) {
        // Implementar swipe para ações rápidas
        console.log('Touch device detected - swipe actions available');
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Elber\Documents\GitHub\prototipoSite\diario-bordo\resources\views/viagens/index-mobile.blade.php ENDPATH**/ ?>