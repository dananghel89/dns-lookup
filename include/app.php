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

// Some PHP builds (e.g. Windows) lack constants such as DNS_CAA; drop those types.
$dns_records = array_values( array_filter( $dns_records, function ( $record ) {
	return defined( 'DNS_' . strtoupper($record) );
} ) );

$hostname = !empty( $_GET['hostname'] ) ? test_input( $_GET['hostname'] ) : '';
$type = !empty( $_GET['type'] ) ? strtolower( test_input( $_GET['type'] ) ) : '';

// Fall back to A when the type is missing or not one we support.
if ( !in_array( $type, $dns_records, true ) ) {
	$type = 'a';
}
