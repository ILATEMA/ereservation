<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion compagnie — e-réservation</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#15130F;
    --ink-2:#1E1B15;
    --ink-3:#28241C;
    --amber:#E8A33D;
    --amber-deep:#C97F1E;
    --amber-dim: rgba(232,163,61,0.16);
    --cream:#F5EFE3;
    --cream-dim:#C9C2B0;
    --muted:#8B8272;
    --error:#D9694F;
    --line: rgba(245,239,227,0.10);
    --radius: 4px;
  }
  html[data-theme="light"]{
    --ink:#f7f3e9; --ink-2:#ffffff; --ink-3:#efe9da;
    --cream:#1b1812; --cream-dim:#5b5548; --muted:#736c5c;
    --line: rgba(20,15,5,0.12);
  }
  *{ box-sizing:border-box; margin:0; padding:0; }
  html,body{ height:100%; }
  body{
    background:
      radial-gradient(1200px 600px at 15% -10%, rgba(232,163,61,0.10), transparent 60%),
      radial-gradient(900px 500px at 100% 100%, rgba(232,163,61,0.06), transparent 55%),
      var(--ink);
    color:var(--cream);
    font-family:'Inter', sans-serif;
    min-height:100vh;
    display:flex;
    flex-direction:column;
  }
  a{ color:inherit; text-decoration:none; }

  /* ---- header ---- */
  header{
    display:flex; align-items:center; justify-content:space-between;
    padding:22px 40px;
  }
  .logo-wrap{ display:flex; align-items:center; gap:10px; }
  .logo-wrap img{ height:34px; width:auto; display:block; }
  .logo-fallback{
    font-family:'Fraunces', serif; font-weight:600; font-size:20px; letter-spacing:-0.01em;
  }
  .logo-fallback span{ color:var(--amber); }
  .back-link{
    font-size:13px; color:var(--muted); display:flex; align-items:center; gap:6px;
    transition:color .2s ease;
  }
  .header-right{ display:flex; align-items:center; gap:16px; }
  .theme-toggle{
    display:flex; align-items:center; justify-content:center;
    width:34px; height:34px; border-radius:50%;
    background:var(--ink-2); border:1px solid var(--line); color:var(--cream-dim);
    transition:color .15s ease, border-color .15s ease;
  }
  .theme-toggle:hover{color:var(--amber); border-color:var(--amber);}
  .theme-toggle svg{width:16px; height:16px;}
  .theme-toggle .icon-sun{display:none;}
  html[data-theme="light"] .theme-toggle .icon-moon{display:none;}
  html[data-theme="light"] .theme-toggle .icon-sun{display:block;}
  .back-link:hover{ color:var(--cream); }
  .back-link svg{ width:14px; height:14px; }

  /* ---- main layout ---- */
  main{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
  }

  .stage{
    display:flex;
    width:100%;
    max-width:980px;
    align-items:stretch;
  }

  /* left: context panel */
  .context{
    flex:1;
    padding:56px 48px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    position:relative;
    max-width:420px;
  }
  .eyebrow{
    font-family:'IBM Plex Mono', monospace;
    font-size:11px;
    letter-spacing:0.14em;
    text-transform:uppercase;
    color:var(--amber);
    margin-bottom:18px;
    display:flex; align-items:center; gap:10px;
  }
  .eyebrow::before{
    content:'';
    width:20px; height:1px; background:var(--amber-deep);
  }
  .context h1{
    font-family:'Fraunces', serif;
    font-weight:600;
    font-size:38px;
    line-height:1.12;
    letter-spacing:-0.01em;
    margin-bottom:18px;
  }
  .context p{
    font-size:14.5px;
    line-height:1.65;
    color:var(--cream-dim);
    max-width:340px;
    margin-bottom:32px;
  }
  .context-list{
    list-style:none;
    display:flex;
    flex-direction:column;
    gap:14px;
  }
  .context-list li{
    display:flex;
    align-items:center;
    gap:12px;
    font-size:13px;
    color:var(--cream-dim);
  }
  .context-list .dot{
    width:6px; height:6px; border-radius:50%;
    background:var(--amber);
    flex-shrink:0;
  }
  .context-footer{
    margin-top:44px;
    font-size:12.5px;
    color:var(--muted);
  }
  .context-footer a{ color:var(--amber); border-bottom:1px solid transparent; transition:border-color .2s; }
  .context-footer a:hover{ border-color:var(--amber); }

  /* right: ticket-shaped login card */
  .ticket-wrap{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px 0;
  }
  .ticket{
    width:100%;
    max-width:400px;
    background:var(--ink-2);
    border:1px solid var(--line);
    border-radius:10px;
    box-shadow: 0 30px 80px -20px rgba(0,0,0,0.55);
    overflow:hidden;
    position:relative;
  }
  .ticket-stub{
    padding:22px 32px 20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    border-bottom:none;
  }
  .stub-label{
    font-family:'IBM Plex Mono', monospace;
    font-size:10.5px;
    letter-spacing:0.12em;
    text-transform:uppercase;
    color:var(--muted);
  }
  .stub-code{
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    color:var(--amber);
  }

  /* perforation divider */
  .perforation{
    position:relative;
    height:0;
    border-top:1.5px dashed rgba(245,239,227,0.18);
    margin:0 0;
  }
  .perforation::before, .perforation::after{
    content:'';
    position:absolute;
    top:-9px;
    width:18px; height:18px;
    background:var(--ink);
    border-radius:50%;
  }
  .perforation::before{ left:-9px; }
  .perforation::after{ right:-9px; }

  .ticket-body{
    padding:30px 32px 34px;
  }
  .ticket-body h2{
    font-family:'Fraunces', serif;
    font-weight:600;
    font-size:22px;
    margin-bottom:6px;
    letter-spacing:-0.01em;
  }
  .ticket-body .sub{
    font-size:13px;
    color:var(--muted);
    margin-bottom:28px;
  }

  form{ display:flex; flex-direction:column; gap:18px; }
  .field{ display:flex; flex-direction:column; gap:8px; }
  .field label{
    font-size:12px;
    font-weight:600;
    color:var(--cream-dim);
    letter-spacing:0.01em;
  }
  .field input{
    background:var(--ink-3);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:12px 14px;
    color:var(--cream);
    font-family:'Inter', sans-serif;
    font-size:14px;
    outline:none;
    transition:border-color .2s ease, background .2s ease;
  }
  .field input::placeholder{ color:var(--muted); }
  .field input:focus{
    border-color:var(--amber);
    background:#221E17;
  }
  .field input:focus-visible{
    outline:2px solid var(--amber);
    outline-offset:1px;
  }

  .password-row{ position:relative; }
  .toggle-visibility{
    position:absolute;
    right:12px; top:38px;
    background:none; border:none; cursor:pointer;
    color:var(--muted);
    font-family:'IBM Plex Mono', monospace;
    font-size:11px;
    letter-spacing:0.04em;
    text-transform:uppercase;
    padding:4px;
  }
  .toggle-visibility:hover{ color:var(--amber); }

  .row-between{
    display:flex; align-items:center; justify-content:space-between;
    margin-top:-4px;
  }
  .remember{
    display:flex; align-items:center; gap:8px;
    font-size:12.5px; color:var(--cream-dim);
  }
  .remember input[type="checkbox"]{
    appearance:none;
    width:15px; height:15px;
    border:1px solid var(--line);
    border-radius:3px;
    background:var(--ink-3);
    cursor:pointer;
    position:relative;
    flex-shrink:0;
  }
  .remember input[type="checkbox"]:checked{
    background:var(--amber);
    border-color:var(--amber);
  }
  .remember input[type="checkbox"]:checked::after{
    content:'';
    position:absolute;
    left:4px; top:1px;
    width:4px; height:8px;
    border:solid var(--ink);
    border-width:0 2px 2px 0;
    transform:rotate(45deg);
  }
  .forgot{
    font-size:12.5px;
    color:var(--amber);
    border-bottom:1px solid transparent;
    transition:border-color .2s;
  }
  .forgot:hover{ border-color:var(--amber); }

  .submit-btn{
    margin-top:6px;
    background:var(--amber);
    color:var(--ink);
    border:none;
    border-radius:var(--radius);
    padding:13px 20px;
    font-family:'Inter', sans-serif;
    font-weight:700;
    font-size:14px;
    cursor:pointer;
    letter-spacing:0.01em;
    transition:background .2s ease, transform .15s ease;
    display:flex; align-items:center; justify-content:center; gap:8px;
  }
  .submit-btn:hover{ background:var(--amber-deep); }
  .submit-btn:active{ transform:scale(0.99); }
  .submit-btn:focus-visible{ outline:2px solid var(--amber); outline-offset:2px; }

  .error-banner{
    display:none;
    background:rgba(217,105,79,0.12);
    border:1px solid rgba(217,105,79,0.35);
    color:var(--error);
    font-size:12.5px;
    padding:10px 12px;
    border-radius:var(--radius);
    line-height:1.5;
  }
  .error-banner.visible{ display:block; }

  .divider-text{
    display:flex; align-items:center; gap:12px;
    margin:26px 0 20px;
    color:var(--muted);
    font-size:11px;
    font-family:'IBM Plex Mono', monospace;
    letter-spacing:0.08em;
    text-transform:uppercase;
  }
  .divider-text::before, .divider-text::after{
    content:''; flex:1; height:1px; background:var(--line);
  }

  .apply-cta{
    display:flex; align-items:center; justify-content:space-between;
    background:var(--ink-3);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:14px 16px;
    font-size:13px;
  }
  .apply-cta span{ color:var(--cream-dim); }
  .apply-cta a{
    color:var(--amber); font-weight:600;
    display:flex; align-items:center; gap:5px;
  }

  footer{
    text-align:center;
    padding:24px;
    font-size:11.5px;
    color:var(--muted);
    font-family:'IBM Plex Mono', monospace;
  }

  @media (max-width: 860px){
    .stage{ flex-direction:column; align-items:center; }
    .context{ max-width:520px; padding:8px 12px 32px; text-align:left; }
    .context h1{ font-size:30px; }
    .ticket-wrap{ padding:0; }
  }
  @media (max-width: 480px){
    header{ padding:18px 20px; }
    .ticket-body{ padding:26px 22px 28px; }
    .ticket-stub{ padding:18px 22px 16px; }
  }

  @media (prefers-reduced-motion: reduce){
    *{ transition:none !important; animation:none !important; }
  }
