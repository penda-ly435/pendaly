<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

$user = $_SESSION['user'] ?? null;

$produits = $pdo->query(
    "SELECT p.id, p.name, p.description, p.prix, p.quantite, c.name AS categorie
     FROM produits p LEFT JOIN categories c ON p.categorie_id = c.id"
)->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Catalogue</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Catalogue</h2>

<?php if ($user): ?>
<p>Connecté : <strong><?= htmlspecialchars($user['username']) ?></strong> (<?= $user['role'] ?>)</p>
<a href="deconnexion.php" class="btn btn-danger mb-3">Déconnexion</a>
<?php else: ?>
<a href="connexion.php" class="btn btn-primary mb-3">Connexion</a>
<a href="inscription.php" class="btn btn-secondary mb-3">S'inscrire</a>
<?php endif; ?>

<h3>Produits</h3>
<table class="table table-bordered">
<tr>
<th>Nom</th>
<th>Description</th>
<th>Prix</th>
<th>Quantité</th>
<th>Catégorie</th>
<?php if ($user && $user['role'] === 'admin'): ?><th>Actions</th><?php endif; ?>
</tr>
<?php foreach ($produits as $p): ?>
<tr>
<td><?= htmlspecialchars($p['name']) ?></td>
<td><?= htmlspecialchars($p['description']) ?></td>
<td><?= (int)$p['prix'] ?></td>
<td><?= (int)$p['quantite'] ?></td>
<td><?= htmlspecialchars($p['categorie'] ?? 'Non définie') ?></td>
<?php if ($user && $user['role'] === 'admin'): ?>
<td>
<a href="produitsModifier.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
<a href="produits_liste.php?supprimer=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
</td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</table>

<h3>Catégories</h3>
<table class="table table-bordered">
<tr>
<th>Nom</th>
<th>Description</th>
<?php if ($user && $user['role'] === 'admin'): ?><th>Actions</th><?php endif; ?>
</tr>
<?php foreach ($categories as $c): ?>
<tr>
<td><?= htmlspecialchars($c['name']) ?></td>
<td><?= htmlspecialchars($c['description']) ?></td>
<?php if ($user && $user['role'] === 'admin'): ?>
<td>
<a href="categoriesModifier.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
<a href="categories_liste.php?supprimer=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
</td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</table>

<?php if ($user && $user['role'] === 'admin'): ?>
<a href="produitsAjout.php" class="btn btn-success">Ajouter Produit</a>
<a href="categoriesAjout.php" class="btn btn-success">Ajouter Catégorie</a>
<a href="tableau_de_bord.php" class="btn btn-dark">Tableau de bord</a>
<?php endif; ?>

</body>
</html>