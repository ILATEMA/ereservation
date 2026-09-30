<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Console admin — e-réservation</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#0F0E0C;
    --ink-2:#18160F;
    --ink-3:#211E17;
    --ink-4:#2B2720;
    --amber:#E8A33D;
    --amber-deep:#C97F1E;
    --amber-dim: rgba(232,163,61,0.14);
    --cream:#F5EFE3;
    --cream-dim:#A79E8B;
    --muted:#6E6858;
    --error:#D9694F;
    --error-dim: rgba(217,105,79,0.14);
    --success:#7FAE7A;
    --success-dim: rgba(127,174,122,0.14);
    --warn:#E8C13D;
    --warn-dim: rgba(232,193,61,0.14);
    --line: rgba(245,239,227,0.08);
    --radius: 5px;
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

  body::before{
    content:'';
    position:fixed; inset:0;
    background-image:
      linear-gradient(rgba(245,239,227,0.018) 1px, transparent 1px),
      linear-gradient(90deg, rgba(245,239,227,0.018) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events:none;
    z-index:0;
  }

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
    padding:22px 22px 18px;
    border-bottom:1px solid var(--line);
  }
  .sidebar-logo img{ height:26px; }
  .sidebar-logo .fb{ font-family:'Fraunces', serif; font-weight:600; font-size:16px; }
  .sidebar-logo .fb span{ color:var(--amber); }
  .console-badge{
    padding:12px 22px 16px;
    border-bottom:1px solid var(--line);
    font-family:'IBM Plex Mono', monospace;
    font-size:10px; letter-spacing:0.1em; text-transform:uppercase;
    color:var(--amber);
    display:flex; align-items:center; gap:7px;
  }
  .console-badge::before{ content:''; width:6px; height:6px; border-radius:50%; background:var(--amber); box-shadow:0 0 0 3px var(--amber-dim); }

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
    position:relative;
  }
  .nav-item svg{ width:16px; height:16px; flex-shrink:0; opacity:0.85; }
  .nav-item:hover{ background:var(--ink-3); color:var(--cream); }
  .nav-item.active{ background:var(--amber-dim); color:var(--amber); border-left-color:var(--amber); }
  .nav-item.active svg{ opacity:1; }
  .nav-badge{
    margin-left:auto;
    background:var(--error);
    color:var(--ink);
    font-family:'IBM Plex Mono', monospace;
    font-size:10px; font-weight:700;
    padding:1px 6px; border-radius:10px;
  }

  .sidebar-footer{ padding:14px 12px; border-top:1px solid var(--line); }
  .logout-link{
    display:flex; align-items:center; gap:10px;
    padding:10px 12px; border-radius:var(--radius);
    color:var(--muted); font-size:13px;
    transition:color .15s ease, background .15s ease;
  }
  .logout-link:hover{ color:var(--error); background:var(--error-dim); }
  .logout-link svg{ width:15px; height:15px; }

  /* ============ MAIN ============ */
  .main{ margin-left:var(--sidebar-w); flex:1; display:flex; flex-direction:column; min-width:0; position:relative; z-index:1; }
  .topbar{
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 32px; border-bottom:1px solid var(--line);
    background:var(--ink); position:sticky; top:0; z-index:10;
  }
  .topbar h1{ font-family:'Fraunces', serif; font-weight:600; font-size:21px; }
  .topbar-right{ display:flex; align-items:center; gap:16px; }
  .icon-btn{
    width:36px; height:36px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    background:var(--ink-2); border:1px solid var(--line);
    color:var(--cream-dim); position:relative;
  }
  .icon-btn:hover{ color:var(--cream); border-color:var(--amber); }
  .icon-btn .icon-sun{display:none;}
  html[data-theme="light"] .icon-btn .icon-moon{display:none;}
  html[data-theme="light"] .icon-btn .icon-sun{display:block;}
  .icon-btn svg{ width:16px; height:16px; }
  .notif-dot{ position:absolute; top:6px; right:6px; width:7px; height:7px; border-radius:50%; background:var(--error); border:1.5px solid var(--ink); }
  .avatar{ width:36px; height:36px; border-radius:50%; background:var(--amber); color:var(--ink); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; }

  .content{ padding:28px 32px 60px; }
  .view{ display:none; }
  .view.active{ display:block; animation:fadein .25s ease; }
  @keyframes fadein{ from{opacity:0; transform:translateY(4px);} to{opacity:1; transform:none;} }

  .alert-banner{
    display:flex; align-items:center; gap:12px;
    background:var(--error-dim); border:1px solid rgba(217,105,79,0.3); color:var(--error);
    padding:13px 16px; border-radius:var(--radius); font-size:13px; margin-bottom:16px;
  }
  .alert-banner svg{ width:17px; height:17px; flex-shrink:0; }
  .alert-banner a{ color:var(--amber); font-weight:600; text-decoration:underline; margin-left:auto; flex-shrink:0; }
  .alert-banner.warn{ background:var(--warn-dim); border-color:rgba(232,193,61,0.3); color:var(--warn); }

  .kpi-grid{ display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px; }
  .kpi-card{ background:var(--ink-2); border:1px solid var(--line); border-radius:var(--radius); padding:18px 20px; }
  .kpi-label{ font-family:'IBM Plex Mono', monospace; font-size:10.5px; letter-spacing:0.06em; text-transform:uppercase; color:var(--muted); margin-bottom:10px; }
  .kpi-value{ font-family:'Fraunces', serif; font-weight:600; font-size:26px; margin-bottom:6px; }
  .kpi-delta{ font-size:11.5px; color:var(--success); display:flex; align-items:center; gap:4px; }
  .kpi-delta.down{ color:var(--error); }
  .kpi-delta.neutral{ color:var(--muted); }

  .section-block{ background:var(--ink-2); border:1px solid var(--line); border-radius:var(--radius); margin-bottom:22px; overflow:hidden; }
  .block-header{ display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid var(--line); }
  .block-header h3{ font-family:'Fraunces', serif; font-weight:600; font-size:16px; }
  .block-header .see-all{ font-size:12px; color:var(--amber); }

  .btn{ display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:var(--radius); font-size:13px; font-weight:600; border:none; transition:background .2s ease, transform .15s ease; }
  .btn svg{ width:14px; height:14px; }
  .btn-primary{ background:var(--amber); color:var(--ink); }
  .btn-primary:hover{ background:var(--amber-deep); }
  .btn-ghost{ background:var(--ink-3); color:var(--cream); border:1px solid var(--line); }
  .btn-ghost:hover{ border-color:var(--amber); color:var(--amber); }
  .btn-danger{ background:var(--error-dim); color:var(--error); border:1px solid rgba(217,105,79,0.3); }
  .btn-danger:hover{ background:var(--error); color:var(--ink); }
  .btn-sm{ padding:6px 11px; font-size:12px; }
  .btn:active{ transform:scale(0.98); }

  .table-wrap{ overflow-x:auto; }
  table{ width:100%; border-collapse:collapse; }
  thead th{ text-align:left; font-family:'IBM Plex Mono', monospace; font-size:10.5px; letter-spacing:0.06em; text-transform:uppercase; color:var(--muted); padding:11px 20px; border-bottom:1px solid var(--line); white-space:nowrap; }
  tbody td{ padding:14px 20px; border-bottom:1px solid var(--line); font-size:13.5px; white-space:nowrap; }
  tbody tr:last-child td{ border-bottom:none; }
  tbody tr{ transition:background .15s ease; }
  tbody tr:hover{ background:var(--ink-3); }
  .cell-muted{ color:var(--muted); font-size:12.5px; }
  .cell-mono{ font-family:'IBM Plex Mono', monospace; font-size:12px; color:var(--cream-dim); }
  .company-cell{ display:flex; align-items:center; gap:10px; font-weight:500; }
  .company-avatar{ width:28px; height:28px; border-radius:7px; background:var(--ink-4); display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono', monospace; font-size:11px; color:var(--amber); flex-shrink:0; }

  .badge{ display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; font-family:'IBM Plex Mono', monospace; letter-spacing:0.02em; }
  .badge::before{ content:''; width:6px; height:6px; border-radius:50%; }
  .badge-success{ background:var(--success-dim); color:var(--success); } .badge-success::before{ background:var(--success); }
  .badge-warn{ background:var(--warn-dim); color:var(--warn); } .badge-warn::before{ background:var(--warn); }
  .badge-error{ background:var(--error-dim); color:var(--error); } .badge-error::before{ background:var(--error); }
  .badge-muted{ background:var(--ink-3); color:var(--muted); } .badge-muted::before{ background:var(--muted); }

  .action-icons{ display:flex; gap:6px; }
  .action-icon{ width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; background:var(--ink-3); border:1px solid var(--line); color:var(--cream-dim); }
  .action-icon:hover{ color:var(--amber); border-color:var(--amber); }
  .action-icon svg{ width:13px; height:13px; }

  .filter-row{ display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid var(--line); flex-wrap:wrap; }
  select, .search-input{ background:var(--ink-3); border:1px solid var(--line); border-radius:var(--radius); padding:8px 12px; color:var(--cream); font-size:12.5px; font-family:'Inter', sans-serif; outline:none; }
  select:focus, .search-input:focus{ border-color:var(--amber); }
  .search-input{ min-width:220px; }

  .two-col{ display:grid; grid-template-columns:1.4fr 1fr; gap:20px; align-items:start; }

  .audit-log{ padding:6px 0; }
  .audit-item{ display:flex; gap:14px; padding:13px 20px; border-bottom:1px solid var(--line); font-size:12.5px; }
  .audit-item:last-child{ border-bottom:none; }
  .audit-time{ font-family:'IBM Plex Mono', monospace; color:var(--muted); flex-shrink:0; width:78px; }
  .audit-text strong{ color:var(--amber); font-weight:600; }
  .audit-text{ color:var(--cream-dim); line-height:1.5; }

  .form-card{ padding:22px; }
  .form-grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px; }
  .field{ display:flex; flex-direction:column; gap:7px; }
  .field.full{ grid-column:1 / -1; }
  .field label{ font-size:12px; font-weight:600; color:var(--cream-dim); }
  .field input, .field select{ background:var(--ink-3); border:1px solid var(--line); border-radius:var(--radius); padding:10px 13px; color:var(--cream); font-size:13.5px; outline:none; }
  .field input:focus, .field select:focus{ border-color:var(--amber); }
  .field-hint{ font-size:11px; color:var(--muted); }

  .modal-overlay{ display:none; position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.65); align-items:center; justify-content:center; padding:24px; }
  .modal-overlay.visible{ display:flex; }
  .modal{ background:var(--ink-2); border:1px solid var(--line); border-radius:10px; width:100%; max-width:480px; max-height:90vh; overflow-y:auto; }
  .modal-header{ display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid var(--line); }
  .modal-header h3{ font-family:'Fraunces', serif; font-weight:600; font-size:17px; }
  .modal-close{ background:none; border:none; color:var(--muted); font-size:20px; line-height:1; }
  .modal-close:hover{ color:var(--cream); }
  .modal-body{ padding:22px; font-size:13.5px; color:var(--cream-dim); line-height:1.6; }
  .modal-body strong{ color:var(--cream); }
  .modal-footer{ display:flex; justify-content:flex-end; gap:10px; padding:16px 22px; border-top:1px solid var(--line); }

  .toast{
    position:fixed; bottom:24px; right:24px; z-index:200;
    background:var(--ink-3); border:1px solid var(--success); color:var(--cream);
    padding:12px 18px; border-radius:var(--radius); font-size:13px;
    display:none; align-items:center; gap:9px; box-shadow:0 20px 50px -10px rgba(0,0,0,0.5);
  }
  .toast.visible{ display:flex; }
  .toast svg{ width:15px; height:15px; color:var(--success); }

  @media (max-width: 1100px){ .kpi-grid{ grid-template-columns:repeat(2, 1fr); } .two-col{ grid-template-columns:1fr; } }
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

