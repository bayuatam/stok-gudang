<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'WIKA BETON SYSTEM' ?></title>

    <link rel="icon" href="<?= base_url('favicon.ico') ?>?v=300">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        :root {
            --navy: #071a46;
            --navy2: #0e2f75;
            --gold: #f7c948;
            --gold2: #ffd86b;
            --soft: #f5f7fb;
            --line: #e8edf5;
            --text: #0f172a;
            --muted: #94a3b8;
            --white: #ffffff;
        }

        body {
            background: var(--soft);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ======================
SIDEBAR DESKTOP
====================== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 270px;
            height: 100vh;
            background: linear-gradient(180deg, var(--navy), #021133);
            padding: 18px;
            z-index: 1000;
            overflow-y: auto;
            transition: .3s ease;
            box-shadow: 10px 0 30px rgba(0, 0, 0, .08);
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .12);
            border-radius: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 8px 8px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            margin-bottom: 16px;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
            color: #111;
            box-shadow: 0 12px 24px rgba(247, 201, 72, .25);
        }

        .brand-text h5 {
            margin: 0;
            font-size: 24px;
            line-height: 1.05;
            font-weight: 900;
            color: #fff;
        }

        .brand-text small {
            color: #bfd0f5;
            font-size: 13px;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            padding: 0 10px;
            margin: 18px 0 10px;
            color: #7f95c6;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 18px;
            margin-bottom: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            color: #dbeafe;
            transition: .25s ease;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, .06);
            transform: translateX(5px);
        }

        .menu a.active {
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            color: #111;
            box-shadow: 0 12px 24px rgba(247, 201, 72, .18);
        }

        .menu-icon {
            width: 22px;
            text-align: center;
            font-size: 18px;
        }

        .logout-box {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        /* ======================
MAIN
====================== */
        .main {
            margin-left: 270px;
            min-height: 100vh;
            transition: .3s ease;
        }

        /* ======================
TOPBAR
====================== */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(14px);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--line);
        }

        .desktop-title h4 {
            margin: 0;
            font-size: 26px;
            font-weight: 900;
            line-height: 1.1;
        }

        .desktop-title small {
            font-size: 13px;
            color: var(--muted);
        }

        .mobile-title {
            display: none;
        }

        .profile-btn {
            width: 46px;
            height: 46px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #eff6ff, #ffffff);
            font-size: 20px;
            cursor: pointer;
            font-weight: 700;
            border: 1px solid #dbeafe;
        }

        /* ======================
CONTENT
====================== */
        .content {
            padding: 26px;
        }

        .footer {
            padding: 18px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
        }

        /* ======================
OVERLAY
====================== */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 999;
        }

        /* ======================
BOTTOM NAV
====================== */
        .bottom-nav {
            display: none;
        }

        /* ======================
MOBILE
====================== */
        @media(max-width:991px) {

            .sidebar {
                left: -320px;
                width: 290px;
            }

            .sidebar.show {
                left: 0;
            }

            .overlay.show {
                display: block;
            }

            .main {
                margin-left: 0;
                padding-bottom: 88px;
            }

            .desktop-title {
                display: none;
            }

            .mobile-title {
                display: block;
            }

            .mobile-title .hello {
                font-size: 17px;
                font-weight: 900;
                line-height: 1.1;
            }

            .mobile-title small {
                font-size: 12px;
                color: #94a3b8;
            }

            .topbar {
                padding: 14px 16px;
            }

            .profile-btn {
                width: 42px;
                height: 42px;
                font-size: 18px;
                border-radius: 14px;
            }

            .content {
                padding: 14px;
            }

            .footer {
                padding: 14px;
                font-size: 12px;
            }

            .bottom-nav {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                position: fixed;
                left: 12px;
                right: 12px;
                bottom: 12px;
                padding: 10px 6px;
                background: rgba(255, 255, 255, .86);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
                border-radius: 22px;
                box-shadow: 0 20px 35px rgba(0, 0, 0, .10);
                z-index: 998;
            }

            .bottom-nav a {
                text-decoration: none;
                text-align: center;
                font-size: 11px;
                font-weight: 800;
                color: #64748b;
                padding: 6px 0;
            }

            .bottom-nav a span {
                display: block;
                font-size: 18px;
                margin-bottom: 4px;
            }

            .bottom-nav a.active {
                color: #0b2d74;
                transform: translateY(-2px);
            }

            .bottom-nav a.active span {
                filter: drop-shadow(0 6px 10px rgba(11, 45, 116, .25));
            }
        }
    </style>
