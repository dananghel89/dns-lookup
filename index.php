<?php

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

if ( ! defined( 'APPURL' ) ) {
	define( 'APPURL', 'http://dns-lookup.test' );
}

require_once ABSPATH . 'include/misc.php';
require_once ABSPATH . 'include/app.php';

?>

<!DOCTYPE html>
<html lang="en" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no">

		<title>DNS lookup</title>

		<link rel="shortcut icon" href="<?= APPURL . '/assets/img/dns-favicon.png' ?>">

		<link rel="stylesheet" href="<?= APPURL . '/assets/css/bootstrap.min.css' ?>">
		<link rel="stylesheet" href="<?= APPURL . '/assets/css/style.css' ?>">
	</head>

	<body class="d-flex flex-column h-100">
		<header class="header mb-5">
			<nav class="navbar navbar-light">
				<a class="navbar-brand" href="<?= APPURL ?>">
					<img src="<?= APPURL . '/assets/img/dns-solid.png' ?>" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
					<span class="ml-2 h5">DNS lookup</span>
				</a>

				<a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm px-3">HELP</a>
			</nav>
		</header>

		<main class="main pb-5 mb-5 flex-shrink-0">
			<div class="container">
				<div class="jumbotron bg-light shadow-sm px-4 py-5">
					<form action="<?= APPURL ?>" method="get" class="form-dns">
						<div class="form-group mb-5">
							<input
								type="text" class="form-control bg-transparent border-top-0 border-right-0 border-left-0"
								id="hostname" name="hostname" placeholder="Name" value="<?= $hostname ?>"
							>
						</div>

						<div class="clearfix mx-n1">
							<?php foreach ( $dns_records as $record ) : ?>
								<input
									type="submit" class="btn btn-light <?= ( $type === $record ) ? 'active' : 'not-active' ?>"
									id="<?= $record . '_record' ?>" name="type" value="<?= $record ?>"
								>
							<?php endforeach ?>
						</div>
					</form>
				</div>

				<?php if ( !empty($hostname) ) : ?>
					<div class="dns-response bg-light shadow-sm p-4">
						<?php
							$type_str = 'DNS_' . strtoupper($type);
							$type_int = constant($type_str);

							$get_record = @dns_get_record( $hostname, $type_int );

							if ( !empty($get_record) ) {
								$count  = count($get_record) - 1;
								$output = '';

								foreach ( $get_record as $rk => $rv ) {
									ob_start();
									array_walk_recursive($rv, 'test_print');
									$output .= ob_get_clean();

									if ( $rk < $count ) {
										$output .= "\n";
									}
								}

							} else {
								$output = sprintf( '%s not found', $type_str );
							}
						?>

						<pre class="mb-0"><?= $output ?></pre>
					</div>
				<?php endif ?>
			</div>
		</main>

		<footer class="footer bg-secondary mt-auto py-3">
			<div class="container-fluid">
				<div class="d-flex justify-content-between align-items-center text-white-50">
					<ul class="nav">
						<li class="nav-item">
							<a class="nav-link text-white-50" href="javascript:void(0)">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-white-50" href="javascript:void(0)">Feedback</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-white-50" href="javascript:void(0)">Privacy & Terms</a>
						</li>
					</ul>

					<span>&copy; <?= date('Y') ?> Powered by Bootstrap</span>
				</div>
			</div>
		</footer>

		<script src="<?= APPURL . '/assets/js/jquery-3.5.1.slim.min.js' ?>"></script>
		<script src="<?= APPURL . '/assets/js/bootstrap.bundle.min.js' ?>"></script>
	</body>
</html>
