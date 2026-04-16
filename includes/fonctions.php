<?php
function envoyer_mail($destinataire, $sujet, $message) {
    $headers = "From: contact@ecoride.fr\r\n";
    $headers .= "Reply-To: contact@ecoride.fr\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Pour le développement local, vous pouvez aussi utiliser mailtrap.io ou MailHog pour tester les mails
    return mail($destinataire, $sujet, $message, $headers);
}
