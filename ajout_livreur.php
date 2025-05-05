<?php
// Afficher les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'bd_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire et sécuriser les entrées
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hachage du mot de passe
    $cni = htmlspecialchars($_POST['cni']);
    $quartier = htmlspecialchars($_POST['quartier']);
    $heures_service = htmlspecialchars($_POST['heures_service']);

    // Préparer la requête d'insertion
    $sql = "INSERT INTO livreurs (nom, prenom, email, mot_de_passe, cni, quartier, heures_service) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Erreur dans la préparation de la requête : " . $conn->error);
    }

    // Lier les paramètres
    $stmt->bind_param("sssssss", $nom, $prenom, $email, $mot_de_passe, $cni, $quartier, $heures_service);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Livreur ajouté avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'ajout du livreur : " . $stmt->error . "</div>";
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
    <title>Ajouter un Livreur</title>
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
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            margin-top: 10px;
            width: 100%;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Ajouter un Nouveau Livreur</h2>
    <form action="ajout_livreur.php" method="POST">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Mot de Passe</label>
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
        </div>
        <div class="form-group">
            <label for="cni">CNI</label>
            <input type="text" class="form-control" id="cni" name="cni" required>
        </div>
        <div class="form-group">
            <label for="quartier">Quartier</label>
            <select class="form-control" id="quartier" name="quartier" required>
                <option value="Mokolo">Mokolo</option>
                <option value="Briqueterie">Briqueterie</option>
                <option value="Nlongkak">Nlongkak</option>
                <option value="Ngalaba">Ngalaba</option>
                <option value="Mvog-Mbi">Mvog-Mbi</option>
                <option value="Mvog-Ada">Mvog-Ada</option>
                <option value="Emana">Emana</option>
                <option value="Obili">Obili</option>
                <option value="Messa">Messa</option>
                <option value="Nkondengui">Nkondengui</option>
                <option value="Bastos">Bastos</option>
                <option value="Centre commercial">Centre commercial</option>
            </select>
        </div>
        <div class="form-group">
            <label for="heures_service">Heures de Service</label>
            <select class="form-control" id="heures_service" name="heures_service" required>
                <option value="8h-12h">8h-12h</option>
                <option value="12h-16h">12h-16h</option>
                <option value="16h-20h">16h-20h</option>
                <option value="20h-22h">20h-22h</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter le Livreur</button>
        <a href="index.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<div class="footer">
    &copy; 2025 Gestion des Livreurs
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>