<div class="view-section fade-in" wire:poll.10s>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="w-16 h-16 rounded-full <?php echo e($monitor->status == 'online' ? 'bg-emerald-500' : 'bg-red-500'); ?> flex items-center justify-center text-white text-3xl shadow-lg shadow-emerald-500/20">
                    <i class="ph-fill <?php echo e($monitor->status == 'online' ? 'ph-check' : 'ph-x'); ?>"></i>
                </div>
                <div class="absolute -bottom-1 -right-1 bg-slate-900 rounded-full p-1">
                    <div class="w-4 h-4 rounded-full <?php echo e($monitor->status == 'online' ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'); ?>"></div>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                    PING <?php echo e($monitor->ip_address); ?> 
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monitor->name): ?> <span class="text-slate-500 text-lg font-normal">(<?php echo e($monitor->name); ?>)</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </h1>
                <p class="text-slate-400">Ping monitor for <?php echo e($monitor->ip_address); ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            
            <button wire:click="recheckNow"
                wire:loading.attr="disabled"
                wire:target="recheckNow"
                class="bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide border border-emerald-500/40 transition-colors flex items-center gap-2">
                <span wire:loading.remove wire:target="recheckNow"><i class="ph-bold ph-arrows-clockwise"></i></span>
                <span wire:loading wire:target="recheckNow"><i class="ph-bold ph-spinner animate-spin"></i></span>
                <span wire:loading.remove wire:target="recheckNow">Re-check Now</span>
                <span wire:loading wire:target="recheckNow">Checking…</span>
            </button>
            <button wire:click="testNotification" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide border border-slate-700 transition-colors flex items-center gap-2">
                <i class="ph-bold ph-bell-ringing"></i> Test Notification
            </button>
            <button wire:click="togglePause" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide border border-slate-700 transition-colors flex items-center gap-2">
                <i class="ph-bold <?php echo e($monitor->status === 'maintenance' ? 'ph-play' : 'ph-pause'); ?>"></i> <?php echo e($monitor->status === 'maintenance' ? 'Resume' : 'Pause'); ?>

            </button>
            <button class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide border border-slate-700 transition-colors flex items-center gap-2">
                <i class="ph-bold ph-pencil-simple"></i> Edit
            </button>
            <button class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-2 py-2 rounded-lg border border-slate-700 transition-colors">
                <i class="ph-bold ph-dots-three-vertical"></i>
            </button>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('recheck')): ?>
    <div class="mb-4 flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold px-4 py-3 rounded-xl">
        <i class="ph-fill ph-check-circle text-lg"></i>
        <?php echo e(session('recheck')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <div class="lg:col-span-3 space-y-6">
            
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg">
                    <h3 class="text-sm font-bold text-slate-300 mb-1">Current status</h3>
                    <div class="text-2xl font-bold <?php echo e($monitor->status == 'online' ? 'text-emerald-500' : ($monitor->status == 'maintenance' ? 'text-amber-500' : 'text-red-500')); ?> mb-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monitor->status == 'online'): ?> Up <?php elseif($monitor->status == 'maintenance'): ?> Paused <?php else: ?> Down <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="text-xs text-slate-400">Currently <?php echo e($monitor->status == 'online' ? 'up' : 'down'); ?> for <?php echo e($monitor->uptime); ?>% (est)</div>
                </div>

                
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg">
                    <h3 class="text-sm font-bold text-slate-300 mb-1">Last check</h3>
                    <div class="text-2xl font-bold text-white mb-1">
                        <?php echo e($monitor->updated_at->diffForHumans(['short' => true])); ?>

                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <span>Checked every 5m</span>
                        <a href="#" class="text-amber-500 hover:text-amber-400 flex items-center gap-1"><i class="ph-fill ph-lock-key"></i> Get 60 sec. checks</a>
                    </div>
                </div>

                
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-sm font-bold text-slate-300">Last 24 hours</h3>
                        <span class="text-emerald-500 font-bold text-sm"><?php echo e($uptime24h); ?>%</span>
                    </div>
                    <div class="flex items-end gap-[3px] h-8 w-full" title="Hourly Average Latency/Loss">
                         <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $barData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex-1 rounded-sm h-full <?php echo e($bar->avg_loss > 0 ? 'bg-red-500' : 'bg-emerald-500'); ?> opacity-<?php echo e(min(100, max(40, $bar->avg_latency * 2))); ?>" 
                                 title="<?php echo e(\Carbon\Carbon::parse($bar->hour)->format('H:i')); ?> - <?php echo e(number_format($bar->avg_latency)); ?>ms - <?php echo e(number_format($bar->avg_loss)); ?>% Loss"></div>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                         
                         <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < (24 - count($barData)); $i++): ?>
                             <div class="flex-1 bg-slate-700/30 rounded-sm h-full"></div>
                         <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="text-xs text-slate-400 mt-2">0 incidents, 0m down</div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg">
                 
                <div>
                     <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Last 7 days</h3>
                     <div class="text-xl font-bold text-emerald-500"><?php echo e($uptime7d); ?>%</div>
                     <div class="text-xs text-slate-500">0 incidents, 0m down</div>
                </div>
                 
                <div class="md:border-l md:border-slate-700 md:pl-6">
                     <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Last 30 days</h3>
                     <div class="text-xl font-bold text-emerald-500"><?php echo e($uptime30d); ?>%</div>
                     <div class="text-xs text-slate-500">0 incidents, 0m down</div>
                </div>
                 
                <div class="md:border-l md:border-slate-700 md:pl-6">
                     <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Last 365 days</h3>
                     <div class="text-xl font-bold text-slate-600">--.--%</div>
                </div>
                
                <div class="md:border-l md:border-slate-700 md:pl-6 flex items-center relative" x-data="{ pickerOpen: false }" @click.away="pickerOpen = false">
                     <button @click="pickerOpen = !pickerOpen" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-300 flex items-center justify-between hover:border-slate-600 transition-colors">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-calendar-blank"></i> 
                            <span><?php echo e($customDateStart && $customDateEnd ? "$customDateStart - $customDateEnd" : "Pick a date range"); ?></span>
                        </div>
                        <i class="ph-bold ph-caret-down"></i>
                     </button>

                     
                     <div x-show="pickerOpen" 
                          style="display: none;"
                          x-transition
                          class="absolute top-full right-0 mt-2 z-50 bg-slate-900 border border-slate-700 rounded-xl shadow-2xl w-[600px] p-6">
                        
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-white">Pick a date range<span class="text-emerald-500">.</span></h3>
                        </div>

                        
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['this_week' => 'This week', 'last_week' => 'Last week', 'this_month' => 'This month', 'last_month' => 'Last month', 'entire_history' => 'Entire history']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button wire:click="setPreset('<?php echo e($key); ?>')" @click="pickerOpen = false" class="px-2 py-1.5 text-[10px] font-bold rounded bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white border border-slate-700 transition-all">
                                <?php echo e($label); ?>

                            </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <div class="grid grid-cols-3 gap-3 mb-6 text-center text-slate-500 text-[10px]">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 3; $i++): ?>
                            <div class="p-2 border border-slate-800 rounded bg-slate-950/50">
                                <div class="font-bold mb-2 text-slate-300"><?php echo e(now()->subMonths(2-$i)->format('F Y')); ?></div>
                                <div class="grid grid-cols-7 gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['M','T','W','T','F','S','S']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <span class="text-slate-600"><?php echo e($d); ?></span> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($d=1; $d<=30; $d++): ?> <span class="hover:bg-slate-700 rounded cursor-pointer"><?php echo e($d); ?></span> <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <div class="flex-1 w-full">
                                <input wire:model.blur="customDateStart" type="text" placeholder="DD/MM/YYYY (Start)" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:border-emerald-500 outline-none font-mono text-xs">
                            </div>
                            <div class="text-slate-500">-</div>
                            <div class="flex-1 w-full">
                                <input wire:model.blur="customDateEnd" type="text" placeholder="DD/MM/YYYY (End)" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-white focus:border-emerald-500 outline-none font-mono text-xs">
                            </div>
                            <button @click="pickerOpen = false" class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2 rounded-lg font-bold text-xs shadow-lg shadow-primary-500/20 whitespace-nowrap">Apply</button>
                        </div>

                     </div>
                </div>
            </div>

            
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-white">Response time<span class="text-emerald-500">.</span></h3>
                        <p class="text-xs text-slate-500 mt-0.5">Latency per check — <?php echo e($metrics->count()); ?> data points</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 text-xs text-slate-400 bg-slate-900/50 px-2 py-1 rounded border border-slate-700/50">
                            <i class="ph-fill ph-lock-key text-amber-500"></i>
                            <span class="text-emerald-500 cursor-pointer hover:underline">Setup alerts</span> for slow response times
                        </div>
                        <select wire:model.live="timeframe" class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:border-emerald-500 outline-none">
                            <option value="1">Last hour</option>
                            <option value="6">Last 6 hours</option>
                            <option value="24">Last 24 hours</option>
                            <option value="168">Last 7 days</option>
                            <option value="720">Last 30 days</option>
                        </select>
                    </div>
                </div>

                
                <div class="relative h-64 w-full"
                     x-data="{
                         chart: null,
                         labels: <?php echo e(json_encode($chartLabels)); ?>,
                         values: <?php echo e(json_encode($chartData)); ?>,
                         avgVal: <?php echo e($avgLatency); ?>,
                         init() {
                             this.$nextTick(() => this.buildChart());
                             Livewire.on('chart-update', (payload) => {
                                 const d = Array.isArray(payload) ? payload[0] : payload;
                                 if (!d) return;
                                 this.labels = d.labels;
                                 this.values = d.data;
                                 this.avgVal  = d.avg;
                                 if (this.chart) {
                                     this.chart.data.labels = d.labels;
                                     this.chart.data.datasets[0].data = d.data;
                                     this.chart.update('active');
                                 }
                             });
                         },
                         buildChart() {
                             if (this.chart) { this.chart.destroy(); this.chart = null; }
                             const canvas = this.$refs.canvas;
                             if (!canvas) return;
                             const ctx = canvas.getContext('2d');
                             const gradient = ctx.createLinearGradient(0, 0, 0, 256);
                             gradient.addColorStop(0, 'rgba(16,185,129,0.18)');
                             gradient.addColorStop(1, 'rgba(16,185,129,0)');
                             this.chart = new Chart(ctx, {
                                 type: 'line',
                                 data: {
                                     labels: this.labels,
                                     datasets: [{
                                         label: 'Response Time (ms)',
                                         data: this.values,
                                         borderColor: '#10b981',
                                         backgroundColor: gradient,
                                         borderWidth: 2,
                                         pointRadius: this.values.length > 60 ? 0 : 3,
                                         pointHoverRadius: 6,
                                         pointBackgroundColor: '#10b981',
                                         pointBorderColor: '#0f172a',
                                         pointBorderWidth: 2,
                                         fill: true,
                                         tension: 0.35,
                                         spanGaps: true
                                     }]
                                 },
                                 options: {
                                     responsive: true,
                                     maintainAspectRatio: false,
                                     animation: { duration: 400, easing: 'easeInOutQuart' },
                                     plugins: {
                                         legend: { display: false },
                                         tooltip: {
                                             mode: 'index',
                                             intersect: false,
                                             backgroundColor: '#1e293b',
                                             titleColor: '#94a3b8',
                                             bodyColor: '#f1f5f9',
                                             borderColor: '#334155',
                                             borderWidth: 1,
                                             callbacks: { label: ctx => ' ' + ctx.parsed.y.toFixed(1) + ' ms' }
                                         }
                                     },
                                     scales: {
                                         x: {
                                            display: true,
                                            grid: { display: false },
                                            ticks: { color: '#475569', maxTicksLimit: 8, font: { size: 10, family: 'Roboto Mono' } }
                                         },
                                         y: {
                                             display: true,
                                             grid: { color: 'rgba(51,65,85,0.4)' },
                                             ticks: { color: '#475569', font: { size: 10 }, callback: v => v + ' ms' },
                                             beginAtZero: true
                                         }
                                     },
                                     interaction: { mode: 'nearest', axis: 'x', intersect: false }
                                 }
                             });
                         }
                     }"
                     x-init="init()"
                >
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($metrics->isEmpty()): ?>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-500">
                        <i class="ph ph-chart-line text-4xl mb-2 opacity-30"></i>
                        <p class="text-sm">No data for this timeframe yet.</p>
                        <p class="text-xs text-slate-600 mt-1">Run <code class="bg-slate-700 px-1 rounded">monitor:check</code> to collect metrics.</p>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <canvas x-ref="canvas" wire:ignore></canvas>
                </div>

                
                <div class="grid grid-cols-3 gap-6 mt-6 pt-5 border-t border-slate-700/50">
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-2 text-2xl font-bold text-white">
                            <?php echo e(number_format($avgLatency, 1)); ?><span class="text-sm text-slate-500 font-medium">ms</span>
                        </div>
                        <div class="text-xs text-slate-400 mt-1">Average</div>
                    </div>
                    <div class="text-center border-x border-slate-700">
                        <div class="flex items-center justify-center gap-2 text-2xl font-bold text-emerald-400">
                            <?php echo e(number_format($minLatency, 1)); ?><span class="text-sm text-slate-500 font-medium">ms</span>
                        </div>
                        <div class="text-xs text-slate-400 mt-1">Minimum</div>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-2 text-2xl font-bold text-red-400">
                            <?php echo e(number_format($maxLatency, 1)); ?><span class="text-sm text-slate-500 font-medium">ms</span>
                        </div>
                        <div class="text-xs text-slate-400 mt-1">Maximum</div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="space-y-6">
             
             <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-bold text-slate-300">Next maintenance<span class="text-emerald-500">.</span></h3>
                    <i class="ph-bold ph-gear text-slate-600 hover:text-white cursor-pointer"></i>
                </div>
                <div class="text-center py-4">
                    <div class="text-sm text-slate-400 mb-4">No maintenance planned.</div>
                    <button class="bg-slate-900 hover:bg-slate-950 text-white text-xs font-bold py-2 px-4 rounded-lg border border-slate-700 transition-colors w-full">
                        Set up maintenance
                    </button>
                </div>
             </div>

             
             <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg">
                <h3 class="text-sm font-bold text-slate-300 mb-4">Regions<span class="text-emerald-500">.</span></h3>
                <div class="relative h-32 w-full bg-slate-900 rounded-lg flex items-center justify-center border border-slate-700/50 overflow-hidden group">
                     
                    <svg viewBox="0 0 100 60" class="w-full h-full text-slate-700 fill-current opacity-30">
                        <path d="M20,10 Q30,5 40,15 T60,20 T80,10 V50 H20 Z" />
                    </svg>
                    <div class="absolute top-[30%] left-[25%]">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                    </div>
                </div>
                <div class="mt-4 font-bold text-white text-center">North America</div>
             </div>

             
             <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-bold text-slate-300">To be notified<span class="text-emerald-500">.</span></h3>
                     <i class="ph-bold ph-gear text-slate-600 hover:text-white cursor-pointer"></i>
                </div>
                <div class="flex flex-wrap gap-2">
                    <div class="h-8 w-8 rounded-full bg-primary-600 text-white font-bold text-xs flex items-center justify-center border border-primary-400" title="<?php echo e(auth()->user()->name); ?>">
                        <?php echo e(auth()->user()->initials()); ?>

                    </div>
                     
                    <button class="h-8 w-8 rounded-full bg-slate-700 border border-slate-600 text-slate-400 flex items-center justify-center hover:text-white hover:bg-slate-600 transition-colors">
                        <i class="ph-bold ph-plus"></i>
                    </button>
                </div>
             </div>

             
             <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg">
                <h3 class="text-sm font-bold text-slate-300 mb-4">Appears on<span class="text-emerald-500">.</span></h3>
                
             </div>
        </div>
    </div>

    
</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/monitor-details.blade.php ENDPATH**/ ?>