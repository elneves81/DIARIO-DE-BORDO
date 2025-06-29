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
    ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-primary text-left text-base font-semibold text-primary bg-primary bg-opacity-10 focus:outline-none focus:text-primary focus:bg-primary focus:bg-opacity-20 focus:border-primary transition duration-150 ease-in-out rounded-end shadow-sm'
    : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-secondary hover:text-primary hover:bg-primary hover:bg-opacity-10 hover:border-primary focus:outline-none focus:text-primary focus:bg-primary focus:bg-opacity-10 focus:border-primary transition duration-150 ease-in-out rounded-end';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</a>
<?php /**PATH C:\Users\Elber\Documents\GitHub\prototipoSite\diario-bordo\resources\views/components/responsive-nav-link.blade.php ENDPATH**/ ?>