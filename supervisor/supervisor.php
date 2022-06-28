<?php
@session_start();
$t=1;
date_default_timezone_set('Asia/Calcutta');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
require("../validation/validation.php");
include("../enc/urlenc.php");

if($_SESSION['supervisordetail'][0]['sessionid']!="" && $_SESSION['supervisordetail'][0]['authtype']=="SUPERVISOR")
{
	echo '<script>document.location.href="./vnr_mainindex?m='.encrypt("profile page").'&p='.encrypt("profilepage").'";</script>';
	exit;
}

require('../db/db_connect.php');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();


if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="login")
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['username']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter user name</li>";
		}
		if(empty($_POST['password']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter password</li>";
		}
		if($flag==0)
		{
			$rs_sel		=	$dbconnection->firequery("select * from supervisor_tbl where (mobilenumber='".$_POST['username']."' or username='".$_POST['username']."') and password='".$_POST['password']."'");
			$i=0;
			while($ro=mysqli_fetch_assoc($rs_sel))
			{
				$i++;
				$supervisorid	=	$ro['supervisorid'];
				$authname		=	$ro['firstname']." ".$ro['lastname'];
				$loginname		=	$ro['mobilenumber'];
				$username		=	$ro['username'];				
				$companyid		=	$ro['companyname'];
				$departmentid	=	$ro['departmentname'];				
				$locationid		=	$ro['locationname'];				
				$logintype		=	"SUPERVISOR";
			}
			if($i>0)
			{
				$_SESSION['supervisordetail']=array();
				$_SESSION['supervisordetail'][0]['sessionid']	=	$supervisorid;
				$_SESSION['supervisordetail'][0]['username']	=	$username;				
				$_SESSION['supervisordetail'][0]['authname']	=	$authname;
				$_SESSION['supervisordetail'][0]['authid']		=	$loginname;
				$_SESSION['supervisordetail'][0]['authtype']	=	$logintype;
				$_SESSION['supervisordetail'][0]['companyid']	=	$companyid;
				$_SESSION['supervisordetail'][0]['departmentid']=	$departmentid;
				$_SESSION['supervisordetail'][0]['locationid']	=	$locationid;												
				unset($msgErr);
				unset($flag);
				$_SESSION['successmessage']	=	"Dear $authname!, Welcome to VNR PVT. LTD. SUPERVISOR PANEL Thanks!";
				echo '<script>document.location.href="./vnr_mainindex?m='.encrypt("dashboard").'&p='.encrypt("dashboard").'";</script>';
				exit;
			}
			else
			{
				$msgErr	=	"Invalid username or password.";
			}
		}
	}
	else
	{
		
	}
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Supervisor Panel</title>
		<link rel="shortcut icon" href="../images/screen.png">
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="../assets/css/fonts.googleapis.com.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="../assets/css/jquery-ui.custom.min.css" />
		<link rel="stylesheet" href="../assets/css/jquery.gritter.min.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../assets/css/ace.min.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="./assets/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />
		<script src="../js/forwarder.js"></script>		
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="./assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="./assets/js/html5shiv.min.js"></script>
		<script src="./assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="login-layout light-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h4 class="blue" id="id-company-text" style="color:#008c40!important;">SUPERVISOR LOGIN WINDOW</h4>								
								<h1>
									<!--<span class="red">ANU</span><span class="white">G</span><span class="red">RAH</span>-->
									
								</h1>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger" style="color:#008c40;">
												<i class="ace-icon fa fa-user" style="color:#008c40;"></i>
												Please Enter Your Login Details
											</h4>

											<div class="space-6"></div>
											<?php
											if($msgErr!='')
											{
											?>
											<div class="alert alert-warning">
												<button type="button" class="close" data-dismiss="alert">
													<i class="ace-icon fa fa-times" style="color:#008c40;"></i>
												</button>
												<strong>Action Result!</strong>
												<br>				
												<?php echo $msgErr;?>
												<br />
											</div>
											<?php
											}
											?>
											<form name="frm" id="frm" action="#" method="post">
												<input type="hidden" name="acn" id="acn" value="login">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="username" id="username" class="form-control" placeholder="Username / Registered Email" value="<?php echo $_POST['username'];?>" tabindex="<?php echo $t++;?>" autofocus onKeyPress="return OnKeyPress(this, event)"/>
															<i class="ace-icon fa fa-user" style="color:#008c40;"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="password" id="password" class="form-control" placeholder="Password" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)"/>
															<i class="ace-icon fa fa-lock" style="color:#008c40;"></i>
														</span>
													</label>
													<div class="space"></div>

													<div class="clearfix">

														<button type="submit" class="width-35 pull-right btn btn-sm btn-primary" tabindex="<?php echo $t++;?>">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Login</span>
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											<div class="space-6"></div>

										</div><!-- /.widget-main -->

										<div class="toolbar clearfix" style="background-color:#008c40!important;">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link" style="color:#fff;">
													<i class="ace-icon fa fa-arrow-left" style="color:#fff;"></i>
													I forgot my password
												</a>
											</div>

										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>
											<p>
												Enter your email and receive instructions
											</p>

											<form name="forgot" id="forgot" action="#" method="post">
												<input type="hidden" name="acn" id="acn" value="forgot">											
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<div class="clearfix">
														<button type="button" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Send Me!</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<!-- /.signup-box -->
							</div><!-- /.position-relative -->

							<div class="navbar-fixed-top align-right">
								<br />
								&nbsp;
								<!--
								<a id="btn-login-dark" href="#">Dark</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-blur" href="#">Blur</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-light" href="#">Light</a>
								&nbsp; &nbsp; &nbsp;
								-->
							</div>
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="../assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="../assets/js/bootstrap.min.js"></script>		
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});
		</script>
	</body>
</html>
<script src="../assets/js/bootstrap.min.js"></script>