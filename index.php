<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            padding: 30px;
            background-color: #343a40;
            color: white;
            position: fixed;
            transition: width 0.3s;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
            margin-bottom: 5px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar i {
            margin-right: 15px;
            font-size: 18px;
        }
        .submenu {
            display: none;
            padding-left: 20px;
        }
        .client-menu:hover .submenu,
        .livreur-menu:hover .submenu,
        .articles-menu:hover .submenu,
        .livraison-menu:hover .submenu {
            display: block;
        }
        .content {
            margin-left: 250px;
            padding: 30px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        .nav-links {
            display: flex;
            justify-content: space-around;
        }
        .welcome-message {
            font-size: 20px;
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
                padding: 15px;
            }
            .nav-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Tableau de bord Admin</h2>
    <div class="client-menu">
        <a href="#"><i class="fas fa-users"></i>Gestion Clients</a>
        <div class="submenu">
            <a href="index.php?afficher_clients=true&action=modifier"><i class="fas fa-edit"></i>Modifier un Client</a>
            <a href="index.php?afficher_clients=true&action=supprimer"><i class="fas fa-trash"></i>Supprimer un Client</a>
        </div>
    </div>
    <div class="livreur-menu">
        <a href="#"><i class="fas fa-truck"></i>Gestion Livreurs</a>
        <div class="submenu">
            <a href="ajout_livreur.php"><i class="fas fa-plus"></i>Ajouter un livreur</a>
            <a href="supp_livreur.php"><i class="fas fa-trash"></i>Supprimer un livreur</a>
            <a href="assigner_commande.php"><i class="fas fa-check"></i>Assigner une Commande</a> <!-- Nouveau sous-menu -->
        </div>
    </div>
    <div class="articles-menu">
        <a href="#"><i class="fas fa-box"></i>Gestion Articles</a>
        <div class="submenu">
            <a href="ajouter_article.php"><i class="fas fa-plus"></i>Ajouter un Article</a>
            <a href="modifier_article.php"><i class="fas fa-edit"></i>Modifier un Article</a>
            <a href="supprimer_article.php"><i class="fas fa-trash"></i>Supprimer un Article</a>
        </div>
    </div>
    <div class="livraison-menu">
        <a href="#"><i class="fas fa-truck"></i>Gérer les Livraisons</a>
        <div class="submenu">
            <a href="liste_livraisons.php"><i class="fas fa-list"></i>Liste des Livraisons</a>
            <a href="ajouter_livraison.php"><i class="fas fa-plus"></i>Ajouter une Livraison</a>
            <a href="modifier_livraison.php"><i class="fas fa-edit"></i>Modifier une Livraison</a>
            <a href="supprimer_livraison.php"><i class="fas fa-trash"></i>Supprimer une Livraison</a>
        </div>
    </div>
    <a href="messages.php"><i class="fas fa-envelope"></i>Messages aux Clients</a>
    <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
</div>

<div class="content">
    <div class="header">
        <div class="nav-links">
            <a href="clients.php" class="text-white">Gestion Clients</a>
            <a href="livreurs.php" class="text-white">Gestion Livreurs</a>
            <a href="articles.php" class="text-white">Gestion Articles</a>
            <a href="messages.php" class="text-white">Messages aux Clients</a>
            <a href="logout.php" class="text-danger">Déconnexion</a>
        </div>
    </div>

    <?php
    // Inclure le fichier de connexion à la base de données
    include 'bd_connection.php';

    // Vérifier si une demande de suppression a été faite
    if (isset($_GET['supprimer_client']) && isset($_GET['id'])) {
        $client_id = intval($_GET['id']);
        $sql = "DELETE FROM clients WHERE id = $client_id";
        
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Client supprimé avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de la suppression du client : " . $conn->error . "</div>";
        }
    }

    // Vérifier si le tableau des clients doit être affiché
    if (isset($_GET['afficher_clients']) && $_GET['afficher_clients'] === 'true') {
        // Récupérer tous les clients
        $sql = "SELECT * FROM clients";
        $result = $conn->query($sql);
        ?>

        <h2>Liste des Clients</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
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
                                <td>{$row['email']}</td>
                                <td>
                                    <a href='modifier_client.php?id={$row['id']}' class='btn btn-warning'>Modifier</a>
                                    <a href='index.php?supprimer_client=true&id={$row['id']}' class='btn btn-danger'>Supprimer</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Aucun client trouvé.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
    }
    ?>

</div>

<?php
// Fermer la connexion
$conn->close();
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>