<?php

namespace App\Http\Controllers;

use App\Models\SuratPengantar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratPengantarController extends Controller
{
    /**
     * Menampilkan halaman surat pengantar untuk Mahasiswa.
     * (Formulir pengajuan dan riwayat pengajuan)
     */
    public function index()
    {
        // Ambil data surat pengantar milik mahasiswa yang sedang login
        $mahasiswaId = Auth::user()->mahasiswa->id;
        $suratPengantars = SuratPengantar::where('mahasiswa_id', $mahasiswaId)->orderBy('created_at', 'desc')->get();

        return view('mahasiswa.surat_pengantar.index', compact('suratPengantars'));
    }

    /**
     * Menyimpan data pengajuan surat pengantar baru dari Mahasiswa.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'lokasi_kp_surat_pengantar' => 'required|string|max:255',
            'penerima_surat_pengantar' => 'required|string|max:255',
            'alamat_surat_pengantar' => 'required|string',
            'tembusan_surat_pengantar' => 'nullable|string',
        ]);

        // Simpan data ke database
        SuratPengantar::create([
            'mahasiswa_id' => Auth::user()->mahasiswa->id,
            'lokasi_kp_surat_pengantar' => $request->lokasi_kp_surat_pengantar,
            'penerima_surat_pengantar' => $request->penerima_surat_pengantar,
            'alamat_surat_pengantar' => $request->alamat_surat_pengantar,
            'tembusan_surat_pengantar' => $request->tembusan_surat_pengantar,
            'tanggal_pengajuan_surat_pengantar' => now(),
        ]);

        return redirect()->route('mahasiswa.surat-pengantar.index')->with('success', 'Surat pengantar berhasil diajukan.');
    }

    /**
     * Menampilkan halaman validasi surat pengantar untuk Bapendik.
     */
    public function adminIndex()
    {
        // Ambil semua data surat pengantar
        $suratPengantars = SuratPengantar::with('mahasiswa.user')->orderBy('created_at', 'desc')->get();

        return view('bapendik.surat_pengantar.index', compact('suratPengantars'));
    }

    /**
     * Menampilkan halaman detail/edit untuk Bapendik memproses surat.
     */
//    public function edit(SuratPengantar $suratPengantar)
//    {
//        return view('bapendik.surat_pengantar.edit', compact('suratPengantar'));
//    }

    /**
     * Mengupdate status surat pengantar (disetujui/ditolak).
     */
    public function update(Request $request, SuratPengantar $suratPengantar)
    {
        $request->validate([
            'status_surat_pengantar' => 'required|in:disetujui,ditolak',
            'nomor_surat_pengantar' => 'required_if:status_surat_pengantar,disetujui|nullable|string|unique:surat_pengantars,nomor_surat_pengantar,' . $suratPengantar->id,
            'tanggal_pengambilan_surat_pengantar' => 'required_if:status_surat_pengantar,disetujui|nullable|date',
            'catatan_surat_pengantar' => 'required_if:status_surat_pengantar,ditolak|nullable|string',
        ]);

        $suratPengantar->status_surat_pengantar = $request->status_surat_pengantar;
        $suratPengantar->catatan_surat_pengantar = $request->catatan_surat_pengantar;

        if ($request->status_surat_pengantar == 'disetujui') {
            $suratPengantar->nomor_surat_pengantar = $request->nomor_surat_pengantar;
            $suratPengantar->tanggal_disetujui_surat_pengantar = now();
            $suratPengantar->tanggal_pengambilan_surat_pengantar = $request->tanggal_pengambilan_surat_pengantar;
        }

        $suratPengantar->save();
        return response()->json($suratPengantar);
    }

    public function datatable(Request $request)
    {
        $query = SuratPengantar::with('mahasiswa.user')->select('surat_pengantars.*');
        if ($request->filled('status') && $request->status != '') {
            $query->where('status_surat_pengantar', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('mahasiswa_name', function ($row) { return $row->mahasiswa->user->name; })
            ->addColumn('mahasiswa_nim', function ($row) { return $row->mahasiswa->nim; })
            ->editColumn('tanggal_pengajuan_surat_pengantar', function ($row) { return \Carbon\Carbon::parse($row->tanggal_pengajuan_surat_pengantar)->format('d F Y'); })
            ->editColumn('status_surat_pengantar', function ($row) {
                if ($row->status_surat_pengantar == 'disetujui') { return '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Disetujui</span>';
                } elseif ($row->status_surat_pengantar == 'ditolak') { return '<span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">Ditolak</span>';
                } else { return '<span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600">Diajukan</span>'; }
            })
            ->addColumn('aksi', function ($row) {
                $row->load('mahasiswa.user');
                $json_data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                $prosesButton = '<button @click="openModal(' . $json_data . ')" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Proses"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>';
                $exportButton = '';
                if ($row->status_surat_pengantar == 'disetujui') {
                    $exportUrl = route('bapendik.surat-pengantar.export-word', $row->id);
                    $exportButton = '<a href="' . $exportUrl . '" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Unduh"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></a>';
                }
                return '<div class="flex items-center space-x-2">' . $prosesButton . $exportButton . '</div>';
            })
            ->rawColumns(['status_surat_pengantar', 'aksi'])
            ->make(true);
    }

    public function mahasiswaDatatable()
    {
        $mahasiswaId = auth()->user()->mahasiswa->id;
        $query = SuratPengantar::where('mahasiswa_id', $mahasiswaId);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('tanggal_pengajuan_surat_pengantar', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal_pengajuan_surat_pengantar)->format('d/m/Y');
            })
            ->editColumn('tanggal_disetujui_surat_pengantar', function ($row) {
                return $row->tanggal_disetujui_surat_pengantar ? \Carbon\Carbon::parse($row->tanggal_disetujui_surat_pengantar)->format('d/m/Y') : '-';
            })
            ->editColumn('tanggal_pengambilan_surat_pengantar', function ($row) {
                return $row->tanggal_pengambilan_surat_pengantar ? \Carbon\Carbon::parse($row->tanggal_pengambilan_surat_pengantar)->format('d/m/Y') : '-';
            })
            ->editColumn('status_surat_pengantar', function ($row) {
                if ($row->status_surat_pengantar == 'disetujui') {
                    return '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Disetujui</span>';
                } elseif ($row->status_surat_pengantar == 'ditolak') {
                    return '<span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">Ditolak</span>';
                } else {
                    return '<span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600">Diajukan</span>';
                }
            })
            ->addColumn('aksi', function ($row) {
                $json_data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                // Tombol Lihat Detail (selalu ada)
                $viewButton = '
                <button @click="isModalOpen = true; selectedSurat = ' . $json_data . '" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="View">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            ';

                $cancelButton = '';
                // Jika status masih diajukan, tambahkan tombol batal
                if ($row->status_surat_pengantar == 'diajukan') {
                    $cancelRoute = route('mahasiswa.surat-pengantar.cancel', $row->id);
                    $cancelButton = '
                    <form action="' . $cancelRoute . '" method="POST" onsubmit="return confirm(\'Anda yakin ingin membatalkan pengajuan ini?\');">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Cancel">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                ';
                }

                return '<div class="flex items-center space-x-2">' . $viewButton . $cancelButton . '</div>';
            })
            ->rawColumns(['status_surat_pengantar', 'aksi'])
            ->make(true);
    }

    public function exportWord(SuratPengantar $suratPengantar)
    {
        // Pastikan surat sudah disetujui
        if ($suratPengantar->status_surat_pengantar !== 'disetujui') {
            return redirect()->back()->with('error', 'Hanya surat yang disetujui yang bisa diekspor.');
        }

        // Path ke file template
        $templatePath = storage_path('app/templates/surat_pengantar_template.docx');

        // Cek jika template ada
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template surat tidak ditemukan.');
        }

        // Muat data relasi
        $suratPengantar->load('mahasiswa.user', 'mahasiswa.jurusan');
        $mahasiswa = $suratPengantar->mahasiswa;

        // Buat instance TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Ganti placeholder dengan data
        $templateProcessor->setValue('nomor_surat', $suratPengantar->nomor_surat_pengantar);
        $templateProcessor->setValue('penerima_surat', $suratPengantar->penerima_surat_pengantar);
        $templateProcessor->setValue('lokasi_kp', $suratPengantar->lokasi_kp_surat_pengantar);
        $templateProcessor->setValue('alamat_surat', $suratPengantar->alamat_surat_pengantar);
        $templateProcessor->setValue('nama_mahasiswa', $mahasiswa->user->name);
        $templateProcessor->setValue('nim', $mahasiswa->nim);
        $templateProcessor->setValue('jurusan', $mahasiswa->jurusan->nama);

        // Buat nama file baru
        $fileName = 'Surat Pengantar KP - ' . $mahasiswa->user->name . '.docx';
        $savedPath = storage_path('app/public/' . $fileName);

        // Simpan dokumen yang sudah diisi
        $templateProcessor->saveAs($savedPath);

        // Unduh file lalu hapus dari server
        return response()->download($savedPath)->deleteFileAfterSend(true);
    }

    public function cancel(SuratPengantar $suratPengantar)
    {
        // Keamanan: Pastikan yang membatalkan adalah pemilik surat
        if (auth()->user()->mahasiswa->id !== $suratPengantar->mahasiswa_id) {
            abort(403, 'Anda tidak memiliki akses untuk aksi ini.');
        }

        // Pastikan status masih 'diajukan'
        if ($suratPengantar->status_surat_pengantar !== 'diajukan') {
            return redirect()->route('mahasiswa.surat-pengantar.index')->with('error', 'Pengajuan yang sudah diproses tidak dapat dibatalkan.');
        }

        // Hapus data pengajuan
        $suratPengantar->delete();

        return redirect()->route('mahasiswa.surat-pengantar.index')->with('success', 'Pengajuan surat berhasil dibatalkan.');
    }
}
