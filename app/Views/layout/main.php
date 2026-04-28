<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'PT WIKA Beton' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary: #003366;
            --secondary: #005BAC;
            --accent: #f5a623;
            --dark: #0f172a;
            --gray: #64748b;
        }

        body {
            margin: 0;
            background: #f8fafc;
            color: #111827;
        }

        /* MOBILE TOPBAR */
        .mobile-topbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 1050;
            background: white;
            padding: 14px 18px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .04);
            justify-content: space-between;
            align-items: center;
        }

        .mobile-brand {
            font-weight: 800;
            font-size: 18px;
        }

        .mobile-brand span {
            color: var(--accent);
        }

        .menu-btn {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 12px;
            background: #f1f5f9;
            font-size: 20px;
        }

        /* SIDEBAR */
        .sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #0b1120, #111827);
            padding: 24px 18px;
            color: white;
            overflow-y: auto;
            transition: .3s ease;
            z-index: 1040;
        }

        .brand-box {
            padding: 12px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .04);
            margin-bottom: 24px;
        }

        .brand {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.1;
        }

        .brand span {
            color: var(--accent);
        }

        .brand small {
            display: block;
            margin-top: 8px;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 500;
        }

        .user-box {
            background: rgba(255, 255, 255, .05);
            padding: 14px;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .user-box small {
            color: #94a3b8;
        }

        .user-box strong {
            display: block;
            margin-top: 4px;
        }

        .menu-title {
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 18px 10px 10px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #e2e8f0;
            padding: 13px 14px;
            border-radius: 14px;
            margin-bottom: 8px;
            font-weight: 600;
            transition: .25s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: linear-gradient(135deg, #f5a623, #ffbf47);
            color: #111827;
            transform: translateX(4px);
        }

        .logout-link {
            margin-top: 18px;
            background: rgba(239, 68, 68, .08);
            color: #fca5a5 !important;
        }

        /* OVERLAY */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 1030;
        }

        .overlay.show {
            display: block;
        }

        /* CONTENT */
        .content {
            margin-left: 275px;
            padding: 30px;
            min-height: 100vh;
        }

        .top-info {
            background: white;
            border-radius: 18px;
            padding: 14px 20px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, .04);
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .footer {
            margin-top: 35px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }

        /* MOBILE */
        @media(max-width:992px) {

            .mobile-topbar {
                display: flex;
            }

            .sidebar {
                left: -290px;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
                padding: 18px;
            }

        }
    </style>
</head>

<body>

    <!-- MOBILE -->
    <div class="mobile-topbar">
        <button class="menu-btn" onclick="openSidebar()">☰</button>

        <div class="mobile-brand">
            <span>WIKA</span> BETON
        </div>
    </div>

    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <div class="brand-box">
            <div class="brand">
                <span>WIKA</span> BETON
                <small>Monitoring Material System</small>
            </div>
        </div>

        <div class="user-box">
            <small>Login Sebagai</small>
            <strong><?= session()->get('nama') ?? 'Administrator' ?></strong>
        </div>

        <div class="menu-title">MAIN MENU</div>

        <a href="<?= base_url('dashboard') ?>" class="<?= uri_string() == 'dashboard' ? 'active' : '' ?>">
            🏠 Dashboard
        </a>

        <a href="<?= base_url('barang') ?>" class="<?= uri_string() == 'barang' ? 'active' : '' ?>">
            📦 Master Material
        </a>

        <a href="<?= base_url('barang-masuk') ?>" class="<?= uri_string() == 'barang-masuk' ? 'active' : '' ?>">
            📥 Barang Masuk
        </a>

        <a href="<?= base_url('barang-keluar') ?>" class="<?= uri_string() == 'barang-keluar' ? 'active' : '' ?>">
            📤 Barang Keluar
        </a>

        <a href="<?= base_url('histori') ?>" class="<?= uri_string() == 'histori' ? 'active' : '' ?>">
            📑 Histori
        </a>

        <div class="menu-title">ACCOUNT</div>

        <a href="<?= base_url('logout') ?>" class="logout-link">
            🚪 Logout
        </a>

    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="top-info">
            <div>
                <strong><?= date('l, d F Y') ?></strong>
            </div>

            <div>
                Sistem Gudang Material PT WIKA Beton
            </div>
        </div>

        <?= $this->renderSection('content') ?>

        <div class="footer">
            © <?= date('Y') ?> PT Wijaya Karya Beton Tbk • Internal System
        </div>

    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('show');
            document.getElementById('overlay').classList.add('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('overlay').classList.remove('show');
        }
    </script>

</body>

</html>