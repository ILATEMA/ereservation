<?php

$libellesStatutReservation = [
    'en_attente_paiement' => ['badge-warn', 'En attente'],
    'confirmee'           => ['badge-success', 'Confirmée'],
    'expiree'             => ['badge-muted', 'Expirée'],
    'annulee'             => ['badge-error', 'Annulée'],
];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tableau de bord — e-réservation</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#15130F;
    --ink-2:#1E1B15;
    --ink-3:#28241C;
    --ink-4:#332D22;
    --amber:#E8A33D;
    --amber-deep:#C97F1E;
    --amber-dim: rgba(232,163,61,0.14);
    --cream:#F5EFE3;
    --cream-dim:#B8AF9C;
    --muted:#8B8272;
    --error:#D9694F;
    --error-dim: rgba(217,105,79,0.14);
    --success:#7FAE7A;
    --success-dim: rgba(127,174,122,0.14);
    --warn:#E8C13D;
    --warn-dim: rgba(232,193,61,0.14);
    --line: rgba(245,239,227,0.09);
    --radius: 6px;
    --sidebar-w: 236px;
  }
  html[data-theme="light"]{
    --ink:#f7f3e9; --ink-2:#ffffff; --ink-3:#efe9da; --ink-4:#e3dbc5;
    --cream:#1b1812; --cream-dim:#5b5548; --muted:#736c5c;
    --line: rgba(20,15,5,0.10);
  }
  *{ box-sizing:border-box; margin:0; padding:0; }
  html,body{ height:100%; }
  body{
    background:var(--ink);
    color:var(--cream);
    font-family:'Inter', sans-serif;
    font-size:14px;
    display:flex;
    min-height:100vh;
  }
  a{ color:inherit; text-decoration:none; }
  button{ font-family:inherit; cursor:pointer; }
  ::-webkit-scrollbar{ width:8px; height:8px; }
  ::-webkit-scrollbar-thumb{ background:var(--ink-4); border-radius:4px; }

  /* ============ SIDEBAR ============ */
  .sidebar{
    width:var(--sidebar-w);
    background:var(--ink-2);
    border-right:1px solid var(--line);
    display:flex;
    flex-direction:column;
    position:fixed;
    top:0; bottom:0; left:0;
    z-index:20;
  }
  .sidebar-logo{
    display:flex; align-items:center; gap:9px;
    padding:22px 22px 20px;
    border-bottom:1px solid var(--line);
  }
  .sidebar-logo img{ height:28px; }
  .sidebar-logo .fb{ font-family:'Fraunces', serif; font-weight:600; font-size:17px; }
  .sidebar-logo .fb span{ color:var(--amber); }

  .company-tag{
    padding:16px 22px;
    border-bottom:1px solid var(--line);
  }
  .company-tag .name{ font-family:'Fraunces', serif; font-weight:600; font-size:15px; margin-bottom:4px; }
  .company-tag .plan{
    display:inline-flex; align-items:center; gap:5px;
    font-family:'IBM Plex Mono', monospace;
    font-size:10px; letter-spacing:0.05em; text-transform:uppercase;
    color:var(--success);
    background:var(--success-dim);
    padding:3px 8px; border-radius:20px;
  }

  nav.sidebar-nav{
    flex:1;
    padding:14px 12px;
    display:flex; flex-direction:column; gap:2px;
    overflow-y:auto;
  }
  .nav-item{
    display:flex; align-items:center; gap:11px;
    padding:10px 12px;
    border-radius:var(--radius);
    font-size:13.5px;
    color:var(--cream-dim);
    border-left:2px solid transparent;
    transition:background .15s ease, color .15s ease;
  }
  .nav-item svg{ width:16px; height:16px; flex-shrink:0; opacity:0.85; }
  .nav-item:hover{ background:var(--ink-3); color:var(--cream); }
  .nav-item.active{
    background:var(--amber-dim);
    color:var(--amber);
    border-left-color:var(--amber);
  }
  .nav-item.active svg{ opacity:1; }
  .nav-section-label{
    font-family:'IBM Plex Mono', monospace;
    font-size:10px; letter-spacing:0.1em; text-transform:uppercase;
    color:var(--muted);
    padding:14px 12px 6px;
  }

  .sidebar-footer{
    padding:14px 12px;
    border-top:1px solid var(--line);
  }
  .logout-link{
    display:flex; align-items:center; gap:10px;
    padding:10px 12px;
    border-radius:var(--radius);
    color:var(--muted);
    font-size:13px;
    transition:color .15s ease, background .15s ease;
  }
  .logout-link:hover{ color:var(--error); background:var(--error-dim); }
  .logout-link svg{ width:15px; height:15px; }

  /* ============ MAIN ============ */
  .main{
    margin-left:var(--sidebar-w);
    flex:1;
    display:flex;
    flex-direction:column;
    min-width:0;
  }
  .topbar{
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 32px;
    border-bottom:1px solid var(--line);
    background:var(--ink);
    position:sticky; top:0; z-index:10;
  }
  .topbar h1{
    font-family:'Fraunces', serif; font-weight:600; font-size:21px;
  }
  .topbar-right{ display:flex; align-items:center; gap:16px; }
  .icon-btn{
    width:36px; height:36px;
    border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    background:var(--ink-2);
    border:1px solid var(--line);
    color:var(--cream-dim);
    position:relative;
  }
  .icon-btn:hover{ color:var(--cream); border-color:var(--amber); }
  .icon-btn .icon-sun{display:none;}
  html[data-theme="light"] .icon-btn .icon-moon{display:none;}
  html[data-theme="light"] .icon-btn .icon-sun{display:block;}
  .icon-btn svg{ width:16px; height:16px; }
  .notif-dot{
    position:absolute; top:6px; right:6px;
    width:7px; height:7px; border-radius:50%;
    background:var(--error);
    border:1.5px solid var(--ink);
  }
  .notif-panel{
    display:none;
    position:absolute; top:calc(100% + 8px); right:0;
    width:300px;
    background:var(--ink-2);
    border:1px solid var(--line);
    border-radius:var(--radius);
    box-shadow:0 20px 50px -10px rgba(0,0,0,0.5);
    z-index:50;
    overflow:hidden;
  }
  .notif-panel.visible{ display:block; }
  .notif-panel-header{
    padding:12px 16px;
    font-family:'Fraunces', serif; font-weight:600; font-size:14px;
    border-bottom:1px solid var(--line);
  }
  .notif-item{
    padding:12px 16px;
    font-size:12.5px;
    border-bottom:1px solid var(--line);
    line-height:1.5;
  }
  .notif-item:last-child{ border-bottom:none; }
  .notif-item.notif-success{ color:var(--success); }
  .notif-item.notif-warn{ color:var(--warn); }
  .notif-item.notif-error{ color:var(--error); }
  .notif-item.notif-empty{ color:var(--muted); }
  .avatar{
    width:36px; height:36px; border-radius:50%;
    background:var(--amber);
    color:var(--ink);
    display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:13px;
  }

  .content{
    padding:28px 32px 60px;
  }
  .view{ display:none; }
  .view.active{ display:block; animation:fadein .25s ease; }
  @keyframes fadein{ from{opacity:0; transform:translateY(4px);} to{opacity:1; transform:none;} }

  /* alert banner */
  .alert-banner{
    display:flex; align-items:center; gap:12px;
    background:var(--warn-dim);
    border:1px solid rgba(232,193,61,0.3);
    color:var(--warn);
    padding:13px 16px;
    border-radius:var(--radius);
    font-size:13px;
    margin-bottom:24px;
  }
  .alert-banner svg{ width:17px; height:17px; flex-shrink:0; }
  .alert-banner a{ color:var(--amber); font-weight:600; text-decoration:underline; margin-left:auto; flex-shrink:0; }

  /* KPI cards */
  .kpi-grid{
    display:grid;
    grid-template-columns:repeat(4, 1fr);
    gap:16px;
    margin-bottom:28px;
  }
  .kpi-card{
    background:var(--ink-2);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:18px 20px;
  }
  .kpi-label{
    font-family:'IBM Plex Mono', monospace;
    font-size:10.5px; letter-spacing:0.06em; text-transform:uppercase;
    color:var(--muted);
    margin-bottom:10px;
  }
  .kpi-value{
    font-family:'Fraunces', serif; font-weight:600; font-size:26px;
    margin-bottom:6px;
  }
  .kpi-delta{ font-size:11.5px; color:var(--success); display:flex; align-items:center; gap:4px; }
  .kpi-delta.down{ color:var(--error); }
  .kpi-delta.neutral{ color:var(--muted); }

  /* section blocks */
  .section-block{
    background:var(--ink-2);
    border:1px solid var(--line);
    border-radius:var(--radius);
    margin-bottom:22px;
    overflow:hidden;
  }
  .block-header{
    display:flex; align-items:center; justify-content:space-between;
    padding:16px 20px;
    border-bottom:1px solid var(--line);
  }
  .block-header h3{ font-family:'Fraunces', serif; font-weight:600; font-size:16px; }
  .block-header .see-all{ font-size:12px; color:var(--amber); }

  /* buttons */
  .btn{
    display:inline-flex; align-items:center; gap:7px;
    padding:9px 16px;
    border-radius:var(--radius);
    font-size:13px; font-weight:600;
    border:none;
    transition:background .2s ease, transform .15s ease;
  }
  .btn svg{ width:14px; height:14px; }
  .btn-primary{ background:var(--amber); color:var(--ink); }
  .btn-primary:hover{ background:var(--amber-deep); }
  .btn-ghost{ background:var(--ink-3); color:var(--cream); border:1px solid var(--line); }
  .btn-ghost:hover{ border-color:var(--amber); color:var(--amber); }
  .btn-sm{ padding:6px 11px; font-size:12px; }
  .btn:active{ transform:scale(0.98); }

  /* tables */
  .table-wrap{ overflow-x:auto; }
  table{ width:100%; border-collapse:collapse; }
  thead th{
    text-align:left;
    font-family:'IBM Plex Mono', monospace;
    font-size:10.5px; letter-spacing:0.06em; text-transform:uppercase;
    color:var(--muted);
    padding:11px 20px;
    border-bottom:1px solid var(--line);
    white-space:nowrap;
  }
  tbody td{
    padding:14px 20px;
    border-bottom:1px solid var(--line);
    font-size:13.5px;
    white-space:nowrap;
  }
  tbody tr:last-child td{ border-bottom:none; }
  tbody tr{ transition:background .15s ease; }
  tbody tr:hover{ background:var(--ink-3); }
  .cell-muted{ color:var(--muted); font-size:12.5px; }
  .cell-mono{ font-family:'IBM Plex Mono', monospace; font-size:12px; color:var(--cream-dim); }
  .route-cell{ display:flex; align-items:center; gap:7px; font-weight:500; }
  .route-cell svg{ width:12px; height:12px; color:var(--muted); }

  .badge{
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px;
    border-radius:20px;
    font-size:11px; font-weight:600;
    font-family:'IBM Plex Mono', monospace;
    letter-spacing:0.02em;
  }
  .badge::before{ content:''; width:6px; height:6px; border-radius:50%; }
  .badge-success{ background:var(--success-dim); color:var(--success); }
  .badge-success::before{ background:var(--success); }
  .badge-warn{ background:var(--warn-dim); color:var(--warn); }
  .badge-warn::before{ background:var(--warn); }
  .badge-error{ background:var(--error-dim); color:var(--error); }
  .badge-error::before{ background:var(--error); }
  .badge-muted{ background:var(--ink-3); color:var(--muted); }
  .badge-muted::before{ background:var(--muted); }

  .seats-bar{
    width:70px; height:5px; border-radius:3px;
    background:var(--ink-4);
    overflow:hidden;
    display:inline-block;
    vertical-align:middle;
    margin-right:8px;
  }
  .seats-bar-fill{ height:100%; background:var(--amber); }

  .action-icons{ display:flex; gap:6px; }
  .action-icon{
    width:28px; height:28px;
    border-radius:6px;
    display:flex; align-items:center; justify-content:center;
    background:var(--ink-3);
    border:1px solid var(--line);
    color:var(--cream-dim);
  }
  .action-icon:hover{ color:var(--amber); border-color:var(--amber); }
  .action-icon svg{ width:13px; height:13px; }

  .filter-row{
    display:flex; align-items:center; gap:10px;
    padding:14px 20px;
    border-bottom:1px solid var(--line);
    flex-wrap:wrap;
  }
  select, .search-input{
    background:var(--ink-3);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:8px 12px;
    color:var(--cream);
    font-size:12.5px;
    font-family:'Inter', sans-serif;
    outline:none;
  }
  select:focus, .search-input:focus{ border-color:var(--amber); }
  .search-input{ min-width:220px; }

  /* two-col layout */
  .two-col{ display:grid; grid-template-columns:1.4fr 1fr; gap:20px; align-items:start; }

  /* chart bars */
  .chart-area{ padding:20px; }
  .bar-chart{ display:flex; align-items:flex-end; gap:10px; height:160px; }
  .bar-col{ flex:1; display:flex; flex-direction:column; align-items:center; gap:8px; height:100%; justify-content:flex-end; }
  .bar-fill{ width:100%; background:linear-gradient(180deg, var(--amber), var(--amber-deep)); border-radius:4px 4px 0 0; min-height:4px; }
  .bar-label{ font-size:10.5px; color:var(--muted); font-family:'IBM Plex Mono', monospace; }
  .bar-value{ font-size:11px; color:var(--cream-dim); font-family:'IBM Plex Mono', monospace; }

  /* forms (voyages, mobile money) */
  .form-card{ padding:22px; }
  .form-grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px; }
  .field{ display:flex; flex-direction:column; gap:7px; }
  .field.full{ grid-column:1 / -1; }
  .field label{ font-size:12px; font-weight:600; color:var(--cream-dim); }
  .field input, .field select{
    background:var(--ink-3); border:1px solid var(--line); border-radius:var(--radius);
    padding:10px 13px; color:var(--cream); font-size:13.5px; outline:none;
  }
  .field input:focus, .field select:focus{ border-color:var(--amber); }
  .field-hint{ font-size:11px; color:var(--muted); }

  /* modal */
  .modal-overlay{
    display:none;
    position:fixed; inset:0; z-index:100;
    background:rgba(0,0,0,0.6);
    align-items:center; justify-content:center;
    padding:24px;
  }
  .modal-overlay.visible{ display:flex; }
  .modal{
    background:var(--ink-2);
    border:1px solid var(--line);
    border-radius:10px;
    width:100%; max-width:520px;
    max-height:90vh; overflow-y:auto;
  }
  .modal-header{
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 22px; border-bottom:1px solid var(--line);
  }
  .modal-header h3{ font-family:'Fraunces', serif; font-weight:600; font-size:17px; }
  .modal-close{ background:none; border:none; color:var(--muted); font-size:20px; line-height:1; }
  .modal-close:hover{ color:var(--cream); }
  .modal-body{ padding:22px; }
  .modal-footer{
    display:flex; justify-content:flex-end; gap:10px;
    padding:16px 22px; border-top:1px solid var(--line);
  }

  .toast{
    position:fixed; bottom:24px; right:24px; z-index:200;
    background:var(--ink-3); border:1px solid var(--success); color:var(--cream);
    padding:12px 18px; border-radius:var(--radius);
    font-size:13px; display:none; align-items:center; gap:9px;
    box-shadow:0 20px 50px -10px rgba(0,0,0,0.5);
  }
  .toast.visible{ display:flex; }
  .toast svg{ width:15px; height:15px; color:var(--success); }

  @media (max-width: 1100px){
    .kpi-grid{ grid-template-columns:repeat(2, 1fr); }
    .two-col{ grid-template-columns:1fr; }
  }
  @media (max-width: 780px){
    .sidebar{ transform:translateX(-100%); transition:transform .2s ease; }
    .sidebar.open{ transform:translateX(0); }
    .main{ margin-left:0; }
    .kpi-grid{ grid-template-columns:1fr; }
    .form-grid{ grid-template-columns:1fr; }
  }
  @media (prefers-reduced-motion: reduce){ *{ transition:none !important; animation:none !important; } }
