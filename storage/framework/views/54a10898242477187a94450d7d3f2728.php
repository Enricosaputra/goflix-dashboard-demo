<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['span']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['span']); ?>
<?php foreach (array_filter((['span']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<tr><td colspan="<?php echo e($span); ?>" class="px-4 py-10 text-center text-slate-500"><?php echo e(__('Nothing here yet.')); ?></td></tr>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/empty-row.blade.php ENDPATH**/ ?>