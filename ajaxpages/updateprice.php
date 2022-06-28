<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	if($dbconnection->firequery("update rate_list set price=".doubleval($_POST['val'])." where recid=".intval($_POST['recid']).""))
	{
		echo "success";
		exit;
	}
	else
	{
		echo "error";
		exit;
	}
}
?>
