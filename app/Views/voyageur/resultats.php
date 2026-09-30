<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Résultats de recherche — e-réservation</title>
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


.search-recap{background:var(--paper); color:var(--ink-950); border-radius:8px; padding:20px 24px; margin:28px 0 8px; display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap;}
.search-recap .route{font-family:'Fraunces',serif; font-weight:700; font-size:19px; display:flex; align-items:center; gap:10px;}
.search-recap .route .arrow{color:var(--amber-600);}
.search-recap .date{font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:#6b6450; margin-top:4px;}
.edit-btn{background:transparent; border:1.5px solid #d5ccae; color:#4a4433; font-weight:600; font-size:13px; padding:9px 16px; border-radius:5px;}
.edit-form{display:none; background:var(--ink-900); border:1px solid var(--line); border-radius:8px; padding:20px 24px; margin-bottom:24px;}
.edit-form.open{display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:14px; align-items:end;}
.results-toolbar{display:flex; justify-content:space-between; align-items:center; margin:28px 0 16px; flex-wrap:wrap; gap:12px;}
.results-count{color:var(--text-mid); font-size:14px;}
.sort-group{display:flex; gap:8px;}
.sort-btn{background:var(--ink-900); border:1px solid var(--line); color:var(--text-mid); font-size:12.5px; padding:8px 13px; border-radius:100px;}
.sort-btn.active{border-color:var(--amber-400); color:var(--amber-400);}
.trip-list{display:flex; flex-direction:column; gap:14px; margin-bottom:60px;}
.trip-card{background:var(--ink-900); border:1px solid var(--line); border-radius:8px; padding:22px 24px; display:grid; grid-template-columns:1.4fr 1.6fr auto auto; gap:18px; align-items:center;}
.trip-co{display:flex; flex-direction:column; gap:6px;}
.trip-co .name{font-family:'Fraunces',serif; font-weight:600; font-size:16.5px;}
.trip-times{display:flex; align-items:center; gap:14px;}
.trip-times .t{font-family:'IBM Plex Mono',monospace; font-size:20px; font-weight:600;}
.trip-times .sep{flex:1; min-width:40px; height:1px; background:var(--line); position:relative; margin:0 6px;}
.trip-times .sep .duree{position:absolute; left:50%; top:-18px; transform:translateX(-50%); font-size:11px; color:var(--text-low); white-space:nowrap;}
.trip-places{font-size:12.5px; color:var(--text-mid);}
.trip-price{text-align:right;}
.trip-price .amount{font-family:'Fraunces',serif; font-weight:700; font-size:21px; color:var(--amber-400);}
.trip-price .note{font-size:11px; color:var(--text-low);}
.empty-state{text-align:center; padding:60px 20px; color:var(--text-mid);}
@media (max-width:900px){
  .trip-card{grid-template-columns:1fr; text-align:left;}
  .trip-price{text-align:left;}
  .edit-form.open{grid-template-columns:1fr;}
}

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
<div class="step-tracker"><div class="wrap"><div class="st-item active"><span class="st-num">1</span><span>Recherche</span></div><div class="st-sep"></div><div class="st-item "><span class="st-num">2</span><span>Siège</span></div><div class="st-sep"></div><div class="st-item "><span class="st-num">3</span><span>Paiement</span></div><div class="st-sep"></div><div class="st-item "><span class="st-num">4</span><span>Billet</span></div></div></div>

<div class="wrap">
  <div class="search-recap">
    <div>
      <div class="route"><span id="recapDepart">Cotonou</span> <span class="arrow">&rarr;</span> <span id="recapArrivee">Parakou</span></div>
      <div class="date mono" id="recapDate">Toutes dates</div>
    </div>
    <button class="edit-btn" id="editToggle" type="button">Modifier la recherche</button>
  </div>

  <form class="edit-form" id="editForm">
    <div class="field" style="margin-bottom:0;"><label for="fDepart">Départ</label><input id="fDepart" type="text" placeholder="Cotonou"></div>
    <div class="field" style="margin-bottom:0;"><label for="fArrivee">Arrivée</label><input id="fArrivee" type="text" placeholder="Parakou"></div>
    <div class="field" style="margin-bottom:0;"><label for="fDate">Date</label><input id="fDate" type="date"></div>
    <button type="submit" class="btn btn-primary">Rechercher</button>
  </form>

  <div class="results-toolbar">
    <span class="results-count" id="resultsCount">-- voyages trouvés</span>
    <div class="sort-group">
      <button class="sort-btn active" data-sort="heure" type="button">Heure de départ</button>
      <button class="sort-btn" data-sort="prix" type="button">Prix</button>
    </div>
  </div>

  <div class="trip-list" id="tripList"></div>
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
// ===== e-réservation — données réelles injectées depuis PHP (voir plus haut) =====
const VOYAGES = <?= json_encode($voyages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

const FRAIS_SERVICE = <?= config('metier.frais_service_fcfa') ?>; // FCFA, inclus dans le prix affiché, jamais remboursé
const NB_PLACES = <?= config('metier.nb_places_par_voyage') ?>;

function formatFCFA(n){ return n.toLocaleString('fr-FR').replace(/,/g,' ') + ' FCFA'; }

function getQueryParam(name){
  return new URLSearchParams(window.location.search).get(name);
}

function findVoyage(id){
  return VOYAGES.find(v => String(v.id) === String(id));
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


const params = new URLSearchParams(window.location.search);
let critDepart = (params.get('depart') || '').trim();
let critArrivee = (params.get('arrivee') || '').trim();
let critDate = (params.get('date') || '').trim();

document.getElementById('recapDepart').textContent = critDepart || 'Toutes villes';
document.getElementById('recapArrivee').textContent = critArrivee || 'Toutes destinations';
document.getElementById('recapDate').textContent = critDate ? new Date(critDate+'T00:00:00').toLocaleDateString('fr-FR',{weekday:'long',day:'numeric',month:'long'}) : 'Toutes dates';
document.getElementById('fDepart').value = critDepart;
document.getElementById('fArrivee').value = critArrivee;
document.getElementById('fDate').value = critDate;

document.getElementById('editToggle').addEventListener('click', () => {
  document.getElementById('editForm').classList.toggle('open');
});
document.getElementById('editForm').addEventListener('submit', (e) => {
  e.preventDefault();
  const d = document.getElementById('fDepart').value.trim();
  const a = document.getElementById('fArrivee').value.trim();
  const dt = document.getElementById('fDate').value;
  const qs = new URLSearchParams();
  if(d) qs.set('depart', d);
  if(a) qs.set('arrivee', a);
  if(dt) qs.set('date', dt);
  window.location.href = APP_BASE + '/resultats?' + qs.toString();
});

function matches(v){
  const okD = !critDepart || v.depart.toLowerCase().includes(critDepart.toLowerCase());
  const okA = !critArrivee || v.arrivee.toLowerCase().includes(critArrivee.toLowerCase());
  return okD && okA;
}

let currentSort = 'heure';
function render(){
  let list = VOYAGES.filter(matches);
  list = list.slice().sort((a,b) => currentSort === 'prix' ? a.prix - b.prix : a.heureDepart.localeCompare(b.heureDepart));

  const container = document.getElementById('tripList');
  document.getElementById('resultsCount').textContent = list.length + ' voyage' + (list.length>1?'s':'') + ' trouvé' + (list.length>1?'s':'');

  if(list.length === 0){
    container.innerHTML = '<div class="empty-state">Aucun voyage ne correspond à votre recherche pour le moment.</div>';
    return;
  }

  container.innerHTML = list.map(v => {
    const restantes = v.placesRestantes;
    const qs = new URLSearchParams();
    qs.set('voyage', v.id);
    if(critDate) qs.set('date', critDate);
    const indisponible = restantes <= 0;
    return '<div class="trip-card">' +
      '<div class="trip-co"><span class="badge badge-amber">' + v.type + '</span><span class="name">' + v.compagnie + '</span><span style="font-size:12.5px;color:var(--text-mid);">' + v.gare + '</span></div>' +
      '<div class="trip-times"><span class="t">' + v.heureDepart + '</span><div class="sep"><span class="duree">' + v.duree + '</span></div><span class="t">' + v.heureArrivee + '</span></div>' +
      '<div class="trip-places">' + (indisponible ? 'Complet' : restantes + ' place' + (restantes>1?'s':'') + ' disponible' + (restantes>1?'s':'')) + '<br>' + v.depart + ' → ' + v.arrivee + '</div>' +
      '<div class="trip-price"><div class="amount">' + formatFCFA(v.prix) + '</div><div class="note">frais de service inclus</div>' +
      (indisponible
        ? '<button class="btn btn-primary" disabled style="margin-top:10px;opacity:.5;">Complet</button></div>'
        : '<a href="' + APP_BASE + '/siege?' + qs.toString() + '" class="btn btn-primary" style="margin-top:10px;">Choisir ce voyage &rarr;</a></div>') +
    '</div>';
  }).join('');
}

document.querySelectorAll('.sort-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentSort = btn.getAttribute('data-sort');
    render();
  });
});

render();

</script>
</body>
</html>
