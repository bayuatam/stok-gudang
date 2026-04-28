<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- HERO HEADER -->
    <div class="hero-box mb-4">

        <div>
            <div class="hero-mini">PT WIJAYA KARYA BETON TBK</div>

            <h2 class="hero-title mb-1">
                Tambah Material
            </h2>

            <p class="hero-subtitle mb-0">
                Input data material baru ke sistem gudang perusahaan
            </p>
        </div>

        <div class="hero-icon">
            📦
        </div>

    </div>

    <!-- FORM PANEL -->
    <div class="panel-box">

        <div class="section-head mb-4">
            <div>
                <h5>Form Material Baru</h5>
                <small>Lengkapi data dengan benar sebelum menyimpan</small>
            </div>
        </div>

        <form method="post" action="<?= base_url('barang/simpan') ?>">

            <div class="row g-4">

                <div class="col-md-6">
                    <label>Kode Sumber Daya</label>
                    <input type="text" name="kode_sumber_daya" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Nama Material</label>
                    <input type="text" name="nama_material" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Jenis Material</label>
                    <input type="text" name="jenis_material" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Kategori</label>
                    <input type="text" name="kategori" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Supplier</label>
                    <input type="text" name="supplier" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>No Part</label>
                    <input type="text" name="no_part" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Satuan</label>
                    <input type="text" name="satuan" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Minimum Stok</label>
                    <input type="number" name="minimum_stok" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Lokasi Gudang</label>
                    <input type="text" name="lokasi_gudang" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Status Barang</label>
                    <select name="status_barang" class="form-select">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="mt-5 d-flex gap-2 flex-wrap">

                <button class="btn btn-main">
                    Simpan Material
                </button>

                <a href="<?= base_url('barang') ?>" class="btn btn-back">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

<style>
    .hero-box {
        background: linear-gradient(135deg, #003366, #005BAC);
        color: white;
        border-radius: 24px;
        padding: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: 0 18px 35px rgba(0, 51, 102, .18);
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
        width: 70px;
        height: 70px;
        border-radius: 20px;
        background: rgba(255, 255, 255, .14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        backdrop-filter: blur(8px);
    }

    .panel-box {
        background: white;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
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
    }

    .form-control,
    .form-select {
        height: 54px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 4px rgba(0, 91, 172, .08);
        border-color: #005BAC;
    }

    .btn-main {
        background: linear-gradient(135deg, #f5a623, #ffbf47);
        border: none;
        color: #111827;
        font-weight: 800;
        border-radius: 14px;
        padding: 12px 24px;
    }

    .btn-back {
        background: #e5e7eb;
        border: none;
        font-weight: 700;
        border-radius: 14px;
        padding: 12px 24px;
    }

    @media(max-width:768px) {

        .hero-box,
        .panel-box {
            padding: 20px;
        }

        .hero-title {
            font-size: 24px;
        }

        .hero-icon {
            width: 58px;
            height: 58px;
            font-size: 26px;
        }
    }
</style>

<?= $this->endSection() ?>