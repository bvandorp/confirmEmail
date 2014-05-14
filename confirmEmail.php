<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

//verzamelen gegevens
//$voornaam = $hook->getValue('voornaam');
$email = $hook->getValue('email');

//$voornaam = 'Bart';
//$email = 'henk@raadhuis.com';

//site name
$site_name = $modx->getOption('site_name');

$message = $modx->parseChunk('email_bevestiging_tpl',array(
	'site_name' => $site_name
));

//mail versturen
$modx->getService('mail', 'mail.modPHPMailer');
$modx->mail->set(modMail::MAIL_BODY,$message);
$modx->mail->set(modMail::MAIL_FROM, $modx->getOption('reply_to'));
$modx->mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('site_name'));
$modx->mail->set(modMail::MAIL_SUBJECT,'Bevestiging van uw sollicitatie');
$modx->mail->address('to',$email);
$modx->mail->address('reply-to', $modx->getOption('reply_to'));
$modx->mail->setHTML(true);
if (!$modx->mail->send()) {
$modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: '.$modx->mail->mailer->ErrorInfo);
}
$modx->mail->reset();


//return voor verder uitvoeren hooks
return true;