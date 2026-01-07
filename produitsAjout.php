<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if (isset($_POST['ajouter'])) {
    $stmt = $pdo->prepare(
        "INSERT INTO produits (name, description, prix, quantite, categorie_id)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([$_POST['name'], $_POST['description'], $_POST['prix'], $_POST['quantite'], $_POST['categorie_id']]);
    header("Location: produits_liste.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ajouter produit</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Ajouter un produit</h2>
<form method="post">
<label>Nom du produit</label>
<input type="text" name="name" class="form-control mb-2" placeholder="Entrez le nom du produit" required>

<label>Description</label>
<textarea name="description" class="form-control mb-2" placeholder="Description du produit" required></textarea>

<label>Prix</label>
<input type="number" name="prix" class="form-control mb-2" placeholder="Prix..." required>

<label>Quantité</label>
<input type="number" name="quantite" class="form-control mb-2" placeholder="Quantité disponible" required>

<label>Catégorie</label>
<select name="categorie_id" class="form-control mb-3" required>
    <option value="">-- Choisir --</option>
    <?php foreach ($categories as $c): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
    <?php endforeach; ?>
</select>

<button type="submit" name="ajouter" class="btn btn-success">Ajouter</button>
</form>

<a href="produits_liste.php" class="btn btn-secondary mt-3">Retour à la liste</a>
</body>
</html>