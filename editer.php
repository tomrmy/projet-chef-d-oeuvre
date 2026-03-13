<?php 
require 'db.php'; 

// 1. On vérifie qu'on a bien reçu l'ID de l'utilisateur à modifier
if (!isset($_GET['id'])) { 
    header("Location: utilisateurs.php"); 
    exit; 
}
$id = $_GET['id'];

// 2. On récupère ses informations actuelles dans la base de données
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?"); 
$stmt->execute([$id]); 
$user = $stmt->fetch();

// Si l'utilisateur n'existe pas ou a été supprimé entre temps
if (!$user) {
    header("Location: utilisateurs.php"); 
    exit;
}

$message = "";

// 3. Si le formulaire est validé (Bouton "Mettre à jour" cliqué)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom']; 
    $prenom = $_POST['prenom']; 
    $classe = $_POST['classe']; 
    $badge = $_POST['badge'];

    try {
        $update = $pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, classe = ?, numero_badge = ? WHERE id = ?");
        $update->execute([$nom, $prenom, $classe, $badge, $id]);
        
        $message = "<div class='alert alert-success bg-success bg-opacity-10 text-success border-success rounded-3 mb-4'><i class='fa-solid fa-circle-check me-2'></i>Le profil de <b>$prenom $nom</b> a été mis à jour.</div>";
        
        // On met à jour la variable $user pour que l'affichage change tout de suite
        $user['nom'] = $nom; $user['prenom'] = $prenom; $user['classe'] = $classe; $user['numero_badge'] = $badge;
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger bg-danger bg-opacity-10 text-danger border-danger rounded-3 mb-4'><i class='fa-solid fa-circle-xmark me-2'></i>Erreur : Ce numéro de badge est déjà utilisé par un autre profil.</div>";
    }
}

// On charge le design global (Sidebar, Polices, Couleurs)
require 'header.php'; 
?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-8 col-lg-6">
        
        <div class="mb-4">
            <a href="utilisateurs.php" class="text-decoration-none text-muted fw-bold hover-primary">
                <i class="fa-solid fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>

        <div class="card p-5">
            <div class="text-center mb-4">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <h3 class="fw-bold">Modifier le profil</h3>
                <p class="text-muted">Mettez à jour les informations ou réassignez un nouveau badge.</p>
            </div>
            
            <?= $message ?>
            
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Nom</label>
                        <input type="text" class="form-control form-control-lg" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Prénom</label>
                        <input type="text" class="form-control form-control-lg" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase">Service / Classe</label>
                    <input type="text" class="form-control form-control-lg" name="classe" value="<?= htmlspecialchars($user['classe']) ?>" required>
                </div>
                
                <div class="mb-5 border-top pt-4 mt-4" style="border-color: var(--border-color) !important;">
                    <label class="form-label text-primary small fw-bold text-uppercase"><i class="fa-solid fa-microchip me-1"></i> Identifiant RFID Actuel</label>
                    <input type="text" class="form-control form-control-lg border-primary font-monospace bg-light" name="badge" value="<?= htmlspecialchars($user['numero_badge']) ?>" required>
                    <div class="form-text mt-2"><i class="fa-solid fa-circle-info me-1"></i> Si l'utilisateur a perdu son badge, vous pouvez scanner le nouveau et coller son UID ici.</div>
                </div>
                
                <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow-sm text-dark">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Sauvegarder les modifications
                </button>
            </form>
        </div>
    </div>
</div>

</main>
</div>
<script src="theme.js"></script>
</body>
</html>