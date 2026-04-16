<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? null) !== 'admin') {
    header('Location: /ecoridestudi/ecoride/public/index.php?url=connexionAdmin');
    exit;
}

require_once __DIR__ . '/../../../includes/db.php';

$users = $pdo->query("
    SELECT id, username, nom, prenom, email, role, suspendu
    FROM utilisateurs
    WHERE suspendu = 0
    ORDER BY role, id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 style="text-align:center; margin-top:2rem; color:#2e7d32;">⛔ Suspendre un compte</h2>

<div style="max-width:1000px; margin:2rem auto; background:#fff; padding:1.5rem; border-radius:12px; box-shadow:0 0 10px rgba(0,0,0,.06);">
    <?php if (empty($users)): ?>
        <p style="text-align:center; color:#666;">Aucun compte actif.</p>
    <?php else: ?>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #ddd;">
                    <th style="padding:.6rem;">ID</th>
                    <th style="padding:.6rem;">Nom</th>
                    <th style="padding:.6rem;">Email</th>
                    <th style="padding:.6rem;">Rôle</th>
                    <th style="padding:.6rem;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:.6rem;"><?= (int)$u['id'] ?></td>
                        <td style="padding:.6rem;">
                            <?= htmlspecialchars(
                                trim(($u['prenom'] ?? '') . ' ' . ($u['nom'] ?? '')) ?: ($u['username'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                        <td style="padding:.6rem;"><?= htmlspecialchars($u['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="padding:.6rem;"><?= htmlspecialchars($u['role'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="padding:.6rem;">
                            <form method="POST" action="/ecoridestudi/ecoride/public/index.php?url=traiterSuspension" class="form-suspension" style="margin:0;">
                                <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                <input type="hidden" name="action" value="suspendre">
                                <button
                                    type="submit"
                                    class="btn-suspendre"
                                    style="background:#c62828; color:#fff; border:none; padding:.45rem .8rem; border-radius:6px; cursor:pointer;"
                                >
                                    Suspendre
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p style="text-align:center; margin-top:1rem;">
        <a href="/ecoridestudi/ecoride/public/index.php?url=admin">⬅ Retour espace admin</a>
    </p>
</div>

<script src="/ecoridestudi/ecoride/public/assets/js/admin.js"></script>