<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	if($_POST['s']=="D")
	{
		$accesstoken	=	rand(100000,999999);
		$dbconnection->firequery("update supervisor_tbl set activestatus='DEACTIVE',accesstoken='".$accesstoken."' where supervisorid=".$_POST['supervisorid']."");
	}
	if($_POST['s']=="A")
	{
		$accesstoken	=	rand(100000,999999);	
		$dbconnection->firequery("update supervisor_tbl set activestatus='ACTIVE',accesstoken='".$accesstoken."' where supervisorid=".$_POST['supervisorid']."");
	}
	echo "success";
	exit;
}
?>
