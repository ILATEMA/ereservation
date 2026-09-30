<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Détail compagnie — e-réservation Admin</title>
<style>
  :root{ --ink-950:#0a0a0f; --ink-900:#111116; --ink-800:#18181f; --line:#26262f; --text-hi:#f4f4f6; --text-mid:#a8a8b3; --text-low:#6b6b76; --amber-300:#f0b429; --success:#22c55e; --error:#ef4444; --warn:#f0b429; }
  *{box-sizing:border-box;}
  body{margin:0; background:var(--ink-950); color:var(--text-hi); font-family:-apple-system,'Segoe UI',sans-serif; padding:32px;}
  .wrap{max-width:720px; margin:0 auto;}
  a.back{color:var(--text-mid); text-decoration:none; font-size:13px;}
  .card{background:var(--ink-900); border:1px solid var(--line); border-radius:10px; padding:28px; margin-top:20px;}
  h1{font-size:22px; margin:0 0 4px;}
  .sub{color:var(--text-mid); font-size:13px; margin-bottom:24px;}
  .row{display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--line); font-size:14px;}
  .row:last-child{border-bottom:none;}
  .row .label{color:var(--text-mid);}
  .row .value{text-align:right; max-width:60%;}
  .badge{display:inline-block; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;}
  .badge-muted{background:#3a3a44; color:var(--text-mid);}
  .badge-success{background:rgba(34,197,94,.15); color:var(--success);}
  .badge-error{background:rgba(239,68,68,.15); color:var(--error);}
  .badge-warn{background:rgba(240,180,41,.15); color:var(--warn);}
  .message-box{background:var(--ink-800); border:1px solid var(--line); border-radius:8px; padding:14px; margin-top:8px; font-size:13.5px; line-height:1.6; white-space:pre-wrap;}
  .doc-link{display:inline-flex; align-items:center; gap:8px; background:var(--ink-800); border:1px solid var(--line); padding:10px 14px; border-radius:8px; color:var(--amber-300); text-decoration:none; font-size:13.5px; margin-top:8px;}
  .actions{display:flex; gap:12px; margin-top:24px;}
  .btn{flex:1; padding:12px; border-radius:8px; border:none; font-size:14.5px; font-weight:600; cursor:pointer;}
  .btn-valider{background:var(--success); color:#06210f;}
  .btn-rejeter{background:transparent; border:1.5px solid var(--error); color:var(--error);}
  .no-doc{color:var(--text-low); font-size:13px; font-style:italic;}
</style>
</head>
<body>
<div class="wrap">
  <a class="back" href="<?= url_app('/admin') ?>">&larr; Retour au tableau de bord</a>

  <div class="card">
    <h1><?= htmlspecialchars($compagnie['nom'], ENT_QUOTES, 'UTF-8') ?></h1>
    <div class="sub">Demande reçue le <?= date('d/m/Y \à H:i', strtotime($compagnie['created_at'])) ?></div>

    <div class="row"><span class="label">Statut actuel</span><span class="value">
      <?php
      $badges = [
          'en_attente_validation' => ['badge-muted', 'En attente de validation'],
          'active'                => ['badge-success', 'Active'],
          'rejetee'                => ['badge-error', 'Rejetée'],
          'desactivee'              => ['badge-error', 'Désactivée'],
      ];
      [$classe, $libelle] = $badges[$compagnie['statut']];
      ?>
      <span class="badge <?= $classe ?>"><?= $libelle ?></span>
    </span></div>
    <div class="row"><span class="label">Email</span><span class="value"><?= htmlspecialchars($compagnie['email'], ENT_QUOTES, 'UTF-8') ?></span></div>
    <div class="row"><span class="label">Téléphone</span><span class="value"><?= htmlspecialchars($compagnie['telephone'], ENT_QUOTES, 'UTF-8') ?></span></div>
    <div class="row"><span class="label">Ville / siège social</span><span class="value"><?= htmlspecialchars($compagnie['ville_siege'] ?? '—', ENT_QUOTES, 'UTF-8') ?></span></div>
    <div class="row"><span class="label">Nombre de véhicules déclarés</span><span class="value"><?= $compagnie['nombre_vehicules'] ?? '—' ?></span></div>

    <?php if ($abonnement): ?>
    <div class="row"><span class="label">Abonnement</span><span class="value"><?= htmlspecialchars($abonnement['statut'], ENT_QUOTES, 'UTF-8') ?>
      <?php if ($abonnement['date_fin_periode']): ?> — échéance <?= date('d/m/Y', strtotime($abonnement['date_fin_periode'])) ?><?php endif; ?>
    </span></div>
    <?php endif; ?>

    <div style="margin-top:20px;">
      <div class="label" style="color:var(--text-mid); font-size:13px; margin-bottom:4px;">Présentation de la compagnie</div>
      <?php if ($compagnie['message_presentation']): ?>
        <div class="message-box"><?= htmlspecialchars($compagnie['message_presentation'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php else: ?>
        <div class="no-doc">Aucune présentation fournie.</div>
      <?php endif; ?>
    </div>

    <div style="margin-top:16px;">
      <div class="label" style="color:var(--text-mid); font-size:13px; margin-bottom:4px;">Document justificatif (registre de commerce, etc.)</div>
      <?php if ($compagnie['document_justificatif_chemin']): ?>
        <a class="doc-link" href="<?= htmlspecialchars(url_app('/' . ltrim($compagnie['document_justificatif_chemin'], '/')), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
          📄 Ouvrir le document (<?= strtoupper(pathinfo((string) $compagnie['document_justificatif_chemin'], PATHINFO_EXTENSION)) ?>)
        </a>
      <?php else: ?>
        <div class="no-doc">Aucun document fourni par la compagnie.</div>
      <?php endif; ?>
    </div>

    <?php if ($compagnie['statut'] === 'en_attente_validation'): ?>
    <div class="actions">
      <button type="button" class="btn btn-valider" id="btnValider">✓ Valider et démarrer l'essai gratuit</button>
      <button type="button" class="btn btn-rejeter" id="btnRejeter">✕ Rejeter la demande</button>
    </div>
    <?php elseif ($compagnie['statut'] === 'rejetee'): ?>
    <div class="actions">
      <button type="button" class="btn btn-rejeter" id="btnSupprimer">🗑 Supprimer définitivement</button>
    </div>
    <?php elseif ($compagnie['statut'] === 'active'): ?>
    <div class="actions">
      <button type="button" class="btn btn-rejeter" id="btnDesactiver">⏸ Désactiver cette compagnie</button>
    </div>
    <?php elseif ($compagnie['statut'] === 'desactivee'): ?>
    <div class="actions"> 
      <button type="button" class="btn btn-valider" id="btnReactiver">▶ Réactiver cette compagnie</button>
    </div>
    <?php endif; ?>
  </div>
</div>

<script>
const APP_BASE = <?= json_encode(rtrim(\App\Core\Aide::urlBase(), '/'), JSON_UNESCAPED_SLASHES) ?>;
const compagnieId = <?= (int) $compagnie['id'] ?>;
const CSRF_TOKEN = <?= json_encode($jetonCsrf) ?>;

async function appelerAction(url, libelleSucces) {

    try {

        const reponse = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': CSRF_TOKEN
            },
            body: JSON.stringify({
                compagnieId: compagnieId
            })
        });

        const donnees = await reponse.json().catch(() => ({}));

        if (!reponse.ok) {
            alert(donnees.erreur || 'Une erreur est survenue.');
            return;
        }

        if (donnees.motDePasseTemporaire) {

            alert(
                libelleSucces +
                '\n\nSMTP non configuré : le mot de passe temporaire à transmettre à la compagnie est :\n\n' +
                donnees.motDePasseTemporaire
            );

        } else if (donnees.emailEnvoye === false) {

            alert(
                libelleSucces +
                '\n\nAttention : l\'email d\'activation n\'a pas pu être envoyé. Configure le SMTP avant une utilisation réelle.'
            );

        } else {

            alert(libelleSucces);
        }

        window.location.href = APP_BASE + '/admin';

    } catch (erreur) {

        console.error('Erreur lors de l\'appel API :', erreur);

        alert(
            'Impossible de contacter le serveur. Vérifie la connexion et la console du navigateur.'
        );
    }
}




const btnValider = document.getElementById('btnValider');

if (btnValider) {

    btnValider.addEventListener('click', () => {

        if (
            !confirm(
                'Valider cette compagnie et démarrer son essai gratuit de 1 mois ?'
            )
        ) {
            return;
        }

        appelerAction(
            APP_BASE + '/api/admin/compagnies/' + compagnieId + '/valider',
            'Compagnie validée, essai gratuit démarré.'
        );
    });
}




const btnRejeter = document.getElementById('btnRejeter');

if (btnRejeter) {

    btnRejeter.addEventListener('click', () => {

        if (
            !confirm(
                'Refuser définitivement cette demande de partenariat ?'
            )
        ) {
            return;
        }

        appelerAction(
            APP_BASE + '/api/admin/compagnies/' + compagnieId + '/rejeter',
            'Demande refusée.'
        );
    });
}




const btnSupprimer = document.getElementById('btnSupprimer');

if (btnSupprimer) {

    btnSupprimer.addEventListener('click', () => {

        if (
            !confirm(
                'Supprimer DÉFINITIVEMENT cette compagnie ? Cette action est irréversible.'
            )
        ) {
            return;
        }

        appelerAction(
            APP_BASE + '/api/admin/compagnies/' + compagnieId + '/supprimer',
            'Compagnie supprimée.'
        );
    });
}




const btnDesactiver = document.getElementById('btnDesactiver');

if (btnDesactiver) {

    btnDesactiver.addEventListener('click', () => {

        if (
            !confirm(
                'Désactiver cette compagnie ? Elle ne pourra plus se connecter et ses voyages disparaîtront de la recherche voyageur. Réversible via "Réactiver".'
            )
        ) {
            return;
        }

        appelerAction(
            APP_BASE + '/api/admin/compagnies/' + compagnieId + '/desactiver',
            'Compagnie désactivée.'
        );
    });
}




const btnReactiver = document.getElementById('btnReactiver');

if (btnReactiver) {

    btnReactiver.addEventListener('click', () => {

        if (!confirm('Réactiver cette compagnie ?')) {
            return;
        }

        appelerAction(
            APP_BASE + '/api/admin/compagnies/' + compagnieId + '/reactiver',
            'Compagnie réactivée.'
        );
    });
}
</script>
</body>
</html>