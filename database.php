<?php
function openConnexion()
{
    try {
        $host = "localhost";
        $bd = "gestion_produits";
        $user = "root";
        $pass = "";

        $pdo = new PDO(
            "mysql:host=$host;dbname=$bd;charset=utf8",
            $user,
            $pass
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    } catch (Throwable $th) {
        echo "Erreur de connexion à la base de données";
        return false;
    }
}
?>