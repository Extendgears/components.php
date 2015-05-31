<?php

// send a mail
//
// @param string $mailTo
// @param string $text (optional)
// @param string $subject (optional)
// @param string $mailFrom (optional)
// @param string $nameTo (optional)
// @param string $nameFrom (optional)
//
// @return bool true if sending was successful
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

	$headers  = 'MIME-Version: 1.0' . '\r\n';
	$headers .= 'From: ' . $from . '\r\n';

	return mail($to, $subject, $text, $headers);
}

?>
