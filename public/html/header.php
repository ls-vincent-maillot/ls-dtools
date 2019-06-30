<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<style>
		.sidebar {
			position: fixed;
			top: 0;
			bottom: 0;
			left: 0;
			z-index: 100; /* Behind the navbar */
			padding: 0;
			box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
		}
		.sidebar-sticky {
			position: -webkit-sticky;
			position: sticky;
			top: 48px; /* Height of navbar */
			height: calc(100vh - 48px);
			padding-top: .5rem;
			overflow-x: hidden;
			overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
		}
	</style>
	<title>Lightspeed Development tools</title>
</head>
<body>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
	<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">LS-DTools</a>
</nav>

<div class="container-fluid">
	<nav class="col-md-2 d-none d-md-block bg-light sidebar">
		<?php require_once('html/sidebar.php'); ?>
	</nav>
</div>
