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
	if(intval($_POST['discount'])!=0)
	{
		if(($balance<doubleval($_POST['discount'])))
		{
			echo "error";
			exit;
		}
		else
		{
			$dbconnection->firequery("update customertest_tbl set afterdiscount=afterdiscount-".doubleval($_POST['discount']).",balance=balance-".doubleval($_POST['discount']).",admindiscount=admindiscount+".doubleval($_POST['discount']).",adminremark='".$_POST['remark']."' where customerid=".$_POST['custid']."");

			$dbconnection->firequery("insert into admindiscount_tbl(customerid,remark,discount,creationdate,discountby,franchiseid,centerid,staffid,centerheadid) values(".$_POST['custid'].",'".$_POST['remark']."',".doubleval($_POST['discount']).",'".date('Y\-m\-d H:i:s')."','FRANCHISE',".$_SESSION['franchisedetail'][0]['sessionid'].",".$_POST['centerid'].",".$_POST['staffid'].",".$_POST['headid'].")");
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
