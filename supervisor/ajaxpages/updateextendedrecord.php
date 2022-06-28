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

$rs_rec		=	$dbconnection->firequery("select * from workcode_master where workcode=".$_POST['workcode']."");
$o=0;
while($rs=mysqli_fetch_array($rs_rec))
{
	$o++;
	$workcode		=	$rs['workcode'];
	$price			=	$rs['price'];
	$actionvalue	=	$rs['actionvalue'];
	$verb			=	$rs['verb'];			
	$material		=	$rs['material'];	
	$product		=	$rs['product'];						
	$notation		=	$rs['notation'];			
}
$total	=	doubleval($_POST['quantity'])*doubleval($price);
$narration	=	"";
if($_POST['rem1']=="" && $_POST['rem2']=="")
{
	$narration	=	$actionvalue." ".$verb." ".$material." ".$product." ".$notation;
}
else if($_POST['rem1']=="" && $_POST['rem2']!="")
{
	$narration	=	$actionvalue." ".$verb." ".$material." ".$_POST['rem2']." ".$notation;
}
else if($_POST['rem1']!="" && $_POST['rem2']=="")
{
	$narration	=	$actionvalue." ".$verb." ".$_POST['rem1']." ".$product." ".$notation;
}
else
{
	$narration	=	$actionvalue." ".$verb." ".$_POST['rem1']." ".$_POST['rem2']." ".$notation;					
}
unset($rs_rec);
unset($rs);


$total	=	doubleval($_POST['quantity'])*doubleval($_POST['rate']);

$dbconnection->firequery("update workslip_tbl set workslipamount=workslipamount+".doubleval($total)." where workslipid=".$_POST['slipid']."");


$dbconnection->firequery("update workslip_detail set quantity=".doubleval($_POST['quantity']).",rem1='".$_POST['rem1']."',rem2='".$_POST['rem2']."',total=".doubleval($total).",narration='".$narration."' where detailid=".$_POST['did']."");
exit;
?>