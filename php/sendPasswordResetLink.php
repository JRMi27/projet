<?php
require 'PDO.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["email"])) {
    $email = trim($_POST["email"]);
    
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE mailUtil = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
       
        $token = bin2hex(random_bytes(50)); 

        
        $stmt = $conn->prepare("UPDATE utilisateur SET reset_token = ? WHERE mailUtil = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

       
        $resetLink = "https://slooms.com/resetPasswordForm.php?token=" . $token;

        
        $to = $email;
        $subject = "Réinitialisation de votre mot de passe";
        $message = "Bonjour,\n\nPour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant : " . $resetLink . "\n\nSi vous n'avez pas demandé à réinitialiser votre mot de passe, ignorez simplement cet e-mail.";
        $headers = "From: no-reply@yslooms.com\r\n";
        $headers .= "Reply-To: no-reply@slooms.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            echo "Un lien de réinitialisation a été envoyé à votre adresse e-mail.";
        } else {
            echo "L'envoi de l'e-mail de réinitialisation a échoué.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet e-mail.";
    }
} else {
    echo "Veuillez soumettre votre adresse e-mail.";
}
?>
