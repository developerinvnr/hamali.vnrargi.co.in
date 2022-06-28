<?php
@session_start();
$t=7;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

$cntrec	=	intval($dbconnection->getField("workslip_detail","count(detailid)","workslipid=".intval($_POST['slipid']).""));

if($cntrec>1)
{
	$dbconnection->firequery("delete from workslip_detail where detailid=".$_POST['did']."");
	$dbconnection->firequery("update workslip_tbl set workslipamount=workslipamount-".doubleval($_POST['total'])." where workslipid=".$_POST['slipid']."");
	exit;
}
else
{
	echo "1";
	exit;
}
?>