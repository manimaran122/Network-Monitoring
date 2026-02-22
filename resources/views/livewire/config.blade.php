<div class="view-section fade-in space-y-6" x-data="{ tab: 'users' }">
    
    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">System Settings</h2>
            <p class="text-sm text-slate-400">Manage access control, users, and audit logs.</p>
        </div>
        <div class="flex items-center gap-3 bg-slate-800 border border-slate-700 p-3 rounded-lg">
            <div class="text-right">
                <div class="text-xs font-bold text-white uppercase">User Registration</div>
                <div class="text-[10px] text-slate-400">Allow new accounts</div>
            </div>
            <div class="relative inline-block w-10 align-middle select-none">
                <input type="checkbox" checked id="toggle-reg" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer right-0 border-emerald-500"/>
                <label for="toggle-reg" class="toggle-label block overflow-hidden h-5 rounded-full bg-emerald-500 cursor-pointer"></label>
            </div>
        </div>
    </div>

    <div class="border-b border-slate-700 flex gap-6 text-sm">
        <button @click="tab = 'users'" :class="tab === 'users' ? 'border-primary-500 text-white font-bold' : 'border-transparent text-slate-400 hover:text-white'" class="pb-3 border-b-2 transition-colors">User Management</button>
        <button @click="tab = 'logs'" :class="tab === 'logs' ? 'border-primary-500 text-white font-bold' : 'border-transparent text-slate-400 hover:text-white'" class="pb-3 border-b-2 transition-colors">Activity Logs</button>
    </div>

    <div x-show="tab === 'users'" class="space-y-4">
        <livewire:settings.user-management />
    </div>

    <div x-show="tab === 'logs'" class="space-y-4" style="display: none;">
        <livewire:settings.activity-logs />
    </div>
</div>
