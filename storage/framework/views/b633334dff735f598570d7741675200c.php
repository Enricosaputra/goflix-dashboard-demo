<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['edit', 'destroy', 'confirm']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['edit', 'destroy', 'confirm']); ?>
<?php foreach (array_filter((['edit', 'destroy', 'confirm']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="flex items-center justify-end gap-3 whitespace-nowrap text-sm">
    <a href="<?php echo e($edit); ?>" class="text-slate-500 hover:text-slate-900"><?php echo e(__('Edit')); ?></a>
    <form method="POST" action="<?php echo e($destroy); ?>" onsubmit="return confirm(<?php echo \Illuminate\Support\Js::from($confirm)->toHtml() ?>)">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button class="text-slate-500 hover:text-red-600"><?php echo e(__('Delete')); ?></button>
    </form>
</div>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/row-actions.blade.php ENDPATH**/ ?>