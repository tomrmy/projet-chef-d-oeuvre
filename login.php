<?php
session_start();
// Si on valide le formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    // Le mot de passe est "admin123" (tu peux le changer)
    if ($password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $erreur = "Mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Connexion | Pro-Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card p-5 shadow-lg" style="max-width: 400px; width: 100%; border-radius: 15px;">
        <div class="text-center mb-4">
            <i class="fa-solid fa-shield-halved text-primary" style="font-size: 3rem;"></i>
            <h3 class="fw-bold mt-3">Pro-Access</h3>
            <p class="text-muted">Espace Sécurisé</p>
        </div>
        <?php if(isset($erreur)) echo "<div class='alert alert-danger'>$erreur</div>"; ?>
        <form method="POST">
            <div class="mb-4">
                <input type="password" name="password" class="form-control form-control-lg text-center" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold rounded-pill">Se connecter</button>
        </form>
    </div>
</body>
</html>