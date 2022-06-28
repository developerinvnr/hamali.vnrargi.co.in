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
	echo '<script>document.location.href="./index.php";</script>';
}
require("./smtp/PHPMailerAutoload.php");
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
		<div id="navbar" class="navbar navbar-default ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="./mainindex.php" class="navbar-brand"><!--<small>ANU<span style="color:red;">G</span><span style="color:white;">RAH</span>--> CPL PANEL</small></a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">

						<li class="purple dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
								<span class="badge badge-important">0</span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header"><i class="ace-icon fa fa-exclamation-triangle"></i>0 Notifications</li>

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar navbar-pink">
									<!--
										<li>
											<a href="./mainindex.php?m=<?php echo encrypt("category-add category");?>&p=<?php echo encrypt("addcategory");?>">
												<div class="clearfix">
													<span class="pull-left"><i class="btn btn-xs no-hover btn-pink fa fa-list"></i> Category Request</span>
													<span class="pull-right badge badge-pink"><?php echo $ap;?></span>
												</div>
											</a>
										</li>										
									-->
									</ul>
								</li>

								<!--<li class="dropdown-footer"><a href="#">See all notifications<i class="ace-icon fa fa-arrow-right"></i></a></li>-->
							</ul>
						</li>
						<li class="green dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
								<span class="badge badge-success">0</span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header"><i class="ace-icon fa fa-envelope-o"></i>0 Messages</li>

							</ul>
						</li>

						<li class="light-blue dropdown-modal">
						<?php
						if($_SESSION['datadetail'][0]['authtype']=="ADMIN")
						{
						?>
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="./profilepic/<?php echo trim(decryptvalue($dbconnection->getField("user_tbl","profilepic","userid=".$_SESSION['datadetail'][0]['sessionid']."")));?>" alt="" />
								<span class="user-info"><small>Welcome,</small><?php echo $_SESSION['datadetail'][0]['authname'];?></span>
								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!--<li><a href="#"><i class="ace-icon fa fa-cog"></i>Settings</a></li>-->
								<li><a href="./mainindex.php?m=<?php echo encrypt("profile page");?>&p=<?php echo encrypt("profilepage");?>"><i class="ace-icon fa fa-user"></i>Profile</a></li>
								<li><a href="./mainindex.php?m=<?php echo encrypt("change password");?>&p=<?php echo encrypt("changepassword");?>"><i class="ace-icon fa fa-lock"></i>Change Password</a></li>								
								<li class="divider"></li>
								<li><a href="./logout.php"><i class="ace-icon fa fa-power-off"></i>Logout</a></li>
							</ul>
						<?php
						}
						else
						{
						?>
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">								
								<span class="user-info"><small>Welcome,</small><?php echo $_SESSION['datadetail'][0]['authname'];?></span>
								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!--<li><a href="#"><i class="ace-icon fa fa-cog"></i>Settings</a></li>-->
								<li class="divider"></li>
								<li><a href="./logout.php"><i class="ace-icon fa fa-power-off"></i>Logout</a></li>
							</ul>
						<?php
						}
						?>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>
		<div id="message_to" class="modal fade"> </div>
		
		
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				
				<!--Menu Area-->
				<?php require("./menu.php");?>
				<!--Menu Area Closed-->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
			
			
			<?php 
			require("./birthday_wishes.php");
			//require("./".$p.".php");
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
