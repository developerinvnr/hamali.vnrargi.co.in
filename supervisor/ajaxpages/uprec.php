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

$dbconnection->firequery("update workslip_tbl set workslipdate='".date('Y\-m\-d H:i:s',strtotime($_POST['slipdate']))."',remark='".$_POST['remark']."' where workslipid=".$_POST['slipid']."");

exit;
?>