</style>
</head>
<body>

<!-- ============ SIDEBAR ============ -->
<aside class="sidebar" id="sidebar">
  <a href="<?= url_app('/') ?>" class="sidebar-logo">
    <img src="<?= url_app('/assets/logo.png') ?>" alt="" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
    <span class="fb" style="display:none;">e-r<span>é</span>servation</span>
  </a>
  <div class="company-tag">
    <div class="name"><?= htmlspecialchars($compagnie['nom'], ENT_QUOTES, 'UTF-8') ?></div>
    <span class="plan">● Abonnement actif</span>
  </div>
  <nav class="sidebar-nav">
    <a class="nav-item active" data-view="apercu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
      Vue d'ensemble
    </a>
    <a class="nav-item" data-view="voyages">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17h18M5 17V9a2 2 0 012-2h10a2 2 0 012 2v8M7 21v-2M17 21v-2"/><circle cx="7" cy="17" r="1"/><circle cx="17" cy="17" r="1"/></svg>
      Voyages
    </a>
    <a class="nav-item" data-view="reservations">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/></svg>
      Réservations
    </a>
    <a class="nav-item" data-view="passagers">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6M17 8a3 3 0 110 6M22 20c0-2.6-1.8-4.7-4.3-5.5"/></svg>
      Passagers
    </a>
    <a class="nav-item" data-view="stats">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18M8 17V10M13 17V6M18 17v-4"/></svg>
      Statistiques
    </a>
    <div class="nav-section-label">Paramètres</div>
    <a class="nav-item" data-view="mobilemoney">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="13" rx="2"/><path d="M2 10h20"/><circle cx="7" cy="14.5" r="1"/></svg>
      Mobile Money
    </a>
    <a class="nav-item" data-view="abonnement">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l2.4 6.6L21 9l-5 4.3L17.5 21 12 17.3 6.5 21 8 13.3 3 9l6.6-.4z"/></svg>
      Abonnement
    </a>
  </nav>
  <div class="sidebar-footer">
    <a href="<?= url_app('/deconnexion?espace=compagnie') ?>" class="logout-link" id="logoutBtn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      Déconnexion
    </a>
  </div>
