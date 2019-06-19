<?php
/**
 * mailer.php
 *
 * This file handles secure mail transport using the Swiftmailer
 * library with Google reCAPTCHA integration.
 *
 * @author Rochelle Lewis <rlewis37@cnm.edu>
 **/

// require all composer dependencies
require_once("vendor/autoload.php");

// require mail-config.php
require_once("mail-config.php");



// verify user's reCAPTCHA input
$recaptcha = new \ReCaptcha\ReCaptcha($secret);
$resp = $recaptcha->verify($_POST["g-recaptcha-response"], $_SERVER["REMOTE_ADDR"]);

try {
	 //if there's a reCAPTCHA error, throw an exception
	if (!$resp->isSuccess()) {
		throw(new Exception("reCAPTCHA error!"));
	}

	/**
	 * Sanitize the inputs from the form: name, email, subject, and message.
	 * This assumes jQuery (NOT Angular!) will be AJAX submitting the form,
	 * so we're using the $_POST superglobal.
	 **/

	$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
	$subject = filter_input(INPUT_POST, "subject", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);


	// report a successful send!
	echo "<div class=\"alert alert-success\" role=\"alert\">Email successfully sent.</div>";

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to send email: " . $exception->getMessage() . "</div>";
}
