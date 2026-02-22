<div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-lg">
    <div class="p-4 border-b border-slate-700 flex justify-between items-center">
        <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-fill ph-scroll"></i> Access Logs</h3>
        <button class="text-xs text-slate-400 hover:text-white border border-slate-600 px-2 py-1 rounded">Export CSV</button>
    </div>
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-900/50 text-xs uppercase text-slate-500 font-bold">
            <tr><th class="px-6 py-4">User</th><th class="px-6 py-4">Event</th><th class="px-6 py-4">IP Address</th><th class="px-6 py-4">Timestamp</th><th class="px-6 py-4">Status</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-700 text-sm">
            @forelse($logs as $log)
            <tr class="hover:bg-slate-750 transition-colors">
                <td class="px-6 py-4 font-bold text-white">{{ $log->user_name }}</td>
                <td class="px-6 py-4">
                    @if($log->action === 'login')
                    <span class="text-emerald-400 font-bold uppercase">LOGIN</span>
                    @elseif($log->action === 'logout')
                    <span class="text-slate-400 font-bold uppercase">LOGOUT</span>
                    @else
                    <span class="text-blue-400 font-bold uppercase">{{ $log->action }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 font-mono text-slate-400">{{ $log->ip_address }}</td>
                <td class="px-6 py-4 text-slate-300">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, h:i:s A') }}</td>
                <td class="px-6 py-4">
                    @if($log->action === 'login')
                    <span class="bg-emerald-500/10 text-emerald-500 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-500/20">SUCCESS</span>
                    @elseif($log->action === 'logout')
                    <span class="bg-slate-700 text-slate-300 px-2 py-0.5 rounded text-[10px] font-bold border border-slate-600">SESSION END</span>
                    @else
                    <span class="bg-blue-500/10 text-blue-500 px-2 py-0.5 rounded text-[10px] font-bold border border-blue-500/20">INFO</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-4 text-center text-slate-500">No logs found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-slate-700">
        {{ $logs->links() }}
    </div>
</div>
