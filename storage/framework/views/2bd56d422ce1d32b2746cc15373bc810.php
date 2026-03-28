<div class="flex items-center justify-center h-screen w-screen bg-[#071421] p-4 md:p-8">
    <!-- Large Outer Container (Maintained same size as Login) -->
    <div class="w-full md:w-[90%] max-w-[1700px] h-[95vh] bg-[#0a1628] rounded-[24px] p-6 md:p-[40px] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.8)] flex items-center justify-start border border-[#16273f] overflow-hidden">
        
        <!-- Register Card -->
        <div class="w-full max-w-[450px] sm:w-[450px] bg-[#0d1d33] rounded-[16px] shadow-[0_10px_30px_-5px_rgba(0,0,0,0.7)] p-5 md:p-6 md:ml-[4%] border border-[#1d2f4a]">

            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#0b2235] border border-[#2a4a60] mb-2">
                    <i class="ph-fill ph-radar text-2xl text-[#1abc9c]"></i>
                </div>
                <h2 class="text-lg font-bold text-white tracking-tight">Monitoring Tool</h2>
                <p class="text-[#a0b3c2] text-[10px] mt-1">Create your account</p>
            </div>

            
            <div class="mb-4 p-1 bg-slate-800/60 rounded-2xl flex items-center">
                <a href="<?php echo e(route('login')); ?>" class="flex-1 py-1.5 px-4 text-center text-xs font-medium transition-all duration-300 text-slate-400 hover:text-white">
                    Sign In
                </a>
                <button type="button" class="flex-1 py-1.5 px-4 text-xs font-medium transition-all duration-300 bg-white/10 text-white rounded-xl">
                    Sign Up
                </button>
            </div>

            <form wire:submit="register" class="space-y-3">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph-bold ph-user text-slate-500"></i>
                        </div>
                        <input wire:model="name" type="text" id="name"
                               class="block w-full pl-11 pr-4 py-2 bg-slate-800 border border-[#2a4a60] rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#1abc9c] transition-colors sm:text-sm"
                               placeholder="John Doe">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph-bold ph-envelope text-slate-500"></i>
                        </div>
                        <input wire:model="email" type="email" id="email"
                               class="block w-full pl-11 pr-4 py-2 bg-slate-800 border border-[#2a4a60] rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#1abc9c] transition-colors sm:text-sm"
                               placeholder="john@example.com">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph-bold ph-lock text-slate-500"></i>
                        </div>
                        <input wire:model="password" type="password" id="password"
                               class="block w-full pl-11 pr-4 py-2 bg-slate-800 border border-[#2a4a60] rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#1abc9c] transition-colors sm:text-sm"
                               placeholder="••••••••">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph-bold ph-lock-key text-slate-500"></i>
                        </div>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation"
                               class="block w-full pl-11 pr-4 py-2 bg-slate-800 border border-[#2a4a60] rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#1abc9c] transition-colors sm:text-sm"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex justify-center items-center py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-[#1abc9c] hover:bg-[#16a085] transition-colors shadow-sm uppercase tracking-wide">
                        <span wire:loading.remove>Create Account</span>
                        <span wire:loading class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin"></i> Processing...
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center border-t border-[#1d2f4a] pt-3">
                <p class="text-[11px] text-slate-400">
                    Already have an account? 
                    <a href="<?php echo e(route('login')); ?>" class="text-[#1abc9c] font-medium hover:underline ml-1">Sign In</a>
                </p>
            </div>
        </div>
        
    </div>
</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/register.blade.php ENDPATH**/ ?>