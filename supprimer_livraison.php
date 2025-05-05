<?php
include 'bd_connection.php'; // Connexion à la base

// Supprimer une livraison si l'ID est passé dans l'URL
if (isset($_GET['supprimer_livraison']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM livraisons WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Livraison supprimée avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur : " . $conn->error . "</div>";
    }
}

// Récupérer toutes les livraisons
$sql = "SELECT * FROM livraisons";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer une Livraison</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 30px;
        }

        h2 {
            font-weight: bold;
            color: #343a40;
        }

        .table th, .table td {
            vertical-align: middle !important;
            text-align: center;
        }

        .table thead th {
            background-color: #343a40;
            color: #fff;
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-danger i {
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <h2 class="mb-4">Liste des Livraisons</h2>

    <div class="table-responsive">
        <table class="table table-hover table-bordered bg-white shadow-sm">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>ID_Commande</th>
                    <th>ID_Livreur</th>
                    <th>Date_Livraison</th>
                    <th>Statut</th>
                    <th>Adresse_Livraison</th>
                    <th>Action</th>
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
                                <td>
                                    <a href='supprimer_livraison.php?supprimer_livraison=true&id={$row['id']}'
                                       class='btn btn-sm btn-danger'
                                       onclick=\"return confirm('Supprimer cette livraison ?');\">
                                       <i class='fas fa-trash'></i> Supprimer
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Aucune livraison trouvée.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php $conn->close(); ?>
