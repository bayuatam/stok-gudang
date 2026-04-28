<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- HERO HEADER -->
    <div class="hero-dashboard mb-4">

        <div>
            <div class="mini-label">PT WIJAYA KARYA BETON TBK</div>

            <h2 class="hero-title mb-2">
                Dashboard Monitoring Material
            </h2>

            <p class="hero-subtitle mb-0">
                Pantau stok, transaksi, dan kondisi gudang secara realtime
            </p>
        </div>

        <div class="hero-right">
            <div class="date-pill">
                <?= date('d F Y') ?>
            </div>
        </div>

    </div>

    <!-- KPI -->
    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon bg-blue">📦</div>
                <div class="kpi-content">
                    <small>Total Material</small>
                    <h3><?= $total_barang ?></h3>
                    <span>Seluruh item aktif</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon bg-red">⚠️</div>
                <div class="kpi-content">
                    <small>Stok Kritis</small>
                    <h3><?= $stok_menipis ?></h3>
                    <span>Perlu reorder segera</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon bg-green">📥</div>
                <div class="kpi-content">
                    <small>Masuk Hari Ini</small>
                    <h3><?= $barang_masuk_hari_ini ?? 0 ?></h3>
                    <span>Penerimaan material</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="kpi-card">
                <div class="kpi-icon bg-orange">📤</div>
                <div class="kpi-content">
                    <small>Keluar Hari Ini</small>
                    <h3><?= $barang_keluar_hari_ini ?? 0 ?></h3>
                    <span>Pemakaian material</span>
                </div>
            </div>
        </div>

    </div>

    <!-- CHART + STATUS -->
    <div class="row g-4 mb-4">

        <div class="col-xl-8">

            <div class="panel-card chart-panel">

                <div class="section-head mb-3">
                    <div>
                        <h5>Grafik Pergerakan Material</h5>
                        <small>7 hari terakhir</small>
                    </div>

                    <span class="status-badge">Live</span>
                </div>

                <!-- FIX ERROR GRAFIK -->
                <div class="chart-wrap">
                    <canvas id="grafikTransaksi"></canvas>
                </div>

            </div>

        </div>

        <div class="col-xl-4">

            <div class="panel-card h-100">

                <div class="section-head mb-3">
                    <div>
                        <h5>Ringkasan Sistem</h5>
                        <small>Status operasional</small>
                    </div>
                </div>

                <div class="info-line">
                    <span>Gudang Aktif</span>
                    <strong>3 Lokasi</strong>
                </div>

                <div class="info-line">
                    <span>User Login</span>
                    <strong><?= session()->get('nama') ?></strong>
                </div>

                <div class="info-line">
                    <span>Telegram Bot</span>
                    <strong class="text-success">Aktif</strong>
                </div>

                <div class="info-line">
                    <span>Server</span>
                    <strong class="text-success">Normal</strong>
                </div>

                <div class="info-line border-0">
                    <span>Backup</span>
                    <strong class="text-primary">Terjadwal</strong>
                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="panel-card">

        <div class="section-head mb-3">
            <div>
                <h5>Material Stok Kritis</h5>
                <small>Prioritas pengadaan ulang</small>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table table-enterprise align-middle">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode SD</th>
                        <th>Material</th>
                        <th>Stok</th>
                        <th>Minimum</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($barang_kritis) > 0): ?>
                        <?php $no = 1;
                        foreach ($barang_kritis as $b): ?>

                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <span class="code-chip">
                                        <?= $b['kode_sumber_daya'] ?>
                                    </span>
                                </td>

                                <td class="fw-semibold">
                                    <?= $b['nama_material'] ?>
                                </td>

                                <td><?= $b['stok'] ?></td>
                                <td><?= $b['minimum_stok'] ?></td>

                                <td>
                                    <span class="badge bg-danger">
                                        Kritis
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Tidak ada material kritis
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<style>
    .hero-dashboard {
        background: linear-gradient(135deg, #003366, #005BAC);
        color: white;
        border-radius: 24px;
        padding: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: 0 18px 35px rgba(0, 51, 102, .18);
    }

    .mini-label {
        font-size: 12px;
        letter-spacing: 1px;
        opacity: .85;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .hero-title {
        font-size: 32px;
        font-weight: 800;
    }

    .hero-subtitle {
        opacity: .9;
    }

    .date-pill {
        background: rgba(255, 255, 255, .14);
        padding: 12px 18px;
        border-radius: 14px;
        font-weight: 700;
    }

    .kpi-card,
    .panel-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
    }

    .kpi-card {
        display: flex;
        align-items: center;
        gap: 18px;
        height: 100%;
    }

    .kpi-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .bg-blue {
        background: rgba(0, 91, 172, .10);
    }

    .bg-red {
        background: rgba(239, 68, 68, .10);
    }

    .bg-green {
        background: rgba(16, 185, 129, .10);
    }

    .bg-orange {
        background: rgba(245, 166, 35, .14);
    }

    .kpi-content small {
        color: #64748b;
        font-weight: 700;
    }

    .kpi-content h3 {
        font-size: 32px;
        margin: 4px 0;
        font-weight: 800;
    }

    .kpi-content span {
        font-size: 13px;
        color: #94a3b8;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .section-head h5 {
        margin: 0;
        font-weight: 800;
    }

    .section-head small {
        color: #94a3b8;
    }

    .status-badge {
        background: rgba(16, 185, 129, .10);
        color: #10b981;
        padding: 8px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 800;
    }

    .info-line {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid #eef2f7;
    }

    .info-line span {
        color: #64748b;
    }

    .table-enterprise thead th {
        background: #f8fafc;
        border: none;
        color: #475569;
        font-size: 14px;
    }

    .table-enterprise td {
        border-color: #eef2f7;
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

    /* FIX CHART */
    .chart-panel {
        min-height: 420px;
    }

    .chart-wrap {
        position: relative;
        height: 320px;
        width: 100%;
    }

    .chart-wrap canvas {
        width: 100% !important;
        height: 100% !important;
    }

    @media(max-width:768px) {

        .hero-dashboard,
        .kpi-card,
        .panel-card {
            padding: 20px;
        }

        .hero-title {
            font-size: 24px;
        }

        .chart-panel {
            min-height: auto;
        }

        .chart-wrap {
            height: 260px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('grafikTransaksi');

    new Chart(ctx, {
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
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,.08)',
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
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,.08)',
                    fill: true,
                    tension: .4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>