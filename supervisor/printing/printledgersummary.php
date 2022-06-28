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

$staffname	=	$_REQUEST['staffid'];
$centername	=	$_REQUEST['centerid'];
$frmdate	=	$_REQUEST['frmdate'];
$todate		=	$_REQUEST['todate'];

?>
<html>
<head>
<title>PRINT RECEIPT</title>
<link rel="stylesheet" href="../css/font-awesome.css">
</head>
<center>
<body>
<h4>Ledger Summary For <?php echo $dbconnection->getField("staff_tbl","staffname","staffid=".$staffname."");?> From <?php echo date('d\-m\-Y',strtotime($frmdate));?> To <?php echo date('d\-m\-Y',strtotime($todate));?></h4>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<?php
if($staffname!="")
{

	$i=0;	
	$k=0;
	while(strtotime($frmdate)<=strtotime($todate))
	{
	$k++;
	if($k==1)
	{
	?>
		<tr>
			<td colspan="6" style="text-align:left;">
			<?php
			$received	=	$dbconnection->getField("staffledger_view","sum(payingamount)","date(paymentdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and staffid=".$staffname." and centerid=".$centername."");
			$paid		=	$dbconnection->getField("staffledger_view","sum(receivingamount)","date(paymentdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and staffid=".$staffname." and centerid=".$centername."");			
			$closing	=	$received-$paid;
			$ldate	=	date('Y\-m\-d',strtotime($frmdate));
			$ldate	=	date('Y\-m\-d', strtotime($ldate.'-1 day'));			
			
			?>
			<label style="font-size:14px; font-weight:bold;">CLOSING BALANCE FOR <?php echo date('d\-m\-Y',strtotime($ldate));?> = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
			</td>
		</tr>
		<thead>
		<tr>
			<th style="padding:3px; text-align:center;">Sno</th>
			<th style="padding:3px; text-align:center;">Date</th>
			<th style="padding:3px; text-align:center;">Received</th>
			<th style="padding:3px; text-align:center;">Paid</th>
			<th style="padding:3px; text-align:center;">Balance</th>
		</tr>
		</thead>
		<tbody>
		<?php
	}
		$rs_led	=	$dbconnection->firequery("select paid,received,paymentdate,staffid,centerid from staffledger_summary where staffid=".$staffname." and centerid=".$centername." and date(paymentdate)='".date('Y\-m\-d',strtotime($frmdate))."'");
		while($row=mysqli_fetch_assoc($rs_led))
		{
		$rec	=	0;
		$pd		=	0;
		$i++;
		?>
		<tr>
			<td align="center"><?php echo $i;?></td>			
			<td align="center"><?php echo date('d\-m\-Y',strtotime($row['paymentdate']));?></td>
			<td align="center"><i class="fa fa-inr"></i> <?php echo $rec	=	doubleval($row['received']); ?></td>
			<td align="center"><i class="fa fa-inr"></i> <?php echo $pd	=	doubleval($row['paid']); ?></td>
			<td align="center"><i class="fa fa-inr"></i> <?php echo $closing	=	$closing+$rec-$pd;?></td>		
		</tr>
		<?php
		}
		$frmdate	=	date('Y\-m\-d',strtotime($frmdate));
		$frmdate	=	date('Y\-m\-d', strtotime($frmdate.'+1 day'));			
	}
	if($i>0)
	{
	?>
	<tr>
		<td colspan="6" style="text-align:left;">
		<label style="font-size:14px; font-weight:bold;">AS PER SELECTED DATE RANGE CLOSING BALANCE ON <?php echo date('d\-m\-Y',strtotime($todate));?> IS = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
		</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td colspan="6" style="text-align:right;">
		<?php
		$received	=	$dbconnection->getField("staffledger_view","sum(payingamount)","date(paymentdate)>'".date('Y\-m\-d',strtotime($todate))."' and staffid=".$staffname." and centerid=".$centername."");
		$paid		=	$dbconnection->getField("staffledger_view","sum(receivingamount)","date(paymentdate)>'".date('Y\-m\-d',strtotime($todate))."' and staffid=".$staffname." and centerid=".$centername."");			
		$closing	=	$closing+$received-$paid;
		?>
		<label style="font-size:16px; font-weight:bold;">FINAL CLOSING BALANCE TO RECEIVE FROM [<?php echo strtoupper($dbconnection->getField("staff_tbl","staffname","staffid=".$staffname.""));?>] = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
		</td>
	</tr>
	<?php
}
?>
</tbody>
</table>
</body>
</center>
</html>