</head>

<body>

    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <div class="brand">
            <div class="brand-logo">W</div>
            <div class="brand-text">
                <h5>WIKA<br>BETON</h5>
                <small>Material System</small>
            </div>
        </div>

        <div class="menu">

            <div class="menu-title">MAIN MENU</div>

            <a href="<?= base_url('dashboard') ?>" class="<?= uri_string() == 'dashboard' ? 'active' : '' ?>">
                <span class="menu-icon">🏠</span>
                <span>Dashboard</span>
            </a>

            <a href="<?= base_url('barang') ?>" class="<?= uri_string() == 'barang' ? 'active' : '' ?>">
                <span class="menu-icon">📦</span>
                <span>Master Material</span>
            </a>

            <a href="<?= base_url('barang-masuk') ?>" class="<?= uri_string() == 'barang-masuk' ? 'active' : '' ?>">
                <span class="menu-icon">📥</span>
                <span>Barang Masuk</span>
            </a>

            <a href="<?= base_url('barang-keluar') ?>" class="<?= uri_string() == 'barang-keluar' ? 'active' : '' ?>">
                <span class="menu-icon">📤</span>
                <span>Barang Keluar</span>
            </a>

            <a href="<?= base_url('histori') ?>" class="<?= uri_string() == 'histori' ? 'active' : '' ?>">
                <span class="menu-icon">📑</span>
                <span>Histori</span>
            </a>

            <div class="logout-box">

                <a href="<?= base_url('logout') ?>">
                    <span class="menu-icon">🚪</span>
                    <span>Logout</span>
                </a>

            </div>

        </div>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">

            <div class="desktop-title">
                <h4>Material Monitoring Dashboard</h4>
                <small><?= date('l, d F Y') ?></small>
            </div>

            <div class="mobile-title">
                <div class="hello">👋 Halo, <?= session()->get('nama') ?? 'Manager' ?></div>
                <small><?= date('d M Y') ?></small>
            </div>

            <button class="profile-btn" onclick="openSidebar()">👤</button>

        </div>

        <!-- CONTENT -->
        <div class="content">
            <?= $this->renderSection('content') ?>
        </div>

        <div class="footer">
            © <?= date('Y') ?> PT Wijaya Karya Beton Tbk • Internal Enterprise System
        </div>

    </div>

    <!-- MOBILE BOTTOM NAV -->
    <div class="bottom-nav">

        <a href="<?= base_url('dashboard') ?>" class="<?= uri_string() == 'dashboard' ? 'active' : '' ?>">
            <span>🏠</span>Home
        </a>

        <a href="<?= base_url('barang') ?>" class="<?= uri_string() == 'barang' ? 'active' : '' ?>">
            <span>📦</span>Data
        </a>

        <a href="<?= base_url('barang-masuk') ?>" class="<?= uri_string() == 'barang-masuk' ? 'active' : '' ?>">
            <span>📥</span>Masuk
        </a>

        <a href="<?= base_url('barang-keluar') ?>" class="<?= uri_string() == 'barang-keluar' ? 'active' : '' ?>">
            <span>📤</span>Keluar
        </a>

        <a href="<?= base_url('histori') ?>" class="<?= uri_string() == 'histori' ? 'active' : '' ?>">
            <span>📄</span>Histori
        </a>

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