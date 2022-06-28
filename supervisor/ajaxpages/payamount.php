<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['supervisordetail'][0]['sessionid']))
{
	$slipid		=	$_POST['slipid'];
	$paydate	=	date('Y\-m\-d H:i:s',strtotime($_POST['paydate']));
	$paymode	=	$_POST['paymode'];
	$pay		=	$_POST['pay'];
	$cdn		=	$_POST['cdn'];
	$bal		=	$_POST['bal'];
	$dbconnection->firequery("insert into payment_detail(payslipid,paymentmode,documentnumber,paidamount,paymentdate,creationdate,supervisorid,department,location,groupnumber) values(".$slipid.",'".$paymode."','".$cdn."',".doubleval($_POST['pay']).",'".$paydate."','".date('Y\-m\-d H:i:s')."',".intval($_SESSION['supervisordetail'][0]['sessionid']).",".intval($_POST['department']).",".intval($_POST['location']).",".intval($_POST['groupnumber']).")");		
	
}
?>
