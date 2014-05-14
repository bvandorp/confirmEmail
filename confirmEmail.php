<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

//define sending settings
$mailFrom = ''; //emailadress for the from: field
$replyTo = ''; //replyto emailadress
$subject = ''; //subject for email
$tpl = 'emailConfirmTpl'; //name of chunk to use for the confirmEmail
$siteName = $modx->getOption('site_name'); //retrieve settings from context/system settings

//retrieve values from FormIt hook
$name = $hook->getValue('name');
$email = $hook->getValue('email');
$message = $hook->getValue('message');

///compose message with an array of the settings you want to have available as placeholders on the email template
$emailHTML = $modx->getChunk($tpl,array(
	'site_name' => $siteName,
	'name' => $name,
	'email' => $email,
	'message' => $message
));

//setup mail service settings
$modx->getService('mail', 'mail.modPHPMailer');
$modx->mail->set(modMail::MAIL_BODY,$emailHTML);
$modx->mail->set(modMail::MAIL_FROM, $mailFrom);
$modx->mail->set(modMail::MAIL_FROM_NAME, $siteName);
$modx->mail->set(modMail::MAIL_SUBJECT,$subject);
$modx->mail->address('to',$email);
$modx->mail->address('reply-to', $replyTo);
$modx->mail->setHTML(true);

//send mail and check for success
if (!$modx->mail->send()) {
	$modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: '.$modx->mail->mailer->ErrorInfo); //send error to errorlog
	$errorMsg = '';
	//add an error to the formit error handler, error is the name of the field you want the error to show
	$hook->addError('error',$errorMsg);
	
	//return flase to stop executing other hooks
	return false;
}else{
	$modx->mail->reset();
	//return true to continue other hooks
	return true;
}