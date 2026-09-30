<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Votre billet — e-réservation</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --ink-950:#0a0c10; --ink-900:#12151b; --ink-800:#1c212b; --ink-700:#2a303c; --ink-600:#3a4150;
  --amber-300:#ffcb6b; --amber-400:#ffb238; --amber-500:#f59e0b; --amber-600:#c8790a;
  --paper:#f3ecd9; --paper-dim:#e3dabf; --text-hi:#f6f2e7; --text-mid:#aeb4c0; --text-low:#767d8c;
  --line:#2a303c; --radius-card:6px;
  --success:#7FAE7A; --success-dim:rgba(127,174,122,0.14);
  --error:#D9694F; --error-dim:rgba(217,105,79,0.14);
}
html[data-theme="light"]{
  --ink-950:#faf7f0; --ink-900:#f1ebdc; --ink-800:#e8e0cb; --ink-700:#d9cfb0; --ink-600:#c4b896;
  --text-hi:#14171c; --text-mid:#4b5160; --text-low:#6b7280; --line:#d9cfb0;
}
*{box-sizing:border-box; margin:0; padding:0;}
html{scroll-behavior:smooth;}
body{background:var(--ink-950); color:var(--text-hi); font-family:'Inter',sans-serif; line-height:1.5; -webkit-font-smoothing:antialiased;}
h1,h2,h3{font-family:'Fraunces',serif; font-weight:600; letter-spacing:-0.01em; color:var(--text-hi);}
.mono{font-family:'IBM Plex Mono',monospace; letter-spacing:0.02em;}
a{color:inherit; text-decoration:none;}
button{font-family:inherit; cursor:pointer;}
:focus-visible{outline:2px solid var(--amber-400); outline-offset:3px;}
.wrap{max-width:1180px; margin:0 auto; padding:0 32px;}

/* HEADER */
header{position:sticky; top:0; z-index:50; background:rgba(10,12,16,0.92); backdrop-filter:blur(10px); border-bottom:1px solid var(--line);}
.header-inner{display:flex; align-items:center; justify-content:space-between; padding:16px 32px; max-width:1180px; margin:0 auto;}
.brand{display:flex; flex-direction:column; gap:2px;}
.brand-name{font-family:'Fraunces',serif; font-size:20px; font-weight:700; color:var(--text-hi); display:flex; align-items:center; gap:8px;}
.brand-name img.logo-mark{height:36px; width:auto; display:block;}
.brand-slogan{font-size:10.5px; font-family:'IBM Plex Mono',monospace; color:var(--text-low); text-transform:uppercase; letter-spacing:0.1em;}
nav{display:flex; align-items:center; gap:28px;}
.nav-links{display:flex; gap:22px; align-items:center;}
.nav-links a{font-size:14px; color:var(--text-mid); font-weight:500; position:relative; padding:4px 0; transition:color .15s ease;}
.nav-links a:hover{color:var(--text-hi);}
.theme-toggle{display:flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:50%; background:var(--ink-800); border:1px solid var(--line); color:var(--text-mid); transition:color .15s ease, border-color .15s ease; margin-left:4px;}
.theme-toggle:hover{color:var(--amber-400); border-color:var(--amber-400);}
.theme-toggle svg{width:16px; height:16px;}
.theme-toggle .icon-sun{display:none;}
html[data-theme="light"] .theme-toggle .icon-moon{display:none;}
html[data-theme="light"] .theme-toggle .icon-sun{display:block;}
.nav-toggle{display:none; background:none; border:none; color:var(--text-hi); font-size:24px;}

