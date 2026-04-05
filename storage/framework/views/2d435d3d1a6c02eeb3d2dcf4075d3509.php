<div class="view-section fade-in space-y-6">

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('message')): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold px-4 py-3 rounded-xl flex items-center gap-2">
        <i class="ph-fill ph-check-circle text-lg"></i> <?php echo e(session('message')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-1.5 flex flex-col md:flex-row justify-between items-center gap-3">
        <div class="flex bg-slate-900/50 rounded-lg p-1 w-full md:w-auto">
            <button wire:click="$set('statusFilter', 'all')"
                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all <?php echo e($statusFilter === 'all' ? 'text-white bg-slate-700 shadow-sm' : 'text-slate-400 hover:text-white'); ?>">
                All <span class="opacity-60">(<?php echo e($counts['all']); ?>)</span>
            </button>
            <button wire:click="$set('statusFilter', 'online')"
                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all <?php echo e($statusFilter === 'online' ? 'text-white bg-slate-700 shadow-sm' : 'text-slate-400 hover:text-white'); ?>">
                <span class="text-emerald-400">●</span> Online <span class="opacity-60">(<?php echo e($counts['online']); ?>)</span>
            </button>
            <button wire:click="$set('statusFilter', 'offline')"
                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all <?php echo e($statusFilter === 'offline' ? 'text-white bg-slate-700 shadow-sm' : 'text-slate-400 hover:text-white'); ?>">
                <span class="text-red-400">●</span> Offline <span class="opacity-60">(<?php echo e($counts['offline']); ?>)</span>
            </button>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <div class="relative flex-1 md:w-64">
                <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-500"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name or IP…"
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg pl-9 pr-3 py-2 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
            </div>
            <button x-data @click="$dispatch('open-add-monitor')"
                class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                <i class="ph-bold ph-plus"></i> <span class="hidden sm:inline">Add Monitor</span>
            </button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($selected) > 0): ?>
            <button wire:click="deleteSelected" wire:confirm="Delete <?php echo e(count($selected)); ?> selected monitors?"
                class="px-3 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-500 border border-red-500 rounded-lg flex items-center gap-1.5 transition-colors">
                <i class="ph-bold ph-trash"></i> Delete (<?php echo e(count($selected)); ?>)
            </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900/50 border-b border-slate-700 text-xs uppercase text-slate-500 font-bold tracking-wider">
                    <th class="px-5 py-4 w-10"><input type="checkbox" class="rounded bg-slate-700 border-slate-600 text-emerald-500 focus:ring-0"></th>
                    <th class="px-5 py-4">Monitor</th>
                    <th class="px-5 py-4">Connection</th>
                    <th class="px-5 py-4">Health</th>
                    <th class="px-5 py-4">Uptime (24h)</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/60 text-sm">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $monitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monitor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr wire:key="monitor-<?php echo e($monitor->id); ?>"
                    class="hover:bg-slate-700/20 transition-colors <?php echo e($monitor->status === 'offline' ? 'bg-red-500/5 border-l-2 border-l-red-500' : ''); ?>">

                    <td class="px-5 py-4">
                        <input type="checkbox" wire:model.live="selected" value="<?php echo e($monitor->id); ?>"
                            class="rounded bg-slate-700 border-slate-600 text-emerald-500 focus:ring-0">
                    </td>

                    
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-shrink-0">
                                <div class="h-10 w-10 rounded-xl <?php echo e($monitor->status === 'offline' ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20'); ?> flex items-center justify-center border">
                                    <i class="ph-bold <?php echo e($monitor->type === 'http' ? 'ph-globe' : ($monitor->type === 'port' ? 'ph-plug' : 'ph-router')); ?>"></i>
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-slate-800
                                    <?php echo e($monitor->status === 'online' ? 'bg-emerald-500' : ($monitor->status === 'offline' ? 'bg-red-500 animate-ping' : 'bg-amber-500')); ?>">
                                </span>
                            </div>
                            <div>
                                <div class="font-bold text-white"><?php echo e($monitor->name); ?></div>
                                <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                    <span class="uppercase font-mono bg-slate-700 px-1.5 py-px rounded text-[10px] border border-slate-600"><?php echo e($monitor->type ?? 'ping'); ?></span>
                                    <span class="text-slate-600"><?php echo e($monitor->group ?? 'default'); ?></span>
                                </div>
                            </div>
                        </div>
                    </td>

                    
                    <td class="px-5 py-4 font-mono text-xs text-slate-400">
                        <div class="text-slate-300"><?php echo e($monitor->ip_address); ?></div>
                        <div class="text-slate-600">:<?php echo e($monitor->port ?? 80); ?></div>
                    </td>

                    
                    <td class="px-5 py-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monitor->status === 'online'): ?>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Operational
                            </span>
                        <?php elseif($monitor->status === 'offline'): ?>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-red-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span> Unreachable
                            </span>
                        <?php elseif($monitor->status === 'maintenance'): ?>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-blue-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span> Maintenance
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span> <?php echo e(ucfirst($monitor->status)); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monitor->ssl_expiry): ?>
                        <div class="text-[10px] text-slate-500 mt-1">
                            <i class="ph-fill ph-lock-key text-emerald-500"></i>
                            SSL: <?php echo e(\Carbon\Carbon::parse($monitor->ssl_expiry)->diffInDays()); ?>d
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>

                    
                    <td class="px-5 py-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-slate-500">recent</span>
                            <span class="<?php echo e($monitor->uptime >= 99 ? 'text-emerald-400' : ($monitor->uptime >= 90 ? 'text-amber-400' : 'text-red-400')); ?> font-bold"><?php echo e($monitor->uptime); ?>%</span>
                        </div>
                        <div class="flex items-end gap-px h-6">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $monitor->recentMetrics->reverse(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // Green: ≤200ms, no loss | Amber: 200–500ms | Red: >500ms or packet loss
                                $barClass = $metric->packet_loss > 0 || $metric->latency > 500
                                    ? 'bg-red-500 h-3'
                                    : ($metric->latency > 200
                                        ? 'bg-amber-400 h-4'
                                        : 'bg-emerald-500 h-6');
                            ?>
                            <div class="w-2 rounded-sm <?php echo e($barClass); ?>"
                                title="<?php echo e($metric->created_at->format('H:i')); ?> — <?php echo e($metric->latency); ?>ms / <?php echo e($metric->packet_loss); ?>% loss">
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < (20 - $monitor->recentMetrics->count()); $i++): ?>
                            <div class="w-2 h-2 bg-slate-700/30 rounded-sm"></div>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="text-[10px] text-slate-600 mt-1">avg <span class="<?php echo e($monitor->latency > 500 ? 'text-red-400' : ($monitor->latency > 200 ? 'text-amber-400' : 'text-blue-400')); ?> font-bold"><?php echo e(number_format($monitor->latency)); ?>ms</span></div>
                    </td>

                    
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="<?php echo e(route('monitor.details', $monitor->id)); ?>" wire:navigate
                                class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-700 rounded-lg transition-colors" title="View Details">
                                <i class="ph-bold ph-eye"></i>
                            </a>
                            <button wire:click="editMonitor(<?php echo e($monitor->id); ?>)"
                                class="p-2 text-slate-400 hover:text-amber-400 hover:bg-slate-700 rounded-lg transition-colors" title="Edit Monitor">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>
                            <button wire:click="delete(<?php echo e($monitor->id); ?>)" wire:confirm="Delete '<?php echo e($monitor->name); ?>'? This cannot be undone."
                                class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-700 rounded-lg transition-colors" title="Delete">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-14 text-center text-slate-500">
                        <i class="ph ph-monitor text-5xl block mb-2 opacity-20"></i>
                        <p class="font-semibold text-slate-400">No monitors found.</p>
                        <p class="text-xs mt-1">Try adjusting your search or add a new monitor.</p>
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <div><?php echo e($monitors->links()); ?></div>


    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showEditModal): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-on:keydown.escape.window="$wire.cancelEdit()">

        
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" wire:click="cancelEdit"></div>

        
        <div class="relative bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl w-full max-w-xl z-10 max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="ph-fill ph-pencil-simple text-amber-400"></i>
                    Edit Monitor
                </h2>
                <button wire:click="cancelEdit" class="text-slate-500 hover:text-white p-1 hover:bg-slate-700 rounded-lg transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            
            <div class="p-6 space-y-5">

                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Monitor Name</label>
                    <input wire:model="editName" type="text" placeholder="e.g. Production Server"
                        class="w-full bg-slate-900 border <?php echo e($errors->has('editName') ? 'border-red-500' : 'border-slate-700'); ?> rounded-xl px-4 py-2.5 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Monitor Type</label>
                        <select wire:model="editType"
                            class="w-full bg-slate-900 border <?php echo e($errors->has('editType') ? 'border-red-500' : 'border-slate-700'); ?> rounded-xl px-4 py-2.5 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
                            <option value="ping">Ping (ICMP)</option>
                            <option value="http">HTTP / HTTPS</option>
                            <option value="port">TCP Port</option>
                            <option value="keyword">Keyword Check</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">IP / Hostname</label>
                        <input wire:model="editIp" type="text" placeholder="192.168.1.1"
                            class="w-full bg-slate-900 border <?php echo e($errors->has('editIp') ? 'border-red-500' : 'border-slate-700'); ?> rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:border-emerald-500 outline-none transition-colors">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editIp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Port</label>
                        <input wire:model="editPort" type="number" min="1" max="65535"
                            class="w-full bg-slate-900 border <?php echo e($errors->has('editPort') ? 'border-red-500' : 'border-slate-700'); ?> rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:border-emerald-500 outline-none transition-colors">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editPort'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Check Every (sec)</label>
                        <input wire:model="editInterval" type="number" min="30" step="30"
                            class="w-full bg-slate-900 border <?php echo e($errors->has('editInterval') ? 'border-red-500' : 'border-slate-700'); ?> rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:border-emerald-500 outline-none transition-colors">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editInterval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Group</label>
                    <input wire:model="editGroup" type="text" placeholder="default"
                        class="w-full bg-slate-900 border <?php echo e($errors->has('editGroup') ? 'border-red-500' : 'border-slate-700'); ?> rounded-xl px-4 py-2.5 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editGroup'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Notifications</label>
                    <div class="grid grid-cols-2 gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                            ['editEmail', 'Email', 'ph-envelope'],
                            ['editSms',   'SMS',   'ph-chat-circle-text'],
                            ['editVoice', 'Voice Call', 'ph-phone'],
                            ['editPush',  'Push',  'ph-bell'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$prop, $label, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center gap-3 bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 cursor-pointer hover:border-slate-600 transition-colors">
                            <input type="checkbox" wire:model="<?php echo e($prop); ?>" class="rounded bg-slate-700 border-slate-600 text-emerald-500 focus:ring-0 focus:ring-offset-0">
                            <span class="flex items-center gap-2 text-sm text-slate-300">
                                <i class="ph-fill <?php echo e($icon); ?> text-slate-500"></i> <?php echo e($label); ?>

                            </span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-700 bg-slate-900/40">
                <button wire:click="cancelEdit"
                    class="px-5 py-2.5 text-sm font-semibold text-slate-400 hover:text-white bg-slate-700 hover:bg-slate-600 border border-slate-600 rounded-xl transition-colors">
                    Cancel
                </button>
                <button wire:click="updateMonitor" wire:loading.attr="disabled"
                    class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl transition-colors shadow-lg shadow-emerald-500/20 flex items-center gap-2 disabled:opacity-60">
                    <span wire:loading.remove wire:target="updateMonitor"><i class="ph-bold ph-floppy-disk"></i> Save Changes</span>
                    <span wire:loading wire:target="updateMonitor"><i class="ph ph-spinner animate-spin"></i> Saving…</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/monitors/index.blade.php ENDPATH**/ ?>