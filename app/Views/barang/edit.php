<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- HERO -->
    <div class="hero-box mb-4">

        <div>
            <div class="hero-mini">PT WIJAYA KARYA BETON TBK</div>

            <h2 class="hero-title mb-1">
                Edit Material
            </h2>

            <p class="hero-subtitle mb-0">
                Perbarui data material gudang perusahaan
            </p>
        </div>

        <div class="hero-icon">
            ✏️
        </div>

    </div>

    <!-- PANEL -->
    <div class="panel-box">

        <div class="info-box mb-4">
            Sedang mengedit:
            <strong><?= $barang['nama_material'] ?></strong>
        </div>

        <div class="section-head mb-4">
            <div>
                <h5>Form Perubahan Data</h5>
                <small>Pastikan data yang diperbarui sudah benar</small>
            </div>
        </div>

        <form method="post" action="<?= base_url('barang/update/' . $barang['id']) ?>">

            <div class="row g-4">

                <div class="col-md-6">
                    <label>Kode Sumber Daya</label>
                    <input type="text"
                        name="kode_sumber_daya"
                        value="<?= $barang['kode_sumber_daya'] ?>"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6">
                    <label>Nama Material</label>
                    <input type="text"
                        name="nama_material"
                        value="<?= $barang['nama_material'] ?>"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6">
                    <label>Jenis Material</label>
                    <input type="text"
                        name="jenis_material"
                        value="<?= $barang['jenis_material'] ?>"
                        class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Kategori</label>
                    <input type="text"
                        name="kategori"
                        value="<?= $barang['kategori'] ?>"
                        class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Supplier</label>
                    <input type="text"
                        name="supplier"
                        value="<?= $barang['supplier'] ?>"
                        class="form-control">
                </div>

                <div class="col-md-6">
                    <label>No Part</label>
                    <input type="text"
                        name="no_part"
                        value="<?= $barang['no_part'] ?>"
                        class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Satuan</label>
                    <input type="text"
                        name="satuan"
                        value="<?= $barang['satuan'] ?>"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-4">
                    <label>Stok</label>
                    <input type="number"
                        name="stok"
                        value="<?= $barang['stok'] ?>"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-4">
                    <label>Minimum Stok</label>
                    <input type="number"
                        name="minimum_stok"
                        value="<?= $barang['minimum_stok'] ?>"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6">
                    <label>Lokasi Gudang</label>
                    <input type="text"
                        name="lokasi_gudang"
                        value="<?= $barang['lokasi_gudang'] ?>"
                        class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Status Barang</label>
                    <select name="status_barang" class="form-select">

                        <option value="Aktif"
                            <?= $barang['status_barang'] == 'Aktif' ? 'selected' : '' ?>>
                            Aktif
                        </option>

                        <option value="Nonaktif"
                            <?= $barang['status_barang'] == 'Nonaktif' ? 'selected' : '' ?>>
                            Nonaktif
                        </option>

                    </select>
                </div>

            </div>

            <div class="mt-5 d-flex gap-2 flex-wrap">

                <button type="submit" class="btn btn-main">
                    Update Material
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
        font-size: 30px;
    }

    .panel-box {
        background: white;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
    }

    .info-box {
        background: rgba(0, 91, 172, .08);
        color: #003366;
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
            font-size: 24px;
        }
    }
</style>

<?= $this->endSection() ?>