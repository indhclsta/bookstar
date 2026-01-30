<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../app/libraries/PHPMailer/src/Exception.php';
require_once '../app/libraries/PHPMailer/src/PHPMailer.php';
require_once '../app/libraries/PHPMailer/src/SMTP.php';

class Mailer {

  public static function send($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'indahcalistaexcella@gmail.com'; // GANTI
      $mail->Password   = 'yghy ebab lfnq nyht';  // GANTI
      $mail->SMTPSecure = 'ssl';
      $mail->Port       = 465;

      $mail->setFrom('indahcalistaexcella@gmail.com', 'BookStar'); // GANTI
      $mail->addAddress($to);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $body;

      return $mail->send();

    } catch (Exception $e) {
      return false;
    }
  }
}
