<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;
use Dompdf\Dompdf;

class Transaksi extends BaseController
{
    /* =====================================
       FORM BARANG MASUK
    ===================================== */
    public function masuk()
    {
        $barangModel = new BarangModel();

        $data['barang'] = $barangModel
            ->where('status_barang', 'Aktif')
            ->orderBy('nama_material', 'ASC')
            ->findAll();

        return view('transaksi/masuk', $data);
    }

    /* =====================================
       SIMPAN BARANG MASUK
    ===================================== */
    public function simpanMasuk()
    {
        $db             = \Config\Database::connect();
        $barangModel    = new BarangModel();
        $transaksiModel = new TransaksiModel();

        $barang_id  = (int)$this->request->getPost('barang_id');
        $jumlah     = (int)$this->request->getPost('jumlah');
        $dokumen    = trim($this->request->getPost('dokumen'));
        $keterangan = trim($this->request->getPost('keterangan'));

        if ($jumlah <= 0) {
            return redirect()->back()->with('error', 'Jumlah harus lebih dari 0');
        }

        $barang = $barangModel->find($barang_id);

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        $stokBaru = $barang['stok'] + $jumlah;

        $db->transStart();

        $barangModel->update($barang_id, [
            'stok' => $stokBaru
        ]);

        $transaksiModel->insert([
            'tanggal'    => date('Y-m-d H:i:s'),
            'jenis'      => 'masuk',
            'barang_id'  => $barang_id,
            'jumlah'     => $jumlah,
            'user_id'    => session()->get('id') ?? 0,
            'keterangan' => $dokumen . ' | ' . $keterangan
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi');
        }

        $pesan  = "ðŸ“¥ BARANG MASUK\n\n";
        $pesan .= "ðŸ“¦ {$barang['nama_material']}\n";
        $pesan .= "Jumlah: +{$jumlah} {$barang['satuan']}\n";
        $pesan .= "Stok Baru: {$stokBaru}\n";
        $pesan .= "Dokumen: {$dokumen}\n";
        $pesan .= "Admin: " . (session()->get('nama') ?? 'System');

        $this->kirimTelegram($pesan);

        return redirect()->to('/barang-masuk')
            ->with('success', 'Barang masuk berhasil disimpan');
    }

    /* =====================================
       FORM BARANG KELUAR
    ===================================== */
    public function keluar()
    {
        $barangModel = new BarangModel();

        $data['barang'] = $barangModel
            ->where('status_barang', 'Aktif')
            ->where('stok >', 0)
            ->orderBy('nama_material', 'ASC')
            ->findAll();

        return view('transaksi/keluar', $data);
    }

    /* =====================================
       SIMPAN BARANG KELUAR
    ===================================== */
    public function simpanKeluar()
    {
        $db             = \Config\Database::connect();
        $barangModel    = new BarangModel();
        $transaksiModel = new TransaksiModel();

        $barang_id  = (int)$this->request->getPost('barang_id');
        $jumlah     = (int)$this->request->getPost('jumlah');
        $dokumen    = trim($this->request->getPost('dokumen'));
        $keterangan = trim($this->request->getPost('keterangan'));

        if ($jumlah <= 0) {
            return redirect()->back()->with('error', 'Jumlah harus lebih dari 0');
        }

        $barang = $barangModel->find($barang_id);

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        if ($barang['stok'] < $jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        $stokBaru = $barang['stok'] - $jumlah;

        $db->transStart();

        $barangModel->update($barang_id, [
            'stok' => $stokBaru
        ]);

        $transaksiModel->insert([
            'tanggal'    => date('Y-m-d H:i:s'),
            'jenis'      => 'keluar',
            'barang_id'  => $barang_id,
            'jumlah'     => $jumlah,
            'user_id'    => session()->get('id') ?? 0,
            'keterangan' => $dokumen . ' | ' . $keterangan
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi');
        }

        $pesan  = "ðŸ“¤ BARANG KELUAR\n\n";
        $pesan .= "ðŸ“¦ {$barang['nama_material']}\n";
        $pesan .= "Jumlah: -{$jumlah} {$barang['satuan']}\n";
        $pesan .= "Sisa Stok: {$stokBaru}\n";
        $pesan .= "Dokumen: {$dokumen}\n";
        $pesan .= "Admin: " . (session()->get('nama') ?? 'System');

        $this->kirimTelegram($pesan);

        if ($stokBaru <= $barang['minimum_stok']) {

            $alert  = "âš  ALERT STOK KRITIS\n\n";
            $alert .= "ðŸ“¦ {$barang['nama_material']}\n";
            $alert .= "Sisa stok: {$stokBaru}\n";
            $alert .= "Minimum stok: {$barang['minimum_stok']}";

            $this->kirimTelegram($alert);
        }

        return redirect()->to('/barang-keluar')
            ->with('success', 'Barang keluar berhasil disimpan');
    }

    /* =====================================
       HISTORI
    ===================================== */
    public function histori()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('transaksi');

        $builder->select("
            transaksi.*,
            barang.nama_material,
            barang.kode_sumber_daya,
            barang.kategori,
            users.nama
        ");

        $builder->join('barang', 'barang.id = transaksi.barang_id');
        $builder->join('users', 'users.id = transaksi.user_id', 'left');

        $builder->orderBy('transaksi.id', 'DESC');

        $data['transaksi'] = $builder->get()->getResultArray();

        $data['total_masuk'] = $db->table('transaksi')
            ->where('jenis', 'masuk')
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0;

        $data['total_keluar'] = $db->table('transaksi')
            ->where('jenis', 'keluar')
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0;

        $data['total_transaksi'] = $db->table('transaksi')->countAllResults();

        return view('transaksi/histori', $data);
    }

    /* =====================================
       PDF
    ===================================== */
    public function exportPdf()
    {
        $db = \Config\Database::connect();

        $data['transaksi'] = $db->table('transaksi')
            ->select('transaksi.*, barang.nama_material')
            ->join('barang', 'barang.id = transaksi.barang_id')
            ->orderBy('transaksi.id', 'DESC')
            ->get()->getResultArray();

        $html = view('laporan/pdf_histori', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("laporan_histori.pdf", ["Attachment" => false]);
    }

    /* =====================================
       EXCEL
    ===================================== */
    public function exportExcel()
    {
        $db = \Config\Database::connect();

        $data = $db->table('transaksi')
            ->select('transaksi.*, barang.nama_material')
            ->join('barang', 'barang.id = transaksi.barang_id')
            ->orderBy('transaksi.id', 'DESC')
            ->get()->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Barang');
        $sheet->setCellValue('C1', 'Jenis');
        $sheet->setCellValue('D1', 'Jumlah');
        $sheet->setCellValue('E1', 'Keterangan');

        $row = 2;

        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $d['tanggal']);
            $sheet->setCellValue('B' . $row, $d['nama_material']);
            $sheet->setCellValue('C' . $row, $d['jenis']);
            $sheet->setCellValue('D' . $row, $d['jumlah']);
            $sheet->setCellValue('E' . $row, $d['keterangan']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_histori.xlsx"');

        $writer->save('php://output');
        exit;
    }

    /* =====================================
       TELEGRAM (.env)
    ===================================== */
    private function kirimTelegram($pesan)
    {
        $token = env('telegram.token');
        $chat  = env('telegram.chat_id');

        if (!$token || !$chat) return;

        @file_get_contents(
            "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat}&text=" . urlencode($pesan)
        );
    }
}
