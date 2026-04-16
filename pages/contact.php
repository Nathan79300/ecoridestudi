<head><meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<div class="contact-container">
  <h2>📬 Contactez-nous</h2>
  <p>Une question, une suggestion ou juste un petit mot ? Écrivez-nous ! 🌿</p>

  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="contact-success-message">
      ✅ Merci pour votre message, nous vous répondrons rapidement.
    </div>
  <?php endif; ?>

  <form method="post" action="traitement_contact.php" class="contact-form">
    <div class="form-group">
      <label for="prenom">Prénom</label>
      <input type="text" name="prenom" id="prenom" placeholder="Votre prénom" required>
    </div>

    <div class="form-group">
      <label for="nom">Nom</label>
      <input type="text" name="nom" id="nom" placeholder="Votre nom" required>
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" placeholder="votre@email.com" required>
    </div>

    <div class="form-group">
      <label for="message">Message</label>
      <textarea name="message" id="message" placeholder="Votre message..." required></textarea>
    </div>

    <button type="submit">📨 Envoyer</button>
  </form>
</div>
