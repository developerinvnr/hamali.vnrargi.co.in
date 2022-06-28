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
if($_SERVER['REQUEST_METHOD']=="POST")
{
	$flag		=	0;
	$msgErr		=	"";
	$records	=	json_decode($_POST['allWorklistlist'],true);
	$counter	=	0;
	$total		=	0;
	$rateid		=	0;
	$cnt		=	count($records);
	$dids		=	array();
	for($i=0;$i<count($records);$i++)
	{
		if($i==0)
		{
			$rateid	=	$dbconnection->getField("hamaligroup_tbl","ratelistname","hgid=".$records[$i]['hgid']."");
		}
		$rate		=	$dbconnection->getField("rate_list","price","rateid=".$rateid." and workcode=".$records[$i]['workcode']."");
		$total		=	$records[$i]['qty']*$rate;
		$remark1	=	$records[$i]['remark1'];
		$remark2	=	$records[$i]['remark2'];
		if($remark1=="---Material---" || $remark1=="")
		{
			$remark1	=	"";
		}
		if($remark2=="-Product/Method-" || $remark2=="")
		{
			$remark2	=	"";
		}
		$slipid			=	trim($records[$i]['workslipno']);
		$workslipdate	=	date('Y\-m\-d H:i:s',strtotime($records[$i]['startdatetime']));
		//$workslipdate	=	$records[$i]['startdatetime'];
		$groupnumber	=	trim($records[$i]['hgid']);
		$groupname		=	$dbconnection->getField("hamaligroup_tbl","groupname","hgid=".$groupnumber."");
		$remark			=	$records[$i]['remark'];
		$supervisorid	=	trim($records[$i]['supervisorid']);
		$total			=	$total+doubleval(trim($records[$i]['qty']))*rate;

		if($dbconnection->firequery("insert into offlineworkslipdetail(recid,workslipid,workcode,narration,quantity,rate,total,rem1,rem2,creationdate,supervisorid,remark,workslipdate,groupnumber,groupname,recorddatetime) values(".$records[$i]['id'].",".$records[$i]['workslipno'].",".$records[$i]['workcode'].",'".$records[$i]['narration']."',".$records[$i]['qty'].",".$rate.",".$total.",'".$remark1."','".$remark2."','".date('Y\-m\-d H:i:s')."',".$records[$i]['supervisorid'].",'".$remark."','".$workslipdate."',".intval($groupnumber).",'".$groupname."','".date('Y\-m\-d H:i:s',strtotime($records[$i]['workcodedatetime']))."')"))
		{
			$dids[]	=	$dbconnection->getField("offlineworkslipdetail","detailid","workslipid=".intval($records[$i]['workslipno'])." and workcode=".intval($records[$i]['workcode'])." and supervisorid=".intval($supervisorid)." and groupnumber=".intval($groupnumber)." and processstatus=0");
			$counter++;
		}
	}	

$rs_sel	=	$dbconnection->firequery("SELECT workslipid,supervisorid,groupnumber,groupname,remark,workslipdate FROM offlineworkslipdetail where processstatus=0 group by workslipid,groupnumber,supervisorid");
while($row=mysqli_fetch_assoc($rs_sel))
{
	$lastreference	=	intval($dbconnection->getField("workslipreference_tbl","count(*)","creationdate='".date('Y\-m\-d')."'"))+1;
	$lastreference	=	ltrim($lastreference,"0");
	$lastreference	=	date("d")."/".$lastreference;
	$etn			=	date('m');
	$slipno			=	"WRK/".date('Y')."".$etn."".$lastreference;
	$depid			=	$dbconnection->getField("supervisor_tbl","departmentname","supervisorid=".$row['supervisorid']."");
	$locid			=	$dbconnection->getField("supervisor_tbl","locationname","supervisorid=".$row['supervisorid']."");


	if($dbconnection->firequery("insert into workslip_tbl(workslipnumber,workslipdate,groupnumber,groupname,remark,workslipamount,creationdate,supervisorid,location,department) values('".$slipno."','".date('Y\-m\-d H:i:s',strtotime($row['workslipdate']))."','".$row['groupnumber']."','".$row['groupname']."','".$_POST['remark']."',0,'".date('Y\-m\-d H:i:s')."',".$row['supervisorid'].",".$locid.",".$depid.")"))
	{
		$total	=	0;
		$slid	=	$dbconnection->last_inserted_id();
		$rs_det	=	$dbconnection->firequery("select * from offlineworkslipdetail where workslipid=".$row['workslipid']." and supervisorid=".$row['supervisorid']." and groupnumber=".$row['groupnumber']." and processstatus=0");
		while($det=mysqli_fetch_assoc($rs_det))
		{
			$total	=	$total+$det['total'];
			$dbconnection->firequery("insert into workslip_detail(workslipid,workcode,narration,quantity,rate,total,creationdate,supervisorid,rem1,rem2,location,department,groupnumber) values(".$slid.",'".$det['workcode']."','".$det['narration']."',".doubleval($det['quantity']).",".doubleval($det['rate']).",".doubleval($det['total']).",'".date('Y\-m\-d H:i:s',strtotime($det['recorddatetime']))."',".$det['supervisorid'].",'".$det['rem1']."','".$det['rem2']."',".$locid.",".$depid.",".intval($row['groupnumber']).")");				
		}

		$locname	=	strtoupper(substr($dbconnection->getField("location_tbl","locationname","locationid=".$locid.""),0,1));
		$ind		=	intval($dbconnection->getField("location_tbl","workslip","locationid=".$locid.""));
		$fyear		=	$dbconnection->GetFinancialYear();
		$slipnumber	=	$locname."/WRK/".$fyear."/".$ind;
		$dbconnection->firequery("update location_tbl set workslip=workslip+1 where locationid=".$locid."");
		$dbconnection->firequery("update workslip_tbl set workslipnumber='".$slipnumber."',workslipamount=".doubleval($total)." where workslipid=".$slid."");

		$dbconnection->firequery("insert into workslipreference_tbl(creationdate) values('".date('Y\-m\-d')."')");				
	}
	//$dbconnection->firequery("delete from offlineworkslipdetail where workslipid=".$row['workslipid']." and supervisorid=".$row['supervisorid']." and groupnumber=".$row['groupnumber']."");
	$dbconnection->firequery("update offlineworkslipdetail set processstatus=1 where workslipid=".$row['workslipid']." and supervisorid=".$row['supervisorid']." and groupnumber=".$row['groupnumber']."");
}
$response=array("code"=>'300',"msg"=>'Work Slip Added Successfully',"completestatus"=>0);
$response=json_encode($response);
echo $response;
exit();
die();
}
?>
