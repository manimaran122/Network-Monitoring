<div class="flex flex-wrap gap-2">
    @forelse($role->permissions as $permission)
        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">
            {{ $permission->name }}
        </span>
    @empty
        <span class="text-gray-400 italic text-xs">No permissions assigned</span>
    @endforelse
</div>
