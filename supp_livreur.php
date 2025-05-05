<?php
// Afficher les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'bd_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'ID du livreur à supprimer
    $id_livreur = $_POST['id_livreur'];

    // Préparer la requête de suppression
    $sql = "DELETE FROM livreurs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Erreur dans la préparation de la requête : " . $conn->error);
    }

    // Lier les paramètres
    $stmt->bind_param("i", $id_livreur);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Livreur supprimé avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la suppression du livreur : " . $stmt->error . "</div>";
    }

    // Fermer la déclaration
    $stmt->close();
}

// Récupérer tous les livreurs pour afficher dans le formulaire
$sql = "SELECT id, nom, prenom FROM livreurs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Livreur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            max-width: 500px;
        }
        h2 {
            color: #343a40;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }
        .btn-secondary {
            margin-top: 10px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Supprimer un Livreur</h2>
    <form action="supp_livreur.php" method="POST">
        <div class="form-group">
            <label for="id_livreur">Sélectionnez un Livreur</label>
            <select class="form-control" id="id_livreur" name="id_livreur" required>
                <option value="">Choisissez un livreur</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nom'] . ' ' . $row['prenom']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Supprimer le Livreur</button>
        <a href="index.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<div class="footer">
    <!--&copy; 2025 Gestion des Livreurs-->
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