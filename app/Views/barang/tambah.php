<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- HERO -->
    <div class="hero-box mb-4">

        <div>
            <div class="hero-mini">PT WIJAYA KARYA BETON TBK</div>

            <h2 class="hero-title mb-1">
                Tambah Barang Gudang
            </h2>

            <p class="hero-subtitle mb-0">
                Input data persediaan baru ke sistem gudang perusahaan
            </p>
        </div>

        <div class="hero-icon">
            ðŸ“¦
        </div>

    </div>

    <!-- FORM -->
    <div class="panel-box">

        <div class="section-head mb-4">
            <div>
                <h5>Form Barang Baru</h5>
                <small>Lengkapi data dengan benar sebelum menyimpan</small>
            </div>
        </div>

        <form method="post" action="<?= base_url('barang/simpan') ?>">

            <div class="row g-4">

                <div class="col-md-6">
                    <label>Kode Barang</label>
                    <input type="text" name="kode_sumber_daya" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Nama Barang</label>
                    <input type="text" name="nama_material" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Kategori</label>
                    <select name="kategori" id="kategori" class="form-select" required onchange="setJenis()">
                        <option value="">Pilih Kategori</option>
                        <option value="Material">Material</option>
                        <option value="Suku Cadang">Suku Cadang</option>
                        <option value="BBM">BBM</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Jenis Barang</label>
                    <select name="jenis_material" id="jenis_material" class="form-select">
                        <option value="">Pilih Jenis</option>
                    </select>
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
                    <select name="satuan" class="form-select" required>
                        <option value="">Pilih Satuan</option>
                        <option>Kg</option>
                        <option>Pcs</option>
                        <option>Liter</option>
                        <option>M3</option>
                        <option>Zak</option>
                        <option>Roll</option>
                        <option>Drum</option>
                        <option>Unit</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Minimum Stok</label>
                    <input type="number" name="minimum_stok" id="minimum_stok" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Lokasi Gudang</label>
                    <select name="lokasi_gudang" class="form-select">
                        <option value="">Pilih Gudang</option>
                        <option>Gudang Utama</option>
                        <option>Gudang Teknik</option>
                        <option>Gudang Kimia</option>
                        <option>Gudang BBM</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Status Barang</label>
                    <select name="status_barang" class="form-select">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="btn-wrap mt-5">

                <button class="btn btn-main">
                    Simpan Barang
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
        font-size: 34px;
        backdrop-filter: blur(8px);
    }

    .panel-box {
        background: #fff;
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

    @media(max-width:768px) {

        .hero-box,
        .panel-box {
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
    }
</style>

<script>
    function setJenis() {

        let kategori = document.getElementById("kategori").value;
        let jenis = document.getElementById("jenis_material");
        let min = document.getElementById("minimum_stok");

        jenis.innerHTML = '<option value="">Pilih Jenis</option>';

        if (kategori == "Material") {

            let data = ['Produksi', 'Kimia', 'Baja', 'Bangunan'];

            data.forEach(function(x) {
                jenis.innerHTML += `<option value="${x}">${x}</option>`;
            });

            min.value = 10;

        }

        if (kategori == "Suku Cadang") {

            let data = ['Mesin', 'Teknik', 'Elektrikal', 'Tools'];

            data.forEach(function(x) {
                jenis.innerHTML += `<option value="${x}">${x}</option>`;
            });

            min.value = 2;

        }

        if (kategori == "BBM") {

            let data = ['Solar', 'Oli', 'Gas'];

            data.forEach(function(x) {
                jenis.innerHTML += `<option value="${x}">${x}</option>`;
            });

            min.value = 50;

        }

    }
</script>

<?= $this->endSection() ?>