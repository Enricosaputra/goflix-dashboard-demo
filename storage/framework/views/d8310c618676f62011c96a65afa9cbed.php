<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['name', 'label', 'checked' => false]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['name', 'label', 'checked' => false]); ?>
<?php foreach (array_filter((['name', 'label', 'checked' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<label class="flex items-center gap-2 pt-7 text-sm font-medium text-slate-700">
    <input type="checkbox" name="<?php echo e($name); ?>" value="1" <?php if(old($name, $checked)): echo 'checked'; endif; ?> class="h-4 w-4 rounded border-slate-300">
    <?php echo e($label); ?>

</label>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/checkbox.blade.php ENDPATH**/ ?>