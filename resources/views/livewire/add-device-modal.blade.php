<div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity"
     x-data="{ open: @entangle('isOpen').live }"
     x-show="open"
     x-transition
     style="display: none;">
    <div class="bg-slate-800 border border-slate-700 w-full max-w-md rounded-xl shadow-2xl" @click.outside="open = false">
        <div class="p-6 border-b border-slate-700 flex justify-between items-center"><h3 class="text-lg font-bold text-white">Add New Monitor</h3><button wire:click="close" class="text-slate-400 hover:text-white"><i class="ph ph-x text-xl"></i></button></div>
        
        <form wire:submit="save">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Friendly Name</label>
                    <input wire:model="friendly_name" type="text" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2.5 text-sm text-white focus:ring-1 focus:ring-emerald-500 outline-none">
                    @error('friendly_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">IP Address</label>
                    <input wire:model="ip_address" type="text" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2.5 text-sm text-white focus:ring-1 focus:ring-emerald-500 outline-none">
                    @error('ip_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="p-6 border-t border-slate-700 flex justify-end gap-3">
                <button type="button" wire:click="close" class="px-4 py-2 text-sm text-slate-300 hover:text-white">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20">Start Monitoring</button>
            </div>
        </form>
    </div>
</div>
