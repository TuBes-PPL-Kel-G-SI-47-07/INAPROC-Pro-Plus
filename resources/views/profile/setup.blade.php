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

                            <div>
                                <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
                                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" placeholder="Contoh: 08123456789" />
                            </div>

                            <div>
                                <x-input-label for="address" :value="__('Alamat Kantor Utama')" />
                                <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-gray-50 py-3 px-4" rows="3" placeholder="Masukkan alamat lengkap kantor...">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <div class="space-y-2">
                                <x-input-label :value="__('Pinpoint Geotagging Lokasi Kantor (PBI-11)')" class="font-bold text-gray-800" />
                                <p class="text-xs text-gray-500">Klik pada peta di bawah ini untuk menandai posisi koordinat kantor operasional Anda secara akurat.</p>
                                
                                <!-- Leaflet.js CSS & JS CDN -->
                                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
                                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                                
                                <!-- Map Container -->
                                <div id="map" class="h-80 w-full rounded-2xl border-2 border-slate-200 shadow-inner z-0"></div>
                                
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <x-input-label for="latitude" :value="__('Latitude')" class="text-xs text-gray-500" />
                                        <x-text-input id="latitude" name="latitude" type="text" readonly class="mt-1 block w-full bg-slate-100 cursor-not-allowed font-mono text-sm" :value="old('latitude', $user->latitude ?? '-6.2088')" required />
                                    </div>
                                    <div>
                                        <x-input-label for="longitude" :value="__('Longitude')" class="text-xs text-gray-500" />
                                        <x-text-input id="longitude" name="longitude" type="text" readonly class="mt-1 block w-full bg-slate-100 cursor-not-allowed font-mono text-sm" :value="old('longitude', $user->longitude ?? '106.8456')" required />
                                    </div>
                                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var defaultLat = parseFloat(document.getElementById('latitude').value) || -6.2088;
            var defaultLng = parseFloat(document.getElementById('longitude').value) || 106.8456;

            var map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            function updateCoords(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(8);
                document.getElementById('longitude').value = lng.toFixed(8);
            }

            // On map click
            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                updateCoords(e.latlng.lat, e.latlng.lng);
            });

            // On marker drag
            marker.on('dragend', function (e) {
                var position = marker.getLatLng();
                updateCoords(position.lat, position.lng);
            });
        });
    </script>
</x-app-layout>