<div class="view-section fade-in space-y-6">

    {{-- Flash message --}}
    @if(session('message'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold px-4 py-3 rounded-xl flex items-center gap-2">
        <i class="ph-fill ph-check-circle text-lg"></i> {{ session('message') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-1.5 flex flex-col md:flex-row justify-between items-center gap-3">
        <div class="flex bg-slate-900/50 rounded-lg p-1 w-full md:w-auto">
            <button wire:click="$set('statusFilter', 'all')"
                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all {{ $statusFilter === 'all' ? 'text-white bg-slate-700 shadow-sm' : 'text-slate-400 hover:text-white' }}">
                All <span class="opacity-60">({{ $counts['all'] }})</span>
            </button>
            <button wire:click="$set('statusFilter', 'online')"
                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all {{ $statusFilter === 'online' ? 'text-white bg-slate-700 shadow-sm' : 'text-slate-400 hover:text-white' }}">
                <span class="text-emerald-400">●</span> Online <span class="opacity-60">({{ $counts['online'] }})</span>
            </button>
            <button wire:click="$set('statusFilter', 'offline')"
                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all {{ $statusFilter === 'offline' ? 'text-white bg-slate-700 shadow-sm' : 'text-slate-400 hover:text-white' }}">
                <span class="text-red-400">●</span> Offline <span class="opacity-60">({{ $counts['offline'] }})</span>
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
            @if(count($selected) > 0)
            <button wire:click="deleteSelected" wire:confirm="Delete {{ count($selected) }} selected monitors?"
                class="px-3 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-500 border border-red-500 rounded-lg flex items-center gap-1.5 transition-colors">
                <i class="ph-bold ph-trash"></i> Delete ({{ count($selected) }})
            </button>
            @endif
        </div>
    </div>

    {{-- Table --}}
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
                @forelse($monitors as $monitor)
                <tr wire:key="monitor-{{ $monitor->id }}"
                    class="hover:bg-slate-700/20 transition-colors {{ $monitor->status === 'offline' ? 'bg-red-500/5 border-l-2 border-l-red-500' : '' }}">

                    <td class="px-5 py-4">
                        <input type="checkbox" wire:model.live="selected" value="{{ $monitor->id }}"
                            class="rounded bg-slate-700 border-slate-600 text-emerald-500 focus:ring-0">
                    </td>

                    {{-- Monitor Name --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-shrink-0">
                                <div class="h-10 w-10 rounded-xl {{ $monitor->status === 'offline' ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20' }} flex items-center justify-center border">
                                    <i class="ph-bold {{ $monitor->type === 'http' ? 'ph-globe' : ($monitor->type === 'port' ? 'ph-plug' : 'ph-router') }}"></i>
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-slate-800
                                    {{ $monitor->status === 'online' ? 'bg-emerald-500' : ($monitor->status === 'offline' ? 'bg-red-500 animate-ping' : 'bg-amber-500') }}">
                                </span>
                            </div>
                            <div>
                                <div class="font-bold text-white">{{ $monitor->name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                    <span class="uppercase font-mono bg-slate-700 px-1.5 py-px rounded text-[10px] border border-slate-600">{{ $monitor->type ?? 'ping' }}</span>
                                    <span class="text-slate-600">{{ $monitor->group ?? 'default' }}</span>
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Connection --}}
                    <td class="px-5 py-4 font-mono text-xs text-slate-400">
                        <div class="text-slate-300">{{ $monitor->ip_address }}</div>
                        <div class="text-slate-600">:{{ $monitor->port ?? 80 }}</div>
                    </td>

                    {{-- Health --}}
                    <td class="px-5 py-4">
                        @if($monitor->status === 'online')
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Operational
                            </span>
                        @elseif($monitor->status === 'offline')
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-red-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span> Unreachable
                            </span>
                        @elseif($monitor->status === 'maintenance')
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-blue-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span> Maintenance
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span> {{ ucfirst($monitor->status) }}
                            </span>
                        @endif
                        @if($monitor->ssl_expiry)
                        <div class="text-[10px] text-slate-500 mt-1">
                            <i class="ph-fill ph-lock-key text-emerald-500"></i>
                            SSL: {{ \Carbon\Carbon::parse($monitor->ssl_expiry)->diffInDays() }}d
                        </div>
                        @endif
                    </td>

                    {{-- Uptime bars --}}
                    <td class="px-5 py-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-slate-500">recent</span>
                            <span class="{{ $monitor->uptime >= 99 ? 'text-emerald-400' : ($monitor->uptime >= 90 ? 'text-amber-400' : 'text-red-400') }} font-bold">{{ $monitor->uptime }}%</span>
                        </div>
                        <div class="flex items-end gap-px h-6">
                            @foreach($monitor->recentMetrics->reverse() as $metric)
                            @php
                                // Green: ≤200ms, no loss | Amber: 200–500ms | Red: >500ms or packet loss
                                $barClass = $metric->packet_loss > 0 || $metric->latency > 500
                                    ? 'bg-red-500 h-3'
                                    : ($metric->latency > 200
                                        ? 'bg-amber-400 h-4'
                                        : 'bg-emerald-500 h-6');
                            @endphp
                            <div class="w-2 rounded-sm {{ $barClass }}"
                                title="{{ $metric->created_at->format('H:i') }} — {{ $metric->latency }}ms / {{ $metric->packet_loss }}% loss">
                            </div>
                            @endforeach
                            @for($i = 0; $i < (20 - $monitor->recentMetrics->count()); $i++)
                            <div class="w-2 h-2 bg-slate-700/30 rounded-sm"></div>
                            @endfor
                        </div>
                        <div class="text-[10px] text-slate-600 mt-1">avg <span class="{{ $monitor->latency > 500 ? 'text-red-400' : ($monitor->latency > 200 ? 'text-amber-400' : 'text-blue-400') }} font-bold">{{ number_format($monitor->latency) }}ms</span></div>
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('monitor.details', $monitor->id) }}" wire:navigate
                                class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-700 rounded-lg transition-colors" title="View Details">
                                <i class="ph-bold ph-eye"></i>
                            </a>
                            <button wire:click="editMonitor({{ $monitor->id }})"
                                class="p-2 text-slate-400 hover:text-amber-400 hover:bg-slate-700 rounded-lg transition-colors" title="Edit Monitor">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>
                            <button wire:click="delete({{ $monitor->id }})" wire:confirm="Delete '{{ $monitor->name }}'? This cannot be undone."
                                class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-700 rounded-lg transition-colors" title="Delete">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-14 text-center text-slate-500">
                        <i class="ph ph-monitor text-5xl block mb-2 opacity-20"></i>
                        <p class="font-semibold text-slate-400">No monitors found.</p>
                        <p class="text-xs mt-1">Try adjusting your search or add a new monitor.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div>{{ $monitors->links() }}</div>


    {{-- ══════════════════════════════════════════
         EDIT MONITOR MODAL
    ══════════════════════════════════════════ --}}
    @if($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-on:keydown.escape.window="$wire.cancelEdit()">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" wire:click="cancelEdit"></div>

        {{-- Modal --}}
        <div class="relative bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl w-full max-w-xl z-10 max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="ph-fill ph-pencil-simple text-amber-400"></i>
                    Edit Monitor
                </h2>
                <button wire:click="cancelEdit" class="text-slate-500 hover:text-white p-1 hover:bg-slate-700 rounded-lg transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-5">

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Monitor Name</label>
                    <input wire:model="editName" type="text" placeholder="e.g. Production Server"
                        class="w-full bg-slate-900 border {{ $errors->has('editName') ? 'border-red-500' : 'border-slate-700' }} rounded-xl px-4 py-2.5 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
                    @error('editName') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Type + IP row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Monitor Type</label>
                        <select wire:model="editType"
                            class="w-full bg-slate-900 border {{ $errors->has('editType') ? 'border-red-500' : 'border-slate-700' }} rounded-xl px-4 py-2.5 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
                            <option value="ping">Ping (ICMP)</option>
                            <option value="http">HTTP / HTTPS</option>
                            <option value="port">TCP Port</option>
                            <option value="keyword">Keyword Check</option>
                        </select>
                        @error('editType') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">IP / Hostname</label>
                        <input wire:model="editIp" type="text" placeholder="192.168.1.1"
                            class="w-full bg-slate-900 border {{ $errors->has('editIp') ? 'border-red-500' : 'border-slate-700' }} rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:border-emerald-500 outline-none transition-colors">
                        @error('editIp') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Port + Interval --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Port</label>
                        <input wire:model="editPort" type="number" min="1" max="65535"
                            class="w-full bg-slate-900 border {{ $errors->has('editPort') ? 'border-red-500' : 'border-slate-700' }} rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:border-emerald-500 outline-none transition-colors">
                        @error('editPort') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Check Every (sec)</label>
                        <input wire:model="editInterval" type="number" min="30" step="30"
                            class="w-full bg-slate-900 border {{ $errors->has('editInterval') ? 'border-red-500' : 'border-slate-700' }} rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:border-emerald-500 outline-none transition-colors">
                        @error('editInterval') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Group --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Group</label>
                    <input wire:model="editGroup" type="text" placeholder="default"
                        class="w-full bg-slate-900 border {{ $errors->has('editGroup') ? 'border-red-500' : 'border-slate-700' }} rounded-xl px-4 py-2.5 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
                    @error('editGroup') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Notifications --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Notifications</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['editEmail', 'Email', 'ph-envelope'],
                            ['editSms',   'SMS',   'ph-chat-circle-text'],
                            ['editVoice', 'Voice Call', 'ph-phone'],
                            ['editPush',  'Push',  'ph-bell'],
                        ] as [$prop, $label, $icon])
                        <label class="flex items-center gap-3 bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 cursor-pointer hover:border-slate-600 transition-colors">
                            <input type="checkbox" wire:model="{{ $prop }}" class="rounded bg-slate-700 border-slate-600 text-emerald-500 focus:ring-0 focus:ring-offset-0">
                            <span class="flex items-center gap-2 text-sm text-slate-300">
                                <i class="ph-fill {{ $icon }} text-slate-500"></i> {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Footer --}}
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
    @endif

</div>
