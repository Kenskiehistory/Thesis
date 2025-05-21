<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require 'vendor/autoload.php';

function sendEmail($to, $name, $email, $password) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply.acereview@gmail.com';
        $mail->Password   = 'zbkj kttf qqpj zkpp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply.acereview@gmail.com', 'No Reply');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your New Account Details';
        $mail->Body    = "
            <h2>Welcome to Ace Review</h2>
            <p>Your account has been created. Here are your login details:</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Password:</strong> {$password}</p>
            <p>Please change your password after your first login.</p>
        ";
        $mail->AltBody = "Welcome to Ace Review Your account has been created. Here are your login details:\n\nEmail: {$email}\nPassword: {$password}\n\nPlease change your password after your first login.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>