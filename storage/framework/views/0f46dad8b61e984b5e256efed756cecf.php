<div class="view-section fade-in space-y-6"
     x-data="{ 
        toasts: [],
        addToast(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 4000);
        }
     }"
     @user-management-toast.window="addToast($event.detail.message, $event.detail.type)">

    
    <div class="fixed top-24 right-8 z-[100] flex flex-col gap-3 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0 scale-90"
                 x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0 opacity-100 scale-100"
                 x-transition:leave-end="translate-x-full opacity-0 scale-90"
                 class="bg-slate-800 border-l-4 shadow-2xl flex items-center gap-4 px-6 py-4 rounded-xl pointer-events-auto min-w-[300px]"
                 :class="{
                    'border-emerald-500 bg-emerald-500/10': toast.type === 'success',
                    'border-blue-500 bg-blue-500/10': toast.type === 'info',
                    'border-red-500 bg-red-500/10': toast.type === 'error'
                 }">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                     :class="{
                        'bg-emerald-500/20 text-emerald-400': toast.type === 'success',
                        'bg-blue-500/20 text-blue-400': toast.type === 'info',
                        'bg-red-500/20 text-red-400': toast.type === 'error'
                     }">
                    <i class="ph ph-check-circle text-2xl" x-show="toast.type === 'success'"></i>
                    <i class="ph ph-info text-2xl" x-show="toast.type === 'info'"></i>
                    <i class="ph ph-warning-circle text-2xl" x-show="toast.type === 'error'"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-white" x-text="toast.message"></p>
                    <p class="text-[10px] text-slate-400" x-text="toast.type === 'error' ? 'Something went wrong' : 'Action successful'"></p>
                </div>
            </div>
        </template>
    </div>


    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('message')): ?>
    <div class="mb-4 flex items-center gap-2 bg-emerald-900/30 border border-emerald-500/30 text-emerald-300 text-sm px-4 py-3 rounded-lg">
        <i class="ph-fill ph-check-circle text-emerald-400"></i>
        <?php echo e(session('message')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-5">
        <div class="flex flex-wrap items-center gap-6">
            
            <div class="flex items-center gap-2 text-sm text-slate-400">
                <span>Show</span>
                <select wire:model.live="perPage" class="bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-white focus:border-blue-500 outline-none transition-all cursor-pointer">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entries</span>
            </div>

            <div class="h-4 w-px bg-slate-700 hidden md:block"></div>

            
            <div class="flex items-center gap-2">
                <label class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Role</label>
                <select wire:model.live="roleFilter" class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1 text-xs text-white focus:border-blue-500 outline-none transition-all cursor-pointer">
                    <option value="all">All Roles</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($roleOption->name); ?>"><?php echo e(ucwords($roleOption->name)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>

            
            <div class="flex items-center gap-2">
                <label class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Status</label>
                <select wire:model.live="statusFilter" class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1 text-xs text-white focus:border-blue-500 outline-none transition-all cursor-pointer">
                    <option value="all">Any Status</option>
                    <option value="active">Active</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500">
                    <i class="ph ph-magnifying-glass text-slate-500"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search user..." 
                       class="bg-slate-900/50 border border-slate-700 rounded-xl pl-10 pr-4 py-2 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 focus:bg-slate-900 outline-none transition-all w-64">
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_users')): ?>
            <button wire:click="openModal" 
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all shadow-lg shadow-blue-600/20 active:scale-95">
                <i class="ph-bold ph-plus"></i> Add User
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-lg">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-900/50 text-xs uppercase text-slate-500 font-bold">
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Role</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_users')): ?>
                    <th class="px-6 py-4">Status</th>
                    <?php endif; ?>
                    <th class="px-6 py-4">Last Login</th>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_users')): ?>
                    <th class="px-6 py-4 text-right">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-sm">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-800/80 transition-colors <?php echo e(!$user->status ? 'opacity-60' : ''); ?>">

                    
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img class="h-8 w-8 rounded-full bg-slate-700 shrink-0 <?php echo e(!$user->status ? 'grayscale' : ''); ?>"
                                 src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name)); ?>&background=334155&color=fff"
                                 alt="<?php echo e($user->name); ?>">
                            <div>
                                <div class="font-bold <?php echo e($user->status ? 'text-white' : 'text-slate-400'); ?>"><?php echo e($user->name); ?></div>
                                <div class="text-xs text-slate-400"><?php echo e($user->email); ?></div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'admin'): ?>
                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold px-2 py-0.5 rounded">ADMIN</span>
                        <?php else: ?>
                            <span class="bg-slate-700 text-slate-400 border border-slate-600 text-[10px] font-bold px-2 py-0.5 rounded">VIEWER</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_users')): ?>
                    <td class="px-6 py-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   class="sr-only peer"
                                   wire:click="toggleStatus(<?php echo e($user->id); ?>)"
                                   <?php echo e($user->status ? 'checked' : ''); ?>

                                   <?php echo e($user->id === auth()->id() ? 'disabled' : ''); ?>>
                            <div class="relative w-9 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer
                                        peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full
                                        peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px]
                                        after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full
                                        after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ms-2 text-xs font-medium <?php echo e($user->status ? 'text-emerald-400' : 'text-slate-500'); ?>">
                                <?php echo e($user->status ? 'Active' : 'Disabled'); ?>

                            </span>
                        </label>
                    </td>
                    <?php endif; ?>

                    <td class="px-6 py-4 text-slate-300">
                        <?php echo e($user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never'); ?>

                    </td>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_users')): ?>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-1">
                            <button type="button"
                                    wire:click="openModal(<?php echo e($user->id); ?>)"
                                    title="Edit user"
                                    class="text-slate-400 hover:text-blue-400 p-2 hover:bg-slate-700 rounded transition-colors group">
                                <i class="ph-bold ph-pencil-simple text-sm"></i>
                            </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->id !== auth()->id()): ?>
                            <button type="button"
                                    wire:click="confirmUserDeletion(<?php echo e($user->id); ?>)"
                                    title="Delete user"
                                    class="text-slate-500 hover:text-red-400 p-2 hover:bg-red-900/20 rounded transition-colors">
                                <i class="ph-bold ph-trash text-sm"></i>
                            </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">No users found.</td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-700">
            <?php echo e($users->links()); ?>

        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmingUserDeletion): ?>
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4 transition-all"
         wire:click.self="cancelDeletion"
         wire:key="delete-modal">
        <div class="bg-slate-800 border border-slate-700 w-full max-w-sm rounded-2xl shadow-2xl p-8 text-center fade-in"
             @click.stop>
            <div class="w-20 h-20 bg-red-500/10 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ph-bold ph-trash text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Delete User Account?</h3>
            <p class="text-sm text-slate-400 leading-relaxed mb-8">
                Are you sure? This will permanently remove the user and their access. This action cannot be undone.
            </p>
            <div class="flex gap-4">
                <button type="button"
                        wire:click="cancelDeletion" 
                        class="flex-1 px-4 py-3 text-sm font-bold text-slate-300 hover:text-white bg-slate-700 hover:bg-slate-600 rounded-xl transition-all">
                    Cancel
                </button>
                <button type="button"
                        wire:click="deleteUser" 
                        wire:loading.attr="disabled"
                        class="flex-1 px-4 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-500 rounded-xl shadow-lg shadow-red-600/30 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="deleteUser">Delete</span>
                    <span wire:loading wire:target="deleteUser">Deleting...</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isModalOpen): ?>
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-slate-800 border border-slate-700 w-full max-w-md rounded-2xl shadow-2xl"
             @click.stop>

            
            <div class="p-6 border-b border-slate-700 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center <?php echo e($editingUserId ? 'bg-blue-500/20' : 'bg-emerald-500/20'); ?>">
                        <i class="ph-bold <?php echo e($editingUserId ? 'ph-pencil-simple text-blue-400' : 'ph-user-plus text-emerald-400'); ?>"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white"><?php echo e($editingUserId ? 'Edit User' : 'Create New User'); ?></h3>
                </div>
                <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>

            
            <form wire:submit="save">
                <div class="p-6 space-y-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Full Name</label>
                        <input wire:model="name" type="text" placeholder="e.g. John Smith"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
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
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Email Address</label>
                        <input wire:model="email" type="email" placeholder="user@example.com"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
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
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Role</label>
                        <select wire:model="role"
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($roleOption->name); ?>"><?php echo e(ucwords($roleOption->name)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$editingUserId): ?>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">
                            Initial Password
                            <span class="ml-1 text-slate-500 font-normal">(user will be forced to change on first login)</span>
                        </label>
                        <input wire:model="password" type="password" placeholder="Minimum 8 characters"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>

                <div class="p-6 border-t border-slate-700 flex justify-end gap-3">
                    <button type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-bold rounded-lg transition-colors <?php echo e($editingUserId ? 'bg-blue-600 hover:bg-blue-500' : 'bg-emerald-600 hover:bg-emerald-500'); ?> text-white">
                        <span wire:loading.remove wire:target="save"><?php echo e($editingUserId ? 'Update User' : 'Create User'); ?></span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin"></i> Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/settings/user-management.blade.php ENDPATH**/ ?>