<?php
session_start();
require_once "database.php";

$pdo = openConnexion();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM produits WHERE categorie_id=?");
    $stmt->execute([$_GET['supprimer']]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
        $stmt->execute([$_GET['supprimer']]);
    } else {
        $erreur = "Impossible de supprimer : des produits sont associés à cette catégorie.";
    }
    header("Location: categories_liste.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Liste des Catégories</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Liste des Catégories</h2>

<?php if (isset($erreur)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<a href="categoriesAjout.php" class="btn btn-success mb-3">Ajouter Catégorie</a>
<a href="accueil.php" class="btn btn-secondary mb-3">Retour</a>

<table class="table table-bordered">
<tr>
<th>Nom</th>
<th>Description</th>
<th>Actions</th>
</tr>

<?php foreach ($categories as $c): ?>
<tr>
<td><?= htmlspecialchars($c['name']) ?></td>
<td><?= htmlspecialchars($c['description']) ?></td>
<td>
<a href="categoriesModifier.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
<a href="categories_liste.php?supprimer=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>