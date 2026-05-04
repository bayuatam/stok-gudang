<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    protected $db;
    protected $path;

    public function __construct()
    {
        $this->db   = db_connect();
        $this->path = WRITEPATH . 'uploads/';

        if (!is_dir($this->path)) {
            mkdir($this->path, 0775, true);
        }
    }

    /* =====================================================
       CORE PDF HOSTING EDITION
    ===================================================== */
    private function build($title, $body, $filename)
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 120);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = '
        <html>
        <head>
        <style>
            body{
                font-family: Helvetica, Arial, sans-serif;
                font-size:12px;
                color:#111;
                margin:20px;
            }
            .header{
                text-align:center;
                border-bottom:2px solid #000;
                padding-bottom:10px;
                margin-bottom:20px;
            }
            .company{
                font-size:18px;
                font-weight:bold;
            }
            .subtitle{
                font-size:13px;
                margin-top:4px;
            }
            table{
                width:100%;
                border-collapse:collapse;
                margin-top:10px;
            }
            th,td{
                border:1px solid #444;
                padding:6px;
                font-size:11px;
            }
            th{
                background:#ddd;
            }
            .footer{
                margin-top:20px;
                font-size:10px;
                text-align:right;
            }
        </style>
        </head>
        <body>

        <div class="header">
            <div class="company">PT WIJAYA KARYA BETON TBK</div>
            <div class="subtitle">Inventory Monitoring System</div>
            <div class="subtitle">' . $title . '</div>
        </div>

        ' . $body . '

        <div class="footer">
            Dicetak : ' . date('d-m-Y H:i:s') . '
        </div>

        </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $file = $this->path . $filename;

        file_put_contents($file, $dompdf->output());

        return $file;
    }

    /* =====================================================
       PDF STOK
    ===================================================== */
    public function stok()
    {
        $rows = $this->db->table('barang')
            ->select('nama_material,stok,satuan,lokasi_gudang')
            ->orderBy('nama_material', 'ASC')
            ->limit(300)
            ->get()->getResultArray();

        $html = '
        <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Satuan</th>
            <th>Lokasi</th>
        </tr>';

        $no = 1;

        foreach ($rows as $r) {

            $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . $r['nama_material'] . '</td>
                <td>' . $r['stok'] . '</td>
                <td>' . $r['satuan'] . '</td>
                <td>' . $r['lokasi_gudang'] . '</td>
            </tr>';
        }

        $html .= '</table>';

        return $this->build(
            'LAPORAN STOK BARANG',
            $html,
            'stok_baru_' . date('d_m_Y_H_i') . '.pdf'
        );
    }

    /* =====================================================
       PDF HARIAN
    ===================================================== */
    public function harian()
    {
        $today = date('Y-m-d');

        return $this->laporanTransaksi(
            "DATE(t.tanggal) = '{$today}'",
            'LAPORAN HARIAN',
            'harian_' . date('Ymd_His') . '.pdf'
        );
    }

    /* =====================================================
       PDF MINGGUAN
    ===================================================== */
    public function mingguan()
    {
        $mulai = date('Y-m-d', strtotime('-7 days'));

        return $this->laporanTransaksi(
            "DATE(t.tanggal) >= '{$mulai}'",
            'LAPORAN MINGGUAN',
            'mingguan_' . date('Ymd_His') . '.pdf'
        );
    }

    /* =====================================================
       PDF BULANAN
    ===================================================== */
    public function bulanan()
    {
        $bulan = date('Y-m');

        return $this->laporanTransaksi(
            "DATE_FORMAT(t.tanggal,'%Y-%m') = '{$bulan}'",
            'LAPORAN BULANAN',
            'bulanan_' . date('Ymd_His') . '.pdf'
        );
    }

    /* =====================================================
       TEMPLATE TRANSAKSI
    ===================================================== */
    private function laporanTransaksi($where, $judul, $filename)
    {
        $rows = $this->db->query("
            SELECT t.tanggal,t.jenis,t.jumlah,
                   b.nama_material,b.satuan
            FROM transaksi t
            JOIN barang b ON b.id=t.barang_id
            WHERE {$where}
            ORDER BY t.id DESC
            LIMIT 500
        ")->getResultArray();

        $html = '
        <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Satuan</th>
        </tr>';

        $no = 1;

        if ($rows) {

            foreach ($rows as $r) {

                $html .= '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . date('d-m-Y H:i', strtotime($r['tanggal'])) . '</td>
                    <td>' . strtoupper($r['jenis']) . '</td>
                    <td>' . $r['nama_material'] . '</td>
                    <td>' . $r['jumlah'] . '</td>
                    <td>' . $r['satuan'] . '</td>
                </tr>';
            }
        } else {

            $html .= '
            <tr>
                <td colspan="6" align="center">
                    Tidak ada data
                </td>
            </tr>';
        }

        $html .= '</table>';

        return $this->build($judul, $html, $filename);
    }
}
