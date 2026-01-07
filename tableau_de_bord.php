<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

$nbProduits     = $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
$nbCategories   = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$nbUtilisateurs = $pdo->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();

$derniersUtilisateurs = $pdo->query(
    "SELECT username, role, created_at 
     FROM utilisateurs 
     ORDER BY created_at DESC 
     LIMIT 5"
)->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Tableau de bord</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Tableau de bord Administrateur</h2>

<a href="accueil.php" class="btn btn-secondary mb-3">Retour au catalogue</a>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body text-center">
                <h5>Produits</h5>
                <h3><?= $nbProduits ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body text-center">
                <h5>Catégories</h5>
                <h3><?= $nbCategories ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body text-center">
                <h5>Utilisateurs</h5>
                <h3><?= $nbUtilisateurs ?></h3>
            </div>
        </div>
    </div>
</div>

<h3>Derniers utilisateurs inscrits</h3>
<table class="table table-bordered">
<tr>
    <th>Nom d'utilisateur</th>
    <th>Rôle</th>
    <th>Date</th>
</tr>

<?php foreach ($derniersUtilisateurs as $u): ?>
<tr>
    <td><?= htmlspecialchars($u['username']) ?></td>
    <td><?= htmlspecialchars($u['role']) ?></td>
    <td><?= $u['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>