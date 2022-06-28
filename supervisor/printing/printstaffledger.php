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
$centerid	=	$_REQUEST['centerid'];
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
<?php
if($staffname!="" && (strtotime($frmdate)<=strtotime($todate)))
{
?>
<h4>Ledger Detail For <?php echo $dbconnection->getField("staff_tbl","staffname","staffid=".$staffname."");?> From <?php echo date('d\-m\-Y',strtotime($frmdate));?> To <?php echo date('d\-m\-Y',strtotime($todate));?></h4>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<tr>
	<td colspan="6" style="text-align:left; padding:5px;">
	<?php
	$received	=	$dbconnection->getField("staffledger_view","sum(payingamount)","date(paymentdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and staffid=".$staffname." and centerid=".$centerid."");
	$paid		=	$dbconnection->getField("staffledger_view","sum(receivingamount)","date(paymentdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and staffid=".$staffname." and centerid=".$centerid."");			
	$closing	=	$received-$paid;
	$ldate	=	date('Y\-m\-d',strtotime($frmdate));
	$ldate	=	date('Y\-m\-d', strtotime($ldate.'-1 day'));			
	
	?>
	<label style="font-size:14px; font-weight:bold;">CLOSING BALANCE FOR <?php echo date('d\-m\-Y',strtotime($ldate));?> = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
	</td>
</tr>

<thead>
<tr>
	<th style="padding:5px; text-align:center;">Sno</th>
	<th style="padding:5px; text-align:left;">Particular</th>
	<th style="padding:5px;">Date</th>
	<th style="padding:5px;">Received</th>
	<th style="padding:5px;">Paid</th>
	<th style="padding:5px;">Balance</th>
</tr>
</thead>
<?php

//$rs_led	=	$dbconnection->firequery("select * from staffledger_view where staffid=".$staffname." and centerid=".$_SESSION['centerdetail'][0]['sessionid']."");
$rs_led	=	$dbconnection->firequery("select a.customerid,a.referencenumber,a.payingamount,a.receivingamount,a.paymentdate,a.staffid,a.centerid,a.franchiseid,b.customername,b.mobilenumber from staffledger_view a left join customertest_tbl b on b.customerid=a.customerid where a.staffid=".$staffname." and a.centerid=".$centerid." and date(a.paymentdate)>='".date('Y\-m\-d',strtotime($frmdate))."' and date(a.paymentdate)<='".date('Y\-m\-d',strtotime($todate))."' order by a.paymentdate");
$i=0;
while($row=mysqli_fetch_assoc($rs_led))
{
$rec	=	0;
$pd		=	0;
$i++;
?>
<tbody>
<tr>
	<td style="padding:5px;" align="center"><?php echo $i;?></td>
	<td style="padding:5px;">
	<?php
	if($row['customername']!="")
	{
	?>
	Payment received against <?php echo $row['customername'];?> [<?php echo $row['mobilenumber'];?>]<br />
	<?php
	}
	else
	{
	echo "Payment received against collection";
	}
	$rs_det	=	$dbconnection->firequery("select a.testid,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.customerid=".$row['customerid']."");
	while($ro=mysqli_fetch_assoc($rs_det))
	{
		echo $ro['testname'].",";
	}
	unset($rs_det);
	unset($ro);
	?>
	</td>
	<td style="padding:5px;"><?php echo date('d\-m\-Y, h:i A',strtotime($row['paymentdate']));?></td>
	<td style="padding:5px;"><i class="fa fa-inr"></i> <?php echo $rec	=	doubleval($row['payingamount']); ?></td>
	<td style="padding:5px;"><i class="fa fa-inr"></i> <?php echo $pd	=	doubleval($row['receivingamount']); ?></td>
	<td style="padding:5px;"><i class="fa fa-inr"></i> <?php echo $closing	=	$closing+$rec-$pd;?></td>		
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
	$received	=	$dbconnection->getField("staffledger_view","sum(payingamount)","date(paymentdate)>'".date('Y\-m\-d',strtotime($todate))."' and staffid=".$staffname." and centerid=".$centerid."");
	$paid		=	$dbconnection->getField("staffledger_view","sum(receivingamount)","date(paymentdate)>'".date('Y\-m\-d',strtotime($todate))."' and staffid=".$staffname." and centerid=".$centerid."");			
	$closing	=	$closing+$received-$paid;
	?>
	<label style="font-size:16px; font-weight:bold;">FINAL CLOSING BALANCE TO RECEIVE FROM [<?php echo strtoupper($dbconnection->getField("staff_tbl","staffname","staffid=".$staffname.""));?>] = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
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

