<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$totalBarang = count($barang);

$totalMaterial = count(array_filter($barang, fn($x) => $x['kategori'] == 'Material'));
$totalSparepart = count(array_filter($barang, fn($x) => $x['kategori'] == 'Suku Cadang'));
$totalBBM = count(array_filter($barang, fn($x) => $x['kategori'] == 'BBM'));

$stokKritis = count(array_filter($barang, fn($x) => $x['stok'] <= 10 && $x['stok'] > 0));
?>

<div class="container-fluid">

    <!-- HERO -->
    <div class="hero-box mb-4">
        <div>
            <div class="hero-mini">PT WIJAYA KARYA BETON TBK</div>
            <h2 class="hero-title mb-1">Master Barang Gudang</h2>
            <p class="hero-subtitle mb-0">
                Monitoring data persediaan gudang perusahaan
            </p>
        </div>

        <a href="<?= base_url('barang/tambah') ?>" class="btn btn-main">
            + Tambah Barang
        </a>
    </div>

    <!-- KPI -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-2">
            <div class="mini-card">
                <small>Total Barang</small>
                <h3><?= $totalBarang ?></h3>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="mini-card">
                <small>Material</small>
                <h3 class="text-primary"><?= $totalMaterial ?></h3>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="mini-card">
                <small>Suku Cadang</small>
                <h3 style="color:#f59e0b"><?= $totalSparepart ?></h3>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="mini-card">
                <small>BBM</small>
                <h3 class="text-success"><?= $totalBBM ?></h3>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="mini-card">
                <small>Stok Kritis</small>
                <h3 class="text-danger"><?= $stokKritis ?></h3>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="mini-card">
                <small>Status</small>
                <h3 class="text-success">Online</h3>
            </div>
        </div>

    </div>

    <!-- PANEL -->
    <div class="panel-box">

        <div class="row g-3 mb-4">

            <div class="col-12 col-lg-5">
                <input type="text"
                    id="searchInput"
                    class="form-control search-box"
                    placeholder="Cari kode / nama barang / gudang...">
            </div>

            <div class="col-6 col-lg-2">
                <select class="form-select" id="filterJenis">
                    <option value="">Semua Kategori</option>
                    <option value="Material">Material</option>
                    <option value="Suku Cadang">Suku Cadang</option>
                    <option value="BBM">BBM</option>
                </select>
            </div>

            <div class="col-6 col-lg-2">
                <select class="form-select" id="filterStok">
                    <option value="">Semua Stok</option>
                    <option value="aman">Aman</option>
                    <option value="kritis">Kritis</option>
                    <option value="habis">Habis</option>
                </select>
            </div>

            <div class="col-12 col-lg-3">
                <button class="btn btn-reset w-100" onclick="resetSearch()">
                    Reset Filter
                </button>
            </div>

        </div>

        <div class="result-box mb-3">
            Menampilkan <strong id="resultCount"><?= $totalBarang ?></strong> data barang
        </div>

        <div class="table-responsive">
            <table class="table table-enterprise align-middle" id="materialTable">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Gudang</th>
                        <th>Status</th>
                        <th width="170">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $no = 1;
                    foreach ($barang as $b): ?>

                        <tr>
                            <td><?= $no++ ?></td>

                            <td>
                                <span class="code-chip"><?= $b['kode_sumber_daya'] ?></span>
                            </td>

                            <td>
                                <strong><?= ucwords(strtolower($b['nama_material'])) ?></strong><br>
                                <small class="text-muted"><?= $b['satuan'] ?></small>
                            </td>

                            <td class="jenis-cell">

                                <?php if ($b['kategori'] == 'Material'): ?>
                                    <span class="badge bg-primary">Material</span>

                                <?php elseif ($b['kategori'] == 'Suku Cadang'): ?>
                                    <span class="badge bg-warning text-dark">Suku Cadang</span>

                                <?php else: ?>
                                    <span class="badge bg-success">BBM</span>
                                <?php endif; ?>

                            </td>

                            <td>

                                <?php if ($b['stok'] == 0): ?>
                                    <span class="badge bg-dark">0</span>

                                <?php elseif ($b['stok'] <= 10): ?>
                                    <span class="badge bg-danger"><?= $b['stok'] ?></span>

                                <?php else: ?>
                                    <span class="badge bg-success"><?= $b['stok'] ?></span>
                                <?php endif; ?>

                            </td>

                            <td><?= $b['lokasi_gudang'] ?></td>

                            <td>
                                <span class="badge bg-info text-dark"><?= $b['status_barang'] ?></span>
                            </td>

                            <td>
                                <div class="action-wrap">

                                    <a href="<?= base_url('barang/edit/' . $b['id']) ?>" class="btn btn-edit btn-sm">
                                        Edit
                                    </a>

                                    <a href="<?= base_url('barang/hapus/' . $b['id']) ?>"
                                        onclick="return confirm('Hapus data ini?')"
                                        class="btn btn-delete btn-sm">
                                        Hapus
                                    </a>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    </div>
