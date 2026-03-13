<?php require 'db.php'; require 'header.php'; ?>

<div class="row align-items-center mb-4">
    <div class="col-md-5 mb-3 mb-md-0">
        <h3 class="fw-bold mb-1">Administration</h3>
        <p class="text-muted small mb-0">Gérez vos utilisateurs et les accès.</p>
    </div>
    <div class="col-md-7 text-md-end d-flex justify-content-md-end gap-3">
        <div class="d-inline-flex align-items-center bg-white border rounded-pill px-3 py-2 shadow-sm" style="max-width: 300px; width: 100%; border-color: var(--border-color) !important;">
            <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
            <input type="text" id="searchInput" class="form-control border-0 bg-transparent p-0 shadow-none" placeholder="Chercher un nom, un UID...">
        </div>
        <a href="ajouter.php" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm d-flex align-items-center"><i class="fa-solid fa-plus me-2"></i>Nouveau</a>
    </div>
</div>

<div class="card p-0 overflow-hidden">
    <ul class="nav nav-tabs border-bottom-0 px-4 pt-3" style="background-color: var(--bg-color);" id="myTab" role="tablist">
        <li class="nav-item"><button class="nav-link active bg-transparent fw-bold" style="color: var(--primary); border:none; border-bottom: 3px solid var(--primary);" data-bs-toggle="tab" data-bs-target="#users">Actifs</button></li>
        <li class="nav-item ms-3"><button class="nav-link bg-transparent fw-bold text-muted" style="border:none;" data-bs-toggle="tab" data-bs-target="#blocked" onclick="this.style.color='#dc3545'; this.style.borderBottom='3px solid #dc3545'; document.querySelector('.nav-link.active').style.border='none'; document.querySelector('.nav-link.active').style.color='var(--text-muted)';">Badges Bloqués</button></li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active p-0" id="users">
            <table class="table table-custom mb-0 search-table">
                <thead><tr><th>Nom complet</th><th>Département</th><th>Badge UID</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    <?php
                    $query = $pdo->query("SELECT * FROM utilisateurs ORDER BY nom ASC");
                    while ($row = $query->fetch()) {
                        echo "<tr>
                                <td><div class='d-flex align-items-center gap-3'><div class='bg-light text-primary rounded-circle d-flex align-items-center justify-content-center border' style='width: 40px; height: 40px;'><i class='fa-solid fa-user'></i></div><span class='fw-bold'>{$row['nom']} {$row['prenom']}</span></div></td>
                                <td><span class='badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 rounded-pill'>{$row['classe']}</span></td>
                                <td><span class='badge-uid'>{$row['numero_badge']}</span></td>
                                <td class='text-end'>
                                    <a href='modifier.php?id={$row['id']}' class='btn btn-sm btn-light border rounded-3 me-1 text-primary shadow-sm'><i class='fa-solid fa-pen'></i></a>
                                    <a href='action.php?action=supprimer_user&badge={$row['numero_badge']}' class='btn btn-sm btn-light border rounded-3 text-danger shadow-sm' onclick=\"return confirm('Supprimer définitivement cet utilisateur ?');\"><i class='fa-solid fa-trash'></i></a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade p-0" id="blocked">
            <table class="table table-custom mb-0 search-table">
                <thead><tr><th>UID Bloqué</th><th>Date d'interdiction</th><th class="text-end">Action</th></tr></thead>
                <tbody>
                    <?php
                    $query = $pdo->query("SELECT * FROM badges_bloques ORDER BY date_blocage DESC");
                    if ($query->rowCount() == 0) echo "<tr><td colspan='3' class='text-center py-4 text-muted'>Aucun badge n'est bloqué.</td></tr>";
                    while ($row = $query->fetch()) {
                        echo "<tr>
                                <td><span class='badge-uid text-danger border-danger'><i class='fa-solid fa-ban me-2'></i>{$row['numero_badge']}</span></td>
                                <td><span class='text-muted'>{$row['date_blocage']}</span></td>
                                <td class='text-end'><a href='action.php?action=debloquer&badge={$row['numero_badge']}' class='btn btn-sm btn-success rounded-3 fw-semibold shadow-sm'><i class='fa-solid fa-unlock me-1'></i>Autoriser</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="theme.js"></script>
<script>
// SCRIPT DE RECHERCHE
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('.tab-pane.active .search-table tbody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});
</script>
</body>
</html>