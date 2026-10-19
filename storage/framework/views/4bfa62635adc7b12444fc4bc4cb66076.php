<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title' => null, 'description' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title' => null, 'description' => null]); ?>
<?php foreach (array_filter((['title' => null, 'description' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<section <?php echo e($attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white shadow-sm'])); ?>>
    <?php if($title): ?>
        <header class="border-b border-slate-100 px-6 py-4">
            <h2 class="font-semibold"><?php echo e($title); ?></h2>
            <?php if($description): ?>
                <p class="mt-0.5 text-sm text-slate-500"><?php echo e($description); ?></p>
            <?php endif; ?>
        </header>
    <?php endif; ?>
    <div class="p-6"><?php echo e($slot); ?></div>
</section>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/card.blade.php ENDPATH**/ ?>