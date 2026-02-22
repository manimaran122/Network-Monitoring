<div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity"
     x-data="{ open: @entangle('isOpen').live }"
     x-show="open"
     x-transition
     style="display: none;">
    <div class="bg-slate-900 border border-slate-700 w-full max-w-4xl rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" @click.outside="open = false">
        
        <!-- Header -->
        <div class="h-16 bg-slate-950 border-b border-slate-800 flex justify-between items-center px-6">
            <h2 class="text-xl font-bold text-white">User Profile</h2>
            <button wire:click="close" class="text-slate-500 hover:text-white"><i class="ph-bold ph-x text-xl"></i></button>
        </div>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar Tabs -->
            <div class="w-64 bg-slate-900 border-r border-slate-800 p-4 space-y-1">
                <button wire:click="switchTab('account')" class="w-full text-left px-4 py-3 rounded-lg flex items-center gap-3 transition-colors {{ $activeTab === 'account' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-user text-lg"></i> <span class="font-medium text-sm">Account Info</span>
                </button>
                <button wire:click="switchTab('security')" class="w-full text-left px-4 py-3 rounded-lg flex items-center gap-3 transition-colors {{ $activeTab === 'security' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-shield-check text-lg"></i> <span class="font-medium text-sm">Security</span>
                </button>
                <button wire:click="switchTab('sessions')" class="w-full text-left px-4 py-3 rounded-lg flex items-center gap-3 transition-colors {{ $activeTab === 'sessions' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="ph ph-desktop text-lg"></i> <span class="font-medium text-sm">Active Sessions</span>
                </button>
            </div>

            <!-- Content Area -->
            <div class="flex-1 p-8 overflow-y-auto bg-slate-900/50">
                
                @if($activeTab === 'account')
                    <div class="space-y-6 fade-in">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="h-20 w-20 rounded-full bg-slate-700 border-2 border-slate-600 overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=334155&color=fff&size=128" alt="" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">{{ $user->name ?? 'Guest' }}</h3>
                                <p class="text-slate-400">{{ $user->email ?? 'No Email' }}</p>
                                <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Administrator</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1 uppercase">Full Name</label>
                                <div class="px-4 py-3 bg-slate-950 border border-slate-800 rounded-lg text-slate-300">{{ $user->name ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1 uppercase">Email Address</label>
                                <div class="px-4 py-3 bg-slate-950 border border-slate-800 rounded-lg text-slate-300">{{ $user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($activeTab === 'security')
                    <div class="space-y-8 fade-in">
                        <!-- Password Update -->
                        <div class="bg-slate-950 border border-slate-800 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4">Update Password</h3>
                            <form wire:submit.prevent="updatePassword" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Current Password</label>
                                    <input wire:model="current_password" type="password" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-sm text-white focus:border-emerald-500 outline-none">
                                    @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">New Password</label>
                                        <input wire:model="new_password" type="password" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-sm text-white focus:border-emerald-500 outline-none">
                                        @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Confirm New Password</label>
                                        <input wire:model="new_password_confirmation" type="password" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-sm text-white focus:border-emerald-500 outline-none">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">Update Password</button>
                                </div>
                                @if (session()->has('status'))
                                    <div class="text-emerald-400 text-sm font-medium text-right">{{ session('status') }}</div>
                                @endif
                            </form>
                        </div>

                        <!-- MFA Toggle -->
                        <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white">Multi-Factor Authentication</h3>
                                <p class="text-sm text-slate-400 mt-1">Add an extra layer of security to your account.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium {{ $mfa_enabled ? 'text-emerald-400' : 'text-slate-500' }}">{{ $mfa_enabled ? 'Enabled' : 'Disabled' }}</span>
                                <button wire:click="toggleMfa" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $mfa_enabled ? 'bg-emerald-600' : 'bg-slate-700' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $mfa_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if($activeTab === 'sessions')
                    <div class="space-y-6 fade-in">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-2">Active Sessions</h3>
                            <p class="text-sm text-slate-400">View and manage sessions where you're currently logged in.</p>
                        </div>

                        <div class="bg-slate-950 border border-slate-800 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-900 text-slate-400 border-b border-slate-800">
                                    <tr>
                                        <th class="px-6 py-3 font-medium">Device / OS</th>
                                        <th class="px-6 py-3 font-medium">IP Address</th>
                                        <th class="px-6 py-3 font-medium">Last Active</th>
                                        <th class="px-6 py-3 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800 text-slate-300">
                                    @foreach($sessions as $session)
                                    <tr class="hover:bg-slate-900/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <i class="ph ph-desktop text-xl text-slate-500"></i>
                                                <span class="truncate max-w-[200px]" title="{{ $session->user_agent }}">{{ Str::limit($session->user_agent, 40) }}</span>
                                                @if($session->is_current_device)
                                                    <span class="text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded ml-2">This Device</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs">{{ $session->ip_address }}</td>
                                        <td class="px-6 py-4">{{ $session->last_active }}</td>
                                        <td class="px-6 py-4 text-right">
                                            @if(!$session->is_current_device)
                                                <button wire:click="logoutSession('{{ $session->id }}')" class="text-red-400 hover:text-red-300 text-xs font-medium hover:underline">Log Out</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
