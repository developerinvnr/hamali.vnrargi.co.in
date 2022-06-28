<?php
@session_start();
$t=1;
date_default_timezone_set('Asia/Calcutta');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
require("../validation/validation.php");
include("../enc/urlenc.php");
require('../db/db_connect.php');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$dd 	     	    = Date("d");
$mm				    = Date("m");
$yy				    = Date("Y");
$currentDate	    = $yy."-".$mm."-".$dd;
/*
$_POST['mobilenumber']="9926886681";
$_POST['password']	=	"123456";
*/
//$_SERVER['REQUEST_METHOD']="POST";
//$_POST['authcode']	=	"CPL12345";
if($_SERVER['REQUEST_METHOD']=="POST")
{
	$flag	=	0;
	$msgErr	=	"";
	if(empty($_POST['authcode']))
	{
		$flag++;
		$msgErr.=	"Please provide authentication code";		
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
		$i=0;
		$rs_sel	=	$dbconnection->firequery("select * from auth_tbl where authenticationcode='".$_POST['authcode']."'");
		while($row=mysqli_fetch_assoc($rs_sel))
		{
			$i++;
			$response=array("code"=>'300',"msg"=>'DEAR USER, WELCOME TO CPL TEST APP');
			$response=json_encode($response);
			echo $response;
			exit();
			die();			
		}
		if($i==0)
		{
			$response=array("code"=>'100',"msg"=>'Authentication code is invalid. Please check and try again');
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
?>