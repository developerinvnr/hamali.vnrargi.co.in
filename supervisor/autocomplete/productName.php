<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",1);
ini_set("session.bug_compat_warn",1);
ini_set("session.bug_compat_42",1);

date_default_timezone_set('Asia/Calcutta');
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

$q = trim(strtolower($_GET["q"]));
$sql = "select a.productid,a.productname,b.displayname,c.unitname from product_tbl a inner join productgroup_tbl b on b.groupid=a.productgroup inner join unit_tbl c on c.unitid=a.unitname where a.productname LIKE '%$q%'";
$rsd = $dbconnection->firequery($sql);
while($rs=mysqli_fetch_array($rsd))
{
	$cname		=	$rs['productname']." [<b>".$rs['displayname']."</b>]";
	$pname		=	$rs['productname'];
	$productid	=	$rs['productid'];	
	echo "$cname|$pname|$productid\n";
}

?>