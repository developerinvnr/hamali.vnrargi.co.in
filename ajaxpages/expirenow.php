<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$t=10;

if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$rateid	=	$_POST['ratelist'];
	$expiry	=	$_POST['expiry'];
		
	$dbconnection->firequery("update rate_list set expirydate='".date('Y\-m\-d H:i:s')."' where rateid=".$rateid." and expirydate='".date('Y\-m\-d H:i:s',strtotime($expiry))."'");
	$dbconnection->firequery("update rateexpiry_list set nextexpiry='".date('Y\-m\-d H:i:s')."' where rateid=".$rateid." and nextexpiry='".date('Y\-m\-d H:i:s',strtotime($expiry))."'");
	$dbconnection->firequery("update rateexpiry_tbl set nextexpiry='".date('Y\-m\-d H:i:s')."' where rateid=".$rateid." and nextexpiry='".date('Y\-m\-d H:i:s',strtotime($expiry))."'");
	echo "success";
	exit;			
}
?>
