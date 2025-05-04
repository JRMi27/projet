<?php
$reservationMessage = "";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'php/PDO.php';

    $dateDebut = $_POST['date'];
    $heureDebut = $_POST['heureDebut'];
    $heureFin = $_POST['heureFin'];
    $espace = $_POST['espace'];
    $message = $_POST['message'];
    $dateFin = $_POST['dateFin']; // Nouvelle variable pour la date de fin

    $dateHeureDebut = $dateDebut . ' ' . $heureDebut;
    $dateHeureFin = $dateFin . ' ' . $heureFin; // Utilisation de la date de fin

    // Calcul du tarif en fonction de la durée de la réservation
    $tarif = calculerTarif($espace, $dateHeureDebut, $dateHeureFin, $conn);

    $query = "INSERT INTO reservation (dateHeureDebReser, dateHeureFinReser, idEspace, idUtil, idEtat, montantReser) VALUES (?, ?, ?, ?, 3, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $dateHeureDebut);
    $stmt->bindParam(2, $dateHeureFin);
    $stmt->bindParam(3, $espace);
    $stmt->bindParam(4, $_SESSION['user_id']);
    $stmt->bindParam(5, $tarif);

    if ($stmt->execute()) {
        header("Location: confirmation.php");
        exit();
    } else {
        $reservationMessage = "Une erreur s'est produite lors de la soumission de votre demande de réservation. Veuillez réessayer.";
    }

    $conn = null; 
}

// Fonction pour calculer le tarif en fonction de la durée de la réservation
function calculerTarif($idEspace, $dateHeureDebut, $dateHeureFin, $conn) {
    // Calcul de la durée de la réservation en heures
    $dateHeureDebut = strtotime($dateHeureDebut);
    $dateHeureFin = strtotime($dateHeureFin);
    $dureeEnSecondes = $dateHeureFin - $dateHeureDebut;
    $dureeEnHeures = $dureeEnSecondes / (60 * 60);

    // Récupération du tarif de base
    $query = "SELECT prix FROM tarif WHERE idEspace = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $idEspace);
    $stmt->execute();
    $tarifBase = $stmt->fetchColumn();

    // Calcul du montant de la réservation en fonction de la durée
    if ($dureeEnHeures <= 12) {
        // Réservation pour une demi-journée (moins de 12 heures)
        $montantReservation = $tarifBase / 2;
    } elseif ($dureeEnHeures <= 24 * 7) {
        // Réservation pour moins de 7 jours
        $montantReservation = $tarifBase * ceil($dureeEnHeures / 24); // Arrondir à la journée supérieure
    } else {
        // Réservation pour plus de 7 jours
        $montantReservation = $tarifBase * 7 + ($dureeEnHeures - 24 * 7) * $tarifBase / 24;
    }

    return $montantReservation;
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Réservation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="reservation-page">
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


    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <h2>Demande de Réservation</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="reservation-form">
                    <div class="form-group">
                    <?php echo $reservationMessage;?>
                        <label for="date">Date début:</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="dateFin">Date de fin :</label>
                        <input type="date" class="form-control" id="dateFin" name="dateFin" required>
                    </div>
                    <div class="form-group">
                        <label for="heureDebut">Heure de début :</label>
                        <input type="time" class="form-control" id="heureDebut" name="heureDebut" required>
                    </div>
                    <div class="form-group">
                        <label for="heureFin">Heure de fin :</label>
                        <input type="time" class="form-control" id="heureFin" name="heureFin" required>
                    </div>
                    <div class="form-group">
                        <label for="espace">Espace :</label>
                        <select class="form-control" id="espace" name="espace" required>
                            <option value="">Choisissez un espace</option>
                            <?php
                                require_once 'php/PDO.php';

                                $query = "SELECT id, nomEspace FROM espace";
                                $stmt = $conn->query($query);
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['id'] . '">' . $row['nomEspace'] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Message :</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Envoyer la demande</button>
                        <a href="homepage.php" class="btn btn-secondary">Retour à la page d'accueil</a>
                    </div>
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
</body>
</html>