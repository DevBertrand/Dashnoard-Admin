<?php
// Afficher les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'bd_connection.php';

// Initialiser le message
$message = '';

// Vérification de l'ajout de la livraison si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_commande = $_POST['id_commande'];
    $adresse = $_POST['adresse'];
    $date_livraison = $_POST['date_livraison'];

    // Préparer la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO livraisons (id_commande, adresse_livraison, date_livraison) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_commande, $adresse, $date_livraison);

    if ($stmt->execute()) {
        $message = "Livraison ajoutée avec succès.";
    } else {
        $message = "Erreur lors de l'ajout de la livraison : " . $conn->error;
    }
}

// Récupérer les commandes pour le formulaire
$commandes = $conn->query("SELECT * FROM commandes");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Livraison</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Ajouter une Livraison</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="id_commande">Choisir une Commande :</label>
            <select name="id_commande" id="id_commande" class="form-control" required>
                <?php while ($commande = $commandes->fetch_assoc()): ?>
                    <option value="<?= $commande['id'] ?>"><?= $commande['id_client'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="adresse">Adresse de Livraison :</label>
            <input type="text" name="adresse" id="adresse" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="date_livraison">Date de Livraison :</label>
            <input type="date" name="date_livraison" id="date_livraison" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter Livraison</button>
    </form>

    <?php if ($message): ?>
        <div class="alert alert-info mt-3"><?= $message ?></div>
    <?php endif; ?>
    
    <a href="index.php" class="btn btn-secondary mt-3">Retour</a>
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