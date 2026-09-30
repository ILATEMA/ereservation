<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paiement — e-réservation</title>
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


.pay-layout{display:grid; grid-template-columns:1.5fr 1fr; gap:28px; align-items:start; margin:28px 0 60px;}
.passenger-row{background:var(--ink-800); border:1px solid var(--line); border-radius:6px; padding:16px; margin-bottom:12px;}
.passenger-row .seat-tag{font-family:'IBM Plex Mono',monospace; font-size:11px; color:var(--amber-400); margin-bottom:8px; display:block;}
.method-group{display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:18px;}
.method-card{position:relative; cursor:pointer;}
.method-card input{position:absolute; opacity:0; inset:0; cursor:pointer;}
.method-card .box{border:1.5px solid var(--line); border-radius:6px; padding:14px 10px; text-align:center; transition:all .15s ease;}
.method-card .box .dot{width:34px; height:34px; border-radius:50%; margin:0 auto 8px; display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono',monospace; font-weight:700; font-size:12px;}
.method-card .box .name{font-size:12.5px; font-weight:600;}
.method-card[data-brand="mtn"] .dot{background:#ffcc08; color:#111;}
.method-card[data-brand="moov"] .dot{background:#0072ce; color:#fff;}
.method-card[data-brand="celtiis"] .dot{background:#e30613; color:#fff;}
.method-card input:checked + .box{border-color:var(--amber-400); background:rgba(255,178,56,0.08);}
.summary-panel{position:sticky; top:90px;}
.sum-line{display:flex; justify-content:space-between; font-size:13.5px; color:var(--text-mid); padding:6px 0;}
.sum-total{display:flex; justify-content:space-between; font-family:'Fraunces',serif; font-size:20px; font-weight:700; margin-top:12px; padding-top:14px; border-top:1px solid var(--line);}
.secure-note{display:flex; gap:8px; align-items:flex-start; font-size:12px; color:var(--text-low); margin-top:16px; line-height:1.6;}
.processing{display:none; align-items:center; gap:10px; justify-content:center; padding:14px; color:var(--text-mid); font-size:13.5px;}
.processing.show{display:flex;}
.spinner{width:16px; height:16px; border:2px solid var(--line); border-top-color:var(--amber-400); border-radius:50%; animation:spin .7s linear infinite;}
@keyframes spin{to{transform:rotate(360deg);}}
@media (max-width:900px){ .pay-layout{grid-template-columns:1fr;} .summary-panel{position:static;} .method-group{grid-template-columns:1fr;} }

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
<div class="step-tracker"><div class="wrap"><div class="st-item done"><span class="st-num">✓</span><span>Recherche</span></div><div class="st-sep"></div><div class="st-item done"><span class="st-num">✓</span><span>Siège</span></div><div class="st-sep"></div><div class="st-item active"><span class="st-num">3</span><span>Paiement</span></div><div class="st-sep"></div><div class="st-item "><span class="st-num">4</span><span>Billet</span></div></div></div>

<div class="wrap">
  <h1 style="font-size:24px; margin:28px 0 4px;">Informations et paiement</h1>
  <p style="color:var(--text-mid); font-size:14px; margin-bottom:8px;">Votre billet électronique sera envoyé par SMS et email dès le paiement confirmé — aucun compte n'est nécessaire.</p>

  <form id="paiementForm">
    <div class="pay-layout">
      <div>
        <div class="panel" style="margin-bottom:20px;">
          <h2>Passagers</h2>
          <p class="sub">Un nom par siège sélectionné.</p>
          <div id="passengerFields"></div>
        </div>

        <div class="panel" style="margin-bottom:20px;">
          <h2>Contact</h2>
          <p class="sub">Pour recevoir votre billet et les rappels de voyage.</p>
          <div class="field-row">
            <div class="field"><label for="contactTel">Téléphone</label><input id="contactTel" type="tel" placeholder="+229 XX XX XX XX" required></div>
            <div class="field"><label for="contactEmail">Email (optionnel)</label><input id="contactEmail" type="email" placeholder="vous@exemple.com"></div>
          </div>
        </div>

        <div class="panel">
          <h2>Moyen de paiement</h2>
          <p class="sub">Paiement Mobile Money via FedaPay.</p>
          <div class="method-group">
            <label class="method-card" data-brand="mtn">
              <input type="radio" name="methode" value="MTN Mobile Money" checked>
              <div class="box"><div class="dot">MTN</div><div class="name">MTN MoMo</div></div>
            </label>
            <label class="method-card" data-brand="moov">
              <input type="radio" name="methode" value="Moov Money">
              <div class="box"><div class="dot">MV</div><div class="name">Moov Money</div></div>
            </label>
            <label class="method-card" data-brand="celtiis">
              <input type="radio" name="methode" value="Celtiis Cash">
              <div class="box"><div class="dot">CC</div><div class="name">Celtiis Cash</div></div>
            </label>
          </div>
          <div class="field"><label for="payTel">Numéro Mobile Money</label><input id="payTel" type="tel" placeholder="+229 XX XX XX XX" required></div>
          <div class="hint">Vous recevrez une demande de confirmation sur ce numéro.</div>
        </div>
      </div>

      <div class="summary-panel panel">
        <h2 id="sumRoute">Voyage</h2>
        <p class="sub" id="sumMeta"></p>
        <div id="sumSeats"></div>
        <div class="sum-total"><span>Total à payer</span><span id="sumTotal">0 FCFA</span></div>
        <button type="submit" class="btn btn-primary btn-block" id="payBtn" style="margin-top:20px;">Payer maintenant</button>
        <div class="processing" id="processing"><span class="spinner"></span> Traitement du paiement en cours...</div>
        <p class="secure-note">🔒 Paiement sécurisé via FedaPay Marketplace. Les 250 FCFA de frais de service par billet sont inclus et non remboursables en cas d'annulation.</p>
      </div>
    </div>
  </form>
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
// ===== e-réservation — données réelles injectées depuis PHP =====
const VOYAGES = [<?= json_encode($voyage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>];

const FRAIS_SERVICE = <?= config('metier.frais_service_fcfa') ?>;
const NB_PLACES = <?= config('metier.nb_places_par_voyage') ?>;

function formatFCFA(n){ return n.toLocaleString('fr-FR').replace(/,/g,' ') + ' FCFA'; }

function getQueryParam(name){
  return new URLSearchParams(window.location.search).get(name);
}

function findVoyage(id){
  return VOYAGES.find(v => String(v.id) === String(id));
}

function getPanier(){
  try{ return JSON.parse(localStorage.getItem('ereservation_panier') || 'null'); }
  catch(e){ return null; }
}
function clearPanier(){ localStorage.removeItem('ereservation_panier'); }

const panier = getPanier();
if(!panier){
  window.location.href = APP_BASE + '/';
}
const voyage = findVoyage(panier ? panier.voyageId : null);
if(!voyage){
  window.location.href = APP_BASE + '/';
}

document.getElementById('sumRoute').textContent = voyage.compagnie + ' — ' + voyage.depart + ' → ' + voyage.arrivee;
document.getElementById('sumMeta').textContent = voyage.heureDepart + ' → ' + voyage.heureArrivee + (panier.date ? ' — ' + new Date(panier.date+'T00:00:00').toLocaleDateString('fr-FR',{day:'numeric',month:'long'}) : '');

const seats = panier.seats;
document.getElementById('sumSeats').innerHTML = seats.map(n =>
  '<div class="sum-line"><span>Siège ' + n + '</span><span>' + formatFCFA(voyage.prix) + '</span></div>'
).join('') + '<div class="sum-line"><span>dont frais de service</span><span>' + formatFCFA(FRAIS_SERVICE * seats.length) + '</span></div>';
document.getElementById('sumTotal').textContent = formatFCFA(voyage.prix * seats.length);

const passengerFields = document.getElementById('passengerFields');
passengerFields.innerHTML = seats.map((n,i) =>
  '<div class="passenger-row"><span class="seat-tag">SIÈGE ' + n + '</span><input class="passenger-name" data-seat="' + n + '" type="text" placeholder="Nom complet du passager" required style="width:100%; background:var(--ink-900); border:1.5px solid var(--line); color:var(--text-hi); padding:11px 13px; border-radius:5px; font-size:14.5px;"></div>'
).join('');

// Correspondance entre les libellés affichés et les codes attendus par l'API
const CODES_MOYEN_PAIEMENT = {
  'MTN Mobile Money': 'mtn',
  'Moov Money': 'moov',
  'Celtiis Cash': 'celtiis',
};

// Retenu après la création réussie de la commande, pour permettre un
// nouvel essai de paiement SANS re-créer la commande (donc sans risquer
// un conflit "siège déjà pris" sur les sièges qu'on vient nous-même de
// réserver).
let codeCommandeEnCours = null;

async function appelApi(url, corps){
  const reponse = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': CSRF_TOKEN },
    body: JSON.stringify(corps),
  });
  const donnees = await reponse.json().catch(() => ({}));
  return { ok: reponse.ok, statut: reponse.status, donnees };
}

function afficherErreur(message){
  alert(message);
  document.getElementById('payBtn').disabled = false;
  document.getElementById('processing').classList.remove('show');
}

async function tenterPaiement(){

  const { ok, statut, donnees } = await appelApi(APP_BASE + '/api/fedapay/initier', {
    codeCommande: codeCommandeEnCours,
  });

  if(!ok){
    if(statut === 410){
      afficherErreur('Le délai de 15 minutes est dépassé, vos sièges ont été libérés. Merci de refaire votre sélection.');
      clearPanier();
      setTimeout(() => window.location.href = APP_BASE + '/siege?voyage=' + voyage.id, 2500);
      return;
      console.log('RÉPONSE FedaPay :', {
    ok,
    statut,
    donnees
    });
    }
    afficherErreur(donnees.erreur || 'Impossible de démarrer le paiement. Merci de réessayer.');
    document.getElementById('payBtn').textContent = 'Réessayer le paiement';
    return;
  }

  clearPanier();
  // Redirection vers la page FedaPay hébergée : le client y choisit son
  // opérateur (MTN/Moov/Celtiis) et confirme avec son code Mobile Money.
  window.location.href = donnees.checkoutUrl;
}

document.getElementById('paiementForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const names = Array.from(document.querySelectorAll('.passenger-name')).map(i => i.value.trim());
  const tel = document.getElementById('contactTel').value.trim();
  const payTel = document.getElementById('payTel').value.trim();
  if(names.some(n => !n) || !tel || !payTel){
    alert('Merci de renseigner le nom de chaque passager ainsi que les numéros de téléphone.');
    return;
  }

  const payBtn = document.getElementById('payBtn');
  const processing = document.getElementById('processing');
  payBtn.disabled = true;
  processing.classList.add('show');

  // Si une commande a déjà été créée avec succès lors d'un essai
  // précédent (paiement raté ensuite), on ne recrée PAS la commande —
  // on retente juste la confirmation du paiement sur la même commande.
  if(codeCommandeEnCours){
    await tenterPaiement();
    return;
  }

  const { ok, statut, donnees } = await appelApi(APP_BASE + '/api/commandes', {
    voyageId: voyage.id,
    sieges: seats,
    passagers: names,
    telephoneContact: tel,
    emailContact: document.getElementById('contactEmail').value.trim() || null,
  });

  if(!ok){
    if(statut === 409){
      afficherErreur((donnees.details && donnees.details.join(', ')) || donnees.erreur || 'Un ou plusieurs sièges viennent d\'être pris. Merci de refaire votre sélection.');
      clearPanier();
      setTimeout(() => window.location.href = APP_BASE + '/siege?voyage=' + voyage.id, 2500);
      return;
    }
    afficherErreur((donnees.details && donnees.details.join(', ')) || donnees.erreur || 'Une erreur est survenue, merci de réessayer.');
    return;
  }

  codeCommandeEnCours = donnees.codeCommande;
  await tenterPaiement();
});

</script>
</body>
</html>
