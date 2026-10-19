<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Plans']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Plans']); ?>
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => __('Plans'),'description' => __('Subscription plans the app sells. Buying again while subscribed adds the time on top.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Plans')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Subscription plans the app sells. Buying again while subscribed adds the time on top.'))]); ?>
        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => route('packages.create')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('packages.create'))]); ?><?php echo e(__('New plan')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $attributes = $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $component = $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['headings' => ['Plan', '>Price', 'Includes', '>Sold', '>Revenue', 'Status']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headings' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Plan', '>Price', 'Includes', '>Sold', '>Revenue', 'Status'])]); ?>
        <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="px-4 py-3">
                    <span class="block font-medium"><?php echo e($package->name); ?></span>
                    <span class="block text-xs text-slate-500"><?php echo e(trans_choice(':count month|:count months', $package->duration_months)); ?></span>
                </td>
                <td class="whitespace-nowrap px-4 py-3 text-right tabular-nums"><?php echo e(rupiah($package->price)); ?></td>
                <td class="px-4 py-3 text-xs text-slate-600">
                    <?php echo e($package->max_quality); ?> · <?php echo e(trans_choice(':count device|:count devices', $package->max_devices)); ?>

                    <?php if($package->watch_on_tv): ?> · <?php echo e(__('TV')); ?> <?php endif; ?>
                    <?php if($package->ads_free): ?> · <?php echo e(__('no ads')); ?> <?php endif; ?>
                    <?php if($package->features->isNotEmpty()): ?>
                        <span class="block text-slate-500"><?php echo e($package->features->map(fn ($f) => $f->value !== '' ? "{$f->name}: {$f->value}" : $f->name)->implode(' · ')); ?></span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-right tabular-nums"><?php echo e($package->subscriptions_count); ?></td>
                <td class="whitespace-nowrap px-4 py-3 text-right tabular-nums"><?php echo e(rupiah($package->revenue ?? 0)); ?></td>
                <td class="px-4 py-3"><?php if (isset($component)) { $__componentOriginal23f1bbd2fed10b05ea73e1fd3a6d9617 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23f1bbd2fed10b05ea73e1fd3a6d9617 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.active-badge','data' => ['active' => $package->is_active]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('active-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($package->is_active)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal23f1bbd2fed10b05ea73e1fd3a6d9617)): ?>
<?php $attributes = $__attributesOriginal23f1bbd2fed10b05ea73e1fd3a6d9617; ?>
<?php unset($__attributesOriginal23f1bbd2fed10b05ea73e1fd3a6d9617); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal23f1bbd2fed10b05ea73e1fd3a6d9617)): ?>
<?php $component = $__componentOriginal23f1bbd2fed10b05ea73e1fd3a6d9617; ?>
<?php unset($__componentOriginal23f1bbd2fed10b05ea73e1fd3a6d9617); ?>
<?php endif; ?></td>
                <td class="px-4 py-3">
                    <?php if (isset($component)) { $__componentOriginal46b3bac9d56bec6271aaffa668486026 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal46b3bac9d56bec6271aaffa668486026 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.row-actions','data' => ['edit' => route('packages.edit', $package),'destroy' => route('packages.destroy', $package),'confirm' => __('Delete :name?', ['name' => $package->name])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('row-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['edit' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('packages.edit', $package)),'destroy' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('packages.destroy', $package)),'confirm' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Delete :name?', ['name' => $package->name]))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal46b3bac9d56bec6271aaffa668486026)): ?>
<?php $attributes = $__attributesOriginal46b3bac9d56bec6271aaffa668486026; ?>
<?php unset($__attributesOriginal46b3bac9d56bec6271aaffa668486026); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal46b3bac9d56bec6271aaffa668486026)): ?>
<?php $component = $__componentOriginal46b3bac9d56bec6271aaffa668486026; ?>
<?php unset($__componentOriginal46b3bac9d56bec6271aaffa668486026); ?>
<?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php if (isset($component)) { $__componentOriginal4eed7548ad3bd4d0709494afb2239601 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4eed7548ad3bd4d0709494afb2239601 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-row','data' => ['span' => '7']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('empty-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['span' => '7']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4eed7548ad3bd4d0709494afb2239601)): ?>
<?php $attributes = $__attributesOriginal4eed7548ad3bd4d0709494afb2239601; ?>
<?php unset($__attributesOriginal4eed7548ad3bd4d0709494afb2239601); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4eed7548ad3bd4d0709494afb2239601)): ?>
<?php $component = $__componentOriginal4eed7548ad3bd4d0709494afb2239601; ?>
<?php unset($__componentOriginal4eed7548ad3bd4d0709494afb2239601); ?>
<?php endif; ?>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>

    <div class="mt-4"><?php echo e($packages->links()); ?></div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /workspaces/goflix-dashboard-demo/resources/views/packages/index.blade.php ENDPATH**/ ?>