<!DOCTYPE html>
<html lang="fr" data-lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Devenir partenaire — e-réservation</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink-950:#0a0c10; --ink-900:#12151b; --ink-800:#1c212b; --ink-700:#2a303c; --ink-600:#3a4150;
    --amber-300:#ffcb6b; --amber-400:#ffb238; --amber-500:#f59e0b; --amber-600:#c8790a;
    --paper:#f3ecd9; --paper-dim:#e3dabf;
    --text-hi:#f6f2e7; --text-mid:#aeb4c0; --text-low:#767d8c; --line:#2a303c;
    --ok:#7fbf7f;
  }
  html[data-theme="light"]{
    --ink-950:#faf7f0; --ink-900:#f1ebdc; --ink-800:#e8e0cb; --ink-700:#d9cfb0; --ink-600:#c4b896;
    --text-hi:#14171c; --text-mid:#4b5160; --text-low:#6b7280; --line:#d9cfb0;
  }
  *{box-sizing:border-box; margin:0; padding:0;}
  html{scroll-behavior:smooth;}
  body{background:var(--ink-950); color:var(--text-hi); font-family:'Inter', sans-serif; line-height:1.5; -webkit-font-smoothing:antialiased;}
  h1,h2,h3{font-family:'Fraunces', serif; font-weight:600; letter-spacing:-0.01em; color:var(--text-hi);}
  .mono{font-family:'IBM Plex Mono', monospace; letter-spacing:0.02em;}
  a{color:inherit; text-decoration:none;}
  button{font-family:inherit; cursor:pointer;}
  :focus-visible{outline:2px solid var(--amber-400); outline-offset:3px;}
  .wrap{max-width:1180px; margin:0 auto; padding:0 32px;}

  /* header (identique à l'accueil) */
  header{position:sticky; top:0; z-index:50; background:rgba(10,12,16,0.88); backdrop-filter:blur(10px); border-bottom:1px solid var(--line);}
  .header-inner{display:flex; align-items:center; justify-content:space-between; padding:18px 32px; max-width:1180px; margin:0 auto;}
  .brand{display:flex; flex-direction:column; gap:2px;}
  .brand-name{font-family:'Fraunces', serif; font-size:22px; font-weight:700; color:var(--text-hi); display:flex; align-items:center; gap:8px;}
  .brand-name .dot{width:7px; height:7px; border-radius:50%; background:var(--amber-400); display:inline-block;}
  .brand-name img.logo-mark{height:42px; width:auto; display:block;}
  .footer-col .brand-name img.logo-mark{height:34px;}
  .brand-slogan{font-size:11px; font-family:'IBM Plex Mono', monospace; color:var(--text-low); text-transform:uppercase; letter-spacing:0.12em;}
  nav{display:flex; align-items:center; gap:36px;}
  .nav-links{display:flex; gap:28px;}
  .nav-links a{font-size:14.5px; color:var(--text-mid); font-weight:500; position:relative; padding:4px 0; transition:color .15s ease;}
  .nav-links a:hover{color:var(--text-hi);}
  .nav-links a.current{color:var(--amber-400);}
  .lang-toggle{display:flex; align-items:center; gap:2px; background:var(--ink-800); border:1px solid var(--line); border-radius:100px; padding:3px;}
  .lang-toggle button{border:none; background:transparent; color:var(--text-low); font-family:'IBM Plex Mono', monospace; font-size:12px; font-weight:600; padding:6px 12px; border-radius:100px; transition:all .15s ease;}
  .lang-toggle button.active{background:var(--amber-400); color:var(--ink-950);}
  .theme-toggle{display:flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:50%; background:var(--ink-800); border:1px solid var(--line); color:var(--text-mid); transition:color .15s ease, border-color .15s ease;}
  .theme-toggle:hover{color:var(--amber-400); border-color:var(--amber-400);}
  .theme-toggle svg{width:16px; height:16px;}
  .theme-toggle .icon-sun{display:none;}
  html[data-theme="light"] .theme-toggle .icon-moon{display:none;}
  html[data-theme="light"] .theme-toggle .icon-sun{display:block;}

  /* hero page */
  .page-hero{padding:64px 0 20px; position:relative;}
  .page-hero::before{
    content:''; position:absolute; inset:0;
    background:radial-gradient(ellipse 800px 420px at 85% 0%, rgba(245,158,11,0.09), transparent 60%);
    pointer-events:none;
  }
  .breadcrumb{font-family:'IBM Plex Mono', monospace; font-size:11px; color:var(--text-low); margin-bottom:18px; display:flex; gap:8px; align-items:center;}
  .breadcrumb a:hover{color:var(--amber-400);}
  .page-hero .eyebrow{font-family:'IBM Plex Mono', monospace; font-size:12px; color:var(--amber-400); text-transform:uppercase; letter-spacing:0.14em; margin-bottom:16px; display:flex; align-items:center; gap:10px;}
  .page-hero .eyebrow::before{content:''; width:20px; height:1px; background:var(--amber-400);}
  .page-hero h1{font-size:40px; line-height:1.12; max-width:680px; margin-bottom:16px;}
  .page-hero h1 em{font-style:normal; color:var(--amber-400);}
  .page-hero p.lead{font-size:16px; color:var(--text-mid); max-width:560px;}

  /* value props */
  .values{padding:56px 0; display:grid; grid-template-columns:repeat(3,1fr); gap:1px; background:var(--line); border:1px solid var(--line);}
  .value-card{background:var(--ink-950); padding:30px 26px;}
  .value-icon{width:42px; height:42px; border:1.5px solid var(--line); border-radius:10px; display:flex; align-items:center; justify-content:center; color:var(--amber-400); margin-bottom:18px; background:var(--ink-900);}
  .value-card h3{font-size:16.5px; margin-bottom:8px;}
  .value-card p{font-size:13.5px; color:var(--text-mid); line-height:1.6;}

  /* pricing ticket */
  .pricing-section{padding:20px 0 64px;}
  .pricing-grid{display:grid; grid-template-columns:1.1fr 0.9fr; gap:36px; align-items:stretch;}
  .pricing-copy h2{font-size:26px; margin-bottom:14px;}
  .pricing-copy p{color:var(--text-mid); font-size:14.5px; margin-bottom:14px; max-width:460px;}
  .pricing-list{list-style:none; margin-top:18px;}
  .pricing-list li{display:flex; gap:12px; margin-bottom:14px; font-size:14px; color:var(--text-hi);}
  .pricing-list li svg{flex-shrink:0; margin-top:2px; color:var(--ok);}

  .price-ticket{
    background:var(--paper); color:var(--ink-950); border-radius:6px;
    padding:0; position:relative; box-shadow:0 30px 60px -20px rgba(0,0,0,0.55);
    display:flex; flex-direction:column; overflow:hidden;
  }
  .price-ticket .top{padding:26px 26px 20px; border-bottom:2px dashed #cfc6a6; position:relative;}
  .price-ticket .top::before, .price-ticket .top::after{
    content:''; position:absolute; bottom:-9px; width:18px; height:18px; border-radius:50%; background:var(--ink-950);
  }
  .price-ticket .top::before{left:-9px;}
  .price-ticket .top::after{right:-9px;}
  .price-ticket .tag{font-family:'IBM Plex Mono', monospace; font-size:11px; text-transform:uppercase; letter-spacing:0.1em; color:#6b6450; margin-bottom:10px;}
  .price-ticket .amount{font-family:'Fraunces', serif; font-size:38px; font-weight:700; line-height:1;}
  .price-ticket .amount span{font-size:16px; font-weight:600; color:#6b6450;}
  .price-ticket .trial{
    display:inline-flex; align-items:center; gap:6px; margin-top:12px;
    background:var(--amber-400); color:var(--ink-950); font-weight:700; font-size:12px;
    padding:5px 10px; border-radius:100px;
  }
  .price-ticket .bottom{padding:20px 26px 26px; flex:1; display:flex; flex-direction:column;}
  .price-ticket .bottom ul{list-style:none; flex:1;}
  .price-ticket .bottom li{font-size:13px; color:#4a4432; margin-bottom:10px; padding-left:16px; position:relative;}
  .price-ticket .bottom li::before{content:'—'; position:absolute; left:0; color:#8a8260;}
  .price-ticket .code{font-family:'IBM Plex Mono', monospace; font-size:10px; color:#9a9276; margin-top:16px; letter-spacing:0.08em;}

  /* form */
  .form-section{padding:20px 0 96px;}
  .form-card{
    background:var(--ink-900); border:1px solid var(--line); border-radius:8px;
    padding:44px; max-width:760px; margin:0 auto;
  }
  .form-card h2{font-size:24px; margin-bottom:8px;}
  .form-card > p{color:var(--text-mid); font-size:14px; margin-bottom:32px;}
  .form-grid{display:grid; grid-template-columns:1fr 1fr; gap:22px;}
  .form-field{display:flex; flex-direction:column; gap:8px;}
  .form-field.full{grid-column:1/-1;}
  .form-field label{font-size:12.5px; font-weight:600; color:var(--text-mid);}
  .form-field label .opt{font-weight:400; color:var(--text-low); font-style:italic;}
  .form-field input, .form-field textarea, .form-field select{
    background:var(--ink-800); border:1px solid var(--ink-600); border-radius:5px;
    padding:12px 14px; color:var(--text-hi); font-family:'Inter', sans-serif; font-size:14.5px;
  }
  .form-field input::placeholder, .form-field textarea::placeholder{color:var(--text-low);}
  .form-field input:focus, .form-field textarea:focus, .form-field select:focus{outline:none; border-color:var(--amber-400);}
  .form-field textarea{resize:vertical; min-height:100px;}
  .file-drop{
    border:1.5px dashed var(--ink-600); border-radius:5px; padding:22px;
    display:flex; align-items:center; gap:14px; color:var(--text-low); font-size:13px;
    background:var(--ink-800); cursor:pointer;
  }
  .file-drop:hover{border-color:var(--amber-400);}
  .file-drop svg{flex-shrink:0; color:var(--amber-400);}
  .file-drop input[type=file]{display:none;}

  .submit-row{display:flex; align-items:center; justify-content:space-between; margin-top:32px; flex-wrap:wrap; gap:16px;}
  .submit-btn{
    background:var(--amber-400); color:var(--ink-950); border:none; font-weight:700; font-size:14.5px;
    padding:14px 28px; border-radius:5px; transition:background .15s ease;
  }
  .submit-btn:hover{background:var(--amber-300);}
  .submit-note{font-size:12px; color:var(--text-low); max-width:280px;}

  /* footer (identique) */
  footer{padding:64px 0 0; background:var(--ink-950); border-top:1px solid var(--line); margin-top:40px;}
  .footer-grid{display:grid; grid-template-columns:1.4fr 1fr 1fr 1fr; gap:40px; padding-bottom:48px; border-bottom:1px solid var(--line);}
  .footer-col h4{font-family:'IBM Plex Mono', monospace; font-size:11px; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-low); margin-bottom:16px;}
  .footer-col p{font-size:13.5px; color:var(--text-mid); line-height:1.7; max-width:260px;}
  .footer-col ul{list-style:none;}
  .footer-col li{margin-bottom:10px;}
  .footer-col a{font-size:13.5px; color:var(--text-mid); transition:color .15s ease;}
  .footer-col a:hover{color:var(--amber-400);}
  .social-pill{display:inline-flex; align-items:center; gap:6px; background:var(--ink-800); border:1px solid var(--line); border-radius:100px; padding:6px; margin-top:16px;}
  .social-pill a{position:relative; width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--text-mid); background:transparent; transition:background .25s ease, color .25s ease, transform .25s ease;}
  .social-pill a svg{width:18px; height:18px; position:relative; z-index:1;}
  .social-pill a:hover{color:#fff; transform:translateY(-4px);}
  .social-pill a[data-brand="facebook"]:hover{background:#1877F2; box-shadow:0 8px 18px -6px rgba(24,119,242,0.6);}
  .social-pill a[data-brand="instagram"]:hover{background:radial-gradient(circle at 30% 110%, #ffdb73 0%, #fdb32f 22%, #ee2a7b 55%, #a33bcf 78%, #4776e6 100%); box-shadow:0 8px 18px -6px rgba(238,42,123,0.55);}
  .social-pill a[data-brand="whatsapp"]:hover{background:#25D366; box-shadow:0 8px 18px -6px rgba(37,211,102,0.6);}
  .social-pill a::after{content:attr(data-label); position:absolute; bottom:calc(100% + 10px); left:50%; transform:translateX(-50%) translateY(4px); background:var(--paper); color:var(--ink-950); font-family:'IBM Plex Mono', monospace; font-size:10px; font-weight:600; padding:5px 9px; border-radius:4px; white-space:nowrap; opacity:0; pointer-events:none; transition:opacity .2s ease, transform .2s ease;}
  .social-pill a::before{content:''; position:absolute; bottom:calc(100% + 5px); left:50%; transform:translateX(-50%); border:5px solid transparent; border-top-color:var(--paper); opacity:0; transition:opacity .2s ease;}
  .social-pill a:hover::after, .social-pill a:hover::before{opacity:1; transform:translateX(-50%) translateY(0);}
  @media (prefers-reduced-motion: reduce){ .social-pill a{ transition:none; } }
  .footer-bottom{display:flex; justify-content:space-between; align-items:center; padding:22px 0 32px; flex-wrap:wrap; gap:10px;}
  .footer-bottom p{font-size:12px; color:var(--text-low);}
  .footer-bottom .admin-link{font-size:11px; color:var(--ink-700); font-family:'IBM Plex Mono', monospace;}
  .footer-bottom .admin-link:hover{color:var(--text-low);}

  @media (max-width:920px){
    .values{grid-template-columns:1fr;}
    .pricing-grid{grid-template-columns:1fr;}
    .form-grid{grid-template-columns:1fr;}
    .footer-grid{grid-template-columns:1fr 1fr; gap:32px;}
    .page-hero h1{font-size:32px;}
  }
  @media (max-width:640px){
    .wrap, .header-inner{padding-left:20px; padding-right:20px;}
    .nav-links{display:none;}
    .form-card{padding:28px 22px;}
    .footer-grid{grid-template-columns:1fr;}
  }
  @media (prefers-reduced-motion: reduce){*{transition:none !important; scroll-behavior:auto !important;}}
</style>
</head>
<body>

<header>
  <div class="header-inner">
    <div class="brand">
      <a href="<?= url_app('/') ?>" class="brand-name"><img class="logo-mark" src="<?= url_app('/assets/logo.png') ?>" alt="e-réservation"></a>
      <span class="brand-slogan" data-fr="Votre billet de bus, sans détour" data-en="Your bus ticket, no detour">Votre billet de bus, sans détour</span>
    </div>
    <nav>
      <div class="nav-links">
        <a href="<?= url_app('/') ?>" data-fr="Accueil" data-en="Home">Accueil</a>
        <a href="<?= url_app('/#comment-ca-marche') ?>" data-fr="Comment ça marche" data-en="How it works">Comment ça marche</a>
        <a href="<?= url_app('/rejoindre') ?>" class="current" data-fr="Devenir partenaire" data-en="Become a partner">Devenir partenaire</a>
        <a href="<?= url_app('/#contact') ?>" data-fr="Contact" data-en="Contact">Contact</a>
      </div>
      <div class="lang-toggle" role="group" aria-label="Langue">
        <button type="button" class="active" data-set-lang="fr">FR</button>
        <button type="button" data-set-lang="en">EN</button>
      </div>
      <button type="button" class="theme-toggle" id="themeToggle" aria-label="Changer de thème" title="Mode clair / sombre">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
      </button>
    </nav>
  </div>
</header>

<section class="page-hero wrap">
  <div class="breadcrumb"><a href="<?= url_app('/') ?>">Accueil</a><span>/</span><span data-fr="Devenir partenaire" data-en="Become a partner">Devenir partenaire</span></div>
  <span class="eyebrow" data-fr="Espace compagnies" data-en="For companies">Espace compagnies</span>
  <h1 data-fr="Vendez vos billets en ligne, <em>sans commission cachée</em>." data-en="Sell your tickets online, <em>with no hidden commission</em>.">Vendez vos billets en ligne, <em>sans commission cachée</em>.</h1>
  <p class="lead" data-fr="e-réservation connecte votre compagnie aux voyageurs de tout le Bénin. Vous gardez l'intégralité du prix de vos billets et payez un abonnement fixe, prévisible, avec 1 mois d'essai gratuit." data-en="e-réservation connects your company to travelers across Benin. You keep the full ticket price and pay a fixed, predictable subscription, with a 1-month free trial.">e-réservation connecte votre compagnie aux voyageurs de tout le Bénin. Vous gardez l'intégralité du prix de vos billets et payez un abonnement fixe, prévisible, avec 1 mois d'essai gratuit.</p>
</section>

<section class="wrap values">
  <div class="value-card">
    <div class="value-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 17l6-6 4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
    <h3 data-fr="Vendez plus, sans agence" data-en="Sell more, no agency needed">Vendez plus, sans agence</h3>
    <p data-fr="Vos voyages sont visibles par tous les voyageurs qui recherchent un trajet, 24h/24, sans passer par un guichet." data-en="Your trips are visible to every traveler searching a route, 24/7, with no counter needed.">Vos voyages sont visibles par tous les voyageurs qui recherchent un trajet, 24h/24, sans passer par un guichet.</p>
  </div>
  <div class="value-card">
    <div class="value-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect x="4" y="4" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/><rect x="13" y="4" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/><rect x="4" y="13" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/><rect x="13" y="13" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/></svg></div>
    <h3 data-fr="Un tableau de bord clair" data-en="A clear dashboard">Un tableau de bord clair</h3>
    <p data-fr="Suivez vos réservations, vos passagers et vos statistiques de vente en temps réel, depuis un seul espace." data-en="Track your bookings, passengers and sales stats in real time, from one place.">Suivez vos réservations, vos passagers et vos statistiques de vente en temps réel, depuis un seul espace.</p>
  </div>
  <div class="value-card">
    <div class="value-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></div>
    <h3 data-fr="Vous gardez tout le prix du billet" data-en="You keep the full ticket price">Vous gardez tout le prix du billet</h3>
    <p data-fr="Aucune commission prélevée sur vos ventes. Le prix de vos billets vous revient intégralement." data-en="No commission taken on your sales. Your ticket price comes back to you in full.">Aucune commission prélevée sur vos ventes. Le prix de vos billets vous revient intégralement.</p>
  </div>
</section>

<section class="wrap pricing-section">
  <div class="pricing-grid">
    <div class="pricing-copy">
      <h2 data-fr="Une tarification simple et transparente" data-en="Simple, transparent pricing">Une tarification simple et transparente</h2>
      <p data-fr="Pas de pourcentage prélevé sur vos ventes. e-réservation ajoute de petits frais de service, déjà inclus dans le prix affiché au voyageur — vous, vous recevez le prix intégral du billet." data-en="No percentage taken from your sales. e-réservation adds a small service fee, already included in the price shown to travelers — you receive the full ticket price.">Pas de pourcentage prélevé sur vos ventes. e-réservation ajoute de petits frais de service, déjà inclus dans le prix affiché au voyageur — vous, vous recevez le prix intégral du billet.</p>
      <ul class="pricing-list">
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M8 12l3 3 5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span data-fr="1 mois d'essai gratuit, activé après vérification de votre compagnie." data-en="1-month free trial, activated after your company is verified.">1 mois d'essai gratuit, activés après vérification de votre compagnie.</span>
        </li>
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M8 12l3 3 5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span data-fr="Ensuite, un abonnement mensuel fixe, quel que soit le nombre de billets vendus." data-en="Then, a fixed monthly subscription, no matter how many tickets you sell.">Ensuite, un abonnement mensuel fixe, quel que soit le nombre de billets vendus.</span>
        </li>
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M8 12l3 3 5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span data-fr="Paiement de l'abonnement par Mobile Money, en quelques clics." data-en="Subscription payment by Mobile Money, in a few clicks.">Paiement de l'abonnement par Mobile Money, en quelques clics.</span>
        </li>
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M8 12l3 3 5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span data-fr="Une période de grâce de 7 jours en cas de retard de paiement, avant toute suspension." data-en="A 7-day grace period in case of late payment, before any suspension.">Une période de grâce de 7 jours en cas de retard de paiement, avant toute suspension.</span>
        </li>
      </ul>
    </div>

    <div class="price-ticket">
      <div class="top">
        <div class="tag" data-fr="Abonnement compagnie" data-en="Company subscription">Abonnement compagnie</div>
        <div class="amount">20 000 <span data-fr="FCFA / mois" data-en="FCFA / month">FCFA / mois</span></div>
        <div class="trial">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2L12 16.4 5.7 21l2.3-7.2-6-4.6h7.6z" fill="currentColor"/></svg>
          <span data-fr="1 mois offert" data-en="1 month free">1 mois offert</span>
        </div>
      </div>
      <div class="bottom">
        <ul>
          <li data-fr="Nombre illimité de véhicules et de voyages" data-en="Unlimited vehicles and trips">Nombre illimité de véhicules et de voyages</li>
          <li data-fr="Tableau de bord et statistiques de vente" data-en="Dashboard and sales statistics">Tableau de bord et statistiques de vente</li>
          <li data-fr="Gestion des réservations et des passagers" data-en="Booking and passenger management">Gestion des réservations et des passagers</li>
          <li data-fr="0% de commission sur vos ventes" data-en="0% commission on your sales">0% de commission sur vos ventes</li>
          <li data-fr="Résiliable à tout moment" data-en="Cancel anytime">Résiliable à tout moment</li>
        </ul>
        <div class="code mono">e-RES · ABONNEMENT · N°001</div>
      </div>
    </div>
  </div>
</section>

<section class="wrap form-section" id="formulaire">
  <div class="form-card">
    <h2 data-fr="Inscrivez votre compagnie" data-en="Register your company">Inscrivez votre compagnie</h2>
    <p data-fr="Remplissez ce formulaire, notre équipe vous contacte sous peu pour valider votre compagnie et activer votre essai gratuit." data-en="Fill in this form, our team will contact you shortly to verify your company and activate your free trial.">Remplissez ce formulaire, notre équipe vous contacte sous peu pour valider votre compagnie et activer votre essai gratuit.</p>

    <?php if ($succes): ?>
    <div class="form-card" style="text-align:center;">
      <h2>Demande envoyée ✅</h2>
      <p>Votre compagnie a bien été enregistrée. Notre équipe va examiner votre demande et activera votre essai gratuit de 1 mois après validation. Vous pourrez vous connecter dès l'activation via <a href="<?= url_app('/compagnie/connexion') ?>">l'espace compagnie</a>.</p>
    </div>
    <?php else: ?>

    <?php if ($erreurs !== []): ?>
    <div class="form-card" style="border-color:#dc2626; margin-bottom:16px;">
      <p style="color:#dc2626; margin:0;"><?php foreach ($erreurs as $e) echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8') . '<br>'; ?></p>
    </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($jetonCsrf, ENT_QUOTES, 'UTF-8') ?>">
      <div class="form-grid">
        <div class="form-field full">
          <label for="nom-compagnie" data-fr="Nom de la compagnie" data-en="Company name">Nom de la compagnie</label>
          <input id="nom-compagnie" name="nom_compagnie" type="text" placeholder="Ex : Confort Lines" required>
        </div>

        <div class="form-field">
          <label for="ville" data-fr="Ville / siège social" data-en="City / headquarters">Ville / siège social</label>
          <input id="ville" name="ville" type="text" placeholder="Cotonou" required>
        </div>

        <div class="form-field">
          <label for="vehicules" data-fr="Nombre de véhicules" data-en="Number of vehicles">Nombre de véhicules</label>
          <input id="vehicules" name="vehicules" type="number" min="1" placeholder="Ex : 8" required>
        </div>

        <div class="form-field">
          <label for="telephone" data-fr="Téléphone" data-en="Phone">Téléphone</label>
          <input id="telephone" name="telephone" type="tel" placeholder="+229 XX XX XX XX" required>
        </div>

        <div class="form-field">
          <label for="email" data-fr="Email" data-en="Email">Email</label>
          <input id="email" name="email" type="email" placeholder="contact@macompagnie.bj" required>
        </div>

        <div class="form-field full">
          <p style="font-size:13px; color:var(--text-mid); margin:0;">Un mot de passe sera automatiquement généré et envoyé à cette adresse email dès que votre demande sera validée par notre équipe.</p>
        </div>

        <div class="form-field full">
          <label for="message" data-fr="Présentation de votre compagnie <span class=\"opt\">(optionnel)</span>" data-en="Company presentation <span class=\"opt\">(optional)</span>">Présentation de votre compagnie <span class="opt">(optionnel)</span></label>
          <textarea id="message" name="message" placeholder="Vos lignes desservies, votre flotte, votre ancienneté..."></textarea>
        </div>

        <div class="form-field full">
          <label for="documents" data-fr="Registre de commerce ou autre document justificatif <span class=\"opt\">(optionnel)</span>" data-en="Business registration or supporting document <span class=\"opt\">(optional)</span>">Registre de commerce ou autre document justificatif <span class="opt">(optionnel — pas encore traité par le serveur)</span></label>
          <label class="file-drop" for="documents">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3v12m0-12l-4 4m4-4l4 4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span data-fr="Cliquez pour ajouter un fichier (PDF, JPG — 10 Mo max)" data-en="Click to add a file (PDF, JPG — 10MB max)">Cliquez pour ajouter un fichier (PDF, JPG — 10 Mo max)</span>
            <input id="documents" name="documents" type="file" accept=".pdf,.jpg,.jpeg,.png">
          </label>
        </div>
      </div>

      <div class="submit-row">
        <button type="submit" class="submit-btn" data-fr="Envoyer ma demande" data-en="Send my request">Envoyer ma demande</button>
        <p class="submit-note" data-fr="Votre demande sera examinée par notre équipe avant activation de votre espace compagnie." data-en="Your request will be reviewed by our team before your company space is activated.">Votre demande sera examinée par notre équipe avant activation de votre espace compagnie.</p>
      </div>
    </form>
    <?php endif; ?>
  </div>
</section>

<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-col">
        <span class="brand-name" style="font-size:18px;"><img class="logo-mark" src="<?= url_app('/assets/logo.png') ?>" alt="e-réservation"></span>
        <p style="margin-top:14px;" data-fr="La plateforme de réservation de billets de bus au Bénin. Simple, fiable, sans détour." data-en="The bus ticket booking platform in Benin. Simple, reliable, no detour.">La plateforme de réservation de billets de bus au Bénin. Simple, fiable, sans détour.</p>
        <div class="social-pill">
          <a href="#" data-brand="facebook" data-label="Facebook" aria-label="Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7.6h2.6l.4-3h-3v-1.9c0-.87.24-1.46 1.5-1.46h1.6V4.34C15.9 4.24 15 4.15 14 4.15c-2.1 0-3.5 1.28-3.5 3.63v2.62H8v3h2.5V21h3z"/></svg>
          </a>
          <a href="#" data-brand="instagram" data-label="Instagram" aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"/></svg>
          </a>
          <a href="#" data-brand="whatsapp" data-label="WhatsApp" aria-label="WhatsApp">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 00-7.8 13.5L3 21l4.6-1.2A9 9 0 1012 3zm0 2a7 7 0 015.9 10.8l-.2.4.6 2.3-2.3-.6-.4.2A7 7 0 1112 5zm-3.1 3.4c-.2 0-.5 0-.7.3-.2.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.4 3.8 2.1.8 2.6.7 3 .6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.6-.4-.3-.2-1.5-.8-1.8-.9-.2-.1-.4-.1-.6.1-.2.3-.7.9-.8 1-.2.2-.3.2-.5.1-.3-.2-1.1-.4-2.1-1.3-.8-.7-1.3-1.6-1.5-1.9-.1-.3 0-.4.1-.6l.4-.5c.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.6-1.5-.9-2-.2-.5-.4-.4-.6-.4z"/></svg>
          </a>
        </div>
      </div>
      <div class="footer-col">
        <h4 data-fr="Contact" data-en="Contact">Contact</h4>
        <ul>
          <li><a href="tel:+229XXXXXXXX">+229 XX XX XX XX</a></li>
          <li><a href="mailto:contact@e-reservation.bj">contact@e-reservation.bj</a></li>
          <li><a href="#" data-fr="WhatsApp Business" data-en="WhatsApp Business">WhatsApp Business</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4 data-fr="Légal" data-en="Legal">Légal</h4>
        <ul>
          <li><a href="#" data-fr="Conditions générales" data-en="Terms of use">Conditions générales</a></li>
          <li><a href="#" data-fr="Confidentialité" data-en="Privacy">Confidentialité</a></li>
          <li><a href="#" data-fr="Mentions légales" data-en="Legal notice">Mentions légales</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4 data-fr="À propos" data-en="About">À propos</h4>
        <ul>
          <li><a href="<?= url_app('/') ?>" data-fr="Accueil" data-en="Home">Accueil</a></li>
          <li><a href="<?= url_app('/rejoindre') ?>" data-fr="Devenir partenaire" data-en="Become a partner">Devenir partenaire</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p data-fr="© 2026 e-réservation. Tous droits réservés." data-en="© 2026 e-réservation. All rights reserved.">© 2026 e-réservation. Tous droits réservés.</p>
      <a href="<?= url_app('/admin/connexion') ?>" class="admin-link mono">Administration</a>
    </div>
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

  const langButtons = document.querySelectorAll('[data-set-lang]');
  const translatable = document.querySelectorAll('[data-fr]');
  langButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const lang = btn.getAttribute('data-set-lang');
      document.documentElement.setAttribute('data-lang', lang);
      document.documentElement.setAttribute('lang', lang);
      langButtons.forEach(b => b.classList.toggle('active', b === btn));
      translatable.forEach(el => {
        const val = el.getAttribute('data-' + lang);
        if (val !== null) el.innerHTML = val;
      });
    });
  });

  const fileInput = document.getElementById('documents');
  const fileDrop = document.querySelector('.file-drop span');
  fileInput.addEventListener('change', () => {
    if (fileInput.files.length) fileDrop.textContent = fileInput.files[0].name;
  });

  // Le formulaire soumet réellement en POST vers ce même fichier PHP
  // (voir le traitement en haut de rejoindre.php) — plus besoin
  // d'intercepter le submit ici.
</script>

</body>
</html>
