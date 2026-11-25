<?php

defined( 'ABSPATH' ) || exit;

if ( strpos($_SERVER['REQUEST_URI'], 'index.php') !== false ) {
	header('Location: ' . APPURL);
}

// ---

$dns_records = [
	'a',
	'aaaa',
	'any',
	'caa',
	'cname',
	'mx',
	'ns',
	'ptr',
	'soa',
	'srv',
	'txt',
];

$hostname = !empty( $_GET['hostname'] ) ? test_input( $_GET['hostname'] ) : '';
$type = !empty( $_GET['type'] ) ? test_input( $_GET['type'] ) : '';
