<?php
@session_start();
date_default_timezone_set('Asia/Calcutta');
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
	$recdate	=	date('Y\-m\-d',strtotime($_POST['insdate']))." ".date('H:i:s');
	if(doubleval($_POST['amount'])<=doubleval($_POST['balance']))
	{
		if($dbconnection->firequery("insert into centerreceiving_tbl(staffid,supervisorid,centerid,franchiseid,receivingamount,receivedby,receivingdate,creationdate,centerstatus,franchisestatus,adminstatus,remark) values(".$_POST['staffid'].",".$_POST['headid'].",".$_POST['centerid'].",".$_POST['franchiseid'].",".doubleval($_POST['amount']).",".$_POST['headid'].",'".$recdate."','".date('Y\-m\-d H:i:s')."','','','','".$_POST['remark']."')"))
		{
			$b		=	$_POST['balance']-$_POST['amount'];
			$ppd	=	$_POST['ppd']+$_POST['amount'];
			echo "success|$b|$ppd";
			exit;
		}
		else
		{
			echo "error";
			exit;
		}
	}
	else
	{
		echo "error";
		exit;
	}
}

?>
