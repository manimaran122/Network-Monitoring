<header class="sticky top-0 h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0 z-30 shadow-lg shadow-black/20">
    
    <div class="flex items-center gap-8">
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3">
            <i class="ph-fill ph-radar text-primary-500 text-2xl animate-pulse"></i>
            <span class="text-lg font-bold tracking-wide text-white">Monitoring Tool</span>
        </a>

        <nav class="hidden md:flex items-center gap-1">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_dashboard')): ?>
            <a href="<?php echo e(route('dashboard')); ?>" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn <?php echo e(request()->routeIs('dashboard') ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                <i class="ph ph-squares-four mr-1"></i>Dashboard
            </a>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_monitors')): ?>
            <a href="<?php echo e(route('monitors')); ?>" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn <?php echo e(request()->routeIs('monitors') ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                <i class="ph ph-list-dashes mr-1"></i>Monitors
            </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_alerts')): ?>
            <a href="<?php echo e(route('alerts')); ?>" wire:navigate class="relative px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn <?php echo e(request()->routeIs('alerts') ? 'text-red-400 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                <i class="ph ph-warning-octagon mr-1"></i>Alerts
                <?php try { $openAlerts = \App\Models\Alert::open()->count(); } catch(\Throwable $e) { $openAlerts = 0; } ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($openAlerts > 0): ?>
                <span class="absolute -top-1 -right-1 h-4 min-w-4 px-1 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center leading-none shadow shadow-red-500/40">
                    <?php echo e($openAlerts > 99 ? '99+' : $openAlerts); ?>

                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_reports')): ?>
            <a href="<?php echo e(route('reports')); ?>" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn <?php echo e(request()->routeIs('reports') ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                <i class="ph ph-chart-bar mr-1"></i>Reports
            </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view_settings', 'manage_settings'])): ?>
            <a href="<?php echo e(route('settings')); ?>" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn <?php echo e((request()->routeIs('settings')) ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                <i class="ph ph-gear mr-1"></i>Settings
            </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view_users', 'manage_users'])): ?>
            <a href="<?php echo e(route('user-management')); ?>" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn <?php echo e((request()->routeIs('user-management')) ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800'); ?>">
                <i class="ph ph-users mr-1"></i>User Management
            </a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="flex items-center gap-4">
        


        <button @click="$dispatch('open-user-profile')" class="flex items-center gap-2 hover:bg-slate-800 p-1.5 rounded-lg transition-colors cursor-pointer">
            <img class="h-8 w-8 rounded-full border border-slate-600" src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name ?? 'Admin')); ?>&background=334155&color=fff" alt="">
            <i class="ph-bold ph-caret-down text-slate-500 text-xs"></i>
        </button>

        <div class="h-8 w-px bg-slate-800 mx-2"></div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-slate-400 hover:text-red-400 p-2 rounded-lg hover:bg-slate-800 transition-colors" title="Logout">
                <i class="ph-bold ph-sign-out text-xl"></i>
            </button>
        </form>
    </div>
</header>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/components/partials/navbar.blade.php ENDPATH**/ ?>