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

$dbconnection->firequery("update workslip_tbl set workslipamount=workslipamount-".doubleval($_POST['total'])." where workslipid=".$_POST['slipid']."");

$total	=	doubleval($_POST['quantity'])*doubleval($_POST['rate']);

$dbconnection->firequery("update workslip_tbl set workslipamount=workslipamount+".doubleval($total)." where workslipid=".$_POST['slipid']."");
$dbconnection->firequery("update workslip_detail set quantity=".doubleval($_POST['quantity']).",rem1='".$_POST['rem1']."',rem2='".$_POST['rem2']."',total=".doubleval($total)." where detailid=".$_POST['did']."");
exit;
?>