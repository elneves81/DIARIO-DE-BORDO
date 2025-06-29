

<?php $__env->startSection('content'); ?>
<div class="container">
    <h3 class="mb-4">Mensagens e Sugestões</h3>
    <form method="get" class="mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="tipo" class="form-label">Filtrar por tipo:</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="contato" <?php if($tipo=='contato'): ?> selected <?php endif; ?>>Fale Conosco</option>
                    <option value="sugestao" <?php if($tipo=='sugestao'): ?> selected <?php endif; ?>>Sugestão</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Tipo</th>
                    <th>Mensagem</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $sugestoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sugestao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($sugestao->id); ?></td>
                    <td>
                        <?php echo e($sugestao->user ? $sugestao->user->name : 'Desconhecido'); ?><br>
                        <small><?php echo e($sugestao->user ? $sugestao->user->email : ''); ?></small>
                    </td>
                    <td>
                        <?php if($sugestao->tipo=='contato'): ?>
                            <span class="badge bg-info">Fale Conosco</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Sugestão</span>
                        <?php endif; ?>
                    </td>
                    <td style="max-width:300px;white-space:pre-wrap;">
                        <?php echo e($sugestao->mensagem); ?>

                    </td>
                    <td><?php echo e($sugestao->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <form action="<?php echo e(route('admin.sugestoes.destroy', $sugestao->id)); ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta mensagem?');" class="mb-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                        <form action="<?php echo e(route('admin.sugestoes.responder', $sugestao->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <textarea name="resposta" class="form-control mb-2" rows="2" placeholder="Digite sua resposta (será enviada por email)..." required></textarea>
                            <button type="submit" class="btn btn-sm btn-success">Enviar Resposta</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center">Nenhuma mensagem encontrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php echo e($sugestoes->withQueryString()->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Elber\Documents\GitHub\prototipoSite\diario-bordo\resources\views/admin/sugestoes/index.blade.php ENDPATH**/ ?>