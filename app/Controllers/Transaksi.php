<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;
use Dompdf\Dompdf;

class Transaksi extends BaseController
{
    private $token   = '8664592787:AAHxTnEZyozCWVWaM_lBXLBDNkH9BCIJwto';
    private $chat_id = '6069266941';

    /* =====================================
       FORM BARANG MASUK
    ===================================== */
    public function masuk()
    {
        $barangModel = new BarangModel();

        $data['barang'] = $barangModel->findAll();

        return view('transaksi/masuk', $data);
    }

    /* =====================================
       SIMPAN BARANG MASUK
    ===================================== */
    public function simpanMasuk()
    {
        $barangModel    = new BarangModel();
        $transaksiModel = new TransaksiModel();

        $barang_id = $this->request->getPost('barang_id');
        $jumlah    = (int)$this->request->getPost('jumlah');

        $barang = $barangModel->find($barang_id);

        if (!$barang) {
            return redirect()->back()->with('error', 'Material tidak ditemukan');
        }

        if ($jumlah <= 0) {
            return redirect()->back()->with('error', 'Jumlah harus lebih dari 0');
        }

        $stok_baru = $barang['stok'] + $jumlah;

        $barangModel->update($barang_id, [
            'stok' => $stok_baru
        ]);

        $transaksiModel->save([
            'jenis'      => 'masuk',
            'barang_id'  => $barang_id,
            'jumlah'     => $jumlah,
            'user_id'    => session()->get('id'),
            'keterangan' => 'Barang masuk'
        ]);

        $pesan  = "📥 BARANG MASUK\n\n";
        $pesan .= "📦 {$barang['nama_material']}\n";
        $pesan .= "Jumlah: +{$jumlah} {$barang['satuan']}\n";
        $pesan .= "Stok Sekarang: {$stok_baru}\n";
        $pesan .= "Admin: " . session()->get('nama');

        $this->kirimTelegram($pesan);

        return redirect()->to('/barang-masuk')
            ->with('success', 'Barang masuk berhasil');
    }

    /* =====================================
       FORM BARANG KELUAR
    ===================================== */
    public function keluar()
    {
        $barangModel = new BarangModel();

        $data['barang'] = $barangModel->findAll();

        return view('transaksi/keluar', $data);
    }

    /* =====================================
       SIMPAN BARANG KELUAR
    ===================================== */
    public function simpanKeluar()
    {
        $barangModel    = new BarangModel();
        $transaksiModel = new TransaksiModel();

        $barang_id = $this->request->getPost('barang_id');
        $jumlah    = (int)$this->request->getPost('jumlah');

        $barang = $barangModel->find($barang_id);

        if (!$barang) {
            return redirect()->back()->with('error', 'Material tidak ditemukan');
        }

        if ($jumlah <= 0) {
            return redirect()->back()->with('error', 'Jumlah harus lebih dari 0');
        }

        if ($barang['stok'] < $jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        $stok_baru = $barang['stok'] - $jumlah;

        $barangModel->update($barang_id, [
            'stok' => $stok_baru
        ]);

        $transaksiModel->save([
            'jenis'      => 'keluar',
            'barang_id'  => $barang_id,
            'jumlah'     => $jumlah,
            'user_id'    => session()->get('id'),
            'keterangan' => 'Barang keluar'
        ]);

        $pesan  = "📤 BARANG KELUAR\n\n";
        $pesan .= "📦 {$barang['nama_material']}\n";
        $pesan .= "Jumlah: -{$jumlah} {$barang['satuan']}\n";
        $pesan .= "Sisa Stok: {$stok_baru}\n";
        $pesan .= "Admin: " . session()->get('nama');

        $this->kirimTelegram($pesan);

        if ($stok_baru <= $barang['minimum_stok']) {

            $alert  = "⚠ ALERT STOK KRITIS\n\n";
            $alert .= "📦 {$barang['nama_material']}\n";
            $alert .= "Sisa stok: {$stok_baru}\n";
            $alert .= "Minimum stok: {$barang['minimum_stok']}";

            $this->kirimTelegram($alert);
        }

        return redirect()->to('/barang-keluar')
            ->with('success', 'Barang keluar berhasil');
    }

    /* =====================================
       HISTORI TRANSAKSI
    ===================================== */
    public function histori()
    {
        $db = \Config\Database::connect();

        $keyword = $this->request->getGet('keyword');
        $jenis   = $this->request->getGet('jenis');

        $builder = $db->table('transaksi');

        $builder->select("
            transaksi.*,
            barang.nama_material,
            barang.kode_sumber_daya,
            users.nama
        ");

        $builder->join('barang', 'barang.id = transaksi.barang_id');
        $builder->join('users', 'users.id = transaksi.user_id', 'left');

        if ($keyword) {
            $builder->groupStart();
            $builder->like('barang.nama_material', $keyword);
            $builder->orLike('barang.kode_sumber_daya', $keyword);
            $builder->groupEnd();
        }

        if ($jenis) {
            $builder->where('transaksi.jenis', $jenis);
        }

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
       EXPORT PDF
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
       EXPORT EXCEL
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
        $sheet->setCellValue('B1', 'Material');
        $sheet->setCellValue('C1', 'Jenis');
        $sheet->setCellValue('D1', 'Jumlah');

        $row = 2;

        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $d['tanggal']);
            $sheet->setCellValue('B' . $row, $d['nama_material']);
            $sheet->setCellValue('C' . $row, $d['jenis']);
            $sheet->setCellValue('D' . $row, $d['jumlah']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_histori.xlsx"');

        $writer->save('php://output');
        exit;
    }

    /* =====================================
       TELEGRAM
    ===================================== */
    private function kirimTelegram($pesan)
    {
        @file_get_contents(
            "https://api.telegram.org/bot{$this->token}/sendMessage?chat_id={$this->chat_id}&text=" . urlencode($pesan)
        );
    }
}
