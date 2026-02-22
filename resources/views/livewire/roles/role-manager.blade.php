<x-settings.layout heading="Role Management" class="max-w-7xl mx-auto" :noSidebar="true">
    <x-settings.top-nav active="roles" />
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
        @if (session()->has('message'))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                <div class="flex">
                    <div>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center py-4">
             <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Role Management
            </h2>
            <button wire:click="create()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded my-3">Create New Role</button>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs p-4 bg-white">
            <table id="roles-table" class="w-full whitespace-no-wrap display" style="width:100%">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Role Name</th>
                        <th class="px-4 py-3">Permissions</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        @push('scripts')
        <script>
            function initRoleTable() {
                if ($('#roles-table').length) {
                    if ($.fn.DataTable.isDataTable('#roles-table')) {
                        $('#roles-table').DataTable().destroy();
                    }

                    $('#roles-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('api.roles.datatable') }}", 
                        columns: [
                            { data: 'name', name: 'name' },
                            { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
                            { data: 'action', name: 'action', orderable: false, searchable: false },
                        ]
                    });
                }
            }

            document.addEventListener('livewire:initialized', function () {
                initRoleTable();

                Livewire.on('role-saved', () => {
                    if ($.fn.DataTable.isDataTable('#roles-table')) {
                        $('#roles-table').DataTable().ajax.reload(null, false);
                    }
                });
            });

            document.addEventListener('livewire:navigated', function () {
                initRoleTable();
            });
        </script>
        @endpush
    </div>
    <!-- Modal -->
    <x-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            {{ $roleId ? 'Edit Role' : 'Create Role' }}
        </x-slot>
        
        <x-slot name="content">
            <div class="mb-4">
                <x-label for="name" value="Name" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Enter Role Name" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            
            <div class="mb-4">
                <x-label value="Assign Permissions" class="mb-2" />
                @if($permissions->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 border p-4 rounded-lg bg-gray-50 max-h-60 overflow-y-auto">
                        @foreach($permissions as $permission)
                            <div class="flex items-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No permissions found. Create permissions first.</p>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal()" wire:loading.attr="disabled">
                Cancel
            </x-secondary-button>

            <x-button class="ml-2" wire:click="store()" wire:loading.attr="disabled">
                {{ $roleId ? 'Update Role' : 'Save Role' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</x-settings.layout>
