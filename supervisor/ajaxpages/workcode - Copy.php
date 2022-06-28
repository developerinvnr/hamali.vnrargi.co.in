<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

if(isset($_SESSION['supervisordetail'][0]['sessionid']))
{
	date_default_timezone_set('Asia/Calcutta');
	include("../../db/db_connect.php");
	include("../../enc/urlenc.php");
	$dbconnection = new DatabaseConnection;
	$dbconnection->connect();
	
	$gpno		=	$_POST['gpno'];
	$workcode	=	$_POST['workcode'];
	foreach($_SESSION['records'] as $key=>$val)
	{
		if($_SESSION['records'][$key]['workcode']==$workcode)
		{
			echo "error|Work code already exist in your work slip. Please check and try againa.";
			exit;
		}
	}
	
	
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
		if($o>0)
		{
			echo $workcode."|".$actionvalue." ".$verb." ".$material." ".$product." ".$notation."|".$price;		
			exit;
		}
		else
		{
			echo "error|Could not find work code detail";
			exit;
		}
		unset($rs_list);
		unset($rs);
	}
	else
	{
		echo "error|Rate list not assigned";
		exit;
	}
}
?>