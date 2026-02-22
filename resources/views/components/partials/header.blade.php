<header class="h-16 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 flex items-center justify-between px-6 z-20 sticky top-0">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-400 hover:text-white">
            <i class="ph ph-list text-2xl"></i>
        </button>
        <h2 class="text-white font-semibold text-lg tracking-tight">{{ $title ?? 'System Overview' }}</h2>
        <div class="hidden md:flex items-center bg-slate-800 rounded-full px-4 py-1.5 border border-slate-700 w-64 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all ml-4">
            <i class="ph ph-magnifying-glass text-slate-500 mr-2"></i>
            <input type="text" placeholder="Search IP or Host..." class="bg-transparent border-none outline-none text-sm text-slate-200 placeholder-slate-500 w-full">
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 text-xs font-mono text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            SYSTEM ONLINE
        </div>

        <div class="h-6 w-px bg-slate-700 mx-1"></div>

        <!-- Optional: Add Monitor in Header as well, matching the HTML provided -->
        <button x-data @click="$dispatch('open-add-monitor')" class="bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold uppercase px-4 py-2 rounded-lg shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2 hover:-translate-y-0.5">
            <i class="ph-bold ph-plus"></i> Add Monitor
        </button>
    </div>
</header>
