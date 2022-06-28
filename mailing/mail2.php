<?php
$to = $_POST['email'];
$sub = "Username and Password of vishwa bazaar seller panel!";
$body="";
require("class.phpmailer.php");
$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = "mail.vishwabazaar.com"; // SMTP server
$mail->SMTPAuth = true;
$mail->Username = "donotreply@vishwabazaar.com";
$mail->Password = "vishwa#2015";
///$body="Name: " . $cname . "\nEmail: " . $cemail . "\nComments: " . $comments;
$mail->From = "donotreply@vishwabazaar.com";
$mail->FromName = "Vishwa Bazzar";
$mail->AddAddress($to);
$mail->Subject = $sub;
$mail->Body = $body;
$mail->WordWrap = 500;
$mail->Send();
?> 