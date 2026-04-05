<div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($forceReason === 'first_login'): ?>
    <div class="mb-5 flex items-start gap-3 bg-blue-900/40 border border-blue-500/40 rounded-xl p-4">
        <i class="ph-fill ph-info text-blue-400 text-xl mt-0.5 shrink-0"></i>
        <div>
            <p class="text-blue-300 font-semibold text-sm">First Login — Password Change Required</p>
            <p class="text-blue-400 text-xs mt-0.5">Your account was created by an administrator. You must set a new personal password before continuing.</p>
        </div>
    </div>
    <?php elseif($forceReason === 'expired'): ?>
    <div class="mb-5 flex items-start gap-3 bg-red-900/40 border border-red-500/40 rounded-xl p-4">
        <i class="ph-fill ph-warning text-red-400 text-xl mt-0.5 shrink-0"></i>
        <div>
            <p class="text-red-300 font-semibold text-sm">Password Expired</p>
            <p class="text-red-400 text-xs mt-0.5">Your password has expired (45-day policy). Please create a new password to regain access.</p>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="bg-slate-900/80 backdrop-blur-md border border-slate-700 rounded-2xl shadow-2xl overflow-hidden">

        
        <div class="p-6 border-b border-slate-700 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-emerald-600/20 border border-emerald-500/30">
                <i class="ph-fill ph-key text-emerald-400 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Change Password</h2>
                <p class="text-xs text-slate-400">Keep your account secure with a strong password</p>
            </div>
        </div>

        
        <form wire:submit="save" class="p-6 space-y-5">

            
            <div>
                <label for="current_password" class="block text-sm font-medium text-slate-300 mb-1.5">
                    Current Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-lock text-slate-500"></i>
                    </div>
                    <input
                        wire:model="current_password"
                        type="password"
                        id="current_password"
                        autocomplete="current-password"
                        class="block w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm"
                        placeholder="Enter your current password"
                    >
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                        <i class="ph-bold ph-x-circle"></i> <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="border-t border-slate-700/60"></div>

            
            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">
                    New Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-lock-key text-slate-500"></i>
                    </div>
                    <input
                        wire:model="password"
                        type="password"
                        id="password"
                        autocomplete="new-password"
                        class="block w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm"
                        placeholder="Minimum 8 characters"
                    >
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                        <i class="ph-bold ph-x-circle"></i> <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="text-slate-500 text-xs mt-1.5">Must be at least 8 characters with uppercase, lowercase &amp; numbers.</p>
            </div>

            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">
                    Confirm New Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-lock-key text-slate-500"></i>
                    </div>
                    <input
                        wire:model="password_confirmation"
                        type="password"
                        id="password_confirmation"
                        autocomplete="new-password"
                        class="block w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-sm"
                        placeholder="Re-enter your new password"
                    >
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                        <i class="ph-bold ph-x-circle"></i> <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-emerald-500 transition-all uppercase tracking-wide"
                >
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                        <i class="ph-bold ph-check-circle"></i> Update Password
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <i class="ph-bold ph-spinner animate-spin"></i> Saving...
                    </span>
                </button>
            </div>

        </form>
    </div>
</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/change-password.blade.php ENDPATH**/ ?>