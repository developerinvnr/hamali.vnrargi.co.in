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
	$rs_ct	=	$dbconnection->firequery("select * from customertest_tbl where customerid in (".$ids.")");
	while($ct=mysqli_fetch_assoc($rs_ct))
	{
		if($dbconnection->firequery("insert into customerreceipt_tbl(referencenumber,customerid,payingamount,paymentdate,creationdate,staffid,centerid,franchiseid,remark,receivedby,headid) values('".$ct['referencenumber']."',".$ct['customerid'].",".$ct['balance'].",'".date('Y\-m\-d H:i:s')."','".date('Y\-m\-d H:i:s')."',".$ct['staffid'].",".$ct['centerid'].",".$ct['franchiseid'].",'BULK UPDATE','FRANCHISE','".$_POST['headid']."')"))
		{
			$dbconnection->firequery("update customertest_tbl set paid=paid+".$ct['balance'].",balance=balance-".$ct['balance']." where customerid=".$ct['customerid']."");	
		}
	}
}
?>
