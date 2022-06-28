<?php
@session_start();
$t=1;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
$sessionid	=	$_SESSION['datadetail'][0]['sessionid'];
if(!isset($_SESSION['datadetail'][0]['sessionid']))
{
	echo '<script>document.location.href="./vnr_index";</script>';
}
require("./validation/validation.php");
include("./enc/urlenc.php");
require('./db/db_connect.php');
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

if($_REQUEST['p']!="")
{
	$p	=	trim(decryptvalue($_REQUEST['p']));
}
else
{
	$p	=	"dashboard";
}


?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<?php require("./commonheader.php");?>
	</head>

	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default ace-save-state" style="background-color:#008c40!important;">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="./vnr_mainindex" class="navbar-brand"><small style="float:left;"><span style="color:#fe9722;">VNR SEEDS PVT. LTD.</span></small></a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						

						

						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color:#008c40!important;">
								<img class="nav-user-photo" src="./profilepic/unknown.png" alt="" />
								<span class="user-info"><small>Welcome,</small><?php echo $_SESSION['datadetail'][0]['authname'];?></span>
								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!--<li><a href="#"><i class="ace-icon fa fa-cog"></i>Settings</a></li>-->
								<li><a href="./mainindex.php?m=<?php echo encrypt("change password");?>&p=<?php echo encrypt("changepassword");?>"><i class="ace-icon fa fa-lock" style="color:#008c40!important;"></i>Change Password</a></li>								
								<li class="divider"></li>
								<li><a href="./vnr_logout"><i class="ace-icon fa fa-power-off" style="color:#008c40!important;"></i>Logout</a></li>
							</ul>
							
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<!--<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>						
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>						
						</button>

						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>						
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>						
						</button>
					</div>
					
					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>					
					</div>
				</div>--><!-- /.sidebar-shortcuts -->
				<!--Menu Area-->
				<?php require("./menu.php");?>
				<!--Menu Area Closed-->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
			
			<?php 
			require("./".$p.".php");
			?>
			<!-- /.main-content -->
			<?php require("./footer.php");?>
			
			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i></a>		
		</div><!-- /.main-container -->

		<?php require("./basicscripts.php");?>
		<!-- inline scripts related to this page -->
		<?php require("./indexscripts.php");?>
		
	</body>
</html>
