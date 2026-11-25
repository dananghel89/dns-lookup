<?php

defined( 'ABSPATH' ) || exit;

// ---

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);

	return $data;
}

function test_print($item, $key) {
	if ( is_int($key) ) {
		return;
	}

	// DNS data (e.g. TXT records) is untrusted; escape before output.
	echo sprintf(
		"%s: %s\n",
		htmlspecialchars( (string) $key, ENT_QUOTES, 'UTF-8' ),
		htmlspecialchars( (string) $item, ENT_QUOTES, 'UTF-8' )
	);
}
