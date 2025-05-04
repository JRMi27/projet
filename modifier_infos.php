<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'php/PDO.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    $update_stmt = $conn->prepare("UPDATE utilisateur SET prenom = :prenom, nom = :nom, mailUtil = :email, telUtil = :telephone WHERE id = :user_id");
    $update_stmt->bindParam(':prenom', $prenom);
    $update_stmt->bindParam(':nom', $nom);
    $update_stmt->bindParam(':email', $email);
    $update_stmt->bindParam(':telephone', $telephone);
    $update_stmt->bindParam(':user_id', $user_id);
    
    if ($update_stmt->execute()) {
        header("Location: confirmation.php");
        exit();
    } else {
        echo "Une erreur s'est produite lors de la mise à jour de vos informations. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mes informations</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="modify-info-page">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">
                <img src="images/logo.jpg" alt="Logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reservation.php">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="espaces.php">Espaces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="demande_reservation.php">Demande de réservation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="modifier_infos.php">Modifier mes informations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mt-5">
                <h2 class="mt-5 mb-3">Modifier mes informations</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="modify-info-form">
                    <div class="form-group">
                        <label for="prenom">Prénom :</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo isset($user['prenomUtil']) ? $user['prenomUtil'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo isset($user['nomUtil']) ? $user['nomUtil'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($user['mailUtil']) ? $user['mailUtil'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone :</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo isset($user['telUtil']) ? $user['telUtil'] : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="homepage.php" class="btn btn-secondary">Retour à la page d'accueil</a> 
                </form>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-dark fixed-bottom">
        <div class="container text-center">
            <p class="text-white">&copy; 2024 Slooms. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

</body>
</html>
