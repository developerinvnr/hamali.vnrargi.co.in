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
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
	$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));
	$location	=	$_POST['location'];
	$supervisor	=	$_POST['supervisor'];
	$hamali		=	$_POST['hamali'];
	?>
	<table style="width:100%; border:1px solid #777; border-collapse:collapse;">
	<tr style="background-color:#008C40; color:white;">
		<td align="center" style="width:25px;">S.No.</td>
		<td align="left">Location Name</td>
		<td align="left">Hamali Group</td>	
		<td align="left">Supervisor Name</td>	
		<td align="center">Work Code</td>
		<td align="center">Rate</td>
		<td align="center">Quantity</td>
		<td align="center">Total</td>
	</tr>
	<?php
	
	$qry	=	array();
	if($location!="")
	{
		$qry[count($qry)]="a.location=".$location."";
	}
	else
	{
		$qry[count($qry)]="a.location in (".$_SESSION['datadetail'][0]['loca'].")";
	}
	if($supervisor!="")
	{
		$qry[count($qry)]="a.supervisorid=".$supervisor."";
	}
	if($hamali!="")
	{
		$qry[count($qry)]="a.groupnumber=".$hamali."";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query	= " and ".$str."";	
	}
	$rs_workcd	=	$dbconnection->firequery("select a.workcode,a.narration,sum(a.quantity) as qty,sum(a.total) as totalamt,a.rate,b.groupname,c.firstname,c.lastname,d.locationname from workslip_detail a left join hamaligroup_tbl b on b.hgid=a.groupnumber left join supervisor_tbl c on c.supervisorid=a.supervisorid left join location_tbl d on d.locationid=a.location where date(a.creationdate) between '".$frmdate."' and '".$todate."' ".$query." group by a.workcode,a.location,a.supervisorid,a.groupnumber order by d.locationname,b.groupname,c.firstname,a.workcode");
	$i=0;
	$tqty	=	0;
	$tamt	=	0;
	while($ro=mysqli_fetch_assoc($rs_workcd))
	{
	$i++;
	?>
	<tr>
		<td align="center"><?php echo $i;?></td>
		<td align="left"><?php echo $ro['locationname'];?></td>
		<td align="left"><?php echo $ro['groupname'];?></td>
		<td align="left"><?php echo $ro['firstname']." ".$ro['lastname'];?></td>
		<td align="center"><?php echo $ro['workcode'];?></td>
		<td align="center"><?php echo number_format($ro['rate'],'2','.','');?></td>
		<td align="center"><?php echo $ro['qty'];?></td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo number_format($ro['totalamt'],'2','.','');?></td>
	</tr>
	<?php
	$tamt	=	$tamt+number_format($ro['totalamt'],'2','.','');
	$tqty	=	$tqty+$ro['qty'];
	}
	?>
	<tr style="background-color:#008C40; color:white;">
		<td colspan="6" align="right"><b>Total</b></td>
		<td align="center"><?php echo $tqty;?></td>
		<td align="center"><i class="fa fa-inr text-white"></i> <?php echo number_format($tamt,'2','.','');?></td>	
	</tr>
	</table>
	<?php		

}
?>