<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <img src="<?= url_app('/assets/logo.png') ?>" alt="" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
    <span class="fb" style="display:none;">e-r<span>é</span>servation</span>
  </div>
  <div class="console-badge">Console super-admin</div>
  <nav class="sidebar-nav">
    <a class="nav-item active" data-view="apercu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
      Vue plateforme
    </a>
    <a class="nav-item" data-view="compagnies">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M6 21V8l6-4 6 4v13M9 21v-6h6v6"/></svg>
      Compagnies <span class="nav-badge">2</span>
    </a>
    <a class="nav-item" data-view="itineraires">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 20l-5.5-2V4L9 6m0 14l6-2m-6 2V6m6 12l5.5 2V6L15 4m0 14V4m0 2L9 4"/></svg>
      Itinéraires
    </a>
    <a class="nav-item" data-view="reservations">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/></svg>
      Réservations
    </a>
    <a class="nav-item" data-view="paiements">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="13" rx="2"/><path d="M2 10h20"/><circle cx="7" cy="14.5" r="1"/></svg>
      Paiements <span class="nav-badge">1</span>
    </a>
    <a class="nav-item" data-view="rapports">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18M8 17V10M13 17V6M18 17v-4"/></svg>
      Rapports
    </a>
    <a class="nav-item" data-view="audit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      Journal d'audit
    </a>
  </nav>
  <div class="sidebar-footer">
    <a href="<?= url_app('/deconnexion?espace=admin') ?>" class="logout-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      Déconnexion
    </a>
  </div>
