<div class="flex items-center text-sm">
    <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
        <div class="w-full h-full rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-bold">
            {{ substr($role->name, 0, 1) }}
        </div>
    </div>
    <div>
        <p class="font-semibold text-capitalize">{{ $role->name }}</p>
    </div>
</div>
