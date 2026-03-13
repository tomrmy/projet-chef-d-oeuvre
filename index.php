<?php 
require 'db.php'; 
require 'header.php'; // Gère le design, la sidebar et la session

// KPIs
$totalUsers = $pdo->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$scansToday = $pdo->query("SELECT COUNT(*) FROM passages_badge WHERE DATE(date_passage) = CURDATE()")->fetchColumn();
$totalAlerts = $pdo->query("SELECT COUNT(*) FROM badges_inconnus")->fetchColumn();

// Données pour le Graphique (Uniquement les passages autorisés, sur 7 jours)
$chartQuery = $pdo->query("
    SELECT DATE(date_passage) as jour, COUNT(*) as total 
    FROM passages_badge 
    WHERE date_passage >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY jour 
    ORDER BY jour ASC
");
$chartLabels = [];
$chartData = [];
while($row = $chartQuery->fetch()) {
    $chartLabels[] = date('d/m', strtotime($row['jour'])); 
    $chartData[] = $row['total'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Vue d'ensemble</h3>
        <p class="text-muted small mb-0">L'activité de votre système de sécurité en temps réel.</p>
    </div>
    <div class="text-end">
        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill shadow-sm"><i class="fa-solid fa-signal me-2"></i>Système Actif</span>
    </div>
</div>

<div id="dashboard-kpis">
    <div id="chart-data-source" data-labels='<?= json_encode($chartLabels) ?>' data-values='<?= json_encode($chartData) ?>' style="display:none;"></div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-4 mb-3">
            <div class="card p-4 h-100"><div class="d-flex justify-content-between align-items-center"><div><h6 class="text-muted fw-semibold mb-2 text-uppercase" style="font-size:0.8rem;">Utilisateurs</h6><h2 class="fw-bold mb-0"><?= $totalUsers ?></h2></div><div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 20px;"><i class="fa-solid fa-users"></i></div></div></div>
        </div>
        <div class="col-xl-4 col-md-4 mb-3">
            <div class="card p-4 h-100"><div class="d-flex justify-content-between align-items-center"><div><h6 class="text-muted fw-semibold mb-2 text-uppercase" style="font-size:0.8rem;">Passages (Aujourd'hui)</h6><h2 class="fw-bold mb-0"><?= $scansToday ?></h2></div><div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 20px;"><i class="fa-solid fa-door-open"></i></div></div></div>
        </div>
        <div class="col-xl-4 col-md-4 mb-3">
            <div class="card p-4 h-100 border-start border-danger border-4 shadow-sm"><div class="d-flex justify-content-between align-items-center"><div><h6 class="text-muted fw-semibold mb-2 text-uppercase" style="font-size:0.8rem;">Alertes Sécurité</h6><h2 class="fw-bold mb-0 text-danger" id="alerte-counter"><?= $totalAlerts ?></h2></div><div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 20px;"><i class="fa-solid fa-triangle-exclamation"></i></div></div></div>
        </div>
    </div>
</div>

<div class="card p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="fa-solid fa-chart-line text-primary me-2"></i>Activité des 7 derniers jours (Accès autorisés)</h6>
    <div style="height: 250px;">
        <canvas id="activityChart"></canvas>
    </div>
</div>

<div id="dashboard-tables">
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card p-0 overflow-hidden h-100">
                <div class="p-4 border-bottom" style="border-color: var(--border-color) !important;"><h5 class="fw-bold mb-0">Derniers Accès Autorisés</h5></div>
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead><tr><th class="ps-4">Heure</th><th>Identité</th><th>Service</th><th>UID</th></tr></thead>
                        <tbody>
                            <?php
                            $query = $pdo->query("SELECT p.date_passage, u.nom, u.prenom, u.classe, p.numero_badge FROM passages_badge p JOIN utilisateurs u ON p.numero_badge = u.numero_badge ORDER BY p.date_passage DESC LIMIT 8");
                            if ($query->rowCount() == 0) echo "<tr><td colspan='4' class='text-center py-4 text-muted'>Aucun passage récent.</td></tr>";
                            while ($row = $query->fetch()) {
                                $heure = date('H:i:s', strtotime($row['date_passage']));
                                $initiales = strtoupper(substr($row['prenom'], 0, 1) . substr($row['nom'], 0, 1));
                                echo "<tr>
                                        <td class='ps-4'><span class='text-muted small'>Aujourd'hui</span><br><span class='fw-bold'>{$heure}</span></td>
                                        <td><div class='d-flex align-items-center gap-3'><div class='bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm' style='width: 38px; height: 38px; font-size: 13px; font-weight: 600;'>{$initiales}</div><div><div class='fw-bold'>{$row['prenom']} {$row['nom']}</div></div></div></td>
                                        <td><span class='badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 rounded-pill'>{$row['classe']}</span></td>
                                        <td><span class='badge-uid'>{$row['numero_badge']}</span></td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="card p-0 overflow-hidden h-100 border-danger border-opacity-25">
                <div class="p-4 border-bottom bg-danger bg-opacity-10" style="border-color: var(--border-color) !important;"><h5 class="fw-bold text-danger mb-0"><i class="fa-solid fa-shield-virus me-2"></i>Tentatives Refusées</h5></div>
                <div class="p-3" style="max-height: 400px; overflow-y: auto;">
                    <?php
                    $inconnus = $pdo->query("SELECT * FROM badges_inconnus ORDER BY date_scan DESC LIMIT 10");
                    if ($inconnus->rowCount() == 0) echo "<div class='text-center py-5 text-muted'><i class='fa-solid fa-check-circle fs-1 mb-3 opacity-25 text-success'></i><br>Aucune alerte en cours</div>";
                    while ($bad = $inconnus->fetch()) {
                        $temps = date('H:i:s', strtotime($bad['date_scan']));
                        echo "<div class='p-3 mb-2 rounded border shadow-sm alert-item' style='border-color: var(--border-color) !important; background-color: var(--bg-color); border-left: 4px solid #dc3545 !important;'>
                                <div class='d-flex justify-content-between mb-2'><span class='fw-bold text-danger'><i class='fa-solid fa-id-card me-1'></i> {$bad['numero_badge']}</span><span class='text-muted small'>{$temps}</span></div>
                                <div class='d-flex gap-2'>
                                    <a href='ajouter.php?badge={$bad['numero_badge']}' class='btn btn-sm btn-outline-success flex-fill rounded-3' style='font-size: 0.75rem;' title='Ajouter'><i class='fa-solid fa-plus'></i></a>
                                    <a href='action.php?action=bloquer&badge={$bad['numero_badge']}' class='btn btn-sm btn-outline-warning flex-fill rounded-3' style='font-size: 0.75rem;' title='Bloquer'><i class='fa-solid fa-ban'></i></a>
                                    <a href='action.php?action=supprimer_inconnu&badge={$bad['numero_badge']}' class='btn btn-sm btn-outline-danger rounded-3' style='font-size: 0.75rem;' title='Supprimer'><i class='fa-solid fa-trash'></i></a>
                                </div>
                              </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

</main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="theme.js"></script>

<script>
    // --- 1. CONFIGURATION DU GRAPHIQUE ---
    const ctx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Passages validés',
                data: <?= json_encode($chartData) ?>,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointRadius: 5,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { borderDash: [5, 5] } },
                x: { grid: { display: false } }
            }
        }
    });

    // --- 2. GESTION DES NOTIFICATIONS ET SONS ---
    let derniereAlerteTotal = <?= $totalAlerts ?>; 
    
    // Demander la permission à Windows/Mac au chargement de la page
    if ("Notification" in window && Notification.permission !== "denied") {
        Notification.requestPermission();
    }

    // Fonction qui génère un Bip Bip sonore 100% JS (pas besoin de fichier mp3)
    const playAlertSound = () => {
        let audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        let oscillator = audioCtx.createOscillator();
        let gainNode = audioCtx.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        oscillator.type = 'square';
        oscillator.frequency.setValueAtTime(440, audioCtx.currentTime); // Note La
        gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime); // Volume bas
        oscillator.start();
        oscillator.stop(audioCtx.currentTime + 0.3); // Durée du Bip (0.3s)
    };

    // --- 3. ACTUALISATION MAGIQUE (Toutes les 2 secondes) ---
    setInterval(() => {
        fetch(window.location.href).then(r => r.text()).then(html => {
            let doc = new DOMParser().parseFromString(html, 'text/html');
            
            // Mise à jour de l'interface visuelle
            document.getElementById('dashboard-tables').innerHTML = doc.getElementById('dashboard-tables').innerHTML;
            document.getElementById('dashboard-kpis').innerHTML = doc.getElementById('dashboard-kpis').innerHTML;
            
            // VÉRIFICATION DES ALERTES POUR LES NOTIFICATIONS
            let nouveauxAlertesTotal = parseInt(doc.getElementById('alerte-counter').innerText);
            
            if (nouveauxAlertesTotal > derniereAlerteTotal) {
                // Alerte sonore !
                playAlertSound();
                
                // Notification Windows/Mac !
                if ("Notification" in window && Notification.permission === "granted") {
                    new Notification("🚨 Pro-Access : Alerte Sécurité", {
                        body: "Un badge inconnu ou bloqué vient d'être refusé à l'entrée.",
                    });
                }
            }
            // On met à jour le compteur en mémoire
            derniereAlerteTotal = nouveauxAlertesTotal;

            // Mise à jour du Graphique en douceur
            let newDataSource = document.getElementById('chart-data-source');
            if (newDataSource && activityChart) {
                activityChart.data.labels = JSON.parse(newDataSource.dataset.labels);
                activityChart.data.datasets[0].data = JSON.parse(newDataSource.dataset.values);
                activityChart.update();
            }
        });
    }, 2000);
</script>
</body>
</html>