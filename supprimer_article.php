<?php
// Afficher les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'bd_connection.php';

// Vérifier si une demande de suppression a été faite
if (isset($_GET['id'])) {
    $id_article = intval($_GET['id']);
    
    // Requête pour supprimer l'article
    $sql = "DELETE FROM articles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_article);

    if ($stmt->execute()) {
        // Rediriger avec un message de succès
        header("Location: supprimer_article.php?message=Article supprimé avec succès.");
        exit();
    } else {
        // Rediriger avec un message d'erreur
        header("Location: supprimer_article.php?error=Erreur lors de la suppression de l'article.");
        exit();
    }

    $stmt->close();
}

// Récupérer tous les articles
$sql = "SELECT * FROM articles";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Liste des Articles</h2>
    
    <?php
    // Afficher les messages de succès ou d'erreur
    if (isset($_GET['message'])) {
        echo "<div class='alert alert-success'>{$_GET['message']}</div>";
    }
    if (isset($_GET['error'])) {
        echo "<div class='alert alert-danger'>{$_GET['error']}</div>";
    }
    ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['prix']}</td>
                            <td>
                                <a href='supprimer_article.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet article ?\");'>Supprimer</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Aucun article trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <!-- Bouton pour revenir à l'index -->
    <a href="index.php" class="btn btn-primary">Retour</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>