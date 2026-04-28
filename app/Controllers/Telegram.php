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

            if (!$update) {
                return;
            }

            $chat_id = null;
            $text = '';
            $username = '';
            $message_id = null;
            $is_callback = false;

            /* =======================================================
               1. TANGKAP INPUT (TERMASUK FORCE REPLY)
            ======================================================= */
            if (isset($update['callback_query'])) {
                $is_callback = true;
                $chat_id    = $update['callback_query']['message']['chat']['id'];
                $message_id = $update['callback_query']['message']['message_id'];
                $text       = $update['callback_query']['data'];
                $username   = $update['callback_query']['from']['username'] ?? 'User';

                $this->sendApiRequest('answerCallbackQuery', [
                    'callback_query_id' => $update['callback_query']['id']
                ]);
            } elseif (isset($update['message'])) {
                $chat_id  = $update['message']['chat']['id'];
                $text     = trim($update['message']['text'] ?? '');
                $username = $update['message']['from']['username'] ?? 'User';

                // TANGKAP BALASAN PENCARIAN (FORCE REPLY)
                if (isset($update['message']['reply_to_message'])) {
                    $reply_text = $update['message']['reply_to_message']['text'] ?? '';
                    if (strpos($reply_text, 'Ketik nama material') !== false) {
                        $text = '/stok ' . $text;
                    }
                }
            }

            if (!$chat_id) return;

            $barangModel    = new BarangModel();
            $transaksiModel = new TransaksiModel();

            /* =======================================================
               2. MENU UTAMA (START) DENGAN PROGRESS BAR ASCII
            ======================================================= */
            if ($text == '/start' || $text == '🏠 Menu') {

                if ($is_callback && $message_id) {
                    $this->sendApiRequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
                }

                $total  = $barangModel->countAllResults();
                $habis  = $barangModel->where('stok', 0)->countAllResults();
                $kritis = $barangModel->where('stok <= minimum_stok')->where('stok >', 0)->countAllResults();
                $aman   = $total - $kritis - $habis;

                $barAman   = $this->generateProgressBar($aman, $total);
                $barKritis = $this->generateProgressBar($kritis, $total);
                $barHabis  = $this->generateProgressBar($habis, $total);

                $pesan  = "🏭 <b>PT WIKA BETON TBK</b>\n";
                $pesan .= "━━━━━━━━━━━━━━━━━━\n";
                $pesan .= "Halo, <b>@$username</b>! 👋\n";
                $pesan .= "Selamat datang di <b>Warehouse Monitoring System</b>.\n\n";

                $pesan .= "📊 <b>SUMMARY INVENTORY</b>\n";
                $pesan .= "<code>Total Material : $total Item</code>\n\n";
                $pesan .= "🟢 <b>Stok Aman ($aman)</b>\n<code>$barAman</code>\n";
                $pesan .= "🟡 <b>Stok Kritis ($kritis)</b>\n<code>$barKritis</code>\n";
                $pesan .= "🔴 <b>Stok Habis ($habis)</b>\n<code>$barHabis</code>\n\n";
                $pesan .= "Pilih menu operasional di bawah ini 👇";

                $this->kirimPesan($chat_id, $pesan, $this->menuUtama());
                return;
            }

            /* =======================================================
               3. FITUR BARU: PROFIL SAYA (USER INFO DARI DATABASE)
            ======================================================= */ elseif ($text == '👤 Profil Saya') {

                // Koneksi ke Database untuk mencari data User/Manajer
                $db = \Config\Database::connect();
                $userDb = $db->table('users')->where('telegram_id', $chat_id)->get()->getRowArray();

                // Set data jika ketemu, jika tidak berikan status default
                $namaLengkap = $userDb ? strtoupper($userDb['nama']) : strtoupper($username);
                $roleAsli    = $userDb ? strtoupper($userDb['role']) : 'GUEST / BELUM TERDAFTAR';
                $waktu       = date('d F Y \p\u\k\u\l H:i:s \W\I\B');

                $pesan  = "Halo <b>$namaLengkap</b> 👋\n";
                $pesan .= "$waktu\n\n";

                $pesan .= "<b>User Info :</b>\n";
                $pesan .= "└ ID Telegram : <code>$chat_id</code>\n";
                $pesan .= "└ Username : @$username\n";
                $pesan .= "└ Jabatan : <b>$roleAsli</b>\n\n";

                $pesan .= "<b>System Stats :</b>\n";
                $pesan .= "└ Status Bot : 🟢 Aktif (Online)\n";
                $pesan .= "└ Mode Keamanan : Development (Open Access)\n\n";

                $pesan .= "<i>Sistem Monitoring WIKA Beton Enterprise.</i>";

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
                return;
            }

            /* =======================================================
               4. CEK STOK (PAGINASI NEXT & PREV)
            ======================================================= */ elseif (strpos($text, 'cekstok_') === 0) {

                if ($is_callback && isset($update['callback_query']['message']['photo'])) {
                    $this->sendApiRequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
                    $message_id = null;
                }

                $offset = (int) str_replace('cekstok_', '', $text);
                $limit  = 8;

                $totalData = $barangModel->countAllResults();
                $barang = $barangModel->orderBy('nama_material', 'ASC')->findAll($limit, $offset);

                $halaman_sekarang = ($offset / $limit) + 1;
                $pesan  = "📦 <b>DATA MATERIAL (Hal. $halaman_sekarang)</b>\n";
                $pesan .= "━━━━━━━━━━━━━━━━━━\n\n";

                foreach ($barang as $b) {
                    $icon = ($b['stok'] == 0) ? '❌' : (($b['stok'] <= $b['minimum_stok']) ? '⚠' : '✅');
                    $pesan .= "$icon <b>{$b['nama_material']}</b>\n";
                    $pesan .= "└ Stok: <b>{$b['stok']} {$b['satuan']}</b> | Gd: {$b['lokasi_gudang']}\n\n";
                }

                $nav = [];
                if ($offset > 0) $nav[] = ['text' => '⬅️ Prev', 'callback_data' => 'cekstok_' . ($offset - $limit)];
                if (($offset + $limit) < $totalData) $nav[] = ['text' => 'Next ➡️', 'callback_data' => 'cekstok_' . ($offset + $limit)];

                $keyboardPaginasi = ['inline_keyboard' => [$nav, [['text' => '🏠 Kembali ke Menu', 'callback_data' => '🏠 Menu']]]];

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $keyboardPaginasi, $is_callback);
                return;
            }

            /* =======================================================
               5. DASHBOARD (API QUICKCHART DONUT)
            ======================================================= */ elseif ($text == '📊 Dashboard') {

                $total  = $barangModel->countAllResults();
                $habis  = $barangModel->where('stok', 0)->countAllResults();
                $kritis = $barangModel->where('stok <= minimum_stok')->where('stok >', 0)->countAllResults();
                $aman   = $total - $kritis - $habis;

                $chartConfig = [
                    'type' => 'doughnut',
                    'data' => [
                        'labels' => ['Aman', 'Kritis', 'Habis'],
                        'datasets' => [['data' => [$aman, $kritis, $habis], 'backgroundColor' => ['#10b981', '#f59e0b', '#ef4444'], 'borderWidth' => 2]]
                    ],
                    'options' => [
                        'plugins' => ['legend' => ['position' => 'bottom'], 'datalabels' => ['color' => '#fff', 'font' => ['weight' => 'bold', 'size' => 16]]]
                    ]
                ];

                $chartUrl = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode(json_encode($chartConfig));

                $pesan  = "📊 <b>DASHBOARD VISUAL REALTIME</b>\n━━━━━━━━━━━━━━━━━━\n\n";
                $pesan .= "🟢 Aman   : $aman Material\n";
                $pesan .= "🟡 Kritis : $kritis Material\n";
                $pesan .= "🔴 Habis  : $habis Material\n\n";
                $pesan .= "🕒 Update: " . date('d-m-Y H:i:s');

                if ($is_callback && $message_id) {
                    $this->sendApiRequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
                }

                $this->kirimFoto($chat_id, $chartUrl, $pesan, $this->menuKembali());
                return;
            }

            /* =======================================================
               6. PENCARIAN INTERAKTIF (FORCE REPLY)
            ======================================================= */ elseif ($text == '🔎 Cari') {

                if ($is_callback && $message_id) {
                    $this->sendApiRequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
                }

                $pesan  = "🔎 <b>PENCARIAN MATERIAL</b>\n";
                $pesan .= "━━━━━━━━━━━━━━━━━━\n\n";
                $pesan .= "Ketik nama material yang ingin dicari pada kolom di bawah ini 👇";

                $keyboardForce = [
                    'force_reply' => true,
                    'input_field_placeholder' => 'Misal: semen, pc, agregat...'
                ];

                $this->kirimPesan($chat_id, $pesan, $keyboardForce);
                return;
            }

            /* =======================================================
               7. EKSEKUSI PENCARIAN MATERIAL
            ======================================================= */ elseif (strpos(strtolower($text), '/stok ') === 0) {

                $keyword = trim(str_replace('/stok', '', strtolower($text)));

                $res = $barangModel->groupStart()
                    ->like('nama_material', $keyword)
                    ->orLike('kode_sumber_daya', $keyword)
                    ->groupEnd()->findAll(15);

                if (!$res) {
                    $this->kirimPesan($chat_id, "❌ Material '<b>$keyword</b>' tidak ditemukan.", $this->menuKembali());
                    return;
                }

                $pesan = "🔎 <b>HASIL PENCARIAN: \"$keyword\"</b>\n━━━━━━━━━━━━━━━━━━\n\n";
                foreach ($res as $b) {
                    $status_icon = ($b['stok'] <= $b['minimum_stok']) ? '⚠' : '✅';
                    if ($b['stok'] == 0) $status_icon = '❌';

                    $pesan .= "$status_icon <b>" . strtoupper($b['nama_material']) . "</b>\n";
                    $pesan .= "├ Stok : <b>{$b['stok']} {$b['satuan']}</b>\n";
                    $pesan .= "└ Gudang : {$b['lokasi_gudang']}\n\n";
                }

                $this->kirimPesan($chat_id, $pesan, $this->menuKembali());
                return;
            }

            /* =======================================================
               8. MATERIAL KRITIS & TRANSAKSI
            ======================================================= */ elseif ($text == '⚠ Kritis') {
                if ($is_callback && isset($update['callback_query']['message']['photo'])) {
                    $this->sendApiRequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
                    $message_id = null;
                }

                $barang = $barangModel->where('stok <= minimum_stok')->findAll(15);
                if (!$barang) {
                    $this->kirimAtauEdit($chat_id, $message_id, "✅ Semua material aman.", $this->menuKembali(), $is_callback);
                    return;
                }

                $pesan = "🚨 <b>MATERIAL KRITIS</b>\n━━━━━━━━━━━━━━━━━━\n\n";
                foreach ($barang as $b) {
                    $icon = ($b['stok'] == 0) ? '❌' : '⚠';
                    $pesan .= "$icon <b>{$b['nama_material']}</b>\n├ Stok : <b>{$b['stok']}</b>\n└ Min  : {$b['minimum_stok']}\n\n";
                }
                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
                return;
            } elseif (in_array($text, ['📥 Masuk', '📤 Keluar', '📄 Histori'])) {
                if ($is_callback && isset($update['callback_query']['message']['photo'])) {
                    $this->sendApiRequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
                    $message_id = null;
                }

                $jenis = ($text == '📥 Masuk') ? 'masuk' : (($text == '📤 Keluar') ? 'keluar' : null);
                $judul = ($jenis == 'masuk') ? "📥 TRANSAKSI MASUK" : (($jenis == 'keluar') ? "📤 TRANSAKSI KELUAR" : "📄 HISTORI TERBARU");

                $data = $jenis ? $transaksiModel->where('jenis', $jenis)->orderBy('id', 'DESC')->findAll(10) : $transaksiModel->orderBy('id', 'DESC')->findAll(10);

                $pesan = "<b>$judul</b>\n━━━━━━━━━━━━━━━━━━\n\n";
                if (!$data) {
                    $pesan .= "Belum ada transaksi.";
                } else {
                    foreach ($data as $d) {
                        $b = $barangModel->find($d['barang_id']);
                        $simbol = ($d['jenis'] == 'masuk') ? '🟢 +' : '🔴 -';
                        $pesan .= "▪️ <b>{$b['nama_material']}</b>\n└ $simbol {$d['jumlah']} | " . date('d/m/Y', strtotime($d['tanggal'])) . "\n\n";
                    }
                }
                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
                return;
            } elseif ($text == '🏢 Tentang') {
                if ($is_callback && isset($update['callback_query']['message']['photo'])) {
                    $this->sendApiRequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id]);
                    $message_id = null;
                }

                $pesan  = "🏭 <b>PT WIKA BETON TBK</b>\n━━━━━━━━━━━━━━━━━━\n";
                $pesan .= "<b>Warehouse Monitoring Bot V.Final (Enterprise)</b>\n\n";
                $pesan .= "Visual Analytics, Pagination & DB Integrated.\n";
                $pesan .= "Developed by Bayu Pratama.";

                $this->kirimAtauEdit($chat_id, $message_id, $pesan, $this->menuKembali(), $is_callback);
                return;
            } elseif (strpos($text, '/') === 0) {
                if ($text != '/start') $this->kirimPesan($chat_id, "Ketik /start untuk menampilkan menu.");
            }
        } catch (\Throwable $e) {
            log_message('error', 'Bot Error: ' . $e->getMessage());
        }
    }


    /* =======================================================
       KUMPULAN FUNGSI HELPER
    ======================================================= */

    private function generateProgressBar($current, $total, $length = 12)
    {
        if ($total == 0) return str_repeat('░', $length) . " 0%";
        $percentage = round(($current / $total) * 100);
        $filledLength = round(($length * $percentage) / 100);
        $bar = str_repeat('█', $filledLength) . str_repeat('░', $length - $filledLength);
        return "$bar $percentage%";
    }

    private function kirimAtauEdit($chat_id, $message_id, $text, $keyboard, $is_callback)
    {
        if ($is_callback && $message_id) {
            $this->sendApiRequest('editMessageText', [
                'chat_id'      => $chat_id,
                'message_id'   => $message_id,
                'text'         => $text,
                'parse_mode'   => 'HTML',
                'reply_markup' => json_encode($keyboard)
            ]);
        } else {
            $this->kirimPesan($chat_id, $text, $keyboard);
        }
    }

    private function kirimPesan($chat_id, $text, $keyboard = null)
    {
        $data = ['chat_id' => $chat_id, 'text' => $text, 'parse_mode' => 'HTML'];
        if ($keyboard) $data['reply_markup'] = json_encode($keyboard);
        $this->sendApiRequest('sendMessage', $data);
    }

    private function kirimFoto($chat_id, $photoUrl, $caption, $keyboard = null)
    {
        $data = ['chat_id' => $chat_id, 'photo' => $photoUrl, 'caption' => $caption, 'parse_mode' => 'HTML'];
        if ($keyboard) $data['reply_markup'] = json_encode($keyboard);
        $this->sendApiRequest('sendPhoto', $data);
    }

    private function sendApiRequest($method, $data)
    {
        $client = \Config\Services::curlrequest();
        try {
            $client->post($this->apiUrl . $this->token . '/' . $method, [
                'form_params' => $data,
                'http_errors' => false,
                'timeout'     => 15,
                'verify'      => false // ANTI ERROR DI LOCALHOST XAMPP
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Curl Error Telegram: ' . $e->getMessage());
        }
    }

    public static function kirimNotifSistem($pesanPeringatan)
    {
        $telegram = new self();
        $admin_ids = ['6069266941'];
        foreach ($admin_ids as $admin_id) {
            $pesan = "🚨 <b>SYSTEM ALERT</b> 🚨\n━━━━━━━━━━━━━━━━━━\n\n" . $pesanPeringatan;
            $telegram->sendApiRequest('sendMessage', ['chat_id' => $admin_id, 'text' => $pesan, 'parse_mode' => 'HTML']);
        }
    }

    private function menuUtama()
    {
        // Penambahan Menu "Profil Saya"
        return [
            'inline_keyboard' => [
                [['text' => '📦 Cek Semua Stok', 'callback_data' => 'cekstok_0'], ['text' => '🚨 Stok Kritis', 'callback_data' => '⚠ Kritis']],
                [['text' => '📥 Brg Masuk', 'callback_data' => '📥 Masuk'], ['text' => '📤 Brg Keluar', 'callback_data' => '📤 Keluar']],
                [['text' => '📊 Dashboard Live', 'callback_data' => '📊 Dashboard'], ['text' => '🔎 Cari Material', 'callback_data' => '🔎 Cari']],
                [['text' => '📄 Histori Transaksi', 'callback_data' => '📄 Histori'], ['text' => '👤 Profil Saya', 'callback_data' => '👤 Profil Saya']],
                [['text' => '🔄 Refresh Sistem', 'callback_data' => '🏠 Menu'], ['text' => '🏢 Tentang Bot', 'callback_data' => '🏢 Tentang']]
            ]
        ];
    }

    private function menuKembali()
    {
        return ['inline_keyboard' => [[['text' => '🏠 Kembali ke Menu', 'callback_data' => '🏠 Menu']]]];
    }
}
