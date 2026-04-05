<div class="view-section fade-in space-y-6" wire:poll.10s>

    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="ph-fill ph-siren text-red-500 animate-pulse"></i>
                Alert Center
            </h1>
            <p class="text-sm text-slate-400 mt-1">Real-time monitoring alerts — auto-resolved when monitors recover.</p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($openCount > 0): ?>
        <button wire:click="acknowledgeAll" wire:confirm="Acknowledge all open alerts?"
            class="flex items-center gap-2 px-4 py-2 bg-slate-700 hover:bg-slate-600 border border-slate-600 text-white text-sm font-semibold rounded-lg transition-all">
            <i class="ph-bold ph-checks"></i> Acknowledge All (<?php echo e($openCount); ?>)
        </button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-red-400 text-xs font-bold uppercase tracking-wider">Critical</div>
                <div class="text-3xl font-bold text-white mt-1"><?php echo e($criticalCount); ?></div>
            </div>
            <div class="h-12 w-12 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center <?php echo e($criticalCount > 0 ? 'animate-pulse' : ''); ?>">
                <i class="ph-fill ph-siren text-2xl"></i>
            </div>
        </div>
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-amber-400 text-xs font-bold uppercase tracking-wider">Warnings</div>
                <div class="text-3xl font-bold text-white mt-1"><?php echo e($warningCount); ?></div>
            </div>
            <div class="h-12 w-12 rounded-full bg-amber-500/20 text-amber-500 flex items-center justify-center">
                <i class="ph-fill ph-warning text-2xl"></i>
            </div>
        </div>
        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-emerald-400 text-xs font-bold uppercase tracking-wider">Resolved Today</div>
                <div class="text-3xl font-bold text-white mt-1"><?php echo e($resolvedToday); ?></div>
            </div>
            <div class="h-12 w-12 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center">
                <i class="ph-fill ph-check-circle text-2xl"></i>
            </div>
        </div>
        <div class="bg-slate-700/50 border border-slate-600 rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Open</div>
                <div class="text-3xl font-bold text-white mt-1"><?php echo e($openCount); ?></div>
            </div>
            <div class="h-12 w-12 rounded-full bg-slate-600 text-slate-300 flex items-center justify-center">
                <i class="ph-fill ph-bell-ringing text-2xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex flex-col md:flex-row gap-3">
        
        <div class="relative flex-1">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by monitor name or IP..."
                class="w-full bg-slate-900 border border-slate-700 rounded-lg pl-9 pr-3 py-2 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
        </div>
        
        <div class="flex gap-1 bg-slate-900 border border-slate-700 rounded-lg p-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['open' => 'Open', 'acknowledged' => 'Acknowledged', 'resolved' => 'Resolved', 'all' => 'All']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button wire:click="$set('filter', '<?php echo e($val); ?>')"
                class="px-3 py-1.5 text-xs font-semibold rounded-md transition-all <?php echo e($filter === $val ? 'bg-emerald-600 text-white shadow' : 'text-slate-400 hover:text-white'); ?>">
                <?php echo e($label); ?>

            </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        <div class="flex gap-1 bg-slate-900 border border-slate-700 rounded-lg p-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['all' => 'All', 'critical' => 'Critical', 'warning' => 'Warning', 'info' => 'Info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button wire:click="$set('severity', '<?php echo e($val); ?>')"
                class="px-3 py-1.5 text-xs font-semibold rounded-md transition-all <?php echo e($severity === $val ? 'bg-slate-600 text-white shadow' : 'text-slate-400 hover:text-white'); ?>">
                <?php echo e($label); ?>

            </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="space-y-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $colors = [
                'critical' => ['border' => 'border-l-red-500',   'icon_bg' => 'bg-red-500/10',   'icon_text' => 'text-red-500',   'badge' => 'bg-red-500/10 text-red-400 border-red-500/30'],
                'warning'  => ['border' => 'border-l-amber-500', 'icon_bg' => 'bg-amber-500/10', 'icon_text' => 'text-amber-500', 'badge' => 'bg-amber-500/10 text-amber-400 border-amber-500/30'],
                'info'     => ['border' => 'border-l-blue-500',  'icon_bg' => 'bg-blue-500/10',  'icon_text' => 'text-blue-400',  'badge' => 'bg-blue-500/10 text-blue-400 border-blue-500/30'],
            ][$alert->severity] ?? [];
            $statusColors = [
                'open'         => 'bg-red-500/10 text-red-400 border-red-500/30',
                'acknowledged' => 'bg-slate-600/50 text-slate-300 border-slate-500',
                'resolved'     => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
            ];
        ?>
        <div wire:key="alert-<?php echo e($alert->id); ?>"
            class="bg-slate-800 border border-slate-700 border-l-4 <?php echo e($colors['border']); ?> rounded-r-xl p-4 flex flex-col md:flex-row items-start md:items-center gap-4 shadow-lg transition-all hover:bg-slate-750 group">

            
            <div class="h-11 w-11 rounded-xl <?php echo e($colors['icon_bg']); ?> <?php echo e($colors['icon_text']); ?> flex items-center justify-center flex-shrink-0">
                <i class="ph-fill <?php echo e($alert->typeIcon()); ?> text-xl"></i>
            </div>

            
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h3 class="font-bold text-white truncate"><?php echo e($alert->monitor->name ?? 'Unknown'); ?></h3>
                    <span class="text-xs px-2 py-0.5 rounded-full border font-bold uppercase <?php echo e($colors['badge']); ?>">
                        <?php echo e($alert->severity); ?>

                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold <?php echo e($statusColors[$alert->status] ?? ''); ?>">
                        <?php echo e(ucfirst($alert->status)); ?>

                    </span>
                    <span class="text-xs text-slate-500 bg-slate-900 px-2 py-0.5 rounded font-mono"><?php echo e(str_replace('_', ' ', strtoupper($alert->type))); ?></span>
                </div>
                <p class="text-sm text-slate-300 mb-2"><?php echo e($alert->message); ?></p>
                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                    <span><i class="ph ph-map-pin mr-1"></i><?php echo e($alert->monitor->ip_address ?? '—'); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alert->latency > 0): ?>
                    <span><i class="ph ph-timer mr-1"></i><?php echo e(number_format($alert->latency, 1)); ?>ms latency</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alert->packet_loss !== null): ?>
                    <span><i class="ph ph-wave-sawtooth mr-1"></i><?php echo e($alert->packet_loss); ?>% packet loss</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <span><i class="ph ph-clock mr-1"></i><?php echo e($alert->created_at->diffForHumans()); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alert->acknowledged_at): ?>
                    <span class="text-slate-600"><i class="ph ph-check mr-1"></i>Acknowledged <?php echo e($alert->acknowledged_at->diffForHumans()); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alert->resolved_at): ?>
                    <span class="text-emerald-600"><i class="ph ph-check-circle mr-1"></i>Resolved <?php echo e($alert->resolved_at->diffForHumans()); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="<?php echo e(route('monitor.details', $alert->monitor_id)); ?>"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-700 hover:bg-slate-600 border border-slate-600 rounded-lg transition-colors flex items-center gap-1">
                    <i class="ph ph-arrow-square-out"></i> View
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alert->status === 'open'): ?>
                <button wire:click="acknowledge(<?php echo e($alert->id); ?>)"
                    class="px-3 py-1.5 text-xs font-semibold text-amber-300 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 rounded-lg transition-colors flex items-center gap-1">
                    <i class="ph ph-check"></i> Acknowledge
                </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($alert->status, ['open', 'acknowledged'])): ?>
                <button wire:click="resolve(<?php echo e($alert->id); ?>)"
                    class="px-3 py-1.5 text-xs font-semibold text-emerald-300 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 rounded-lg transition-colors flex items-center gap-1">
                    <i class="ph ph-check-circle"></i> Resolve
                </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="p-12 text-center text-slate-500 bg-slate-800/50 rounded-xl border border-slate-700 border-dashed">
            <i class="ph ph-check-circle text-5xl text-emerald-500/30 block mb-3"></i>
            <p class="text-lg font-semibold text-slate-400">All clear!</p>
            <p class="text-sm mt-1">No alerts matching your current filters.</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alerts->hasPages()): ?>
    <div class="mt-4">
        <?php echo e($alerts->links()); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/alert-center.blade.php ENDPATH**/ ?>