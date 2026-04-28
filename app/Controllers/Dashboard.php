<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $barangModel = new BarangModel();
        $transaksiModel = new TransaksiModel();

        // Total barang
        $total_barang = $barangModel->countAll();

        // Stok menipis
        $stok_menipis = $barangModel
            ->where('stok <= minimum_stok')
            ->countAllResults();

        // Transaksi hari ini
        $today = date('Y-m-d');
        $transaksi_hari_ini = $transaksiModel
            ->where('DATE(tanggal)', $today)
            ->countAllResults();

        // Data grafik 7 hari
        $grafik = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));

            $masuk = $transaksiModel
                ->where('jenis', 'masuk')
                ->where('DATE(tanggal)', $tanggal)
                ->countAllResults();

            $keluar = $transaksiModel
                ->where('jenis', 'keluar')
                ->where('DATE(tanggal)', $tanggal)
                ->countAllResults();

            $grafik[] = [
                'tanggal' => $tanggal,
                'masuk' => $masuk,
                'keluar' => $keluar
            ];
        }

        // Barang stok kritis
        $barang_kritis = $barangModel
            ->where('stok <= minimum_stok')
            ->findAll();

        return view('dashboard', [
            'total_barang' => $total_barang,
            'stok_menipis' => $stok_menipis,
            'transaksi_hari_ini' => $transaksi_hari_ini,
            'grafik' => $grafik,
            'barang_kritis' => $barang_kritis
        ]);
    }
}
