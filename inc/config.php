<?php

/* Only email errors */
ini_set('display_errors', 1);

//set_error_handler('generator_error_handler');
function generator_error_handler($number, $message, $file, $line, $vars)
{
	// Only email errors to me; no notices
	if ( $number > 8 && $number < 2000 ) {
		$email = "
			<p>An error ($number) occurred on line 
			<strong>$line</strong> and in the <strong>file: $file.</strong> 
			<p> $message </p>";
			
		$email .= "<pre>" . print_r($vars, 1) . "</pre>";
		
		$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		error_log($email, 1, 'jeffrey@envato.com', $headers);
		die('Whoops. I must have screwed something up. It will be fixed soon; please try again later.');
	}
}