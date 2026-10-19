<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['headings']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['headings']); ?>
<?php foreach (array_filter((['headings']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
    <table class="w-full min-w-[640px] text-left text-sm">
        <thead class="text-xs uppercase tracking-wide text-slate-400">
            <tr>
                <?php $__currentLoopData = $headings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heading): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="px-4 py-3 font-medium <?php echo e(str_starts_with($heading, '>') ? 'text-right' : ''); ?>"><?php echo e(__(ltrim($heading, '>'))); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100"><?php echo e($slot); ?></tbody>
    </table>
</div>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/table.blade.php ENDPATH**/ ?>