</div>

<style>
    .hero-box {
        background: linear-gradient(135deg, #003366, #005BAC);
        color: #fff;
        border-radius: 24px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        box-shadow: 0 20px 35px rgba(0, 51, 102, .18);
    }

    .hero-title {
        font-size: 30px;
        font-weight: 800;
    }

    .hero-mini {
        font-size: 12px;
        font-weight: 700;
        opacity: .8;
        letter-spacing: 1px;
    }

    .hero-subtitle {
        opacity: .9;
    }

    .mini-card,
    .panel-box {
        background: #fff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, .05);
        height: 100%;
    }

    .mini-card small {
        color: #64748b;
        font-weight: 700;
    }

    .mini-card h3 {
        font-size: 28px;
        font-weight: 800;
        margin-top: 8px;
    }

    .search-box,
    .form-select {
        height: 52px;
        border-radius: 14px;
    }

    .result-box {
        background: #f8fafc;
        padding: 12px 16px;
        border-radius: 14px;
        font-size: 14px;
        color: #475569;
    }

    .btn-main {
        background: linear-gradient(135deg, #f5a623, #ffbf47);
        border: none;
        font-weight: 700;
        border-radius: 14px;
        padding: 12px 18px;
    }

    .btn-reset {
        background: #e5e7eb;
        border: none;
        font-weight: 700;
        border-radius: 14px;
        height: 52px;
    }

    .table-enterprise thead th {
        background: #f8fafc;
        border: none;
        color: #64748b;
        font-size: 14px;
    }

    .table-enterprise td {
        border-color: #eef2f7;
        vertical-align: middle;
        font-size: 14px;
    }

    .table-enterprise tbody tr:hover {
        background: #fafafa;
    }

    .code-chip {
        background: rgba(0, 91, 172, .08);
        color: #005BAC;
        padding: 8px 12px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
    }

    .btn-edit {
        background: rgba(0, 91, 172, .08);
        color: #005BAC;
        border: none;
        border-radius: 10px;
        font-weight: 700;
    }

    .btn-delete {
        background: rgba(239, 68, 68, .08);
        color: #ef4444;
        border: none;
        border-radius: 10px;
        font-weight: 700;
    }

    .action-wrap {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    @media(max-width:768px) {

        .hero-box,
        .mini-card,
        .panel-box {
            padding: 16px;
            border-radius: 18px;
        }

        .hero-title {
            font-size: 22px;
        }

        .btn-main {
            width: 100%;
        }

        .action-wrap {
            flex-direction: column;
        }

        .btn-edit,
        .btn-delete {
            width: 100%;
        }
    }
</style>

<script>
    const searchInput = document.getElementById("searchInput");
    const filterJenis = document.getElementById("filterJenis");
    const filterStok = document.getElementById("filterStok");
    const rows = document.querySelectorAll("#materialTable tbody tr");
    const resultCount = document.getElementById("resultCount");

    function getStok(row) {
        return parseInt(row.children[4].innerText.trim()) || 0;
    }

    function filterTable() {

        let keyword = searchInput.value.toLowerCase();
        let kategori = filterJenis.value.toLowerCase();
        let stokMode = filterStok.value.toLowerCase();

        let visible = 0;

        rows.forEach(row => {

            let text = row.innerText.toLowerCase();
            let stok = getStok(row);

            let matchKeyword = keyword == "" || text.includes(keyword);
            let matchJenis = kategori == "" || text.includes(kategori);

            let matchStok = true;

            if (stokMode == "aman") matchStok = stok > 10;
            if (stokMode == "kritis") matchStok = stok > 0 && stok <= 10;
            if (stokMode == "habis") matchStok = stok == 0;

            if (matchKeyword && matchJenis && matchStok) {
                row.style.display = "";
                visible++;
            } else {
                row.style.display = "none";
            }

        });

        resultCount.innerText = visible;
    }

    searchInput.addEventListener("keyup", filterTable);
    filterJenis.addEventListener("change", filterTable);
    filterStok.addEventListener("change", filterTable);

    function resetSearch() {
        searchInput.value = "";
        filterJenis.value = "";
        filterStok.value = "";
        filterTable();
    }

    filterTable();
</script>

<?= $this->endSection() ?>