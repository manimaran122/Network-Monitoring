<div class="fixed inset-0 flex items-center justify-center bg-[#071421] overflow-hidden z-[100]">
    <!-- Large Outer Container -->
    <div class="w-full md:w-[90%] max-w-[1700px] h-[95vh] bg-[#0a1628] rounded-[24px] p-6 md:p-[40px] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.8)] flex items-center justify-start border border-[#16273f] overflow-hidden">
        
        <!-- Login Card -->
        <div class="w-full max-w-[450px] sm:w-[450px] bg-[#0d1d33] rounded-[16px] shadow-[0_10px_30px_-5px_rgba(0,0,0,0.7)] border border-[#1d2f4a] md:ml-[4%] flex flex-col overflow-hidden">
            
            
            <div class="flex border-b border-[#1d2f4a] bg-[#0b1a2e]">
                <div class="flex-1 py-4 text-center text-xs font-bold text-[#1abc9c] bg-[#0d1d33] uppercase tracking-widest relative overflow-hidden">
                    Sign In
                    <div class="absolute bottom-0 left-0 w-full h-0.5 bg-[#1abc9c]"></div>
                </div>
                <a href="<?php echo e(route('register')); ?>" class="flex-1 py-4 text-center text-xs font-bold transition-all duration-300 text-slate-500 bg-[#0b1a2e] hover:bg-[#0d1d33] hover:text-white uppercase tracking-widest relative overflow-hidden border-l border-[#1d2f4a]">
                    Sign Up
                </a>
            </div>

            <div class="p-6 md:p-8">

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('password_reset_success')): ?>
            <div class="mb-5 flex items-center gap-2 bg-[#1abc9c]/10 border border-[#1abc9c]/30 text-[#1abc9c] text-sm px-4 py-3 rounded-lg">
                <i class="ph-fill ph-check-circle shrink-0"></i>
                <?php echo e(session('password_reset_success')); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form wire:submit="login" class="space-y-5">
                <div>
                    <label for="email" class="block text-[11px] font-bold text-[#1abc9c] uppercase tracking-wider mb-2 ms-0.5">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph-bold ph-envelope text-[#a0b3c2]/40 group-focus-within:text-[#1abc9c] transition-colors"></i>
                        </div>
                        <input wire:model="email" type="email" id="email"
                               class="block w-full pl-12 pr-4 py-3 bg-[#0b1a2e] border border-[#2a4a60] rounded-xl text-white placeholder-[#a0b3c2]/20 focus:outline-none focus:border-[#1abc9c] focus:ring-1 focus:ring-[#1abc9c]/30 transition-all sm:text-sm shadow-inner"
                               placeholder="admin@monitor.local">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-2 block ps-1 font-medium"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2 ms-0.5">
                        <label for="password" class="block text-[11px] font-bold text-[#1abc9c] uppercase tracking-wider">Password</label>
                        <a href="<?php echo e(route('password.request')); ?>"
                           class="text-[10px] font-bold text-[#a0b3c2]/60 hover:text-[#1abc9c] uppercase tracking-tighter transition-colors">
                            Forgot?
                        </a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph-bold ph-lock text-[#a0b3c2]/40 group-focus-within:text-[#1abc9c] transition-colors"></i>
                        </div>
                        <input wire:model="password" type="password" id="password"
                               class="block w-full pl-12 pr-4 py-3 bg-[#0b1a2e] border border-[#2a4a60] rounded-xl text-white placeholder-[#a0b3c2]/20 focus:outline-none focus:border-[#1abc9c] focus:ring-1 focus:ring-[#1abc9c]/30 transition-all sm:text-sm shadow-inner"
                               placeholder="••••••••">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-2 block ps-1 font-medium"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="pt-3">
                    <button type="submit"
                            class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-[#1abc9c] hover:bg-[#16a085] transition-all shadow-[0_0_20px_-5px_rgba(26,188,156,0.3)] hover:shadow-[0_0_25px_-5px_rgba(26,188,156,0.5)] active:scale-[0.98] uppercase tracking-widest">
                        <span wire:loading.remove>Sign In Now</span>
                        <span wire:loading class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin"></i> Authenticating...
                        </span>
                    </button>
                </div>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center px-2">
                        <div class="w-full border-t border-[#2a4a60]/50"></div>
                    </div>
                    <div class="relative flex justify-center text-[10px] uppercase font-bold tracking-tighter">
                        <span class="bg-[#0d1d33] px-3 text-[#a0b3c2]/30">Secured Gateway</span>
                    </div>
                </div>

                <div>
                    <a href="<?php echo e(route('auth.google')); ?>"
                       class="w-full flex justify-center items-center gap-3 py-3 px-4 rounded-xl text-xs font-bold text-white bg-transparent hover:bg-[#1c3448] border border-[#2a4a60] transition-all hover:border-[#1abc9c]/50 group">
                        <i class="ph-bold ph-google-logo text-lg text-red-500 group-hover:scale-110 transition-transform"></i>
                        <span class="uppercase tracking-widest text-slate-300">Continue with Google</span>
                    </a>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-[#2a4a60] pt-6">
                <p class="text-xs text-[#a0b3c2]/60">Authorized Personnel Only. System activity is monitored.</p>
            </div>
        </div>
        
    </div>
</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/login.blade.php ENDPATH**/ ?>