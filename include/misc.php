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

	echo sprintf( "%s: %s\n", $key, $item );
}