</style>
</head>
<body>

<header>
  <a href="<?= url_app('/') ?>" class="logo-wrap">
    <img src="<?= url_app('/assets/logo.png') ?>" alt="e-réservation" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
    <span class="logo-fallback" style="display:none;">e-r<span>é</span>servation</span>
  </a>
  <div class="header-right">
    <a href="<?= url_app('/') ?>" class="back-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Retour au site
    </a>
    <button type="button" class="theme-toggle" id="themeToggle" aria-label="Changer de thème" title="Mode clair / sombre">
      <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
      <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
    </button>
  </div>
</header>

<main>
  <div class="stage">

    <div class="context">
      <div class="eyebrow">Espace compagnie</div>
      <h1>Pilotez vos voyages depuis un seul endroit.</h1>
      <p>Suivez vos ventes, gérez vos voyages et vos réservations, et gardez une vue claire sur votre activité — en temps réel.</p>
      <ul class="context-list">
        <li><span class="dot"></span> Gestion des voyages et des places</li>
        <li><span class="dot"></span> Suivi des réservations et des ventes</li>
        <li><span class="dot"></span> Statistiques de remplissage</li>
      </ul>
      <div class="context-footer">
        Pas encore partenaire ? <a href="<?= url_app('/rejoindre') ?>">Devenir partenaire</a>
      </div>
    </div>

    <div class="ticket-wrap">
      <div class="ticket">
        <div class="ticket-stub">
          <span class="stub-label">Accès compagnie</span>
          <span class="stub-code">ER · PRO</span>
        </div>
        <div class="perforation"></div>
        <div class="ticket-body">
          <h2>Connexion</h2>
          <p class="sub">Identifiez-vous pour accéder à votre tableau de bord.</p>

          <div class="error-banner<?= $erreur !== null ? ' visible' : '' ?>" id="errorBanner">
            <?= $erreur !== null ? htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') : 'Identifiants incorrects. Vérifiez votre email et votre mot de passe.' ?>
          </div>

          <form id="loginForm" method="post" novalidate>
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($jetonCsrf, ENT_QUOTES, 'UTF-8') ?>">
            <div class="field">
              <label for="email">Email professionnel</label>
              <input type="email" id="email" name="email" placeholder="contact@votrecompagnie.bj" autocomplete="username" required>
            </div>

            <div class="field password-row">
              <label for="password">Mot de passe</label>
              <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
              <button type="button" class="toggle-visibility" id="toggleVisibility">Afficher</button>
            </div>

            <div class="row-between">
              <label class="remember">
                <input type="checkbox" id="remember" name="remember">
                Se souvenir de moi
              </label>
              <a href="#" class="forgot">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="submit-btn">
              Se connecter
            </button>
          </form>

          <div class="divider-text">Nouveau sur e-réservation</div>

          <div class="apply-cta">
            <span>Vous transportez des voyageurs ?</span>
            <a href="<?= url_app('/rejoindre') ?>">Postuler →</a>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<footer>
  e-réservation — espace compagnie · connexion sécurisée
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

  const toggleBtn = document.getElementById('toggleVisibility');
  const passwordInput = document.getElementById('password');
  toggleBtn.addEventListener('click', () => {
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    toggleBtn.textContent = isHidden ? 'Masquer' : 'Afficher';
  });

  // La soumission passe maintenant par un vrai POST PHP (voir en haut du fichier).
</script>

</body>
</html>
