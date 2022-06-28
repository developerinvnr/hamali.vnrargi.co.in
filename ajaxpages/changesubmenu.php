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
	if($_POST['cstatus']==1)
	{
		$dbconnection->firequery("update permission_tbl set permission=0 where id=".$_POST['id']."");
	}
	else
	{
		$dbconnection->firequery("update permission_tbl set permission=1 where id=".$_POST['id']."");	
	}
}
