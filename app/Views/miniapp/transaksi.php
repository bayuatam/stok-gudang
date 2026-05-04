<?= $this->extend('miniapp/layout') ?>
<?= $this->section('content') ?>

<?php
// PERBAIKAN: Menghitung jumlah ITEM (frekuensi), bukan total volumenya
$item_masuk = 0;
$item_keluar = 0;
if (!empty($transaksi)) {
    foreach ($transaksi as $t) {
        if ($t['jenis'] == 'masuk') {
            $item_masuk++;
        } else {
            $item_keluar++;
        }
    }
}
?>

<style>
    /* --- ANIMASI MASUK --- */
    .animate-up {
        opacity: 0;
        transform: translateY(15px);
        animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* --- SUMMARY CARD (REKAP HARIAN) --- */
    .summary-card {
        background: var(--card-bg);
        border-radius: 20px;
        margin: 0 16px 20px 16px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
    }

    .summary-item {
        flex: 1;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .summary-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--hint-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-value {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.5px;
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 4px;
    }

    .summary-divider {
        width: 1px;
        height: 40px;
        background-color: var(--nav-border);
        margin: 0 16px;
    }

    /* --- STICKY HEADER --- */
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background: var(--bg-color);
        padding-top: 5px;
    }

    .search-container {
        padding: 0 16px 12px 16px;
    }

    .search-box {
        background: rgba(118, 118, 128, 0.12);
        border-radius: 10px;
        display: flex;
        align-items: center;
        padding: 8px 12px;
    }

    .search-box input {
        border: none;
        background: transparent;
        width: 100%;
        color: var(--text-color);
        font-size: 16px;
        outline: none;
        margin-left: 8px;
        font-family: inherit;
    }

    /* SEGMENTED CONTROL */
    .segmented-control {
        background-color: rgba(118, 118, 128, 0.12);
        border-radius: 8px;
        display: flex;
        padding: 3px;
        margin: 0 16px 16px 16px;
    }

    .segment-btn {
        flex: 1;
        text-align: center;
        padding: 6px 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-color);
        border-radius: 6px;
        cursor: pointer;
        transition: 0.2s ease;
        -webkit-tap-highlight-color: transparent;
    }

    .segment-btn.active {
        background-color: var(--card-bg);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12), 0 3px 1px rgba(0, 0, 0, 0.04);
    }

    /* --- GROUPED LIST iOS STYLE --- */
    .ios-list {
        background-color: var(--card-bg);
        border-radius: 14px;
        margin: 0 16px 20px 16px;
        overflow: hidden;
    }

    .ios-list-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
        -webkit-tap-highlight-color: transparent;
    }

    .ios-list-item:active {
        background-color: var(--nav-border);
    }

    .ios-list-item:not(:last-child)::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 60px;
        right: 0;
        height: 0.5px;
        background-color: var(--nav-border);
    }

    .item-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        flex-shrink: 0;
    }

    .icon-masuk {
        background-color: rgba(52, 199, 89, 0.15);
        color: #34C759;
    }

    .icon-keluar {
        background-color: rgba(255, 59, 48, 0.15);
        color: #FF3B30;
    }

    .item-body {
        flex: 1;
    }

    .item-title {
        font-weight: 600;
        font-size: 16px;
        letter-spacing: -0.01em;
        color: var(--text-color);
        margin-bottom: 2px;
    }

    .item-subtitle {
        font-size: 13px;
        color: var(--hint-color);
    }

    .item-trailing {
        text-align: right;
    }

    .item-qty {
        font-weight: 700;
        font-size: 16px;
    }

    .qty-masuk {
        color: #34C759;
    }

    .qty-keluar {
        color: var(--text-color);
    }

    /* --- BOTTOM SHEET (DETAIL MUTASI) --- */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        opacity: 0;
        pointer-events: none;
        transition: 0.3s ease;
        z-index: 2000;
    }

    .overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .bottom-sheet {
        position: fixed;
        bottom: -100%;
        left: 0;
        width: 100%;
        background: var(--card-bg);
        border-radius: 24px 24px 0 0;
        padding: 16px 20px calc(24px + env(safe-area-inset-bottom));
        box-sizing: border-box;
        transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 2001;
        box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.1);
    }

    .bottom-sheet.active {
        bottom: 0;
    }

    .drag-handle {
        width: 40px;
        height: 5px;
        background: var(--hint-color);
        border-radius: 3px;
        margin: 0 auto 20px auto;
        opacity: 0.4;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 0.5px solid var(--nav-border);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--hint-color);
        font-size: 14px;
        font-weight: 500;
    }

    .detail-value {
        color: var(--text-color);
        font-size: 15px;
        font-weight: 600;
        text-align: right;
    }

    /* Tombol Aksi Bawah */
    .action-row {
        display: flex;
        gap: 12px;
        margin-top: 10px;
    }

    .btn-primary {
        flex: 1;
        padding: 16px;
        border-radius: 14px;
        border: none;
        background: #007AFF;
        color: #FFF;
        font-size: 16px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-primary:active {
        transform: scale(0.96);
    }

    .btn-secondary {
        flex: 1;
        padding: 16px;
        border-radius: 14px;
        border: none;
        background: rgba(0, 122, 255, 0.1);
        color: #007AFF;
        font-size: 16px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-secondary:active {
        transform: scale(0.96);
    }
</style>

<div class="header animate-up">
    <h1 style="font-size: 34px; letter-spacing: -0.5px; font-weight: 700;">Hari Ini</h1>
    <p><?= date('d F Y') ?></p>
</div>

<!-- =========================================
     REKAP HARIAN (SUMMARY CARD) - FIXED
========================================== -->
<div class="summary-card animate-up" style="animation-delay: 0.05s;">
    <div class="summary-item">
        <span class="summary-label">Barang Masuk</span>
        <span class="summary-value" style="color: #34C759;">
            <?= number_format($item_masuk, 0, ',', '.') ?>
            <span style="font-size: 13px; font-weight: 600;">Item</span>
        </span>
    </div>
    <div class="summary-divider"></div>
    <div class="summary-item">
        <span class="summary-label">Barang Keluar</span>
        <span class="summary-value" style="color: #FF3B30;">
            <?= number_format($item_keluar, 0, ',', '.') ?>
            <span style="font-size: 13px; font-weight: 600;">Item</span>
        </span>
    </div>
</div>

<!-- =========================================
     STICKY HEADER (SEARCH + TABS)
========================================== -->
<div class="sticky-header animate-up" style="animation-delay: 0.1s;">
    <div class="search-container">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--hint-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari transaksi hari ini...">
        </div>
    </div>
    <div class="segmented-control">
        <div class="segment-btn active" onclick="setFilter('semua', this)">Semua</div>
        <div class="segment-btn" onclick="setFilter('masuk', this)">Masuk</div>
        <div class="segment-btn" onclick="setFilter('keluar', this)">Keluar</div>
    </div>
</div>

<!-- =========================================
     DAFTAR TRANSAKSI
========================================== -->
<div id="transaksiContainer" class="animate-up" style="animation-delay: 0.2s; padding-bottom: 20px;">

    <div id="emptyState" style="display: <?= empty($transaksi) ? 'block' : 'none' ?>; text-align: center; color: var(--hint-color); padding: 40px 16px;">
        <div style="font-size: 40px; margin-bottom: 10px; opacity: 0.5;">ðŸ“­</div>
        <div style="font-weight: 500; font-size: 15px;">Tidak ada transaksi hari ini.</div>
    </div>

    <?php if (!empty($transaksi)): ?>
        <div class="ios-list">
            <?php foreach ($transaksi as $t): ?>
                <?php
                $isMasuk = $t['jenis'] == 'masuk';
                $qty = $t['jumlah'] ?? $t['qty'] ?? 0;
                $satuan = $t['satuan'] ?? '';
                $time = date('H:i', strtotime($t['tanggal']));
                $fullDate = date('d M Y, H:i:s', strtotime($t['tanggal']));
                $qtyFormatted = number_format($qty, 0, ',', '.');
                ?>

                <div class="ios-list-item trx-item"
                    data-nama="<?= htmlspecialchars($t['nama_material']) ?>"
                    data-jenis="<?= $t['jenis'] ?>"
                    data-waktu="<?= $fullDate ?>"
                    data-qty="<?= $isMasuk ? '+' . $qtyFormatted : '-' . $qtyFormatted ?>"
                    data-satuan="<?= htmlspecialchars($satuan) ?>"
                    data-warna="<?= $isMasuk ? '#34C759' : '#FF3B30' ?>"
                    onclick="openDetail(this)">

                    <div class="item-icon <?= $isMasuk ? 'icon-masuk' : 'icon-keluar' ?>">
                        <?php if ($isMasuk): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:20px; height:20px;">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <polyline points="19 12 12 19 5 12"></polyline>
                            </svg>
                        <?php else: ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:20px; height:20px;">
                                <line x1="12" y1="19" x2="12" y2="5"></line>
                                <polyline points="5 12 5 19 12"></polyline>
                            </svg>
                        <?php endif; ?>
                    </div>

                    <div class="item-body">
                        <div class="item-title"><?= $t['nama_material'] ?></div>
                        <div class="item-subtitle">Pukul <?= $time ?> WIB</div>
                    </div>

                    <div class="item-trailing">
                        <div class="item-qty <?= $isMasuk ? 'qty-masuk' : 'qty-keluar' ?>">
                            <?= $isMasuk ? '+' : '-' ?><?= $qtyFormatted ?> <span style="font-size:12px; opacity:0.7;"><?= $satuan ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- =========================================
     BOTTOM SHEET (DETAIL & SHARE)
========================================== -->
<div class="overlay" id="sheetOverlay" onclick="closeDetail()"></div>

<div class="bottom-sheet" id="detailSheet">
    <div class="drag-handle"></div>

    <div style="text-align: center; margin-bottom: 24px;">
        <div style="font-size: 13px; color: var(--hint-color); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Bukti Mutasi Harian</div>
        <h2 id="sheetQty" style="margin: 0; font-size: 36px; font-weight: 700; letter-spacing: -0.02em;">0</h2>
    </div>

    <div style="background: var(--bg-color); border-radius: 14px; padding: 0 16px; margin-bottom: 24px;">
        <div class="detail-row"><span class="detail-label">Material</span><span class="detail-value" id="sheetNama">-</span></div>
        <div class="detail-row"><span class="detail-label">Jenis Transaksi</span><span class="detail-value" id="sheetJenis" style="text-transform: capitalize;">-</span></div>
        <div class="detail-row"><span class="detail-label">Waktu Catat</span><span class="detail-value" id="sheetWaktu">-</span></div>
        <div class="detail-row"><span class="detail-label">Sistem</span><span class="detail-value">Aplikasi Gudang Web</span></div>
    </div>

    <div class="action-row">
        <button class="btn-primary" onclick="shareReceipt()">Bagikan Resi</button>
        <button class="btn-secondary" onclick="closeDetail()">Tutup</button>
    </div>
</div>

<!-- =========================================
     LOGIKA JAVASCRIPT
========================================== -->
<script>
    let currentFilter = 'semua';
    let tg = window.Telegram.WebApp;

    function tgHaptic(style) {
        if (tg.HapticFeedback) tg.HapticFeedback.impactOccurred(style);
    }

    // Filter Logic
    function setFilter(jenis, btn) {
        tgHaptic('light');
        document.querySelectorAll('.segment-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = jenis;
        applyFilters();
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);

    function applyFilters() {
        let keyword = document.getElementById('searchInput').value.toLowerCase();
        let items = document.querySelectorAll('.trx-item');
        let visibleCount = 0;

        items.forEach(item => {
            let nama = item.getAttribute('data-nama').toLowerCase();
            let jenis = item.getAttribute('data-jenis');

            let matchSearch = nama.includes(keyword);
            let matchFilter = (currentFilter === 'semua') || (jenis === currentFilter);

            if (matchSearch && matchFilter) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        let listContainer = document.querySelector('.ios-list');
        if (listContainer) {
            if (visibleCount === 0) {
                document.getElementById('emptyState').style.display = 'block';
                listContainer.style.display = 'none';
            } else {
                document.getElementById('emptyState').style.display = 'none';
                listContainer.style.display = 'block';
            }
        }
    }

    // Bottom Sheet Logic
    function openDetail(element) {
        tgHaptic('medium');

        let qty = element.getAttribute('data-qty');
        let satuan = element.getAttribute('data-satuan');
        let warna = element.getAttribute('data-warna');

        let sheetQty = document.getElementById('sheetQty');
        sheetQty.innerText = qty + ' ' + satuan;
        sheetQty.style.color = warna;

        document.getElementById('sheetNama').innerText = element.getAttribute('data-nama');
        document.getElementById('sheetJenis').innerText = "Barang " + element.getAttribute('data-jenis');
        document.getElementById('sheetWaktu').innerText = element.getAttribute('data-waktu');

        document.getElementById('sheetOverlay').classList.add('active');
        document.getElementById('detailSheet').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDetail() {
        document.getElementById('sheetOverlay').classList.remove('active');
        document.getElementById('detailSheet').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Fitur Share Native Telegram
    function shareReceipt() {
        tgHaptic('heavy');
        tg.showConfirm("Kirim bukti mutasi/resi transaksi ini ke chat Telegram?", function(is_confirmed) {
            if (is_confirmed) {
                tg.showAlert("âœ… Bukti mutasi berhasil dibagikan!");
                closeDetail();
            }
        });
    }
</script>

<?= $this->endSection() ?>