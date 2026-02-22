<x-settings.layout heading="Department Management" class="max-w-7xl mx-auto" :noSidebar="true">
    <x-settings.top-nav active="departments" />
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
             <button wire:click="create()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create New Department</button>
        </div>
        <div class="w-full overflow-hidden rounded-lg shadow-xs p-4 bg-white">
            <table id="departments-table" class="w-full whitespace-no-wrap display" style="width:100%">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        @push('scripts')
        <script>
            function initDepartmentTable() {
                if ($('#departments-table').length) {
                    if ($.fn.DataTable.isDataTable('#departments-table')) {
                        $('#departments-table').DataTable().destroy();
                    }

                    $('#departments-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('api.departments.datatable') }}", 
                        columns: [
                            { data: 'name', name: 'name' },
                            { data: 'description', name: 'description' },
                            { data: 'action', name: 'action', orderable: false, searchable: false },
                        ]
                    });
                }
            }

            document.addEventListener('livewire:initialized', function () {
                initDepartmentTable();

                Livewire.on('department-saved', () => {
                    if ($.fn.DataTable.isDataTable('#departments-table')) {
                        $('#departments-table').DataTable().ajax.reload(null, false);
                    }
                });
            });

            document.addEventListener('livewire:navigated', function () {
                initDepartmentTable();
            });
        </script>
        @endpush
    </div>
    <!-- Modal -->
    <x-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            {{ $departmentId ? 'Edit Department' : 'Create Department' }}
        </x-slot>
        
        <x-slot name="content">
            <div class="mb-4">
                <x-label for="name" value="Name" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" placeholder="Enter Department Name" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            
            <div class="mb-4">
                <x-label for="description" value="Description" />
                <textarea id="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="description" placeholder="Enter Description"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal()" wire:loading.attr="disabled">
                Cancel
            </x-secondary-button>

            <x-button class="ml-2" wire:click="store()" wire:loading.attr="disabled">
                {{ $departmentId ? 'Update Department' : 'Save Department' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</x-settings.layout>
