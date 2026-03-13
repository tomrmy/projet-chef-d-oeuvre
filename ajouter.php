<?php 
require 'db.php'; 
require 'header.php'; 

$message = "";
$prefill_badge = isset($_GET['badge']) ? htmlspecialchars($_GET['badge']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom']; $prenom = $_POST['prenom']; $classe = $_POST['classe']; $badge = $_POST['badge'];
    try {
        $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, classe, numero_badge) VALUES (?, ?, ?, ?)")->execute([$nom, $prenom, $classe, $badge]);
        $pdo->prepare("DELETE FROM badges_inconnus WHERE numero_badge = ?")->execute([$badge]);
        $message = "<div class='alert alert-success border-success rounded-3'><i class='fa-solid fa-circle-check me-2'></i>Succès : <b>$prenom $nom</b> enregistré.</div>";
        $prefill_badge = ''; 
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger border-danger rounded-3'><i class='fa-solid fa-circle-xmark me-2'></i>Erreur : Badge déjà utilisé.</div>";
    }
}
?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-8 col-lg-6">
        <div class="card p-5">
            <div class="text-center mb-5">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h3 class="fw-bold">Ajouter un profil</h3>
                <p class="text-muted">Remplissez les informations pour assigner un accès.</p>
            </div>
            
            <?= $message ?>
            
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Nom</label>
                        <input type="text" class="form-control form-control-lg" name="nom" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Prénom</label>
                        <input type="text" class="form-control form-control-lg" name="prenom" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase">Service / Classe</label>
                    <input type="text" class="form-control form-control-lg" name="classe" required>
                </div>
                <div class="mb-5">
                    <label class="form-label text-primary small fw-bold text-uppercase"><i class="fa-solid fa-microchip me-1"></i> Identifiant RFID</label>
                    <input type="text" class="form-control form-control-lg border-primary font-monospace" name="badge" value="<?= $prefill_badge ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">
                    Valider la création
                </button>
            </form>
        </div>
    </div>
</div>

</main></div>
<script src="theme.js"></script>
</body>
</html>