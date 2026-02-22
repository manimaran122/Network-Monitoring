<div class="view-section fade-in space-y-6">

    {{-- ═══════════════════════════ PAGE HEADER ═══════════════════════════ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="ph-fill ph-chart-bar text-emerald-500"></i> System Reports
            </h1>
            <p class="text-sm text-slate-400 mt-1">Export historical metrics by IP, date range, and format.</p>
        </div>
        <button wire:click="openExportModal" id="btn-open-export"
            class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all text-sm">
            <i class="ph-bold ph-export"></i> Export Report
        </button>
    </div>

    {{-- ═══════════════════════════ KPI SUMMARY CARDS ═══════════════════════════ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($summaryRows as $row)
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 shadow-lg hover:border-slate-600 transition-colors">
            <div class="flex items-start justify-between mb-3">
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider truncate">{{ $row->name }}</p>
                    <p class="text-xs font-mono text-slate-500 mt-0.5">{{ $row->ip_address }}</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-bold flex-shrink-0 ml-2
                    {{ $row->status === 'online' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                     : ($row->status === 'offline' ? 'bg-red-500/10 text-red-400 border border-red-500/20'
                     : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                    {{ strtoupper($row->status) }}
                </span>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center">
                <div>
                    <div class="text-lg font-bold text-white">{{ number_format($row->avg_latency ?? 0, 0) }}<span class="text-xs text-slate-500">ms</span></div>
                    <div class="text-[10px] text-slate-500 uppercase">Avg Lat.</div>
                </div>
                <div class="border-x border-slate-700">
                    <div class="text-lg font-bold {{ ($row->avg_loss ?? 0) > 0 ? 'text-red-400' : 'text-emerald-400' }}">{{ number_format($row->avg_loss ?? 0, 1) }}<span class="text-xs text-slate-500">%</span></div>
                    <div class="text-[10px] text-slate-500 uppercase">Loss</div>
                </div>
                <div>
                    <div class="text-lg font-bold text-slate-300">{{ $row->check_count }}</div>
                    <div class="text-[10px] text-slate-500 uppercase">Checks</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════ RECENT METRICS TABLE ═══════════════════════════ --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-xl">
        <div class="p-4 border-b border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h3 class="font-bold text-white flex items-center gap-2">
                <i class="ph-fill ph-table text-emerald-500"></i> Recent Metrics
            </h3>
            <div class="flex items-center gap-2">
                <select wire:model.live="dateRange" class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white focus:border-emerald-500 outline-none">
                    <option value="1">Last 1 hour</option>
                    <option value="6">Last 6 hours</option>
                    <option value="24" selected>Last 24 hours</option>
                    <option value="168">Last 7 days</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-900/50 text-xs uppercase text-slate-400 font-semibold">
                    <tr>
                        <th class="px-5 py-3">Timestamp</th>
                        <th class="px-5 py-3">Monitor</th>
                        <th class="px-5 py-3">IP Address</th>
                        <th class="px-5 py-3">Latency</th>
                        <th class="px-5 py-3">Packet Loss</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-slate-300">
                    @forelse($metrics as $m)
                    <tr class="hover:bg-slate-700/20 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $m->created_at->format('M d, H:i:s') }}</td>
                        <td class="px-5 py-3 font-semibold text-white">{{ $m->monitor?->name ?? '—' }}</td>
                        <td class="px-5 py-3 font-mono text-slate-300">{{ $m->monitor?->ip_address ?? '—' }}</td>
                        <td class="px-5 py-3 font-mono">
                            @if($m->latency > 0)
                                <span class="{{ $m->latency > 200 ? 'text-red-400' : ($m->latency > 100 ? 'text-amber-400' : 'text-emerald-400') }}">
                                    {{ number_format($m->latency, 1) }}ms
                                </span>
                            @else
                                <span class="text-red-500 font-bold">TIMEOUT</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-mono {{ $m->packet_loss > 0 ? 'text-red-400' : 'text-slate-400' }}">
                            {{ number_format($m->packet_loss, 1) }}%
                        </td>
                        <td class="px-5 py-3">
                            @if($m->packet_loss >= 100)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 font-bold">OFFLINE</span>
                            @elseif($m->packet_loss > 0)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-bold">DEGRADED</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold">ONLINE</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                            <i class="ph ph-database text-3xl block mb-2 opacity-30"></i>
                            No metrics recorded in this time window yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════ EXPORT MODAL ═══════════════════════════ --}}
    @if($showExportModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-init="$el.querySelector('#export-modal').classList.add('scale-100','opacity-100')">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeExportModal"></div>

        {{-- Modal Panel --}}
        <div id="export-modal"
             class="relative w-full max-w-2xl bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl shadow-black/60 scale-95 opacity-0 transition-all duration-200 z-10 max-h-[90vh] flex flex-col">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-6 border-b border-slate-700 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                        <i class="ph-bold ph-export text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Export Report</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Select monitors, date range, and format</p>
                    </div>
                </div>
                <button wire:click="closeExportModal" class="h-8 w-8 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto space-y-6 flex-1">

                {{-- Step 1: Monitor / IP Selection --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-sm font-bold text-white flex items-center gap-2">
                            <span class="h-5 w-5 rounded-full bg-emerald-500 text-white text-xs flex items-center justify-center font-black">1</span>
                            Select Monitors / IPs
                        </label>
                        <div class="flex gap-2">
                            <button wire:click="selectAllIps" class="text-xs text-emerald-400 hover:text-emerald-300 underline">Select All</button>
                            <span class="text-slate-600">|</span>
                            <button wire:click="clearAllIps" class="text-xs text-slate-400 hover:text-slate-300 underline">Clear</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto pr-1">
                        @foreach($monitors as $monitor)
                        <label wire:click="toggleIp({{ $monitor->id }})"
                            class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all
                                {{ in_array($monitor->id, $selectedIps)
                                    ? 'bg-emerald-500/10 border-emerald-500/40 text-white'
                                    : 'bg-slate-800 border-slate-700 text-slate-400 hover:border-slate-500' }}">
                            <div class="h-5 w-5 rounded border-2 flex items-center justify-center flex-shrink-0 transition-all
                                {{ in_array($monitor->id, $selectedIps) ? 'bg-emerald-500 border-emerald-500' : 'border-slate-600' }}">
                                @if(in_array($monitor->id, $selectedIps))
                                <i class="ph-bold ph-check text-[10px] text-white"></i>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-sm truncate">{{ $monitor->name }}</div>
                                <div class="font-mono text-xs text-slate-500">{{ $monitor->ip_address }}</div>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded font-bold flex-shrink-0
                                {{ $monitor->status === 'online' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ strtoupper($monitor->status) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @if(empty($selectedIps))
                    <p class="text-xs text-red-400 mt-2 flex items-center gap-1"><i class="ph ph-warning-circle"></i> Select at least one monitor.</p>
                    @else
                    <p class="text-xs text-slate-500 mt-2">{{ count($selectedIps) }} monitor(s) selected</p>
                    @endif
                </div>

                {{-- Divider --}}
                <div class="border-t border-slate-700/60"></div>

                {{-- Step 2: Date Range --}}
                <div>
                    <label class="text-sm font-bold text-white flex items-center gap-2 mb-3">
                        <span class="h-5 w-5 rounded-full bg-emerald-500 text-white text-xs flex items-center justify-center font-black">2</span>
                        Date Range
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-slate-400 mb-1 block">From</label>
                            <input wire:model="dateFrom" type="date"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 mb-1 block">To</label>
                            <input wire:model="dateTo" type="date"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-slate-700/60"></div>

                {{-- Step 3: Report Type + Group By --}}
                <div>
                    <label class="text-sm font-bold text-white flex items-center gap-2 mb-3">
                        <span class="h-5 w-5 rounded-full bg-emerald-500 text-white text-xs flex items-center justify-center font-black">3</span>
                        Report Type &amp; Grouping
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-slate-400 mb-1 block">Report Type</label>
                            <select wire:model.live="reportType" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:border-emerald-500 outline-none">
                                <option value="summary">Summary (1 row per IP)</option>
                                <option value="latency">Latency Over Time</option>
                                <option value="packet_loss">Packet Loss Over Time</option>
                                <option value="full">Full Raw Data</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 mb-1 block">Group By</label>
                            <select wire:model="groupBy"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:border-emerald-500 outline-none {{ $reportType === 'summary' ? 'opacity-40 cursor-not-allowed' : '' }}"
                                {{ $reportType === 'summary' ? 'disabled' : '' }}>
                                <option value="raw">Raw (every check)</option>
                                <option value="hour">Hourly Average</option>
                                <option value="day">Daily Average</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-slate-700/60"></div>

                {{-- Step 4: Format --}}
                <div>
                    <label class="text-sm font-bold text-white flex items-center gap-2 mb-3">
                        <span class="h-5 w-5 rounded-full bg-emerald-500 text-white text-xs flex items-center justify-center font-black">4</span>
                        Export Format
                    </label>
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all
                            {{ $exportFormat === 'csv' ? 'bg-emerald-500/10 border-emerald-500/40' : 'bg-slate-800 border-slate-700 hover:border-slate-500' }}">
                            <input type="radio" wire:model.live="exportFormat" value="csv" class="sr-only">
                            <div class="h-9 w-9 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                                <i class="ph-bold ph-file-csv text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-white">CSV</div>
                                <div class="text-xs text-slate-400">Spreadsheet ready</div>
                            </div>
                        </label>
                        <label class="flex-1 flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all opacity-50 cursor-not-allowed
                            {{ $exportFormat === 'json' ? 'bg-blue-500/10 border-blue-500/40' : 'bg-slate-800 border-slate-700' }}">
                            <div class="h-9 w-9 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center">
                                <i class="ph-bold ph-brackets-curly text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-white">JSON <span class="text-[10px] bg-slate-700 text-slate-400 px-1.5 rounded ml-1">Soon</span></div>
                                <div class="text-xs text-slate-400">API / developer use</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Preview Info Box --}}
                <div class="bg-slate-800/80 border border-slate-700 rounded-xl p-4 flex items-start gap-3">
                    <i class="ph-fill ph-info text-blue-400 text-lg flex-shrink-0 mt-0.5"></i>
                    <div class="text-xs text-slate-400 leading-relaxed">
                        <strong class="text-slate-300">Preview:</strong>
                        Exporting <strong class="text-white">{{ count($selectedIps) }} monitor(s)</strong>
                        from <strong class="text-white">{{ $dateFrom }}</strong> to <strong class="text-white">{{ $dateTo }}</strong>
                        as <strong class="text-emerald-400">{{ strtoupper($exportFormat) }}</strong>
                        — <strong class="text-white">{{ collect(['summary'=>'Summary','latency'=>'Latency','packet_loss'=>'Packet Loss','full'=>'Full Data'])[$reportType] }}</strong>
                        @if($reportType !== 'summary')
                        grouped by <strong class="text-white">{{ $groupBy }}</strong>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-5 border-t border-slate-700 flex items-center justify-between gap-3 flex-shrink-0">
                <button wire:click="closeExportModal"
                    class="px-5 py-2 text-sm text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition-colors font-semibold">
                    Cancel
                </button>
                <button wire:click="exportCsv"
                    @disabled(empty($selectedIps))
                    class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-700 disabled:text-slate-500 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all text-sm">
                    <i class="ph-bold ph-download-simple"></i>
                    <span wire:loading.remove wire:target="exportCsv">Download {{ strtoupper($exportFormat) }}</span>
                    <span wire:loading wire:target="exportCsv" class="flex items-center gap-1.5"><i class="ph-bold ph-circle-notch animate-spin"></i> Generating…</span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
