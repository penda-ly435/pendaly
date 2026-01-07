<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: produits_liste.php");
    exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id=?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

if (!$produit) {
    header("Location: produits_liste.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if (isset($_POST['modifier'])) {
    $stmt = $pdo->prepare(
        "UPDATE produits SET name=?, description=?, prix=?, quantite=?, categorie_id=?, updated_at=NOW() WHERE id=?"
    );
    $stmt->execute([$_POST['name'], $_POST['description'], $_POST['prix'], $_POST['quantite'], $_POST['categorie_id'], $id]);
    header("Location: produits_liste.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Modifier produit</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Modifier le produit</h2>
<form method="post">
<label>Nom du produit</label>
<input type="text" name="name" class="form-control mb-2" value="<?= htmlspecialchars($produit['name']) ?>" required>

<label>Description</label>
<textarea name="description" class="form-control mb-2" required><?= htmlspecialchars($produit['description']) ?></textarea>

<label>Prix</label>
<input type="number" name="prix" class="form-control mb-2" value="<?= (int)$produit['prix'] ?>" required>

<label>Quantité</label>
<input type="number" name="quantite" class="form-control mb-2" value="<?= (int)$produit['quantite'] ?>" required>

<label>Catégorie</label>
<select name="categorie_id" class="form-control mb-3" required>
    <option value="">-- Choisir --</option>
    <?php foreach ($categories as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $produit['categorie_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
    <?php endforeach; ?>
</select>

<button type="submit" name="modifier" class="btn btn-warning">Modifier</button>
</form>

<a href="produits_liste.php" class="btn btn-secondary mt-3">Retour à la liste</a>
</body>
</html>