<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

if (isset($_POST['connexion'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        header("Location: accueil.php");
        exit;
    } else {
        $erreur = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Connexion</h2>
<?php if (isset($erreur)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<form method="post">
<label>Nom d'utilisateur</label>
<input type="text" name="username" class="form-control mb-2" placeholder="saisir ici" required>

<label>Mot de passe</label>
<input type="password" name="password" class="form-control mb-2" placeholder="saisir ici" required>

<button type="submit" name="connexion" class="btn btn-primary">Se connecter</button>
</form>

<a href="inscription.php" class="btn btn-secondary mt-3">S'inscrire</a>
</body>
</html>