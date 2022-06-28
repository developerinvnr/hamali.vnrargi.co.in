<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("./db/db_connect.php");
include("./enc/urlenc.php");
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$rs_sel	=	$dbconnection->firequery("select * from workslip_tbl");
while($row=mysqli_fetch_assoc($rs_sel))
{
	$dbconnection->firequery("update workslip_detail set location=".$row['location'].",department=".$row['department'].",groupnumber=".$row['groupnumber']." where workslipid=".$row['workslipid']."");
	
}
?>