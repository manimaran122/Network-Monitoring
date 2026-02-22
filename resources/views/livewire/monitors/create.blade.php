<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white tracking-tight">Add single monitor<span class="text-primary-500">.</span></h1>
    </div>

    <form wire:submit="save">
        {{-- Monitor Type --}}
        <div class="mb-8">
            <label class="block text-sm font-bold text-slate-300 mb-3">Monitor type</label>
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center justify-between cursor-pointer hover:border-slate-600 transition-colors shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                        <i class="ph-fill ph-target text-2xl text-emerald-500"></i>
                    </div>
                    <div>
                        <div class="font-bold text-white text-lg">Ping monitoring</div>
                        <div class="text-sm text-slate-400">Make sure your server or any device in the network is always available.</div>
                    </div>
                </div>
                <i class="ph-bold ph-caret-down text-slate-500"></i>
            </div>
            @error('monitorType') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- IP or Host --}}
        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-300 mb-3">IP or host to monitor</label>
            <input wire:model="ip_address" type="text" placeholder="E.g. 80.75.11.12 or example.com" 
                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all placeholder-slate-600 shadow-sm">
             @error('ip_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Friendly Name --}}
        <div class="mb-8">
             <div class="flex items-center gap-3 mb-3">
                <label class="block text-sm font-bold text-slate-300">Friendly name: <span class="text-white">{{ $name ?: 'PING' }}</span></label>
                <div class="h-4 w-px bg-slate-700"></div>
                <button type="button" class="text-xs bg-slate-800 hover:bg-slate-700 px-3 py-1.5 rounded-md border border-slate-700 text-slate-300 transition-colors flex items-center gap-1.5 font-medium">
                    <i class="ph-bold ph-pencil-simple"></i> Rename
                </button>
             </div>
             <input wire:model="name" type="text" placeholder="Friendly Name" 
                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all placeholder-slate-600 shadow-sm">
             @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Group & Tags --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            {{-- Group --}}
            <div>
                 <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-bold text-slate-300">Group</label>
                 </div>
                 <div class="text-xs text-slate-500 mb-2">Your monitor will be automatically added to the chosen group</div>
                 <div class="relative">
                    <select wire:model="group" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-primary-500 outline-none appearance-none shadow-sm cursor-pointer">
                        <option value="default">Monitors (default)</option>
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                 </div>
            </div>

            {{-- Tags --}}
            <div>
                <label class="block text-sm font-bold text-slate-300 mb-2">Add tags</label>
                <div class="text-xs text-slate-500 mb-2">Tags will enable you to organise your monitors in a better way</div>
                <div class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 flex flex-wrap items-center gap-2 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all min-h-[46px] shadow-sm">
                    @foreach($tags as $index => $tag)
                        <span class="bg-primary-500/20 border border-primary-500/30 text-primary-400 text-xs px-2 py-1 rounded flex items-center gap-1">
                            {{ $tag }}
                            <button type="button" wire:click="removeTag({{ $index }})" class="hover:text-white transition-colors"><i class="ph-bold ph-x"></i></button>
                        </span>
                    @endforeach
                    <input wire:model="tagInput" wire:keydown.enter.prevent="addTag" type="text" placeholder="Click to add tag..." 
                        class="bg-transparent border-none outline-none text-sm text-white placeholder-slate-600 flex-1 min-w-[100px] h-full py-1.5 px-2">
                </div>
            </div>
        </div>

        {{-- Notifications --}}
        <div class="mb-8">
            <h3 class="text-lg font-bold text-white mb-6">How will we notify you?</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- E-mail --}}
                <label class="cursor-pointer group relative p-4 rounded-xl border border-transparent hover:border-slate-700 hover:bg-slate-800/50 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <input type="checkbox" wire:model="email_notification" class="w-5 h-5 rounded border-slate-600 text-primary-600 focus:ring-primary-600 bg-slate-800 transition-all checked:bg-primary-600">
                        <span class="font-bold text-white">E-mail</span>
                    </div>
                    <div class="text-xs text-slate-400 pl-8 truncate">{{ auth()->user()->email }}</div>
                    <div class="text-[10px] text-slate-600 pl-8 mt-2 flex items-center gap-1.5"><i class="ph-bold ph-arrows-clockwise text-slate-500"></i> No delay, no repeat</div>
                </label>

                {{-- SMS --}}
                <label class="cursor-pointer group relative p-4 rounded-xl border border-transparent hover:border-slate-700 hover:bg-slate-800/50 transition-all">
                     <div class="flex items-center gap-3 mb-2">
                        <input type="checkbox" wire:model="sms_notification" class="w-5 h-5 rounded border-slate-600 text-primary-600 focus:ring-primary-600 bg-slate-800 transition-all checked:bg-primary-600">
                        <span class="font-bold text-white">SMS message</span>
                    </div>
                    <div class="text-xs text-amber-500 pl-8 flex items-center gap-1">Not configured <i class="ph-bold ph-warning"></i></div>
                     <div class="text-[10px] text-slate-600 pl-8 mt-2 flex items-center gap-1.5"><i class="ph-bold ph-arrows-clockwise text-slate-500"></i> No delay, no repeat</div>
                </label>

                 {{-- Voice Call --}}
                <label class="cursor-pointer group relative p-4 rounded-xl border border-transparent hover:border-slate-700 hover:bg-slate-800/50 transition-all">
                     <div class="flex items-center gap-3 mb-2">
                        <input type="checkbox" wire:model="voice_notification" class="w-5 h-5 rounded border-slate-600 text-primary-600 focus:ring-primary-600 bg-slate-800 transition-all checked:bg-primary-600">
                        <span class="font-bold text-white">Voice call</span>
                    </div>
                    <div class="text-xs text-amber-500 pl-8 flex items-center gap-1">Not configured <i class="ph-bold ph-warning"></i></div>
                     <div class="text-[10px] text-slate-600 pl-8 mt-2 flex items-center gap-1.5"><i class="ph-bold ph-arrows-clockwise text-slate-500"></i> No delay, no repeat</div>
                </label>

                 {{-- Push --}}
                <label class="cursor-pointer group relative p-4 rounded-xl border border-transparent hover:border-slate-700 hover:bg-slate-800/50 transition-all">
                     <div class="flex items-center gap-3 mb-2">
                        <input type="checkbox" wire:model="push_notification" class="w-5 h-5 rounded border-slate-600 text-primary-600 focus:ring-primary-600 bg-slate-800 transition-all checked:bg-primary-600">
                        <span class="font-bold text-slate-400 group-hover:text-white transition-colors">Push</span>
                    </div>
                    <div class="text-xs text-emerald-500 pl-8 hover:underline">Download app</div>
                     <div class="text-[10px] text-slate-600 pl-8 mt-2 flex items-center gap-1.5"><i class="ph-bold ph-arrows-clockwise text-slate-500"></i> No delay, no repeat</div>
                </label>
            </div>
            <p class="text-sm text-slate-400 mt-8">You can set up notifications for <a href="#" class="text-emerald-500 hover:text-emerald-400 underline decoration-emerald-500/30 transition-colors">Integrations & Team</a> in the specific tab and edit it later.</p>
        </div>

        <div class="flex justify-end pt-6 border-t border-slate-800/50">
            <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-primary-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                <i class="ph-bold ph-plus-circle"></i> Create Monitor
            </button>
        </div>
    </form>
</div>
