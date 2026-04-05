<header class="sticky top-0 h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-6 shrink-0 z-30 shadow-lg shadow-black/20">
    
    <div class="flex items-center gap-8">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <i class="ph-fill ph-radar text-primary-500 text-2xl animate-pulse"></i>
            <span class="text-lg font-bold tracking-wide text-white">Monitoring Tool</span>
        </a>

        <nav class="hidden md:flex items-center gap-1">
            <a href="{{ route('dashboard') }}" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn {{ request()->routeIs('dashboard') ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <i class="ph ph-squares-four mr-1"></i>Dashboard
            </a>
            <a href="{{ route('monitors') }}" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn {{ request()->routeIs('monitors') ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <i class="ph ph-list-dashes mr-1"></i>Monitors
            </a>
            <a href="{{ route('alerts') }}" wire:navigate class="relative px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn {{ request()->routeIs('alerts') ? 'text-red-400 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <i class="ph ph-warning-octagon mr-1"></i>Alerts
                @php try { $openAlerts = \App\Models\Alert::open()->count(); } catch(\Throwable $e) { $openAlerts = 0; } @endphp
                @if($openAlerts > 0)
                <span class="absolute -top-1 -right-1 h-4 min-w-4 px-1 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center leading-none shadow shadow-red-500/40">
                    {{ $openAlerts > 99 ? '99+' : $openAlerts }}
                </span>
                @endif
            </a>
            <a href="{{ route('reports') }}" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn {{ request()->routeIs('reports') ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <i class="ph ph-chart-bar mr-1"></i>Reports
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('settings') }}" wire:navigate class="px-3 py-2 text-sm font-medium rounded-lg transition-colors nav-btn {{ request()->routeIs('settings') ? 'text-primary-500 bg-slate-800/50 border border-slate-700/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <i class="ph ph-gear mr-1"></i>Settings
            </a>
            @endif
        </nav>
    </div>

    <div class="flex items-center gap-4">
        


        <button @click="$dispatch('open-user-profile')" class="flex items-center gap-2 hover:bg-slate-800 p-1.5 rounded-lg transition-colors cursor-pointer">
            <img class="h-8 w-8 rounded-full border border-slate-600" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=334155&color=fff" alt="">
            <i class="ph-bold ph-caret-down text-slate-500 text-xs"></i>
        </button>

        <div class="h-8 w-px bg-slate-800 mx-2"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-slate-400 hover:text-red-400 p-2 rounded-lg hover:bg-slate-800 transition-colors" title="Logout">
                <i class="ph-bold ph-sign-out text-xl"></i>
            </button>
        </form>
    </div>
</header>
