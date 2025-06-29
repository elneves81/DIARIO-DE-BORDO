

<?php $__env->startSection('content'); ?>
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-header">
            <h5 class="mb-0 text-center">Nova Viagem</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('viagens.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo $__env->make('viagens.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="<?php echo e(route('viagens.index')); ?>" class="btn btn-secondary w-100 w-md-auto me-md-2" tabindex="0" aria-label="Cancelar e voltar para listagem">Cancelar</a>
                    <button type="submit" class="btn btn-primary w-100 w-md-auto" tabindex="0" aria-label="Salvar nova viagem">Salvar</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Elber\Documents\GitHub\prototipoSite\diario-bordo\resources\views/viagens/create.blade.php ENDPATH**/ ?>