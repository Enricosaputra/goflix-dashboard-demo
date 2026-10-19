<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title' => 'Dashboard']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title' => 'Dashboard']); ?>
<?php foreach (array_filter((['title' => 'Dashboard']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(__($title)); ?> - Goflix Dashboard</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased lg:grid lg:grid-cols-[16rem_1fr]">
    <?php
        $menu = [
            [null, [['dashboard', 'Dashboard', 'dashboard']]],
            ['Content', [['movies.index', 'Movies', 'movies.*'], ['shows.index', 'Series', 'shows.*']]],
            ['Master data', [['genres.index', 'Genres', 'genres.*'], ['languages.index', 'Languages', 'languages.*'], ['people.index', 'Cast and crew', 'people.*'], ['seasons.index', 'Seasons', 'seasons.*']]],
            ['Users and payments', [['users.index', 'Users', 'users.*'], ['subscriptions.index', 'Subscriptions', 'subscriptions.*'], ['rentals.index', 'Rentals', 'rentals.*']]],
            ['Plans', [['packages.index', 'Plans', 'packages.*'], ['coupons.index', 'Coupons', 'coupons.*']]],
        ];
    ?>

    <aside class="bg-slate-900 p-4 lg:sticky lg:top-0 lg:flex lg:h-screen lg:flex-col lg:overflow-y-auto lg:p-5">
        <div class="mb-4 flex items-center justify-between lg:mb-8">
            <a href="<?php echo e(route('dashboard')); ?>" class="font-semibold text-white">Goflix <span class="font-normal text-slate-400">Dashboard</span></a>
            <div class="flex rounded-lg bg-white/10 p-0.5 text-xs font-medium" aria-label="<?php echo e(__('Language')); ?>">
                <?php $__currentLoopData = ['id' => 'ID', 'en' => 'EN']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('locale', $locale)); ?>"
                       class="rounded-md px-2 py-1 <?php echo e(app()->getLocale() === $locale ? 'bg-white text-slate-900' : 'text-slate-300 hover:text-white'); ?>"><?php echo e($label); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <nav class="flex items-center gap-1 overflow-x-auto lg:flex-col lg:items-stretch lg:overflow-visible">
            <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$heading, $items]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-1 lg:flex-col lg:items-stretch">
                    <?php if($heading): ?>
                        <p class="hidden px-3 pb-1 pt-5 text-[11px] font-semibold uppercase tracking-wider text-slate-500 lg:block"><?php echo e(__($heading)); ?></p>
                    <?php endif; ?>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$route, $label, $pattern]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route($route)); ?>"
                           class="flex-shrink-0 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs($pattern) ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white'); ?>">
                            <?php echo e(__($label)); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

    </aside>

    <main class="min-w-0 px-4 py-8 sm:px-8 lg:px-10 lg:py-10">
        <div class="mx-auto max-w-5xl">
            <?php if(session('status')): ?>
                <p role="status" class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800"><?php echo e(session('status')); ?></p>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <p role="alert" class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800"><?php echo e(session('error')); ?></p>
            <?php endif; ?>

            <?php echo e($slot); ?>

        </div>
    </main>
</body>
</html>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/components/layouts/app.blade.php ENDPATH**/ ?>