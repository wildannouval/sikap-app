<x-app-layout>
    <div class="container px-6 mx-auto grid" x-data="{ isModalOpen: false, selectedSurat: null }">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Pengajuan Surat Pengantar KP
        </h2>

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('mahasiswa.surat-pengantar.store') }}" method="POST">
                @csrf
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Lokasi Kerja Praktik <span class="text-red-600">*</span></span>
                    <input
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input @error('lokasi_kp_surat_pengantar') border-red-600 focus:border-red-400 focus:shadow-outline-red @enderror"
                        name="lokasi_kp_surat_pengantar"
                        placeholder="Contoh: PT. Google Indonesia"
                        value="{{ old('lokasi_kp_surat_pengantar') }}"
                        required
                    />
                    @error('lokasi_kp_surat_pengantar')
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Penerima Surat <span class="text-red-600">*</span></span>
                    <input
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input @error('penerima_surat_pengantar') border-red-600 focus:border-red-400 focus:shadow-outline-red @enderror"
                        name="penerima_surat_pengantar"
                        placeholder="Contoh: Yth. Bapak/Ibu HRD PT. Google Indonesia"
                        value="{{ old('penerima_surat_pengantar') }}"
                        required
                    />
                    @error('penerima_surat_pengantar')
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Alamat Lengkap Perusahaan <span class="text-red-600">*</span></span>
                    <textarea
                        class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray @error('alamat_surat_pengantar') border-red-600 focus:border-red-400 focus:shadow-outline-red @enderror"
                        rows="3"
                        name="alamat_surat_pengantar"
                        placeholder="Masukkan alamat lengkap perusahaan"
                        required
                    >{{ old('alamat_surat_pengantar') }}</textarea>
                    @error('alamat_surat_pengantar')
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Tembusan (Opsional)</span>
                    <input
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        name="tembusan_surat_pengantar"
                        placeholder="Contoh: Ketua Jurusan Teknik Industri"
                        value="{{ old('tembusan_surat_pengantar') }}"
                    />
                </label>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Ajukan Surat
                    </button>
                </div>
            </form>
        </div>

        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Riwayat Pengajuan</h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                {{-- Beri id pada tabel dan kosongkan tbody --}}
                <table class="w-full whitespace-no-wrap" id="riwayatSuratTable">
                    <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Lokasi KP</th>
                        <th class="px-4 py-3">Tgl Pengajuan</th>
                        <th class="px-4 py-3">Tgl Disetujui</th>
                        <th class="px-4 py-3">Tgl Pengambilan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    {{-- Dibiarkan kosong --}}
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
            <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 transform translate-y-1/2" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0  transform translate-y-1/2" @click.away="closeModal" @keydown.escape.window="isModalOpen = false" class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
                <header class="flex justify-end"><button class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700" @click="isModalOpen = false"> <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg></button></header>
                <div class="mt-4 mb-6">
                    <p class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Detail Pengajuan Surat
                    </p>
                    <div class="px-4 py-3 mb-8 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-inner">
                        <template x-if="selectedSurat">
                            <dl class="text-sm text-gray-700 dark:text-gray-400">
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2">
                                    <dt class="font-semibold md:col-span-1">Lokasi KP</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.lokasi_kp_surat_pengantar"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700">
                                    <dt class="font-semibold md:col-span-1">Penerima</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.penerima_surat_pengantar"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700">
                                    <dt class="font-semibold md:col-span-1">Alamat</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.alamat_surat_pengantar"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700" x-show="selectedSurat.tembusan_surat_pengantar">
                                    <dt class="font-semibold md:col-span-1">Tembusan</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.tembusan_surat_pengantar"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700" x-show="selectedSurat.nomor_surat_pengantar">
                                    <dt class="font-semibold md:col-span-1">Nomor Surat</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.nomor_surat_pengantar"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700">
                                    <dt class="font-semibold md:col-span-1">Tanggal Pengajuan</dt>
                                    <dd class="md:col-span-2" x-text="': ' + new Date(selectedSurat.tanggal_pengajuan_surat_pengantar).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700" x-show="selectedSurat.tanggal_disetujui_surat_pengantar">
                                    <dt class="font-semibold md:col-span-1">Tanggal Disetujui</dt>
                                    <dd class="md:col-span-2" x-text="': ' + new Date(selectedSurat.tanggal_disetujui_surat_pengantar).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700" x-show="selectedSurat.tanggal_pengambilan_surat_pengantar">
                                    <dt class="font-semibold md:col-span-1">Tanggal Pengambilan</dt>
                                    <dd class="md:col-span-2" x-text="': ' + new Date(selectedSurat.tanggal_pengambilan_surat_pengantar).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700" x-show="selectedSurat.status_surat_pengantar === 'ditolak' && selectedSurat.catatan_surat_pengantar">
                                    <dt class="font-semibold md:col-span-1">Catatan Penolakan</dt>
                                    <dd class="md:col-span-2 text-red-600 dark:text-red-400" x-text="': ' + selectedSurat.catatan_surat_pengantar"></dd>
                                </div>
                            </dl>
                        </template>
                    </div>
                </div>
                <footer class="flex justify-end px-6 py-3 -mx-6 -mb-4 bg-gray-50 dark:bg-gray-800"><button @click="isModalOpen = false" class="px-5 py-3 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">Tutup</button></footer>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#riwayatSuratTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('mahasiswa.surat-pengantar.datatable') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'lokasi_kp_surat_pengantar', name: 'lokasi_kp_surat_pengantar' },
                        { data: 'tanggal_pengajuan_surat_pengantar', name: 'tanggal_pengajuan_surat_pengantar' },
                        { data: 'tanggal_disetujui_surat_pengantar', name: 'tanggal_disetujui_surat_pengantar' },
                        { data: 'tanggal_pengambilan_surat_pengantar', name: 'tanggal_pengambilan_surat_pengantar' },
                        { data: 'status_surat_pengantar', name: 'status_surat_pengantar' },
                        { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
