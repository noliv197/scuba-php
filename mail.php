<?php
// classes 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_email_validation($email, $name){
    $token = $email.generate_token(25);
    $url = DOMAIN."?page=mail-validation&token=".ssl_crypt($token);
    $info = [
        'subject' => "Confirm your email",
        'alt' => "Hello ".$name.",\n Please click on the link to confirm your registration",
        'body' => "Hello ".$name.",
            \n Please click on the link to confirm your registration:
            <a href='".$url."'>Confirm email</a>
        ",
    ];
    
    echo $url;
    echo '<br>';
    // send_mail($email, $name, $info);
}

function send_mail($email, $name, $info){
    // Mail class
    $mail = new PHPMailer(true);

    try
    {
        // Server configuration
        $mail->isSMTP();        
        $mail->SMTPAuth = true; // SMTP configuration
        $mail->Username = EMAIL;
        $mail->Password = EMAIL_PASS;

        $mail->SMTPSecure = '**tls**';
        $mail->SMTPDebug  = 1;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;

        $mail->setFrom(EMAIL, 'Scubaphp');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        // $mail->Subject = 'Email Validation';
        // $mail->Body    = 'Hello, please validate your registration using the link';
        // $mail->AltBody = 'Hello, please validate your registration using the link';
        $mail->Subject = $info['subject'];
        $mail->Body    = $info['body'];
        $mail->AltBody = $info['alt'];
        
        // Send
        $mail->send();
        echo 'Message sent!';
    }
    catch (Exception $e)
    {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>