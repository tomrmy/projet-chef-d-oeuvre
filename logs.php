<?php 
require 'db.php'; 
require 'header.php'; // Gère le design, la sidebar et vérifie la session login
?>

<div class="row align-items-center mb-4">
    <div class="col-xl-4 col-lg-12 mb-3 mb-xl-0">
        <h3 class="fw-bold mb-1">Logs Globaux</h3>
        <p class="text-muted small mb-0">L'historique absolu de toutes les interactions.</p>
    </div>
    
    <div class="col-xl-8 col-lg-12 text-xl-end d-flex flex-wrap justify-content-xl-end gap-2 align-items-center">
        <a href="export.php" class="btn btn-success rounded-pill px-3 shadow-sm d-flex align-items-center"><i class="fa-solid fa-file-excel me-2"></i>Export CSV</a>
        <a href="action.php?action=purge_rgpd" class="btn btn-outline-danger rounded-pill px-3 shadow-sm d-flex align-items-center" onclick="return confirm('Confirmez-vous la suppression définitive des données de plus de 30 jours conformément à la loi RGPD ?');"><i class="fa-solid fa-broom me-2"></i>Purge RGPD (30j)</a>
        
        <div class="d-inline-flex align-items-center bg-white border rounded-pill px-3 py-2 shadow-sm" style="max-width: 280px; width: 100%; border-color: var(--border-color) !important;">
            <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
            <input type="text" id="searchLogs" class="form-control border-0 bg-transparent p-0 shadow-none" placeholder="Filtrer les logs...">
        </div>
    </div>
</div>

<div class="card p-0 overflow-hidden" id="logs-data">
    <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
        <table class="table table-custom mb-0 align-middle">
            <thead class="sticky-top bg-white" style="z-index: 1;">
                <tr><th class="ps-4">Horodatage</th><th>Action du Système</th><th>Utilisateur Cible</th><th>Badge Scanné</th></tr>
            </thead>
            <tbody>
                <?php
                $query = $pdo->query("SELECT l.date_scan, l.statut, l.numero_badge, u.nom, u.prenom FROM logs_generaux l LEFT JOIN utilisateurs u ON l.numero_badge = u.numero_badge ORDER BY l.date_scan DESC LIMIT 300");
                if ($query->rowCount() == 0) echo "<tr><td colspan='4' class='text-center py-5 text-muted'>Aucun log enregistré.</td></tr>";
                while ($row = $query->fetch()) {
                    $badgeClass = "bg-secondary"; $icon = "fa-minus";
                    if ($row['statut'] == 'Autorisé') { $badgeClass = "bg-success bg-opacity-10 text-success border-success"; $icon = "fa-check"; }
                    elseif ($row['statut'] == 'Inconnu') { $badgeClass = "bg-warning bg-opacity-10 text-warning border-warning"; $icon = "fa-triangle-exclamation"; }
                    elseif (strpos($row['statut'], 'Refusé') !== false) { $badgeClass = "bg-danger bg-opacity-10 text-danger border-danger"; $icon = "fa-ban"; }

                    $identite = $row['nom'] ? "<span class='fw-bold'>{$row['prenom']} {$row['nom']}</span>" : "<span class='text-muted' style='font-style: italic;'>-- Non attribué --</span>";
                    $date = date('d/m/Y', strtotime($row['date_scan']));
                    $heure = date('H:i:s', strtotime($row['date_scan']));

                    echo "<tr>
                            <td class='ps-4'><span class='text-muted me-2'>{$date}</span> <span class='fw-bold'>{$heure}</span></td>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="theme.js"></script>
<script>
    // Filtrage dynamique au clavier
    function applySearchFilter() {
        let filter = document.getElementById('searchLogs').value.toLowerCase();
        let rows = document.querySelectorAll('#logs-data tbody tr');
        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    }

    document.getElementById('searchLogs').addEventListener('keyup', applySearchFilter);

    // Actualisation temps réel (sans perdre la recherche en cours)
    setInterval(() => {
        fetch(window.location.href).then(r => r.text()).then(html => {
            document.getElementById('logs-data').innerHTML = new DOMParser().parseFromString(html, 'text/html').getElementById('logs-data').innerHTML;
            applySearchFilter();
        });
    }, 2000); 
</script>
</body>
</html>