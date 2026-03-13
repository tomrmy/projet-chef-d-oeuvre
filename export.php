<?php
session_start();
// Sécurité : on vérifie que l'admin est bien connecté
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require 'db.php';

// On indique au navigateur qu'on va lui envoyer un fichier CSV à télécharger
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Rapport_Securite_ProAccess_' . date('Y-m-d') . '.csv');

// On ouvre un flux d'écriture
$output = fopen('php://output', 'w');

// On écrit la première ligne (les en-têtes des colonnes)
fputcsv($output, array('Date', 'Heure', 'Statut du passage', 'UID du Badge', 'Nom', 'Prenom', 'Service / Classe'));

// On récupère TOUS les logs
$query = $pdo->query("SELECT l.date_scan, l.statut, l.numero_badge, u.nom, u.prenom, u.classe 
                      FROM logs_generaux l 
                      LEFT JOIN utilisateurs u ON l.numero_badge = u.numero_badge 
                      ORDER BY l.date_scan DESC");

// On remplit le fichier ligne par ligne
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $date = date('d/m/Y', strtotime($row['date_scan']));
    $heure = date('H:i:s', strtotime($row['date_scan']));
    
    // Si c'est un inconnu, on met "Non attribué"
    $nom = $row['nom'] ? $row['nom'] : 'Non attribué';
    $prenom = $row['prenom'] ? $row['prenom'] : 'Non attribué';
    $classe = $row['classe'] ? $row['classe'] : 'Inconnu';

    fputcsv($output, array($date, $heure, $row['statut'], $row['numero_badge'], $nom, $prenom, $classe));
}

fclose($output);
exit;
?>