</aside>

<div class="main">
  <div class="topbar">
    <h1 id="pageTitle">Vue plateforme</h1>
    <div class="topbar-right">
      <button class="icon-btn" id="themeToggle" title="Mode clair / sombre">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
      </button>
      <button class="icon-btn" title="Notifications">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 01-3.4 0"/></svg>
        <span class="notif-dot"></span>
      </button>
      <div class="avatar">SA</div>
    </div>
  </div>

  <div class="content">

    <!-- ============ VUE PLATEFORME ============ -->
    <section class="view active" id="view-apercu">
      <div class="alert-banner">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
        1 paiement webhook en échec nécessite une vérification manuelle.
        <a href="#" data-goto="paiements">Voir les paiements</a>
      </div>
      <div class="alert-banner warn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
        2 compagnies en attente de validation manuelle pour activer leur essai gratuit.
        <a href="#" data-goto="compagnies">Voir les compagnies</a>
      </div>

      <div class="kpi-grid">
        <div class="kpi-card">
          <div class="kpi-label">Frais de service perçus (mois)</div>
          <div class="kpi-value"><?= htmlspecialchars(\App\Core\Aide::formaterFcfa($revenuFraisMois), ENT_QUOTES, 'UTF-8') ?></div>
          <div class="kpi-delta neutral">250 FCFA × <?= $billetsVendusMois ?> billets confirmés</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Compagnies actives</div>
          <div class="kpi-value"><?= $nbCompagniesActives ?></div>
          <div class="kpi-delta neutral">dont <?= $nbCompagniesEnEssai ?> en période d'essai</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Billets confirmés (mois)</div>
          <div class="kpi-value"><?= number_format($billetsVendusMois, 0, ',', ' ') ?></div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">Voyageurs uniques (mois)</div>
          <div class="kpi-value"><?= number_format($voyageursUniques, 0, ',', ' ') ?></div>
          <div class="kpi-delta neutral">Estimation par numéro de téléphone</div>
        </div>
      </div>

      <div class="two-col">
        <div class="section-block">
          <div class="block-header">
            <h3>Compagnies à surveiller</h3>
            <a class="see-all" href="#" data-goto="compagnies">Tout voir →</a>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Compagnie</th><th>Statut</th><th>Échéance</th></tr></thead>
              <tbody>
                <?php foreach (array_slice($compagnies, 0, 5) as $c): [$classe, $libelle] = \App\Models\TableauDeBordAdmin::badgeStatutCompagnie($c); ?>
                <tr>
                  <td class="company-cell"><span class="company-avatar"><?= htmlspecialchars(\App\Models\TableauDeBordAdmin::initiales($c['nom']), ENT_QUOTES, 'UTF-8') ?></span><?= htmlspecialchars($c['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="badge <?= $classe ?>"><?= $libelle ?></span></td>
                  <td class="cell-muted"><?= $c['date_fin_periode'] ? 'Échéance ' . date('d/m/Y', strtotime($c['date_fin_periode'])) : '—' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if ($compagnies === []): ?>
                <tr><td colspan="3" class="cell-muted">Aucune compagnie enregistrée pour l'instant.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="section-block">
          <div class="block-header"><h3>Activité récente</h3></div>
          <div class="audit-log">
            <div class="audit-item"><div class="audit-time">10:42</div><div class="audit-text"><strong>Sahel Liner</strong> passée en lecture seule (délai de grâce expiré)</div></div>
            <div class="audit-item"><div class="audit-time">09:15</div><div class="audit-text"><strong>Grand Voyageur</strong> a soumis sa demande de partenariat</div></div>
            <div class="audit-item"><div class="audit-time">08:03</div><div class="audit-text">Webhook FedaPay en échec sur transaction <span class="cell-mono">TX-88213</span></div></div>
            <div class="audit-item"><div class="audit-time">Hier</div><div class="audit-text"><strong>Rapido Bénin</strong> renouvellement automatique confirmé</div></div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ COMPAGNIES ============ -->
    <section class="view" id="view-compagnies">
      <div class="section-block">
        <div class="filter-row">
          <input class="search-input" type="text" placeholder="Rechercher une compagnie…">
          <select><option>Tous les statuts</option><option>Actif</option><option>Essai gratuit</option><option>Lecture seule</option><option>Suspendu</option><option>En attente de validation</option></select>
          <button class="btn btn-ghost" style="margin-left:auto;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15V3M7 10l5 5 5-5M4 21h16"/></svg>
            Exporter
          </button>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Compagnie</th><th>Statut</th><th>Voyages actifs</th><th>Depuis</th><th></th></tr></thead>
            <tbody>
              <?php foreach ($compagnies as $c): [$classe, $libelle] = \App\Models\TableauDeBordAdmin::badgeStatutCompagnie($c); ?>
              <tr data-compagnie-id="<?= $c['id'] ?>">
                <td class="company-cell"><span class="company-avatar"><?= htmlspecialchars(\App\Models\TableauDeBordAdmin::initiales($c['nom']), ENT_QUOTES, 'UTF-8') ?></span><?= htmlspecialchars($c['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><span class="badge <?= $classe ?>"><?= $libelle ?></span></td>
                <td class="cell-mono"><?= $c['voyages_actifs'] ?></td>
                <td class="cell-muted"><?= date('M Y', strtotime($c['created_at'])) ?></td>
                <td><div class="action-icons">
                  <?php if ($c['statut'] === 'en_attente_validation'): ?>
                  <a class="action-icon" href="<?= url_app('/admin/compagnies/' . (int) $c['id']) ?>" title="Voir les détails et décider"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg></a>
                  <?php else: ?>
                  <a class="action-icon" href="<?= url_app('/admin/compagnies/' . (int) $c['id']) ?>" title="Voir les détails"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg></a>
                  <?php endif; ?>
                </div></td>
              </tr>
              <?php endforeach; ?>
              <?php if ($compagnies === []): ?>
              <tr><td colspan="5" class="cell-muted">Aucune compagnie enregistrée pour l'instant.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ ITINERAIRES ============ -->
    <section class="view" id="view-itineraires">
      <div class="alert-banner warn" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg> Cet onglet affiche encore des données de démonstration, pas encore relié à la base. À faire ensuite.</div>
    
      <div class="section-block">
        <div class="filter-row">
          <input class="search-input" type="text" placeholder="Rechercher une ville…">
          <button class="btn btn-primary" style="margin-left:auto;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Nouvel itinéraire
          </button>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Départ</th><th>Arrivée</th><th>Distance approx.</th><th>Compagnies actives</th><th>Statut</th></tr></thead>
            <tbody>
              <tr><td>Cotonou</td><td>Parakou</td><td class="cell-muted">410 km</td><td class="cell-mono">4</td><td><span class="badge badge-success">Actif</span></td></tr>
              <tr><td>Cotonou</td><td>Natitingou</td><td class="cell-muted">590 km</td><td class="cell-mono">2</td><td><span class="badge badge-success">Actif</span></td></tr>
              <tr><td>Porto-Novo</td><td>Bohicon</td><td class="cell-muted">115 km</td><td class="cell-mono">3</td><td><span class="badge badge-success">Actif</span></td></tr>
              <tr><td>Cotonou</td><td>Abomey</td><td class="cell-muted">145 km</td><td class="cell-mono">5</td><td><span class="badge badge-success">Actif</span></td></tr>
              <tr><td>Cotonou</td><td>Djougou</td><td class="cell-muted">480 km</td><td class="cell-mono">0</td><td><span class="badge badge-muted">Inactif</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ RESERVATIONS (supervision globale) ============ -->
    <section class="view" id="view-reservations">
      <div class="alert-banner warn" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg> Cet onglet affiche encore des données de démonstration, pas encore relié à la base. À faire ensuite.</div>
    
      <div class="section-block">
        <div class="filter-row">
          <input class="search-input" type="text" placeholder="Code réservation, nom ou téléphone…">
          <select><option>Toutes les compagnies</option><option>Transport Confort Plus</option><option>Rapido Bénin</option><option>Sahel Liner</option></select>
          <select><option>Tous les statuts</option><option>Payée</option><option>En attente</option><option>Annulée</option></select>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Code</th><th>Compagnie</th><th>Passager</th><th>Trajet</th><th>Statut</th></tr></thead>
            <tbody>
              <tr><td class="cell-mono">ER-4K9P2X7A</td><td class="cell-muted">Transport Confort Plus</td><td>Amina Souley</td><td class="cell-muted">Cotonou → Parakou</td><td><span class="badge badge-success">Payée</span></td></tr>
              <tr><td class="cell-mono">ER-7T2M8L1Q</td><td class="cell-muted">Rapido Bénin</td><td>Roland Adjovi</td><td class="cell-muted">Cotonou → Natitingou</td><td><span class="badge badge-success">Payée</span></td></tr>
              <tr><td class="cell-mono">ER-9X3B6D0F</td><td class="cell-muted">Transport Confort Plus</td><td>Chantal Hounkpè</td><td class="cell-muted">Porto-Novo → Bohicon</td><td><span class="badge badge-warn">En attente</span></td></tr>
              <tr><td class="cell-mono">ER-1W5N4G8H</td><td class="cell-muted">Sahel Liner</td><td>Ibrahim Dosso</td><td class="cell-muted">Cotonou → Djougou</td><td><span class="badge badge-error">Annulée</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ PAIEMENTS ============ -->
    <section class="view" id="view-paiements">
      <div class="alert-banner warn" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg> Cet onglet affiche encore des données de démonstration, pas encore relié à la base. À faire ensuite.</div>
    
      <div class="alert-banner">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
        Transaction TX-88213 : webhook FedaPay non confirmé après 3 tentatives. Vérification manuelle requise.
      </div>
      <div class="section-block">
        <div class="filter-row">
          <select><option>Tous les statuts</option><option>Réussi</option><option>En attente</option><option>Échoué</option></select>
          <select><option>Toutes les compagnies</option><option>Transport Confort Plus</option><option>Rapido Bénin</option></select>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Transaction</th><th>Compagnie</th><th>Montant</th><th>Frais plateforme</th><th>Statut</th></tr></thead>
            <tbody>
              <tr><td class="cell-mono">TX-88201</td><td class="cell-muted">Transport Confort Plus</td><td class="cell-mono">6 600 FCFA</td><td class="cell-mono">250 FCFA</td><td><span class="badge badge-success">Réussi</span></td></tr>
              <tr><td class="cell-mono">TX-88207</td><td class="cell-muted">Rapido Bénin</td><td class="cell-mono">8 500 FCFA</td><td class="cell-mono">250 FCFA</td><td><span class="badge badge-success">Réussi</span></td></tr>
              <tr><td class="cell-mono">TX-88213</td><td class="cell-muted">Sahel Liner</td><td class="cell-mono">3 200 FCFA</td><td class="cell-mono">250 FCFA</td><td><span class="badge badge-error">Échoué</span></td></tr>
              <tr><td class="cell-mono">TX-88219</td><td class="cell-muted">Transport Confort Plus</td><td class="cell-mono">4 000 FCFA</td><td class="cell-mono">250 FCFA</td><td><span class="badge badge-warn">En attente</span></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ============ RAPPORTS ============ -->
    <section class="view" id="view-rapports">
      <div class="alert-banner warn" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg> Cet onglet affiche encore des données de démonstration, pas encore relié à la base. À faire ensuite.</div>
    
      <div class="section-block">
        <div class="block-header"><h3>Rapports disponibles</h3></div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Rapport</th><th>Période</th><th></th></tr></thead>
            <tbody>
              <tr><td>Revenu plateforme (frais + abonnements)</td><td class="cell-muted">Août 2026</td><td><button class="btn btn-ghost btn-sm">Générer</button></td></tr>
              <tr><td>Ventes par compagnie</td><td class="cell-muted">Août 2026</td><td><button class="btn btn-ghost btn-sm">Générer</button></td></tr>
              <tr><td>Réservations et annulations</td><td class="cell-muted">Août 2026</td><td><button class="btn btn-ghost btn-sm">Générer</button></td></tr>
              <tr><td>Compagnies en essai / à risque de churn</td><td class="cell-muted">Temps réel</td><td><button class="btn btn-ghost btn-sm">Générer</button></td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <p class="field-hint">L'export détaillé (CSV/PDF) sera activé lors du branchement au backend.</p>
    </section>

    <!-- ============ JOURNAL D'AUDIT ============ -->
    <section class="view" id="view-audit">
      <div class="alert-banner warn" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg> Cet onglet affiche encore des données de démonstration, pas encore relié à la base. À faire ensuite.</div>
    
      <div class="section-block">
        <div class="block-header"><h3>Journal des actions super-admin</h3></div>
        <div class="audit-log">
          <div class="audit-item"><div class="audit-time">Auj. 10:42</div><div class="audit-text"><strong>admin@e-reservation.bj</strong> a suspendu <strong>Sahel Liner</strong> (délai de grâce expiré, 7 jours)</div></div>
          <div class="audit-item"><div class="audit-time">Auj. 09:15</div><div class="audit-text"><strong>admin@e-reservation.bj</strong> a consulté la demande de partenariat de <strong>Grand Voyageur</strong></div></div>
          <div class="audit-item"><div class="audit-time">Hier 16:20</div><div class="audit-text"><strong>admin@e-reservation.bj</strong> a validé l'essai gratuit de <strong>Rapido Bénin</strong></div></div>
          <div class="audit-item"><div class="audit-time">17 août</div><div class="audit-text"><strong>admin@e-reservation.bj</strong> a modifié l'itinéraire <strong>Cotonou → Djougou</strong> (statut → inactif)</div></div>
          <div class="audit-item"><div class="audit-time">15 août</div><div class="audit-text"><strong>admin@e-reservation.bj</strong> a créé le compte compagnie <strong>Sahel Liner</strong></div></div>
        </div>
      </div>
      <p class="field-hint">Chaque action super-admin est journalisée (qui / quoi / quand) à des fins de traçabilité et de résolution de litiges.</p>
    </section>

  </div>
</div>

<!-- ============ MODAL ACTION COMPAGNIE ============ -->
<div class="modal-overlay" id="actionModal">
  <div class="modal">
    <div class="modal-header">
      <h3 id="modalTitle">Confirmer l'action</h3>
      <button class="modal-close" id="closeActionModal">&times;</button>
    </div>
    <div class="modal-body" id="modalBody">
      Confirmez-vous cette action ?
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" id="cancelActionModal">Annuler</button>
      <button class="btn btn-primary" id="confirmActionModal">Confirmer</button>
    </div>
  </div>
</div>

<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
  <span id="toastText">Action effectuée.</span>
</div>

<script>
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
    apercu: "Vue plateforme", compagnies: "Compagnies", itineraires: "Itinéraires",
    reservations: "Réservations", paiements: "Paiements", rapports: "Rapports", audit: "Journal d'audit"
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

  // ---- modal actions compagnie (suspendre / réactiver / valider) ----
  const actionModal = document.getElementById('actionModal');
  const modalTitle = document.getElementById('modalTitle');
  const modalBody = document.getElementById('modalBody');
  const toast = document.getElementById('toast');
  const toastText = document.getElementById('toastText');
  let pendingAction = null;

  const actionConfig = {
    suspend: {
      title: "Suspendre la compagnie",
      body: (name) => `Vous êtes sur le point de suspendre <strong>${name}</strong>. Ses voyages disparaîtront immédiatement de la recherche client. Cette action est journalisée dans le journal d'audit.`,
      toast: (name) => `${name} a été suspendue.`
    },
    reactivate: {
      title: "Réactiver la compagnie",
      body: (name) => `Réactiver <strong>${name}</strong> lui redonnera immédiatement accès à la vente en ligne. Un paiement Mobile Money confirmé sera requis si la suspension dépasse 30 jours.`,
      toast: (name) => `${name} a été réactivée.`
    },
    validate: {
      title: "Valider l'essai gratuit",
      body: (name) => `Valider <strong>${name}</strong> activera son essai gratuit de 1 mois et lui donnera accès à l'espace compagnie.`,
      toast: (name) => `Essai gratuit activé pour ${name}.`
    }
  };

  document.querySelectorAll('[data-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
      const type = btn.dataset.modal;
      const name = btn.dataset.name;
      const config = actionConfig[type];
      if(!config) return;
      pendingAction = { type, name, config };
      modalTitle.textContent = config.title;
      modalBody.innerHTML = config.body(name);
      actionModal.classList.add('visible');
    });
  });

  document.getElementById('closeActionModal').addEventListener('click', () => actionModal.classList.remove('visible'));
  document.getElementById('cancelActionModal').addEventListener('click', () => actionModal.classList.remove('visible'));
  actionModal.addEventListener('click', (e) => { if(e.target === actionModal) actionModal.classList.remove('visible'); });

  document.getElementById('confirmActionModal').addEventListener('click', () => {
    if(!pendingAction) return;
    // Note : plus aucun bouton du tableau ne déclenche cette modale
    // (suspendre/réactiver n'existent plus dans l'UI — remplacés par
    // les vrais boutons valider/rejeter, branchés plus bas). Ce
    // gestionnaire ne sera jamais appelé, laissé en place uniquement
    // au cas où une future action l'utiliserait à nouveau.
    actionModal.classList.remove('visible');
    toastText.textContent = pendingAction.config.toast(pendingAction.name);
    toast.classList.add('visible');
    setTimeout(() => toast.classList.remove('visible'), 3200);
    pendingAction = null;
  });

  // Les actions valider/rejeter une compagnie se font désormais depuis
  // la page de détail (admin-compagnie-detail.php), pas depuis ce
  // tableau — voir le lien "Voir détails" sur chaque ligne en attente.
</script>

</body>
</html>
