<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Tool - Master Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], mono: ['Roboto Mono', 'monospace'] },
                    colors: { 
                        slate: { 850: '#1e293b', 900: '#0f172a', 950: '#020617' },
                        primary: { 500: '#10b981', 600: '#059669' } // Emerald Green
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
        
        /* Animations */
        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        
        .pulse-red { animation: pulseRed 2s infinite; }
        @keyframes pulseRed { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }

        .bg-cyber-circuit {
            background-color: #0B1120;
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(6, 182, 212, 0.15) 0%, transparent 60%),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 10h80v80h-80z' fill='none' stroke='rgba(30, 41, 59, 0.5)' stroke-width='1'/%3E%3Cpath d='M30 30h40v40h-40z' fill='none' stroke='rgba(30, 41, 59, 0.5)' stroke-width='1'/%3E%3Ccircle cx='10' cy='10' r='2' fill='rgba(6, 182, 212, 0.1)'/%3E%3Ccircle cx='90' cy='10' r='2' fill='rgba(6, 182, 212, 0.1)'/%3E%3Ccircle cx='90' cy='90' r='2' fill='rgba(6, 182, 212, 0.1)'/%3E%3Ccircle cx='10' cy='90' r='2' fill='rgba(6, 182, 212, 0.1)'/%3E%3Cpath d='M50 10v20M50 70v20M10 50h20M70 50h20' stroke='rgba(51, 65, 85, 0.3)' stroke-width='1'/%3E%3C/svg%3E");
            background-size: 100% 100%, 120px 120px;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-cyber-circuit text-slate-200 font-sans antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">

    @auth
        @include('components.partials.navbar')

        {{-- ── Password expiry warning banner (last 10 days) ──────────────────── --}}
        @php
            $__pwDays = auth()->user()->passwordDaysRemaining();
            $__showPwWarning = auth()->user()->shouldShowPasswordWarning();
        @endphp
        @if($__showPwWarning)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 max-h-20"
            x-transition:leave-end="opacity-0 max-h-0"
            class="w-full bg-amber-900/50 border-b border-amber-500/40 overflow-hidden"
        >
            <div class="max-w-7xl mx-auto px-6 py-2 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-amber-300 text-sm">
                    <i class="ph-fill ph-warning text-amber-400 text-base shrink-0"></i>
                    <span>
                        <strong class="font-bold">Password Expiry Notice:</strong>
                        Your password will expire in
                        <strong class="font-bold text-amber-200">{{ $__pwDays }} {{ $__pwDays === 1 ? 'day' : 'days' }}</strong>.
                        Please change it to avoid being locked out.
                    </span>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="{{ route('password.change') }}"
                        class="text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-900 px-3 py-1 rounded-lg transition-colors"
                    >
                        Change Now
                    </a>
                    <button @click="show = false" class="text-amber-400 hover:text-amber-200 transition-colors">
                        <i class="ph-bold ph-x text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Password changed success flash ─────────────────────────────────── --}}
        @if(session('password_changed'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="w-full bg-emerald-900/40 border-b border-emerald-500/40"
        >
            <div class="max-w-7xl mx-auto px-6 py-2 flex items-center gap-2 text-emerald-300 text-sm">
                <i class="ph-fill ph-check-circle text-emerald-400"></i>
                {{ session('password_changed') }}
            </div>
        </div>
        @endif
    @endauth

    <main class="flex-1 p-6 relative w-full max-w-7xl mx-auto">
        {{ $slot }}
    </main>

    @auth
        <livewire:user-profile />
        <livewire:add-monitor />
    @endauth

    {{-- Alpine Plugins — loaded synchronously so they're available on alpine:init --}}
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Register plugins before Livewire boots Alpine
        document.addEventListener('alpine:init', () => {
            if (window.Alpine && window.AlpineCollapse) Alpine.plugin(window.AlpineCollapse);
            if (window.Alpine && window.AlpineFocus)    Alpine.plugin(window.AlpineFocus);
        });
    </script>

    @livewireScripts
    @stack('scripts')
</body>
</html>
