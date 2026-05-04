<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- HERO -->
    <div class="hero-dashboard mb-4">
        <div>
            <div class="mini-label">PT WIJAYA KARYA BETON TBK</div>
            <h2 class="hero-title mb-2">Dashboard Analytics Gudang</h2>
            <p class="hero-subtitle mb-0">
                Monitoring stok, transaksi, dan performa gudang secara realtime
            </p>
        </div>

        <div class="date-pill">
            <?= date('d F Y') ?>
        </div>
    </div>

    <!-- KPI -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-2">
            <div class="kpi-card">
                <div class="kpi-icon bg-blue">📦</div>
                <div>
                    <small>Total</small>
                    <h3><?= $total_barang ?></h3>
                    <span>Barang</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card">
                <div class="kpi-icon bg-sky">🏗️</div>
                <div>
                    <small>Material</small>
                    <h3><?= $total_material ?></h3>
                    <span>Aktif</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card">
                <div class="kpi-icon bg-orange">🔧</div>
                <div>
                    <small>Sparepart</small>
                    <h3><?= $total_sparepart ?></h3>
                    <span>Tersedia</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card">
                <div class="kpi-icon bg-green">⛽</div>
                <div>
                    <small>BBM</small>
                    <h3><?= $total_bbm ?></h3>
                    <span>Gudang</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card">
                <div class="kpi-icon bg-red">⚠️</div>
                <div>
                    <small>Kritis</small>
                    <h3><?= $stok_menipis ?></h3>
                    <span>Reorder</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-2">
            <div class="kpi-card">
                <div class="kpi-icon bg-purple">📊</div>
                <div>
                    <small>Hari Ini</small>
                    <h3><?= $transaksi_hari_ini ?></h3>
                    <span>Transaksi</span>
                </div>
            </div>
        </div>

    </div>

    <!-- CHART -->
    <div class="row g-4 mb-4">

        <div class="col-xl-8">
            <div class="panel-card">

                <div class="section-head mb-3">
                    <div>
                        <h5>Grafik Pergerakan</h5>
                        <small>7 hari terakhir</small>
                    </div>

                    <span class="status-badge">Live</span>
                </div>

                <div class="chart-wrap">
                    <canvas id="chartLine"></canvas>
                </div>

            </div>
        </div>

        <div class="col-xl-4">
            <div class="panel-card h-100">

                <div class="section-head mb-3">
                    <div>
                        <h5>Komposisi Kategori</h5>
                        <small>Master barang</small>
                    </div>
                </div>

                <div class="chart-wrap pie-wrap">
                    <canvas id="chartPie"></canvas>
                </div>

            </div>
        </div>

    </div>

    <!-- SECOND ROW -->
    <div class="row g-4">

        <!-- KRITIS -->
        <div class="col-xl-6">
            <div class="panel-card">

                <div class="section-head mb-3">
                    <div>
                        <h5>Barang Kritis</h5>
                        <small>Prioritas pembelian</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-enterprise">

                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Stok</th>
                                <th>Min</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($barang_kritis as $b): ?>
                                <tr>
                                    <td><?= $b['nama_material'] ?></td>
                                    <td><?= $b['stok'] ?></td>
                                    <td><?= $b['minimum_stok'] ?></td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        <!-- TOP KELUAR -->
        <div class="col-xl-6">
            <div class="panel-card">

                <div class="section-head mb-3">
                    <div>
                        <h5>Top Barang Keluar</h5>
                        <small>Paling sering digunakan</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-enterprise">

                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($top_keluar as $t): ?>
                                <tr>
                                    <td><?= $t['nama_material'] ?></td>
                                    <td><?= $t['total'] ?></td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

    <!-- AKTIVITAS -->
    <div class="panel-card mt-4">

        <div class="section-head mb-3">
            <div>
                <h5>Aktivitas Terakhir</h5>
                <small>5 transaksi terbaru</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-enterprise">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Jenis</th>
                        <th>Qty</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($aktivitas as $a): ?>
                        <tr>
                            <td><?= date('d-m H:i', strtotime($a['tanggal'])) ?></td>
                            <td><?= $a['nama_material'] ?></td>
                            <td>
                                <?php if ($a['jenis'] == 'masuk'): ?>
                                    <span class="badge bg-success">Masuk</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Keluar</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $a['jumlah'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    </div>

</div>

<style>
    .hero-dashboard {
        background: linear-gradient(135deg, #003366, #005BAC);
        color: #fff;
        border-radius: 24px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 18px 35px rgba(0, 51, 102, .18);
    }

    .hero-title {
        font-size: 32px;
        font-weight: 800;
    }

    .hero-subtitle {
        opacity: .9
    }

    .mini-label {
        font-size: 12px;
        font-weight: 700;
        opacity: .8;
        letter-spacing: 1px;
    }

    .date-pill {
        background: rgba(255, 255, 255, .15);
        padding: 12px 18px;
        border-radius: 14px;
        font-weight: 700;
    }

    .kpi-card,
    .panel-card {
        background: #fff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, .05);
        height: 100%;
    }

    .kpi-card {
        display: flex;
        gap: 14px;
        align-items: center;
    }

    .kpi-icon {
        width: 55px;
        height: 55px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .bg-blue {
        background: #dbeafe;
    }

    .bg-sky {
        background: #e0f2fe;
    }

    .bg-orange {
        background: #ffedd5;
    }

    .bg-green {
        background: #dcfce7;
    }

    .bg-red {
        background: #fee2e2;
    }

    .bg-purple {
        background: #ede9fe;
    }

    .kpi-card h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
    }

    .kpi-card small {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }

    .kpi-card span {
        font-size: 11px;
        color: #94a3b8;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .section-head h5 {
        margin: 0;
        font-weight: 800;
    }

    .section-head small {
        color: #94a3b8;
    }

    .status-badge {
        background: #dcfce7;
        color: #16a34a;
        padding: 8px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 800;
    }

    .chart-wrap {
        height: 320px;
        position: relative;
    }

    .pie-wrap {
        height: 300px;
    }

    .table-enterprise thead th {
        background: #f8fafc;
        border: none;
        font-size: 13px;
        color: #64748b;
    }

    .table-enterprise td {
        border-color: #eef2f7;
        font-size: 14px;
        white-space: nowrap;
    }

    @media(max-width:768px) {

        .hero-dashboard,
        .kpi-card,
        .panel-card {
            padding: 16px;
            border-radius: 18px;
        }

        .hero-title {
            font-size: 22px;
        }

        .chart-wrap {
            height: 240px;
        }

        .kpi-card h3 {
            font-size: 22px;
        }

        .table-enterprise td,
        .table-enterprise th {
            font-size: 12px;
        }

    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    new Chart(document.getElementById('chartLine'), {
        type: 'line',
        data: {
            labels: [
                <?php foreach ($grafik as $g) {
                    echo "'" . $g['tanggal'] . "',";
                } ?>
            ],
            datasets: [{
                    label: 'Masuk',
                    data: [
                        <?php foreach ($grafik as $g) {
                            echo $g['masuk'] . ",";
                        } ?>
                    ],
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,.08)',
                    fill: true,
                    tension: .4
                },
                {
                    label: 'Keluar',
                    data: [
                        <?php foreach ($grafik as $g) {
                            echo $g['keluar'] . ",";
                        } ?>
                    ],
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220,38,38,.08)',
                    fill: true,
                    tension: .4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    new Chart(document.getElementById('chartPie'), {
        type: 'doughnut',
        data: {
            labels: ['Material', 'Suku Cadang', 'BBM'],
            datasets: [{
                data: [
                    <?= $total_material ?>,
                    <?= $total_sparepart ?>,
                    <?= $total_bbm ?>
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?= $this->endSection() ?>