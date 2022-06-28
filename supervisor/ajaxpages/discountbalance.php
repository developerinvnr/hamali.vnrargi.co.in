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
	$ids	=	ltrim($_POST['ids'],",");
	$rs_dis	=	$dbconnection->firequery("select * from customertest_tbl where customerid in (".$ids.")");
	while($dis=mysqli_fetch_assoc($rs_dis))
	{
		$balance	=	$dis['balance'];
		$dbconnection->firequery("update customertest_tbl set afterdiscount=afterdiscount-".doubleval($balance).",balance=balance-".doubleval($balance).",admindiscount=admindiscount+".doubleval($balance).",adminremark='".$_POST['remark']."' where customerid=".$dis['customerid']."");

		$dbconnection->firequery("insert into admindiscount_tbl(customerid,remark,discount,creationdate,discountby,franchiseid,centerid,staffid,centerheadid) values(".$dis['customerid'].",'DIRECT DISCOUNT',".doubleval($balance).",'".date('Y\-m\-d H:i:s')."','FRANCHISE',".$_SESSION['franchisedetail'][0]['sessionid'].",".$dis['centerid'].",".$dis['staffid'].",".$_POST['headid'].")");		
	}
}
?>
