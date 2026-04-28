<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;

class Telegram extends BaseController
{
    private $token = '8664592787:AAHxTnEZyozCWVWaM_lBXLBDNkH9BCIJwto';
    private $apiUrl = 'https://api.telegram.org/bot';

    public function webhook()
    {
        try {
            $update = json_decode(file_get_contents("php://input"), true);
            if (!$update) return;

            $chat_id = null;
            $text = '';
            $username = '';
            $message_id = null;
            $is_callback = false;

            /* =======================================================
               TANGKAP INPUT
            ======================================================= */
            if (isset($update['callback_query'])) {
                $is_callback = true;
                $chat_id    = $update['callback_query']['message']['chat']['id'];
                $message_id = $update['callback_query']['message']['message_id'];
                $text       = $update['callback_query']['data'];
                $username   = $update['callback_query']['from']['username'] ?? 'User';

                $this->sendApiRequest('answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
            } elseif (isset($update['message'])) {
                $chat_id  = $update['message']['chat']['id'];
                $text     = trim($update['message']['text'] ?? '');
                $username = $update['message']['from']['username'] ?? 'User';
            }

            if (!$chat_id) return;

            /* =======================================================
               🚨 SISTEM KEAMANAN (DIMATIKAN SEMENTARA UNTUK DEV)
            ======================================================= */
            /* $db = \Config\Database::connect();
            $pegawai = $db->table('users')->where('telegram_id', $chat_id)->get()->getRowArray();

            if (!$pegawai) {
                $pesan_tolak  = "⛔ <b>AKSES DITOLAK</b>\n";
                $pesan_tolak .= "Maaf @$username, Anda tidak terdaftar di sistem PT WIKA Beton.\n\n";
                $pesan_tolak .= "Berikan ID Telegram ini kepada Admin IT untuk didaftarkan:\n";
                $pesan_tolak .= "👉 <code>$chat_id</code> 👈";
                
                $this->kirimPesan($chat_id, $pesan_tolak);
                return;
            }
            */

            $barangModel    = new BarangModel();
            $transaksiModel = new TransaksiModel();

            /* =======================================================
               MENU UTAMA / START
            ======================================================= */
            if ($text == '/start' || $text == '🏠 Menu') {

                if ($text == '/start') {
                    $this->kirimPesan($chat_id, "<i>🔄 Sinkronisasi sistem...</i>", ['remove_keyboard' => true]);
                }

                $total = $barangModel->countAllResults();
                $kritis = $barangModel->where('stok <= minimum_stok')->where('stok >', 0)->countAllResults();
                $habis = $barangModel->where('stok', 0)->countAllResults();

                $pesan  = "🏭 <b>PT WIKA BETON TBK</b>\n━━━━━━━━━━━━━━━━━━\n";
                // Menggunakan username Telegram karena bebas akses
                $pesan .= "Halo, <b>@$username</b>! 👋\n";
                $pesan .= "Selamat datang di <b>Warehouse Monitoring System</b>.\n\n";

                $pesan .= "📊 <b>DASHBOARD SINGKAT</b>\n";
                $pesan .= "├ Total Material : <b>$total</b> item\n";
                $pesan .= "├ Stok Kritis : <b>$kritis</b> item\n";
                $pesan .= "├ Stok Habis : <b>$habis</b> item\n";
                $pesan .= "└ Server : 🟢 ONLINE\n\n";
                $pesan .= "Pilih menu operasional di bawah ini 👇";

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuUtama(), $is_callback);
            }

            /* =======================================================
               CEK STOK DENGAN PAGINASI (NEXT/PREV)
            ======================================================= */ elseif (strpos($text, 'cekstok_') === 0) {

                $offset = (int) str_replace('cekstok_', '', $text);
                $limit  = 8;

                $totalData = $barangModel->countAllResults();
                $barang = $barangModel->orderBy('nama_material', 'ASC')->findAll($limit, $offset);

                $pesan  = "📦 <b>DATA MATERIAL (Hal. " . (($offset / $limit) + 1) . ")</b>\n";
                $pesan .= "━━━━━━━━━━━━━━━━━━\n\n";

                foreach ($barang as $b) {
                    $icon = ($b['stok'] == 0) ? '❌' : (($b['stok'] <= $b['minimum_stok']) ? '⚠' : '✅');
                    $pesan .= "$icon <b>{$b['nama_material']}</b>\n";
                    $pesan .= "└ Stok: <b>{$b['stok']} {$b['satuan']}</b> | Gd: {$b['lokasi_gudang']}\n\n";
                }

                $nav = [];
                if ($offset > 0) {
                    $nav[] = ['text' => '⬅️ Prev', 'callback_data' => 'cekstok_' . ($offset - $limit)];
                }
                if (($offset + $limit) < $totalData) {
                    $nav[] = ['text' => 'Next ➡️', 'callback_data' => 'cekstok_' . ($offset + $limit)];
                }

                $keyboardPaginasi = [
                    'inline_keyboard' => [
                        $nav,
                        [['text' => '🏠 Kembali ke Menu', 'callback_data' => '🏠 Menu']]
                    ]
                ];

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $keyboardPaginasi, $is_callback);
            }

            /* =======================================================
               STOK KRITIS
            ======================================================= */ elseif ($text == '⚠ Kritis') {

                $barang = $barangModel->where('stok <= minimum_stok')->findAll(15);

                if (!$barang) {
                    $this->kirimAtauEdit($chat_id, $message_id, "✅ Saat ini tidak ada material dengan status kritis/habis.", $this->menuKembali(), $is_callback);
                    return;
                }

                $pesan  = "🚨 <b>MATERIAL KRITIS / HABIS</b>\n━━━━━━━━━━━━━━━━━━\n\n";
                foreach ($barang as $b) {
                    $icon = ($b['stok'] == 0) ? '❌' : '⚠';
                    $pesan .= "$icon <b>{$b['nama_material']}</b>\n";
                    $pesan .= "├ Stok Saat Ini : <b>{$b['stok']} {$b['satuan']}</b>\n";
                    $pesan .= "└ Min. Stok : {$b['minimum_stok']}\n\n";
                }

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
            }

            /* =======================================================
               DASHBOARD LENGKAP
            ======================================================= */ elseif ($text == '📊 Dashboard') {
                $total = $barangModel->countAllResults();
                $kritis = $barangModel->where('stok <= minimum_stok')->where('stok >', 0)->countAllResults();
                $habis = $barangModel->where('stok', 0)->countAllResults();

                $pesan  = "📊 <b>DASHBOARD REALTIME</b>\n━━━━━━━━━━━━━━━━━━\n\n";
                $pesan .= "📦 Total Material : $total Item\n";
                $pesan .= "⚠ Stok Kritis : $kritis Item\n";
                $pesan .= "❌ Stok Habis : $habis Item\n\n";
                $pesan .= "🟢 Status Bot : AMAN & ONLINE\n";
                $pesan .= "🕒 Update : " . date('d-m-Y H:i:s');

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
            }

            /* =======================================================
               TRANSAKSI & HISTORI
            ======================================================= */ elseif (in_array($text, ['📥 Masuk', '📤 Keluar', '📄 Histori'])) {

                $jenis_transaksi = '';
                if ($text == '📥 Masuk') {
                    $jenis_transaksi = 'masuk';
                    $judul = "TRANSAKSI MASUK";
                    $icon_title = "📥";
                } elseif ($text == '📤 Keluar') {
                    $jenis_transaksi = 'keluar';
                    $judul = "TRANSAKSI KELUAR";
                    $icon_title = "📤";
                } else {
                    $judul = "HISTORI TERBARU";
                    $icon_title = "📄";
                }

                if ($jenis_transaksi) {
                    $data = $transaksiModel->where('jenis', $jenis_transaksi)->orderBy('id', 'DESC')->findAll(10);
                } else {
                    $data = $transaksiModel->orderBy('id', 'DESC')->findAll(10);
                }

                $pesan = "$icon_title <b>$judul</b>\n━━━━━━━━━━━━━━━━━━\n\n";

                if (!$data) {
                    $pesan .= "Belum ada transaksi tercatat.";
                } else {
                    foreach ($data as $d) {
                        $barang = $barangModel->find($d['barang_id']);
                        $nama = $barang['nama_material'] ?? 'Unknown Material';
                        $simbol = ($d['jenis'] == 'masuk') ? '🟢 +' : '🔴 -';
                        $tgl = date('d/m/Y H:i', strtotime($d['tanggal']));

                        $pesan .= "▪️ <b>$nama</b>\n";
                        $pesan .= "└ $simbol{$d['jumlah']} Item | $tgl\n\n";
                    }
                }

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
            }

            /* =======================================================
               PENCARIAN MATERIAL
            ======================================================= */ elseif ($text == '🔎 Cari') {
                $pesan  = "🔎 <b>PENCARIAN MATERIAL</b>\n━━━━━━━━━━━━━━━━━━\n\n";
                $pesan .= "Ketik manual perintah pencarian:\n";
                $pesan .= "<code>/stok [nama barang]</code>\n\n";
                $pesan .= "Contoh:\n";
                $pesan .= "• <code>/stok pc</code>\n";
                $pesan .= "• <code>/stok semen</code>";

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
            } elseif (strpos(strtolower($text), '/stok ') === 0) {
                $keyword = trim(str_replace('/stok', '', strtolower($text)));
                $barangList = $barangModel->groupStart()
                    ->like('nama_material', $keyword)
                    ->orLike('kode_sumber_daya', $keyword)
                    ->orLike('kategori', $keyword)
                    ->groupEnd()->findAll(15);

                if (!$barangList) {
                    $this->kirimPesan($chat_id, "❌ Material '<b>$keyword</b>' tidak ditemukan.", $this->menuKembali());
                    return;
                }

                $pesan  = "🔎 <b>HASIL PENCARIAN : \"$keyword\"</b>\n━━━━━━━━━━━━━━━━━━\n\n";
                foreach ($barangList as $b) {
                    $status_icon = ($b['stok'] <= $b['minimum_stok']) ? '⚠' : '✅';
                    $status_text = ($b['stok'] == 0) ? 'HABIS' : (($b['stok'] <= $b['minimum_stok']) ? 'KRITIS' : 'AMAN');

                    $pesan .= "$status_icon <b>" . strtoupper($b['nama_material']) . "</b>\n";
                    $pesan .= "├ Kode : <code>{$b['kode_sumber_daya']}</code>\n";
                    $pesan .= "├ Stok : <b>{$b['stok']} {$b['satuan']}</b> ($status_text)\n";
                    $pesan .= "└ Gudang : {$b['lokasi_gudang']}\n\n";
                }

                $this->kirimPesan($chat_id, $pesan, $this->menuKembali());
            }

            /* =======================================================
               TENTANG
            ======================================================= */ elseif ($text == '🏢 Tentang') {
                $pesan  = "🏭 <b>PT WIKA BETON TBK</b>\n━━━━━━━━━━━━━━━━━━\n";
                $pesan .= "<b>Warehouse Monitoring Bot V.3 (Enterprise)</b>\n\n";
                $pesan .= "Dilengkapi fitur:\n";
                $pesan .= "✅ Paginasi Data\n";
                $pesan .= "✅ Anti-Spam Message\n";
                $pesan .= "✅ Caching HTTP Request\n";
                $pesan .= "🔓 Mode Pengembangan (Terbuka)";

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Bot Telegram Error: ' . $e->getMessage());
        }
    }


