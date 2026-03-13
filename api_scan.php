<?php
require 'db.php';
date_default_timezone_set('Europe/Paris'); // Pour avoir la bonne heure

if (isset($_GET['badge'])) {
    $badge = htmlspecialchars($_GET['badge']);
    $heure_actuelle = date('H:i:s');

    // 1. Badge bloqué ?
    $stmt = $pdo->prepare("SELECT id FROM badges_bloques WHERE numero_badge = ?");
    $stmt->execute([$badge]);
    if ($stmt->fetch()) {
        $pdo->prepare("INSERT INTO logs_generaux (numero_badge, statut) VALUES (?, 'Refusé - Bloqué')")->execute([$badge]);
        die("ERREUR: Badge bloque.");
    }

    // 2. Utilisateur connu ?
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE numero_badge = ?");
    $stmt->execute([$badge]);
    $user = $stmt->fetch();

    if ($user) {
        // VÉRIFICATION DES PLAGES HORAIRES
        if ($heure_actuelle >= $user['heure_debut'] && $heure_actuelle <= $user['heure_fin']) {
            $pdo->prepare("INSERT INTO passages_badge (numero_badge) VALUES (?)")->execute([$badge]);
            $pdo->prepare("DELETE FROM badges_inconnus WHERE numero_badge = ?")->execute([$badge]);
            $pdo->prepare("INSERT INTO logs_generaux (numero_badge, statut) VALUES (?, 'Autorisé')")->execute([$badge]);
            echo "OK: Passage autorise";
        } else {
            // HORS HORAIRES
            $pdo->prepare("INSERT INTO logs_generaux (numero_badge, statut) VALUES (?, 'Refusé - Hors horaires')")->execute([$badge]);
            echo "ERREUR: Hors horaires";
        }
    } else {
        // Badge inconnu
        $pdo->prepare("INSERT INTO badges_inconnus (numero_badge) VALUES (?) ON DUPLICATE KEY UPDATE date_scan = CURRENT_TIMESTAMP")->execute([$badge]);
        $pdo->prepare("INSERT INTO logs_generaux (numero_badge, statut) VALUES (?, 'Inconnu')")->execute([$badge]);
        echo "ERREUR: Badge inconnu";
    }
}
?>