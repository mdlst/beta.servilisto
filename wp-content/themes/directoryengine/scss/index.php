<?php
/**
*	Prototype UI Element
*/
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>DirectoryEngine Prototype</title>
	<link rel="stylesheet" href="">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/reset.css">
	<link rel="stylesheet" type="text/css" href="assets/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="assets/css/customized.css">
	
	<link type="text/css" rel="stylesheet" href="assets/css/shCoreEclipse.css"/>
	<link type="text/css" rel="stylesheet" href="assets/css/shThemeEclipse.css"/>

	<link rel="stylesheet" type="text/css" href="assets/css/prototype.css">
	
	<script type="text/javascript" src="assets/scripts/jquery.min.js"></script>
	<script type="text/javascript" src="assets/scripts/bootstrap.min.js"></script>

	<!--------- Plugins -------->
	<script type="text/javascript" src="assets/plugins/jquery.magnific-raty.js"></script>
	<script type="text/javascript" src="assets/plugins/chosen.jquery.min.js"></script>

</head>
<body>
	<div class="wrapper">
		<div class="prototype-title">
			<h1>DirectoryEngine Prototype UI</h1>
		</div>
		<div class="wrapper-container">
			<div class="container">
				<div class="row">
					<div class="col-md-3 hidden-sm hidden-xs">
						<nav class="sidebar">
							<ul class="nav sidenav">
								<li id="typography">Typography</li>
								<li id="input">Input</li>
								<li id="dropdown">Dropdown</li>
								<li id="button">Button</li>
								<li id="breadcrumb">Breadcrumb</li>
								<li id="pagination">Pagination</li>
								<li id="modals">Modals</li>
								<li id="tabs">Tabs</li>
								<li id="form">Form</li>
								<li id="block-places">Block places</li>
								<li id="categories">Categories</li>
							</ul>
						</nav>
					</div>
					<div class="col-md-9 col-ms-12">
						<div class="prototype-content">
							<!-------- Typography -------->
							<div class="block-element block-typography">
								<div class="title">
									<h2>Typography</h2>
								</div>
								<div class="content-typography">
									<?php include 'elements/typography.php' ?>
								</div>
							</div>

							<!-------- Input -------->
							<div class="block-element block-input">
								<div class="title">
									<h2>Input</h2>
								</div>
								<div class="content-input">
									<?php include 'elements/input.php' ?>
								</div>
							</div>

							<!-------- Dropdown -------->
							<div class="block-element block-dropdown">
								<div class="title">
									<h2>Dropdown</h2>
								</div>
								<div class="content-dropdown">
									<?php include 'elements/dropdown.php' ?>
								</div>
							</div>

							<!-------- Button -------->
							<div class="block-element block-button">
								<div class="title">
									<h2>Button</h2>
								</div>
								<div class="content-button">
									<?php include 'elements/button.php' ?>
								</div>
							</div>

							<!-------- Breadcrumb -------->
							<div class="block-element block-breadcrumb">
								<div class="title">
									<h2>Breadcrumb</h2>
								</div>
								<div class="content-breadcrumb">
									<?php include 'elements/breadcrumb.php' ?>
								</div>
							</div>

							<!-------- Pagination -------->
							<div class="block-element block-pagination">
								<div class="title">
									<h2>Pagination</h2>
								</div>
								<div class="content-pagination">
									<?php include 'elements/pagination.php' ?>
								</div>
							</div>
							<!-------- Modals -------->
							<div class="block-element block-modals">
								<div class="title">
									<h2>Modals</h2>
								</div>
								<div class="content-modals">
									<?php include 'elements/modals.php' ?>
								</div>
							</div>

							<!-------- Tabs -------->
							<div class="block-element block-tabs">
								<div class="title">
									<h2>Tabs</h2>
								</div>
								<div class="content-tabs">
									<?php include 'elements/tabs.php' ?>
								</div>
							</div>

							<!-------- Form -------->
							<div class="block-element block-form">
								<div class="title">
									<h2>Form</h2>
								</div>
								<div class="content-form">
									<?php include 'elements/form.php' ?>
								</div>
							</div>

							<!-------- Places block -------->
							<div class="block-element block-block-places">
								<div class="title">
									<h2>Block places</h2>
								</div>
								<div class="content-block-places">
									<?php include 'elements/block-places.php' ?>
								</div>
							</div>
							<!-------- Places block -------->
							<div class="block-element block-categories">
								<div class="title">
									<h2>Categories</h2>
								</div>
								<div class="content-categories">
									<?php include 'elements/categories.php' ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
	<!------ SyntaxHighlighter ------>
	<script type="text/javascript" src="assets/scripts/shCore.js"></script>
	<script type="text/javascript" src="assets/scripts/shBrushJScript.js"></script>
	<script type="text/javascript" src="assets/scripts/shBrushXml.js"></script>

	<script type="text/javascript" src="assets/scripts/clipboard.js"></script>
	
	<!------ Backbone ------>
	<script type="text/javascript" src="assets/scripts/underscore-min.js"></script>
	<script type="text/javascript" src="assets/scripts/backbone-min.js"></script>

	<script type="text/javascript" src="assets/scripts/app.js"></script>	
</body>
</html>