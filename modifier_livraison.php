<?php
include 'bd_connection.php';


$message = '';

// Vérification de la modification de la livraison si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {
    
    $id_commande = $_POST['id_commande'];
    $id_livreur = $_POST['id_livreur'];
    $adresse_livraison = $_POST['adresse_livraison'];
    $date_livraison = $_POST['date_livraison'];

    // Préparer la requête de mise à jour
    $stmt = $conn->prepare("UPDATE livraisons SET id = ?, id_livreur = ?, adresse_livraison = ?, date_livraison = ? WHERE id = ?");
    $stmt->bind_param("iissi", $id_commande, $id_livreur, $adresse_livraison, $date_livraison, $id_livraison);

    if ($stmt->execute()) {
        $message = "Livraison modifiée avec succès.";
    } else {
        $message = "Erreur lors de la modification de la livraison : " . $conn->error;
    }
}

// Récupérer les livraisons pour le tableau
$livraisons = $conn->query("SELECT * FROM livraisons");
$commandes = $conn->query("SELECT * FROM commandes");
$livreurs = $conn->query("SELECT * FROM livreurs");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Livraison</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Modifier une Livraison</h2>

    <?php if ($message): ?>
        <div class="alert alert-info mt-3"><?= $message ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Commande</th>
                <th>Livreur</th>
                <th>Adresse</th>
                <th>Date de Livraison</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($livraison = $livraisons->fetch_assoc()): ?>
                <tr>
                    <td><?= $livraison['id'] ?></td>
                    <td><?= $livraison['id_commande'] ?></td>
                    <td><?= $livraison['id_livreur'] ?></td>
                    <td><?= $livraison['adresse_livraison'] ?></td>
                    <td><?= $livraison['date_livraison'] ?></td>
                    <td>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#modifierModal" data-id="<?= $livraison['id'] ?>" data-commande="<?= $livraison['id_commande'] ?>" data-livreur="<?= $livraison['id_livreur'] ?>" data-adresse="<?= $livraison['adresse_livraison'] ?>" data-date="<?= $livraison['date_livraison'] ?>">Modifier</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="modifierModal" tabindex="-1" role="dialog" aria-labelledby="modifierModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifierModalLabel">Modifier la Livraison</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formModifier" action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_livraison" id="id_livraison">
                        <div class="form-group">
                            <label for="id_commande">Choisir une Commande :</label>
                            <select name="id_commande" id="id_commande" class="form-control" required>
                                <?php while ($commande = $commandes->fetch_assoc()): ?>
                                    <option value="<?= $commande['id'] ?>"><?= $commande['id'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_livreur">Choisir un Livreur :</label>
                            <select name="id_livreur" id="id_livreur" class="form-control" required>
                                <?php while ($livreur = $livreurs->fetch_assoc()): ?>
                                    <option value="<?= $livreur['id'] ?>"><?= $livreur['nom'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="adresse_livraison">Adresse de Livraison :</label>
                            <input type="text" name="adresse_livraison" id="adresse_livraison" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="date_livraison">Date de Livraison :</label>
                            <input type="date" name="date_livraison" id="date_livraison" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" name="modifier" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $('#modifierModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Bouton qui a déclenché le modal
        var id = button.data('id');
        var commande = button.data('commande');
        var livreur = button.data('livreur');
        var adresse = button.data('adresse');
        var date = button.data('date');

        // Remplir les champs du modal avec les données de la livraison
        var modal = $(this);
        modal.find('#id_livraison').val(id);
        modal.find('#id_commande').val(commande);
        modal.find('#id_livreur').val(livreur);
        modal.find('#adresse_livraison').val(adresse);
        modal.find('#date_livraison').val(date);
    });
</script>
<a href="index.php" class="btn btn-secondary">Retour</a>
</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>