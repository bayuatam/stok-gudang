<?= $this->extend('miniapp/layout') ?>
<?= $this->section('content') ?>

<?php
// Logika Sapaan Cerdas Berdasarkan Waktu
$jam = date('H');
if ($jam < 11) $sapaan = 'Selamat Pagi';
elseif ($jam < 15) $sapaan = 'Selamat Siang';
elseif ($jam < 18) $sapaan = 'Selamat Sore';
else $sapaan = 'Selamat Malam';
?>

<style>
    /* --- ANIMASI MASUK --- */
    .animate-up {
        opacity: 0;
        transform: translateY(15px);
        animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .delay-1 {
        animation-delay: 0.1s;
    }

    .delay-2 {
        animation-delay: 0.2s;
    }

    .delay-3 {
        animation-delay: 0.3s;
    }

    .delay-4 {
        animation-delay: 0.4s;
    }

    /* --- HEADER & AVATAR --- */
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 20px 10px 20px;
    }

    .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background-color: var(--nav-border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid var(--card-bg);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        flex-shrink: 0;
    }

    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    /* --- QUICK ACTIONS ALA GOPAY/OVO --- */
    .quick-actions {
        display: flex;
        justify-content: space-between;
        padding: 10px 24px 16px 24px;
    }

    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }

    .action-btn:active {
        transform: scale(0.92);
        transition: 0.1s;
    }

    .action-circle {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 22px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .action-text {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-color);
        letter-spacing: -0.01em;
    }

    /* --- MEMBER CARD UI --- */
    .member-card {
        background: linear-gradient(135deg, #007AFF 0%, #0056b3 100%);
        border-radius: 20px;
        padding: 20px;
        margin: 0 20px 20px 20px;
        color: #fff;
        box-shadow: 0 10px 20px rgba(0, 122, 255, 0.25);
        position: relative;
        overflow: hidden;
    }

    .member-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* --- GRID WIDGETS INTERAKTIF --- */
    .grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        padding: 0 20px 20px 20px;
    }

    .stat-card {
        background-color: var(--card-bg);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s ease, background-color 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .stat-card:active {
        transform: scale(0.96);
        background-color: var(--nav-border);
    }

    .stat-card span {
        font-size: 12px;
        color: var(--hint-color);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .stat-card b {
        font-size: 26px;
        margin-top: 6px;
        color: var(--text-color);
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .widget-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .widget-icon svg {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
    }
</style>

<!-- 1. HEADER & AVATAR -->
<div class="header-top animate-up">
    <div>
        <p style="margin: 0; font-size: 15px; color: var(--hint-color); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;"><?= $sapaan ?> 👋</p>
        <h1 style="margin: 2px 0 0 0; font-size: 26px; font-weight: 800; letter-spacing: -0.02em;" id="tg-user-name">Manager</h1>
    </div>
    <div class="avatar-circle" onclick="tgHaptic('medium')">
        <img id="avatar-img" src="" alt="Profile">
        <svg id="avatar-fallback" viewBox="0 0 24 24" fill="none" stroke="var(--hint-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:24px; height:24px;">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
    </div>
</div>

<!-- 2. QUICK ACTIONS -->
<div class="quick-actions animate-up delay-1">
    <div class="action-btn" onclick="navTo('/miniapp/barang')">
        <div class="action-circle" style="background: rgba(52, 199, 89, 0.12); color: #34C759;">📥</div>
        <span class="action-text">In</span>
    </div>
    <div class="action-btn" onclick="navTo('/miniapp/barang')">
        <div class="action-circle" style="background: rgba(255, 59, 48, 0.12); color: #FF3B30;">📤</div>
        <span class="action-text">Out</span>
    </div>
    <div class="action-btn" onclick="navTo('/miniapp/histori')">
        <div class="action-circle" style="background: rgba(175, 82, 222, 0.12); color: #AF52DE;">📜</div>
        <span class="action-text">Mutasi</span>
    </div>
    <div class="action-btn" onclick="triggerCetakPDF()">
        <div class="action-circle" style="background: rgba(0, 122, 255, 0.12); color: #007AFF;">📄</div>
        <span class="action-text">Cetak PDF</span>
    </div>
</div>

<!-- 3. MEMBER CARD UI -->
<div class="member-card animate-up delay-2" onclick="tgHaptic('light')">
    <div style="font-size: 13px; opacity: 0.8; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Sistem Utama</div>
    <div style="font-size: 22px; font-weight: 800; margin-top: 2px; letter-spacing: -0.5px;">WIKA BETON Gudang</div>
    <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div style="font-size: 12px; opacity: 0.9; font-weight: 500;">
            <div style="opacity: 0.7; font-size: 10px;">TERAKHIR DIPERBARUI</div>
            <?= date('d M Y, H:i') ?> WIB
        </div>
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; opacity: 0.5;">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
    </div>
</div>

<!-- 4. GRID WIDGETS INTERAKTIF -->
<div class="grid animate-up delay-3">
    <div class="stat-card" onclick="navTo('/miniapp/barang')">
        <div class="widget-icon" style="background: rgba(0, 122, 255, 0.12); color: #007AFF;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            </svg>
        </div>
        <span>Total Material</span>
        <b><?= number_format($total_barang, 0, ',', '.') ?></b>
    </div>

    <div class="stat-card" onclick="navTo('/miniapp/transaksi')">
        <div class="widget-icon" style="background: rgba(52, 199, 89, 0.12); color: #34C759;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
        </div>
        <span>Aktivitas Harian</span>
        <b style="color: #34C759;"><?= number_format($transaksi_hari_ini, 0, ',', '.') ?></b>
    </div>

    <div class="stat-card" style="grid-column: span 2;" onclick="tgHaptic('medium')">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span>Perlu Restock (Kritis)</span>
                <b style="color: #FF3B30; display: block;"><?= number_format($stok_kritis, 0, ',', '.') ?> <small style="font-size: 14px; font-weight: 500;">Item</small></b>
            </div>
            <div class="widget-icon" style="background: rgba(255, 59, 48, 0.12); color: #FF3B30; margin: 0; width: 44px; height: 44px; border-radius: 14px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- 5. CHART KATEGORI -->
<div class="card animate-up delay-4" style="margin-top: 0;">
    <div style="font-weight: 700; font-size: 17px; margin-bottom: 16px;">Komposisi Kategori</div>
    <div style="position: relative; height: 260px; width: 100%;">
        <canvas id="kategoriChart" style="width: 100%; height: 100%;"></canvas>
    </div>
</div>

<script>
    let tg = window.Telegram.WebApp;

    function tgHaptic(style) {
        if (tg.HapticFeedback) tg.HapticFeedback.impactOccurred(style);
    }

    function navTo(url) {
        tgHaptic('light');
        setTimeout(() => {
            window.location.href = url;
        }, 100);
    }

    // FUNGSI CETAK PDF ASLI (MENGGUNAKAN TRIGGERPDR DI TELEGRAM CONTROLLER)
    function triggerCetakPDF() {
        tgHaptic('heavy');
        tg.showConfirm("Kirim rekapan laporan PDF STOK ke chat Telegram?", function(is_confirmed) {
            if (is_confirmed) {
                let tgUser = tg.initDataUnsafe?.user;
                if (!tgUser || !tgUser.id) {
                    tg.showAlert("⚠️ Gagal mendapatkan ID Telegram Anda.");
                    return;
                }

                tg.showAlert("⏳ Sedang memproses PDF Stok... Silakan cek chat Bot Anda sebentar lagi.");

                let formData = new FormData();
                formData.append('telegram_id', tgUser.id);

                fetch('/telegram/triggerPdf', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    // Beri jeda 2 detik sebelum menutup app agar sinyal terkirim sempurna
                    setTimeout(() => {
                        tg.close();
                    }, 2000);
                }).catch(() => {
                    tg.showAlert("❌ Gagal menghubungi server.");
                });
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        let tgData = tg.initDataUnsafe;
        if (tgData && tgData.user) {
            document.getElementById('tg-user-name').innerText = tgData.user.first_name;
            if (tgData.user.photo_url) {
                let imgEl = document.getElementById('avatar-img');
                imgEl.src = tgData.user.photo_url;
                imgEl.style.display = 'block';
                document.getElementById('avatar-fallback').style.display = 'none';
            }
        }
    });

    const textColor = getComputedStyle(document.documentElement).getPropertyValue('--text-color').trim() || '#000';
    const ctx = document.getElementById('kategoriChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                data: <?= json_encode($chartData) ?>,
                backgroundColor: ['#007AFF', '#FF2D55', '#FF9500', '#34C759', '#AF52DE', '#FFCC00'],
                borderWidth: 3,
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--card-bg').trim() || '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: textColor,
                        usePointStyle: true,
                        boxWidth: 8,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>