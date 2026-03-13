<?php
session_start();
$id_del_usuario = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : null;
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Iniciar Sesión';
$correo_usuario = isset($_SESSION['correo']) ? $_SESSION['correo'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MultiShop — Inicio</title>
  <meta name="description" content="MultiShop - Envoltorios de regalos, bolsas, cajas y más.">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="../public/img/Multi.png">
  <link rel="shortcut icon" href="../public/img/Multi.ico">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Bootstrap -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">

  <style>
    :root {
      --primary: #6e15c2;
      --primary-dark: #4c0d86;
      --primary-light: #9b59d6;
      --accent: #f59e0b;
      --bg: #f5f3ff;
      --bg2: #ffffff;
      --bg3: #f0ebfa;
      --text: #1e1333;
      --text-muted: #6b5c8a;
      --border: rgba(110,21,194,0.12);
      --card-shadow: 0 4px 24px rgba(110,21,194,0.08);
      --card-shadow-hover: 0 12px 40px rgba(110,21,194,0.18);
      --radius: 16px;
      --transition: 0.35s cubic-bezier(0.4,0,0.2,1);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg2);
      color: var(--text);
      overflow-x: hidden;
    }

    /* ========== SCROLLBAR ========== */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--bg); }
    ::-webkit-scrollbar-thumb { background: var(--primary-light); border-radius: 10px; }

    /* ========== NAVBAR ========== */
    #navbar {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 1000;
      padding: 14px 0;
      transition: var(--transition);
      background: var(--primary);
    }
    #navbar.scrolled {
      background: var(--primary-dark);
      box-shadow: 0 4px 24px rgba(76,13,134,0.35);
      padding: 10px 0;
    }
    .nav-brand {
      font-family: 'Outfit', sans-serif;
      font-size: 1.6rem;
      font-weight: 800;
      color: #fff;
      -webkit-text-fill-color: #fff;
      letter-spacing: -0.5px;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .nav-brand .brand-logo {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem;
      color: white;
      -webkit-text-fill-color: white;
      flex-shrink: 0;
    }
    .navbar-toggler {
      border: 1px solid rgba(255,255,255,0.3);
      padding: 6px 10px;
      border-radius: 8px;
      background: rgba(255,255,255,0.15);
    }
    .navbar-toggler-icon { filter: invert(1); }

    .nav-links { gap: 4px; }
    .nav-links .nav-item .nav-link {
      color: rgba(255,255,255,0.85);
      font-size: 0.9rem;
      font-weight: 500;
      padding: 7px 14px;
      border-radius: 8px;
      transition: var(--transition);
      text-decoration: none;
    }
    .nav-links .nav-item .nav-link:hover,
    .nav-links .nav-item .nav-link.active {
      color: #fff;
      background: rgba(255,255,255,0.15);
    }
    .nav-icon-btn {
      position: relative;
      color: rgba(255,255,255,0.85);
      font-size: 1.1rem;
      padding: 8px 12px;
      border-radius: 8px;
      text-decoration: none;
      transition: var(--transition);
      display: flex; align-items: center;
    }
    .nav-icon-btn:hover { color: #fff; background: rgba(255,255,255,0.15); }
    .nav-badge {
      position: absolute; top: 3px; right: 5px;
      background: var(--accent);
      color: #000; font-size: 0.6rem; font-weight: 700;
      width: 16px; height: 16px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
    }
    .btn-user {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: #fff !important;
      border: none;
      padding: 8px 18px;
      border-radius: 10px;
      font-size: 0.875rem;
      font-weight: 600;
      display: flex; align-items: center; gap: 7px;
      text-decoration: none;
      transition: var(--transition);
      box-shadow: 0 4px 15px rgba(110,21,194,0.25);
    }
    .btn-user:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(110,21,194,0.4);
      color: #fff;
    }
    .dropdown-menu {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--card-shadow-hover);
      padding: 8px;
    }
    .dropdown-item {
      color: var(--text); border-radius: 8px; padding: 10px 14px;
      font-size: 0.875rem; font-weight: 500; transition: var(--transition);
    }
    .dropdown-item:hover { background: rgba(110,21,194,0.07); color: var(--primary); }

    /* NOTIFICATION DROPDOWN */
    .notification-dropdown {
      position: absolute; top: calc(100% + 10px); right: 0;
      width: 300px;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      display: none; z-index: 2000;
      box-shadow: var(--card-shadow-hover);
      overflow: hidden;
    }
    .notification-header {
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
      font-weight: 600; font-size: 0.9rem; color: var(--text);
    }
    .notification-body { padding: 14px 16px; font-size: 0.85rem; color: var(--text-muted); }
    #markAsRead {
      width: 100%; border: none; border-radius: 0;
      background: var(--bg3); color: var(--text-muted);
      padding: 10px; font-size: 0.8rem; cursor: pointer;
      transition: var(--transition); border-top: 1px solid var(--border);
    }
    #markAsRead:hover { background: rgba(110,21,194,0.07); color: var(--primary); }

    /* ========== HERO ========== */
    #hero {
      position: relative;
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      overflow: hidden;
      background: linear-gradient(160deg, #faf8ff 0%, #f0ebfa 40%, #ede8ff 100%);
    }
    .hero-bg {
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 60% 50% at 20% 20%, rgba(110,21,194,0.08) 0%, transparent 60%),
        radial-gradient(ellipse 50% 40% at 85% 75%, rgba(155,89,214,0.10) 0%, transparent 60%);
    }
    .hero-grid {
      position: absolute; inset: 0;
      background-image: linear-gradient(rgba(110,21,194,0.05) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(110,21,194,0.05) 1px, transparent 1px);
      background-size: 60px 60px;
      mask-image: radial-gradient(ellipse 80% 60% at 50% 50%, black 20%, transparent 80%);
    }
    .hero-orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(70px);
      pointer-events: none;
    }
    .hero-orb-1 {
      width: 380px; height: 380px;
      background: rgba(110,21,194,0.12);
      top: -80px; left: -80px;
      animation: float1 8s ease-in-out infinite;
    }
    .hero-orb-2 {
      width: 280px; height: 280px;
      background: rgba(245,158,11,0.08);
      bottom: -40px; right: -40px;
      animation: float2 10s ease-in-out infinite;
    }
    @keyframes float1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(30px,20px)} }
    @keyframes float2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-20px,-30px)} }

    .hero-content {
      position: relative; z-index: 2;
      text-align: center; padding: 0 20px;
      max-width: 800px;
    }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(110,21,194,0.08);
      border: 1px solid rgba(110,21,194,0.2);
      border-radius: 100px;
      padding: 6px 16px; font-size: 0.8rem; font-weight: 600;
      color: var(--primary); letter-spacing: 0.5px;
      margin-bottom: 28px;
      animation: fadeInDown 0.8s ease both;
    }
    .hero-badge span { width: 6px; height: 6px; background: var(--primary); border-radius: 50%; }
    .hero-title {
      font-family: 'Outfit', sans-serif;
      font-size: clamp(2.8rem, 7vw, 5rem);
      font-weight: 800; line-height: 1.1;
      letter-spacing: -2px;
      color: var(--text);
      animation: fadeInUp 0.8s ease 0.1s both;
    }
    .hero-title .gradient-text {
      background: linear-gradient(135deg, var(--primary) 0%, #9b59d6 60%, var(--accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .hero-subtitle {
      font-size: 1.1rem; color: var(--text-muted);
      line-height: 1.7; margin: 20px auto 36px;
      max-width: 560px;
      animation: fadeInUp 0.8s ease 0.2s both;
    }
    .hero-btns {
      display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;
      animation: fadeInUp 0.8s ease 0.3s both;
    }
    .btn-primary-hero {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: #fff; text-decoration: none;
      padding: 14px 32px; border-radius: 12px;
      font-weight: 700; font-size: 0.95rem;
      box-shadow: 0 8px 30px rgba(110,21,194,0.3);
      transition: var(--transition);
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-primary-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 16px 45px rgba(110,21,194,0.45);
      color: #fff;
    }
    .btn-secondary-hero {
      background: #fff;
      border: 1.5px solid var(--border);
      color: var(--primary); text-decoration: none;
      padding: 14px 32px; border-radius: 12px;
      font-weight: 600; font-size: 0.95rem;
      transition: var(--transition);
      display: inline-flex; align-items: center; gap: 8px;
      box-shadow: 0 4px 15px rgba(110,21,194,0.08);
    }
    .btn-secondary-hero:hover {
      background: rgba(110,21,194,0.05);
      border-color: rgba(110,21,194,0.3);
      transform: translateY(-3px); color: var(--primary);
    }
    .hero-scroll {
      position: absolute; bottom: 36px; left: 50%;
      transform: translateX(-50%);
      z-index: 2; display: flex; flex-direction: column;
      align-items: center; gap: 8px;
      color: var(--text-muted); font-size: 0.75rem; letter-spacing: 1px;
      animation: fadeIn 1s ease 1s both;
    }
    .scroll-line {
      width: 1px; height: 50px;
      background: linear-gradient(to bottom, var(--primary-light), transparent);
      animation: scrollPulse 2s ease infinite;
    }
    @keyframes scrollPulse { 0%,100%{opacity:0.3} 50%{opacity:1} }

    /* ========== SECTIONS COMMON ========== */
    .section { padding: 100px 0; }
    .section-label {
      font-size: 0.75rem; font-weight: 700; letter-spacing: 3px;
      text-transform: uppercase; color: var(--primary);
      margin-bottom: 12px;
    }
    .section-title {
      font-family: 'Outfit', sans-serif;
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 800; color: var(--text);
      letter-spacing: -1px; line-height: 1.2;
    }
    .section-desc {
      color: var(--text-muted); font-size: 1rem;
      line-height: 1.7; max-width: 520px; margin-top: 14px;
    }

    /* ========== ABOUT ========== */
    #about { background: var(--bg3); }
    .about-img-wrapper {
      position: relative; border-radius: 24px; overflow: hidden;
    }
    .about-img-wrapper img {
      width: 100%; height: 380px; object-fit: cover;
      border-radius: 24px;
      transition: transform 0.6s ease;
    }
    .about-img-wrapper:hover img { transform: scale(1.05); }
    .about-img-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(135deg, rgba(110,21,194,0.15), transparent);
      border-radius: 24px;
    }
    .about-img-badge {
      position: absolute; bottom: 24px; left: 24px;
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border);
      border-radius: 14px; padding: 12px 18px;
      display: flex; align-items: center; gap: 10px;
      box-shadow: var(--card-shadow);
    }
    .about-img-badge .badge-icon {
      width: 40px; height: 40px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem; color: #fff;
    }
    .about-img-badge .badge-text { font-size: 0.75rem; color: var(--text-muted); }
    .about-img-badge .badge-num { font-size: 1rem; font-weight: 700; color: var(--text); }
    .feature-item {
      display: flex; align-items: flex-start; gap: 14px;
      padding: 16px 0; border-bottom: 1px solid var(--border);
    }
    .feature-item:last-child { border-bottom: none; }
    .feature-icon {
      width: 42px; height: 42px; flex-shrink: 0;
      background: rgba(110,21,194,0.08);
      border: 1px solid rgba(110,21,194,0.15);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: var(--primary); font-size: 1rem;
    }
    .feature-title { font-weight: 600; font-size: 0.95rem; color: var(--text); }
    .feature-desc { font-size: 0.82rem; color: var(--text-muted); margin-top: 2px; }

    /* ========== CATEGORIES ========== */
    #categories { background: var(--bg2); }
    .cat-card {
      position: relative;
      border-radius: 20px; overflow: hidden;
      aspect-ratio: 4/3;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      display: block;
      box-shadow: var(--card-shadow);
    }
    .cat-card:hover {
      transform: translateY(-6px);
      box-shadow: var(--card-shadow-hover);
    }
    .cat-card img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform 0.6s ease;
    }
    .cat-card:hover img { transform: scale(1.08); }
    .cat-card-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(to top, rgba(15,5,30,0.88) 0%, rgba(15,5,30,0.15) 55%, transparent 100%);
    }
    .cat-card-content {
      position: absolute; bottom: 0; left: 0; right: 0;
      padding: 20px;
    }
    .cat-card-tag {
      display: inline-block; margin-bottom: 6px;
      background: rgba(110,21,194,0.5);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(155,89,214,0.5);
      border-radius: 6px; padding: 3px 10px;
      font-size: 0.68rem; font-weight: 700;
      color: #e9d8ff; letter-spacing: 1px; text-transform: uppercase;
    }
    .cat-card-title {
      font-family: 'Outfit', sans-serif;
      font-size: 1.15rem; font-weight: 700; color: #fff;
      margin: 0 0 4px;
    }
    .cat-card-desc { font-size: 0.78rem; color: rgba(255,255,255,0.7); }
    .cat-card-arrow {
      position: absolute; top: 16px; right: 16px;
      width: 36px; height: 36px;
      background: rgba(255,255,255,0.9);
      border: 1px solid rgba(110,21,194,0.15); border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: var(--primary); font-size: 0.8rem;
      transform: rotate(0deg);
      transition: var(--transition);
    }
    .cat-card:hover .cat-card-arrow {
      background: var(--primary);
      color: #fff;
      transform: rotate(-45deg);
    }

    /* ========== CONTACT ========== */
    #contact { background: var(--bg3); }
    .contact-info-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 28px;
      height: 100%;
      box-shadow: var(--card-shadow);
    }
    .contact-item {
      display: flex; align-items: flex-start; gap: 16px;
      padding: 16px 0; border-bottom: 1px solid var(--border);
    }
    .contact-item:last-child { border-bottom: none; padding-bottom: 0; }
    .contact-item-icon {
      width: 46px; height: 46px; flex-shrink: 0;
      background: rgba(110,21,194,0.08);
      border: 1px solid rgba(110,21,194,0.15);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      color: var(--primary); font-size: 1.1rem;
    }
    .contact-item-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }
    .contact-item-value { font-size: 0.95rem; color: var(--text); font-weight: 600; margin-top: 3px; }

    .contact-form-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 36px;
      box-shadow: var(--card-shadow);
    }
    .form-control {
      background: var(--bg);
      border: 1.5px solid var(--border);
      border-radius: 12px;
      color: var(--text);
      font-size: 0.9rem;
      padding: 12px 16px;
      transition: var(--transition);
    }
    .form-control::placeholder { color: var(--text-muted); }
    .form-control:focus {
      background: #fff;
      border-color: var(--primary);
      color: var(--text);
      box-shadow: 0 0 0 3px rgba(110,21,194,0.1);
      outline: none;
    }
    textarea.form-control { min-height: 120px; resize: none; }
    .btn-submit {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: #fff; border: none;
      padding: 14px 32px; border-radius: 12px;
      font-weight: 700; font-size: 0.9rem;
      width: 100%; cursor: pointer;
      transition: var(--transition);
      box-shadow: 0 8px 25px rgba(110,21,194,0.25);
    }
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 35px rgba(110,21,194,0.4);
    }
    .form-label { color: var(--text-muted); font-size: 0.82rem; font-weight: 500; margin-bottom: 6px; }

    /* ========== FOOTER ========== */
    #footer {
      background: #fff;
      border-top: 1px solid var(--border);
      padding: 36px 0 24px;
    }
    .footer-brand {
      font-family: 'Outfit', sans-serif;
      font-size: 1.4rem; font-weight: 800;
      color: var(--primary);
      -webkit-text-fill-color: var(--primary);
    }
    .footer-text { color: var(--text-muted); font-size: 0.82rem; margin-top: 8px; }
    .social-icon {
      width: 38px; height: 38px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      display: inline-flex; align-items: center; justify-content: center;
      color: var(--text-muted); font-size: 0.9rem;
      transition: var(--transition); text-decoration: none;
      background: var(--bg3);
    }
    .social-icon:hover {
      background: rgba(110,21,194,0.08);
      border-color: rgba(110,21,194,0.3);
      color: var(--primary);
      transform: translateY(-2px);
    }
    .footer-copy { color: var(--text-muted); font-size: 0.8rem; }

    /* ========== SCROLL TOP ========== */
    #scrollTop {
      position: fixed; bottom: 24px; right: 24px;
      width: 44px; height: 44px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: #fff; border: none; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem; cursor: pointer;
      opacity: 0; visibility: hidden;
      transition: var(--transition);
      box-shadow: 0 8px 25px rgba(110,21,194,0.3);
      z-index: 9999;
    }
    #scrollTop.visible { opacity: 1; visibility: visible; }
    #scrollTop:hover { transform: translateY(-3px); box-shadow: 0 14px 35px rgba(110,21,194,0.45); }

    /* ========== ANIMATIONS ========== */
    @keyframes fadeInDown { from{opacity:0;transform:translateY(-20px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeInUp   { from{opacity:0;transform:translateY(30px)}  to{opacity:1;transform:translateY(0)} }
    @keyframes fadeIn     { from{opacity:0} to{opacity:1} }

    .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* ========== STATS BAR ========== */
    .stats-bar {
      padding: 48px 0;
      background: var(--bg3);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
    }
    .stat-item { text-align: center; }
    .stat-num {
      font-family: 'Outfit', sans-serif;
      font-size: 2.2rem; font-weight: 800;
      color: var(--primary);
    }
    .stat-label { color: var(--text-muted); font-size: 0.82rem; font-weight: 500; margin-top: 4px; }

    /* ========== MOBILE MENU ========== */
    #mobileMenu .nav-link { color: var(--text-muted); font-weight: 500; }
    #mobileMenu .nav-link:hover { color: var(--primary); }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 991px) {
      .hero-title { font-size: 3rem; letter-spacing: -1px; }
      .hero-content { padding-top: 60px; }
      .contact-form-card { padding: 24px; margin-top: 30px; }
      .section { padding: 60px 0; }
      #contact .contact-form-card { margin-top: 30px; }
      .stat-num { font-size: 1.8rem; }
    }
    @media (max-width: 768px) {
      .hero-title { font-size: 2.2rem; line-height: 1.1; letter-spacing: -0.5px; }
      .hero-subtitle { font-size: 1rem; padding: 0 10px; }
      .hero-btns { flex-direction: column; gap: 10px; align-items: stretch; width: 100%; max-width: 300px; margin: 30px auto 0; }
      .hero-content { padding-top: 80px; }
      .hero-scroll { bottom: 10px; }
      .section { padding: 50px 0; }
      .section-title { font-size: 2rem; }
      .stat-item { margin-bottom: 15px; }
      .contact-info-list { margin-bottom: 30px; }
    }
  </style>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav id="navbar">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between w-100">

      <!-- Brand -->
      <a href="login.php" class="nav-brand">
        <img src="../public/img/Multi.png" alt="MultiShop Logo" style="height: 40px; margin-right: 8px;">
        MultiShop
      </a>

      <!-- Toggler -->
      <button class="navbar-toggler d-lg-none border-0" type="button" id="navToggler">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Nav Links (desktop) -->
      <div class="d-none d-lg-flex align-items-center gap-2 nav-links">
        <div class="nav-item"><a class="nav-link active" href="login.php">Inicio</a></div>
        <div class="nav-item"><a class="nav-link" href="escritorio_tienda.php">Productos</a></div>
        <div class="nav-item"><a class="nav-link" href="#" id="compras-link">Compras</a></div>
      </div>

      <!-- Right Icons -->
      <div class="d-none d-lg-flex align-items-center gap-1">
        <!-- Cart -->
        <a href="#" id="carrito-link" class="nav-icon-btn">
          <i class="fas fa-shopping-cart"></i>
          <span id="cart-count" class="nav-badge"></span>
        </a>
        <!-- Bell -->
        <div class="position-relative">
          <a href="#" id="notificationBell" class="nav-icon-btn">
            <i class="fas fa-bell"></i>
            <span id="notificationCount" class="nav-badge"></span>
          </a>
          <div id="notificationDropdown" class="notification-dropdown">
            <div class="notification-header">Notificaciones</div>
            <div class="notification-body"><p>No hay notificaciones aún.</p></div>
            <button id="markAsRead">Marcar como leídas</button>
          </div>
        </div>
        <!-- User -->
        <div class="position-relative ms-2">
          <a href="inicio.php" id="userDropBtn"
             class="btn-user <?= isset($_SESSION['idusuario']) ? 'dropdown-toggle-custom' : '' ?>">
            <i class="fas fa-user"></i>
            <?= isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Iniciar Sesión' ?>
          </a>
          <?php if (isset($_SESSION['idusuario'])): ?>
          <div id="userDropMenu" class="dropdown-menu" style="display:none;position:absolute;right:0;top:calc(100%+8px);min-width:180px;">
            <a class="dropdown-item" href="./perfil-usuario.php"><i class="fas fa-user me-2"></i>Perfil</a>
            <a class="dropdown-item" href="../ajax/usuario.php?op=salir"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="d-lg-none" style="display:none; padding: 16px 0 8px;">
      <div style="border-top:1px solid rgba(255,255,255,0.15); padding-top:16px;">
        <a class="nav-link d-block mb-3 px-3 text-white fw-bold" href="login.php">Inicio</a>
        <a class="nav-link d-block mb-3 px-3 text-white fw-bold" href="escritorio_tienda.php">Productos</a>
        <a class="nav-link d-block mb-3 px-3 text-white fw-bold" href="#" id="compras-link-mobile">Compras</a>
        <div class="px-3">
            <a class="btn-user mt-2 d-inline-flex px-4" href="inicio.php"><i class="fas fa-user"></i> <?= $nombre_usuario ?></a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- ========== HERO ========== -->
<section id="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>

  <div class="hero-content">
    <div class="hero-badge"><span></span> Bienvenido a MultiShop</div>
    <h1 class="hero-title">
      Regalos que<br>
      <span class="gradient-text">inspiran momentos</span>
    </h1>
    <p class="hero-subtitle">
      Envoltorios, bolsas, cajas y accesorios para hacer que cada regalo sea una experiencia inolvidable.
    </p>
    <div class="hero-btns">
      <a href="escritorio_tienda.php" class="btn-primary-hero">
        <i class="fas fa-store"></i> Ver Productos
      </a>
      <a href="#about" class="btn-secondary-hero">
        <i class="fas fa-info-circle"></i> Conoce más
      </a>
    </div>
  </div>

  <div class="hero-scroll">
    <span>Descubre</span>
    <div class="scroll-line"></div>
  </div>
</section>

<!-- ========== STATS ========== -->
<div class="stats-bar">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-num">+500</div><div class="stat-label">Productos disponibles</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-num">2014</div><div class="stat-label">Fundada en</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-num">100%</div><div class="stat-label">Satisfacción garantizada</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-num">9+</div><div class="stat-label">Categorías</div></div></div>
    </div>
  </div>
</div>

<!-- ========== ABOUT ========== -->
<section id="about" class="section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 reveal">
        <div class="about-img-wrapper">
          <img src="../imagenes/manfra.png" alt="MANFRA C.A.">
          <div class="about-img-overlay"></div>
          <div class="about-img-badge">
            <div class="badge-icon"><i class="fas fa-store"></i></div>
            <div>
              <div class="badge-num">MANFRA C.A.</div>
              <div class="badge-text">Desde 2014 · Maracay</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 reveal">
        <div class="section-label">Acerca de nosotros</div>
        <h2 class="section-title">Nuestra historia y<br>nuestra pasión</h2>
        <p class="section-desc">
          Nace en 2014 por Yadira García, con el sueño de emprender. Ubicada en el Centro Comercial La Estación Central, Maracay, MANFRA C.A. se especializa en envoltorios de regalos, bolsas, cajas y artículos decorativos.
        </p>
        <div class="mt-4">
          <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-users"></i></div>
            <div>
              <div class="feature-title">Equipo dedicado</div>
              <div class="feature-desc">Contamos con empleados comprometidos con la calidad y el servicio.</div>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-box-open"></i></div>
            <div>
              <div class="feature-title">Venta y comercialización</div>
              <div class="feature-desc">Productos cuidadosamente seleccionados para cada ocasión especial.</div>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-headset"></i></div>
            <div>
              <div class="feature-title">Atención al cliente</div>
              <div class="feature-desc">Respuesta rápida y personalizada para resolver todas tus dudas.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== CATEGORIES ========== -->
<section id="categories" class="section">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-label">Lo que ofrecemos</div>
      <h2 class="section-title">Categorías</h2>
      <p class="section-desc mx-auto">Explora nuestra amplia variedad de productos para hacer especial cada momento.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=papeles-regalo" class="cat-card">
          <img src="../imagenes/papelregalo.jpg" alt="Papeles de regalo">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Decoración</span>
            <h4 class="cat-card-title">Papeles de regalo</h4>
            <p class="cat-card-desc">Colección única de papeles decorativos.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=bolsas-regalos" class="cat-card">
          <img src="../imagenes/regalo.png" alt="Bolsas de regalos">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Embalaje</span>
            <h4 class="cat-card-title">Bolsas de regalos</h4>
            <p class="cat-card-desc">Bolsas elegantes para cada ocasión.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=tarjetas-cumpleanos" class="cat-card">
          <img src="../imagenes/tarjetacumple.png" alt="Tarjetas cumpleaños">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Celebración</span>
            <h4 class="cat-card-title">Tarjetas de cumpleaños</h4>
            <p class="cat-card-desc">Tarjetas únicas para celebrar.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=tarjetas-15-anos" class="cat-card">
          <img src="../imagenes/tarjeta15.jpg" alt="Tarjetas 15 años">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Especial</span>
            <h4 class="cat-card-title">Tarjetas de 15 años</h4>
            <p class="cat-card-desc">Celebra este momento tan especial.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=globos" class="cat-card">
          <img src="../imagenes/Oso.png" alt="Globos">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Decoración</span>
            <h4 class="cat-card-title">Globos</h4>
            <p class="cat-card-desc">Globos decorativos para cualquier evento.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=lazos" class="cat-card">
          <img src="../imagenes/lazos.png" alt="Lazos">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Accesorios</span>
            <h4 class="cat-card-title">Lazos</h4>
            <p class="cat-card-desc">Lazos elegantes para tus regalos.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=peluches" class="cat-card">
          <img src="../imagenes/peluches.png" alt="Peluches">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Regalos</span>
            <h4 class="cat-card-title">Peluches</h4>
            <p class="cat-card-desc">Peluches tiernos para regalar amor.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=bisuteria" class="cat-card">
          <img src="../imagenes/bisuteria.png" alt="Bisutería">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Moda</span>
            <h4 class="cat-card-title">Bisutería</h4>
            <p class="cat-card-desc">Accesorios únicos y elegantes.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <a href="escritorio_tienda.php?categoria=arreglos" class="cat-card">
          <img src="../imagenes/Arreglo.png" alt="Arreglos">
          <div class="cat-card-overlay"></div>
          <div class="cat-card-content">
            <span class="cat-card-tag">Ocasiones</span>
            <h4 class="cat-card-title">Arreglos decorativos</h4>
            <p class="cat-card-desc">Arreglos para ocasiones especiales.</p>
          </div>
          <div class="cat-card-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ========== CONTACT ========== -->
<section id="contact" class="section">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-label">Comunícate con nosotros</div>
      <h2 class="section-title">Contáctanos</h2>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 reveal">
        <div class="contact-info-card">
          <div class="contact-item">
            <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div>
              <div class="contact-item-label">Dirección</div>
              <div class="contact-item-value">Primer nivel carreta pk-001, Magnífica C.A.</div>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
            <div>
              <div class="contact-item-label">Teléfono</div>
              <div class="contact-item-value">+58 424-3439084</div>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
            <div>
              <div class="contact-item-label">Correo</div>
              <div class="contact-item-value">manfraca@gmail.com</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8 reveal">
        <div class="contact-form-card">
          <form action="forms/contact.php" method="post" class="php-email-form">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" placeholder="Tu nombre" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="tu@email.com" required>
              </div>
              <div class="col-12">
                <label class="form-label">Asunto</label>
                <input type="text" name="subject" class="form-control" placeholder="¿En qué podemos ayudarte?" required>
              </div>
              <div class="col-12">
                <label class="form-label">Mensaje</label>
                <textarea name="message" class="form-control" placeholder="Escribe tu mensaje aquí..." required></textarea>
              </div>
              <div class="col-12">
                <div class="loading">Cargando</div>
                <div class="error-message"></div>
                <div class="sent-message">¡Tu mensaje se ha enviado. Gracias!</div>
                <button type="submit" class="btn-submit">
                  <i class="fas fa-paper-plane me-2"></i>Enviar mensaje
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== FOOTER ========== -->
<footer id="footer">
  <div class="container">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
      <div>
        <div class="footer-brand">MultiShop</div>
        <div class="footer-text">© <?= date('Y') ?> MANFRA C.A. — Todos los derechos reservados.</div>
      </div>
      <div class="d-flex gap-2">
        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </div>
</footer>

<!-- Scroll Top -->
<button id="scrollTop" title="Ir arriba"><i class="fas fa-arrow-up"></i></button>

<!-- Scripts -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<script>
// ─── Auth helper ───────────────────────────────────────────────
function estaAutenticado() {
  return Boolean(<?php echo isset($_SESSION['idusuario']) ? 'true' : 'false'; ?>);
}
function mostrarAlerta(titulo, texto) {
  Swal.fire({
    icon: 'warning', title: titulo, text: texto,
    showCancelButton: true,
    confirmButtonColor: '#7c3aed', cancelButtonColor: '#374151',
    confirmButtonText: 'Iniciar sesión', cancelButtonText: 'Cancelar',
    background: '#1a0f33', color: '#e2d9f3'
  }).then(r => { if (r.isConfirmed) window.location.href = 'inicio.php'; });
}

// ─── Navbar scroll ─────────────────────────────────────────────
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 50);
  document.getElementById('scrollTop').classList.toggle('visible', window.scrollY > 300);
});

