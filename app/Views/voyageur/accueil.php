
<!DOCTYPE html>
<html lang="fr" data-lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>e-réservation — Réservez votre billet de bus au Bénin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink-950:#0a0c10;
    --ink-900:#12151b;
    --ink-800:#1c212b;
    --ink-700:#2a303c;
    --ink-600:#3a4150;
    --amber-300:#ffcb6b;
    --amber-400:#ffb238;
    --amber-500:#f59e0b;
    --amber-600:#c8790a;
    --paper:#f3ecd9;
    --paper-dim:#e3dabf;
    --text-hi:#f6f2e7;
    --text-mid:#aeb4c0;
    --text-low:#767d8c;
    --line:#2a303c;
    --radius-card:6px;
  }
  html[data-theme="light"]{
    --ink-950:#faf7f0; --ink-900:#f1ebdc; --ink-800:#e8e0cb; --ink-700:#d9cfb0; --ink-600:#c4b896;
    --text-hi:#14171c; --text-mid:#4b5160; --text-low:#6b7280; --line:#d9cfb0;
  }

  *{box-sizing:border-box; margin:0; padding:0;}
  html{scroll-behavior:smooth;}

  body{
    background:var(--ink-950);
    color:var(--text-hi);
    font-family:'Inter', sans-serif;
    line-height:1.5;
    -webkit-font-smoothing:antialiased;
  }

  h1,h2,h3{
    font-family:'Fraunces', serif;
    font-weight:600;
    letter-spacing:-0.01em;
    color:var(--text-hi);
  }

  .mono{
    font-family:'IBM Plex Mono', monospace;
    letter-spacing:0.02em;
  }

  a{color:inherit; text-decoration:none;}
  button{font-family:inherit; cursor:pointer;}

  :focus-visible{
    outline:2px solid var(--amber-400);
    outline-offset:3px;
  }

  .wrap{
    max-width:1180px;
    margin:0 auto;
    padding:0 32px;
  }

  /* ============ HEADER ============ */
  header{
    position:sticky; top:0; z-index:50;
    background:rgba(10,12,16,0.88);
    backdrop-filter:blur(10px);
    border-bottom:1px solid var(--line);
  }
  .header-inner{
    display:flex; align-items:center; justify-content:space-between;
    padding:18px 32px;
    max-width:1180px; margin:0 auto;
  }
  .brand{
    display:flex; flex-direction:column; gap:2px;
  }
  .brand-name{
    font-family:'Fraunces', serif;
    font-size:22px; font-weight:700;
    color:var(--text-hi);
    display:flex; align-items:center; gap:8px;
  }
  .brand-name .dot{
    width:7px; height:7px; border-radius:50%;
    background:var(--amber-400);
    display:inline-block;
  }
  .brand-name img.logo-mark{
    height:42px; width:auto; display:block;
  }
  .footer-col .brand-name img.logo-mark{
    height:34px;
  }
  .brand-slogan{
    font-size:11px;
    font-family:'IBM Plex Mono', monospace;
    color:var(--text-low);
    text-transform:uppercase;
    letter-spacing:0.12em;
  }

  nav{display:flex; align-items:center; gap:36px;}
  .nav-links{display:flex; gap:28px;}
  .nav-links a{
    font-size:14.5px; color:var(--text-mid); font-weight:500;
    position:relative; padding:4px 0;
    transition:color .15s ease;
  }
  .nav-links a:hover{color:var(--text-hi);}
  .nav-links a::after{
    content:''; position:absolute; left:0; bottom:-2px;
    width:0; height:1px; background:var(--amber-400);
    transition:width .2s ease;
  }
  .nav-links a:hover::after{width:100%;}

  .lang-toggle{
    display:flex; align-items:center; gap:2px;
    background:var(--ink-800); border:1px solid var(--line);
    border-radius:100px; padding:3px;
  }
  .lang-toggle button{
    border:none; background:transparent; color:var(--text-low);
    font-family:'IBM Plex Mono', monospace; font-size:12px; font-weight:600;
    padding:6px 12px; border-radius:100px;
    transition:all .15s ease;
  }
  .lang-toggle button.active{
    background:var(--amber-400); color:var(--ink-950);
  }

  .theme-toggle{
    display:flex; align-items:center; justify-content:center;
    width:34px; height:34px; border-radius:50%;
    background:var(--ink-800); border:1px solid var(--line); color:var(--text-mid);
    transition:color .15s ease, border-color .15s ease;
  }
  .theme-toggle:hover{color:var(--amber-400); border-color:var(--amber-400);}
  .theme-toggle svg{width:16px; height:16px;}
  .theme-toggle .icon-sun{display:none;}
  html[data-theme="light"] .theme-toggle .icon-moon{display:none;}
  html[data-theme="light"] .theme-toggle .icon-sun{display:block;}

  .nav-toggle{display:none; background:none; border:none; color:var(--text-hi); font-size:24px;}

  /* ============ HERO ============ */
  .hero{
    position:relative;
    padding:76px 0 0;
    overflow:hidden;
  }
  .hero::before{
    content:'';
    position:absolute; inset:0;
    background:
      radial-gradient(ellipse 900px 500px at 78% 10%, rgba(245,158,11,0.10), transparent 60%),
      radial-gradient(ellipse 600px 400px at 10% 90%, rgba(245,158,11,0.06), transparent 60%);
    pointer-events:none;
  }
  .hero-grid{
    position:relative;
    display:grid;
    grid-template-columns:1.05fr 0.95fr;
    gap:40px;
    align-items:center;
  }
  .hero-copy .eyebrow{
    font-family:'IBM Plex Mono', monospace;
    font-size:12px; color:var(--amber-400);
    text-transform:uppercase; letter-spacing:0.14em;
    display:flex; align-items:center; gap:10px;
    margin-bottom:20px;
  }
  .hero-copy .eyebrow::before{
    content:''; width:20px; height:1px; background:var(--amber-400);
  }
  .hero-copy h1{
    font-size:46px; line-height:1.08;
    margin-bottom:20px;
  }
  .hero-copy h1 em{
    font-style:normal; color:var(--amber-400);
  }
  .hero-copy p{
    font-size:16.5px; color:var(--text-mid);
    max-width:440px; margin-bottom:8px;
  }

  .bus-illustration{
    display:flex; flex-direction:column; align-items:center; justify-content:center;
  }
  .bus-illustration svg{width:100%; max-width:440px; height:auto; overflow:visible;}

  .bus-group{ animation: bus-suspension 0.7s ease-in-out infinite; }
  @keyframes bus-suspension{
    0%, 100%{ transform:translateY(0); }
    50%{ transform:translateY(-2.5px); }
  }
  .wheel{
    transform-box: fill-box;
    transform-origin: center;
    animation: bus-wheel-spin 0.9s linear infinite;
  }
  @keyframes bus-wheel-spin{ to{ transform:rotate(360deg); } }

  .hero-road{
    width:100%; max-width:400px; height:12px; margin-top:4px; border-radius:2px;
    background-image: repeating-linear-gradient(90deg, var(--amber-400) 0 22px, transparent 22px 40px);
    background-size:200% 100%;
    opacity:0.45;
    animation: hero-road-scroll 0.55s linear infinite;
  }
  @keyframes hero-road-scroll{
    from{ background-position-x:0; }
    to{ background-position-x:-40px; }
  }

  .mini-ticket-box{ position:relative; overflow:hidden; }
  .mini-ticket{
    position:absolute; left:50%; bottom:8px; width:30px; height:22px;
    margin-left:-15px;
    background:var(--paper); border-radius:0 0 3px 3px;
    animation: mini-eject 3.2s cubic-bezier(.4,0,.2,1) infinite;
  }
  .mini-stamp{
    position:absolute; left:50%; top:52%; width:14px; height:14px;
    margin:-7px 0 0 -7px; opacity:0; transform:scale(0.4) rotate(-14deg);
    color:var(--amber-400);
    animation: mini-stamp-in 3.2s ease infinite;
  }
  @keyframes mini-eject{
    0%{   transform: translateY(24px); }
    18%{  transform: translateY(0); }
    62%{  transform: translateY(0); }
    82%{  transform: translateY(24px); }
    100%{ transform: translateY(24px); }
  }
  @keyframes mini-stamp-in{
    0%, 24%{  opacity:0; transform:scale(0.4) rotate(-14deg); }
    34%{      opacity:1; transform:scale(1) rotate(-14deg); }
    58%{      opacity:1; transform:scale(1) rotate(-14deg); }
    70%, 100%{opacity:0; transform:scale(0.5) rotate(-14deg); }
  }

  @media (prefers-reduced-motion: reduce){
    .bus-group, .wheel, .hero-road, .mini-ticket, .mini-stamp{ animation:none !important; }
  }

  /* ============ TICKET SEARCH CARD ============ */
  .ticket-card{
    position:relative;
    background:var(--paper);
    color:var(--ink-950);
    border-radius:var(--radius-card);
    margin-top:36px;
    max-width:640px;
    box-shadow:0 30px 60px -20px rgba(0,0,0,0.55), 0 2px 0 rgba(0,0,0,0.2);
    display:flex;
    transform:rotate(-0.4deg);
  }
  .ticket-main{
    flex:1;
    padding:26px 22px;
    display:grid;
    grid-template-columns:1fr auto 1fr 1fr auto;
    gap:14px;
    align-items:end;
  }
  .ticket-field{display:flex; flex-direction:column; gap:6px; min-width:0;}
  .ticket-field label{
    font-family:'IBM Plex Mono', monospace;
    font-size:10px; text-transform:uppercase; letter-spacing:0.1em;
    color:#6b6450;
  }
  .ticket-field input, .ticket-field select{
    border:none; background:transparent;
    font-family:'Fraunces', serif; font-size:17px; font-weight:600;
    color:var(--ink-950);
    padding:2px 0;
    border-bottom:1.5px solid #d5ccae;
    width:100%;
  }
  .ticket-field input::placeholder{color:#9a9276; font-weight:500;}
  .ticket-field input:focus, .ticket-field select:focus{
    outline:none; border-bottom-color:var(--amber-600);
  }
  .swap-btn{
    width:30px; height:30px; border-radius:50%;
    border:1.5px solid #d5ccae; background:var(--paper);
    display:flex; align-items:center; justify-content:center;
    color:#6b6450; font-size:14px;
    align-self:center; margin-bottom:4px;
    transition:transform .2s ease, border-color .2s ease;
  }
  .swap-btn:hover{transform:rotate(180deg); border-color:var(--amber-600); color:var(--amber-600);}

  .ticket-cta{
    background:var(--amber-400);
    color:var(--ink-950);
    border:none;
    font-weight:700; font-size:14px;
    padding:0 20px;
    display:flex; align-items:center; gap:6px;
    border-radius:4px;
    align-self:end;
    height:44px;
    transition:background .15s ease;
    white-space:nowrap;
  }
  .ticket-cta:hover{background:var(--amber-300);}

  /* perforation stub */
  .ticket-stub{
    width:72px;
    border-left:2px dashed #cfc6a6;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:8px;
    padding:14px 0;
    position:relative;
  }
  .ticket-stub::before, .ticket-stub::after{
    content:'';
    position:absolute; left:-9px;
    width:18px; height:18px; border-radius:50%;
    background:var(--ink-950);
  }
  .ticket-stub::before{top:-9px;}
  .ticket-stub::after{bottom:-9px;}
  .ticket-stub .route{
    writing-mode:vertical-rl;
    font-family:'IBM Plex Mono', monospace;
    font-size:10px; letter-spacing:0.15em;
    color:#8a8260;
  }
  .ticket-stub .mark{
    font-family:'IBM Plex Mono', monospace;
    font-size:9px; color:#b0a880;
    writing-mode:vertical-rl;
  }

  /* ============ PERFORATION DIVIDER ============ */
  .perf-divider{
    display:flex; align-items:center; gap:6px;
    padding:64px 0 0;
    max-width:1180px; margin:0 auto; padding-left:32px; padding-right:32px;
    opacity:0.5;
  }
  .perf-divider span{
    width:5px; height:5px; border-radius:50%;
    background:var(--ink-700);
    flex-shrink:0;
  }
  .perf-divider .line{
    flex:1; height:1px; background:var(--line);
  }

  /* ============ HOW IT WORKS ============ */
  .how{padding:64px 0 96px;}
  .how-head{
    display:flex; justify-content:space-between; align-items:flex-end;
    margin-bottom:48px; gap:24px; flex-wrap:wrap;
  }
  .how-head h2{font-size:30px;}
  .how-head p{color:var(--text-mid); max-width:380px; font-size:14.5px;}

  .steps{
    display:grid; grid-template-columns:repeat(4, 1fr); gap:0;
    position:relative;
  }
  .step{
    padding:0 22px 0 0;
    position:relative;
  }
  .step:not(:last-child)::after{
    content:'';
    position:absolute; top:26px; right:0;
    width:22px; height:1px;
    background:repeating-linear-gradient(90deg, var(--ink-600) 0 5px, transparent 5px 9px);
  }
  .step-icon{
    width:52px; height:52px;
    border:1.5px solid var(--line);
    border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    color:var(--amber-400);
    margin-bottom:20px;
    background:var(--ink-900);
  }
  .step-num{
    font-family:'IBM Plex Mono', monospace;
    font-size:11px; color:var(--text-low);
    margin-bottom:8px; display:block;
  }
  .step h3{font-size:17px; margin-bottom:8px; font-weight:600;}
  .step p{font-size:13.5px; color:var(--text-mid); line-height:1.55;}

  /* ============ PARTNER CTA STRIP ============ */
  .partner-strip{
    background:var(--ink-900);
    border-top:1px solid var(--line);
    border-bottom:1px solid var(--line);
    padding:40px 0;
  }
  .partner-strip .inner{
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:20px;
  }
  .partner-strip h3{font-size:19px; margin-bottom:6px;}
  .partner-strip p{color:var(--text-mid); font-size:14px;}
  .partner-cta{
    background:transparent;
    border:1.5px solid var(--amber-400);
    color:var(--amber-400);
    font-weight:600; font-size:14px;
    padding:12px 22px; border-radius:4px;
    display:inline-flex; align-items:center; gap:8px;
    transition:all .15s ease;
    white-space:nowrap;
  }
  .partner-cta:hover{background:var(--amber-400); color:var(--ink-950);}

  /* ============ FOOTER ============ */
  footer{padding:64px 0 0; background:var(--ink-950);}
  .footer-grid{
    display:grid; grid-template-columns:1.4fr 1fr 1fr 1fr;
    gap:40px;
    padding-bottom:48px;
    border-bottom:1px solid var(--line);
  }
  .footer-col h4{
    font-family:'IBM Plex Mono', monospace;
    font-size:11px; text-transform:uppercase; letter-spacing:0.1em;
    color:var(--text-low); margin-bottom:16px;
  }
  .footer-col p{font-size:13.5px; color:var(--text-mid); line-height:1.7; max-width:260px;}
  .footer-col ul{list-style:none;}
  .footer-col li{margin-bottom:10px;}
  .footer-col a{font-size:13.5px; color:var(--text-mid); transition:color .15s ease;}
  .footer-col a:hover{color:var(--amber-400);}

  .social-pill{
    display:inline-flex; align-items:center; gap:6px;
    background:var(--ink-800); border:1px solid var(--line);
    border-radius:100px; padding:6px;
    margin-top:16px;
  }
  .social-pill a{
    position:relative;
    width:38px; height:38px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    color:var(--text-mid); background:transparent;
    transition:background .25s ease, color .25s ease, transform .25s ease;
  }
  .social-pill a svg{width:18px; height:18px; position:relative; z-index:1;}
  .social-pill a:hover{
    color:#fff;
    transform:translateY(-4px);
  }
  .social-pill a[data-brand="facebook"]:hover{background:#1877F2; box-shadow:0 8px 18px -6px rgba(24,119,242,0.6);}
  .social-pill a[data-brand="instagram"]:hover{
    background:radial-gradient(circle at 30% 110%, #ffdb73 0%, #fdb32f 22%, #ee2a7b 55%, #a33bcf 78%, #4776e6 100%);
    box-shadow:0 8px 18px -6px rgba(238,42,123,0.55);
  }
  .social-pill a[data-brand="whatsapp"]:hover{background:#25D366; box-shadow:0 8px 18px -6px rgba(37,211,102,0.6);}

  .social-pill a::after{
    content:attr(data-label);
    position:absolute; bottom:calc(100% + 10px); left:50%;
    transform:translateX(-50%) translateY(4px);
    background:var(--paper); color:var(--ink-950);
    font-family:'IBM Plex Mono', monospace; font-size:10px; font-weight:600;
    padding:5px 9px; border-radius:4px; white-space:nowrap;
    opacity:0; pointer-events:none;
    transition:opacity .2s ease, transform .2s ease;
  }
  .social-pill a::before{
    content:''; position:absolute; bottom:calc(100% + 5px); left:50%;
    transform:translateX(-50%);
    border:5px solid transparent; border-top-color:var(--paper);
    opacity:0; transition:opacity .2s ease;
  }
  .social-pill a:hover::after, .social-pill a:hover::before{
    opacity:1; transform:translateX(-50%) translateY(0);
  }

  @media (prefers-reduced-motion: reduce){
    .social-pill a{ transition:none; }
  }

  .footer-bottom{
    display:flex; justify-content:space-between; align-items:center;
    padding:22px 0 32px;
    flex-wrap:wrap; gap:10px;
  }
  .footer-bottom p{font-size:12px; color:var(--text-low);}
  .footer-bottom .admin-link{
    font-size:11px; color:var(--ink-700);
    font-family:'IBM Plex Mono', monospace;
  }
  .footer-bottom .admin-link:hover{color:var(--text-low);}

  /* ============ RESPONSIVE ============ */
  @media (max-width:920px){
    .hero-grid{grid-template-columns:1fr;}
    .bus-illustration{order:-1; max-width:320px; margin:0 auto;}
    .hero-copy h1{font-size:36px;}
    .ticket-main{grid-template-columns:1fr; grid-template-areas:none;}
    .swap-btn{display:none;}
    .ticket-cta{width:100%; justify-content:center; margin-top:6px;}
    .steps{grid-template-columns:1fr 1fr; row-gap:36px;}
    .step:nth-child(2)::after{display:none;}
    .footer-grid{grid-template-columns:1fr 1fr; gap:32px;}
  }
  @media (max-width:640px){
    .wrap, .header-inner, .perf-divider{padding-left:20px; padding-right:20px;}
    .nav-links{display:none;}
    .steps{grid-template-columns:1fr;}
    .step::after{display:none;}
    .footer-grid{grid-template-columns:1fr;}
    .ticket-card{transform:none;}
    .ticket-stub{display:none;}
    .hero-copy h1{font-size:30px;}
  }

  @media (prefers-reduced-motion: reduce){
    *{transition:none !important; scroll-behavior:auto !important;}
  }
</style>
</head>
<body>

<header>
  <div class="header-inner">
    <div class="brand">
      <span class="brand-name"><img class="logo-mark" src="<?= url_app('/assets/logo.png') ?>" alt="e-réservation"></span>
      <span class="brand-slogan" data-fr="Votre billet de bus, sans détour" data-en="Your bus ticket, no detour">Votre billet de bus, sans détour</span>
    </div>
    <nav>
      <div class="nav-links">
        <a href="#" data-fr="Accueil" data-en="Home">Accueil</a>
        <a href="#comment-ca-marche" data-fr="Comment ça marche" data-en="How it works">Comment ça marche</a>
        <a href="<?= url_app('/rejoindre') ?>" data-fr="Devenir partenaire" data-en="Become a partner">Devenir partenaire</a>
        <a href="#contact" data-fr="Contact" data-en="Contact">Contact</a>
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

<section class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <span class="eyebrow" data-fr="Réservation de bus au Bénin" data-en="Bus booking in Benin">Réservation de bus au Bénin</span>
      <h1 data-fr="Réservez votre place, <em>sans faire la queue</em> à la gare." data-en="Book your seat, <em>skip the line</em> at the station.">Réservez votre place, <em>sans faire la queue</em> à la gare.</h1>
      <p data-fr="Recherchez un trajet, choisissez votre siège, payez par Mobile Money et recevez votre billet électronique en quelques minutes." data-en="Search a route, pick your seat, pay by Mobile Money and get your e-ticket in minutes.">Recherchez un trajet, choisissez votre siège, payez par Mobile Money et recevez votre billet électronique en quelques minutes.</p>

      <form class="ticket-card" aria-label="Recherche de trajet">
        <div class="ticket-main">
          <div class="ticket-field">
            <label for="depart" data-fr="Départ" data-en="From">Départ</label>
            <input id="depart" type="text" placeholder="Cotonou" autocomplete="off">
          </div>
          <button type="button" class="swap-btn" aria-label="Inverser les villes" data-fr-aria="Inverser les villes" data-en-aria="Swap cities">⇄</button>
          <div class="ticket-field">
            <label for="arrivee" data-fr="Arrivée" data-en="To">Arrivée</label>
            <input id="arrivee" type="text" placeholder="Parakou" autocomplete="off">
          </div>
          <div class="ticket-field">
            <label for="date" data-fr="Date" data-en="Date">Date</label>
            <input id="date" type="date">
          </div>
          <button type="submit" class="ticket-cta">
            <span data-fr="Rechercher" data-en="Search">Rechercher</span>
          </button>
        </div>
        <div class="ticket-stub" aria-hidden="true">
          <span class="route mono">COT → PAR</span>
          <span class="mark mono">e-RES</span>
        </div>
      </form>
    </div>

    <div class="bus-illustration" aria-hidden="true">
      <svg viewBox="0 0 440 300" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g class="bus-group">
          <rect x="46" y="86" width="330" height="140" rx="18" stroke="#FFB238" stroke-width="2.5"/>
          <rect x="46" y="86" width="330" height="34" rx="12" stroke="#FFB238" stroke-width="2.5"/>
          <line x1="46" y1="150" x2="376" y2="150" stroke="#3A4150" stroke-width="1.5"/>
          <rect x="70" y="132" width="44" height="34" rx="4" stroke="#FFB238" stroke-width="2"/>
          <rect x="128" y="132" width="44" height="34" rx="4" stroke="#FFB238" stroke-width="2"/>
          <rect x="186" y="132" width="44" height="34" rx="4" stroke="#FFB238" stroke-width="2"/>
          <rect x="244" y="132" width="44" height="34" rx="4" stroke="#FFB238" stroke-width="2"/>
          <rect x="302" y="132" width="52" height="34" rx="4" stroke="#3A4150" stroke-width="2"/>
          <rect x="46" y="196" width="330" height="10" fill="#FFB238" opacity="0.9"/>
          <rect x="358" y="140" width="18" height="22" rx="3" fill="#FFB238"/>
          <path d="M46 226 L20 226 L20 208 L46 196" stroke="#FFB238" stroke-width="2.5" stroke-linejoin="round"/>

          <g class="wheel">
            <circle cx="120" cy="240" r="26" stroke="#F6F2E7" stroke-width="3"/>
            <line x1="120" y1="218" x2="120" y2="262" stroke="#F6F2E7" stroke-width="1.5"/>
            <line x1="98" y1="240" x2="142" y2="240" stroke="#F6F2E7" stroke-width="1.5"/>
            <circle cx="120" cy="240" r="8" fill="#FFB238"/>
          </g>
          <g class="wheel">
            <circle cx="308" cy="240" r="26" stroke="#F6F2E7" stroke-width="3"/>
            <line x1="308" y1="218" x2="308" y2="262" stroke="#F6F2E7" stroke-width="1.5"/>
            <line x1="286" y1="240" x2="330" y2="240" stroke="#F6F2E7" stroke-width="1.5"/>
            <circle cx="308" cy="240" r="8" fill="#FFB238"/>
          </g>
        </g>
      </svg>
      <div class="hero-road"></div>
    </div>
  </div>
</section>

<div class="perf-divider" aria-hidden="true">
  <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
  <div class="line"></div>
  <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
</div>

<section class="how wrap" id="comment-ca-marche">
  <div class="how-head">
    <h2 data-fr="Comment ça marche" data-en="How it works">Comment ça marche</h2>
    <p data-fr="Du trajet au billet en main, quatre étapes suffisent." data-en="From route to ticket in hand, four steps are enough.">Du trajet au billet en main, quatre étapes suffisent.</p>
  </div>
  <div class="steps">
    <div class="step">
      <span class="step-num mono">01</span>
      <div class="step-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </div>
      <h3 data-fr="Rechercher" data-en="Search">Rechercher</h3>
      <p data-fr="Indiquez votre ville de départ, votre destination et la date de votre voyage." data-en="Enter your departure city, destination and travel date.">Indiquez votre ville de départ, votre destination et la date de votre voyage.</p>
    </div>
    <div class="step">
      <span class="step-num mono">02</span>
      <div class="step-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><rect x="5" y="4" width="14" height="12" rx="2" stroke="currentColor" stroke-width="2"/><path d="M8 20h8M9 16v4M15 16v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </div>
      <h3 data-fr="Choisir un siège" data-en="Choose a seat">Choisir un siège</h3>
      <p data-fr="Sélectionnez votre place sur le plan du bus, en temps réel." data-en="Pick your seat on the live bus plan.">Sélectionnez votre place sur le plan du bus, en temps réel.</p>
    </div>
    <div class="step">
      <span class="step-num mono">03</span>
      <div class="step-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><rect x="3" y="6" width="18" height="13" rx="2" stroke="currentColor" stroke-width="2"/><path d="M3 10h18" stroke="currentColor" stroke-width="2"/></svg>
      </div>
      <h3 data-fr="Payer" data-en="Pay">Payer</h3>
      <p data-fr="Réglez en toute sécurité par Mobile Money, en quelques secondes." data-en="Pay securely by Mobile Money in seconds.">Réglez en toute sécurité par Mobile Money, en quelques secondes.</p>
    </div>
    <div class="step">
      <span class="step-num mono">04</span>
      <div class="step-icon mini-ticket-box">
        <div class="mini-ticket"></div>
        <svg class="mini-stamp" width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M4 12l5 5L20 6" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <h3 data-fr="Recevoir le billet" data-en="Get your ticket">Recevoir le billet</h3>
      <p data-fr="Votre billet électronique avec QR code arrive par SMS et email." data-en="Your e-ticket with QR code arrives by SMS and email.">Votre billet électronique avec QR code arrive par SMS et email.</p>
    </div>
  </div>
</section>

<section class="partner-strip">
  <div class="wrap inner">
    <div>
      <h3 data-fr="Vous êtes une compagnie de transport ?" data-en="Are you a transport company?">Vous êtes une compagnie de transport ?</h3>
      <p data-fr="Vendez vos billets en ligne et modernisez votre gestion." data-en="Sell your tickets online and modernize your operations.">Vendez vos billets en ligne et modernisez votre gestion.</p>
    </div>
    <a href="<?= url_app('/rejoindre') ?>" class="partner-cta">
      <span data-fr="Rejoignez-nous" data-en="Join us">Rejoignez-nous</span> →
    </a>
  </div>
</section>

<footer id="contact">
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
          <li><a href="#" data-fr="Qui sommes-nous" data-en="Who we are">Qui sommes-nous</a></li>
          <li><a href="<?= url_app('/rejoindre') ?>" data-fr="Espace compagnies" data-en="For companies">Espace compagnies</a></li>
          <li>
</li>
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

  const swapBtn = document.querySelector('.swap-btn');
  const depart = document.getElementById('depart');
  const arrivee = document.getElementById('arrivee');
  swapBtn.addEventListener('click', () => {
    const tmp = depart.value;
    depart.value = arrivee.value;
    arrivee.value = tmp;
  });

  document.querySelector('.ticket-card').addEventListener('submit', (e) => {
    e.preventDefault();
    const params = new URLSearchParams();
    if (depart.value.trim()) params.set('depart', depart.value.trim());
    if (arrivee.value.trim()) params.set('arrivee', arrivee.value.trim());
    const dateVal = document.getElementById('date').value;
    if (dateVal) params.set('date', dateVal);
    window.location.href = APP_BASE + '/resultats' + (params.toString() ? '?' + params.toString() : '');
  });
</script>

</body>
</html>
