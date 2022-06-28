<?php
@session_start();
date_default_timezone_set('Asia/Calcutta');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
date_default_timezone_set('Asia/Calcutta');
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{

	$supervisorname	=	$_REQUEST['supervisorid'];
	$centername		=	$_REQUEST['centerid'];
	$staffname		=	$_REQUEST['staffid'];

	$query	=	"select a.*,b.supervisorname,b.mobilenumber,c.centername,c.contactnumber,d.staffname,d.mobilenumber as staffmobile from centerreceiving_tbl a inner join supervisor_tbl b on b.supervisorid=a.supervisorid inner join center_tbl c on c.centerid=a.centerid inner join staff_tbl d on d.staffid=a.staffid where franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and date(a.receivingdate) between '".date('Y\-m\-d',strtotime($_REQUEST['frmdate']))."' and '".date('Y\-m\-d',strtotime($_REQUEST['todate']))."' ";
	
	$qry	=	array();
	
	if($supervisorname!="")
	{
		$qry[count($qry)]	=	"a.supervisorid=".$supervisorname."";
	}	
	if($centername!="")
	{
		$qry[count($qry)]	=	"a.centerid=".$centername."";
	}	
	if($staffname!="")
	{
		$qry[count($qry)]	=	"a.staffid=".$staffname."";
	}	

	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.=	" and ".$str."";		
	}
	$rs_sel	=	$dbconnection->firequery($query);
	$i=0;
	$total	=	0;
	?>
	<html>
	<title>RECEIVING LIST</title>
	<body>
	<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
	<?php	
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	if($i==1)
	{
	?>
	<thead>
	<tr><td colspan="6" align="center"><b>AMOUNT RECEIVED LIST BY SUPERVISOR FROM STAFF</b> <label style="float:right;">PRINTED ON : <?php echo date('d\-m\-Y');?></label></td></tr>
	<tr>
		<th style="padding:3px; text-align:center; width:50px;">Sno</th>
		<th style="padding:3px; text-align:left;" nowrap="nowrap">Received By Supervisor Name</th>
		<th style="padding:3px; text-align:left;">Center Detail</th>		
		<th style="padding:3px; text-align:left;">Staff Detail</th>
		<th style="padding:3px; text-align:center; width:200px;">Date</th>
		<th style="padding:3px; text-align:center; width:80px;">Received</th>
	</tr>
	</thead>
	<tbody>
	<?php
	}
	?>
	<tr>
		<td style="text-align:center; padding:3px; vertical-align:top;"><?php echo $i;?></td>
		<td valign="top" style="padding:3px;" nowrap="nowrap"><?php echo $row['supervisorname'];?><br /><?php echo $row['mobilenumber'];?></td>
		<td valign="top" style="padding:3px;"><?php echo $row['centername'];?><br /><?php echo $row['contactnumber'];?></td>
		<td valign="top" style="padding:3px;"><?php echo $row['staffname'];?><br /><?php echo $row['staffmobile'];?></td>
		<td style="text-align:center; vertical-align:top; padding:3px;"><?php echo date('d\-m\-Y',strtotime($row['receivingdate']));?><br /><?php echo date('h:i A',strtotime($row['receivingdate']));?></td>		
		<td style="text-align:center; vertical-align:top; padding:3px;"><i class="fa fa-inr"></i> <?php echo $row['receivingamount'];?></td>
	</tr>
	<?php
	$total	=	$total+$row['receivingamount'];
	}
	?>
	</tbody>	
	<tr style="font-size:16px;">
		<td colspan="5" align="right"><b>Total</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total;?></b></td>		
	</tr>
	<?php

}
?>
</table>
</body>
</html>