// ─── Scroll Top ────────────────────────────────────────────────
document.getElementById('scrollTop').addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ─── Mobile menu ───────────────────────────────────────────────
document.getElementById('navToggler').addEventListener('click', () => {
  const m = document.getElementById('mobileMenu');
  m.style.display = m.style.display === 'none' || m.style.display === '' ? 'block' : 'none';
});

// ─── User dropdown ─────────────────────────────────────────────
const userBtn = document.getElementById('userDropBtn');
const userMenu = document.getElementById('userDropMenu');
if (userBtn && userMenu) {
  userBtn.addEventListener('click', e => {
    e.preventDefault();
    userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
  });
  document.addEventListener('click', e => {
    if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) userMenu.style.display = 'none';
  });
}

// ─── Compras link ──────────────────────────────────────────────
['compras-link', 'compras-link-mobile'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('click', e => {
    e.preventDefault();
    estaAutenticado() ? window.location.href = 'compras.php'
      : mostrarAlerta('Debes iniciar sesión', 'Para ver tus compras, por favor inicia sesión.');
  });
});

// ─── Cart link ─────────────────────────────────────────────────
document.getElementById('carrito-link')?.addEventListener('click', e => {
  e.preventDefault();
  estaAutenticado() ? window.location.href = 'carrito.php'
    : mostrarAlerta('Debes iniciar sesión', 'Para ver tu carrito, por favor inicia sesión.');
});

