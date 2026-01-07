<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: categories_liste.php");
    exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$categorie = $stmt->fetch();

if (!$categorie) {
    header("Location: categories_liste.php");
    exit;
}

if (isset($_POST['modifier'])) {
    $stmt = $pdo->prepare("UPDATE categories SET name=?, description=?, updated_at=NOW() WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['description'], $id]);
    header("Location: categories_liste.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Modifier catégorie</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Modifier la catégorie</h2>
<form method="post">
<label>Nom de la catégorie</label>
<input type="text" name="name" class="form-control mb-2" value="<?= htmlspecialchars($categorie['name']) ?>" required>

<label>Description</label>
<textarea name="description" class="form-control mb-2" required><?= htmlspecialchars($categorie['description']) ?></textarea>

<button type="submit" name="modifier" class="btn btn-warning">Modifier</button>
</form>

<a href="categories_liste.php" class="btn btn-secondary mt-3">Retour à la liste</a>
</body>
</html>