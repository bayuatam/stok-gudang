<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login Sistem Monitoring Material</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

body{
min-height:100vh;
background:
radial-gradient(circle at top right, rgba(0,91,172,.18), transparent 30%),
radial-gradient(circle at bottom left, rgba(245,166,35,.10), transparent 30%),
linear-gradient(135deg,#0f172a,#111827,#1e293b);
display:flex;
align-items:center;
justify-content:center;
padding:20px;
overflow-x:hidden;
}

.wrapper{
width:100%;
max-width:1120px;
display:grid;
grid-template-columns:1.1fr .9fr;
gap:30px;
align-items:center;
}

/* LEFT */
.company-box{
color:#fff;
padding:20px;
}

.company-mini{
font-size:13px;
letter-spacing:1px;
font-weight:700;
color:#94a3b8;
margin-bottom:18px;
}

.company-title{
font-size:48px;
font-weight:800;
line-height:1.08;
margin-bottom:18px;
}

.company-title span{
color:#f5a623;
}

.company-desc{
color:#cbd5e1;
line-height:1.8;
max-width:520px;
}

.feature-list{
margin-top:28px;
display:grid;
gap:12px;
}

.feature-item{
background:rgba(255,255,255,.05);
padding:14px 18px;
border-radius:14px;
font-weight:600;
color:#e2e8f0;
border:1px solid rgba(255,255,255,.05);
}

/* LOGIN */
.login-box{
background:rgba(255,255,255,.07);
border:1px solid rgba(255,255,255,.08);
backdrop-filter:blur(18px);
border-radius:28px;
padding:42px;
box-shadow:0 25px 55px rgba(0,0,0,.35);
}

.logo-box{
width:84px;
height:84px;
border-radius:24px;
background:linear-gradient(135deg,#005BAC,#003366);
display:flex;
align-items:center;
justify-content:center;
color:#fff;
font-size:34px;
font-weight:800;
margin:auto;
margin-bottom:22px;
box-shadow:0 16px 35px rgba(0,91,172,.35);
}

h1{
text-align:center;
font-size:32px;
font-weight:800;
color:#fff;
margin-bottom:6px;
}

.subtitle{
text-align:center;
color:#cbd5e1;
font-size:14px;
margin-bottom:30px;
}

.subtitle span{
color:#f5a623;
font-weight:700;
}

label{
display:block;
font-size:14px;
font-weight:700;
color:#e2e8f0;
margin-bottom:8px;
}

.form-control{
height:56px;
border-radius:16px;
border:1px solid rgba(255,255,255,.06);
background:rgba(255,255,255,.05);
color:#fff;
padding:0 16px;
}

.form-control::placeholder{
color:#94a3b8;
}

.form-control:focus{
background:rgba(255,255,255,.08);
color:#fff;
border-color:rgba(245,166,35,.35);
box-shadow:0 0 0 4px rgba(245,166,35,.10);
}

.password-wrap{
position:relative;
}

.toggle-pass{
position:absolute;
right:14px;
top:50%;
transform:translateY(-50%);
border:none;
background:none;
color:#cbd5e1;
font-size:14px;
font-weight:700;
cursor:pointer;
}

.remember-box{
display:flex;
justify-content:space-between;
align-items:center;
gap:10px;
margin:18px 0 24px;
flex-wrap:wrap;
}

.form-check-label{
color:#cbd5e1;
font-size:14px;
}

.small-link{
color:#f5a623;
font-size:14px;
text-decoration:none;
font-weight:600;
}

.btn-login{
height:56px;
border:none;
border-radius:16px;
background:linear-gradient(135deg,#f5a623,#ffbf47);
color:#111827;
font-size:16px;
font-weight:800;
transition:.25s ease;
}

.btn-login:hover{
transform:translateY(-2px);
}

.btn-login.loading{
opacity:.8;
pointer-events:none;
}

.alert{
border-radius:14px;
font-size:14px;
}

.footer-text{
text-align:center;
margin-top:22px;
color:#94a3b8;
font-size:13px;
}

/* MOBILE */
@media(max-width:992px){

.wrapper{
grid-template-columns:1fr;
max-width:520px;
}

.company-box{
display:none;
}
}

@media(max-width:576px){

.login-box{
padding:28px 22px;
border-radius:22px;
}

h1{
font-size:26px;
}

.logo-box{
width:72px;
height:72px;
font-size:28px;
border-radius:20px;
}

.form-control,
.btn-login{
height:52px;
}
}
</style>
</head>

<body>

<div class="wrapper">

<!-- LEFT -->
<div class="company-box">

<div class="company-mini">
INTERNAL ENTERPRISE SYSTEM
</div>

<div class="company-title">
Smart <span>Warehouse</span><br>
WIKA Beton
</div>

<div class="company-desc">
Sistem digital perusahaan untuk memantau stok material,
transaksi gudang, histori barang, laporan operasional,
dan kontrol inventori secara realtime.
</div>

<div class="feature-list">

<div class="feature-item">✔ Dashboard Executive</div>
<div class="feature-item">✔ Monitoring Stok Real Time</div>
<div class="feature-item">✔ Barang Masuk / Keluar</div>
<div class="feature-item">✔ Histori & Export Report</div>

</div>

</div>

<!-- RIGHT -->
<div class="login-box">

<div class="logo-box">W</div>

<h1>Login Sistem</h1>

<div class="subtitle">
Internal Access <span>PT WIKA Beton</span>
</div>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
<?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<form method="post" action="<?= base_url('login') ?>" onsubmit="loginLoading()">

<div class="mb-3">
<label>Username</label>
<input type="text"
name="username"
class="form-control"
placeholder="Masukkan username"
required>
</div>

<div class="mb-3">
<label>Password</label>

<div class="password-wrap">

<input type="password"
name="password"
id="password"
class="form-control"
placeholder="Masukkan password"
required>

<button type="button"
class="toggle-pass"
onclick="togglePassword()">
Lihat
</button>

</div>

</div>

<div class="remember-box">

<div class="form-check">
<input class="form-check-input" type="checkbox" id="remember">
<label class="form-check-label" for="remember">
Remember me
</label>
</div>

<a href="#" class="small-link">
Secure Access
</a>

</div>

<div class="d-grid">
<button class="btn btn-login" id="loginBtn">
Login Sistem
</button>
</div>

</form>

<div class="footer-text">
© <?= date('Y') ?> PT Wijaya Karya Beton Tbk
</div>

</div>

</div>

<script>
function togglePassword(){

let pass = document.getElementById("password");

if(pass.type === "password"){
pass.type = "text";
}else{
pass.type = "password";
}

}

function loginLoading(){

let btn = document.getElementById("loginBtn");

btn.classList.add("loading");
btn.innerHTML = "Memproses...";
}
</script>

</body>
</html>