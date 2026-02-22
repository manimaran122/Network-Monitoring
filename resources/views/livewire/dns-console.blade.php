<div class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 flex items-center justify-center p-4 transition-opacity duration-300"
     x-data="{ open: @entangle('isOpen').live }"
     x-show="open"
     x-transition
     style="display: none;">
    
    <div class="bg-slate-900 border border-slate-700 w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="h-14 bg-slate-950 border-b border-slate-800 flex justify-between items-center px-6">
            <div class="flex items-center gap-3"><div class="h-8 w-8 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center border border-blue-500/20"><i class="ph-bold ph-terminal-window"></i></div><h3 class="font-bold text-white tracking-wide">DNS Operations Console</h3></div>
            <button wire:click="close" class="text-slate-500 hover:text-white"><i class="ph-bold ph-x text-xl"></i></button>
        </div>
        <div class="flex flex-col md:flex-row h-full">
            <div class="w-full md:w-1/3 bg-slate-900 border-r border-slate-800 p-5 space-y-6">
                <div><label class="block text-xs font-bold text-slate-500 uppercase mb-2">Target</label><select class="w-full bg-slate-950 border border-slate-700 text-white text-sm rounded-lg px-3 py-2.5 outline-none"><option>{{ $target }}</option></select></div>
                <div class="space-y-2">
                    <button wire:click="runCommand('flush')" class="w-full text-left px-4 py-3 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-blue-500 transition-all group">
                        <span class="text-sm font-medium text-white group-hover:text-blue-400" wire:loading.class="opacity-50" wire:target="runCommand">
                            Flush DNS Cache
                        </span>
                    </button>
                    <button wire:click="runCommand('renew')" class="w-full text-left px-4 py-3 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-emerald-500 transition-all group">
                        <span class="text-sm font-medium text-white group-hover:text-emerald-400" wire:loading.class="opacity-50" wire:target="runCommand">
                            Renew DHCP Lease
                        </span>
                    </button>
                </div>
            </div>
            <div class="flex-1 bg-black p-5 flex flex-col font-mono text-sm overflow-hidden">
                <div class="flex items-center justify-between mb-2 pb-2 border-b border-white/10"><span class="text-xs text-slate-500">TERMINAL OUTPUT</span></div>
                <div id="terminal-logs" class="flex-1 overflow-y-auto space-y-1 text-slate-300" x-ref="logs">
                    @foreach($outputLog as $line)
                        <div class="{{ $line['class'] }}">{{ $line['text'] }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
