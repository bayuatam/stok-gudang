<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Wika Beton Mini App</title>

    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            /* iOS System Colors - Auto Dark Mode */
            --bg-color: var(--tg-theme-secondary-bg-color, #F2F2F7);
            /* Khas Background Setting iOS */
            --card-bg: var(--tg-theme-bg-color, #FFFFFF);
            --text-color: var(--tg-theme-text-color, #000000);
            --hint-color: var(--tg-theme-hint-color, #8E8E93);
            --primary-color: #007AFF;
            /* Apple iOS Blue */
            --nav-bg: rgba(255, 255, 255, 0.75);
            --nav-border: rgba(60, 60, 67, 0.15);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: var(--tg-theme-secondary-bg-color, #000000);
                --card-bg: var(--tg-theme-bg-color, #1C1C1E);
                --text-color: var(--tg-theme-text-color, #FFFFFF);
                --hint-color: var(--tg-theme-hint-color, #EBEBF5);
                --primary-color: #0A84FF;
                /* Apple iOS Dark Blue */
                --nav-bg: rgba(29, 29, 31, 0.85);
                --nav-border: rgba(84, 84, 88, 0.65);
            }
        }

        body {
            margin: 0;
            /* Memanggil Font Asli iPhone (San Francisco) */
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "SF Pro Display", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            -webkit-font-smoothing: antialiased;
            padding-bottom: 95px;
            overscroll-behavior-y: none;
            /* Mencegah layar tertarik kelewatan ala web */
        }

        /* HEADER - iOS Large Title */
        .header {
            padding: 30px 20px 10px 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 34px;
            font-weight: 700;
            letter-spacing: -0.02em;
            /* Spasi huruf rapat khas Apple */
        }

        .header p {
            margin: 4px 0 0 0;
            font-size: 15px;
            color: var(--hint-color);
            font-weight: 500;
        }

        /* CARDS - Radius 20px */
        .card {
            background-color: var(--card-bg);
            border-radius: 20px;
            padding: 18px 20px;
            margin: 16px 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        }

        .card-primary {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: #FFFFFF;
            box-shadow: 0 10px 20px rgba(0, 122, 255, 0.25);
        }

        /* GRID */
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 0 20px;
        }

        .stat-card {
            background-color: var(--card-bg);
            border-radius: 20px;
            padding: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-card span {
            font-size: 13px;
            color: var(--hint-color);
            font-weight: 500;
        }

        .stat-card b {
            font-size: 26px;
            margin-top: 6px;
            color: var(--text-color);
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        /* =========================================
           TAB BAR - NATIVE iOS STYLE
        ========================================= */
        .nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: var(--nav-bg);
            /* Blur super tebal khas iPhone */
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
            border-top: 0.5px solid var(--nav-border);
            z-index: 1000;
        }

        .nav a {
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--hint-color);
            font-size: 10px;
            font-weight: 500;
            gap: 3px;
            width: 60px;
            -webkit-tap-highlight-color: transparent;
        }

        /* Ikon Outline default */
        .nav a svg {
            width: 26px;
            height: 26px;
            stroke: currentColor;
            stroke-width: 1.5;
            fill: none;
            transition: 0.2s ease;
        }

        /* STATE AKTIF - Warna biru dan ikon jadi Filled (Padat) khas iOS */
        .nav a.active {
            color: var(--primary-color);
        }

        .nav a.active svg {
            fill: currentColor;
            stroke: none;
        }

        /* Pengecualian untuk menu yang hanya butuh garis tebal */
        .nav a.active svg.stroke-only {
            fill: none;
            stroke: currentColor;
            stroke-width: 2.2;
        }
    </style>
</head>

<body>

    <?= $this->renderSection('content') ?>

    <div class="nav">
        <a href="/miniapp" class="<?= (current_url() == site_url('miniapp')) ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            </svg>
            <span>Home</span>
        </a>

        <a href="/miniapp/barang" class="<?= (current_url() == site_url('miniapp/barang')) ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            </svg>
            <span>Stok</span>
        </a>

        <a href="/miniapp/transaksi" class="<?= (current_url() == site_url('miniapp/transaksi')) ? 'active' : '' ?>">
            <svg class="stroke-only" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>Hari Ini</span>
        </a>

        <a href="/miniapp/histori" class="<?= (current_url() == site_url('miniapp/histori')) ? 'active' : '' ?>">
            <svg class="stroke-only" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"></line>
                <line x1="8" y1="12" x2="21" y2="12"></line>
                <line x1="8" y1="18" x2="21" y2="18"></line>
                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
            <span>Histori</span>
        </a>
    </div>

    <script>
        let tg = window.Telegram.WebApp;
        tg.expand();
        tg.ready();

        document.addEventListener("DOMContentLoaded", function() {
            let userName = tg.initDataUnsafe?.user?.first_name || "Manager";
            let nameElement = document.getElementById('tg-user-name');
            if (nameElement) nameElement.innerText = userName;
        });
    </script>

</body>

</html>