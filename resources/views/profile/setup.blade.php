<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setup Profil Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border-l-4 border-indigo-500">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Informasi Identitas Vendor') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Pastikan data ini akurat untuk acuan survey lapangan (PBI-04).") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="profile_picture" :value="__('Logo / Foto Profil Perusahaan')" />
                                @if(Auth::user()->profile_picture)
                                    <div class="mt-2 mb-4">
                                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="h-24 w-24 rounded-lg object-cover border shadow-sm">
                                    </div>
                                @endif
                                <input id="profile_picture" name="profile_picture" type="file" class="mt-1 block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50" />
                            </div>

                            <div>
                                <x-input-label for="name" :value="__('Nama Resmi Perusahaan')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Alamat Email Korespondensi')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Simpan Identitas Perusahaan') }}</x-primary-button>
                                @if (session('status') === 'profile-updated')
                                    <p class="text-sm text-green-600">{{ __('Berhasil diperbarui!') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>