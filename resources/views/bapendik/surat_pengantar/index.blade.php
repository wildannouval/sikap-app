<x-app-layout>
    <div class="container px-6 mx-auto grid" x-data="suratPengantarModal()">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Validasi Surat Pengantar KP
        </h2>

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap" id="suratPengantarTable">
                    <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Mahasiswa</th>
                        <th class="px-4 py-3">NIM</th>
                        <th class="px-4 py-3">Lokasi KP</th>
                        <th class="px-4 py-3">Tanggal Pengajuan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"></tbody>
                </table>
            </div>
        </div>

        {{-- Modal untuk Aksi Cepat --}}
        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
            <div @click.away="closeModal()" x-show="isModalOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 transform translate-y-1/2" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0  transform translate-y-1/2" class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog">
                <header class="flex justify-end"><button class="inline-flex items-center justify-center w-6 h-6 text-gray-400" @click="closeModal()"> <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg></button></header>

                <div class="mt-4 mb-6">
                    <p class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Proses Pengajuan Surat</p>

                    <div class="px-4 py-3 mb-8 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-inner">
                        <template x-if="selectedSurat">
                            <dl class="text-sm text-gray-700 dark:text-gray-400">
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2">
                                    <dt class="font-semibold md:col-span-1">Nama Mahasiswa</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.mahasiswa.user.name"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700">
                                    <dt class="font-semibold md:col-span-1">NIM</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.mahasiswa.nim"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700">
                                    <dt class="font-semibold md:col-span-1">Lokasi KP</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.lokasi_kp_surat_pengantar"></dd>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 py-2 border-t dark:border-gray-700">
                                    <dt class="font-semibold md:col-span-1">Penerima Surat</dt>
                                    <dd class="md:col-span-2" x-text="': ' + selectedSurat.penerima_surat_pengantar"></dd>
                                </div>
                            </dl>
                        </template>
                    </div>

                    <form @submit.prevent="submitForm">
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Status <span class="text-red-600">*</span></span>
                            {{-- Class yang diperbarui --}}
                            <select x-model="formData.status_surat_pengantar" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                <option value="diajukan" :selected="selectedSurat && selectedSurat.status_surat_pengantar === 'diajukan'" disabled>Diajukan</option>
                                <option value="disetujui">Setujui</option>
                                <option value="ditolak">Tolak</option>
                            </select>
                        </label>

                        <div x-show="formData.status_surat_pengantar === 'disetujui'" class="mt-4 space-y-4">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Nomor Surat <span class="text-red-600">*</span></span>
                                {{-- Class yang diperbarui --}}
                                <input x-model="formData.nomor_surat_pengantar" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Contoh: 123/UN23.12.1/PK.04.01/2025" />
                                <template x-if="formErrors.nomor_surat_pengantar"><span x-text="formErrors.nomor_surat_pengantar[0]" class="text-xs text-red-600"></span></template>
                            </label>
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Tanggal Pengambilan <span class="text-red-600">*</span></span>
                                {{-- Class yang diperbarui --}}
                                <input x-model="formData.tanggal_pengambilan_surat_pengantar" type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                <template x-if="formErrors.tanggal_pengambilan_surat_pengantar"><span x-text="formErrors.tanggal_pengambilan_surat_pengantar[0]" class="text-xs text-red-600"></span></template>
                            </label>
                        </div>

                        <div x-show="formData.status_surat_pengantar === 'ditolak'" class="mt-4">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Catatan Penolakan <span class="text-red-600">*</span></span>
                                {{-- Class yang diperbarui --}}
                                <textarea x-model="formData.catatan_surat_pengantar" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Contoh: Alamat perusahaan tidak lengkap"></textarea>
                                <template x-if="formErrors.catatan_surat_pengantar"><span x-text="formErrors.catatan_surat_pengantar[0]" class="text-xs text-red-600"></span></template>
                            </label>
                        </div>

                        <footer class="flex items-center justify-end px-6 py-3 -mx-6 -mb-4 mt-6 space-x-4 bg-gray-50 dark:bg-gray-800">
                            <button type="button" @click="closeModal()" class="px-5 py-3 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400">Batal</button>
                            <button type="submit" class="px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700">Simpan Perubahan</button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Script DataTables (tidak ada perubahan) --}}
        <script>$(document).ready(function() {
                // Inisialisasi DataTables
                var table = $('#suratPengantarTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('bapendik.surat-pengantar.datatable') }}",
                        data: function (d) {
                            d.status = $('#statusFilter').val();
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'mahasiswa_name', name: 'mahasiswa.user.name' },
                        { data: 'mahasiswa_nim', name: 'mahasiswa.nim' },
                        { data: 'lokasi_kp_surat_pengantar', name: 'lokasi_kp_surat_pengantar' },
                        { data: 'tanggal_pengajuan_surat_pengantar', name: 'tanggal_pengajuan_surat_pengantar' },
                        { data: 'status_surat_pengantar', name: 'status_surat_pengantar' },
                        { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                    ],

                    // --- TAMBAHKAN BAGIAN INI ---
                    drawCallback: function () {
                        // Inisialisasi ulang Alpine.js pada elemen yang baru ditambahkan oleh DataTables
                        if (window.Alpine) {
                            window.Alpine.discoverUninitializedComponents(function (el) {
                                window.Alpine.initializeComponent(el)
                            })
                        }
                    }
                    // --- BATAS PENAMBAHAN ---
                });

                // Event listener untuk dropdown filter
                $('#statusFilter').on('change', function() {
                    table.draw();
                });
            });
        </script>

        {{-- Logika Alpine.js untuk Modal --}}
        <script>
            function suratPengantarModal() {
                return {
                    isModalOpen: false,
                    selectedSurat: null,
                    formData: {},
                    formErrors: {}, // [REVISI 2] State untuk menampung error
                    openModal(surat) {
                        this.selectedSurat = surat;
                        this.formErrors = {}; // Reset error setiap kali modal dibuka
                        this.formData = {
                            _token: '{{ csrf_token() }}',
                            _method: 'PATCH',
                            status_surat_pengantar: surat.status_surat_pengantar, // [REVISI 1 & 3] Selalu gunakan status terkini
                            nomor_surat_pengantar: surat.nomor_surat_pengantar || '',
                            tanggal_pengambilan_surat_pengantar: surat.tanggal_pengambilan_surat_pengantar || '',
                            catatan_surat_pengantar: surat.catatan_surat_pengantar || '',
                        };
                        this.isModalOpen = true;
                    },
                    closeModal() {
                        this.isModalOpen = false;
                        this.selectedSurat = null;
                    },
                    submitForm() {
                        this.formErrors = {}; // Kosongkan error sebelum submit
                        fetch(`/bapendik/surat-pengantar/${this.selectedSurat.id}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(this.formData)
                        })
                            .then(response => {
                                // [REVISI 2] Logika untuk menangkap error validasi
                                if (response.status === 422) {
                                    response.json().then(data => { this.formErrors = data.errors; });
                                    throw new Error('Validation error');
                                }
                                if (!response.ok) { throw new Error('Network response was not ok'); }
                                return response.json();
                            })
                            .then(data => {
                                this.closeModal();
                                $('#suratPengantarTable').DataTable().draw();
                            })
                            .catch(error => {
                                if (error.message !== 'Validation error') {
                                    console.error('Error:', error);
                                }
                            });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
