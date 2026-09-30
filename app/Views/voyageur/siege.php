<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Choisir un siège — e-réservation</title>
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


.trip-bar{background:var(--ink-900); border:1px solid var(--line); border-radius:8px; padding:18px 22px; margin:28px 0 24px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;}
.trip-bar .info{display:flex; flex-direction:column; gap:4px;}
.trip-bar .route{font-family:'Fraunces',serif; font-weight:600; font-size:17px;}
.trip-bar .meta{font-size:12.5px; color:var(--text-mid);}
.timer{display:flex; align-items:center; gap:10px; background:rgba(255,178,56,0.1); border:1px solid var(--amber-400); color:var(--amber-400); padding:9px 16px; border-radius:100px; font-family:'IBM Plex Mono',monospace; font-weight:600; font-size:14px;}
.timer.low{background:var(--error-dim); border-color:var(--error); color:var(--error);}

.seat-layout{display:grid; grid-template-columns:1.3fr 0.9fr; gap:28px; align-items:start; margin-bottom:60px;}
.bus-panel{background:var(--ink-900); border:1px solid var(--line); border-radius:12px; padding:26px;}
.bus-frame{background:var(--ink-800); border:1.5px solid var(--line); border-radius:24px 24px 10px 10px; padding:22px 18px 26px;}
.bus-driver{display:flex; align-items:center; gap:8px; color:var(--text-low); font-size:11.5px; margin-bottom:16px; padding-bottom:14px; border-bottom:1px dashed var(--line);}
.seat-grid{display:grid; grid-template-columns:repeat(2,40px) 30px repeat(2,40px); gap:10px; justify-content:center; margin:0 auto;}
.seat{width:40px; height:40px; border-radius:7px 7px 3px 3px; border:1.5px solid var(--line); background:var(--ink-700); color:var(--text-mid); font-family:'IBM Plex Mono',monospace; font-size:11.5px; display:flex; align-items:center; justify-content:center; transition:all .12s ease;}
.seat.aisle-gap{background:transparent; border:none;}
.seat[data-status="free"]:hover{border-color:var(--amber-400); color:var(--text-hi);}
.seat[data-status="occupied"]{background:var(--ink-800); color:var(--ink-700); border-color:var(--ink-800); cursor:not-allowed;}
.seat[data-status="selected"]{background:var(--amber-400); border-color:var(--amber-400); color:var(--ink-950); font-weight:700;}
.seat-back-row{display:flex; justify-content:center; gap:10px; margin-top:14px; padding-top:14px; border-top:1px dashed var(--line);}
.legend{display:flex; gap:22px; margin-top:20px; flex-wrap:wrap;}
.legend-item{display:flex; align-items:center; gap:8px; font-size:12.5px; color:var(--text-mid);}
.legend-swatch{width:16px; height:16px; border-radius:4px; border:1.5px solid var(--line);}
.legend-swatch.free{background:var(--ink-700);}
.legend-swatch.selected{background:var(--amber-400); border-color:var(--amber-400);}
.legend-swatch.occupied{background:var(--ink-800); border-color:var(--ink-800);}

.summary-panel{position:sticky; top:90px;}
.selected-seats{display:flex; flex-wrap:wrap; gap:8px; margin:14px 0 18px;}
.selected-seats .chip{background:var(--ink-800); border:1px solid var(--amber-400); color:var(--amber-400); font-family:'IBM Plex Mono',monospace; font-size:12.5px; padding:5px 10px; border-radius:100px;}
.price-line{display:flex; justify-content:space-between; font-size:13.5px; color:var(--text-mid); margin-bottom:8px;}
.price-total{display:flex; justify-content:space-between; font-family:'Fraunces',serif; font-size:19px; font-weight:700; margin-top:14px; padding-top:14px; border-top:1px solid var(--line);}
.max-note{font-size:12px; color:var(--text-low); margin-top:14px;}
@media (max-width:900px){ .seat-layout{grid-template-columns:1fr;} .summary-panel{position:static;} }

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
<div class="step-tracker"><div class="wrap"><div class="st-item done"><span class="st-num">✓</span><span>Recherche</span></div><div class="st-sep"></div><div class="st-item active"><span class="st-num">2</span><span>Siège</span></div><div class="st-sep"></div><div class="st-item "><span class="st-num">3</span><span>Paiement</span></div><div class="st-sep"></div><div class="st-item "><span class="st-num">4</span><span>Billet</span></div></div></div>

