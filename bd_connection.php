<?php
$serveur = "localhost";
$bd = "gestion_store";
$user = "root";
$password = "";

// Création de la connexion
$conn = new mysqli($serveur, $user, $password, $bd);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vous pouvez garder cette ligne commentée si vous n'avez pas besoin de fermer la connexion immédiatement
// $conn->close();
?>