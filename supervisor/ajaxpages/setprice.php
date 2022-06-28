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
$t=7;
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$rs_detail	=	$dbconnection->firequery("select * from customertest_detail where customerid=".$_POST['customerid']." and centerid=".$_POST['centerid']."");
	$dis		=	$dbconnection->getField("customertest_tbl","customerdiscount","customerid=".$_POST['customerid']."");
	$doctotal	=	0;
	while($row=mysqli_fetch_assoc($rs_detail))
	{
		$rs_dis		=	$dbconnection->firequery("select b.discountamount from doctorratemapping_tbl a inner join doctorratelist_tbl b on b.rateid=a.rateid where a.doctorid=".$_POST['referredbyid']." and a.centerid=".$_POST['centerid']." and b.testid=".doubleval($row['testid'])."");
		while($ro=mysqli_fetch_assoc($rs_dis))
		{
			if($dis==0)
			{
				$doccom	=	$ro['discountamount'];
				$doctotal	=	$doctotal+$doccom;
			}
			else
			{
				$amt	=	round((doubleval($_POST['customerprice'][$key])*$dis/100)/2);
				$doccom	=	round($ro['discountamount']-$amt);
				$doctotal	=	$doctotal+$doccom;				
			}
		}
		
		if(doubleval($row['testamount'])==doubleval($row['customerprice']))
		{
			$custdis	=	doubleval($dis);
			$custdisamt	=	number_format(($row['testamount']*$custdis)/100,'2','.','');
		}
		else
		{
			$custdis	=	doubleval($dis);
			$custdisamt	=	0;
		}				
		$dbconnection->firequery("update customertest_detail set doctorcommission=".doubleval($doccom).",doctorid=".$_POST['referredbyid'].",custdis=".$custdis.",custdisamt=".$custdisamt." where recordid=".$row['recordid']."");		
	}
	$dbconnection->firequery("update customertest_tbl set referredbyid=".$_POST['referredbyid'].",refamt=".doubleval($doctotal)." where customerid=".$_POST['customerid']."");
	echo "success";
	exit;
}
?>
