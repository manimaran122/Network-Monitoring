<div class="bg-gradient-to-br from-indigo-900/40 via-slate-800 to-slate-900 border border-indigo-500/30 rounded-xl p-6 shadow-xl relative overflow-hidden backdrop-blur-sm">
    <!-- Background Effect -->
    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
        <i class="ph-fill ph-brain text-9xl text-indigo-500 animate-pulse"></i>
    </div>
    
    <div class="relative z-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="bg-indigo-500/20 text-indigo-400 p-1.5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813a3.75 3.75 0 002.576-2.576l.813-2.846A.75.75 0 019 4.5zM6 20.25a.75.75 0 01.75.75v.75h.75a.75.75 0 010 1.5h-.75v.75a.75.75 0 01-1.5 0v-.75h-.75a.75.75 0 010-1.5h.75v-.75a.75.75 0 01.75-.75zM6 2.25a.75.75 0 01.75.75v.75h.75a.75.75 0 010 1.5h-.75v.75a.75.75 0 01-1.5 0v-.75h-.75a.75.75 0 010-1.5h.75v-.75a.75.75 0 01.75-.75zM20.25 18a.75.75 0 01.75.75v.75h.75a.75.75 0 010 1.5h-.75v.75a.75.75 0 01-1.5 0v-.75h-.75a.75.75 0 010-1.5h.75v-.75a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    System Intelligence
                </h2>
                <p class="text-slate-400 text-sm mt-1 ml-1">AI-powered analysis of your monitoring infrastructure.</p>
            </div>
            
            <button wire:click="analyze" wire:loading.attr="disabled" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-5 rounded-lg flex items-center gap-2 transition-all shadow-lg shadow-indigo-500/20 disabled:opacity-50 disabled:cursor-not-allowed border border-indigo-400/20 group">
                <span wire:loading.remove wire:target="analyze" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 group-hover:animate-pulse">
                        <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 01.359.852L12.982 9.75h7.268a.75.75 0 01.548 1.262l-10.5 11.25a.75.75 0 01-1.272-.71l1.992-7.302H3.75a.75.75 0 01-.548-1.262l10.5-11.25a.75.75 0 01.913-.143z" clip-rule="evenodd" />
                    </svg>
                    Analyze Now
                </span>
                <span wire:loading wire:target="analyze" class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>

        <div class="transition-all duration-500 ease-in-out mt-6">
            <!-- Loading State -->
            <div wire:loading wire:target="analyze" class="w-full">
                <div class="bg-indigo-900/20 rounded-lg p-12 border border-indigo-500/20 text-center border-dashed flex flex-col items-center justify-center animate-pulse">
                    <div class="mb-4 text-indigo-400">
                        <svg class="animate-spin h-10 w-10 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-indigo-300 font-medium text-lg">Analyzing telemetry data with Gemini AI...</p>
                    <p class="text-indigo-400/60 text-sm mt-2">Checking 50 monitors for anomalies, latency trends, and health scores.</p>
                </div>
            </div>

            <!-- Result or Initial State -->
            <div wire:loading.remove wire:target="analyze">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($analysis): ?>
                    <div class="bg-slate-900/80 rounded-lg p-6 border border-slate-700/50 prose prose-invert prose-sm max-w-none shadow-inner">
                        <div class="flex items-center gap-2 mb-4 text-emerald-400 text-xs font-bold uppercase tracking-wider border-b border-slate-700/50 pb-3">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> Analysis Complete
                            <span class="ml-auto text-slate-500 text-[10px]"><?php echo e(now()->format('H:i:s')); ?></span>
                        </div>
                        <div class="text-slate-300 leading-relaxed">
                            <?php echo $analysis; ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-slate-800/30 rounded-lg p-10 border border-slate-700/30 text-center border-dashed hover:border-indigo-500/30 transition-colors group cursor-pointer" wire:click="analyze">
                        <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-slate-700 transition-colors">
                            <i class="ph-duotone ph-magic-wand text-3xl text-indigo-400 group-hover:scale-110 transition-transform"></i>
                        </div>
                        <h3 class="text-white font-medium mb-1">Unlock AI Insights</h3>
                        <p class="text-slate-500 text-sm max-w-md mx-auto">
                            Use our advanced AI to scan your entire monitoring infrastructure for hidden issues, 
                            performance bottlenecks, and optimization opportunities.
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\laravel project\Network-Monitoring\resources\views/livewire/ai-insight.blade.php ENDPATH**/ ?>