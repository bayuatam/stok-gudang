<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $barangModel    = new BarangModel();
        $transaksiModel = new TransaksiModel();

        $today = date('Y-m-d');

        /* =====================================
           KPI MASTER DATA
        ===================================== */
        $total_barang = $barangModel->countAll();

        $total_material = $barangModel
            ->where('kategori', 'Material')
            ->countAllResults();

        $total_sparepart = $barangModel
            ->where('kategori', 'Suku Cadang')
            ->countAllResults();

        $total_bbm = $barangModel
            ->where('kategori', 'BBM')
            ->countAllResults();

        $stok_menipis = $barangModel
            ->where('stok <= minimum_stok')
            ->countAllResults();

        /* =====================================
           TRANSAKSI HARI INI
        ===================================== */
        $masuk_hari_ini = $transaksiModel
            ->where('jenis', 'masuk')
            ->like('tanggal', $today, 'after')
            ->countAllResults();

        $keluar_hari_ini = $transaksiModel
            ->where('jenis', 'keluar')
            ->like('tanggal', $today, 'after')
            ->countAllResults();

        $transaksi_hari_ini = $masuk_hari_ini + $keluar_hari_ini;

        /* =====================================
           GRAFIK 7 HARI
        ===================================== */
        $grafik = [];

        for ($i = 6; $i >= 0; $i--) {

            $tanggal = date('Y-m-d', strtotime("-$i days"));

            $masuk = $transaksiModel
                ->where('jenis', 'masuk')
                ->like('tanggal', $tanggal, 'after')
                ->countAllResults();

            $keluar = $transaksiModel
                ->where('jenis', 'keluar')
                ->like('tanggal', $tanggal, 'after')
                ->countAllResults();

            $grafik[] = [
                'tanggal' => $tanggal,
                'masuk'   => $masuk,
                'keluar'  => $keluar
            ];
        }

        /* =====================================
           BARANG KRITIS
        ===================================== */
        $barang_kritis = $barangModel
            ->where('stok <= minimum_stok')
            ->orderBy('stok', 'ASC')
            ->limit(10)
            ->findAll();

        /* =====================================
           TOP BARANG KELUAR
        ===================================== */
        $top_keluar = $db->table('transaksi')
            ->select('barang.nama_material, SUM(transaksi.jumlah) as total')
            ->join('barang', 'barang.id = transaksi.barang_id')
            ->where('transaksi.jenis', 'keluar')
            ->groupBy('barang.nama_material')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        /* =====================================
           AKTIVITAS TERAKHIR
        ===================================== */
        $aktivitas = $db->table('transaksi')
            ->select('transaksi.*, barang.nama_material')
            ->join('barang', 'barang.id = transaksi.barang_id')
            ->orderBy('transaksi.id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        /* =====================================
           VIEW
        ===================================== */
        return view('dashboard', [

            'total_barang'       => $total_barang,
            'total_material'     => $total_material,
            'total_sparepart'    => $total_sparepart,
            'total_bbm'          => $total_bbm,
            'stok_menipis'       => $stok_menipis,

            'masuk_hari_ini'     => $masuk_hari_ini,
            'keluar_hari_ini'    => $keluar_hari_ini,
            'transaksi_hari_ini' => $transaksi_hari_ini,

            'grafik'             => $grafik,
            'barang_kritis'      => $barang_kritis,
            'top_keluar'         => $top_keluar,
            'aktivitas'          => $aktivitas
        ]);
    }
}
