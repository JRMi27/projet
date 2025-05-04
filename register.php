<?php
session_start();

require_once 'php/PDO.php';
require_once 'autoload.php';

$error_message = "";
$success_message = ""; 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $recaptcha = new \ReCaptcha\ReCaptcha("6Le4NZ8pAAAAANAU65rk9aOu1qJp-RVUWJN4zklC");
    $remoteIp = $_SERVER['REMOTE_ADDR'];
    $gRecaptchaResponse = $_POST['g-recaptcha-response'];
    $resp = $recaptcha->setExpectedHostname('localhost')->verify($gRecaptchaResponse, $remoteIp);

    if ($resp->isSuccess()) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $companyName = $_POST['companyName'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $phoneNumber = $_POST['phoneNumber'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        if ($password !== $confirmPassword) {
            $error_message = "Les mots de passe ne correspondent pas.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE identifiantUtil = :username OR mailUtil = :email");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $error_message = "Le nom d'utilisateur ou l'adresse e-mail est déjà utilisé.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $dateInscription = date("Y-m-d");

                try {
                    $stmt = $conn->prepare("INSERT INTO utilisateur (identifiantUtil, mailUtil, raisonSociale, mdpUtil, telUtil, prenomUtil, nomUtil, dateInscUtil, idTypeUtil) VALUES (:username, :email, :companyName, :password, :phoneNumber, :firstName, :lastName, :dateInscription, 1)");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':companyName', $companyName);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':phoneNumber', $phoneNumber);
                    $stmt->bindParam(':firstName', $firstName);
                    $stmt->bindParam(':lastName', $lastName);
                    $stmt->bindParam(':dateInscription', $dateInscription);
                
                    if ($stmt->execute()) {
                        $success_message = "Votre compte a été créé avec succès."; 
                    } else {
                        $error_message = "Une erreur s'est produite lors de la création du compte. Veuillez réessayer.";
                    }
                } catch(PDOException $e) {
                    $error_message = "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
                }                
            }
        }
    } else {
        $error_message = "Échec de la vérification CAPTCHA. Veuillez réessayer.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="css/style.css" rel="stylesheet"> 
</head>
<body class="register-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-header">
                        <h2 class="mt-0 mb-0">Inscription</h2>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php elseif (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div> 
                        <?php endif; ?> 
                        <form method="POST" onsubmit="return verifyPasswords();">
                            <div class="form-group">
                                <label for="firstName">Prénom :</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Nom :</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Nom d'utilisateur :</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email :</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="companyName">Raison Sociale :</label>
                                <input type="text" class="form-control" id="companyName" name="companyName" required>
                            </div>
                            <div class="form-group">
                                <label for="phoneNumber">Numéro de téléphone :</label>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe :</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirmez le mot de passe :</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="g-recaptcha" data-sitekey="6Le4NZ8pAAAAADs9dpbvmX8_VkXE5NiCHC07w-rC"></div>
                            <button type="submit" name="submit" value="Submit" class="btn btn-primary mt-4">Créer un compte</button>
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                        <a href="login.php" class="btn btn-link">Retour à la page de connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2"></script>
</body>
</html>
