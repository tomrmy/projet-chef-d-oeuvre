<?php require 'db.php'; require 'header.php'; ?>

<div class="row align-items-center mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <h3 class="fw-bold mb-1">Logs Globaux</h3>
        <p class="text-muted small mb-0">L'historique absolu de toutes les interactions.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="d-inline-flex align-items-center bg-white border rounded-pill px-3 py-2 shadow-sm" style="max-width: 300px; width: 100%; border-color: var(--border-color) !important;">
            <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
            <input type="text" id="searchLogs" class="form-control border-0 bg-transparent p-0 shadow-none" placeholder="Filtrer les logs...">
        </div>
    </div>
</div>

<div class="card p-0 overflow-hidden" id="logs-data">
    <div class="table-responsive">
        <table class="table table-custom mb-0 align-middle">
            <thead><tr><th>Horodatage</th><th>Action du Système</th><th>Utilisateur Cible</th><th>Badge Scanné</th></tr></thead>
            <tbody>
                <?php
                $query = $pdo->query("SELECT l.date_scan, l.statut, l.numero_badge, u.nom, u.prenom FROM logs_generaux l LEFT JOIN utilisateurs u ON l.numero_badge = u.numero_badge ORDER BY l.date_scan DESC LIMIT 150");
                while ($row = $query->fetch()) {
                    $badgeClass = "bg-secondary"; $icon = "fa-minus";
                    if ($row['statut'] == 'Autorisé') { $badgeClass = "bg-success bg-opacity-10 text-success border-success"; $icon = "fa-check"; }
                    elseif ($row['statut'] == 'Inconnu') { $badgeClass = "bg-warning bg-opacity-10 text-warning border-warning"; $icon = "fa-triangle-exclamation"; }
                    elseif ($row['statut'] == 'Refusé - Bloqué') { $badgeClass = "bg-danger bg-opacity-10 text-danger border-danger"; $icon = "fa-ban"; }

                    $identite = $row['nom'] ? "<span class='fw-bold'>{$row['prenom']} {$row['nom']}</span>" : "<span class='text-muted' style='font-style: italic;'>-- Non attribué --</span>";
                    $date = date('d/m/Y', strtotime($row['date_scan']));
                    $heure = date('H:i:s', strtotime($row['date_scan']));

                    echo "<tr>
                            <td><span class='text-muted me-2'>{$date}</span> <span class='fw-bold'>{$heure}</span></td>
                            <td><span class='badge border rounded-pill px-3 py-2 shadow-sm {$badgeClass}'><i class='fa-solid {$icon} me-1'></i> {$row['statut']}</span></td>
                            <td>{$identite}</td>
                            <td><span class='badge-uid'>{$row['numero_badge']}</span></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</main></div>
<script src="theme.js"></script>
<script>
    // 1. Fonction qui applique la recherche
    function applySearchFilter() {
        let filter = document.getElementById('searchLogs').value.toLowerCase();
        let rows = document.querySelectorAll('#logs-data tbody tr');
        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    }

    // 2. Ecoute de la saisie au clavier
    document.getElementById('searchLogs').addEventListener('keyup', applySearchFilter);

    // 3. Actualisation temps réel
    setInterval(() => {
        // On mémorise si l'utilisateur est en train de taper pour ne pas le gêner
        let isSearchFocused = (document.activeElement === document.getElementById('searchLogs'));
        
        fetch(window.location.href).then(r => r.text()).then(html => {
            let doc = new DOMParser().parseFromString(html, 'text/html');
            document.getElementById('logs-data').innerHTML = doc.getElementById('logs-data').innerHTML;
            
            // On ré-applique instantanément le filtre pour que les lignes indésirables ne clignotent pas
            applySearchFilter();
        });
    }, 2000); 
</script>
</body>
</html>