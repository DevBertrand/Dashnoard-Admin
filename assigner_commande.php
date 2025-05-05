<?php
// Afficher les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'bd_connection.php';

// Récupérer les livreurs et les commandes pour l'assignation
$livreurs = $conn->query("SELECT * FROM livreurs");
$commandes = $conn->query("SELECT * FROM commandes"); // Assurez-vous que cette table existe

// Initialiser le message
$message = '';

// Vérification de l'assignation si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_commande = $_POST['id_commande'];
    $id_livreur = $_POST['id_livreur'];

    // Vérifier le nombre de commandes du livreur sélectionné
    $query = "SELECT nombre_commandes, nom FROM livreurs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_livreur);
    $stmt->execute();
    $result = $stmt->get_result();
    $livreur = $result->fetch_assoc();

    if ($livreur && $livreur['nombre_commandes'] < 6) {
        // Attribuer la commande au livreur
        $stmt = $conn->prepare("UPDATE commandes SET livreur_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $id_livreur, $id_commande);
        $stmt->execute();

        // Incrémenter le nombre de commandes du livreur
        $stmt = $conn->prepare("UPDATE livreurs SET nombre_commandes = nombre_commandes + 1 WHERE id = ?");
        $stmt->bind_param("i", $id_livreur);
        $stmt->execute();

        $message = "Commande attribuée à " . $livreur['nom'];
    } else {
        // Trouver un autre livreur
        $query = "SELECT * FROM livreurs WHERE nombre_commandes < 6 ORDER BY id";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $nouveau_livreur = $result->fetch_assoc();
            // Attribuer la commande au nouveau livreur
            $stmt = $conn->prepare("UPDATE commandes SET livreur_id = ? WHERE id = ?");
            $stmt->bind_param("ii", $nouveau_livreur['id'], $id_commande);
            $stmt->execute();

            // Incrémenter le nombre de commandes du nouveau livreur
            $stmt = $conn->prepare("UPDATE livreurs SET nombre_commandes = nombre_commandes + 1 WHERE id = ?");
            $stmt->bind_param("i", $nouveau_livreur['id']);
            $stmt->execute();

            $message = "Commande attribuée à " . $nouveau_livreur['nom'];
        } else {
            $message = "Tous les livreurs ont déjà 6 commandes. Veuillez patienter.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner une Commande</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Assigner une Commande</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="id_livreur">Choisir un Livreur :</label>
            <select name="id_livreur" id="id_livreur" class="form-control" required>
                <?php while ($livreur = $livreurs->fetch_assoc()): ?>
                    <option value="<?= $livreur['id'] ?>"><?= $livreur['nom'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_commande">Choisir une Commande :</label>
            <select name="id_commande" id="id_commande" class="form-control" required>
                <?php while ($commande = $commandes->fetch_assoc()): ?>
                    <option value="<?= $commande['id'] ?>"><?= $commande['id_client'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assigner</button>
    </form>
    <a href="index.php" class="btn btn-secondary">Retour</a>

    <?php if ($message): ?>
        <div class="alert alert-info mt-3"><?= $message ?></div>
    <?php endif; ?>
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