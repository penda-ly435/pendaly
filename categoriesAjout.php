<?php
session_start();
require_once "database.php";

$pdo = openConnexion(); 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: accueil.php");
    exit;
}

if (isset($_POST['ajouter'])) {
    $stmt = $pdo->prepare("INSERT INTO categories (name, description, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
    $stmt->execute([$_POST['name'], $_POST['description']]);
    header("Location: categories_liste.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ajouter catégorie</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="container mt-4">

<h2>Ajouter une catégorie</h2>
<form method="post">
<label>Nom de la catégorie</label>
<input type="text" name="name" class="form-control mb-2" placeholder="Entrez le nom" required>

<label>Description</label>
<textarea name="description" class="form-control mb-2" placeholder="Description de la catégorie" required></textarea>

<button type="submit" name="ajouter" class="btn btn-success">Ajouter</button>
</form>

<a href="categories_liste.php" class="btn btn-secondary mt-3">Retour à la liste</a>
</body>
</html>