<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\PdfService;

class Telegram extends BaseController
{
    protected $token;
    protected $api;
    protected $allowed;

    public function __construct()
    {
        $this->token   = env('telegram.token');
        $this->api     = "https://api.telegram.org/bot{$this->token}/";
        $this->allowed = env('telegram.chat_id');
        date_default_timezone_set('Asia/Jakarta');
    }

    /* =====================================================
       WEBHOOK MAIN V13 FULL CLEAN FINAL
    ===================================================== */
    public function webhook()
    {
        try {

            $update = json_decode(file_get_contents('php://input'), true);
            if (!$update) return;

            $chat_id    = null;
            $message_id = null;
            $text       = '';
            $callback   = false;
            $cb_id      = null;
            $nama       = 'User';

            /* CALLBACK */
            if (isset($update['callback_query'])) {

                $callback   = true;
                $cb_id      = $update['callback_query']['id'];
                $chat_id    = $update['callback_query']['message']['chat']['id'];
                $message_id = $update['callback_query']['message']['message_id'];
                $text       = $update['callback_query']['data'];
                $nama       = $update['callback_query']['from']['first_name'] ?? 'User';

                $this->answerCallback($cb_id);
            }

            /* MESSAGE */ elseif (isset($update['message'])) {

                $chat_id    = $update['message']['chat']['id'];
                $message_id = $update['message']['message_id'];
                $text       = trim($update['message']['text'] ?? '');
                $nama       = $update['message']['from']['first_name'] ?? 'User';
            }

            if (!$chat_id) return;

            /* SECURITY */
            if ($this->allowed && $chat_id != $this->allowed) {
                $this->sendMessage($chat_id, "⛔ Akses ditolak.");
                return;
            }

            /* =====================================================
               AUTO SEARCH SMART TYPO AI
            ===================================================== */

            if (!$callback && $text && $text[0] !== '/') {

                $reserved = [
                    'home',
                    'ringkasan',
                    'panduan',
                    'export',
                    'pdf_stok',
                    'pdf_harian',
                    'pdf_mingguan',
                    'pdf_bulanan',
                    'stok_kritis_1'
                ];

                if (!in_array($text, $reserved)) {
                    $this->cariBarang($chat_id, $text);
                    return;
                }
            }

            /* SEARCH COMMAND */
            if (strpos($text, '/cari ') === 0) {
                $this->cariBarang($chat_id, trim(substr($text, 6)));
                return;
            }

            /* PAGINATION */
            if (strpos($text, 'stok_kritis') === 0) {
                $parts = explode('_', $text);
                $page  = isset($parts[2]) ? (int)$parts[2] : 1;
                $this->stokKritis($chat_id, $message_id, $callback, $page);
                return;
            }

            /* =====================================================
               ROUTER FINAL
            ===================================================== */

            switch ($text) {

                case '/start':
                case 'home':
                    $this->menuUtama($chat_id, $message_id, $callback, $nama);
                    return;

                case 'ringkasan':
                    $this->ringkasan($chat_id, $message_id, $callback);
                    return;

                case 'panduan_cari':
                    $this->panduanCari($chat_id, $message_id, $callback);
                    return;

                case 'export':
                    $this->menuExport($chat_id, $message_id, $callback);
                    return;

                case 'pdf_stok':
                    $this->exportPdf($chat_id, 'stok');
                    return;

                case 'pdf_harian':
                    $this->exportPdf($chat_id, 'harian');
                    return;

                case 'pdf_mingguan':
                    $this->exportPdf($chat_id, 'mingguan');
                    return;

                case 'pdf_bulanan':
                    $this->exportPdf($chat_id, 'bulanan');
                    return;

                case 'panduan':
                    $this->panduan($chat_id, $message_id, $callback);
                    return;
            }

            $this->sendMessage($chat_id, "Gunakan /start");
        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());
        }
    }

    /* =====================================================
       MENU UTAMA EXECUTIVE
    ===================================================== */
    private function menuUtama($chat_id, $message_id, $callback, $nama)
    {
        $db = db_connect();
        $totalBarang = $db->table('barang')->countAll();
        $stokKritis  = $db->table('barang')->where('stok <= minimum_stok')->countAllResults();
        $today       = date('Y-m-d');
        $trxHariIni  = $db->table('transaksi')->like('tanggal', $today, 'after')->countAllResults();

        $jam = (int)date('H');
        $salam = ($jam < 11) ? "Pagi" : (($jam < 15) ? "Siang" : (($jam < 18) ? "Sore" : "Malam"));

        $text  = "🏢 <b>WIKA BETON - INVENTORY</b>\n";
        $text .= "<code>Sistem Monitoring Material </code>\n";
        $text .= "━━━━━━━━━━━━━━━━━━\n\n";
        $text .= "Selamat {$salam}, <b>{$nama}</b>\n";
        $text .= ($stokKritis == 0) ? "🟢 Status: <b>Semua Stok Aman</b>\n\n" : "🔴 Status: <b>{$stokKritis} Item Kritis!</b>\n\n";

        $text .= "📊 <b>GUDANG</b>\n";
        $text .= "├ 📦 Total Produk  : <b>{$totalBarang}</b>\n";
        $text .= "├ ⚠️ Stok Menipis : <b>{$stokKritis}</b>\n";
        $text .= "└ 📝 Aktivitas Hari Ini : <b>{$trxHariIni}</b>\n\n";
        $text .= "📅 <i>Update: " . date('d M Y | H:i') . " WIB</i>\n\n";
        $text .= "💡 <i>Gunakan tombol di bawah atau ketik nama barang untuk mencari cepat.</i>";

        $keyboard = [
            'inline_keyboard' => [
                [['text' => '📈 Ringkasan Stat', 'callback_data' => 'ringkasan'], ['text' => '🔎 Cari Barang', 'callback_data' => 'panduan_cari']],
                [['text' => '⚠️ Daftar Stok Kritis', 'callback_data' => 'stok_kritis_1']],
                [['text' => '📄 Cetak Laporan PDF', 'callback_data' => 'export']],
                [['text' => '⚙️ Panduan Sistem', 'callback_data' => 'panduan']]
            ]
        ];

        $this->sendOrEdit($chat_id, $message_id, $text, $keyboard, $callback);
    }

    /* =====================================================
       RINGKASAN
    ===================================================== */
    private function ringkasan($chat_id, $message_id, $callback)
    {
        $db = db_connect();

        $barang = $db->table('barang')->countAll();

        $kritis = $db->table('barang')
            ->where('stok <= minimum_stok')
            ->countAllResults();

        $today = date('Y-m-d');

        $masuk = $db->table('transaksi')
            ->where('jenis', 'masuk')
            ->like('tanggal', $today, 'after')
            ->countAllResults();

        $keluar = $db->table('transaksi')
            ->where('jenis', 'keluar')
            ->like('tanggal', $today, 'after')
            ->countAllResults();

        $text  = "📊 <b>RINGKASAN HARI INI</b>\n\n";
        $text .= "📦 Total Barang : {$barang}\n";
        $text .= "⚠️ Stok Kritis : {$kritis}\n";
        $text .= "📥 Barang Masuk : {$masuk}\n";
        $text .= "📤 Barang Keluar : {$keluar}\n";
        $text .= "🕒 " . date('d-m-Y H:i');

        $keyboard = [
            'inline_keyboard' => [
                [['text' => '🏠 Menu', 'callback_data' => 'home']]
            ]
        ];

        $this->sendOrEdit($chat_id, $message_id, $text, $keyboard, $callback);
    }

    /* =====================================================
       PANDUAN SEARCH
    ===================================================== */
    private function panduanCari($chat_id, $message_id, $callback)
    {
        $text  = "🔎 <b>CARI BARANG CEPAT</b>\n\n";
        $text .= "Ketik nama barang langsung di chat.\n\n";
        $text .= "Contoh:\n";
        $text .= "<code>semen</code>\n";
        $text .= "<code>wiremesh</code>\n";
        $text .= "<code>besi</code>\n\n";
        $text .= "Bot akan mencari otomatis.";

        $keyboard = [
            'inline_keyboard' => [
                [['text' => '🏠 Menu', 'callback_data' => 'home']]
            ]
        ];

        $this->sendOrEdit($chat_id, $message_id, $text, $keyboard, $callback);
    }

    /* =====================================================
       ALERT
    ===================================================== */
    private function stokKritis($chat_id, $message_id, $callback, $page = 1)
    {
        $db = db_connect();

        $rows = $db->table('barang')
            ->where('stok <= minimum_stok')
            ->orderBy('stok', 'ASC')
            ->limit(15)
            ->get()
            ->getResultArray();

        $text = "⚠️ <b>STOK KRITIS</b>\n\n";

        if ($rows) {

            foreach ($rows as $r) {
                $text .= "📦 {$r['nama_material']}\n";
                $text .= "Sisa {$r['stok']} {$r['satuan']}\n\n";
            }
        } else {

            $text .= "✅ Semua stok aman.";
        }

        $keyboard = [
            'inline_keyboard' => [
                [['text' => '🏠 Menu', 'callback_data' => 'home']]
            ]
        ];

        $this->sendOrEdit($chat_id, $message_id, $text, $keyboard, $callback);
    }

    /* =====================================================
       SEARCH SMART TYPO AI
    ===================================================== */
    private function cariBarang($chat_id, $keyword)
    {
        $db = db_connect();
        $keyword = strtolower(trim($keyword));
        $rows = $db->table('barang')->get()->getResultArray();
        $hasil = [];

        foreach ($rows as $r) {
            $nama = strtolower($r['nama_material']);
            $kat  = strtolower($r['kategori']);
            similar_text($keyword, $nama, $s1);
            similar_text($keyword, $kat, $s2);
            $score = max($s1, $s2);

            if (strpos($nama, $keyword) !== false || strpos($kat, $keyword) !== false || $score >= 45) {
                $r['score'] = $score;
                $hasil[] = $r;
            }
        }

        usort($hasil, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        $hasil = array_slice($hasil, 0, 8);

        $text = "🔎 <b>HASIL PENCARIAN</b>\n";
        $text .= "Kata Kunci: <i>'{$keyword}'</i>\n";
        $text .= "━━━━━━━━━━━━━━━━━━\n\n";

        if ($hasil) {
            foreach ($hasil as $r) {
                $isKritis = ($r['stok'] <= $r['minimum_stok']);
                $icon = $isKritis ? '🔴' : '🟢';
                $text .= "{$icon} <b>{$r['nama_material']}</b>\n";
                $text .= "└ Stok: <b>{$r['stok']} {$r['satuan']}</b> | Lokasi: <code>{$r['lokasi_gudang']}</code>\n\n";
            }
        } else {
            $text .= "❌ Maaf, barang tidak ditemukan.\nCoba gunakan kata kunci lain.";
        }

        $keyboard = ['inline_keyboard' => [[['text' => '🏠 Kembali ke Menu', 'callback_data' => 'home']]]];
        $this->sendMessage($chat_id, $text, $keyboard);
    }

    /* =====================================================
       EXPORT PDF
    ===================================================== */
    private function menuExport($chat_id, $message_id, $callback)
    {
        $text  = "📄 <b>PUSAT LAPORAN PDF</b>\n";
        $text .= "━━━━━━━━━━━━━━━━━━\n";
        $text .= "Silakan pilih kategori laporan yang ingin Anda buat secara otomatis:";

        $keyboard = [
            'inline_keyboard' => [
                [['text' => '📦 Laporan Stok (Master)', 'callback_data' => 'pdf_stok']],
                [['text' => '📅 Mutasi Harian', 'callback_data' => 'pdf_harian']],
                [['text' => '🗓 Mutasi Mingguan', 'callback_data' => 'pdf_mingguan']],
                [['text' => '📆 Mutasi Bulanan', 'callback_data' => 'pdf_bulanan']],
                [['text' => '🏠 Kembali', 'callback_data' => 'home']]
            ]
        ];

        $this->sendOrEdit($chat_id, $message_id, $text, $keyboard, $callback);
    }

    private function exportPdf($chat_id, $type)
    {
        try {

            $pdf = new PdfService();

            if ($type == 'stok') $file = $pdf->stok();
            elseif ($type == 'harian') $file = $pdf->harian();
            elseif ($type == 'mingguan') $file = $pdf->mingguan();
            else $file = $pdf->bulanan();

            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🏠 Menu', 'callback_data' => 'home']]
                ]
            ];

            $this->sendDocument($chat_id, $file, $keyboard);
        } catch (\Throwable $e) {

            $this->sendMessage($chat_id, "❌ Gagal membuat PDF.");
        }
    }

    /* =====================================================
       BANTUAN
    ===================================================== */
    private function panduan($chat_id, $message_id, $callback)
    {
        $text  = "⚙️ <b>BANTUAN</b>\n\n";
        $text .= "📊 Ringkasan = Dashboard cepat\n";
        $text .= "🔎 Cari Barang = Ketik nama barang\n";
        $text .= "⚠️ Alert = Barang menipis\n";
        $text .= "📄 Laporan = Download PDF\n\n";
        $text .= "Gunakan tombol ▶ Dashboard untuk Mini App.";

        $keyboard = [
            'inline_keyboard' => [
                [['text' => '🏠 Menu', 'callback_data' => 'home']]
            ]
        ];

        $this->sendOrEdit($chat_id, $message_id, $text, $keyboard, $callback);
    }

    /* =====================================================
       CORE TELEGRAM
    ===================================================== */
    private function sendMessage($chat_id, $text, $keyboard = null)
    {
        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];

        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }

        $this->request('sendMessage', $data);
    }

    private function sendOrEdit($chat_id, $message_id, $text, $keyboard, $callback = false)
    {
        if ($callback) {

            $this->request('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode($keyboard)
            ]);
        } else {

            $this->sendMessage($chat_id, $text, $keyboard);
        }
    }

    private function sendDocument($chat_id, $file, $keyboard = null)
    {
        if (!file_exists($file)) {
            $this->sendMessage($chat_id, '❌ File tidak ditemukan.');
            return;
        }

        $url = $this->api . 'sendDocument';

        $post = [
            'chat_id' => $chat_id,
            'document' => new \CURLFile($file, 'application/pdf', basename($file))
        ];

        if ($keyboard) {
            $post['reply_markup'] = json_encode($keyboard);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);

        @unlink($file);
    }

    private function answerCallback($id)
    {
        $this->request('answerCallbackQuery', [
            'callback_query_id' => $id
        ]);
    }

    private function request($method, $data)
    {
        try {

            $client = \Config\Services::curlrequest();

            $client->post($this->api . $method, [
                'form_params' => $data,
                'http_errors' => false,
                'timeout' => 20
            ]);
        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());
        }
    }

    /* =====================================================
       PLAY BUTTON MINI APP
    ===================================================== */
    public function setMiniAppButton()
    {
        $token = getenv('telegram.token');

        $url = "https://api.telegram.org/bot{$token}/setChatMenuButton";

        $data = [
            'menu_button' => json_encode([
                'type' => 'web_app',
                'text' => '▶ Dashboard',
                'web_app' => [
                    'url' => 'https://stokgudangg.my.id/miniapp'
                ]
            ])
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;
    }
    /* =====================================================
    TRIGGER PDF DARI MINI APP (AJAX)
===================================================== */
    public function triggerPdf()
    {
        $chat_id = $this->request->getPost('telegram_id');

        if (!$chat_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak ditemukan']);
        }

        // UBAH 'harian' MENJADI 'stok' agar bot mengirim laporan stok barang
        $this->exportPdf($chat_id, 'stok');

        return $this->response->setJSON(['status' => 'success']);
    }
}
