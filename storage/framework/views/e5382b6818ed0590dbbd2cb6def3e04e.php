<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title', 'description' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title', 'description' => null]); ?>
<?php foreach (array_filter((['title', 'description' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="mb-8 flex flex-wrap items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight"><?php echo e($title); ?></h1>
        <?php if($description): ?>
            <p class="mt-1 max-w-2xl text-sm text-slate-500"><?php echo e($description); ?></p>
        <?php endif; ?>
    </div>
    <div class="flex flex-wrap items-center gap-2"><?php echo e($slot); ?></div>
</div>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/page-header.blade.php ENDPATH**/ ?>