<?= $this->extend('miniapp/layout') ?>
<?= $this->section('content') ?>

<style>
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

    .category-tabs {
        display: flex;
        gap: 8px;
        padding: 0 16px 12px 16px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .category-tabs::-webkit-scrollbar {
        display: none;
    }

    .cat-btn {
        padding: 6px 16px;
        border-radius: 16px;
        background: rgba(118, 118, 128, 0.1);
        color: var(--hint-color);
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        transition: 0.2s all ease;
        border: 1px solid transparent;
    }

    .cat-btn.active {
        background: var(--primary-color);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 122, 255, 0.2);
    }

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
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background-color: rgba(0, 122, 255, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        flex-shrink: 0;
    }

    .item-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
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
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .item-stock {
        font-weight: 700;
        font-size: 16px;
        color: var(--text-color);
    }

    .item-unit {
        font-size: 12px;
        color: var(--hint-color);
        font-weight: 500;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        margin-top: 4px;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    /* --- BOTTOM SHEET PERBAIKAN CENTER --- */
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
        /* Padding Horizontal di-NOL-kan agar slider bisa 100% full */
        padding: 16px 0 calc(24px + env(safe-area-inset-bottom));
        box-sizing: border-box;
        transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 2001;
        box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.1);
        overflow-x: hidden;
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

    /* Form Input Animasi */
    .view-slider {
        display: flex;
        width: 200%;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .view-pane {
        width: 50%;
        flex-shrink: 0;
        /* KUNCI PERBAIKAN: Padding dimasukkan ke dalam elemen dengan border-box */
        box-sizing: border-box;
        padding: 0 20px;
    }

    .action-btn {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
    }

    .action-btn:active {
        transform: scale(0.95);
    }

    .input-huge {
        width: 100%;
        text-align: center;
        font-size: 40px;
        font-weight: 800;
        border: none;
        background: transparent;
        color: var(--text-color);
        outline: none;
        margin: 20px 0;
    }
</style>

<?php
$categories = [];
foreach ($barang as $b) {
    $kat = !empty($b['kategori']) ? $b['kategori'] : 'Lainnya';
    if (!in_array($kat, $categories)) {
        $categories[] = $kat;
    }
}
sort($categories);
?>

<div class="header animate-up">
    <h1 style="font-size: 34px; letter-spacing: -0.5px; font-weight: 700;">Data Stok</h1>
</div>

<div class="sticky-header animate-up" style="animation-delay: 0.1s;">
    <div class="search-container">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--hint-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari material atau kode...">
        </div>
    </div>

    <div class="category-tabs">
        <div class="cat-btn active" onclick="setCategory('Semua', this)">Semua</div>
        <?php foreach ($categories as $c): ?>
            <div class="cat-btn" onclick="setCategory('<?= htmlspecialchars($c) ?>', this)"><?= $c ?></div>
        <?php endforeach; ?>
    </div>
</div>

<div id="barangList" class="animate-up" style="animation-delay: 0.2s;">
    <div id="emptyState" style="display: none; text-align: center; padding: 40px 16px;">
        <div style="font-size: 40px; margin-bottom: 10px; opacity: 0.5;">📦</div>
        <div style="color: var(--hint-color); font-weight: 500; font-size: 15px;">Pencarian tidak ditemukan.</div>
    </div>

    <?php if (!empty($barang)): ?>
        <div class="ios-list">
            <?php foreach ($barang as $b): ?>
                <?php
                $stok = (int)$b['stok'];
                $min = isset($b['minimum_stok']) ? (int)$b['minimum_stok'] : 0;
                $satuan = isset($b['satuan']) ? $b['satuan'] : 'unit';
                $kode = isset($b['kode_sumber_daya']) ? $b['kode_sumber_daya'] : '-';
                $kategori = !empty($b['kategori']) ? $b['kategori'] : 'Lainnya';
                $lokasi = isset($b['lokasi_gudang']) ? $b['lokasi_gudang'] : '-';

                if ($stok <= 0) {
                    $statusColor = '#FF3B30';
                    $statusBg = 'rgba(255, 59, 48, 0.15)';
                    $statusText = 'Habis';
                } elseif ($stok <= $min) {
                    $statusColor = '#FF9500';
                    $statusBg = 'rgba(255, 149, 0, 0.15)';
                    $statusText = 'Kritis';
                } else {
                    $statusColor = '#34C759';
                    $statusBg = 'rgba(52, 199, 89, 0.15)';
                    $statusText = 'Aman';
                }
                ?>

                <div class="ios-list-item barang-item"
                    data-id="<?= $b['id'] ?>"
                    data-nama="<?= htmlspecialchars($b['nama_material']) ?>"
                    data-kode="<?= htmlspecialchars($kode) ?>"
                    data-kategori="<?= htmlspecialchars($kategori) ?>"
                    data-stok="<?= $stok ?>"
                    data-satuan="<?= $satuan ?>"
                    data-lokasi="<?= htmlspecialchars($lokasi) ?>"
                    data-status="<?= $statusText ?>"
                    data-warna="<?= $statusColor ?>"
                    data-bg="<?= $statusBg ?>"
                    onclick="openDetail(this)">

                    <div class="item-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        </svg>
                    </div>

                    <div class="item-body">
                        <div class="item-title"><?= $b['nama_material'] ?></div>
                        <div class="item-subtitle"><?= $kode ?></div>
                    </div>

                    <div class="item-trailing">
                        <div class="item-stock"><?= number_format($stok, 0, ',', '.') ?> <span class="item-unit"><?= $satuan ?></span></div>
                        <div class="status-badge" style="background: <?= $statusBg ?>; color: <?= $statusColor ?>;"><?= $statusText ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- =========================================
     BOTTOM SHEET (INTERACTIVE)
========================================== -->
<div class="overlay" id="sheetOverlay" onclick="closeDetail()"></div>

<div class="bottom-sheet" id="detailSheet">
    <div class="drag-handle"></div>

    <!-- Wadah Slider (Menggeser antara Info dan Form Input) -->
    <div class="view-slider" id="sheetSlider">

        <!-- PANE 1: INFORMASI DETAIL -->
        <div class="view-pane">
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 id="sheetNama" style="margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.02em;">Nama Material</h2>
                <span id="sheetStatus" style="display: inline-block; margin-top: 8px; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 700;">Status</span>
            </div>

            <div style="background: var(--bg-color); border-radius: 14px; padding: 0 16px; margin-bottom: 20px;">
                <div class="detail-row"><span class="detail-label">Sisa Stok</span><span class="detail-value" id="sheetStok">0</span></div>
                <div class="detail-row"><span class="detail-label">Kode Barang</span><span class="detail-value" id="sheetKode">-</span></div>
                <div class="detail-row"><span class="detail-label">Kategori</span><span class="detail-value" id="sheetKategori">-</span></div>
                <div class="detail-row"><span class="detail-label">Lokasi Gudang</span><span class="detail-value" id="sheetLokasi">-</span></div>
            </div>

            <div style="display: flex; gap: 10px;">
                <div class="action-btn" style="background: rgba(52, 199, 89, 0.15); color: #34C759;" onclick="openInputMode('masuk')">
                    📥 Masuk
                </div>
                <div class="action-btn" style="background: rgba(255, 59, 48, 0.15); color: #FF3B30;" onclick="openInputMode('keluar')">
                    📤 Keluar
                </div>
            </div>
        </div>

        <!-- PANE 2: FORM INPUT DIGITAL -->
        <div class="view-pane">
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <div onclick="backToDetail()" style="color: var(--primary-color); font-weight: 600; cursor: pointer;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </div>
                <div style="flex: 1; text-align: center; font-weight: 700; font-size: 16px;" id="inputTitle">Catat Masuk</div>
                <div style="width: 24px;"></div>
            </div>

            <div style="text-align: center; color: var(--hint-color); font-size: 14px;" id="inputMaterialName">Material Name</div>

            <input type="number" id="inputQty" class="input-huge" placeholder="0" inputmode="numeric">
            <div style="text-align: center; font-weight: 600; color: var(--hint-color); margin-top: -10px; margin-bottom: 20px;" id="inputUnit">Unit</div>
        </div>

    </div>
</div>

<script>
    let currentCategory = 'Semua';
    let selectedItem = {};
    let inputMode = '';

    let tg = window.Telegram.WebApp;

    function tgHaptic(style) {
        if (tg.HapticFeedback) tg.HapticFeedback.impactOccurred(style);
    }

    // Filter Kategori
    function setCategory(kategori, element) {
        tgHaptic('light');
        document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
        element.classList.add('active');
        currentCategory = kategori;
        applyFilter();
    }
    document.getElementById('searchInput').addEventListener('input', applyFilter);

    function applyFilter() {
        let keyword = document.getElementById('searchInput').value.toLowerCase();
        let items = document.querySelectorAll('.barang-item');
        let visibleCount = 0;

        items.forEach(item => {
            let nama = item.getAttribute('data-nama').toLowerCase();
            let kode = item.getAttribute('data-kode').toLowerCase();
            let kat = item.getAttribute('data-kategori');

            let matchSearch = nama.includes(keyword) || kode.includes(keyword);
            let matchCategory = (currentCategory === 'Semua') || (kat === currentCategory);

            if (matchSearch && matchCategory) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            document.getElementById('emptyState').style.display = 'block';
            document.querySelector('.ios-list').style.display = 'none';
        } else {
            document.getElementById('emptyState').style.display = 'none';
            document.querySelector('.ios-list').style.display = 'block';
        }
    }

    // Buka Detail Modal
    function openDetail(element) {
        tgHaptic('medium');

        selectedItem = {
            id: element.getAttribute('data-id'),
            nama: element.getAttribute('data-nama'),
            satuan: element.getAttribute('data-satuan')
        };

        document.getElementById('sheetSlider').style.transform = 'translateX(0)';
        tg.MainButton.hide();

        document.getElementById('sheetNama').innerText = element.getAttribute('data-nama');
        document.getElementById('sheetKode').innerText = element.getAttribute('data-kode');
        document.getElementById('sheetKategori').innerText = element.getAttribute('data-kategori');

        // Memformat angka stok agar ada titik pemisah ribuan
        let stokReal = parseInt(element.getAttribute('data-stok'));
        document.getElementById('sheetStok').innerText = stokReal.toLocaleString('id-ID') + ' ' + element.getAttribute('data-satuan');

        document.getElementById('sheetLokasi').innerText = element.getAttribute('data-lokasi');

        let badge = document.getElementById('sheetStatus');
        badge.innerText = element.getAttribute('data-status');
        badge.style.color = element.getAttribute('data-warna');
        badge.style.backgroundColor = element.getAttribute('data-bg');

        document.getElementById('sheetOverlay').classList.add('active');
        document.getElementById('detailSheet').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDetail() {
        document.getElementById('sheetOverlay').classList.remove('active');
        document.getElementById('detailSheet').classList.remove('active');
        document.body.style.overflow = '';
        tg.MainButton.hide();
    }

    // Geser ke Form Input
    function openInputMode(tipe) {
        tgHaptic('light');
        inputMode = tipe;

        document.getElementById('inputTitle').innerText = (tipe === 'masuk') ? '📥 Barang Masuk' : '📤 Barang Keluar';
        document.getElementById('inputMaterialName').innerText = selectedItem.nama;
        document.getElementById('inputUnit').innerText = selectedItem.satuan.toUpperCase();
        document.getElementById('inputQty').value = '';

        document.getElementById('sheetSlider').style.transform = 'translateX(-50%)';

        tg.MainButton.text = (tipe === 'masuk') ? "SIMPAN BARANG MASUK" : "SIMPAN BARANG KELUAR";
        tg.MainButton.color = (tipe === 'masuk') ? "#34C759" : "#FF3B30";
        tg.MainButton.textColor = "#ffffff";
        tg.MainButton.show();

        setTimeout(() => document.getElementById('inputQty').focus(), 300);
    }

    function backToDetail() {
        tgHaptic('light');
        document.getElementById('sheetSlider').style.transform = 'translateX(0)';
        tg.MainButton.hide();
    }

    // Submit ke Database via API MainButton Telegram
    Telegram.WebApp.onEvent('mainButtonClicked', function() {
        let qty = document.getElementById('inputQty').value;
        if (!qty || qty <= 0) {
            tg.showAlert("⚠️ Masukkan jumlah yang valid!");
            return;
        }

        tg.MainButton.showProgress();

        let formData = new FormData();
        formData.append('barang_id', selectedItem.id);
        formData.append('jumlah', qty);

        let endpointUrl = (inputMode === 'masuk') ? '/barang-masuk/simpan' : '/barang-keluar/simpan';

        fetch(endpointUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                tg.MainButton.hideProgress();
                tg.showAlert("✅ Transaksi berhasil dicatat!", function() {
                    window.location.reload();
                });
            })
            .catch(error => {
                tg.MainButton.hideProgress();
                tg.showAlert("Mencoba menyimpan data... Pastikan Controller 'Transaksi::simpan' mengembalikan JSON response.", function() {
                    closeDetail();
                });
            });
    });
</script>

<?= $this->endSection() ?>