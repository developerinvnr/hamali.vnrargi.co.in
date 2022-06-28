<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['datadetail'][0]['sessionid']))
{

	if($_POST['comtype']=="per")
	{
		$rs_sel	=	$dbconnection->firequery("select testid from ratelist_tbl where recordid=".$_POST['rid']."");
		while($ro=mysqli_fetch_assoc($rs_sel))
		{
			$testamount			=	$dbconnection->getField("test_tbl","testamount","testid=".$ro['testid']."");
			$commissionpercent	=	$_POST['docper'];
			$commissionamount	=	ceil(($testamount*$commissionpercent)/100);
//			$spamt				=	$_POST['spamt'];
//			$dis				=	number_format((($testamount-$spamt)*100)/$testamount,'2','.','');
		}
		unset($rs_sel);
		unset($ro);
		if($dbconnection->firequery("update ratelist_tbl set commissionpercent=".$commissionpercent.",commissionamount=".$commissionamount." where recordid=".$_POST['rid'].""))
		{
			echo "success";
			exit;
		}
		else
		{
			echo "error";
			exit;
		}
	}
	if($_POST['comtype']=="amt")
	{
		$rs_sel	=	$dbconnection->firequery("select testid from ratelist_tbl where recordid=".$_POST['rid']."");
		while($ro=mysqli_fetch_assoc($rs_sel))
		{
			$testamount			=	$dbconnection->getField("test_tbl","testamount","testid=".$ro['testid']."");
			$commissionamount	=	$_POST['docamt'];
			$commissionpercent	=	number_format(((100*$commissionamount)/$testamount),'2','.','');
//			$spamt				=	$_POST['spamt'];
//			$dis				=	number_format((($testamount-$spamt)*100)/$testamount,'2','.','');
		}
		unset($rs_sel);
		unset($ro);
		if($dbconnection->firequery("update ratelist_tbl set commissionpercent=".$commissionpercent.",commissionamount=".$commissionamount." where recordid=".$_POST['rid'].""))
		{
			echo "success";
			exit;
		}
		else
		{
			echo "error";
			exit;
		}
	}
}
?>
