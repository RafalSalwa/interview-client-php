<?php
if(empty($_POST['name']) || empty($_POST['subject']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  http_response_code(500);
  exit();
}

$name = strip_tags(htmlspecialchars((string) $_POST['name']));
$email = strip_tags(htmlspecialchars((string) $_POST['email']));
$m_subject = strip_tags(htmlspecialchars((string) $_POST['subject']));
$message = strip_tags(htmlspecialchars((string) $_POST['message']));

$to = "info@example.com"; // Change this email to your //
$subject = sprintf('%s:  %s', $m_subject, $name);
$body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: {$name}\n\n\nEmail: {$email}\n\nSubject: {$m_subject}\n\nMessage: {$message}";
$header = 'From: ' . $email;
$header .= 'Reply-To: ' . $email;	

if (!mail($to, $subject, $body, $header)) {
    http_response_code(500);
}
?>
