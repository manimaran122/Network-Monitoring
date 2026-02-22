<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 md:hidden" x-transition.opacity></div>

<aside class="fixed inset-y-0 left-0 w-64 bg-slate-950 border-r border-slate-800 flex flex-col z-30 transition-transform duration-300 transform md:translate-x-0 md:static md:flex shadow-xl"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
        <div class="flex items-center">
            <i class="ph-fill ph-radar text-primary-500 text-2xl mr-3 animate-pulse"></i>
            <span class="text-lg font-bold tracking-wide text-white">Monitoring Tool</span>
        </div>
        <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
            <i class="ph ph-x text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        <p class="px-3 text-xs font-bold text-slate-500 uppercase mb-2">Monitoring</p>
        
        <a href="{{ route('dashboard') }}" wire:navigate class="w-full flex items-center px-3 py-2.5 rounded-lg group transition-all nav-btn {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-primary-500 border border-slate-700 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="ph ph-squares-four text-xl mr-3"></i><span class="font-medium">Overview</span>
        </a>

        <!-- Add Monitor Button (Critical: Inside Sidebar) -->
        <button x-data @click="$dispatch('open-add-monitor')" class="w-full flex items-center px-3 py-2.5 rounded-lg group transition-colors text-slate-400 hover:text-white hover:bg-slate-800 mt-2 mb-2 border border-dashed border-slate-700 hover:border-primary-500/50">
            <i class="ph-bold ph-plus text-xl mr-3 text-primary-500"></i><span class="font-medium text-primary-500">Add New Monitor</span>
        </button>

        <a href="{{ route('monitors') }}" wire:navigate class="w-full flex items-center px-3 py-2.5 rounded-lg group transition-colors nav-btn {{ request()->routeIs('monitors') ? 'bg-slate-800 text-primary-500 border border-slate-700 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="ph ph-list-dashes text-xl mr-3 group-hover:text-blue-400"></i><span class="font-medium">Monitors</span>
        </a>

        <a href="{{ route('alerts') }}" wire:navigate class="w-full flex items-center px-3 py-2.5 rounded-lg group transition-colors nav-btn {{ request()->routeIs('alerts') ? 'bg-slate-800 text-red-400 border border-slate-700 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="ph ph-warning-octagon text-xl mr-3 group-hover:text-red-400"></i><span class="font-medium">Alerts</span>
            @php try { $openAlerts = \App\Models\Alert::open()->count(); } catch(\Throwable $e) { $openAlerts = 0; } @endphp
            @if($openAlerts > 0)
            <span class="ml-auto bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full shadow shadow-red-500/30 animate-pulse">
                {{ $openAlerts > 99 ? '99+' : $openAlerts }}
            </span>
            @endif
        </a>

        <p class="px-3 text-xs font-bold text-slate-500 uppercase mt-6 mb-2">Management</p>

        <a href="{{ route('reports') }}" wire:navigate class="w-full flex items-center px-3 py-2.5 rounded-lg group transition-colors nav-btn {{ request()->routeIs('reports') ? 'bg-slate-800 text-primary-500 border border-slate-700 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="ph ph-file-text text-xl mr-3"></i><span class="font-medium">Reports</span>
        </a>

        <a href="{{ route('config') }}" wire:navigate class="w-full flex items-center px-3 py-2.5 rounded-lg group transition-colors nav-btn {{ request()->routeIs('config') ? 'bg-slate-800 text-primary-500 border border-slate-700 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="ph ph-gear text-xl mr-3"></i><span class="font-medium">Settings</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-800 bg-slate-900/50">
        <button x-data @click="$dispatch('open-user-profile')" class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity w-full text-left">
            <div class="relative">
                <img class="h-10 w-10 rounded-full border border-slate-600" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Guest') }}&background=0f172a&color=10b981" alt="">
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-primary-500 border-2 border-slate-900 rounded-full"></span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Guest' }}</span>
                <span class="text-xs text-slate-500">View Profile</span>
            </div>
        </button>
    </div>
</aside>
