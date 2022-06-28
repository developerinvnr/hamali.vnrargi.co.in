		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<?php
		if($_SESSION['datadetail'][0]['authtype']=="ADMIN" || $_SESSION['datadetail'][0]['authtype']=="SUPER ADMIN")
		{
		?>
		<title>Admin Panel</title>
		<?php
		}
		else
		{
		?>
		<title>User Panel</title>		
		<?php
		}
		?>
		<link rel="shortcut icon" href="./images/ico.png">
		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="./assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="./css/cartelstyle.css" />
		<link rel="stylesheet" href="./assets/css/chosen.min.css" />
		<link rel="stylesheet" href="./assets/css/jquery-ui.custom.min.css" />
		<link rel="stylesheet" href="./assets/css/jquery.gritter.min.css" />		

		<style>
		.loader {
		position:absolute;
		text-align:center;
		margin:10% 45%;
		border: 16px solid #f3f3f3;
		  border-radius: 50%;
		  border-top: 16px solid blue;
		  border-right: 16px solid green;
		  border-bottom: 16px solid red;
		  border-left: 16px solid pink;
		  width: 120px;
		  height: 120px;
		  -webkit-animation: spin 2s linear infinite;
		  animation: spin 2s linear infinite;
		  z-index:9;
		  }
		
		@-webkit-keyframes spin {
		  0% { -webkit-transform: rotate(0deg); }
		  100% { -webkit-transform: rotate(360deg); }
		}
		
		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}
		</style>
	

		<!-- text fonts -->
		<link rel="stylesheet" href="./assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="./assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="./assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="./assets/css/ace-rtl.min.css" />
		<?php
		if(trim(decryptvalue($_REQUEST['p']))=="addreagentstock" || trim(decryptvalue($_REQUEST['p']))=="programmelist" || trim(decryptvalue($_REQUEST['p']))=="addtest")
		{
		?>
		<link rel="stylesheet" type="text/css" href="./css/jquery.autocomplete.css" />
	
		<?php
		}
		?>
		<style>
		.btn
		{
			text-decoration:none;
			background-color:#008c40!important;
			border-color:#008c40!important;
		}
		.btn:hover
		{
			text-decoration:none;
			background-color:#008c40 !important;
			border-color:#008c40 !important;
		}
		.btn:focus
		{
			background-color:#008c40 !important;
			border-color:#008c40 !important;
		}
		.btn:active:focus
		{
			background-color:#fe9722 !important;
			border-color:#fe9722 !important;
		}
		.pagelinks
		{
			background-color:#008c40 !important;
			border-color:#008c40 !important;
		}
		.fa
		{
			color:#008c40!important;
		}
		</style>
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.min.css" />
		<![endif]-->
		
		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="./assets/js/ace-extra.min.js"></script>
		<script src="./js/forwarder.js"></script>		

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="./assets/js/html5shiv.min.js"></script>
		<script src="./assets/js/respond.min.js"></script>
		<![endif]-->