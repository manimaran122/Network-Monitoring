<div class="p-6 lg:p-12 fade-in" x-data="{ 
        toasts: [],
        addToast(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 4000);
        }
     }" @roles-toast.window="addToast($event.detail.message, $event.detail.type)">

    {{-- ── Toast Stack ────────────────────────────────────────────── --}}
    <div class="fixed top-24 right-8 z-[100] flex flex-col gap-3 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0 scale-90"
                 x-transition:enter-end="translate-x-0 opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0 opacity-100 scale-100"
                 x-transition:leave-end="translate-x-full opacity-0 scale-90"
                 class="bg-slate-800 border-l-4 shadow-2xl flex items-center gap-4 px-6 py-4 rounded-xl pointer-events-auto min-w-[300px]"
                 :class="{
                    'border-emerald-500 bg-emerald-500/10': toast.type === 'success',
                    'border-blue-500 bg-blue-500/10': toast.type === 'info',
                    'border-red-500 bg-red-500/10': toast.type === 'error'
                 }">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                     :class="{
                        'bg-emerald-500/20 text-emerald-400': toast.type === 'success',
                        'bg-blue-500/20 text-blue-400': toast.type === 'info',
                        'bg-red-500/20 text-red-400': toast.type === 'error'
                     }">
                    <i class="ph ph-check-circle text-2xl" v-show="toast.type === 'success'"></i>
                    <i class="ph ph-info text-2xl" v-show="toast.type === 'info'"></i>
                    <i class="ph ph-warning-circle text-2xl" v-show="toast.type === 'error'"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-white" x-text="toast.message"></p>
                    <p class="text-[10px] text-slate-400">System Notification</p>
                </div>
            </div>
        </template>
    </div>

    {{-- ── Page Header ────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <nav class="flex items-center gap-2 text-[10px] uppercase font-black tracking-widest text-slate-500 mb-2">
                <a href="{{ route('settings') }}" class="hover:text-emerald-500 transition-colors">Settings</a>
                <i class="ph ph-caret-right"></i>
                <span class="text-slate-300">Roles & Permissions</span>
            </nav>
            <h1 class="text-4xl font-black text-white flex items-center gap-4">
                Access Control
                <span class="text-xs bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-full border border-emerald-500/20 shadow-glow shadow-emerald-500/20">RBAC</span>
            </h1>
            <p class="text-slate-400 mt-2 text-sm max-w-lg">Manage system roles, granular permissions, and access levels for all network monitoring operations.</p>
        </div>

        <button wire:click="$set('showCreateModal', true)" 
                class="bg-emerald-600 hover:bg-emerald-500 text-white font-black px-6 py-4 rounded-2xl flex items-center gap-3 transition-all active:scale-95 shadow-xl shadow-emerald-600/20">
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="ph-bold ph-plus text-lg text-white"></i>
            </div>
            <span class="uppercase tracking-widest text-sm">Create Role</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- ── Role Sidebar ────────────────────────────────────────── --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-[2rem] overflow-hidden shadow-2xl">
                <div class="p-6 bg-slate-900/30 border-b border-slate-700 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">System Roles</h3>
                    <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 shadow-glow shadow-emerald-500"></div>
                </div>
                <div class="p-3 space-y-2">
                    @foreach($roles as $r)
                    <div wire:click="selectRole({{ $r->id }})" 
                         class="group p-4 rounded-2xl transition-all cursor-pointer relative overflow-hidden flex items-center justify-between
                                {{ $selectedRoleId === $r->id ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-600/20' : 'bg-slate-900/30 hover:bg-slate-700/30 text-slate-400 hover:text-white' }}">
                        <div class="flex items-center gap-4 relative z-10 transition-transform group-hover:translate-x-1">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0
                                        {{ $selectedRoleId === $r->id ? 'bg-white/20' : 'bg-slate-800 group-hover:bg-slate-700' }}">
                                <i class="ph-fill ph-{{ $r->name === 'admin' ? 'shield-star' : ($r->name === 'viewer' ? 'eye' : 'user-gear') }}"></i>
                            </div>
                            <div>
                                <div class="font-black uppercase tracking-widest text-[10px]">{{ $r->name }}</div>
                                <div class="text-[9px] uppercase font-bold opacity-60">{{ $r->permissions->count() }} active scopes</div>
                            </div>
                        </div>

                        {{-- Hover Actions --}}
                        @if(!in_array($r->name, ['admin', 'viewer']))
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity relative z-20">
                             <button wire:click.stop="openRoleModal({{ $r->id }})" class="p-2 hover:bg-white/20 rounded-lg">
                                 <i class="ph-bold ph-pencil-simple text-sm"></i>
                             </button>
                             <button wire:click.stop="deleteRole({{ $r->id }})" wire:confirm="Are you sure?" class="p-2 hover:bg-white/20 rounded-lg">
                                 <i class="ph-bold ph-trash text-sm"></i>
                             </button>
                        </div>
                        @endif

                        {{-- Glow background for active --}}
                        @if($selectedRoleId === $r->id)
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/20 blur-2xl rounded-full"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Main Workbench ──────────────────────────────────────── --}}
        <div class="lg:col-span-3 space-y-6">
            @if($selectedRole)
            <div class="bg-slate-800 border border-slate-700 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                {{-- Header Pane --}}
                <div class="p-8 lg:p-12 bg-slate-900/50 border-b border-slate-700 relative z-10">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-3xl text-emerald-400 shadow-glow shadow-emerald-500/10">
                                <i class="ph-bold ph-shield-check"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-white uppercase tracking-tight">{{ $selectedRole->name }}</h2>
                                <p class="text-slate-400 text-sm mt-1">Configuring permissions for web-guard context</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-12 bg-slate-900 border border-slate-700 p-6 rounded-3xl">
                             <div class="text-center">
                                 <div class="text-2xl font-black text-white">{{ $selectedRole->permissions->count() }}</div>
                                 <div class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mt-1">Granted</div>
                             </div>
                             <div class="w-px h-10 bg-slate-700"></div>
                             <div class="text-center">
                                 <div class="text-2xl font-black text-slate-700">{{ $permissions->count() - $selectedRole->permissions->count() }}</div>
                                 <div class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mt-1">Revoked</div>
                             </div>
                        </div>
                    </div>
                </div>

                {{-- Permissions Grid --}}
                <div class="p-8 lg:p-12 relative z-10 bg-grid-slate-700/[0.05]">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($permissions as $perm)
                        @php $hasPerm = $selectedRole->hasPermissionTo($perm->name); @endphp
                        <div wire:click="togglePermission('{{ $perm->name }}')" 
                             class="group p-5 rounded-3xl border-2 transition-all cursor-pointer relative overflow-hidden
                                    {{ $hasPerm ? 'bg-emerald-600/5 border-emerald-500/30' : 'bg-slate-900/30 border-slate-700/50 hover:border-slate-600 hover:bg-slate-700/20' }}">
                            
                            <div class="flex items-start justify-between relative z-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl transition-all
                                                {{ $hasPerm ? 'bg-emerald-500 text-white shadow-glow shadow-emerald-500/30' : 'bg-slate-800 text-slate-500 group-hover:text-slate-300' }}">
                                        <i class="ph ph-{{ $hasPerm ? 'check-circle' : 'fingerprint' }}"></i>
                                    </div>
                                    <div>
                                        <div class="font-black text-xs uppercase tracking-widest {{ $hasPerm ? 'text-white' : 'text-slate-400 group-hover:text-slate-300' }}">
                                            {{ str_replace('_', ' ', $perm->name) }}
                                        </div>
                                        <div class="text-[10px] mt-1 text-slate-500 font-medium">System Capability</div>
                                    </div>
                                </div>
                                <div class="relative w-10 h-5 mt-1">
                                     <div class="block w-10 h-5 rounded-full transition-colors {{ $hasPerm ? 'bg-emerald-500' : 'bg-slate-700' }}"></div>
                                     <div class="absolute top-1 left-1 w-3 h-3 bg-white rounded-full transition-transform {{ $hasPerm ? 'translate-x-5' : '' }}"></div>
                                </div>
                            </div>
                            
                            {{-- Decorative glow --}}
                            @if($hasPerm)
                            <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-emerald-500/10 blur-2xl rounded-full"></div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Overlay decoration --}}
                <div class="absolute top-0 right-0 w-[50%] h-[100%] bg-emerald-500/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── Modals ──────────────────────────────────────────────── --}}

    {{-- Create Role Modal --}}
    @if($showCreateModal)
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[200] flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-700 w-full max-w-sm rounded-[2.5rem] shadow-2xl overflow-hidden shadow-emerald-600/10 scale-in">
            <div class="p-8 bg-slate-950/50 border-b border-slate-700 text-center">
                 <div class="w-20 h-20 bg-emerald-500/10 text-emerald-400 rounded-[1.5rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-glow shadow-emerald-500/20">
                     <i class="ph-bold ph-plus-circle"></i>
                 </div>
                 <h2 class="text-2xl font-black text-white uppercase tracking-tight">Define Role</h2>
                 <p class="text-slate-500 text-xs mt-2 font-medium tracking-wide">Enter the internal system identifier</p>
            </div>
            <form wire:submit="createRole">
                <div class="p-8 space-y-6">
                    <div class="relative">
                        <i class="ph ph-key absolute left-5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                        <input wire:model="newRoleName" type="text" placeholder="e.g. administrator"
                               class="w-full bg-slate-800 border-2 border-slate-700/50 rounded-2xl pl-12 pr-5 py-4 text-sm text-white focus:border-emerald-500 outline-none transition-all placeholder:text-slate-600">
                        @error('newRoleName') <span class="text-red-400 text-[10px] font-bold uppercase tracking-widest mt-2 block ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex gap-4">
                        <button type="button" wire:click="$set('showCreateModal', false)"
                                class="flex-1 py-4 text-sm font-black text-slate-400 hover:text-white transition-colors tracking-widest uppercase">
                            Discard
                        </button>
                        <button type="submit"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 rounded-2xl transition-all active:scale-95 shadow-lg shadow-emerald-600/20 uppercase tracking-widest text-sm">
                            Generate
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Edit Role Modal --}}
    @if($editingRoleId)
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[200] flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-700 w-full max-w-sm rounded-[2.5rem] shadow-2xl overflow-hidden scale-in">
            <div class="p-8 text-center border-b border-slate-700 bg-slate-950/50">
                 <div class="w-20 h-20 bg-blue-500/10 text-blue-400 rounded-[1.5rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-glow shadow-blue-500/20">
                     <i class="ph-bold ph-pencil-circle"></i>
                 </div>
                 <h2 class="text-2xl font-black text-white uppercase tracking-tight">Refine Role</h2>
            </div>
            <form wire:submit="updateRole">
                <div class="p-8 space-y-6">
                    <input wire:model="editingRoleName" type="text"
                               class="w-full bg-slate-800 border-2 border-slate-700/50 rounded-2xl px-5 py-4 text-sm text-white focus:border-blue-500 outline-none transition-all">
                    @error('editingRoleName') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    
                    <div class="flex gap-4">
                        <button type="button" wire:click="$set('editingRoleId', null)"
                                class="flex-1 py-4 text-sm text-slate-400 hover:text-white font-black uppercase tracking-widest transition-colors">Cancel</button>
                        <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-black py-4 rounded-2xl transition-all active:scale-95 uppercase tracking-widest text-sm">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .shadow-glow { box-shadow: 0 0 40px -10px currentColor; }
        .scale-in { animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        @keyframes scaleIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</div>