/* STEP TRACKER (parcours d'achat) */
.step-tracker{background:var(--ink-900); border-bottom:1px solid var(--line);}
.step-tracker .wrap{display:flex; align-items:center; gap:0; padding-top:16px; padding-bottom:16px; overflow-x:auto;}
.st-item{display:flex; align-items:center; gap:9px; color:var(--text-low); font-size:12.5px; white-space:nowrap; flex-shrink:0;}
.st-num{width:22px; height:22px; border-radius:50%; border:1.5px solid var(--line); display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono',monospace; font-size:11px; flex-shrink:0;}
.st-item.done .st-num{background:var(--success); border-color:var(--success); color:var(--ink-950);}
.st-item.active .st-num{background:var(--amber-400); border-color:var(--amber-400); color:var(--ink-950);}
.st-item.active{color:var(--text-hi); font-weight:600;}
.st-item.done{color:var(--text-mid);}
.st-sep{width:26px; height:1px; background:var(--line); margin:0 6px; flex-shrink:0;}

/* BUTTONS */
.btn{display:inline-flex; align-items:center; justify-content:center; gap:8px; font-weight:700; font-size:14px; padding:13px 22px; border-radius:5px; border:none; transition:all .15s ease; white-space:nowrap;}
.btn-primary{background:var(--amber-400); color:var(--ink-950);}
.btn-primary:hover{background:var(--amber-300);}
.btn-primary:disabled{background:var(--ink-700); color:var(--text-low); cursor:not-allowed;}
.btn-ghost{background:transparent; border:1.5px solid var(--line); color:var(--text-hi);}
.btn-ghost:hover{border-color:var(--amber-400); color:var(--amber-400);}
.btn-outline-amber{background:transparent; border:1.5px solid var(--amber-400); color:var(--amber-400);}
.btn-outline-amber:hover{background:var(--amber-400); color:var(--ink-950);}
.btn-block{width:100%;}

/* CARD / PANEL */
.panel{background:var(--ink-900); border:1px solid var(--line); border-radius:8px; padding:26px;}
.panel h2{font-size:19px; margin-bottom:4px;}
.panel .sub{color:var(--text-mid); font-size:13.5px; margin-bottom:18px;}

/* PERFORATION DIVIDER */
.perf-divider{display:flex; align-items:center; gap:6px; padding:0; opacity:0.5;}
.perf-divider span{width:5px; height:5px; border-radius:50%; background:var(--ink-700); flex-shrink:0;}
.perf-divider .line{flex:1; height:1px; background:var(--line);}

/* FIELD */
.field{display:flex; flex-direction:column; gap:6px; margin-bottom:16px;}
.field label{font-family:'IBM Plex Mono',monospace; font-size:11px; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-low);}
.field input, .field select{background:var(--ink-800); border:1.5px solid var(--line); color:var(--text-hi); font-family:'Inter',sans-serif; font-size:14.5px; padding:11px 13px; border-radius:5px; width:100%;}
.field input:focus, .field select:focus{outline:none; border-color:var(--amber-400);}
.field input::placeholder{color:var(--text-low);}
.field-row{display:grid; grid-template-columns:1fr 1fr; gap:14px;}
.hint{font-size:12px; color:var(--text-low); margin-top:-10px; margin-bottom:14px;}

/* BADGE */
.badge{display:inline-flex; align-items:center; gap:5px; font-family:'IBM Plex Mono',monospace; font-size:11px; font-weight:600; padding:4px 9px; border-radius:100px; text-transform:uppercase; letter-spacing:0.04em;}
.badge-amber{background:rgba(255,178,56,0.14); color:var(--amber-400);}
.badge-success{background:var(--success-dim); color:var(--success);}
.badge-error{background:var(--error-dim); color:var(--error);}

/* FOOTER (compact) */
footer{padding:48px 0 0; background:var(--ink-950); border-top:1px solid var(--line); margin-top:64px;}
.footer-bottom{display:flex; justify-content:space-between; align-items:center; padding:22px 0 28px; flex-wrap:wrap; gap:10px; max-width:1180px; margin:0 auto; padding-left:32px; padding-right:32px;}
.footer-bottom p{font-size:12px; color:var(--text-low);}
.footer-bottom a{font-size:12px; color:var(--text-low);}
.footer-bottom a:hover{color:var(--amber-400);}
.footer-top{display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; padding-bottom:28px; border-bottom:1px solid var(--line); max-width:1180px; margin:0 auto; padding-left:32px; padding-right:32px;}
.footer-top .brand-name img.logo-mark{height:30px;}
.footer-links{display:flex; gap:22px; flex-wrap:wrap;}
.footer-links a{font-size:13px; color:var(--text-mid);}
.footer-links a:hover{color:var(--amber-400);}

@media (max-width:900px){
  .nav-links{display:none;}
  .field-row{grid-template-columns:1fr;}
}
@media print{
  header, footer, .step-tracker, .no-print{display:none !important;}
}


