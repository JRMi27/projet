<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slooms - Espace de Co-working</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="home-page">
    <nav class="navbar navbar-expand-lg navbar-light bg-light custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">
                <img src="images/logo.jpg" alt="Logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
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

    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Bienvenue chez Sloom</h1>
                    <p>Un espace de co-working moderne et dynamique pour les entrepreneurs, les freelancers et les professionnels.</p>
                    <a href="decouverte.php" class="btn btn-primary">Découvrez nos espaces</a>
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2>Nos services</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature">
                        <i class="fas fa-wifi"></i>
                        <h3>Connexion haut débit</h3>
                        <p>Profitez d'une connexion internet ultra-rapide pour rester productif.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature">
                        <i class="fas fa-coffee"></i>
                        <h3>Espace café</h3>
                        <p>Un espace café convivial pour prendre une pause bien méritée.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature">
                        <i class="fas fa-users"></i>
                        <h3>Communauté</h3>
                        <p>Rejoignez une communauté dynamique d'entrepreneurs et de professionnels.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Slooms. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
