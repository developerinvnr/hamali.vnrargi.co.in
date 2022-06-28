<?php
@session_start();
$t=1;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
$sessionid	=	$_SESSION['supervisordetail'][0]['sessionid'];
if(!isset($_SESSION['supervisordetail'][0]['sessionid']))
{
	echo '<script>document.location.href="./supervisor.php";</script>';
}
require("../validation/validation.php");
include("../enc/urlenc.php");
require('../db/db_connect.php');
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

	$rs_last	=	$dbconnection->firequery("select * from login_history where userid=".$_SESSION['franchisedetail'][0]['sessionid']." order by loginid desc limit 1");
	while($last=mysqli_fetch_assoc($rs_last))
	{
		$lastid	=	$last['loginid'];
	}
	$dbconnection->firequery("update login_history set logouttime='".date('Y\-m\-d H:i:s')."' where loginid=".$lastid."");
	unset($lastid);
	unset($rs_last);
	unset($last);
	session_start(); 
	session_unset(); 
  	session_destroy();  
   echo '<script>document.location.href="./vnr_supervisor";</script>';
?>