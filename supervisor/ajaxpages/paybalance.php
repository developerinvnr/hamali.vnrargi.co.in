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
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{

	$balance	=	$dbconnection->getField("customertest_tbl","balance","customerid=".$_POST['custid']."");
	if(intval($_POST['pay'])!=0)
	{
		if(($balance<doubleval($_POST['pay'])))
		{
			echo "error";
			exit;
		}
		else
		{
			$rs_ct	=	$dbconnection->firequery("select * from customertest_tbl where customerid=".$_POST['custid']."");
			while($ct=mysqli_fetch_assoc($rs_ct))
			{
				if($dbconnection->firequery("insert into customerreceipt_tbl(referencenumber,customerid,payingamount,paymentdate,creationdate,staffid,centerid,franchiseid,remark,receivedby,headid) values('".$ct['referencenumber']."',".$ct['customerid'].",".doubleval($_POST['pay']).",'".date('Y\-m\-d H:i:s')."','".date('Y\-m\-d H:i:s')."',".$ct['staffid'].",".$ct['centerid'].",".$ct['franchiseid'].",'".$_POST['remark']."','FRANCHISE','".$_POST['headid']."')"))
				{
					$dbconnection->firequery("update customertest_tbl set paid=paid+".doubleval($_POST['pay']).",balance=balance-".doubleval($_POST['pay'])." where customerid=".$_POST['custid']."");	
				}
			}
			$rs_dt	=	$dbconnection->firequery("select * from customertest_tbl where customerid=".$_POST['custid']."");
			while($dt=mysqli_fetch_assoc($rs_dt))
			{
				$afterdis	=	$dt['afterdiscount'];
				$paid		=	$dt['paid'];
				$balance	=	$dt['balance'];
				$admin		=	$dt['admindiscount'];
			}
			echo "success|$afterdis|$paid|$balance|$admin";
			exit;
		}
	}
	else
	{
		echo "error";
		exit;
	}
}
?>
