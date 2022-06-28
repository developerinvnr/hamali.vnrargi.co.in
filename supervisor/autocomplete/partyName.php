<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",1);
ini_set("session.bug_compat_warn",1);
ini_set("session.bug_compat_42",1);

date_default_timezone_set('Asia/Calcutta');
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

$q = trim(strtolower($_GET["q"]));
$sql = "select * from party_tbl where partyname LIKE '%$q%'";
$rsd = $dbconnection->firequery($sql);
while($rs=mysqli_fetch_array($rsd))
{
	$cname		=	$rs['partyname'];
	$mobile		=	$rs['mobilenumber'];
	$address	=	$rs['address'];
	$partyid	=	$rs['partyid'];	
	echo "$cname|$mobile|$address|$partyid\n";
}

?>