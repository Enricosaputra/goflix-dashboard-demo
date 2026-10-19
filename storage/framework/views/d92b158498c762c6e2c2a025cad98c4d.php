<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['name', 'label', 'options', 'selected' => []]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['name', 'label', 'options', 'selected' => []]); ?>
<?php foreach (array_filter((['name', 'label', 'options', 'selected' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>


<?php $selected = collect(old($name, $selected))->map(fn ($v) => (string) $v)->all(); ?>
<fieldset <?php echo e($attributes->only('class')); ?>>
    <legend class="mb-1.5 text-sm font-medium text-slate-700"><?php echo e($label); ?></legend>
    <div class="flex flex-wrap gap-x-4 gap-y-2 rounded-lg border border-slate-300 bg-white p-3">
        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="flex items-center gap-1.5 text-sm">
                <input type="checkbox" name="<?php echo e($name); ?>[]" value="<?php echo e($value); ?>" <?php if(in_array((string) $value, $selected, true)): echo 'checked'; endif; ?> class="h-4 w-4 rounded border-slate-300">
                <?php echo e($text); ?>

            </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</fieldset>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/checkbox-list.blade.php ENDPATH**/ ?>