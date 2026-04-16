<?php
$logsFile = realpath(__DIR__ . '/../logs.json');

$logs = file_exists($logsFile) ? json_decode(file_get_contents($logsFile), true) : [];
?>

<div class="container" style="max-width: 900px; margin: auto; background: #fff; border-radius: 10px; padding: 2rem; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    <h2 style="color: #2e7d32;">📁 Journal des actions administrateur</h2>

    <?php if (!empty($logs)): ?>
        <ul style="list-style: none; padding: 0; margin-top: 1rem;">
            <?php foreach (array_reverse($logs) as $log): ?>
                <li style="padding: 1rem; border-bottom: 1px solid #ddd;">
                    <strong><?= htmlspecialchars($log['date']) ?></strong><br>
                    Action : <strong><?= htmlspecialchars($log['action']) ?></strong><br>
                    Cible : <?= htmlspecialchars($log['cible']) ?><br>
                    Admin ID : #<?= htmlspecialchars($log['admin_id']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="color: #888;">Aucun log trouvé.</p>
    <?php endif; ?>
</div>
