<?php
// Afficher les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'bd_connection.php';

// Récupérer toutes les livraisons
$sql = "SELECT * FROM livraisons";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Livraisons</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Liste des Livraisons</h2>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Commande</th>
                <th>ID Livreur</th>
                <th>Date de Livraison</th>
                <th>Statut</th>
                <th>Adresse Livraison</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['id_commande']}</td>
                            <td>{$row['id_livreur']}</td>
                            <td>{$row['date_livraison']}</td>
                            <td>{$row['statut']}</td>
                            <td>{$row['adresse_livraison']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Aucune livraison trouvée.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <!-- Bouton pour revenir à l'index -->
    <a href="index.php" class="btn btn-primary">Retour à l'Accueil</a>
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