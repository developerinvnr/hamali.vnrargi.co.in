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
	if(doubleval($_POST['balance'])<doubleval($_POST['amount']))
	{
		echo "error";
		exit;
	}
	if($dbconnection->firequery("insert into franchisereceiving_tbl(supervisorid,centerid,franchiseid,receivingamount,receivedby,receivingdate,remark,creationdate) values(".$_POST['supervisorid'].",".$_POST['centerid'].",".$_POST['franchiseid'].",".doubleval($_POST['amount']).",".$_SESSION['franchisedetail'][0]['sessionid'].",'".date('Y\-m\-d H:i:s')."','".$_POST['remark']."','".date('Y\-m\-d H:i:s')."')"))
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
