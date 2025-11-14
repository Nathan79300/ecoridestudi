<?php
function log_admin_action($admin_id, $action, $cible) {
    $logFile = __DIR__ . '/../logs.json';

    // Si le fichier n'existe pas, on le crée avec un tableau vide
    if (!file_exists($logFile)) {
        file_put_contents($logFile, json_encode([]));
    }

    // Lecture des logs existants
    $logs = json_decode(file_get_contents($logFile), true);

    // Ajout d'une nouvelle entrée
    $logs[] = [
        'admin_id' => $admin_id,
        'action' => $action,
        'cible' => $cible,
        'date' => date('c') // format ISO 8601
    ];

    // Sauvegarde des logs
    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
