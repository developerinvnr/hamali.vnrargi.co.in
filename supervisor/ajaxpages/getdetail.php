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
	$gpno		=	$_POST['groupnumber'];
	$rs_sel		=	$dbconnection->firequery("select * from hamaligroup_tbl where hgid=".$_POST['groupnumber']." and companyname=".$_SESSION['supervisordetail'][0]['companyid']." and locationname=".$_SESSION['supervisordetail'][0]['locationid']."");
	$i=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
		$groupname	=	$row['groupname'];
	}
	if($i==0)
	{
		echo "error";
		exit;
	}
	else
	{
		echo $groupname;
		exit;
	}
}

?>
