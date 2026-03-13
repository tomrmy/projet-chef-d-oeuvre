<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }
// On récupère le nom de la page actuelle pour surligner le bon bouton dans le menu
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pro-Access | Système de Sécurité</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary: #4f46e5; /* Bleu indigo moderne */
            --primary-hover: #4338ca;
        }

        [data-bs-theme="dark"] {
            --bg-color: #0f172a;
            --sidebar-bg: #1e293b;
            --card-bg: #1e293b;
            --border-color: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            transition: background-color 0.3s ease;
            overflow-x: hidden;
        }

        /* Layout Principal */
        .app-container { display: flex; min-height: 100vh; }
        
        /* Sidebar Menu */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .sidebar-logo {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center; gap: 10px;
        }
        .nav-menu { padding: 1.5rem 1rem; flex-grow: 1; display: flex; flex-direction: column; gap: 8px; }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 15px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .nav-item:hover { background-color: rgba(79, 70, 229, 0.05); color: var(--primary); }
        .nav-item.active { background-color: var(--primary); color: white; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        
        /* Contenu Principal */
        .main-content { flex-grow: 1; padding: 2rem; max-width: calc(100vw - 260px); }
        
        /* Composants UI Premium */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .table-custom th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-color); padding: 1rem; }
        .table-custom td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid var(--border-color); }
        .table-custom tbody tr:hover { background-color: rgba(0,0,0,0.015); }
        [data-bs-theme="dark"] .table-custom tbody tr:hover { background-color: rgba(255,255,255,0.02); }
        
        .badge-uid { font-family: monospace; background: var(--bg-color); padding: 6px 10px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.85rem;}
        .form-control { border-radius: 10px; border: 1px solid var(--border-color); padding: 0.75rem 1rem; background-color: var(--bg-color); color: var(--text-main); }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); border-color: var(--primary); }
    </style>
</head>
<body>

<div class="app-container">
    <nav class="sidebar">
        <div class="sidebar-logo">
            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;"><i class="fa-solid fa-shield-halved"></i></div>
            Pro-Access
        </div>
        <div class="nav-menu">
            <a href="index.php" class="nav-item <?= $currentPage == 'index.php' ? 'active' : '' ?>"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a href="utilisateurs.php" class="nav-item <?= $currentPage == 'utilisateurs.php' ? 'active' : '' ?>"><i class="fa-solid fa-users"></i> Utilisateurs & Blocages</a>
            <a href="logs.php" class="nav-item <?= $currentPage == 'logs.php' ? 'active' : '' ?>"><i class="fa-solid fa-server"></i> Logs Système</a>
            
            <div class="mt-4 mb-2 px-3 text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Actions</div>
            <a href="ajouter.php" class="nav-item <?= $currentPage == 'ajouter.php' ? 'active' : '' ?>"><i class="fa-solid fa-plus"></i> Ajouter Utilisateur</a>
        </div>
        
        <div class="p-4 border-top" style="border-color: var(--border-color) !important;">
            <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background-color: var(--bg-color); border: 1px solid var(--border-color);">
                <span class="small fw-semibold text-muted ms-2">Thème</span>
                <button id="themeToggle" class="btn btn-sm btn-light border rounded-circle shadow-sm" style="width: 35px; height: 35px;">
                    <i class="fa-solid fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <main class="main-content">