<?php
require 'db.php';

if (isset($_GET['action']) && isset($_GET['badge'])) {
    $badge = htmlspecialchars($_GET['badge']);
    
    if ($_GET['action'] == 'bloquer') {
        $pdo->prepare("INSERT IGNORE INTO badges_bloques (numero_badge) VALUES (?)")->execute([$badge]);
        $pdo->prepare("DELETE FROM badges_inconnus WHERE numero_badge = ?")->execute([$badge]);
    } 
    elseif ($_GET['action'] == 'debloquer') {
        $pdo->prepare("DELETE FROM badges_bloques WHERE numero_badge = ?")->execute([$badge]);
    }
    elseif ($_GET['action'] == 'supprimer_inconnu') {
        $pdo->prepare("DELETE FROM badges_inconnus WHERE numero_badge = ?")->execute([$badge]);
    }
    elseif ($_GET['action'] == 'supprimer_user') {
        $pdo->prepare("DELETE FROM utilisateurs WHERE numero_badge = ?")->execute([$badge]);
    }
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>