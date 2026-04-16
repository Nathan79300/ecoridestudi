<section class="profil-page">

  <div class="profil-header">
    <h1>👤 Mon profil</h1>

    <?php if (!empty($message)): ?>
      <div class="msg-success">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>
  </div>

  <?php
    $role = $utilisateur['role'] ?? 'utilisateur';
    $isChauffeur = in_array($role, ['chauffeur', 'passager_chauffeur'], true);
  ?>

  <div class="profil-grid">

    
    <div class="profil-card">
      <h2>📌 Mes informations</h2>

      <div class="profil-info">
        <p><strong>Pseudo :</strong> <?= htmlspecialchars($utilisateur['username'] ?? '—', ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email'] ?? '—', ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Crédits :</strong> 💳 <?= (int)($utilisateur['credits'] ?? 0) ?></p>

        <?php if (isset($utilisateur['note_moyenne'])): ?>
          <p><strong>Note moyenne :</strong> ⭐ <?= htmlspecialchars((string)$utilisateur['note_moyenne'], ENT_QUOTES, 'UTF-8') ?>/5</p>
        <?php else: ?>
          <p><strong>Note moyenne :</strong> ⭐ 0/5</p>
        <?php endif; ?>

        <p>
          <strong>Rôle :</strong>
          <?php
            $roleLabel = [
              'utilisateur' => 'Passager',
              'chauffeur' => 'Chauffeur',
              'passager_chauffeur' => 'Passager + Chauffeur'
            ];
            echo htmlspecialchars($roleLabel[$role] ?? 'Passager', ENT_QUOTES, 'UTF-8');
          ?>
        </p>
      </div>

      
      <div class="profil-actions">

        <a href="/ecoridestudi/ecoride/public/index.php?url=recherche" class="btn btn-wide">
          🔍 Trouver un trajet
        </a>

        <a href="/ecoridestudi/ecoride/public/index.php?url=mesReservations" class="btn btn-wide">
          📌 Voir mes réservations
        </a>

        <?php if ($isChauffeur): ?>

          <a href="/ecoridestudi/ecoride/public/index.php?url=proposer" class="btn btn-wide">
            🚗 Proposer un trajet
          </a>

          <a href="/ecoridestudi/ecoride/public/index.php?url=mesTrajets" class="btn btn-wide btn-outline">
            🚙 Voir mes trajets
          </a>

         
          <a href="/ecoridestudi/ecoride/public/index.php?url=vehicules" class="btn btn-wide">
            🚘 Gérer mes véhicules
          </a>

        <?php endif; ?>

      </div>

    </div>

    
    <div class="profil-card">
      <h2>✏️ Modifier mon profil</h2>

      <form method="POST" class="profil-form">

        <div class="form-group">
          <label>Prénom :</label>
          <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="form-group">
          <label>Nom :</label>
          <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="form-group">
          <label>Je souhaite être :</label>
          <select name="role" required>
            <option value="utilisateur" <?= $role === 'utilisateur' ? 'selected' : '' ?>>Passager</option>
            <option value="chauffeur" <?= $role === 'chauffeur' ? 'selected' : '' ?>>Chauffeur</option>
            <option value="passager_chauffeur" <?= $role === 'passager_chauffeur' ? 'selected' : '' ?>>Les deux</option>
          </select>
        </div>

        <button type="submit" class="btn btn-wide">💾 Mettre à jour</button>

        <?php if ($isChauffeur): ?>
          <p class="profil-hint">✅ Vous êtes chauffeur : vous pouvez proposer des trajets et gérer vos véhicules.</p>
        <?php else: ?>
          <p class="profil-hint">ℹ️ Passez en “Chauffeur” pour proposer des trajets et ajouter un véhicule.</p>
        <?php endif; ?>

      </form>
    </div>

  </div>

</section>