// ─── Cart count ────────────────────────────────────────────────
function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const total = cart.reduce((s, i) => s + i.cantidad, 0);
  const el = document.getElementById('cart-count');
  if (el) { el.textContent = total || ''; el.style.display = total ? 'flex' : 'none'; }
}
updateCartCount();

// ─── Notifications ─────────────────────────────────────────────
const bell = document.getElementById('notificationBell');
const nDrop = document.getElementById('notificationDropdown');
const nCount = document.getElementById('notificationCount');

bell?.addEventListener('click', e => {
  e.preventDefault();
  nDrop.style.display = nDrop.style.display === 'block' ? 'none' : 'block';
  actualizarNotificaciones();
});
document.addEventListener('click', e => {
  if (bell && !bell.contains(e.target) && !nDrop.contains(e.target)) nDrop.style.display = 'none';
});
document.getElementById('markAsRead')?.addEventListener('click', () => {
  marcarLeidas().then(() => { nDrop.style.display='none'; nCount.style.display='none'; });
});

function marcarLeidas() {
  return fetch('notificaciones.php', { method: 'POST' })
    .then(r => r.json()).then(d => { if (d.status !== 'success') throw new Error(d.message); });
}
function actualizarNotificaciones() {
  marcarLeidas().then(() => {
    fetch('notificaciones.php').then(r => r.json()).then(d => {
      if (d.status === 'success') {
        nCount.textContent = d.notificaciones;
        document.querySelector('#notificationDropdown .notification-body p').textContent = d.mensaje;
        if (d.notificaciones === 0) { nDrop.style.display='none'; nCount.style.display='none'; }
      }
    });
  });
}
actualizarNotificaciones();

// ─── Scroll reveal ─────────────────────────────────────────────
const observer = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); } });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>