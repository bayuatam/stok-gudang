<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- HERO -->
    <div class="hero-box mb-4">

        <div>
            <div class="hero-mini">PT WIJAYA KARYA BETON TBK</div>
            <h2 class="hero-title mb-1">Histori Transaksi</h2>
            <p class="hero-subtitle mb-0">
                Riwayat aktivitas stok material perusahaan
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a href="<?= base_url('histori/pdf') ?>" class="btn btn-pdf">
                📄 PDF
            </a>

            <a href="<?= base_url('histori/excel') ?>" class="btn btn-excel">
                📊 Excel
            </a>

        </div>

    </div>

    <!-- KPI -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="mini-card">
                <small>Total Aktivitas</small>
                <h3><?= $total_transaksi ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card">
                <small>Barang Masuk</small>
                <h3 class="text-success"><?= $total_masuk ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card">
                <small>Barang Keluar</small>
                <h3 class="text-danger"><?= $total_keluar ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="mini-card">
                <small>Status</small>
                <h3 class="text-primary">Live</h3>
            </div>
        </div>

    </div>

    <!-- FILTER -->
    <div class="panel-box mb-4">

        <div class="row g-3">

            <div class="col-md-8">
                <input type="text"
                    id="searchInput"
                    class="form-control search-box"
                    placeholder="🔎 Cari material / kode / user...">
            </div>

            <div class="col-md-2">
                <select class="form-select" id="filterJenis">
                    <option value="">Semua</option>
                    <option value="masuk">Barang Masuk</option>
                    <option value="keluar">Barang Keluar</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-reset w-100" onclick="resetFilter()">
                    Reset
                </button>
            </div>

        </div>

        <div class="result-box mt-3">
            Menampilkan <strong id="resultCount"><?= count($transaksi) ?></strong> data transaksi
        </div>

    </div>

    <!-- TABLE -->
    <div class="panel-box">

        <div class="table-responsive">

            <table class="table table-enterprise align-middle" id="historyTable">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Material</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>User</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $no = 1;
                    foreach ($transaksi as $t): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>
                                <?= date('d-m-Y H:i', strtotime($t['tanggal'])) ?>
                            </td>

                            <td>
                                <strong><?= ucwords(strtolower($t['nama_material'])) ?></strong><br>
                                <small class="text-muted">
                                    <?= $t['kode_sumber_daya'] ?>
                                </small>
                            </td>

                            <td class="jenis-cell">

                                <?php if ($t['jenis'] == 'masuk'): ?>
                                    <span class="badge bg-success">
                                        📥 Masuk
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        📤 Keluar
                                    </span>
                                <?php endif; ?>

                            </td>

                            <td>
                                <strong><?= $t['jumlah'] ?></strong>
                            </td>

                            <td>
                                <?= $t['nama'] ?? 'Admin' ?>
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
        color: white;
        border-radius: 24px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: 0 20px 35px rgba(0, 51, 102, .18);
    }

    .hero-mini {
        font-size: 12px;
        font-weight: 700;
        opacity: .8;
        letter-spacing: 1px;
    }

    .hero-title {
        font-size: 30px;
        font-weight: 800;
    }

    .hero-subtitle {
        opacity: .9
    }

    .mini-card,
    .panel-box {
        background: white;
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, .05);
    }

    .mini-card small {
        color: #64748b;
        font-weight: 700;
    }

    .mini-card h3 {
        margin-top: 8px;
        font-size: 30px;
        font-weight: 800;
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

    .btn-pdf {
        background: #ef4444;
        color: white;
        font-weight: 700;
        border-radius: 14px;
    }

    .btn-excel {
        background: #16a34a;
        color: white;
        font-weight: 700;
        border-radius: 14px;
    }

    .btn-reset {
        background: #e5e7eb;
        font-weight: 700;
        border-radius: 14px;
        height: 52px;
    }

    .table-enterprise thead th {
        background: #f8fafc;
        border: none;
        color: #64748b;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .table-enterprise td {
        border-color: #eef2f7;
    }

    .table-enterprise tbody tr:hover {
        background: #fafafa;
    }

    @media(max-width:768px) {

        .hero-box,
        .mini-card,
        .panel-box {
            padding: 18px
        }

        .hero-title {
            font-size: 24px
        }
    }
</style>

<script>
    const searchInput = document.getElementById("searchInput");
    const filterJenis = document.getElementById("filterJenis");
    const rows = document.querySelectorAll("#historyTable tbody tr");
    const resultCount = document.getElementById("resultCount");

    function filterTable() {
        let keyword = searchInput.value.toLowerCase();
        let jenis = filterJenis.value.toLowerCase();
        let visible = 0;

        rows.forEach(row => {

            let text = row.innerText.toLowerCase();
            let jenisText = row.querySelector(".jenis-cell").innerText.toLowerCase();

            let matchKeyword = text.includes(keyword);
            let matchJenis = jenis === "" || jenisText.includes(jenis);

            if (matchKeyword && matchJenis) {
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

    function resetFilter() {
        searchInput.value = "";
        filterJenis.value = "";
        filterTable();
    }
</script>

<?= $this->endSection() ?>