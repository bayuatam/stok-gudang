<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Sistem Monitoring Material</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(0, 91, 172, .18), transparent 30%),
                radial-gradient(circle at bottom left, rgba(245, 166, 35, .10), transparent 30%),
                linear-gradient(135deg, #0f172a, #111827, #1e293b);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .wrapper {
            width: 100%;
            max-width: 1080px;
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 28px;
            align-items: center;
        }

        /* LEFT SIDE */
        .company-box {
            color: white;
            padding: 30px;
        }

        .company-mini {
            font-size: 13px;
            letter-spacing: 1px;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 18px;
        }

        .company-title {
            font-size: 46px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 18px;
        }

        .company-title span {
            color: #f5a623;
        }

        .company-desc {
            color: #cbd5e1;
            max-width: 520px;
            line-height: 1.8;
        }

        .feature-list {
            margin-top: 28px;
        }

        .feature-item {
            margin-bottom: 14px;
            color: #e2e8f0;
            font-weight: 600;
        }

        /* LOGIN CARD */
        .login-box {
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .08);
            backdrop-filter: blur(18px);
            border-radius: 28px;
            padding: 42px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, .35);
        }

        .logo-box {
            width: 82px;
            height: 82px;
            border-radius: 24px;
            background: linear-gradient(135deg, #005BAC, #003366);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 800;
            margin: auto;
            margin-bottom: 24px;
            box-shadow: 0 14px 35px rgba(0, 91, 172, .35);
        }

        h1 {
            text-align: center;
            color: white;
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            color: #cbd5e1;
            margin-bottom: 32px;
            font-size: 14px;
        }

        .subtitle span {
            color: #f5a623;
            font-weight: 700;
        }

        label {
            color: #e2e8f0;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control {
            height: 56px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .06);
            background: rgba(255, 255, 255, .05);
            color: white;
            padding-left: 16px;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, .08);
            color: white;
            border-color: rgba(245, 166, 35, .35);
            box-shadow: 0 0 0 4px rgba(245, 166, 35, .10);
        }

        .btn-login {
            height: 56px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #f5a623, #ffbf47);
            color: #111827;
            font-size: 16px;
            font-weight: 800;
            transition: .25s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 14px;
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            color: #94a3b8;
            font-size: 13px;
        }

        /* MOBILE */
        @media(max-width:992px) {
            .wrapper {
                grid-template-columns: 1fr;
            }

            .company-box {
                display: none;
            }
        }

        @media(max-width:576px) {
            .login-box {
                padding: 28px 22px;
            }

            h1 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <!-- LEFT -->
        <div class="company-box">

            <div class="company-mini">
                INTERNAL SYSTEM
            </div>

            <div class="company-title">
                Monitoring <span>Material</span><br>
                PT WIKA Beton
            </div>

            <div class="company-desc">
                Sistem digital untuk memantau stok material, transaksi gudang,
                histori pergerakan barang, dan kontrol operasional secara realtime.
            </div>

            <div class="feature-list">

                <div class="feature-item">✔ Dashboard Industri</div>
                <div class="feature-item">✔ Monitoring Stok Real Time</div>
                <div class="feature-item">✔ Histori Barang Masuk / Keluar</div>
                <div class="feature-item">✔ Export PDF & Excel</div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="login-box">

            <div class="logo-box">
                W
            </div>

            <h1>Login Sistem</h1>

            <div class="subtitle">
                Internal Access <span>PT WIKA Beton</span>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('login') ?>">

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text"
                        name="username"
                        class="form-control"
                        placeholder="Masukkan username"
                        required>
                </div>

                <div class="mb-4">
                    <label>Password</label>
                    <input type="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        required>
                </div>

                <div class="d-grid">
                    <button class="btn btn-login">
                        Login Sistem
                    </button>
                </div>

            </form>

            <div class="footer-text">
                © <?= date('Y') ?> PT Wijaya Karya Beton Tbk
            </div>

        </div>

    </div>

</body>

</html>