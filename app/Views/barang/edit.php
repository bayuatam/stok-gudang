<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- HERO -->
    <div class="hero-box mb-4">

        <div>
            <div class="hero-mini">PT WIJAYA KARYA BETON TBK</div>

            <h2 class="hero-title mb-1">
                Edit Barang Gudang
            </h2>

            <p class="hero-subtitle mb-0">
                Perbarui data persediaan gudang perusahaan
            </p>
        </div>

        <div class="hero-icon">
            ✏️
        </div>

    </div>

    <!-- PANEL -->
    <div class="panel-box">

        <div class="info-box mb-4">
            Sedang mengedit :
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
                    <label>Kode Barang</label>
                    <input type="text"
                        name="kode_sumber_daya"
                        value="<?= $barang['kode_sumber_daya'] ?>"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6">
                    <label>Nama Barang</label>
                    <input type="text"
                        name="nama_material"
                        value="<?= $barang['nama_material'] ?>"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6">
                    <label>Kategori</label>
                    <select name="kategori" id="kategori" class="form-select" onchange="setJenis()" required>
                        <option value="Material" <?= $barang['kategori'] == 'Material' ? 'selected' : '' ?>>Material</option>
                        <option value="Suku Cadang" <?= $barang['kategori'] == 'Suku Cadang' ? 'selected' : '' ?>>Suku Cadang</option>
                        <option value="BBM" <?= $barang['kategori'] == 'BBM' ? 'selected' : '' ?>>BBM</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Jenis Barang</label>
                    <select name="jenis_material" id="jenis_material" class="form-select"></select>
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
                    <select name="satuan" class="form-select">
                        <?php
                        $satuan = ['Kg', 'Pcs', 'Liter', 'M3', 'Zak', 'Roll', 'Drum', 'Unit'];
                        foreach ($satuan as $s):
                        ?>
                            <option value="<?= $s ?>" <?= $barang['satuan'] == $s ? 'selected' : '' ?>>
                                <?= $s ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
                        id="minimum_stok"
                        value="<?= $barang['minimum_stok'] ?>"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-6">
                    <label>Lokasi Gudang</label>
                    <select name="lokasi_gudang" class="form-select">
                        <?php
                        $gudang = ['Gudang Utama', 'Gudang Teknik', 'Gudang Kimia', 'Gudang BBM'];
                        foreach ($gudang as $g):
                        ?>
                            <option value="<?= $g ?>" <?= $barang['lokasi_gudang'] == $g ? 'selected' : '' ?>>
                                <?= $g ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Status Barang</label>
                    <select name="status_barang" class="form-select">
                        <option value="Aktif" <?= $barang['status_barang'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Nonaktif" <?= $barang['status_barang'] == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="btn-wrap mt-5">

                <button type="submit" class="btn btn-main">
                    Update Barang
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
        color: #fff;
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
        width: 72px;
        height: 72px;
        border-radius: 22px;
        background: rgba(255, 255, 255, .14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .panel-box {
        background: #fff;
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
        box-shadow: 0 0 0 4px rgba(0, 91, 172, .08);
        border-color: #005BAC;
    }

    .btn-wrap {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-main {
        background: linear-gradient(135deg, #f5a623, #ffbf47);
        border: none;
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

    @media(max-width:768px) {

        .hero-box,
        .panel-box {
            padding: 18px;
            border-radius: 18px;
        }

        .hero-title {
            font-size: 22px;
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
        }
    }
</style>

<script>
    let currentJenis = "<?= $barang['jenis_material'] ?>";

    function setJenis() {

        let kategori = document.getElementById("kategori").value;
        let jenis = document.getElementById("jenis_material");
        let min = document.getElementById("minimum_stok");

        jenis.innerHTML = "";

        let data = [];

        if (kategori == "Material") {
            data = ['Produksi', 'Kimia', 'Baja', 'Bangunan'];
            if (min.value == 0 || min.value == '') min.value = 10;
        }

        if (kategori == "Suku Cadang") {
            data = ['Mesin', 'Teknik', 'Elektrikal', 'Tools'];
            if (min.value == 0 || min.value == '') min.value = 2;
        }

        if (kategori == "BBM") {
            data = ['Solar', 'Oli', 'Gas'];
            if (min.value == 0 || min.value == '') min.value = 50;
        }

        data.forEach(function(x) {
            let selected = (x == currentJenis) ? 'selected' : '';
            jenis.innerHTML += `<option value="${x}" ${selected}>${x}</option>`;
        });

    }

    setJenis();
</script>

<?= $this->endSection() ?>