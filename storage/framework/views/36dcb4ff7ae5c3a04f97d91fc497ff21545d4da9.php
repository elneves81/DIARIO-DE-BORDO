
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12">
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary mb-3" tabindex="0" aria-label="Voltar para a tela anterior">Voltar</a>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-3 mb-2">
        <label for="data" class="form-label">Data</label>
        <input type="date" class="form-control text-center w-100" id="data" name="data" value="<?php echo e(old('data', isset($viagem) ? (is_string($viagem->data) ? $viagem->data : $viagem->data->format('Y-m-d')) : '')); ?>" required>
        <?php $__errorArgs = ['data'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="hora_saida" class="form-label"><span style="color:#0d6efd;"><b>Hora Saída</b></span></label>
        <div class="input-group">
            <input type="time" class="form-control text-center w-100" id="hora_saida" name="hora_saida" value="<?php echo e(old('hora_saida', isset($viagem) ? $viagem->hora_saida : '')); ?>" required>
        </div>
        <?php $__errorArgs = ['hora_saida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="km_saida" class="form-label"><span style="color:#0d6efd;"><b>KM Saída</b></span></label>
        <input type="number" class="form-control text-center w-100" id="km_saida" name="km_saida" value="<?php echo e(old('km_saida', isset($viagem) ? $viagem->km_saida : '')); ?>" required>
        <?php $__errorArgs = ['km_saida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-6 mb-2">
        <label for="destino" class="form-label">Destino</label>
        <input type="text" class="form-control text-center w-100" id="destino" name="destino" placeholder="Ex: Centro, Bairro, Cidade" value="<?php echo e(old('destino', isset($viagem) ? $viagem->destino : '')); ?>" required>
        <?php $__errorArgs = ['destino'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-3 mb-2">
        <label for="hora_chegada" class="form-label"><span style="color:#198754;"><b>Hora Chegada</b></span> <small class="text-muted">(opcional)</small></label>
        <div class="input-group">
            <input type="time" class="form-control text-center w-100" id="hora_chegada" name="hora_chegada" value="<?php echo e(old('hora_chegada', isset($viagem) ? $viagem->hora_chegada : '')); ?>">
        </div>
        <?php $__errorArgs = ['hora_chegada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="km_chegada" class="form-label"><span style="color:#198754;"><b>KM Chegada</b></span></label>
        <input type="number" class="form-control text-center w-100" id="km_chegada" name="km_chegada" value="<?php echo e(old('km_chegada', isset($viagem) ? $viagem->km_chegada : '')); ?>" <?php if(!isset($viagem)): ?> <?php else: ?> required <?php endif; ?>>
        <?php $__errorArgs = ['km_chegada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-6 mb-2">
        <label for="condutor" class="form-label">Condutor</label>
        <input type="text" class="form-control text-center w-100" id="condutor" name="condutor" value="<?php echo e(old('condutor', isset($viagem) ? ($viagem->user ? $viagem->user->name : $viagem->condutor) : (Auth::user() ? Auth::user()->name : ''))); ?>" required readonly>
        <input type="hidden" name="user_id" value="<?php echo e(isset($viagem) && $viagem->user ? $viagem->user->id : (Auth::user() ? Auth::user()->id : '')); ?>">
        <?php $__errorArgs = ['condutor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-3 mb-2">
        <label for="num_registro_abastecimento" class="form-label">Nº Reg. Abastecimento</label>
        <input type="text" class="form-control text-center w-100" id="num_registro_abastecimento" name="num_registro_abastecimento" value="<?php echo e(old('num_registro_abastecimento', isset($viagem) ? $viagem->num_registro_abastecimento : '')); ?>">
        <?php $__errorArgs = ['num_registro_abastecimento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="quantidade_abastecida" class="form-label">Qtd. Abastecida (L)</label>
        <input type="number" step="0.01" class="form-control text-center w-100" id="quantidade_abastecida" name="quantidade_abastecida" value="<?php echo e(old('quantidade_abastecida', isset($viagem) ? $viagem->quantidade_abastecida : '')); ?>">
        <?php $__errorArgs = ['quantidade_abastecida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-3 mb-2">
        <label for="tipo_veiculo" class="form-label">Tipo de Veículo</label>
        <input type="text" class="form-control text-center w-100" id="tipo_veiculo" name="tipo_veiculo" value="<?php echo e(old('tipo_veiculo', isset($viagem) ? $viagem->tipo_veiculo : '')); ?>">
        <?php $__errorArgs = ['tipo_veiculo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-12 col-md-3 mb-2">
        <label for="placa" class="form-label">Placa</label>
        <input type="text" class="form-control text-center w-100" id="placa" name="placa" value="<?php echo e(old('placa', isset($viagem) ? $viagem->placa : '')); ?>">
        <?php $__errorArgs = ['placa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-8 mb-2">
        <label class="form-label">Checklist Pré-Viagem</label>
        <div class="d-flex flex-column flex-md-row flex-wrap gap-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checklist[documentos]" id="check_documentos" value="1" <?php echo e(old('checklist.documentos') ? 'checked' : ''); ?>>
                <label class="form-check-label" for="check_documentos">Documentos do veículo em dia</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checklist[manutencao]" id="check_manutencao" value="1" <?php echo e(old('checklist.manutencao') ? 'checked' : ''); ?>>
                <label class="form-check-label" for="check_manutencao">Manutenção preventiva realizada</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checklist[abastecimento]" id="check_abastecimento" value="1" <?php echo e(old('checklist.abastecimento') ? 'checked' : ''); ?>>
                <label class="form-check-label" for="check_abastecimento">Abastecimento suficiente</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checklist[epc]" id="check_epc" value="1" <?php echo e(old('checklist.epc') ? 'checked' : ''); ?>>
                <label class="form-check-label" for="check_epc">Equipamentos de proteção (EPC) conferidos</label>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3 justify-content-center flex-wrap">
    <div class="col-12 col-md-8 mb-2">
        <label for="anexos" class="form-label">Anexos (fotos, recibos, comprovantes)</label>
        <input type="file" class="form-control w-100" id="anexos" name="anexos[]" multiple accept="image/*,application/pdf">
        <small class="text-muted">Você pode anexar imagens ou PDFs. Tamanho máximo por arquivo: 2MB.</small>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function autoFormatHora(input) {
        input.addEventListener('input', function (e) {
            let v = input.value.replace(/[^0-9]/g, '');
            if (v.length > 4) v = v.slice(0,4);
            if (v.length >= 3) {
                input.value = v.slice(0,2) + ':' + v.slice(2,4);
            } else if (v.length >= 1) {
                input.value = v;
            }
        });
    }
    const horaSaida = document.getElementById('hora_saida');
    const horaChegada = document.getElementById('hora_chegada');
    if (horaSaida) autoFormatHora(horaSaida);
    if (horaChegada) autoFormatHora(horaChegada);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataInput = document.getElementById('data');
    if (dataInput) {
        const hoje = new Date().toISOString().split('T')[0];
        dataInput.value = hoje;
        dataInput.setAttribute('max', hoje);
        dataInput.setAttribute('min', hoje);
        dataInput.addEventListener('input', function() {
            if (this.value !== hoje) {
                this.setCustomValidity('Você não pode agendar essa viagem. A data precisa ser de Hoje!!');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\Elber\Documents\GitHub\prototipoSite\diario-bordo\resources\views/viagens/form.blade.php ENDPATH**/ ?>