    /* =======================================================
       FUNGSI HELPER & API REQUEST
    ======================================================= */

    public static function kirimNotifSistem($pesanPeringatan)
    {
        $telegram = new self();

        // Ganti dengan ID grup atau orang yang ingin menerima notif otomatis
        $admin_ids = ['6069266941'];

        foreach ($admin_ids as $admin_id) {
            $pesan = "🚨 <b>SYSTEM ALERT</b> 🚨\n━━━━━━━━━━━━━━━━━━\n\n" . $pesanPeringatan;
            $telegram->sendApiRequest('sendMessage', [
                'chat_id' => $admin_id,
                'text' => $pesan,
                'parse_mode' => 'HTML'
            ]);
        }
    }

    private function kirimAtauEdit($chat_id, $message_id, $text, $keyboard, $is_callback)
    {
        if ($is_callback && $message_id) {
            $this->sendApiRequest('editMessageText', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode($keyboard)
            ]);
        } else {
            $this->kirimPesan($chat_id, $text, $keyboard);
        }
    }

    private function kirimPesan($chat_id, $text, $replyMarkup = null)
    {
        $data = ['chat_id' => $chat_id, 'text' => $text, 'parse_mode' => 'HTML'];
        if ($replyMarkup) $data['reply_markup'] = json_encode($replyMarkup);

        $this->sendApiRequest('sendMessage', $data);
    }

    private function sendApiRequest($method, $data)
    {
        $client = \Config\Services::curlrequest();
        try {
            $client->post($this->apiUrl . $this->token . '/' . $method, [
                'form_params' => $data,
                'http_errors' => false,
                'timeout'     => 10
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Curl Error Telegram: ' . $e->getMessage());
        }
    }

    private function menuUtama()
    {
        return [
            'inline_keyboard' => [
                [
                    ['text' => '📦 Cek Semua Stok', 'callback_data' => 'cekstok_0'],
                    ['text' => '🚨 Stok Kritis', 'callback_data' => '⚠ Kritis']
                ],
                [
                    ['text' => '📥 Brg Masuk', 'callback_data' => '📥 Masuk'],
                    ['text' => '📤 Brg Keluar', 'callback_data' => '📤 Keluar']
                ],
                [
                    ['text' => '📊 Dashboard Live', 'callback_data' => '📊 Dashboard'],
                    ['text' => '🔎 Cari Material', 'callback_data' => '🔎 Cari']
                ],
                [
                    ['text' => '📄 Histori Transaksi', 'callback_data' => '📄 Histori']
                ],
                [
                    ['text' => '🔄 Refresh Sistem', 'callback_data' => '🏠 Menu'],
                    ['text' => '🏢 Tentang Bot', 'callback_data' => '🏢 Tentang']
                ]
            ]
        ];
    }

    private function menuKembali()
    {
        return [
            'inline_keyboard' => [[['text' => '🏠 Kembali ke Menu Utama', 'callback_data' => '🏠 Menu']]]
        ];
    }
}
