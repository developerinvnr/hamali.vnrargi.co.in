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
	$workcode	=	trim($_POST['workcode']);
	$slipdate	=	$_POST['slipdate'];
/*	
	foreach($_SESSION['records'] as $key=>$val)
	{
		if($_SESSION['records'][$key]['workcode']==$workcode)
		{
			echo "error|Work code already exist in your work slip. Please check and try againa.";
			exit;
		}
	}
*/	

if($slipdate=="")
{
	if($_POST['workcode']!="")
	{
		$departs	=	$dbconnection->getField("workcode_master","departmentname","workcode=".$_POST['workcode']."");
		$exp		=	explode(",",$departs);
		if(!in_array($_SESSION['supervisordetail'][0]['departmentid'],$exp))
		{
			echo "codeerror|PLEASE CHECK WORK CODE REFERENCE LIST WHICH IS ASSIGNED TO YOU FOR SELECTED HAMALI GROUP NUMBER.";
			die();
		}
	}
	
	$rateid	=	intval($dbconnection->getField("hamaligroup_tbl","ratelistname","hgid=".$_POST['gpno'].""));
	if($rateid!=0)
	{
		$rs_list		=	$dbconnection->firequery("select a.workcode,a.price,a.expirydate,b.actionvalue,b.verb,b.material,b.product,b.notation,b.defaultnarration from rate_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid." and a.workcode=".$_POST['workcode']."");
		$o=0;
		$flag=0;
		while($rs=mysqli_fetch_array($rs_list))
		{
			$o++;
			$workcode			=	$rs['workcode'];
			$price				=	$rs['price'];
			$actionvalue		=	$rs['actionvalue'];
			$verb				=	$rs['verb'];			
			$material			=	$rs['material'];
			$product			=	$rs['product'];
			$notation			=	$rs['notation'];
			$expirydate			=	$rs['expirydate'];
			$defaultnarration	=	$rs['defaultnarration'];
		}
		if($o>0)
		{
			if($expirydate=="" || $expirydate=="1970-01-01 00:00:00" || $expirydate=="0000-00-00 00:00:00")
			{
				echo $workcode."|".$actionvalue." ".$verb." ".$material." ".$product." ".$notation."|".$price."|".$defaultnarration;		
				exit;
			}
			else
			{
				$today	=	date('Y\-m\-d H:i:s');
				if(strtotime(date('Y\-m\-d H:i:s',strtotime($expirydate)))>=strtotime(date('Y\-m\-d H:i:s',strtotime($today))))
				{
					echo $workcode."|".$actionvalue." ".$verb." ".$material." ".$product." ".$notation."|".$price."|".$defaultnarration;
					exit;					
				}
				else
				{					
					echo "error|Rate list expired for selected hamali group. Please check and try again!";
					exit;
				}
			}
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
else
{
	if($_POST['workcode']!="")
	{
		$departs	=	$dbconnection->getField("workcode_master","departmentname","workcode=".$_POST['workcode']."");
		$exp		=	explode(",",$departs);
		if(!in_array($_SESSION['supervisordetail'][0]['departmentid'],$exp))
		{
			echo "codeerror|PLEASE CHECK WORK CODE REFERENCE LIST WHICH IS ASSIGNED TO YOU FOR SELECTED HAMALI GROUP NUMBER.";
			die();
		}
	}
	
	$rateid	=	intval($dbconnection->getField("hamaligroup_tbl","ratelistname","hgid=".$_POST['gpno'].""));
	if($rateid!=0)
	{
		$rs_list		=	$dbconnection->firequery("select a.workcode,a.price,a.expirydate,b.actionvalue,b.verb,b.material,b.product,b.notation,b.defaultnarration from rateexpiry_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid." and a.workcode=".$_POST['workcode']." and '".$slipdate."' between expirydate and nextexpiry");
		$o=0;
		$flag=0;
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
			$expirydate		=	$rs['expirydate'];
			$defaultnarration	=	$rs['defaultnarration'];
		}
		if($o>0)
		{
			echo $workcode."|".$actionvalue." ".$verb." ".$material." ".$product." ".$notation."|".$price."|".$defaultnarration;		
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
}
?>