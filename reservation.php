<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="reservations-page">
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
            <div class="col-md-8 mt-5 pt-5">
                <h2>Mes Réservations</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Heure de Début</th>
                            <th scope="col">Heure de Fin</th>
                            <th scope="col">Espace</th>
                            <th scope="col">État</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            session_start();
                            require_once 'php/PDO.php';

                            if (!isset($_SESSION['user_id'])) {
                                header("Location: login.php");
                                exit();
                            }

                            $user_id = $_SESSION['user_id'];
                            $query = "SELECT r.id, r.dateHeureDebReser, r.dateHeureFinReser, e.nomEspace, et.libelleEtat
                                      FROM reservation r
                                      JOIN espace e ON r.idEspace = e.id
                                      JOIN etatreservation et ON r.idEtat = et.id
                                      WHERE r.idUtil = :user_id";
                            $stmt = $conn->prepare($query);
                            $stmt->bindParam(':user_id', $user_id);
                            $stmt->execute();

                            $count = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<th scope='row'>$count</th>";
                                echo "<td>" . date('Y-m-d', strtotime($row['dateHeureDebReser'])) . "</td>";
                                echo "<td>{$row['dateHeureDebReser']}</td>";
                                echo "<td>{$row['dateHeureFinReser']}</td>";
                                echo "<td>{$row['nomEspace']}</td>";
                                echo "<td>{$row['libelleEtat']}</td>";
                                echo "</tr>";
                                $count++;
                            }
                        ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <a href="homepage.php" class="btn btn-secondary">Retour à la page d'accueil</a>
                </div>
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
