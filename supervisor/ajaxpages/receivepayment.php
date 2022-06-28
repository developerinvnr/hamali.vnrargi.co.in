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

if(isset($_SESSION['centerdetail'][0]['sessionid']))
{
	if($dbconnection->firequery("insert into centerreceiving_tbl(staffid,centerid,franchiseid,receivingamount,receivedby,receivingdate,creationdate,centerstatus,franchisestatus,adminstatus) values(".trim(decryptvalue($_POST['staffid'])).",".trim(decryptvalue($_POST['centerid'])).",".trim(decryptvalue($_POST['franchiseid'])).",".doubleval($_POST['result']).",".$_SESSION['centerdetail'][0]['sessionid'].",'".date('Y\-m\-d H:i:s')."','".date('Y\-m\-d H:i:s')."','','','')"))
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