<div class="wrap">
  <div class="trip-bar">
    <div class="info">
      <span class="route" id="tbRoute">Chargement du voyage...</span>
      <span class="meta" id="tbMeta"></span>
    </div>
    <div class="timer" id="holdTimer">15:00</div>
  </div>

  <div class="seat-layout">
    <div class="bus-panel">
      <div class="bus-frame">
        <div class="bus-driver">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/><path d="M4 20c0-4 3.5-6 8-6s8 2 8 6" stroke="currentColor" stroke-width="2"/></svg>
          Conducteur
        </div>
        <div class="seat-grid" id="seatGrid"></div>
        <div class="seat-back-row" id="seatBackRow"></div>
      </div>
      <div class="legend">
        <div class="legend-item"><span class="legend-swatch free"></span>Disponible</div>
        <div class="legend-item"><span class="legend-swatch selected"></span>Sélectionné</div>
        <div class="legend-item"><span class="legend-swatch occupied"></span>Occupé</div>
      </div>
    </div>

    <div class="summary-panel panel">
      <h2>Votre sélection</h2>
      <p class="sub">Choisissez jusqu'à 4 sièges pour ce voyage.</p>
      <div class="selected-seats" id="selectedSeats"><span style="color:var(--text-low); font-size:13px;">Aucun siège sélectionné</span></div>
      <div id="priceBreakdown"></div>
      <div class="price-total"><span>Total</span><span id="priceTotal">0 FCFA</span></div>
      <button class="btn btn-primary btn-block" id="continueBtn" style="margin-top:20px;" disabled>Continuer vers le paiement &rarr;</button>
      <p class="max-note">Le prix inclut les 250 FCFA de frais de service par billet, non remboursables. Votre sélection est réservée pendant 15 minutes.</p>
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
// ===== e-réservation — données réelles injectées depuis PHP =====
const VOYAGES = [<?= json_encode($voyage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>];
const SIEGES_OCCUPES_SERVEUR = <?= json_encode($siegesOccupes) ?>;

const FRAIS_SERVICE = <?= config('metier.frais_service_fcfa') ?>;
const HOLD_MINUTES = <?= config('metier.hold_minutes') ?>;
const NB_PLACES = <?= config('metier.nb_places_par_voyage') ?>;

function formatFCFA(n){ return n.toLocaleString('fr-FR').replace(/,/g,' ') + ' FCFA'; }

function getQueryParam(name){
  return new URLSearchParams(window.location.search).get(name);
}

function findVoyage(id){
  return VOYAGES.find(v => String(v.id) === String(id));
}

// Sièges déjà occupés — vraie donnée injectée depuis PHP (voir SIEGES_OCCUPES_SERVEUR).
function seatsOccupied(voyageId){
  return SIEGES_OCCUPES_SERVEUR;
}

function genCodeReservation(){
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
  let code = 'ER-';
  for(let i=0;i<8;i++) code += chars[Math.floor(Math.random()*chars.length)];
  return code;
}

function getPanier(){
  try{ return JSON.parse(localStorage.getItem('ereservation_panier') || 'null'); }
  catch(e){ return null; }
}
function setPanier(p){ localStorage.setItem('ereservation_panier', JSON.stringify(p)); }
function clearPanier(){ localStorage.removeItem('ereservation_panier'); }

function getBillet(){
  try{ return JSON.parse(localStorage.getItem('ereservation_billet') || 'null'); }
  catch(e){ return null; }
}
function setBillet(b){ localStorage.setItem('ereservation_billet', JSON.stringify(b)); }


const voyageId = getQueryParam('voyage');
const voyage = findVoyage(voyageId);
const critDate = getQueryParam('date') || '';

if(!voyage){
  window.location.href = APP_BASE + '/resultats';
}

document.getElementById('tbRoute').textContent = voyage.compagnie + ' — ' + voyage.depart + ' → ' + voyage.arrivee;
document.getElementById('tbMeta').textContent = voyage.heureDepart + ' → ' + voyage.heureArrivee + ' (' + voyage.duree + ') — ' + voyage.type + (critDate ? ' — ' + new Date(critDate+'T00:00:00').toLocaleDateString('fr-FR',{day:'numeric',month:'long'}) : '');

const occupied = new Set(seatsOccupied(voyage.id));
let selected = [];
const MAX_SEATS = 4;

const grid = document.getElementById('seatGrid');
const backRow = document.getElementById('seatBackRow');

function seatBtn(num){
  const btn = document.createElement('button');
  btn.type = 'button';
  btn.className = 'seat';
  btn.textContent = num;
  btn.dataset.num = num;
  btn.dataset.status = occupied.has(num) ? 'occupied' : 'free';
  btn.addEventListener('click', () => toggleSeat(num, btn));
  return btn;
}

for(let row=0; row<12; row++){
  for(let col=0; col<4; col++){
    const num = row*4 + col + 1;
    const seat = seatBtn(num);
    if(col === 2){
      const gap = document.createElement('div');
      gap.className = 'seat aisle-gap';
      grid.appendChild(gap);
    }
    grid.appendChild(seat);
  }
}
backRow.appendChild(seatBtn(49));
backRow.appendChild(seatBtn(50));

function toggleSeat(num, btn){
  if(btn.dataset.status === 'occupied') return;
  if(btn.dataset.status === 'selected'){
    btn.dataset.status = 'free';
    selected = selected.filter(n => n !== num);
  } else {
    if(selected.length >= MAX_SEATS){
      alert('Vous pouvez sélectionner au maximum ' + MAX_SEATS + ' sièges par réservation.');
      return;
    }
    btn.dataset.status = 'selected';
    selected.push(num);
  }
  updateSummary();
}

function updateSummary(){
  const box = document.getElementById('selectedSeats');
  const breakdown = document.getElementById('priceBreakdown');
  const total = document.getElementById('priceTotal');
  const continueBtn = document.getElementById('continueBtn');

  if(selected.length === 0){
    box.innerHTML = '<span style="color:var(--text-low); font-size:13px;">Aucun siège sélectionné</span>';
    breakdown.innerHTML = '';
    total.textContent = '0 FCFA';
    continueBtn.disabled = true;
    return;
  }

  box.innerHTML = selected.slice().sort((a,b)=>a-b).map(n => '<span class="chip">Siège ' + n + '</span>').join('');
  const sousTotal = voyage.prix - FRAIS_SERVICE;
  breakdown.innerHTML =
    '<div class="price-line"><span>' + selected.length + ' billet(s) × ' + formatFCFA(sousTotal) + '</span><span>' + formatFCFA(sousTotal * selected.length) + '</span></div>' +
    '<div class="price-line"><span>Frais de service × ' + selected.length + '</span><span>' + formatFCFA(FRAIS_SERVICE * selected.length) + '</span></div>';
  total.textContent = formatFCFA(voyage.prix * selected.length);
  continueBtn.disabled = false;
}

document.getElementById('continueBtn').addEventListener('click', () => {
  if(selected.length === 0) return;
  setPanier({
    voyageId: voyage.id,
    date: critDate,
    seats: selected.slice().sort((a,b)=>a-b),
    prixUnitaire: voyage.prix
  });
  window.location.href = APP_BASE + '/paiement?voyage=' + voyage.id;
});

// Compte à rebours de 15 minutes (hold du/des siège(s))
let remaining = HOLD_MINUTES * 60;
const timerEl = document.getElementById('holdTimer');
const timerInterval = setInterval(() => {
  remaining--;
  const m = Math.floor(remaining/60), s = remaining%60;
  timerEl.textContent = m + ':' + String(s).padStart(2,'0');
  if(remaining <= 120) timerEl.classList.add('low');
  if(remaining <= 0){
    clearInterval(timerInterval);
    alert('Votre sélection a expiré. Les sièges ont été libérés, merci de recommencer.');
    window.location.href = APP_BASE + '/resultats';
  }
}, 1000);

</script>
</body>
</html>
