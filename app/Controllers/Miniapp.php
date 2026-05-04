<?php

namespace App\Controllers;

class Miniapp extends BaseController
{
    public function index()
    {
        $db = db_connect();

        $total_barang = $db->table('barang')->countAll();

        $stok_kritis = $db->table('barang')
            ->where('stok <= minimum_stok')
            ->countAllResults();

        $transaksi_hari_ini = $db->table('transaksi')
            ->like('tanggal', date('Y-m-d'), 'after')
            ->countAllResults();

        $total_material = $db->table('barang')
            ->where('kategori', 'Material')
            ->countAllResults();

        // ===== NEW: CHART KOMPOSISI KATEGORI =====
        $kategori_query = $db->query("
            SELECT kategori, COUNT(*) as jumlah 
            FROM barang 
            WHERE kategori IS NOT NULL AND kategori != '' 
            GROUP BY kategori 
            ORDER BY jumlah DESC
        ")->getResultArray();

        $chartLabels = [];
        $chartData = [];

        foreach ($kategori_query as $k) {
            $chartLabels[] = $k['kategori'];
            $chartData[] = (int)$k['jumlah'];
        }

        return view('miniapp/dashboard', compact(
            'total_barang',
            'stok_kritis',
            'transaksi_hari_ini',
            'total_material',
            'chartLabels',
            'chartData'
        ));
    }

    public function barang()
    {
        $db = db_connect();

        $barang = $db->table('barang')
            ->orderBy('nama_material', 'ASC')
            ->get()->getResultArray();

        return view('miniapp/barang', compact('barang'));
    }

    public function transaksi()
    {
        $db = db_connect();

        $today = date('Y-m-d');

        $transaksi = $db->table('transaksi t')
            ->select('t.*, b.nama_material')
            ->join('barang b', 'b.id = t.barang_id')
            ->like('t.tanggal', $today, 'after')
            ->orderBy('t.id', 'DESC')
            ->get()->getResultArray();

        return view('miniapp/transaksi', compact('transaksi'));
    }

    public function histori()
    {
        $db = db_connect();

        $histori = $db->table('transaksi t')
            ->select('t.*, b.nama_material')
            ->join('barang b', 'b.id = t.barang_id')
            ->orderBy('t.id', 'DESC')
            ->limit(100)
            ->get()->getResultArray();

        return view('miniapp/histori', compact('histori'));
    }
}
