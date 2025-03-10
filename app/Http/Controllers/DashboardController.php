<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasKeluar;
use App\Models\KasMasuk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $total_kas_masuk = KasMasuk::sum('nominal_masuk');
        $total_kas_keluar = KasKeluar::sum('nominal_keluar');

        $riwayat_kas_masuk = KasMasuk::whereMonth('tgl_masuk', $currentMonth)->whereYear('tgl_masuk', $currentYear)
        ->select('id_masuk as id', 'nominal_masuk as nominal', 'tgl_masuk as tanggal', 'deskripsi_masuk as deskripsi', DB::raw('"Kas Masuk" as jenis'))
        ->get();

        $riwayat_kas_keluar = KasKeluar::whereMonth('tgl_keluar', $currentMonth)->whereYear('tgl_keluar', $currentYear)
        ->select('id_keluar as id', 'nominal_keluar as nominal', 'tgl_keluar as tanggal', 'deskripsi_keluar as deskripsi', DB::raw('"Kas Keluar" as jenis'))
        ->get();

        $riwayat_kas = $riwayat_kas_masuk->concat($riwayat_kas_keluar);
        $riwayat_kas = $riwayat_kas->sortBy('tanggal');

        return view('admin/pages/dashboard', compact('riwayat_kas', 'total_kas_masuk', 'total_kas_keluar'));
    }
}
