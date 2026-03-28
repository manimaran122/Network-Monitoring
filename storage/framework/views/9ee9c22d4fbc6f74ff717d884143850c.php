<div class="flex items-center justify-center h-screen w-screen bg-[#071421] p-4 md:p-8">
    <!-- Large Outer Container -->
    <div class="w-full md:w-[90%] max-w-[1700px] h-[95vh] bg-[#0a1628] rounded-[24px] p-6 md:p-[40px] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.8)] flex items-center justify-start border border-[#16273f] overflow-hidden">
        
        <!-- Login Card -->
        <div class="w-full max-w-[450px] sm:w-[450px] bg-[#0d1d33] rounded-[16px] shadow-[0_10px_30px_-5px_rgba(0,0,0,0.7)] p-6 md:p-8 md:ml-[4%] border border-[#1d2f4a]">

            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#0b2235] border border-[#2a4a60] mb-3">
                    <i class="ph-fill ph-radar text-3xl text-[#1abc9c]"></i>
                </div>
                <h2 class="text-xl font-bold text-white tracking-tight">Monitoring Tool</h2>
                <p class="text-[#a0b3c2] text-xs mt-1.5">Sign in to your account</p>
            </div>

            
            <div class="mb-6 p-1 bg-slate-800/60 rounded-2xl flex items-center">
                <button type="button" class="flex-1 py-2 px-4 text-sm font-medium transition-all duration-300 bg-white/10 text-white rounded-xl">
                    Sign In
                </button>
                <a href="<?php echo e(route('register')); ?>" class="flex-1 py-2 px-4 text-center text-sm font-medium transition-all duration-300 text-slate-400 hover:text-white">
                    Sign Up
                </a>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('password_reset_success')): ?>
            <div class="mb-5 flex items-center gap-2 bg-[#1abc9c]/10 border border-[#1abc9c]/30 text-[#1abc9c] text-sm px-4 py-3 rounded-lg">
                <i class="ph-fill ph-check-circle shrink-0"></i>
                <?php echo e(session('password_reset_success')); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form wire:submit="login" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-[#a0b3c2] mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph-bold ph-envelope text-[#a0b3c2]/60"></i>
                        </div>
                        <input wire:model="email" type="email" id="email"
                               class="block w-full pl-11 pr-4 py-2.5 bg-[#1c3448] border border-[#2a4a60] rounded-lg text-white placeholder-[#a0b3c2]/40 focus:outline-none focus:border-[#1abc9c] transition-colors sm:text-sm"
                               placeholder="admin@monitor.local">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1.5 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-[#a0b3c2]">Password</label>
                        <a href="<?php echo e(route('password.request')); ?>"
                           class="text-xs text-[#1abc9c] hover:text-[#16a085] transition-colors">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph-bold ph-lock text-[#a0b3c2]/60"></i>
                        </div>
                        <input wire:model="password" type="password" id="password"
                               class="block w-full pl-11 pr-4 py-2.5 bg-[#1c3448] border border-[#2a4a60] rounded-lg text-white placeholder-[#a0b3c2]/40 focus:outline-none focus:border-[#1abc9c] transition-colors sm:text-sm"
                               placeholder="••••••••">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1.5 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg text-sm font-semibold text-white bg-[#1abc9c] hover:bg-[#16a085] transition-colors shadow-sm">
                        <span wire:loading.remove>Sign In</span>
                        <span wire:loading class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin"></i> Authenticating...
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-[#2a4a60] pt-6">
                <p class="text-xs text-[#a0b3c2]/60">Authorized Personnel Only. System activity is monitored.</p>
            </div>
        </div>
        
    </div>
</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/login.blade.php ENDPATH**/ ?>