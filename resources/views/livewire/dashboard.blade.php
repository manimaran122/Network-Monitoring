<div wire:poll.5s class="view-section fade-in space-y-6">

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <span class="h-9 w-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                    <i class="ph-fill ph-radar text-xl animate-pulse"></i>
                </span>
                System Overview
            </h1>
            <p class="text-sm text-slate-400 mt-1 ml-12">Real-time network performance · auto-refreshes every 5s</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-400 bg-slate-800 border border-slate-700 px-3 py-2 rounded-xl">
            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Live · {{ now()->format('H:i:s') }}
        </div>
    </div>

    {{-- ═══ AI INSIGHT ═══ --}}
    <livewire:ai-insight />

    {{-- ═══ KPI CARDS ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-800 border border-slate-700 hover:border-slate-600 rounded-2xl p-5 shadow-lg relative overflow-hidden transition-colors group">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent rounded-2xl"></div>
            <div class="relative">
                <div class="h-10 w-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center mb-3">
                    <i class="ph-fill ph-globe text-xl"></i>
                </div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Monitors</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $total }}</h3>
            </div>
        </div>
        <div class="bg-slate-800 border border-emerald-500/20 hover:border-emerald-500/40 rounded-2xl p-5 shadow-lg relative overflow-hidden transition-colors group">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent rounded-2xl"></div>
            <div class="relative">
                <div class="h-10 w-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-3">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Online</p>
                <h3 class="text-3xl font-black text-emerald-400 mt-1">{{ $online }}</h3>
            </div>
        </div>
        <a href="{{ route('alerts') }}" class="bg-slate-800 border {{ $critical > 0 ? 'border-red-500/30 hover:border-red-500/60' : 'border-slate-700 hover:border-slate-600' }} rounded-2xl p-5 shadow-lg relative overflow-hidden transition-all group block">
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent rounded-2xl"></div>
            <div class="relative">
                <div class="h-10 w-10 rounded-xl {{ $critical > 0 ? 'bg-red-500/10 text-red-400' : 'bg-slate-700 text-slate-400' }} flex items-center justify-center mb-3">
                    <i class="ph-fill ph-warning-octagon text-xl {{ $critical > 0 ? 'animate-pulse' : '' }}"></i>
                </div>
                <p class="text-xs {{ $critical > 0 ? 'text-red-400' : 'text-slate-400' }} font-bold uppercase tracking-wider">Critical Down</p>
                <h3 class="text-3xl font-black {{ $critical > 0 ? 'text-red-400' : 'text-white' }} mt-1">{{ $critical }}</h3>
            </div>
        </a>
        <div class="bg-slate-800 border border-amber-500/20 hover:border-amber-500/40 rounded-2xl p-5 shadow-lg relative overflow-hidden transition-colors group">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent rounded-2xl"></div>
            <div class="relative">
                <div class="h-10 w-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center mb-3">
                    <i class="ph-fill ph-timer text-xl"></i>
                </div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Avg Latency</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $avgLatency }}<span class="text-base text-slate-500 font-medium ml-1">ms</span></h3>
            </div>
        </div>
    </div>

    {{-- ═══ CHARTS AREA ═══ --}}
    <div x-data="{
            latencyChart: null,
            jitterChart: null,
            lossChart: null,
            chartData: {{ json_encode($chartData) }},
            avgJitter: 0,
            avgLoss: 0,
            initCharts() {
                // Guard: already initialized — Livewire poll re-runs x-init, don't double-init
                if (this.latencyChart) return;

                // Destroy any orphaned Chart.js instance on the canvas (e.g. after navigate)
                const destroyIfExists = (id) => { const c = Chart.getChart(id); if (c) c.destroy(); };
                destroyIfExists('dashLatencyChart');
                destroyIfExists('dashJitterChart');
                destroyIfExists('dashLossChart');
                const palette = [
                    { line: '#10b981', glow: 'rgba(16,185,129,0.15)' },
                    { line: '#3b82f6', glow: 'rgba(59,130,246,0.15)' },
                    { line: '#f59e0b', glow: 'rgba(245,158,11,0.15)' },
                    { line: '#8b5cf6', glow: 'rgba(139,92,246,0.15)' },
                    { line: '#06b6d4', glow: 'rgba(6,182,212,0.15)' },
                    { line: '#f43f5e', glow: 'rgba(244,63,94,0.15)' },
                ];

                // Compute avg jitter & loss for badge display
                const jitterVals = this.chartData.jitter.filter(v => v !== null);
                const lossVals   = this.chartData.loss.filter(v => v !== null);
                this.avgJitter = jitterVals.length ? (jitterVals.reduce((a,b)=>a+b,0)/jitterVals.length).toFixed(1) : 0;
                this.avgLoss   = lossVals.length   ? (lossVals.reduce((a,b)=>a+b,0)/lossVals.length).toFixed(2) : 0;

                const baseTooltip = {
                    mode: 'index', intersect: false,
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#e2e8f0',
                    borderColor: '#1e293b',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8,
                };
                const baseScaleX = {
                    display: true,
                    grid: { display: false },
                    border: { display: false },
                    ticks: { color: '#475569', font: { size: 10, family: 'Roboto Mono' }, maxRotation: 0 }
                };
                const baseScaleY = {
                    grid: { color: 'rgba(51,65,85,0.35)', drawBorder: false },
                    border: { display: false },
                    ticks: { color: '#475569', font: { size: 10 } }
                };

                // ── LATENCY CHART ──────────────────────────────────────────
                const ctxL = document.getElementById('dashLatencyChart').getContext('2d');
                const series = this.chartData.monitorSeries || [];

                const datasets = series.length > 0
                    ? series.map((s, i) => {
                        const c = palette[i % palette.length];
                        const grad = ctxL.createLinearGradient(0, 0, 0, 300);
                        grad.addColorStop(0, c.glow);
                        grad.addColorStop(1, 'rgba(0,0,0,0)');
                        return {
                            label: s.name + ' · ' + s.ip,
                            data: s.data,
                            borderColor: c.line,
                            backgroundColor: series.length === 1 ? grad : 'transparent',
                            borderWidth: 3,
                            tension: 0.35,
                            fill: series.length === 1,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            pointBackgroundColor: c.line,
                            pointBorderColor: '#0f172a',
                            pointBorderWidth: 2,
                            spanGaps: true,
                            showLine: true,
                        };
                    })
                    : [{
                        label: 'Global Avg Latency',
                        data: this.chartData.latency,
                        borderColor: '#10b981',
                        backgroundColor: (() => {
                            const g = ctxL.createLinearGradient(0,0,0,280);
                            g.addColorStop(0,'rgba(16,185,129,0.22)');
                            g.addColorStop(1,'rgba(16,185,129,0)');
                            return g;
                        })(),
                        borderWidth: 3,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#0f172a',
                        pointBorderWidth: 2,
                        spanGaps: true,
                        showLine: true,
                    }];

                this.latencyChart = new Chart(ctxL, {
                    type: 'line',
                    data: { labels: this.chartData.labels, datasets },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        animation: { duration: 500 },
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                display: series.length > 1,
                                position: 'top',
                                align: 'end',
                                labels: { color: '#94a3b8', font: { size: 11 }, boxWidth: 10, boxHeight: 10, padding: 16, usePointStyle: true, pointStyle: 'circle' }
                            },
                            tooltip: {
                                ...baseTooltip,
                                callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y !== null ? ctx.parsed.y + ' ms' : 'No data'}` }
                            }
                        },
                        spanGaps: true,
                        scales: {
                            x: { ...baseScaleX },
                            y: { ...baseScaleY, min: 0, ticks: { ...baseScaleY.ticks, callback: v => v + ' ms' } }
                        }
                    }
                });

                // ── JITTER CHART ──────────────────────────────────────────
                const ctxJ = document.getElementById('dashJitterChart').getContext('2d');
                const jGrad = ctxJ.createLinearGradient(0,0,0,100);
                jGrad.addColorStop(0,'rgba(251,191,36,0.3)');
                jGrad.addColorStop(1,'rgba(251,191,36,0)');
                this.jitterChart = new Chart(ctxJ, {
                    type: 'bar',
                    data: {
                        labels: this.chartData.labels,
                        datasets: [{
                            data: this.chartData.jitter,
                            backgroundColor: this.chartData.jitter.map(v => v > 20 ? 'rgba(239,68,68,0.7)' : 'rgba(251,191,36,0.6)'),
                            hoverBackgroundColor: '#fbbf24',
                            borderRadius: 4, borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        animation: { duration: 500 },
                        plugins: {
                            legend: { display: false },
                            tooltip: { ...baseTooltip, callbacks: { label: ctx => ` Jitter: ${ctx.parsed.y} ms` } }
                        },
                        scales: {
                            x: { display: false },
                            y: { ...baseScaleY, min: 0, ticks: { ...baseScaleY.ticks, callback: v => v + 'ms' } }
                        }
                    }
                });

                // ── PACKET LOSS CHART ─────────────────────────────────────
                const ctxP = document.getElementById('dashLossChart').getContext('2d');
                const pGrad = ctxP.createLinearGradient(0,0,0,100);
                pGrad.addColorStop(0,'rgba(239,68,68,0.25)');
                pGrad.addColorStop(1,'rgba(239,68,68,0)');
                this.lossChart = new Chart(ctxP, {
                    type: 'line',
                    data: {
                        labels: this.chartData.labels,
                        datasets: [{
                            data: this.chartData.loss,
                            borderColor: '#ef4444',
                            backgroundColor: pGrad,
                            borderWidth: 2, tension: 0.35,
                            fill: true,
                            pointRadius: 3, pointHoverRadius: 6,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#0f172a',
                            spanGaps: true
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        animation: { duration: 500 },
                        plugins: {
                            legend: { display: false },
                            tooltip: { ...baseTooltip, callbacks: { label: ctx => ` Loss: ${ctx.parsed.y}%` } }
                        },
                        scales: {
                            x: { display: false },
                            y: { ...baseScaleY, min: 0, ticks: { ...baseScaleY.ticks, callback: v => v + '%' } }
                        }
                    }
                });
            },
            update(payload) {
                if (!payload) return;
                const d = Array.isArray(payload) ? payload[0] : payload;
                if (!d || !d.labels) return;

                // Update latency
                const series = d.monitorSeries || [];
                if (series.length > 0) {
                    series.forEach((s, i) => {
                        if (this.latencyChart.data.datasets[i]) this.latencyChart.data.datasets[i].data = s.data;
                    });
                } else if (this.latencyChart.data.datasets[0]) {
                    this.latencyChart.data.datasets[0].data = d.latency;
                }
                this.latencyChart.data.labels = d.labels;
                this.latencyChart.update('active');

                // Update jitter colors
                this.jitterChart.data.datasets[0].data = d.jitter;
                this.jitterChart.data.datasets[0].backgroundColor = d.jitter.map(v => v > 20 ? 'rgba(239,68,68,0.7)' : 'rgba(251,191,36,0.6)');
                this.jitterChart.data.labels = d.labels;
                this.jitterChart.update('active');

                // Update loss
                this.lossChart.data.datasets[0].data = d.loss;
                this.lossChart.data.labels = d.labels;
                this.lossChart.update('active');

                // Recompute badges
                const jVals = d.jitter.filter(v => v !== null);
                const lVals = d.loss.filter(v => v !== null);
                this.avgJitter = jVals.length ? (jVals.reduce((a,b)=>a+b,0)/jVals.length).toFixed(1) : 0;
                this.avgLoss   = lVals.length ? (lVals.reduce((a,b)=>a+b,0)/lVals.length).toFixed(2) : 0;
            }
        }"
        x-init="
            $nextTick(() => initCharts());
            // Register the update listener only once using a named variable
            if (!$el.__chartListenerAdded) {
                $el.__chartListenerAdded = true;
                $wire.on('update-charts', data => update(data));
            }
        ">

        {{-- Main Latency Chart (full width) --}}
        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <i class="ph-fill ph-chart-line-up text-emerald-400"></i>
                        Response Latency
                        <span class="text-xs text-slate-500 font-normal">· last 60 min · 3-min buckets</span>
                    </h3>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold">
                        {{ $chartData['monitorCount'] }} active monitor{{ $chartData['monitorCount'] !== 1 ? 's' : '' }}
                    </span>
                    <span class="px-2.5 py-1 rounded-full bg-slate-700 text-slate-300 border border-slate-600 font-bold font-mono">
                        avg {{ $avgLatency }} ms
                    </span>
                </div>
            </div>
            <div class="h-64 w-full" wire:ignore>
                <canvas id="dashLatencyChart"></canvas>
            </div>
        </div>

        {{-- Two mini charts side by side --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">

            {{-- Jitter --}}
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                            <i class="ph-fill ph-wave-sine text-base"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Jitter</p>
                            <p class="text-xs text-slate-500">Latency variance</p>
                        </div>
                    </div>
                    <span class="text-xs font-black font-mono px-2.5 py-1 rounded-lg border"
                        :class="avgJitter > 20 ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20'">
                        avg <span x-text="avgJitter"></span> ms
                    </span>
                </div>
                <div class="h-28 w-full" wire:ignore>
                    <canvas id="dashJitterChart"></canvas>
                </div>
            </div>

            {{-- Packet Loss --}}
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center">
                            <i class="ph-fill ph-warning-circle text-base"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Packet Loss</p>
                            <p class="text-xs text-slate-500">Avg over window</p>
                        </div>
                    </div>
                    <span class="text-xs font-black font-mono px-2.5 py-1 rounded-lg border"
                        :class="avgLoss > 1 ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'">
                        avg <span x-text="avgLoss"></span>%
                    </span>
                </div>
                <div class="h-28 w-full" wire:ignore>
                    <canvas id="dashLossChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ RECENT ACTIVITY TABLE ═══ --}}
    <div class="bg-slate-800 border border-slate-700 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-4 border-b border-slate-700 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center gap-2">
                <i class="ph-fill ph-list-dashes text-emerald-500"></i> Monitor Status
            </h3>
            <a href="{{ route('monitors') }}" wire:navigate
                class="text-xs font-semibold text-slate-300 hover:text-white bg-slate-700 hover:bg-slate-600 border border-slate-600 px-3 py-1.5 rounded-lg transition-colors">
                View All <i class="ph-bold ph-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-900/50 text-xs uppercase text-slate-500 font-bold">
                    <tr>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Monitor</th>
                        <th class="px-5 py-3">IP Address</th>
                        <th class="px-5 py-3">Latency</th>
                        <th class="px-5 py-3">Packet Loss</th>
                        <th class="px-5 py-3">Last Check</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($monitors as $monitor)
                    <tr class="hover:bg-slate-700/20 transition-colors {{ $monitor->status === 'offline' ? 'border-l-2 border-l-red-500 bg-red-500/5' : '' }}">
                        <td class="px-5 py-3.5">
                            @if($monitor->status === 'online')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>ONLINE
                                </span>
                            @elseif($monitor->status === 'offline')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>OFFLINE
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>{{ strtoupper($monitor->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-white">{{ $monitor->name }}</td>
                        <td class="px-5 py-3.5 font-mono text-slate-400 text-xs">{{ $monitor->ip_address }}</td>
                        <td class="px-5 py-3.5 font-mono font-bold
                            {{ $monitor->status === 'offline' ? 'text-red-400' : ($monitor->latency > 200 ? 'text-amber-400' : 'text-emerald-400') }}">
                            {{ $monitor->status === 'offline' ? 'TIMEOUT' : number_format($monitor->latency) . ' ms' }}
                        </td>
                        <td class="px-5 py-3.5">
                            @php $loss = $monitor->packet_loss ?? 0; @endphp
                            <span class="font-mono font-bold {{ $loss > 0 ? 'text-red-400' : 'text-slate-400' }}">
                                {{ $loss }}%
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 text-xs">
                            {{ $monitor->last_check?->diffForHumans() ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                            <i class="ph ph-monitor text-4xl block mb-2 opacity-20"></i>
                            No monitors configured yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
