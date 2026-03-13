<?php
require 'db.php';

if (isset($_GET['badge'])) {
    $badge = htmlspecialchars($_GET['badge']);

    $stmt = $pdo->prepare("SELECT id FROM badges_bloques WHERE numero_badge = ?");
    $stmt->execute([$badge]);
    if ($stmt->fetch()) {
        $pdo->prepare("INSERT INTO logs_generaux (numero_badge, statut) VALUES (?, 'Refusé - Bloqué')")->execute([$badge]);
        die("ERREUR: Badge bloque.");
    }

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE numero_badge = ?");
    $stmt->execute([$badge]);
    if ($stmt->fetch()) {
        $pdo->prepare("INSERT INTO passages_badge (numero_badge) VALUES (?)")->execute([$badge]);
        $pdo->prepare("DELETE FROM badges_inconnus WHERE numero_badge = ?")->execute([$badge]);
        $pdo->prepare("INSERT INTO logs_generaux (numero_badge, statut) VALUES (?, 'Autorisé')")->execute([$badge]);
        echo "OK: Passage autorise";
    } else {
        $pdo->prepare("INSERT INTO badges_inconnus (numero_badge) VALUES (?) ON DUPLICATE KEY UPDATE date_scan = CURRENT_TIMESTAMP")->execute([$badge]);
        $pdo->prepare("INSERT INTO logs_generaux (numero_badge, statut) VALUES (?, 'Inconnu')")->execute([$badge]);
        echo "ERREUR: Badge inconnu";
    }
}
?>