<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Découvrez nos espaces</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
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
        <h2 class="mt-5 pt-5">Découvrez nos espaces</h2>
        <div class="row">
            <?php
            require_once 'php/PDO.php';

            $query = "SELECT * FROM espace";
            $stmt = $conn->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card">';
                #echo '<img src="' . $row['image_url'] . '" class="card-img-top" alt="' . $row['nomEspace'] . '">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row['nomEspace'] . '</h5>';
                echo '<p class="card-text">' . $row['superfEspace'] . ' m² </p>';
                echo '<p class="card-text">Capacité d\'accueil : ' . $row['capaciteAcc'] . '</p>';
                echo '<a href="demande_reservation.php?id=' . $row['id'] . '" class="btn btn-primary">Réserver</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
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