</aside>

<!-- ============ MAIN ============ -->
<div class="main">
  <div class="topbar">
    <h1 id="pageTitle">Vue d'ensemble</h1>
    <div class="topbar-right">
      <button class="icon-btn" id="themeToggle" title="Mode clair / sombre">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
      </button>
      <div style="position:relative;">
        <button class="icon-btn" id="notifBtn" title="Notifications">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 01-3.4 0"/></svg>
          <?php if ($notifications !== []): ?><span class="notif-dot"></span><?php endif; ?>
        </button>
        <div class="notif-panel" id="notifPanel">
          <div class="notif-panel-header">Notifications</div>
          <?php if ($notifications === []): ?>
          <div class="notif-item notif-empty">Aucune notification pour l'instant.</div>
          <?php else: ?>
            <?php foreach ($notifications as $n): ?>
            <div class="notif-item notif-<?= $n['type'] ?>"><?= htmlspecialchars($n['texte'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="avatar">TC</div>
    </div>
  </div>

  <div class="content">

    <!-- ============ VUE D'ENSEMBLE ============ -->
    <section class="view active" id="view-apercu">
      <div class="kpi-grid">
        <div class="kpi-card">
          <div class="kpi-label">Voyages actifs</div>
          <div class="kpi-value"><?= $voyagesActifs ?></div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Réservations aujourd'hui</div>
          <div class="kpi-value"><?= $reservationsAujourdhui ?></div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Revenu du mois</div>
          <div class="kpi-value"><?= htmlspecialchars(\App\Core\Aide::formaterFcfa($revenuMoisCompagnie), ENT_QUOTES, 'UTF-8') ?></div>
          <div class="kpi-delta neutral">Part compagnie, hors frais service</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Taux de remplissage</div>
          <div class="kpi-value"><?= $tauxRemplissage ?>%</div>
        </div>
      </div>

      <div class="two-col">
        <div class="section-block">
          <div class="block-header">
            <h3>Prochains voyages</h3>
            <a class="see-all" href="#" data-goto="voyages">Tout voir →</a>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr><th>Trajet</th><th>Date</th><th>Places</th><th>Statut</th></tr>
              </thead>
              <tbody>
                <?php $prochains = array_slice(array_filter($voyagesListe, fn($v) => $v['statut'] === 'planifie'), 0, 3); ?>
                <?php foreach ($prochains as $v):
                    $occupees = 50 - (int) $v['places_restantes'];
                    $pourcentage = round(($occupees / 50) * 100);
                    $classeBadge = $v['places_restantes'] <= 5 ? 'badge-warn' : 'badge-success';
                    $libelleBadge = $v['places_restantes'] <= 5 ? 'Presque complet' : 'Actif';
                ?>
                <tr>
                  <td class="route-cell"><?= htmlspecialchars($v['ville_depart'], ENT_QUOTES, 'UTF-8') ?> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg> <?= htmlspecialchars($v['ville_arrivee'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td class="cell-muted"><?= date('d M, H\hi', strtotime($v['date_heure_depart'])) ?></td>
                  <td><span class="seats-bar"><span class="seats-bar-fill" style="width:<?= $pourcentage ?>%"></span></span><?= $occupees ?>/50</td>
                  <td><span class="badge <?= $classeBadge ?>"><?= $libelleBadge ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if ($prochains === []): ?>
                <tr><td colspan="4" class="cell-muted">Aucun voyage planifié pour l'instant.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="section-block">
          <div class="block-header">
            <h3>Réservations récentes</h3>
            <a class="see-all" href="#" data-goto="reservations">Tout voir →</a>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Code</th><th>Passager</th><th>Statut</th></tr></thead>
              <tbody>
                <?php foreach (array_slice($reservationsListe, 0, 5) as $r): [$classeR, $libelleR] = $libellesStatutReservation[$r['statut']]; ?>
                <tr><td class="cell-mono"><?= htmlspecialchars($r['code_reservation'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($r['nom_passager'], ENT_QUOTES, 'UTF-8') ?></td><td><span class="badge <?= $classeR ?>"><?= $libelleR ?></span></td></tr>
                <?php endforeach; ?>
                <?php if ($reservationsListe === []): ?>
                <tr><td colspan="3" class="cell-muted">Aucune réservation pour l'instant.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ VOYAGES ============ -->
    <section class="view" id="view-voyages">
      <div class="section-block">
        <div class="filter-row">
          <input class="search-input" type="text" placeholder="Rechercher un trajet…">
          <select><option>Tous les statuts</option><option>Actif</option><option>Complet</option><option>Annulé</option></select>
          <select><option>Tous les bus</option><option>Bus 01 — 50 places</option><option>Bus 02 — 50 places</option></select>
          <button class="btn btn-primary" style="margin-left:auto;" id="openVoyageModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Nouveau voyage
          </button>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>Trajet</th><th>Date / heure</th><th>Prix</th><th>Places</th><th>Statut</th><th></th></tr>
            </thead>
            <tbody>
              <?php foreach ($voyagesListe as $v):
                  $occupees = 50 - (int) $v['places_restantes'];
                  $pourcentage = round(($occupees / 50) * 100);
                  $badges = [
                      'planifie' => $v['places_restantes'] <= 0 ? ['badge-muted', 'Complet'] : ($v['places_restantes'] <= 5 ? ['badge-warn', 'Presque complet'] : ['badge-success', 'Actif']),
                      'annule'   => ['badge-error', 'Annulé'],
                      'termine'  => ['badge-muted', 'Terminé'],
                  ];
                  [$classeBadge, $libelleBadge] = $badges[$v['statut']];
              ?>
              <tr>
                <td class="route-cell"><?= htmlspecialchars($v['ville_depart'], ENT_QUOTES, 'UTF-8') ?> → <?= htmlspecialchars($v['ville_arrivee'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="cell-muted"><?= date('d M Y, H\hi', strtotime($v['date_heure_depart'])) ?></td>
                <td class="cell-mono"><?= htmlspecialchars(\App\Core\Aide::formaterFcfa((int) $v['prix_fcfa']), ENT_QUOTES, 'UTF-8') ?></td>
                <td><span class="seats-bar"><span class="seats-bar-fill" style="width:<?= $pourcentage ?>%"></span></span><?= $occupees ?>/50</td>
                <td><span class="badge <?= $classeBadge ?>"><?= $libelleBadge ?></span></td>
                <td><div class="action-icons">
                  <?php if ($v['statut'] === 'planifie'): ?>
                  <button class="action-icon btn-annuler-voyage" title="Annuler" data-id="<?= $v['id'] ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></button>
                  <?php endif; ?>
                </div></td>
              </tr>
              <?php endforeach; ?>
              <?php if ($voyagesListe === []): ?>
              <tr><td colspan="6" class="cell-muted">Aucun voyage pour l'instant. Clique sur "Nouveau voyage" pour en créer un.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ RESERVATIONS ============ -->
    <section class="view" id="view-reservations">
      <div class="section-block">
        <div class="filter-row">
          <input class="search-input" type="text" placeholder="Code réservation, nom ou téléphone…">
          <select><option>Tous les voyages</option></select>
          <select><option>Tous les statuts</option><option>Payée</option><option>En attente</option><option>Annulée</option></select>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>Code</th><th>Passager</th><th>Téléphone</th><th>Trajet</th><th>Siège</th><th>Statut</th></tr>
            </thead>
            <tbody>
              <?php foreach ($reservationsListe as $r): [$classeR, $libelleR] = $libellesStatutReservation[$r['statut']]; ?>
              <tr>
                <td class="cell-mono"><?= htmlspecialchars($r['code_reservation'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($r['nom_passager'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="cell-muted"><?= htmlspecialchars($r['telephone_passager'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="cell-muted"><?= htmlspecialchars($r['ville_depart'], ENT_QUOTES, 'UTF-8') ?> → <?= htmlspecialchars($r['ville_arrivee'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="cell-mono"><?= str_pad((string) $r['siege_numero'], 2, '0', STR_PAD_LEFT) ?></td>
                <td><span class="badge <?= $classeR ?>"><?= $libelleR ?></span></td>
              </tr>
              <?php endforeach; ?>
              <?php if ($reservationsListe === []): ?>
              <tr><td colspan="6" class="cell-muted">Aucune réservation pour l'instant.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ PASSAGERS ============ -->
    <section class="view" id="view-passagers">
      <div class="section-block">
        <div class="filter-row">
          <select id="selectVoyagePassagers" onchange="window.location.href = APP_BASE + '/compagnie?view=passagers&voyage=' + this.value;">
            <?php if ($voyagesPourSelect === []): ?>
            <option>Aucun voyage créé pour l'instant</option>
            <?php endif; ?>
            <?php foreach ($voyagesPourSelect as $vp): ?>
            <option value="<?= $vp['id'] ?>" <?= $vp['id'] == $voyageSelectionneId ? 'selected' : '' ?>>
              <?= htmlspecialchars($vp['ville_depart'], ENT_QUOTES, 'UTF-8') ?> → <?= htmlspecialchars($vp['ville_arrivee'], ENT_QUOTES, 'UTF-8') ?>, <?= date('d M, H\hi', strtotime($vp['date_heure_depart'])) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Siège</th><th>Nom</th><th>Téléphone</th><th>Statut</th></tr></thead>
            <tbody>
              <?php if ($voyageSelectionneId === 0): ?>
              <tr><td colspan="4" class="cell-muted">Créez d'abord un voyage pour voir son plan de sièges.</td></tr>
              <?php else: ?>
              <?php for ($siege = 1; $siege <= 50; $siege++): ?>
                <?php $occupant = $passagersParSiege[$siege] ?? null; ?>
                <tr>
                  <td class="cell-mono"><?= str_pad((string) $siege, 2, '0', STR_PAD_LEFT) ?></td>
                  <?php if ($occupant): [$classeP, $libelleP] = $libellesStatutReservation[$occupant['statut']]; ?>
                  <td><?= htmlspecialchars($occupant['nom_passager'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td class="cell-muted"><?= htmlspecialchars($occupant['telephone_passager'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="badge <?= $classeP ?>"><?= $libelleP ?></span></td>
                  <?php else: ?>
                  <td>—</td>
                  <td class="cell-muted">—</td>
                  <td><span class="badge badge-muted">Libre</span></td>
                  <?php endif; ?>
                </tr>
              <?php endfor; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ STATISTIQUES ============ -->
    <section class="view" id="view-stats">
      <div class="kpi-grid" style="grid-template-columns:repeat(3, 1fr);">
        <div class="kpi-card"><div class="kpi-label">Billets vendus (30j)</div><div class="kpi-value"><?= number_format($billetsVendus30j, 0, ',', ' ') ?></div></div>
        <div class="kpi-card"><div class="kpi-label">Revenu net (30j)</div><div class="kpi-value"><?= htmlspecialchars(\App\Core\Aide::formaterFcfa($revenuNet30j), ENT_QUOTES, 'UTF-8') ?></div></div>
        <div class="kpi-card"><div class="kpi-label">Taux d'annulation (30j)</div><div class="kpi-value"><?= $tauxAnnulation30j ?>%</div></div>
      </div>
      <div class="section-block">
        <div class="block-header"><h3>Ventes des 7 derniers jours</h3></div>
        <div class="chart-area">
          <div class="bar-chart">
            <?php foreach ($ventes7Jours as $jour): $hauteur = round(($jour['nb'] / $maxVentesJour) * 100); ?>
            <div class="bar-col"><div class="bar-value"><?= $jour['nb'] ?></div><div class="bar-fill" style="height:<?= max(4, $hauteur) ?>%"></div><div class="bar-label"><?= $jour['label'] ?></div></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="section-block">
        <div class="block-header"><h3>Trajets les plus vendus</h3></div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Trajet</th><th>Billets vendus</th><th>Taux de remplissage moyen</th></tr></thead>
            <tbody>
              <?php foreach ($trajetsPopulaires as $t):
                  $tauxRemplissageTrajet = ((int) $t['nb_voyages'] > 0)
                      ? round(((int) $t['billets_vendus'] / ((int) $t['nb_voyages'] * 50)) * 100)
                      : 0;
              ?>
              <tr>
                <td class="route-cell"><?= htmlspecialchars($t['depart'], ENT_QUOTES, 'UTF-8') ?> → <?= htmlspecialchars($t['arrivee'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="cell-mono"><?= (int) $t['billets_vendus'] ?></td>
                <td><?= $tauxRemplissageTrajet ?>%</td>
              </tr>
              <?php endforeach; ?>
              <?php if ($trajetsPopulaires === []): ?>
              <tr><td colspan="3" class="cell-muted">Aucune vente pour l'instant.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ MOBILE MONEY ============ -->
    <section class="view" id="view-mobilemoney">
      <div class="alert-banner warn" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg> Cet onglet affiche encore des données de démonstration, pas encore relié à la base. À faire ensuite.</div>
      
      <div class="section-block">
        <div class="block-header">
          <h3>Compte de réception Mobile Money</h3>
          <span class="badge badge-success">Validé</span>
        </div>
        <div class="form-card">
          <p class="field-hint" style="margin-bottom:18px; line-height:1.6;">
            Ce numéro reçoit automatiquement votre part sur chaque billet vendu (le prix du billet, hors frais de service de 250 FCFA reversé à la plateforme). Le versement intervient sous environ 3 jours ouvrés après paiement du voyageur, via FedaPay.
          </p>
          <div class="form-grid">
            <div class="field">
              <label>Opérateur</label>
              <select><option>MTN Mobile Money</option><option>Moov Money</option><option>Celtiis Cash</option></select>
            </div>
            <div class="field">
              <label>Numéro Mobile Money</label>
              <input type="tel" value="+229 61 22 33 44">
            </div>
            <div class="field full">
              <label>Nom du titulaire du compte</label>
              <input type="text" value="Transport Confort Plus SARL">
            </div>
          </div>
          <button class="btn btn-primary">Enregistrer les modifications</button>
        </div>
      </div>
    </section>

    <!-- ============ ABONNEMENT ============ -->
    <section class="view" id="view-abonnement">
      <div class="section-block">
        <div class="block-header">
          <h3>Votre abonnement</h3>
          <span class="badge badge-success">Actif</span>
        </div>
        <div class="form-card">
          <div class="kpi-grid" style="grid-template-columns:repeat(3,1fr); margin-bottom:0;">
            <div class="kpi-card">
              <div class="kpi-label">Forfait</div>
              <div class="kpi-value" style="font-size:19px;">20 000 FCFA / mois</div>
            </div>
            <div class="kpi-card">
              <div class="kpi-label">Prochain renouvellement</div>
              <div class="kpi-value" style="font-size:19px;"><?= $abonnement && $abonnement['date_fin_periode'] ? date('d M Y', strtotime($abonnement['date_fin_periode'])) : '—' ?></div>
            </div>
            <div class="kpi-card">
              <div class="kpi-label">Statut</div>
              <?php [$classeAbo, $libelleAbo] = $abonnement ? ($libellesAbonnement[$abonnement['statut']] ?? ['badge-muted', $abonnement['statut']]) : ['badge-muted', 'Non configuré']; ?>
              <div class="kpi-value" style="font-size:19px;"><span class="badge <?= $classeAbo ?>"><?= htmlspecialchars($libelleAbo, ENT_QUOTES, 'UTF-8') ?></span></div>
            </div>
          </div>
          <p class="field-hint" style="margin:18px 0;">
            Un rappel automatique vous sera envoyé par email 7 jours puis 1 jour avant chaque échéance. En cas de non-paiement, un délai de grâce de 7 jours s'applique avant passage en mode lecture seule (vos voyages disparaissent alors de la recherche client).
          </p>
          <p class="field-hint">L'historique détaillé des paiements sera disponible prochainement.</p>
        </div>
      </div>

      <div class="section-block">
        <div class="block-header">
          <h3>Sécurité — changer mon mot de passe</h3>
        </div>
        <div class="form-card">
          <div id="pwdErreur" style="display:none; color:#dc2626; font-size:13.5px; margin-bottom:12px;"></div>
          <div id="pwdSucces" style="display:none; color:#22c55e; font-size:13.5px; margin-bottom:12px;">Mot de passe mis à jour avec succès.</div>
          <div class="form-grid" style="grid-template-columns:1fr;">
            <div class="field">
              <label for="pwdActuel">Mot de passe actuel</label>
              <input type="password" id="pwdActuel" placeholder="••••••••">
            </div>
            <div class="field">
              <label for="pwdNouveau">Nouveau mot de passe</label>
              <input type="password" id="pwdNouveau" placeholder="8 caractères, majuscule, chiffre, spécial" minlength="8">
            </div>
            <div class="field">
              <label for="pwdConfirmation">Confirmer le nouveau mot de passe</label>
              <input type="password" id="pwdConfirmation" placeholder="8 caractères, majuscule, chiffre, spécial" minlength="8">
            </div>
          </div>
          <button class="btn btn-primary" id="btnChangerMotDePasse" style="margin-top:14px;">Mettre à jour le mot de passe</button>
        </div>
      </div>
    </section>

  </div>
</div>

<!-- ============ MODAL NOUVEAU VOYAGE ============ -->
<div class="modal-overlay" id="voyageModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Nouveau voyage</h3>
      <button class="modal-close" id="closeVoyageModal">&times;</button>
    </div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="field">
          <label>Ville de départ</label>
          <select id="voyageVilleDepart">
            <?php foreach ($listeVilles as $v): ?>
            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nom'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Ville d'arrivée</label>
          <select id="voyageVilleArrivee">
            <?php foreach ($listeVilles as $v): ?>
            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nom'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Date</label>
          <input type="date" id="voyageDate">
        </div>
        <div class="field">
          <label>Heure de départ</label>
          <input type="time" id="voyageHeure">
        </div>
        <div class="field">
          <label>Durée du trajet (minutes)</label>
          <input type="number" id="voyageDuree" min="15" max="1440" placeholder="Ex : 330 (5h30)">
        </div>
        <div class="field">
          <label>Montant que vous souhaitez recevoir par billet (FCFA)</label>
          <input type="number" id="voyagePrixNet" placeholder="6600" min="1">
          <p class="field-hint" id="apercuPrixFinal" style="margin-top:6px;">Le voyageur paiera ce montant + 250 FCFA de frais de service (jamais affichés séparément).</p>
        </div>
        <div class="field">
          <label>Type de véhicule</label>
          <select id="voyageType">
            <option value="standard">Standard</option>
            <option value="climatise">Climatisé</option>
            <option value="climatise_vip">Climatisé VIP</option>
          </select>
        </div>
        <div class="field">
          <label>Gare de départ (optionnel)</label>
          <input type="text" id="voyageGare" placeholder="Ex : Gare routière Étoile Rouge, Cotonou">
        </div>
      </div>
      <p class="field-hint">Chaque voyage dispose automatiquement de 50 places numérotées de 1 à 50 (non modifiable).</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" id="cancelVoyageModal">Annuler</button>
      <button class="btn btn-primary" id="saveVoyageModal">Créer le voyage</button>
    </div>
  </div>
</div>

<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
  <span id="toastText">Voyage créé avec succès.</span>
</div>

<script>
    const CSRF_TOKEN = <?= json_encode($jetonCsrf) ?>;
    const APP_BASE = <?= json_encode(rtrim(\App\Core\Aide::urlBase(), '/'), JSON_UNESCAPED_SLASHES) ?>;
  (function(){
    const root = document.documentElement;
    if (localStorage.getItem('er-theme') === 'light') root.setAttribute('data-theme', 'light');
    const themeBtn = document.getElementById('themeToggle');
    if (themeBtn) themeBtn.addEventListener('click', () => {
      const isLight = root.getAttribute('data-theme') === 'light';
      if (isLight) { root.removeAttribute('data-theme'); localStorage.setItem('er-theme', 'dark'); }
      else { root.setAttribute('data-theme', 'light'); localStorage.setItem('er-theme', 'light'); }
    });
  })();

  // ---- navigation entre vues ----
  const navItems = document.querySelectorAll('.nav-item[data-view]');
  const views = document.querySelectorAll('.view');
  const pageTitle = document.getElementById('pageTitle');
  const titles = {
    apercu: "Vue d'ensemble", voyages: "Voyages", reservations: "Réservations",
    passagers: "Passagers", stats: "Statistiques", mobilemoney: "Mobile Money", abonnement: "Abonnement"
  };
  function goToView(name){
    navItems.forEach(n => n.classList.toggle('active', n.dataset.view === name));
    views.forEach(v => v.classList.toggle('active', v.id === 'view-' + name));
    pageTitle.textContent = titles[name] || '';
    window.scrollTo({top:0, behavior:'instant'});
  }
  navItems.forEach(item => item.addEventListener('click', (e) => {
    e.preventDefault();
    goToView(item.dataset.view);
  }));
  document.querySelectorAll('[data-goto]').forEach(el => el.addEventListener('click', (e) => {
    e.preventDefault();
    goToView(el.dataset.goto);
  }));

  // Si l'URL contient ?view=..., on ouvre directement cet onglet (utilisé
  // par le sélecteur de voyage de l'onglet Passagers, qui recharge la
  // page pour afficher le vrai plan de sièges du voyage choisi).
  (function(){
    const vueDemandee = new URLSearchParams(window.location.search).get('view');
    if (vueDemandee && titles[vueDemandee]) {
      goToView(vueDemandee);
    }
  })();

  // ---- panneau de notifications (réel, contenu rendu côté serveur) ----
  const notifBtn = document.getElementById('notifBtn');
  const notifPanel = document.getElementById('notifPanel');
  notifBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    notifPanel.classList.toggle('visible');
  });
  document.addEventListener('click', (e) => {
    if (!notifPanel.contains(e.target) && e.target !== notifBtn) {
      notifPanel.classList.remove('visible');
    }
  });

  // ---- modal nouveau voyage ----
  const modal = document.getElementById('voyageModal');
  document.getElementById('openVoyageModal').addEventListener('click', () => modal.classList.add('visible'));
  document.getElementById('closeVoyageModal').addEventListener('click', () => modal.classList.remove('visible'));
  document.getElementById('cancelVoyageModal').addEventListener('click', () => modal.classList.remove('visible'));
  modal.addEventListener('click', (e) => { if(e.target === modal) modal.classList.remove('visible'); });

  const toast = document.getElementById('toast');
  const champPrixNet = document.getElementById('voyagePrixNet');
  const apercuPrixFinal = document.getElementById('apercuPrixFinal');
  champPrixNet.addEventListener('input', () => {
    const net = Number(champPrixNet.value) || 0;
    if (net > 0) {
      const total = net + <?= config('metier.frais_service_fcfa') ?>;
      apercuPrixFinal.textContent = 'Le voyageur paiera ' + total.toLocaleString('fr-FR').replace(/,/g,' ') + ' FCFA (votre montant + 250 FCFA de frais de service, jamais affichés séparément).';
    } else {
      apercuPrixFinal.textContent = 'Le voyageur paiera ce montant + 250 FCFA de frais de service (jamais affichés séparément).';
    }
  });

  document.getElementById('saveVoyageModal').addEventListener('click', async () => {
    const corps = {
      villeDepartId: Number(document.getElementById('voyageVilleDepart').value),
      villeArriveeId: Number(document.getElementById('voyageVilleArrivee').value),
      dureeMinutes: Number(document.getElementById('voyageDuree').value),
      gareDepart: document.getElementById('voyageGare').value.trim(),
      date: document.getElementById('voyageDate').value,
      heure: document.getElementById('voyageHeure').value,
      prixNetFcfa: Number(document.getElementById('voyagePrixNet').value),
      typeVehicule: document.getElementById('voyageType').value,
    };

    const saveBtn = document.getElementById('saveVoyageModal');
    saveBtn.disabled = true;

    try {
      const reponse = await fetch(APP_BASE + '/api/compagnie/voyages', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF_TOKEN },
        body: JSON.stringify(corps),
      });
      const texteBrut = await reponse.text();
      let donnees = {};
      try {
        donnees = JSON.parse(texteBrut);
      } catch (erreurParsing) {
        // Le serveur n'a pas renvoyé du JSON valide (souvent un
        // avertissement PHP affiché avant la réponse) — on affiche le
        // texte brut pour voir la vraie cause au lieu d'un message vague.
        alert('Réponse inattendue du serveur :\n\n' + texteBrut.slice(0, 500));
        saveBtn.disabled = false;
        return;
      }

      if (!reponse.ok) {
        alert((donnees.details && donnees.details.join('\n')) || donnees.erreur || 'Une erreur est survenue.');
        saveBtn.disabled = false;
        return;
      }

      modal.classList.remove('visible');
      toast.classList.add('visible');
      setTimeout(() => window.location.reload(), 900); // recharge pour afficher le nouveau voyage
    } catch (err) {
      alert('Erreur réseau, merci de réessayer.');
      saveBtn.disabled = false;
    }
  });

  // ===== Changement de mot de passe (réel, branché à la base) =====
  document.getElementById('btnChangerMotDePasse').addEventListener('click', async () => {
    const actuel = document.getElementById('pwdActuel').value;
    const nouveau = document.getElementById('pwdNouveau').value;
    const confirmation = document.getElementById('pwdConfirmation').value;
    const erreurEl = document.getElementById('pwdErreur');
    const succesEl = document.getElementById('pwdSucces');
    erreurEl.style.display = 'none';
    succesEl.style.display = 'none';

    if (nouveau.length < 8 || !/[A-Z]/.test(nouveau) || !/[0-9]/.test(nouveau) || !/[^A-Za-z0-9]/.test(nouveau)) {
      erreurEl.textContent = 'Le nouveau mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.';
      erreurEl.style.display = 'block';
      return;
    }
    if (nouveau !== confirmation) {
      erreurEl.textContent = 'Les deux mots de passe ne correspondent pas.';
      erreurEl.style.display = 'block';
      return;
    }

    try {
      const reponse = await fetch(APP_BASE + '/api/compagnie/mot-de-passe', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF_TOKEN },
        body: JSON.stringify({ motDePasseActuel: actuel, nouveauMotDePasse: nouveau }),
      });
      const donnees = await reponse.json().catch(() => ({}));
      if (!reponse.ok) {
        erreurEl.textContent = donnees.erreur || 'Une erreur est survenue.';
        erreurEl.style.display = 'block';
        return;
      }
      succesEl.style.display = 'block';
      document.getElementById('pwdActuel').value = '';
      document.getElementById('pwdNouveau').value = '';
      document.getElementById('pwdConfirmation').value = '';
    } catch (err) {
      erreurEl.textContent = 'Erreur réseau, merci de réessayer.';
      erreurEl.style.display = 'block';
    }
  });
</script>

</body>
</html>