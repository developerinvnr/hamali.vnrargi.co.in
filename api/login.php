<?php
@session_start();
$t=1;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

require('../validation/validation.php');
require('../db/db_connect.php');
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$dd 	     	    = Date("d");
$mm				    = Date("m");
$yy				    = Date("Y");
$currentDate	    = $yy."-".$mm."-".$dd;

/*
$_POST['username']="vnr";
$_POST['password']="vnr#@2020";
$_POST['gcmid']="123";
$_SERVER['REQUEST_METHOD']="POST";
*/

if($_SERVER['REQUEST_METHOD']=="POST")
{
	$msgErr	=	"";
	$flag	=	0;
	if(empty($_POST['username']))
	{
		$msgErr.=	"Enter mobile number";
		$flag++;
	}
	if(empty($_POST['password']))
	{
		$msgErr.=	"Enter password";
		$flag++;
	}
	if(empty($_POST['deviceid']))
	{
		$msgErr.=	"Please provide device id";
		$flag++;
	}
	
	if($flag>0)
	{
		$response=array("code"=>'100',"msg"=>$msgErr);
		$response=json_encode($response);
		echo $response;
		exit();
		die();	
	}
	else
	{
		if($dbconnection->isRecordExist("select * from supervisor_tbl where (mobilenumber='".$_POST['username']."' or username='".$_POST['username']."') and password='".$_POST['password']."'"))
		{
			$rs_sel		=	$dbconnection->firequery("select * from supervisor_tbl where (mobilenumber='".$_POST['username']."' or username='".$_POST['username']."') and password='".$_POST['password']."'");
			while($ro=mysqli_fetch_assoc($rs_sel))
			{
				$i++;
				$supervisorid	=	$ro['supervisorid'];
				$authname		=	$ro['firstname']." ".$ro['lastname'];
				$loginname		=	$ro['mobilenumber'];
				$username		=	$ro['username'];				
				$companyid		=	$ro['companyname'];
				$departmentid	=	$ro['departmentname'];				
				$locationid		=	$ro['locationname'];				
				$logintype		=	"SUPERVISOR";
				$worksliptype	=	$ro['worksliptype'];
			}
			if($worksliptype=="NORMAL")
			{
				$worksliptype	=	1;
			}
			else
			{
				$worksliptype	=	2;			
			}
			unset($ro);
			$hamaligroup	=	array();
			$rs_hg		=	$dbconnection->firequery("select * from hamaligroup_tbl where companyname=".$companyid." and locationname=".$locationid."");
			$rid		=	array();
			while($row=mysqli_fetch_assoc($rs_hg))
			{
				array_push($hamaligroup,array('hgid'=>$row['hgid'],'groupname'=>$row['groupname'],'contact_one'=>$row['contact_one'],'contact_two'=>$row['contact_two'],'aadharcard'=>$row['aadharcard'],'pancard'=>$row['pancard'],'companyid'=>$row['companyname'],'locationid'=>$row['locationname'],'rateid'=>$row['ratelistname']));
				$rid[]	=	$row['ratelistname'];
			}
			unset($row);
			$rid		=	array_unique($rid);
			$rids		=	implode(",",$rid);

			$ratelist	=	array();
			//$rs_rate	=	$dbconnection->firequery("select a.rateid,a.workcode,a.price,b.actionvalue,b.verb,b.material,b.product,b.operator,b.quantity,b.notation,b.unit from rate_list a inner join workcode_master b on b.workcode=a.workcode where a.rateid in (".$rids.") and $departmentid in (b.departmentname) order by a.workcode");
			$rs_rate	=	$dbconnection->firequery("select a.rateid,a.workcode,a.price,a.expirydate,b.actionvalue,b.verb,b.material,b.product,b.operator,b.quantity,b.notation,b.unit,b.defaultnarration from rate_list a inner join workcode_master b on b.workcode=a.workcode where a.rateid in (".$rids.") and FIND_IN_SET(".$departmentid.",b.departmentname)>0 order by a.workcode");
			
			while($row=mysqli_fetch_assoc($rs_rate))
			{
				$expiry	=	date('d\-m\-Y H:i:s',strtotime($row['expirydate']));
				array_push($ratelist,array('rateid'=>$row['rateid'],'workcode'=>$row['workcode'],'price'=>$row['price'],'actionvalue'=>$row['actionvalue'],'verb'=>$row['verb'],'material'=>$row['material'],'product'=>$row['product'],'operator'=>$row['operator'],'quantity'=>$row['quantity'],'notation'=>$row['notation'],'unit'=>$row['unit'],'expirydate'=>$expiry,'defaultnarration'=>$row['defaultnarration']));
			}
			
			$response=array("code"=>'300',"msg"=>'Logged In Successfully',"supervisorid"=>$supervisorid,"supervisorname"=>$authname,"mobilenumber"=>$loginname,"username"=>$username,"companyid"=>$companyid,"departmentid"=>$departmentid,"locationid"=>$locationid,"worksliptype"=>$worksliptype,"hamaligroup"=>$hamaligroup,"ratelist"=>$ratelist);
			$response=json_encode($response);
			echo $response;
			exit();
			die();			
		}
		else
		{
			$response=array("code"=>'100',"msg"=>'Invalid username and password!');
			$response=json_encode($response);
			echo $response;
			exit();
			die();			
		}
	}
}
else
{
    $response=array("code"=>'100',"msg"=>'Invalid form submission');
    $response=json_encode($response);
    echo $response;
	exit();
	die();
}
