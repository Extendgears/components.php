<?php

// Use like this `sendMail(
//   'to which mail address',
//   'message',
//   'subject',
//   'from which mail address',
//   'name to',
//   'name from'
// );`
// The only necessary argument is the first one. All the others are optional.

function sendMail($mailTo, $text = '', $subject = '', $mailFrom = '', $nameTo = '', $nameFrom = '') {
	$mailTo   = (string) $mailTo;
	$mailFrom = (string) $mailFrom;
	$text     = (string) $text;
	$subject  = (string) $subject;
	$nameTo   = (string) $nameTo;
	$nameFrom = (string) $nameFrom;

	// if $mailTo is not valid or $mailFrom is not empty and not valid
	if (!validMail($mailTo) || (!empty($mailFrom) && !validMail($mailFrom))) {
		return 0;
	}

	$to   = $nameTo   . ' <' . $mailTo   . '>';
	$from = $nameFrom . ' <' . $mailFrom . '>';

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'From: ' . $from . "\r\n";

	return mail($to, $subject, $text, $headers);
}

?>
