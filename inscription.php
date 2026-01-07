<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

if (isset($_POST['inscrire'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        $erreur = "Ce nom d'utilisateur existe déjà.";
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO utilisateurs (username,email,password,role,created_at,updated_at)
             VALUES (?, ?, ?, ?, NOW(), NOW())"
        );
        $stmt->execute([$username, $email, $password, $role]);
        header("Location: connexion.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Inscription</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Inscription</h2>
<?php if (isset($erreur)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form method="post">
<label>Nom d'utilisateur</label>
<input type="text" name="username" class="form-control mb-2" placeholder="saisir ici" required>

<label>Email</label>
<input type="email" name="email" class="form-control mb-2" placeholder="saisir ici" required>

<label>Mot de passe</label>
<input type="password" name="password" class="form-control mb-2" placeholder="saisir ici" required>

<label>Rôle</label>
<select name="role" class="form-control mb-3" required>
    <option value="">-- Choisir --</option>
    <option value="standard">Utilisateur standard</option>
    <option value="admin">Administrateur</option>
</select>

<button type="submit" name="inscrire" class="btn btn-success">S'inscrire</button>
</form>

<a href="connexion.php" class="btn btn-secondary mt-3">Connexion</a>
</body>
</html>