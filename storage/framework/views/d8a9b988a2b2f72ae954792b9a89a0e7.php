<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['tone' => 'slate']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['tone' => 'slate']); ?>
<?php foreach (array_filter((['tone' => 'slate']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $tones = ['slate' => 'bg-slate-100 text-slate-600', 'green' => 'bg-emerald-100 text-emerald-700', 'amber' => 'bg-amber-100 text-amber-700'];
?>

<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?php echo e($tones[$tone]); ?>"><?php echo e($slot); ?></span>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/badge.blade.php ENDPATH**/ ?>