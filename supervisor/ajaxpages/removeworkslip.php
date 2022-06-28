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

$dbconnection->firequery("delete from workslip_detail where workslipid=".$_POST['slipid']."");
$dbconnection->firequery("delete from workslip_tbl where workslipid=".$_POST['slipid']."");
$_SESSION['success']	=	"Workslip detail removed successfully!";
exit;


?>