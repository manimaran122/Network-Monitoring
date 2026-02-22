<div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity"
     x-data="{ open: @entangle('isOpen').live }"
     x-show="open"
     x-transition
     style="display: none;">
    <div class="bg-slate-800 border border-slate-700 w-full max-w-4xl rounded-2xl shadow-2xl transform transition-all scale-100 max-h-[90vh] overflow-y-auto" @click.outside="open = false">
        <div class="p-6 border-b border-slate-700 flex justify-between items-center bg-slate-850 rounded-t-2xl sticky top-0 z-10">
            <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="ph-fill ph-plus-circle text-primary-500"></i> Add New Monitor</h3>
            <button wire:click="close" class="text-slate-400 hover:text-white transition-colors"><i class="ph-bold ph-x text-xl"></i></button>
        </div>
        
        <form wire:submit="save">
            <div class="p-8">
                {{-- Monitor Type --}}
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-300 mb-3">Monitor type</label>
                    <div x-data="{ expanded: false }" class="relative">
                        <div @click="expanded = !expanded" class="bg-slate-900 border border-slate-700 rounded-xl p-4 flex items-center justify-between cursor-pointer hover:border-slate-600 transition-colors shadow-sm">
                            @php
                                $selectedType = collect($availableMonitorTypes)->firstWhere('id', $monitorType);
                            @endphp
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                                    <i class="ph-fill {{ $selectedType['icon'] }} text-2xl text-emerald-500"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-white text-lg">{{ $selectedType['name'] }}</div>
                                    <div class="text-sm text-slate-400">{{ $selectedType['description'] }}</div>
                                </div>
                            </div>
                            <i class="ph-bold ph-caret-down text-slate-500 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                        </div>

                        <!-- Dropdown Options -->
                        <div x-show="expanded" @click.outside="expanded = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute top-full left-0 right-0 mt-2 bg-slate-900 border border-slate-700 rounded-xl shadow-2xl z-20 overflow-hidden max-h-64 overflow-y-auto">
                            @foreach($availableMonitorTypes as $type)
                                <div @click="$wire.set('monitorType', '{{ $type['id'] }}'); expanded = false" 
                                     class="p-4 flex items-center gap-4 cursor-pointer hover:bg-slate-800 transition-colors border-b border-slate-800 last:border-0 {{ $monitorType === $type['id'] ? 'bg-slate-800/50' : '' }}">
                                    <div class="w-10 h-10 {{ $monitorType === $type['id'] ? 'bg-emerald-500/10 border-emerald-500/20' : 'bg-slate-800 border-slate-700' }} rounded-lg flex items-center justify-center border shadow-sm transition-colors">
                                        <i class="ph-fill {{ $type['icon'] }} text-xl {{ $monitorType === $type['id'] ? 'text-emerald-500' : 'text-slate-400' }}"></i>
                                    </div>
                                    <div>
                                        <div class="{{ $monitorType === $type['id'] ? 'text-white' : 'text-slate-300' }} font-bold">{{ $type['name'] }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $type['description'] }}</div>
                                    </div>
                                    @if($monitorType === $type['id'])
                                        <i class="ph-bold ph-check text-emerald-500 ml-auto"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('monitorType') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- URL / IP --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-300 mb-3">
                        @if($monitorType === 'http' || $monitorType === 'keyword') URL (or IP)
                        @else IP or Host
                        @endif
                    </label>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <input wire:model="ip_address" type="text" placeholder="{{ $monitorType === 'http' ? 'https://example.com' : 'E.g. 192.168.1.1' }}" 
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all placeholder-slate-600 shadow-sm">
                        </div>
                        @if($monitorType === 'port')
                        <div class="w-24">
                            <input wire:model="port" type="number" placeholder="Port" 
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all placeholder-slate-600 shadow-sm">
                        </div>
                        @endif
                    </div>
                     @error('ip_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Keyword --}}
                @if($monitorType === 'keyword')
                <div class="mb-8 p-4 bg-slate-900 border border-slate-700 rounded-xl">
                    <label class="block text-sm font-bold text-slate-300 mb-3">Keyword to find</label>
                    <input wire:model="keyword" type="text" placeholder="e.g. System Online" 
                        class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all placeholder-slate-600 shadow-sm mb-4">
                    
                    <label class="block text-sm font-bold text-slate-300 mb-2">Alert when</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="keyword_should_exist" :value="true" class="bg-slate-800 border-slate-600 text-primary-500 focus:ring-primary-500">
                            <span class="text-sm text-slate-300">Keyword Exists (is present)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" wire:model="keyword_should_exist" :value="false" class="bg-slate-800 border-slate-600 text-danger-500 focus:ring-danger-500">
                            <span class="text-sm text-slate-300">Keyword Missing (is not present)</span>
                        </label>
                    </div>
                </div>
                @endif
                
                {{-- Friendly Name --}}
                <div class="mb-8">
                     <div class="flex items-center gap-3 mb-3">
                        <label class="block text-sm font-bold text-slate-300">Friendly name: <span class="text-white">{{ $name ?: 'New Monitor' }}</span></label>
                     </div>
                     <input wire:model="name" type="text" placeholder="Friendly Name" 
                        class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all placeholder-slate-600 shadow-sm">
                     @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Monitoring Interval --}}
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-300 mb-3">Monitoring Interval</label>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        @foreach([60 => '1 min', 300 => '5 min', 600 => '10 min', 1800 => '30 min', 3600 => '60 min'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="check_interval" value="{{ $val }}" class="peer sr-only">
                            <div class="text-center px-4 py-2 rounded-lg border border-slate-700 bg-slate-900 text-slate-400 peer-checked:bg-primary-600 peer-checked:text-white peer-checked:border-primary-500 transition-all hover:border-slate-600 text-sm font-medium">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Monitor Failure Timeout --}}
                <div class="mb-8">
                    <div class="flex justify-between mb-3">
                        <label class="block text-sm font-bold text-slate-300">Monitor Timeout</label>
                        <span class="text-xs text-primary-400 font-bold bg-primary-500/10 px-2 py-0.5 rounded">{{ $request_timeout }} seconds</span>
                    </div>
                    <input type="range" wire:model.live="request_timeout" min="1" max="60" step="1" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-primary-500">
                    <div class="flex justify-between text-[10px] text-slate-500 mt-1">
                        <span>1s</span>
                        <span>60s</span>
                    </div>
                </div>

                {{-- Advanced Settings --}}
                <div class="mb-10" x-data="{ expanded: false }">
                    <button type="button" @click="expanded = !expanded" class="flex items-center gap-2 text-sm font-bold text-emerald-500 hover:text-emerald-400 transition-colors">
                        <i class="ph-bold ph-gear"></i> Advanced Settings
                        <i class="ph-bold ph-caret-down transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="expanded" x-collapse class="mt-4 p-6 bg-slate-900/50 rounded-xl border border-slate-700 space-y-6">
                        @if($monitorType === 'http' || $monitorType === 'keyword')
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">Authentication (Basic)</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input wire:model="auth_user" type="text" placeholder="Username" class="bg-slate-900 border border-slate-700 rounded px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                                <input wire:model="auth_pass" type="password" placeholder="Password" class="bg-slate-900 border border-slate-700 rounded px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">HTTP Method</label>
                            <div class="flex gap-4">
                                @foreach(['HEAD', 'GET', 'POST', 'PUT', 'DELETE'] as $m)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="http_method" value="{{ $m }}" class="bg-slate-800 border-slate-600 text-primary-500 focus:ring-primary-500">
                                    <span class="text-sm text-slate-300">{{ $m }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2">Accepted Status Codes</label>
                            <input wire:model="accepted_status_codes" type="text" placeholder="e.g. 200-299" class="w-full bg-slate-900 border border-slate-700 rounded px-3 py-2 text-sm text-white focus:border-primary-500 outline-none">
                            <p class="text-[10px] text-slate-500 mt-1">Enter range (200-299) or specific codes separated by comma</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="verify_ssl" id="ssl_check" class="rounded border-slate-600 bg-slate-800 text-primary-500 focus:ring-primary-500">
                            <label for="ssl_check" class="text-sm text-slate-300">Verify SSL Certificate expiry</label>
                        </div>
                        @else
                        <div class="text-center text-slate-500 text-sm py-2">
                            No advanced settings available for this monitor type.
                        </div>
                        @endif
                    </div>
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
            </div>

            <div class="p-6 border-t border-slate-700 bg-slate-850/50 rounded-b-2xl flex justify-end gap-3 sticky bottom-0 z-10 backdrop-blur-xl">
                <button type="button" wire:click="close" class="px-5 py-2.5 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold bg-primary-600 hover:bg-primary-500 text-white rounded-lg shadow-lg shadow-primary-500/20 transition-all transform active:scale-95 flex items-center gap-2">
                    <i class="ph-bold ph-plus-circle"></i> Create Monitor
                </button>
            </div>
        </form>
    </div>
</div>
