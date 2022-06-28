<?php
@session_start();
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

$t=1;
require("../../validation/validation.php");
include("../../enc/urlenc.php");
include("../../db/db_connect.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$dd 	    = Date("d");
$mm			= Date("m");
$yy			= Date("Y");
$currentDate= $yy."-".$mm."-".$dd;

$supervisorname	=	$_REQUEST['supervisorid'];
$centername		=	$_REQUEST['centerid'];
$frmdate		=	$_REQUEST['frmdate'];
$todate			=	$_REQUEST['todate'];

?>
<html>
<head>
<title>PRINT RECEIPT</title>
<link rel="stylesheet" href="../css/font-awesome.css">
</head>
<center>
<body>
<?php
if($supervisorname!="" && (strtotime($frmdate)<=strtotime($todate)))
{
if($centername=="")
{
?>
<h4>LEDGER DETAIL OF <?php echo strtoupper($dbconnection->getField("supervisor_tbl","supervisorname","supervisorid=".$supervisorname.""));?> BETWEEN <?php echo date('d\-m\-Y',strtotime($frmdate));?> TO <?php echo date('d\-m\-Y',strtotime($todate));?></h4>
<?php
}
else
{
?>
<h4>LEDGER DETAIL OF <?php echo strtoupper($dbconnection->getField("supervisor_tbl","supervisorname","supervisorid=".$supervisorname.""));?> FOR <?php echo strtoupper($dbconnection->getField("center_tbl","centername","centerid=".$centername.""));?> BETWEEN <?php echo date('d\-m\-Y',strtotime($frmdate));?> TO <?php echo date('d\-m\-Y',strtotime($todate));?></h4>
<?php
}
?>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<tr>
	<td colspan="6" style="text-align:left; padding:5px;">
		<?php
		if($centername=="")
		{
			$received	=	$dbconnection->getField("supervisor_receive_paid","sum(received)","date(receivingdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and supervisorid=".$supervisorname."");
			$paid		=	$dbconnection->getField("supervisor_receive_paid","sum(paid)","date(receivingdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and supervisorid=".$supervisorname."");			
			$closing	=	$received-$paid;
		}
		else
		{
			$received	=	$dbconnection->getField("supervisor_receive_paid","sum(received)","date(receivingdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and centerid=".$centername."");
			$paid		=	$dbconnection->getField("supervisor_receive_paid","sum(paid)","date(receivingdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and centerid=".$centername."");			
			$closing	=	$received-$paid;
		}
		$ldate	=	date('Y\-m\-d',strtotime($frmdate));
		$ldate	=	date('Y\-m\-d', strtotime($ldate.'-1 day'));			
		
		
		?>
	<label style="font-size:14px; font-weight:bold;">CLOSING BALANCE FOR <?php echo date('d\-m\-Y',strtotime($ldate));?> = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
	</td>
</tr>

<thead>
<tr>
	<th style="padding:3px; text-align:center;">Sno</th>
	<th style="padding:3px;">Particular</th>
	<th style="padding:3px;">Date</th>
	<th style="padding:3px;">Received From Staff</th>
	<th style="padding:3px;">Paid To Franchise</th>
	<th style="padding:3px;">Balance</th>
</tr>
</thead>
<?php

if($centername=="")
{
$rs_led	=	$dbconnection->firequery("select a.staffid,a.receivingamount,a.paid as pd,a.receivingdate,a.centerid,a.supervisorid from supervisor_ledger a left join staff_tbl b on b.staffid=a.staffid where date(a.receivingdate)>='".date('Y\-m\-d',strtotime($frmdate))."' and date(a.receivingdate)<='".date('Y\-m\-d',strtotime($todate))."' and a.supervisorid=".$supervisorname." order by date(a.receivingdate)");
}
else
{
$rs_led	=	$dbconnection->firequery("select a.staffid,a.receivingamount,a.paid as pd,a.receivingdate,a.centerid,a.supervisorid from supervisor_ledger a left join staff_tbl b on b.staffid=a.staffid where date(a.receivingdate)>='".date('Y\-m\-d',strtotime($frmdate))."' and date(a.receivingdate)<='".date('Y\-m\-d',strtotime($todate))."' and a.supervisorid=".$supervisorname." and a.centerid=".$centername." order by date(a.receivingdate)");
}
$i=0;
while($row=mysqli_fetch_assoc($rs_led))
{
$rec	=	0;
$pd		=	0;
$i++;
?>
<tbody>
		<tr>
			<td align="center"><?php echo $i;?></td>
			<td style="text-align:left; padding:3px;">
			<?php
			if($row['staffid']!="")
			{
				echo "Received From ".$dbconnection->getField("staff_tbl","staffname","staffid=".$row['staffid']."");
			}
			else
			{
			echo "Paid To Franchise";
			}
			?>
			</td>
			<td style="text-align:left; padding:3px;"><?php echo date('d\-m\-Y, h:i A',strtotime($row['receivingdate']));?></td>
			<td style="text-align:left; padding:3px;"><i class="fa fa-inr"></i> <?php echo $rec		=	doubleval(ceil($row['receivingamount'])); ?></td>
			<td style="text-align:left; padding:3px;"><i class="fa fa-inr"></i> <?php echo $pd		=	doubleval(ceil($row['pd'])); ?></td>
			<td style="text-align:left; padding:3px;"><i class="fa fa-inr"></i> <?php echo $closing	=	ceil($closing+$rec-$pd);?></td>		
		</tr>
<?php
}
if($i>0)
{
?>
		<tr>
			<td colspan="6" style="text-align:left; padding:3px;">
			<label style="font-size:14px; font-weight:bold;">AS PER SELECTED DATE RANGE CLOSING BALANCE ON <?php echo date('d\-m\-Y',strtotime($todate));?> IS = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
			</td>
		</tr>
<?php
}
?>
		<tr>
			<td colspan="6" style="text-align:right; padding:3px;">
			<?php
			if($centername=="")
			{
				$received	=	$dbconnection->getField("supervisor_receive_paid","sum(received)","date(receivingdate)>'".date('Y\-m\-d',strtotime($todate))."' and supervisorid=".$supervisorname."");
				$paid		=	$dbconnection->getField("supervisor_receive_paid","sum(paid)","date(receivingdate)>'".date('Y\-m\-d',strtotime($todate))."' and supervisorid=".$supervisorname."");			
				$closing	=	$closing+$received-$paid;
			}
			else
			{
				$received	=	$dbconnection->getField("supervisor_receive_paid","sum(received)","date(receivingdate)>'".date('Y\-m\-d',strtotime($todate))."' and centerid=".$centername." and supervisorid=".$supervisorname."");
				$paid		=	$dbconnection->getField("supervisor_receive_paid","sum(paid)","date(receivingdate)>'".date('Y\-m\-d',strtotime($todate))."' and centerid=".$centername." and supervisorid=".$supervisorname."");			
				$closing	=	$closing+$received-$paid;
			}
			?>
			<label style="font-size:16px; font-weight:bold;">FINAL CLOSING BALANCE FOR [<?php echo strtoupper($dbconnection->getField("supervisor_tbl","supervisorname","supervisorid=".$supervisorname.""));?>] TO GIVE FRANCHISE  = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
			</td>
		</tr>
</tbody>
</table>

<?php
}
?>
</body>
</center>
</html>

