<div>
    {{-- ── Flash message ─────────────────────────────────────── --}}
    @if(session('message'))
    <div class="mb-4 flex items-center gap-2 bg-emerald-900/30 border border-emerald-500/30 text-emerald-300 text-sm px-4 py-3 rounded-lg">
        <i class="ph-fill ph-check-circle text-emerald-400"></i>
        {{ session('message') }}
    </div>
    @endif

    <div class="flex justify-between items-center bg-slate-800 p-4 rounded-lg border border-slate-700 mb-4">
        <div class="flex items-center gap-4">
            <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-fill ph-users-three"></i> System Users</h3>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..." class="bg-slate-900 border border-slate-600 rounded-md px-3 py-1 text-sm text-white focus:border-blue-500 outline-none">
        </div>
        <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 rounded-lg text-xs font-bold flex items-center gap-2 transition-colors">
            <i class="ph-bold ph-user-plus"></i> Add User
        </button>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-lg">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-900/50 text-xs uppercase text-slate-500 font-bold">
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Last Login</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-sm">
                @forelse($users as $user)
                <tr class="hover:bg-slate-800/80 transition-colors {{ !$user->status ? 'opacity-60' : '' }}">

                    {{-- User column: wrap flex in an inner div, NOT on the td --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img class="h-8 w-8 rounded-full bg-slate-700 shrink-0 {{ !$user->status ? 'grayscale' : '' }}"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=334155&color=fff"
                                 alt="{{ $user->name }}">
                            <div>
                                <div class="font-bold {{ $user->status ? 'text-white' : 'text-slate-400' }}">{{ $user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold px-2 py-0.5 rounded">ADMIN</span>
                        @else
                            <span class="bg-slate-700 text-slate-400 border border-slate-600 text-[10px] font-bold px-2 py-0.5 rounded">VIEWER</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   class="sr-only peer"
                                   wire:click="toggleStatus({{ $user->id }})"
                                   {{ $user->status ? 'checked' : '' }}
                                   {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <div class="relative w-9 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer
                                        peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full
                                        peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px]
                                        after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full
                                        after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ms-2 text-xs font-medium {{ $user->status ? 'text-emerald-400' : 'text-slate-500' }}">
                                {{ $user->status ? 'Active' : 'Disabled' }}
                            </span>
                        </label>
                    </td>

                    <td class="px-6 py-4 text-slate-300">
                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never' }}
                    </td>

                    {{-- Edit button — explicit type="button" prevents any form-submit conflict --}}
                    <td class="px-6 py-4 text-right">
                        <button type="button"
                                wire:click="openModal({{ $user->id }})"
                                title="Edit user"
                                class="text-slate-400 hover:text-white p-2 hover:bg-slate-700 rounded transition-colors">
                            <i class="ph-bold ph-pencil-simple"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-700">
            {{ $users->links() }}
        </div>
    </div>

    {{-- ── Modal — z-[60] to sit above the z-50 add-monitor / user-profile overlays --}}
    @if($isModalOpen)
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-slate-800 border border-slate-700 w-full max-w-md rounded-2xl shadow-2xl"
             @click.stop>{{-- stop propagation so click.outside on other overlays doesn't interfere --}}

            {{-- Header --}}
            <div class="p-6 border-b border-slate-700 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $editingUserId ? 'bg-blue-500/20' : 'bg-emerald-500/20' }}">
                        <i class="ph-bold {{ $editingUserId ? 'ph-pencil-simple text-blue-400' : 'ph-user-plus text-emerald-400' }}"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">{{ $editingUserId ? 'Edit User' : 'Create New User' }}</h3>
                </div>
                <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit="save">
                <div class="p-6 space-y-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Full Name</label>
                        <input wire:model="name" type="text" placeholder="e.g. John Smith"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
                        @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Email Address</label>
                        <input wire:model="email" type="email" placeholder="user@example.com"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
                        @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Role</label>
                        <select wire:model="role"
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
                            <option value="viewer">Viewer</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password field only for new users --}}
                    @if(!$editingUserId)
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">
                            Initial Password
                            <span class="ml-1 text-slate-500 font-normal">(user will be forced to change on first login)</span>
                        </label>
                        <input wire:model="password" type="password" placeholder="Minimum 8 characters"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
                        @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endif

                </div>

                <div class="p-6 border-t border-slate-700 flex justify-end gap-3">
                    <button type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-bold rounded-lg transition-colors {{ $editingUserId ? 'bg-blue-600 hover:bg-blue-500' : 'bg-emerald-600 hover:bg-emerald-500' }} text-white">
                        <span wire:loading.remove wire:target="save">{{ $editingUserId ? 'Update User' : 'Create User' }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin"></i> Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
