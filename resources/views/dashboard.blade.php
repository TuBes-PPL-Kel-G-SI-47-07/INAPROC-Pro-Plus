<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @role('admin')
                        <h1 class="text-2xl font-bold">Halo Admin!</h1>
                        <p>Anda memiliki akses penuh ke seluruh sistem INAPROC+.</p>
                    @endrole

                    @role('vendor')
                        <h1 class="text-2xl font-bold">Halo Vendor!</h1>
                        <p>Silakan lengkapi data geotagging Anda pada menu yang tersedia.</p>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
