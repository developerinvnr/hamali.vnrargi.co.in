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

$frmdate		=	$_REQUEST['frmdate'];
$todate			=	$_REQUEST['todate'];
$centerid		=	$_REQUEST['centername'];

if($centerid=="")
{
	$query	=	"select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']."";
}
else
{
	$query	=	"select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." and centerid=".$centerid."";	
}
$rs_sel	=	$dbconnection->firequery($query);

$i=0;
$j=0;
$totalltol	=	0;
$tamount	=	0;
$tafter		=	0;
$tpaid		=	0;	
$tbal		=	0;

?>
<html>
<head>
<title>PRINT CENTER WISE LTOL</title>
<link rel="stylesheet" href="../css/font-awesome.css">
</head>
<center>
<body>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
	<thead>
	<tr><td colspan="11" align="center"><b>CENTER WISE LTOL BETWEEN <?php echo date('d\-m\-Y',strtotime($frmdate));?> To <?php echo date('d\-m\-Y',strtotime($todate));?></b><label style="float:right;">PRINTED ON : <?php echo date('d\-m\-Y');?></label></td></tr>
	<tr>
		<th style="padding:5px; text-align:center;">Sno</th>
		<th style="padding:5px;">Center Detail</th>
		<th style="padding:5px; text-align:center;">Total Business</th>
		<th style="padding:5px; text-align:center;">After Discount</th>
		<th style="padding:5px; text-align:center;">Paid</th>					
		<th style="padding:5px; text-align:center;">Balance</th>
		<th style="padding:5px; text-align:center;">LTOL</th>					
	</tr>
	</thead>
	<tbody>
	<?php
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	?>
	<td align="center" valign="top"><?php echo $i;?></td>
	<td><?php echo $row['centername'];?><br /><?php echo $row['contactnumber'];?></td>
	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($totaltest=$dbconnection->getField("center_business","sum(totalamount)","centerid=".$row['centerid']." and testdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'"));?></td>

	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $after	=	doubleval($dbconnection->getField("center_business","sum(afterdiscount)","centerid=".$row['centerid']." and testdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'"));?></td>

	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $paid	=	doubleval($dbconnection->getField("customertest_tbl","sum(paid)","centerid=".$row['centerid']." and date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'"));?></td>	

	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $balance	=	doubleval($dbconnection->getField("customertest_tbl","sum(balance)","centerid=".$row['centerid']." and date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'"));?></td>	

	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $ltol	=	doubleval($dbconnection->getField("center_business","sum(spdiscount)","centerid=".$row['centerid']." and testdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'"));?></td>	
	</tr>
	<?php
	$tamount	=	$tamount+$totaltest;
	$tafter		=	$tafter+$after;
	$tpaid		=	$tpaid+$paid;
	$tbal		=	$tbal+$balance;
	$totalltol	=	$totalltol+$ltol;
	}	
	?>
	</tbody>
	<tr>
		<td colspan="2" style="padding:2px 10px; text-align:right; font-size:18px;"><b>Total L TO L</b></td>
		<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($tamount);?></td>
		<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($tafter);?></td>	
		<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($tpaid);?></td>	
		<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($tbal);?></td>	
		<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($totalltol);?></td>
	</tr>
</table>
</body>
</center>
</html>

