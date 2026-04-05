<div class="view-section fade-in space-y-6">

    {{-- ── Flash message ─────────────────────────────────────── --}}
    @if(session('message'))
    <div class="flex items-center gap-2 bg-emerald-900/30 border border-emerald-500/30 text-emerald-300 text-sm px-4 py-3 rounded-lg">
        <i class="ph-fill ph-check-circle text-emerald-400"></i>
        {{ session('message') }}
    </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">System Settings</h2>
            <p class="text-sm text-slate-400">Manage access control, users, and audit logs.</p>
        </div>
        <div class="flex items-center gap-3 bg-slate-800 border border-slate-700 p-3 rounded-lg">
            <div class="text-right">
                <div class="text-xs font-bold text-white uppercase">User Registration</div>
                <div class="text-[10px] text-slate-400">{{ $registrationEnabled ? 'Allow new accounts' : 'Registration closed' }}</div>
            </div>
            <div class="relative inline-block w-10 align-middle select-none">
                <input type="checkbox" wire:click="toggleRegistration" @if($registrationEnabled) checked @endif id="toggle-reg" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer right-0 border-emerald-500"/>
                <label for="toggle-reg" class="toggle-label block overflow-hidden h-5 rounded-full {{ $registrationEnabled ? 'bg-emerald-500' : 'bg-slate-600' }} cursor-pointer"></label>
            </div>
        </div>
    </div>

    <div class="border-b border-slate-700 flex gap-6 text-sm">
        <button wire:click="switchTab('users')" class="pb-3 border-b-2 transition-colors {{ $activeTab === 'users' ? 'border-primary-500 text-white font-bold' : 'border-transparent text-slate-400 hover:text-white' }}">User Management</button>
        <button wire:click="switchTab('logs')" class="pb-3 border-b-2 transition-colors {{ $activeTab === 'logs' ? 'border-primary-500 text-white font-bold' : 'border-transparent text-slate-400 hover:text-white' }}">Activity Logs</button>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- USERS TAB                                                              --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'users')
    <div class="space-y-4">
        <div class="flex justify-between items-center bg-slate-800 p-4 rounded-lg border border-slate-700">
            <div class="flex items-center gap-4">
                <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-fill ph-users-three"></i> System Administrators</h3>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..." class="bg-slate-900 border border-slate-600 rounded-md px-3 py-1 text-sm text-white focus:border-emerald-500 outline-none">
            </div>
            <button type="button" x-data @click="$dispatch('open-create-user-modal')"
                    class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 rounded-lg text-xs font-bold flex items-center gap-2 transition-colors">
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

                        {{-- User column: flex is on inner div, NOT the td --}}
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
                                <input type="checkbox" class="sr-only peer"
                                       wire:click="toggleUserStatus({{ $user->id }})"
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

                        {{-- ✅ Edit button — type="button" + wire:click wired correctly --}}
                        <td class="px-6 py-4 text-right">
                            <button type="button"
                                    wire:click="openEditModal({{ $user->id }})"
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
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- LOGS TAB                                                               --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'logs')
    <div class="space-y-4">
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-scroll text-xl"></i>
                </div>
                <div>
                    <div class="text-xl font-bold text-white">{{ number_format($logStats['total']) }}</div>
                    <div class="text-xs text-slate-400">Total Events</div>
                </div>
            </div>
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-calendar-check text-xl"></i>
                </div>
                <div>
                    <div class="text-xl font-bold text-white">{{ number_format($logStats['today']) }}</div>
                    <div class="text-xs text-slate-400">Events Today</div>
                </div>
            </div>
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-sign-in text-xl"></i>
                </div>
                <div>
                    <div class="text-xl font-bold text-white">{{ number_format($logStats['logins']) }}</div>
                    <div class="text-xs text-slate-400">Logins Today</div>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex flex-col sm:flex-row gap-3 items-start sm:items-center">
            <div class="relative flex-1 w-full">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input wire:model.live.debounce.300ms="logSearch" type="text"
                    placeholder="Search by user, IP, or action..."
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg pl-9 pr-3 py-2 text-sm text-white focus:border-emerald-500 outline-none transition-colors">
            </div>
            <div class="flex gap-1 bg-slate-900 border border-slate-700 rounded-lg p-1 flex-wrap">
                @foreach(['all' => 'All', 'login' => 'Login', 'logout' => 'Logout', 'CREATE_USER' => 'Create User', 'UPDATE_USER' => 'Update User', 'UPDATE_SETTING' => 'Setting'] as $filterVal => $filterLabel)
                <button wire:click="$set('filterAction', '{{ $filterVal }}')"
                    class="px-2.5 py-1 text-xs font-semibold rounded-md transition-all whitespace-nowrap
                        {{ $filterAction === $filterVal ? 'bg-emerald-600 text-white shadow' : 'text-slate-400 hover:text-white' }}">
                    {{ $filterLabel }}
                </button>
                @endforeach
            </div>
            <button wire:click="clearLogs" wire:confirm="This will delete ALL activity logs. Are you sure?"
                class="flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-red-400 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 rounded-lg transition-colors whitespace-nowrap">
                <i class="ph-bold ph-trash"></i> Clear Logs
            </button>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-lg">
            <div class="p-4 border-b border-slate-700 flex items-center justify-between">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <i class="ph-fill ph-scroll text-emerald-500"></i> Activity Log
                </h3>
                <span class="text-xs text-slate-400">{{ $logs->total() }} events found</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/50 text-xs uppercase text-slate-500 font-bold">
                        <tr>
                            <th class="px-5 py-3">User</th>
                            <th class="px-5 py-3">Event</th>
                            <th class="px-5 py-3">Details</th>
                            <th class="px-5 py-3">IP Address</th>
                            <th class="px-5 py-3">Timestamp</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse($logs as $log)
                        @php
                            $actionColors = [
                                'login'          => ['pill' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'label' => 'SUCCESS'],
                                'logout'         => ['pill' => 'bg-slate-700 text-slate-300 border-slate-600',             'label' => 'SESSION END'],
                                'CREATE_USER'    => ['pill' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',           'label' => 'DONE'],
                                'UPDATE_USER'    => ['pill' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',        'label' => 'DONE'],
                                'UPDATE_SETTING' => ['pill' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',    'label' => 'DONE'],
                                'CLEAR_LOGS'     => ['pill' => 'bg-red-500/10 text-red-400 border-red-500/20',              'label' => 'DONE'],
                            ];
                            $c = $actionColors[$log->action] ?? ['pill' => 'bg-slate-700 text-slate-400 border-slate-600', 'label' => 'INFO'];
                        @endphp
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-5 py-3">
                                <div class="font-semibold text-white text-sm">{{ $log->user_name }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $log->user_email }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs px-2.5 py-1 rounded-full border font-bold uppercase {{ $c['pill'] }}">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-400 max-w-xs">
                                @if($log->details)
                                    <span class="text-sm text-slate-300">{{ $log->details }}</span>
                                @else
                                    <span class="text-slate-600 italic text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $log->ip_address ?? '—' }}</td>
                            <td class="px-5 py-3 text-xs text-slate-400 whitespace-nowrap">
                                <div>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y') }}</div>
                                <div class="text-slate-600">{{ \Carbon\Carbon::parse($log->created_at)->format('h:i:s A') }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-[10px] px-2 py-0.5 rounded border font-bold {{ $c['pill'] }}">
                                    {{ $c['label'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-500">
                                <i class="ph ph-scroll text-4xl block mb-2 opacity-20"></i>
                                <p class="text-sm font-semibold text-slate-400">No activity logs yet.</p>
                                <p class="text-xs mt-1">Logs are recorded automatically on login, logout, and admin actions.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
            <div class="p-4 border-t border-slate-700">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- CREATE USER MODAL (Alpine-driven open/close, Livewire form submit)     --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div x-data="{ open: false }"
         x-show="open"
         @open-create-user-modal.window="open = true"
         @close-modal.window="open = false"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-slate-800 border border-slate-700 w-full max-w-md rounded-2xl shadow-2xl">
            <div class="p-6 border-b border-slate-700 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                        <i class="ph-bold ph-user-plus text-blue-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Create New User</h3>
                </div>
                <button type="button" @click="open = false" class="text-slate-400 hover:text-white transition-colors">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>
            <form wire:submit="createUser">
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
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 outline-none">
                            <option value="viewer">Viewer</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">
                            Initial Password
                            <span class="ml-1 text-slate-500 font-normal">(user must change on first login)</span>
                        </label>
                        <input wire:model="password" type="password" placeholder="Minimum 8 characters"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all">
                        @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="p-6 border-t border-slate-700 flex justify-end gap-3">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">Cancel</button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-bold bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-colors">
                        <span wire:loading.remove wire:target="createUser">Create User</span>
                        <span wire:loading wire:target="createUser" class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin"></i> Creating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- EDIT USER MODAL (Livewire-driven open/close via showEditModal prop)    --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if($showEditModal)
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-slate-800 border border-slate-700 w-full max-w-md rounded-2xl shadow-2xl">
            <div class="p-6 border-b border-slate-700 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-500/20 flex items-center justify-center">
                        <i class="ph-bold ph-pencil-simple text-amber-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Edit User</h3>
                </div>
                <button type="button" wire:click="closeEditModal" class="text-slate-400 hover:text-white transition-colors">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>
            <form wire:submit="updateUser">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Full Name</label>
                        <input wire:model="editName" type="text"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 outline-none transition-all">
                        @error('editName') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Email Address</label>
                        <input wire:model="editEmail" type="email"
                               class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 outline-none transition-all">
                        @error('editEmail') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1.5">Role</label>
                        <select wire:model="editRole"
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white focus:border-amber-500 outline-none">
                            <option value="viewer">Viewer</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('editRole') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="p-6 border-t border-slate-700 flex justify-end gap-3">
                    <button type="button" wire:click="closeEditModal"
                            class="px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">Cancel</button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-bold bg-amber-600 hover:bg-amber-500 text-white rounded-lg transition-colors">
                        <span wire:loading.remove wire:target="updateUser">Save Changes</span>
                        <span wire:loading wire:target="updateUser" class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin"></i> Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
