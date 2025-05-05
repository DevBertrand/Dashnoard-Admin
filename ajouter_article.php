<?php
// Afficher les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'bd_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données de l'article
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $prix = $_POST['prix'];

    // Préparer la requête d'insertion
    $sql = "INSERT INTO articles (nom, description, prix) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Erreur dans la préparation de la requête : " . $conn->error);
    }

    // Lier les paramètres
    $stmt->bind_param("ssd", $titre, $description, $prix);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Article ajouté avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'ajout de l'article : " . $stmt->error . "</div>";
    }

    // Fermer la déclaration
    $stmt->close();
}

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Article</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Ajouter un Nouvel Article</h2>
    <form action="ajouter_article.php" method="POST">
        <div class="form-group">
            <label for="titre">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="prix">Prix</label>
            <input type="number" class="form-control" id="prix" name="prix" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter l'Article</button>
        <a href="index.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

</body>
</html>