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
	if($_POST['sts']=="T")
	{
		$department		=	$dbconnection->getField("workcode_master","departmentname","recordid=".$_POST['recordid']."");
		if($department!="")
		{
		$deps			=	explode(",",$department);
		$count			=	count($deps);
		$deps[$count]	=	$_POST['depid'];
		}
		else
		{
			$deps	=	array();
			$deps[]	=	$_POST['depid'];
		}
		$department		=	implode(",",$deps);
		$dbconnection->firequery("update workcode_master set departmentname='".$department."' where recordid=".$_POST['recordid']."");
	}
	else
	{
		$department		=	$dbconnection->getField("workcode_master","departmentname","recordid=".$_POST['recordid']."");
		$deps			=	explode(",",$department);
		if(($key = array_search($_POST['depid'], $deps)) !== false) {
			unset($deps[$key]);
		}
		array_values($deps);
		$department		=	implode(",",$deps);
		$dbconnection->firequery("update workcode_master set departmentname='".$department."' where recordid=".$_POST['recordid']."");		
	}
	echo "";
	exit;
}
?>
