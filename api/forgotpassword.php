<?php
@session_start();
$t=1;
date_default_timezone_set('Asia/Calcutta');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

include("../enc/urlenc.php");
require('../validation/validation.php');
require('../db/db_connect.php');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$dd 	     	    = Date("d");
$mm				    = Date("m");
$yy				    = Date("Y");
$currentDate	    = $yy."-".$mm."-".$dd;

//$_POST['mobilenumber']="Gajendra";
//$_POST['password']="9926886681";
//$_POST['deviceid']="deviceid";

//$_SERVER['REQUEST_METHOD']="POST";
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['meth']=="forgot")
	{
		$msgErr	=	"";
		$flag	=	0;
		if(empty($_POST['mobilenumber']))
		{
			$msgErr.=	"Enter mobile number";
			$flag++;
		}
		else
		{
			if(!CheckMobile($_POST['mobilenumber']))
			{
				$msgErr.=	"Enter valid mobile number";
				$flag++;				
			}
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
			$rs_sel	=	$dbconnection->firequery("select * from supervisor_tbl where (mobilenumber='".$_POST['mobilenumber']."' or username='".$_POST['mobilenumber']."')");
			$i=0;
			while($row=mysqli_fetch_assoc($rs_sel))
			{
				$i++;
				$otp		=	"12345";//rand(100001,999999);
				$dbconnection->firequery("insert into forgot_tbl(mobilenumber,otp,activestatus) values('".$row['mobilenumber']."','".$otp."','PENDING')");
				//require("../sms/sendsms.php");				
				//$message	=	"Password reset verifivation otp is ".$otp." Thanks & Regards \n VNR SEED PVT. LTD.";
				//SendSms($row['mobilenumber'],$message);
				$response=array("code"=>'300',"msg"=>"Verification OTP sent successfully.");
				$response=json_encode($response);
				echo $response;
				exit();
				die();			
			}
			if($i==0)
			{
				$response=array("code"=>'100',"msg"=>'Invalid mobile number');
				$response=json_encode($response);
				echo $response;
				exit();
				die();			
			}
		}	
	}
	if($_POST['meth']=="verify")
	{
		$msgErr	=	"";
		$flag	=	0;
		if(empty($_POST['mobilenumber']))
		{
			$msgErr.=	"Enter mobile number";
			$flag++;
		}
		else
		{
			if(!CheckMobile($_POST['mobilenumber']))
			{
				$msgErr.=	"Enter valid mobile number";
				$flag++;				
			}
		}
		if(empty($_POST['newpassword']))
		{
			$msgErr.=	"Enter new password";
			$flag++;
		}
		if(empty($_POST['otp']))
		{
			$msgErr.=	"Enter OTP";
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
			$supervisorid	=	$dbconnection->getField("supervisor_tbl","supervisorid","username='".$_POST['mobilenumber']."' or mobilenumber='".$_POST['mobilenumber']."'");
			$mobilenumber	=	$dbconnection->getField("supervisor_tbl","mobilenumber","username='".$_POST['mobilenumber']."' or mobilenumber='".$_POST['mobilenumber']."'");
			$rs_sel	=	$dbconnection->firequery("select * from forgot_tbl where mobilenumber='".$mobilenumber."' and otp='".$_POST['otp']."' and activestatus='PENDING'");
			$i=0;
			while($row=mysqli_fetch_assoc($rs_sel))
			{
$i++;
$dbconnection->firequery("update supervisor_tbl set password='".$_POST['newpassword']."' where mobilenumber='".$mobilenumber."'");
$dbconnection->firequery("update forgot_tbl set activestatus='DONE' where mobilenumber='".$mobilenumber."' and otp='".$_POST['otp']."' and activestatus='PENDING'");			
$supervisorid	=	$supervisorid;
			}
			if($i==0)
			{
				$response=array("code"=>'100',"msg"=>'Mobile number and otp is not matched. Please check and try again.');
				$response=json_encode($response);
				echo $response;
				exit();
				die();			
			}
			else
			{
				//require("../sms/sendsms.php");							
				$message	=	"Your Password has been updated successfully.\n Thank You";
				//SendSms($_POST['mobilenumber'],$message);
				$response=array("code"=>'300',"msg"=>"Password changed sucessfully.");
				$response=json_encode($response);
				echo $response;
				exit();
				die();			
			}
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
