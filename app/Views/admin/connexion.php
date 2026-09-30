<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Console admin — e-réservation</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#0F0E0C;
    --ink-2:#18160F;
    --ink-3:#211E17;
    --amber:#E8A33D;
    --amber-deep:#C97F1E;
    --cream:#F5EFE3;
    --cream-dim:#9E9686;
    --muted:#6E6858;
    --error:#D9694F;
    --line: rgba(245,239,227,0.09);
    --radius: 3px;
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
      linear-gradient(180deg, rgba(232,163,61,0.05), transparent 40%),
      var(--ink);
    color:var(--cream);
    font-family:'Inter', sans-serif;
    min-height:100vh;
    display:flex;
    flex-direction:column;
    position:relative;
    overflow-x:hidden;
  }
  a{ color:inherit; text-decoration:none; }

  /* faint grid texture, ops-console feel */
  body::before{
    content:'';
    position:fixed; inset:0;
    background-image:
      linear-gradient(rgba(245,239,227,0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(245,239,227,0.025) 1px, transparent 1px);
    background-size: 42px 42px;
    pointer-events:none;
    mask-image: radial-gradient(700px 500px at 50% 40%, black, transparent);
  }

  header{
    display:flex; align-items:center; justify-content:center;
    padding:36px 24px 0;
  }
  .logo-wrap{ display:flex; align-items:center; gap:9px; opacity:0.9; }
  .theme-toggle{
    position:fixed; top:24px; right:24px; z-index:5;
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
  .logo-wrap img{ height:26px; width:auto; }
  .logo-fallback{
    font-family:'Fraunces', serif; font-weight:600; font-size:16px;
  }
  .logo-fallback span{ color:var(--amber); }

  main{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    position:relative;
    z-index:1;
  }

  .console{
    width:100%;
    max-width:360px;
  }

  .console-tag{
    font-family:'IBM Plex Mono', monospace;
    font-size:10.5px;
    letter-spacing:0.16em;
    text-transform:uppercase;
    color:var(--amber);
    text-align:center;
    margin-bottom:10px;
  }
  .console-title{
    font-family:'Fraunces', serif;
    font-weight:600;
    font-size:24px;
    text-align:center;
    margin-bottom:6px;
    letter-spacing:-0.01em;
  }
  .console-sub{
    text-align:center;
    font-size:12.5px;
    color:var(--muted);
    margin-bottom:32px;
  }

  .card{
    background:var(--ink-2);
    border:1px solid var(--line);
    border-radius:6px;
    padding:30px 28px;
    box-shadow: 0 40px 90px -30px rgba(0,0,0,0.7);
  }

  .error-banner{
    display:none;
    background:rgba(217,105,79,0.10);
    border:1px solid rgba(217,105,79,0.3);
    color:var(--error);
    font-size:12px;
    padding:10px 12px;
    border-radius:var(--radius);
    line-height:1.5;
    margin-bottom:18px;
    font-family:'IBM Plex Mono', monospace;
  }
  .error-banner.visible{ display:block; }

  form{ display:flex; flex-direction:column; gap:16px; }
  .field{ display:flex; flex-direction:column; gap:7px; }
  .field label{
    font-family:'IBM Plex Mono', monospace;
    font-size:10.5px;
    letter-spacing:0.08em;
    text-transform:uppercase;
    color:var(--cream-dim);
  }
  .field input{
    background:var(--ink);
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:11px 13px;
    color:var(--cream);
    font-family:'IBM Plex Mono', monospace;
    font-size:13px;
    outline:none;
    transition:border-color .2s ease;
  }
  .field input::placeholder{ color:var(--muted); }
  .field input:focus{ border-color:var(--amber); }
  .field input:focus-visible{ outline:2px solid var(--amber); outline-offset:1px; }

  .password-row{ position:relative; }
  .toggle-visibility{
    position:absolute;
    right:11px; top:33px;
    background:none; border:none; cursor:pointer;
    color:var(--muted);
    font-family:'IBM Plex Mono', monospace;
    font-size:10px;
    letter-spacing:0.04em;
    text-transform:uppercase;
    padding:4px;
  }
  .toggle-visibility:hover{ color:var(--amber); }

  .submit-btn{
    margin-top:6px;
    background:var(--amber);
    color:var(--ink);
    border:none;
    border-radius:var(--radius);
    padding:12px 20px;
    font-family:'IBM Plex Mono', monospace;
    font-weight:600;
    font-size:12.5px;
    letter-spacing:0.05em;
    text-transform:uppercase;
    cursor:pointer;
    transition:background .2s ease, transform .15s ease;
  }
  .submit-btn:hover{ background:var(--amber-deep); }
  .submit-btn:active{ transform:scale(0.99); }
  .submit-btn:focus-visible{ outline:2px solid var(--amber); outline-offset:2px; }

  .security-note{
    display:flex;
    align-items:flex-start;
    gap:8px;
    margin-top:20px;
    padding-top:18px;
    border-top:1px solid var(--line);
    font-size:11px;
    color:var(--muted);
    line-height:1.5;
  }
  .security-note svg{ width:13px; height:13px; flex-shrink:0; margin-top:1px; color:var(--muted); }

  footer{
    text-align:center;
    padding:24px;
    font-size:10.5px;
    color:var(--muted);
    font-family:'IBM Plex Mono', monospace;
    letter-spacing:0.03em;
  }

  @media (prefers-reduced-motion: reduce){
    *{ transition:none !important; animation:none !important; }
  }
</style>
</head>
<body>

<header>
  <span class="logo-wrap">
    <img src="<?= url_app('/assets/logo.png') ?>" alt="e-réservation" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
    <span class="logo-fallback" style="display:none;">e-r<span>é</span>servation</span>
  </span>
  <button type="button" class="theme-toggle" id="themeToggle" aria-label="Changer de thème" title="Mode clair / sombre">
    <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
    <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
  </button>
</header>

<main>
  <div class="console">
    <div class="console-tag">Accès restreint</div>
    <div class="console-title">Console d'administration</div>
    <div class="console-sub">Réservé au super-administrateur de la plateforme</div>

    <div class="card">
      <div class="error-banner<?= $erreur !== null ? ' visible' : '' ?>" id="errorBanner">
        <?= $erreur !== null ? htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') : 'ERR_AUTH · identifiants invalides ou compte non autorisé.' ?>
      </div>

      <form id="loginForm" method="post" novalidate>
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($jetonCsrf, ENT_QUOTES, 'UTF-8') ?>">
        <div class="field">
          <label for="email">Identifiant</label>
          <input type="email" id="email" name="email" placeholder="admin@e-reservation.bj" autocomplete="username" required>
        </div>

        <div class="field password-row">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
          <button type="button" class="toggle-visibility" id="toggleVisibility">Afficher</button>
        </div>

        <button type="submit" class="submit-btn">Accéder à la console</button>
      </form>

      <div class="security-note">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        Connexion journalisée. Toute tentative non autorisée est enregistrée.
      </div>
    </div>
  </div>
</main>

<footer>
  e-réservation · console interne
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

  // La soumission passe maintenant par un vrai POST PHP (voir en haut du
  // fichier) : plus besoin d'intercepter le submit ici.
</script>

</body>
</html>
