<?php if (isset($component)) { $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015 = $component; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\GuestLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <style>
        .login-modern h3 {
            font-family: 'Montserrat', Arial, sans-serif;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 0.2em;
        }
        .login-modern .form-label {
            font-weight: 600;
            color: #222;
        }
        .login-modern .form-control {
            border-radius: 12px;
            font-size: 1.1em;
            padding: 0.7em 1em;
        }
        .login-modern .btn-primary {
            background: linear-gradient(90deg, #0d6efd 60%, #198754 100%);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1em;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 2px 8px rgba(13,110,253,0.08);
        }
        .login-modern .btn-primary:hover {
            background: linear-gradient(90deg, #198754 0%, #0d6efd 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .login-modern .link-primary, .login-modern .link-secondary {
            font-weight: 500;
        }
        @media (max-width: 600px) {
            .login-modern { padding: 1.2rem 0.5rem; }
        }
    </style>
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center" style="background: linear-gradient(135deg, #f8fafc 60%, #e9ecef 100%);">
        <form class="login-modern w-100" style="background: #fff; box-shadow: 0 2px 16px rgba(0,0,0,0.08); border-radius: 18px; max-width: 370px; padding: 2.2rem 1.5rem; margin: 0 auto;" method="POST" action="<?php echo e(route('login')); ?>">
            <div class="text-center mb-4">
                <h3>Diário de Bordo</h3>
                <p class="text-muted mb-0">Acesse sua conta</p>
            </div>
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-3','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-3','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input id="email" type="email" name="email" class="form-control text-center <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username" tabindex="0" aria-label="E-mail de acesso">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input id="password" type="password" name="password" class="form-control text-center <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autocomplete="current-password" tabindex="0" aria-label="Senha de acesso">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-check mb-3">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember" tabindex="0" aria-label="Lembrar-me">
                <label for="remember_me" class="form-check-label">Lembrar-me</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <?php if(Route::has('register')): ?>
                    <a href="<?php echo e(route('register')); ?>" class="link-primary" tabindex="0" aria-label="Criar nova conta">Criar conta</a>
                <?php endif; ?>
                <?php if(Route::has('password.request')): ?>
                    <a href="<?php echo e(route('password.request')); ?>" class="link-secondary" tabindex="0" aria-label="Recuperar senha">Esqueceu a senha?</a>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2" tabindex="0" aria-label="Entrar no sistema">Entrar</button>
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary w-100 mt-2" tabindex="0" aria-label="Voltar para a página anterior">Voltar</a>
        </form>
    </div>
    <div class="text-center text-muted mt-4" style="font-size: 0.95em;">
        By DITIS- ELN- Todos os Direitos reservados
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015)): ?>
<?php $component = $__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015; ?>
<?php unset($__componentOriginalc3251b308c33b100480ddc8862d4f9c79f6df015); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Elber\Documents\GitHub\prototipoSite\diario-bordo\resources\views/auth/login.blade.php ENDPATH**/ ?>