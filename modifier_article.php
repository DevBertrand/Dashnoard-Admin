<?php
// Afficher les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'bd_connection.php';

// Vérifier si une demande de modification a été faite
$id_article = null;
if (isset($_GET['id'])) {
    $id_article = intval($_GET['id']);
    // Récupérer l'article à modifier
    $sql = "SELECT * FROM articles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_article);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mettre à jour les données de l'article
        $titre = htmlspecialchars($_POST['nom']);
        $description = htmlspecialchars($_POST['description']);
        $prix = $_POST['prix'];

        $sql = "UPDATE articles SET nom = ?, description = ?, prix = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $titre, $description, $prix, $id_article);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Article modifié avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de la modification de l'article : " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
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
    <title>Modifier un Article</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Liste des Articles</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
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
                                <button class='btn btn-warning' data-toggle='modal' data-target='#modifierModal{$row['id']}'>Modifier</button>
                            </td>
                          </tr>";

                    // Modale de modification pour chaque article
                    echo "
                    <div class='modal fade' id='modifierModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='modifierModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='modifierModalLabel'>Modifier l'Article</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body'>
                                    <form action='modifier_article.php?id={$row['id']}' method='POST'>
                                        <div class='form-group'>
                                            <label for='titre'>Titre</label>
                                            <input type='text' class='form-control' id='nom' name='nom' value='".htmlspecialchars($row['nom'])."' required>
                                        </div>
                                        <div class='form-group'>
                                            <label for='description'>Description</label>
                                            <textarea class='form-control' id='description' name='description' required>".htmlspecialchars($row['description'])."</textarea>
                                        </div>
                                        <div class='form-group'>
                                            <label for='prix'>Prix</label>
                                            <input type='number' class='form-control' id='prix' name='prix' step='0.01' value='".htmlspecialchars($row['prix'])."' required>
                                        </div>
                                        <button type='submit' class='btn btn-primary'>Modifier l'Article</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<tr><td colspan='5'>Aucun article trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
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