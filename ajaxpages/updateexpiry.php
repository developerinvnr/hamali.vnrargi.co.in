<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$t=10;

if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$rateid	=	$_POST['ratelist'];
	$expiry	=	$_POST['expiry'];
	$dates	=	date('Y\-m\-d H:i:s',strtotime('+1 days'));
	
	$lastexpiry	=	"";
	$rs_last	=	$dbconnection->firequery("select * from rateexpiry_tbl where rateid=".$rateid." order by expiry desc limit 1");
	while($lst=mysqli_fetch_assoc($rs_last))
	{
		$lastexpiry	=	$lst['nextexpiry'];
	}
	
	if($lastexpiry=='')
	{
		if(strtotime(date('Y\-m\-d H:i:s',strtotime($expiry)))>=strtotime(date('Y\-m\-d H:i:s',strtotime($dates))))
		{
			$j=0;
			$rs_sel	=	$dbconnection->firequery("select * from rate_tbl where rateid=".$rateid."");
			while($row=mysqli_fetch_assoc($rs_sel))
			{
				$j++;
				if($dbconnection->firequery("insert into rateexpiry_tbl(rateid,ratelistname,expiry,nextexpiry,creationdate,addedby) values(".intval($row['rateid']).",'".$row['ratelistname']."','".date('Y\-m\-d H:i:s')."','".date('Y\-m\-d H:i:s',strtotime($expiry))."','".$row['creationdate']."',".$row['addedby'].")"))
				{
					$rs_det	=	$dbconnection->firequery("select * from rate_list where rateid=".$row['rateid']."");
					while($det=mysqli_fetch_assoc($rs_det))
					{
	$dbconnection->firequery("insert into rateexpiry_list(rateid,workcode,price,expirydate,nextexpiry,creationdate,addedby) values(".$row['rateid'].",".intval($det['workcode']).",".doubleval($det['price']).",'".date('Y\-m\-d H:i:s')."','".date('Y\-m\-d H:i:s',strtotime($expiry))."','".$det['creationdate']."',".$det['addedby'].")");
					}
				}
			}
			if($j>0)
			{
				$dbconnection->firequery("update rate_list set expirydate='".date('Y\-m\-d H:i:s',strtotime($expiry))."' where rateid=".$rateid."");
				echo "success";
				exit;			
			}
		}
		else
		{
			echo "error";
			exit;
		}
	}
	else
	{
		if(strtotime(date('Y\-m\-d H:i:s',strtotime($expiry)))>strtotime(date('Y\-m\-d H:i:s',strtotime($lastexpiry))))
		{
			$j=0;
			$rs_sel	=	$dbconnection->firequery("select * from rate_tbl where rateid=".$rateid."");
			while($row=mysqli_fetch_assoc($rs_sel))
			{
				$j++;
				if($dbconnection->firequery("insert into rateexpiry_tbl(rateid,ratelistname,expiry,nextexpiry,creationdate,addedby) values(".$row['rateid'].",'".$row['ratelistname']."','".date('Y\-m\-d H:i:s',strtotime($lastexpiry))."','".date('Y\-m\-d H:i:s',strtotime($expiry))."','".$row['creationdate']."',".$row['addedby'].")"))
				{
					$rs_det	=	$dbconnection->firequery("select * from rate_list where rateid=".$row['rateid']."");
					while($det=mysqli_fetch_assoc($rs_det))
					{
	$dbconnection->firequery("insert into rateexpiry_list(rateid,workcode,price,expirydate,nextexpiry,creationdate,addedby) values(".intval($row['rateid']).",".intval($det['workcode']).",".doubleval($det['price']).",'".date('Y\-m\-d H:i:s',strtotime($lastexpiry))."','".date('Y\-m\-d H:i:s',strtotime($expiry))."','".$det['creationdate']."',".$det['addedby'].")");
					}
				}
			}
			if($j>0)
			{
				$dbconnection->firequery("update rate_list set expirydate='".date('Y\-m\-d H:i:s',strtotime($expiry))."' where rateid=".$rateid."");
				echo "success";
				exit;			
			}
		}
		else
		{
			echo "error";
			exit;
		}		
	}
}
?>
