<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id=?");
    $stmt->execute([$_GET['supprimer']]);
    header("Location: produits_liste.php");
    exit;
}

$produits = $pdo->query(
    "SELECT p.id, p.name, p.description, p.prix, p.quantite, c.name AS categorie
     FROM produits p 
     LEFT JOIN categories c ON p.categorie_id = c.id"
)->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Liste des Produits</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Liste des Produits</h2>

<a href="produitsAjout.php" class="btn btn-success mb-3">Ajouter Produit</a>
<a href="accueil.php" class="btn btn-secondary mb-3">Retour au catalogue</a>

<table class="table table-bordered">
<tr>
<th>Nom</th>
<th>Description</th>
<th>Prix</th>
<th>Quantité</th>
<th>Catégorie</th>
<th>Actions</th>
</tr>

<?php foreach ($produits as $p): ?>
<tr>
<td><?= htmlspecialchars($p['name']) ?></td>
<td><?= htmlspecialchars($p['description']) ?></td>
<td><?= (int)$p['prix'] ?></td>
<td><?= (int)$p['quantite'] ?></td>
<td><?= htmlspecialchars($p['categorie'] ?? 'Non définie') ?></td>
<td>
<a href="produitsModifier.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
<a href="produits_liste.php?supprimer=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>