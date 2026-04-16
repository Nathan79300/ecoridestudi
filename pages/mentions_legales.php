<?php
// Page des mentions légales
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../includes/header.php';
?>

<main>
    <div class="container" style="max-width: 900px; margin: 100px auto 40px; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        
        <h1 style="color:#2e7d32;">📄 Mentions légales</h1>

        <h2 style="color:#2e7d32;">Éditeur du site</h2>
        <p>
            <strong>Nom du site :</strong> EcoRide<br>
            <strong>Responsable de publication :</strong> Mme/M. Nom Prénom<br>
            <strong>Statut juridique :</strong> Auto-entrepreneur / SAS / Association (à adapter)<br>
            <strong>SIRET :</strong> 123 456 789 00000<br>
            <strong>Adresse :</strong> 12 rue du Covoiturage Vert, 75000 Paris, France<br>
            <strong>Email :</strong> contact@ecoride.fr
        </p>

        <h2 style="color:#2e7d32;">Hébergement</h2>
        <p>
            <strong>Hébergeur :</strong> OVH / autre<br>
            <strong>Adresse :</strong> 2 rue Kellermann, 59100 Roubaix, France<br>
            <strong>Site :</strong> 
            <a href="https://www.ovh.com" target="_blank">www.ovh.com</a>
        </p>

        <h2 style="color:#2e7d32;">Propriété intellectuelle</h2>
        <p>
            Le contenu du site (textes, images, logo, etc.) est protégé par le droit d’auteur. 
            Toute reproduction ou diffusion sans autorisation est interdite.
        </p>

        <h2 style="color:#2e7d32;">Données personnelles</h2>
        <p>
            Conformément au RGPD, vous disposez d’un droit d’accès, de modification, de suppression 
            et d’opposition concernant vos données.  
            Pour toute demande : <a href="mailto:dpo@ecoride.fr">dpo@ecoride.fr</a>.<br>
            Les données sont utilisées uniquement pour la gestion du service EcoRide 
            et conservées 3 ans maximum.
        </p>

        <h2 style="color:#2e7d32;">Cookies</h2>
        <p>
            Ce site utilise des cookies pour améliorer l’expérience utilisateur. 
            Vous pouvez refuser ou accepter leur utilisation lors de votre navigation.
        </p>

        <h2 style="color:#2e7d32;">Loi applicable</h2>
        <p>
            Le site EcoRide est soumis au droit français. 
            Tout litige sera porté devant les tribunaux compétents.
        </p>
    </div>
</main>

<?php
include __DIR__ . '/../includes/footer.php';
?>
