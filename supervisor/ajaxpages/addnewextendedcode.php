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



$rateid	=	intval($dbconnection->getField("hamaligroup_tbl","ratelistname","hgid=".$_POST['gpno'].""));
if($rateid!=0)
{
	$rs_list		=	$dbconnection->firequery("select a.workcode,a.price,b.actionvalue,b.verb,b.material,b.product,b.notation from rate_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid." and a.workcode=".$_POST['workcode']."");
	$o=0;
	while($rs=mysqli_fetch_array($rs_list))
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
	if($o>0)
	{
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
	}
	unset($rs_list);
	unset($rs);
}

$dbconnection->firequery("insert into workslip_detail(workslipid,workcode,narration,quantity,rate,total,rem1,rem2,creationdate,supervisorid) values(".$_POST['slipid'].",".$_POST['workcode'].",'".$narration."',".doubleval($_POST['quantity']).",".doubleval($price).",".doubleval($total).",'".$_POST['rem1']."','".$_POST['rem2']."','".date('Y\-m\-d H:i:s')."',".$_SESSION['supervisordetail'][0]['sessionid'].")");

$dbconnection->firequery("update workslip_tbl set workslipamount=workslipamount+".doubleval($total)." where workslipid=".$_POST['slipid']."");

exit;
?>