<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

require 'db.php';

// Si c'est l'action de Purge RGPD (Pas besoin d'UID de badge pour ça)
if (isset($_GET['action']) && $_GET['action'] == 'purge_rgpd') {
    // Supprime tous les logs vieux de plus de 30 jours
    $pdo->query("DELETE FROM logs_generaux WHERE date_scan < DATE_SUB(NOW(), INTERVAL 30 DAY)");
}
// Pour toutes les autres actions (qui ciblent un badge précis)
elseif (isset($_GET['action']) && isset($_GET['badge'])) {
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

// On renvoie l'utilisateur sur la page d'où il vient en douceur
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>