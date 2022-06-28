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
$total	=	doubleval($_POST['quantity'])*doubleval($_POST['rate']);

$depid	=	$dbconnection->getField("supervisor_tbl","departmentname","supervisorid=".$_SESSION['supervisordetail'][0]['sessionid']."");
$locid	=	$dbconnection->getField("supervisor_tbl","locationname","supervisorid=".$_SESSION['supervisordetail'][0]['sessionid']."");


$dbconnection->firequery("insert into workslip_detail(workslipid,workcode,narration,quantity,rate,total,rem1,rem2,creationdate,supervisorid,location,department,groupnumber) values(".$_POST['slipid'].",".$_POST['workcode'].",'".$_POST['remark']."',".doubleval($_POST['quantity']).",".doubleval($_POST['rate']).",".doubleval($total).",'".$_POST['rem1']."','".$_POST['rem2']."','".date('Y\-m\-d H:i:s')."',".$_SESSION['supervisordetail'][0]['sessionid'].",".$locid.",".$depid.",".$_POST['groupnumber'].")");

$dbconnection->firequery("update workslip_tbl set workslipamount=workslipamount+".doubleval($total)." where workslipid=".$_POST['slipid']."");

exit;
?>