.confirm-banner{display:flex; align-items:center; gap:12px; background:var(--success-dim); border:1px solid var(--success); color:var(--success); padding:14px 20px; border-radius:8px; margin:28px 0 24px; font-size:14px; font-weight:600;}
.ticket-wrap{max-width:760px; margin:0 auto 60px;}
.ticket{background:var(--paper); color:var(--ink-950); border-radius:10px; overflow:hidden; box-shadow:0 30px 60px -20px rgba(0,0,0,0.5);}
.ticket-top{padding:26px 30px; display:flex; justify-content:space-between; align-items:flex-start; gap:20px;}
.ticket-top .logo-mark{height:34px;}
.ticket-code{text-align:right;}
.ticket-code .lbl{font-family:'IBM Plex Mono',monospace; font-size:10px; text-transform:uppercase; letter-spacing:0.1em; color:#8a8260;}
.ticket-code .code{font-family:'IBM Plex Mono',monospace; font-size:22px; font-weight:700; color:var(--amber-600);}
.ticket-perf{display:flex; align-items:center; padding:0 30px;}
.ticket-perf .dots{flex:1; height:1px; border-top:2px dashed #cfc6a6;}
.ticket-body{padding:10px 30px 26px; display:grid; grid-template-columns:1fr auto; gap:24px;}
.route-big{font-family:'Fraunces',serif; font-weight:700; font-size:26px; display:flex; align-items:center; gap:12px; margin-bottom:4px;}
.route-big .arrow{color:var(--amber-600);}
.trip-meta-grid{display:grid; grid-template-columns:1fr 1fr; gap:14px 24px; margin-top:18px;}
.tm-item .lbl{font-family:'IBM Plex Mono',monospace; font-size:10px; text-transform:uppercase; letter-spacing:0.08em; color:#8a8260; margin-bottom:3px;}
.tm-item .val{font-weight:600; font-size:14.5px;}
.qr-box{width:130px; height:130px; background:#fff; border:1px solid #d5ccae; border-radius:6px; display:grid; grid-template-columns:repeat(11,1fr); grid-template-rows:repeat(11,1fr); padding:8px; gap:1px;}
.qr-box .px{background:#0a0c10;}
.qr-box .px.off{background:transparent;}
.qr-caption{text-align:center; font-family:'IBM Plex Mono',monospace; font-size:9.5px; color:#8a8260; margin-top:6px;}
.passenger-list{margin-top:20px; padding-top:18px; border-top:1px dashed #cfc6a6;}
.passenger-list .lbl{font-family:'IBM Plex Mono',monospace; font-size:10px; text-transform:uppercase; letter-spacing:0.08em; color:#8a8260; margin-bottom:8px;}
.passenger-list .p-row{display:flex; justify-content:space-between; font-size:14px; padding:5px 0;}
.ticket-bottom{background:#e9e0c8; padding:14px 30px; display:flex; justify-content:space-between; font-size:12px; color:#5c563f;}
.ticket-actions{display:flex; gap:12px; margin-top:24px; flex-wrap:wrap;}
.no-billet{text-align:center; padding:80px 20px; color:var(--text-mid);}
@media (max-width:640px){ .ticket-body{grid-template-columns:1fr;} .qr-box{margin:0 auto;} }
@media print{ body{background:#fff;} .ticket-actions{display:none;} .confirm-banner{display:none;} }

</style>
</head>
<body>
<header>
  <div class="header-inner">
    <a href="<?= url_app('/') ?>" class="brand" style="text-decoration:none;">
      <span class="brand-name"><img class="logo-mark" src="<?= url_app('/assets/logo.png') ?>" alt="e-réservation"></span>
      <span class="brand-slogan">Votre billet de bus, sans détour</span>
    </a>
    <nav>
      <div class="nav-links">
        <a href="<?= url_app('/') ?>">Accueil</a>
        <a href="<?= url_app('/#comment-ca-marche') ?>">Comment ça marche</a>
        <a href="<?= url_app('/rejoindre') ?>">Devenir partenaire</a>
      </div>
      <button type="button" class="theme-toggle" id="themeToggle" aria-label="Changer de thème" title="Mode clair / sombre">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
      </button>
    </nav>
  </div>
</header>
<div class="step-tracker"><div class="wrap"><div class="st-item done"><span class="st-num">✓</span><span>Recherche</span></div><div class="st-sep"></div><div class="st-item done"><span class="st-num">✓</span><span>Siège</span></div><div class="st-sep"></div><div class="st-item done"><span class="st-num">✓</span><span>Paiement</span></div><div class="st-sep"></div><div class="st-item active"><span class="st-num">4</span><span>Billet</span></div></div></div>

<div class="wrap" id="pageContent">
  <div class="confirm-banner">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Paiement confirmé — votre billet a été envoyé par SMS et email.
  </div>

  <div class="ticket-wrap">
    <div class="ticket" id="ticket">
      <div class="ticket-top">
        <img class="logo-mark" src="<?= url_app('/assets/logo.png') ?>" alt="e-réservation">
        <div class="ticket-code">
          <div class="lbl">Code de réservation</div>
          <div class="code mono" id="ticketCode">ER-XXXXXXXX</div>
        </div>
      </div>
      <div class="ticket-perf"><div class="dots"></div></div>
      <div class="ticket-body">
        <div>
          <div class="route-big"><span id="tRoute1">Cotonou</span> <span class="arrow">&rarr;</span> <span id="tRoute2">Parakou</span></div>
          <span class="badge badge-amber" id="tCompagnie">Compagnie</span>
          <div class="trip-meta-grid">
            <div class="tm-item"><div class="lbl">Date</div><div class="val" id="tDate">--</div></div>
            <div class="tm-item"><div class="lbl">Départ</div><div class="val" id="tHeure">--</div></div>
            <div class="tm-item"><div class="lbl">Gare d'embarquement</div><div class="val" id="tGare">--</div></div>
            <div class="tm-item"><div class="lbl">Montant payé</div><div class="val" id="tTotal">--</div></div>
          </div>
          <div class="passenger-list">
            <div class="lbl">Passagers &amp; sièges</div>
            <div id="tPassengers"></div>
          </div>
        </div>
        <div>
          <div class="qr-box" id="qrBox"></div>
          <div class="qr-caption">PRÉSENTER À L'EMBARQUEMENT</div>
        </div>
      </div>
      <div class="ticket-bottom">
        <span>e-réservation.bj</span>
        <span>Frais de service inclus — non remboursable</span>
      </div>
    </div>

    <div class="ticket-actions no-print">
      <button class="btn btn-primary" id="printBtn" type="button">Imprimer / Enregistrer en PDF</button>
      <a href="<?= url_app('/') ?>" class="btn btn-ghost" id="newSearchBtn">Nouvelle recherche</a>
    </div>
  </div>
</div>

<footer id="contact">
  <div class="footer-top">
    <span class="brand-name"><img class="logo-mark" src="<?= url_app('/assets/logo.png') ?>" alt="e-réservation"></span>
    <div class="footer-links">
      <a href="<?= url_app('/') ?>">Accueil</a>
      <a href="<?= url_app('/rejoindre') ?>">Espace compagnies</a>
      <a href="tel:+229XXXXXXXX">+229 XX XX XX XX</a>
      <a href="mailto:contact@e-reservation.bj">contact@e-reservation.bj</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 e-réservation. Tous droits réservés.</p>
    <a href="<?= url_app('/admin/connexion') ?>" class="mono">Administration</a>
  </div>
</footer>
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
// ===== e-réservation — billet réel injecté depuis PHP =====
const FRAIS_SERVICE = <?= config('metier.frais_service_fcfa') ?>;

function formatFCFA(n){ return n.toLocaleString('fr-FR').replace(/,/g,' ') + ' FCFA'; }

const billet = <?= $billet !== null ? json_encode($billet, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : 'null' ?>;
if(!billet){
  document.getElementById('pageContent').innerHTML = '<div class="no-billet"><h2 style="margin-bottom:10px;">Aucun billet actif</h2><p>Effectuez une réservation pour voir votre billet ici.</p><a href="<?= url_app('/') ?>" class="btn btn-primary" style="margin-top:20px;">Retour à l\'accueil</a></div>';
} else {
  document.getElementById('ticketCode').textContent = billet.code;
  document.getElementById('tRoute1').textContent = billet.depart;
  document.getElementById('tRoute2').textContent = billet.arrivee;
  document.getElementById('tCompagnie').textContent = billet.compagnie;
  document.getElementById('tDate').textContent = billet.date ? new Date(billet.date+'T00:00:00').toLocaleDateString('fr-FR',{weekday:'long',day:'numeric',month:'long',year:'numeric'}) : 'Date à confirmer';
  document.getElementById('tHeure').textContent = billet.heureDepart + ' → ' + billet.heureArrivee;
  document.getElementById('tGare').textContent = billet.gare;
  document.getElementById('tTotal').textContent = formatFCFA(billet.total) + ' (' + billet.methode + ')';
  document.getElementById('tPassengers').innerHTML = billet.passagers.map((nom,i) =>
    '<div class="p-row"><span>' + nom + '</span><span class="mono">Siège ' + billet.seats[i] + '</span></div>'
  ).join('');

  // Motif QR décoratif déterministe (mockup visuel du futur QR signé HMAC, voir Core\\Security::signerDonnees())
  let seed = 0;
  for(const ch of billet.code) seed = (seed * 31 + ch.charCodeAt(0)) % 100000;
  const qr = document.getElementById('qrBox');
  for(let i=0;i<121;i++){
    seed = (seed * 9301 + 49297) % 233280;
    const on = (seed/233280) > 0.42;
    const px = document.createElement('div');
    px.className = 'px' + (on ? '' : ' off');
    qr.appendChild(px);
  }

  document.getElementById('newSearchBtn').addEventListener('click', () => { localStorage.removeItem('ereservation_panier'); });
}

document.getElementById('printBtn') && document.getElementById('printBtn').addEventListener('click', () => window.print());

</script>
</body>
