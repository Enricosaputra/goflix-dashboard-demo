<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['href' => null, 'variant' => 'primary', 'type' => 'submit']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['href' => null, 'variant' => 'primary', 'type' => 'submit']); ?>
<?php foreach (array_filter((['href' => null, 'variant' => 'primary', 'type' => 'submit']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $class = 'inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors '.
        ($variant === 'primary' ? 'bg-slate-900 text-white hover:bg-slate-700' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50');
?>

<?php if($href): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $class])); ?>><?php echo e($slot); ?></a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => $class])); ?>><?php echo e($slot); ?></button>
<?php endif; ?>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/button.blade.php ENDPATH**/ ?>