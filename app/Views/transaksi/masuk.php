<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- HERO -->
    <div class="hero-box mb-4">

        <div>
            <div class="hero-mini">PT WIJAYA KARYA BETON TBK</div>

            <h2 class="hero-title mb-1">
                Barang Masuk
            </h2>

            <p class="hero-subtitle mb-0">
                Input penerimaan material dari supplier / produksi
            </p>
        </div>

        <div class="hero-icon">
            ðŸ“¥
        </div>

    </div>

    <!-- CONTENT -->
    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-8">

            <div class="panel-box h-100">

                <!-- BARU -->
                <div class="info-box mb-4">
                    Pastikan material diterima sesuai surat jalan dan jumlah fisik.
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success rounded-4">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger rounded-4">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="section-head mb-4">
                    <div>
                        <h5>Form Penerimaan Material</h5>
                        <small>Lengkapi data transaksi dengan benar</small>
                    </div>
                </div>

                <form method="post" action="<?= base_url('barang-masuk/simpan') ?>">

                    <!-- BARU -->
                    <div class="mb-3">
                        <label>Pilih Material</label>

                        <select name="barang_id" class="form-select" required>

                            <option value="">-- Pilih Material --</option>

                            <?php foreach ($barang as $b): ?>
                                <option value="<?= $b['id'] ?>">
                                    <?= $b['kode_sumber_daya'] ?> -
                                    <?= $b['nama_material'] ?> |
                                    <?= $b['lokasi_gudang'] ?> |
                                    Stok <?= $b['stok'] ?> <?= $b['satuan'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label>Jumlah Masuk</label>
                            <input type="number"
                                name="jumlah"
                                class="form-control"
                                placeholder="Masukkan jumlah"
                                required>
                        </div>

                        <!-- BARU -->
                        <div class="col-md-6">
                            <label>No Dokumen</label>
                            <input type="text"
                                name="dokumen"
                                class="form-control"
                                placeholder="SJ / PO / DO">
                        </div>

                        <!-- BARU -->
                        <div class="col-md-12">
                            <label>Keterangan</label>
                            <input type="text"
                                name="keterangan"
                                class="form-control"
                                placeholder="Contoh: Penerimaan supplier">
                        </div>

                    </div>

                    <div class="btn-wrap mt-5">

                        <button type="submit" class="btn btn-main">
                            Simpan Transaksi
                        </button>

                        <a href="<?= base_url('dashboard') ?>" class="btn btn-back">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </div>

        <!-- SIDE INFO -->
        <div class="col-lg-4">

            <!-- BARU -->
            <div class="mini-card mb-4">
                <small>Total Material</small>
                <h3><?= count($barang) ?></h3>
            </div>

            <!-- BARU -->
            <div class="mini-card mb-4">
                <small>Status Gudang</small>
                <h3 class="text-success">Aktif</h3>
            </div>

            <!-- BARU -->
            <div class="mini-card">
                <small>Catatan</small>
                <p class="mb-0 text-muted mt-2">
                    Setiap transaksi masuk akan menambah stok sistem otomatis.
                </p>
            </div>

        </div>

    </div>

</div>

<style>
    .hero-box {
        background: linear-gradient(135deg, #047857, #10b981);
        color: #fff;
        border-radius: 24px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: 0 18px 35px rgba(16, 185, 129, .18);
    }

    .hero-mini {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        opacity: .8;
        margin-bottom: 8px;
    }

    .hero-title {
        font-size: 30px;
        font-weight: 800;
    }

    .hero-subtitle {
        opacity: .92;
    }

    .hero-icon {
        width: 72px;
        height: 72px;
        border-radius: 22px;
        background: rgba(255, 255, 255, .14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        backdrop-filter: blur(8px);
    }

    .panel-box,
    .mini-card {
        background: #fff;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
    }

    .info-box {
        background: rgba(16, 185, 129, .08);
        color: #065f46;
        padding: 14px 18px;
        border-radius: 16px;
        font-weight: 700;
    }

    .section-head h5 {
        margin: 0;
        font-weight: 800;
    }

    .section-head small {
        color: #94a3b8;
    }

    label {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
        display: block;
    }

    .form-control,
    .form-select {
        height: 54px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        padding: 0 14px;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 4px rgba(16, 185, 129, .08);
        border-color: #10b981;
    }

    .btn-wrap {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-main {
        background: linear-gradient(135deg, #f5a623, #ffbf47);
        border: none;
        color: #111827;
        font-weight: 800;
        border-radius: 14px;
        padding: 13px 24px;
    }

    .btn-back {
        background: #e5e7eb;
        border: none;
        font-weight: 700;
        border-radius: 14px;
        padding: 13px 24px;
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

    @media(max-width:768px) {

        .hero-box,
        .panel-box,
        .mini-card {
            padding: 18px;
            border-radius: 18px;
        }

        .hero-title {
            font-size: 22px;
        }

        .hero-subtitle {
            font-size: 13px;
        }

        .hero-icon {
            width: 56px;
            height: 56px;
            font-size: 24px;
            border-radius: 16px;
        }

        .form-control,
        .form-select {
            height: 48px;
            font-size: 14px;
        }

        .btn-wrap {
            flex-direction: column;
        }

        .btn-main,
        .btn-back {
            width: 100%;
            padding: 12px;
            font-size: 14px;
        }

        label {
            font-size: 13px;
        }
    }
</style>

<?= $this->endSection() ?>