<div class="flex items-center justify-center min-h-[calc(100vh-3rem)]">
    <div class="w-full max-w-md bg-slate-900/80 backdrop-blur-md border border-slate-700 rounded-2xl shadow-2xl overflow-hidden p-8 animate-fade-in-up">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-800 border border-slate-700 mb-4 shadow-inner">
                <i class="ph-fill ph-radar text-4xl text-emerald-500 animate-pulse"></i>
            </div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Monitoring Tool</h2>
        </div>

        {{-- Password reset success flash --}}
        @if(session('password_reset_success'))
        <div class="mb-5 flex items-center gap-2 bg-emerald-900/30 border border-emerald-500/30 text-emerald-300 text-sm px-4 py-3 rounded-lg">
            <i class="ph-fill ph-check-circle text-emerald-400 shrink-0"></i>
            {{ session('password_reset_success') }}
        </div>
        @endif

        <form wire:submit="login" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-envelope text-slate-500"></i>
                    </div>
                    <input wire:model="email" type="email" id="email"
                           class="block w-full pl-10 pr-3 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                           placeholder="admin@monitor.local">
                </div>
                @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-emerald-400 hover:text-emerald-300 transition-colors">
                        Forgot password?
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph-bold ph-lock text-slate-500"></i>
                    </div>
                    <input wire:model="password" type="password" id="password"
                           class="block w-full pl-10 pr-3 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                           placeholder="••••••••">
                </div>
                @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-emerald-500 transition-all uppercase tracking-wide">
                    <span wire:loading.remove>Sign In</span>
                    <span wire:loading class="flex items-center gap-2">
                        <i class="ph-bold ph-spinner animate-spin"></i> Authenticating...
                    </span>
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs text-slate-500">Authorized Personnel Only. System activity is monitored.</p>
        </div>
    </div>
</div>
