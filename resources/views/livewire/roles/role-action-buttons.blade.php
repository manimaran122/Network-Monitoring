<div class="flex items-center gap-2">
    <button wire:click="edit({{ $role->id }})" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded text-xs">Edit</button>
    <button wire:click="delete({{ $role->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs" onclick="confirm('Are you sure you want to delete this Role?') || event.stopImmediatePropagation()">Delete</button>
</div>
