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

?>
<html>
<head>
<title>PRINT RECEIVING LIST</title>
<link rel="stylesheet" href="../../css/font-awesome.css">
</head>
<center>
<body>
<h4>BALANCE PAYMENT LIST TO BE COLLECTED FROM STAFF OF <?php echo $_SESSION['centerdetail'][0]['authname'];?> COLLECTION CENTER</h4>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<thead>
	<tr>
		<th style="padding:3px; text-align:center;">Sno</th>
		<th style="padding:3px;">Center Name</th>
		<th style="padding:3px;">Staff Name</th>
		<th style="padding:3px;">Mobile Number</th>
		<th style="padding:3px;">Total Received From Customer</th>					
		<th style="padding:3px;">Total Paid To Center</th>
		<th style="padding:3px;">Balance Payment</th>					
	</tr>
</thead>
<tbody>
<?php
$rs_sel	=	$dbconnection->firequery("select * from staff_tbl where FIND_IN_SET(".$_SESSION['centerdetail'][0]['sessionid'].",collectioncenter)>0");	
$i=0;
while($row=mysqli_fetch_assoc($rs_sel))
{

	$i++;
	$received	=	0;
	$paid		=	0;
	$balance	=	0;
	?>
	<tr>
		<td align="center" style="padding:3px;"><?php echo $i;?></td>
		<td style="padding:3px;"><?php echo $_SESSION['centerdetail'][0]['authname'];?></td>		
		<td style="padding:3px;"><?php echo $row['staffname'];?></td>		
		<td style="padding:3px;"><?php echo $row['mobilenumber'];?></td>
		<td style="padding:3px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $received	=	doubleval($dbconnection->getField("staffbalance_view","received","staffid=".$row['staffid']." and centerid=".$_SESSION['centerdetail'][0]['sessionid'].""));?></td>		
		<td style="padding:3px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $paid	=	doubleval($dbconnection->getField("staffbalance_view","paid","staffid=".$row['staffid']." and centerid=".$_SESSION['centerdetail'][0]['sessionid'].""));?></td>	
		<td style="font-size:16px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $balance	=	$received-$paid;?></td>	
	</tr>
	<?php
	$totalbalance	=	$totalbalance+$balance;
	$totalreceived	=	$totalreceived+$received;	
	$totalpaid		=	$totalpaid+$$paid;

}
?>
<tr style="font-size:18px;">
	<td style="padding:3px;" colspan="4" align="right";><b>Total</b></td>
	<td style="padding:3px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $totalreceived;?></td>
	<td style="padding:3px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $totalpaid;?></td>		
	<td style="padding:3px; text-align:center;" style="padding:3px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $totalbalance;?></td>	
	<td></td>	
</tr>

</tbody>
</table>
</body>
</center>
</html>

