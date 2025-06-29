<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['active']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['active']); ?>
<?php foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 pt-1 border-b-2 border-primary text-base font-semibold leading-5 text-primary focus:outline-none focus:border-primary transition duration-150 ease-in-out bg-primary bg-opacity-10 rounded-top shadow-sm'
    : 'inline-flex items-center px-3 pt-1 border-b-2 border-transparent text-base font-medium leading-5 text-secondary hover:text-primary hover:border-primary focus:outline-none focus:text-primary focus:border-primary transition duration-150 ease-in-out bg-transparent rounded-top';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</a>
<?php /**PATH C:\Users\Elber\Documents\GitHub\prototipoSite\diario-bordo\resources\views/components/nav-link.blade.php ENDPATH**/ ?>