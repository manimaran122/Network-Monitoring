<div class="view-section fade-in space-y-6" wire:poll.10s>

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="ph-fill ph-siren text-red-500 animate-pulse"></i>
                Alert Center
            </h1>
            <p class="text-sm text-slate-400 mt-1">Real-time monitoring alerts — auto-resolved when monitors recover.</p>
        </div>
        @if($openCount > 0)
        <button wire:click="acknowledgeAll" wire:confirm="Acknowledge all open alerts?"
            class="flex items-center gap-2 px-4 py-2 bg-slate-700 hover:bg-slate-600 border border-slate-600 text-white text-sm font-semibold rounded-lg transition-all">
            <i class="ph-bold ph-checks"></i> Acknowledge All ({{ $openCount }})
        </button>
        @endif
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-red-400 text-xs font-bold uppercase tracking-wider">Critical</div>
                <div class="text-3xl font-bold text-white mt-1">{{ $criticalCount }}</div>
            </div>
            <div class="h-12 w-12 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center {{ $criticalCount > 0 ? 'animate-pulse' : '' }}">
                <i class="ph-fill ph-siren text-2xl"></i>
            </div>
        </div>
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-amber-400 text-xs font-bold uppercase tracking-wider">Warnings</div>
                <div class="text-3xl font-bold text-white mt-1">{{ $warningCount }}</div>
            </div>
            <div class="h-12 w-12 rounded-full bg-amber-500/20 text-amber-500 flex items-center justify-center">
                <i class="ph-fill ph-warning text-2xl"></i>
            </div>
        </div>
        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-emerald-400 text-xs font-bold uppercase tracking-wider">Resolved Today</div>
                <div class="text-3xl font-bold text-white mt-1">{{ $resolvedToday }}</div>
            </div>
            <div class="h-12 w-12 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center">
                <i class="ph-fill ph-check-circle text-2xl"></i>
            </div>
        </div>
        <div class="bg-slate-700/50 border border-slate-600 rounded-xl p-4 flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Open</div>
                <div class="text-3xl font-bold text-white mt-1">{{ $openCount }}</div>
            </div>
            <div class="h-12 w-12 rounded-full bg-slate-600 text-slate-300 flex items-center justify-center">
                <i class="ph-fill ph-bell-ringing text-2xl"></i>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex flex-col md:flex-row gap-3">
        {{-- Search --}}
        <div class="relative flex-1">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by monitor name or IP..."
                class="w-full bg-slate-900 border border-slate-700 rounded-lg pl-9 pr-3 py-2 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
        </div>
        {{-- Status Filter --}}
        <div class="flex gap-1 bg-slate-900 border border-slate-700 rounded-lg p-1">
            @foreach(['open' => 'Open', 'acknowledged' => 'Acknowledged', 'resolved' => 'Resolved', 'all' => 'All'] as $val => $label)
            <button wire:click="$set('filter', '{{ $val }}')"
                class="px-3 py-1.5 text-xs font-semibold rounded-md transition-all {{ $filter === $val ? 'bg-emerald-600 text-white shadow' : 'text-slate-400 hover:text-white' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
        {{-- Severity Filter --}}
        <div class="flex gap-1 bg-slate-900 border border-slate-700 rounded-lg p-1">
            @foreach(['all' => 'All', 'critical' => 'Critical', 'warning' => 'Warning', 'info' => 'Info'] as $val => $label)
            <button wire:click="$set('severity', '{{ $val }}')"
                class="px-3 py-1.5 text-xs font-semibold rounded-md transition-all {{ $severity === $val ? 'bg-slate-600 text-white shadow' : 'text-slate-400 hover:text-white' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Alerts List --}}
    <div class="space-y-3">
        @forelse($alerts as $alert)
        @php
            $colors = [
                'critical' => ['border' => 'border-l-red-500',   'icon_bg' => 'bg-red-500/10',   'icon_text' => 'text-red-500',   'badge' => 'bg-red-500/10 text-red-400 border-red-500/30'],
                'warning'  => ['border' => 'border-l-amber-500', 'icon_bg' => 'bg-amber-500/10', 'icon_text' => 'text-amber-500', 'badge' => 'bg-amber-500/10 text-amber-400 border-amber-500/30'],
                'info'     => ['border' => 'border-l-blue-500',  'icon_bg' => 'bg-blue-500/10',  'icon_text' => 'text-blue-400',  'badge' => 'bg-blue-500/10 text-blue-400 border-blue-500/30'],
            ][$alert->severity] ?? [];
            $statusColors = [
                'open'         => 'bg-red-500/10 text-red-400 border-red-500/30',
                'acknowledged' => 'bg-slate-600/50 text-slate-300 border-slate-500',
                'resolved'     => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
            ];
        @endphp
        <div wire:key="alert-{{ $alert->id }}"
            class="bg-slate-800 border border-slate-700 border-l-4 {{ $colors['border'] }} rounded-r-xl p-4 flex flex-col md:flex-row items-start md:items-center gap-4 shadow-lg transition-all hover:bg-slate-750 group">

            {{-- Icon --}}
            <div class="h-11 w-11 rounded-xl {{ $colors['icon_bg'] }} {{ $colors['icon_text'] }} flex items-center justify-center flex-shrink-0">
                <i class="ph-fill {{ $alert->typeIcon() }} text-xl"></i>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h3 class="font-bold text-white truncate">{{ $alert->monitor->name ?? 'Unknown' }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full border font-bold uppercase {{ $colors['badge'] }}">
                        {{ $alert->severity }}
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $statusColors[$alert->status] ?? '' }}">
                        {{ ucfirst($alert->status) }}
                    </span>
                    <span class="text-xs text-slate-500 bg-slate-900 px-2 py-0.5 rounded font-mono">{{ str_replace('_', ' ', strtoupper($alert->type)) }}</span>
                </div>
                <p class="text-sm text-slate-300 mb-2">{{ $alert->message }}</p>
                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                    <span><i class="ph ph-map-pin mr-1"></i>{{ $alert->monitor->ip_address ?? '—' }}</span>
                    @if($alert->latency > 0)
                    <span><i class="ph ph-timer mr-1"></i>{{ number_format($alert->latency, 1) }}ms latency</span>
                    @endif
                    @if($alert->packet_loss !== null)
                    <span><i class="ph ph-wave-sawtooth mr-1"></i>{{ $alert->packet_loss }}% packet loss</span>
                    @endif
                    <span><i class="ph ph-clock mr-1"></i>{{ $alert->created_at->diffForHumans() }}</span>
                    @if($alert->acknowledged_at)
                    <span class="text-slate-600"><i class="ph ph-check mr-1"></i>Acknowledged {{ $alert->acknowledged_at->diffForHumans() }}</span>
                    @endif
                    @if($alert->resolved_at)
                    <span class="text-emerald-600"><i class="ph ph-check-circle mr-1"></i>Resolved {{ $alert->resolved_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('monitor.details', $alert->monitor_id) }}"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-300 bg-slate-700 hover:bg-slate-600 border border-slate-600 rounded-lg transition-colors flex items-center gap-1">
                    <i class="ph ph-arrow-square-out"></i> View
                </a>
                @if($alert->status === 'open')
                <button wire:click="acknowledge({{ $alert->id }})"
                    class="px-3 py-1.5 text-xs font-semibold text-amber-300 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 rounded-lg transition-colors flex items-center gap-1">
                    <i class="ph ph-check"></i> Acknowledge
                </button>
                @endif
                @if(in_array($alert->status, ['open', 'acknowledged']))
                <button wire:click="resolve({{ $alert->id }})"
                    class="px-3 py-1.5 text-xs font-semibold text-emerald-300 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 rounded-lg transition-colors flex items-center gap-1">
                    <i class="ph ph-check-circle"></i> Resolve
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-slate-500 bg-slate-800/50 rounded-xl border border-slate-700 border-dashed">
            <i class="ph ph-check-circle text-5xl text-emerald-500/30 block mb-3"></i>
            <p class="text-lg font-semibold text-slate-400">All clear!</p>
            <p class="text-sm mt-1">No alerts matching your current filters.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($alerts->hasPages())
    <div class="mt-4">
        {{ $alerts->links() }}
    </div>
    @endif

</div>
