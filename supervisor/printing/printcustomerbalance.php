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
$frmdate	=	$_REQUEST['frmdate'];
$todate		=	$_REQUEST['todate'];
$frmdate	=	$_REQUEST['frmdate'];
$todate		=	$_REQUEST['todate'];
?>
<html>
<head>
<title>PRINT CUSTOMER BALANCE</title>
<link rel="stylesheet" href="../css/font-awesome.css">
</head>
<center>
<body>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<thead>
<tr><td colspan="9" align="center"><b>TRF BALANCE LIST OF CUSTOMERS FOR <?php echo $dbconnection->getField("center_tbl","centername","centerid=".$_REQUEST['centerid']."");?> COLLECTION CENTER</b></td></tr>
<tr>
	<th style="padding:3px;">Sno</th>
	<th style="padding:3px;">Center Name</th>
	<th style="padding:3px;">TRF By</th>					
	<th style="padding:3px;">Customer Detail</th>
	<th style="padding:3px;">Test Detail</th>
	<th style="padding:3px;">Total Amount</th>					
	<th style="padding:3px;">After Discount</th>										
	<th style="padding:3px;">Paid</th>										
	<th style="padding:3px;">Balance</th>
</tr>
</thead>
<?php

$query	=	$dbconnection->firequery("select a.*,b.centername,b.contactnumber,c.staffname,c.mobilenumber as stmobile from customertest_tbl a left join center_tbl b on b.centerid=a.centerid left join staff_tbl c on c.staffid=a.staffid where a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and a.centerid=".$_REQUEST['centerid']." and balance>0 and date(a.creationdate) between '".$frmdate."' and '".$todate."'");

$i=0;
$balance	=	0;
$total			=	0;
$totaldiscount	=	0;
$totalpaid	=	0;
$totalbalance	=	0;
while($row=mysqli_fetch_assoc($query))
{
$i++;
$j++;
$cols=0;
?>
<tr>
	<td valign="top" style="text-align:center;"><?php echo $i; $cols++;?></td>
	<td valign="top"><?php echo ucwords($row['centername']);?><br /><?php echo $row['contactnumber'];?></td>
	<td valign="top"><?php echo ucwords($row['staffname']);?><br /><?php echo $row['stmobile'];?></td>	
	<td valign="top"><?php echo $row['customername']."<br>".$row['mobilenumber']."<br>Age : ".$row['age']."<br>Gender : ".$row['gender'];?></td>
	<td valign="top">
	<?php
	$rs_det	=	$dbconnection->firequery("select b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.customerid=".$row['customerid']."");
	while($roo=mysqli_fetch_assoc($rs_det))
	{
		echo $roo['testname']."<br>";
	}
	?>
	</td>
	<td valign="top" style="text-align:center;"><i class="fa fa-inr"></i> <?php echo ceil($row['totalamount']);?></td>
	<td valign="top" style="text-align:center;"><i class="fa fa-inr"></i> <?php echo ceil($row['afterdiscount']);?></td>
	<td valign="top" style="text-align:center;"><i class="fa fa-inr"></i> <?php echo ceil($row['paid']);?></td>
	<td valign="top" style="text-align:center;"><i class="fa fa-inr"></i> <?php echo ceil($row['balance']);?></td>			
</tr>
<?php
$total			=	$total+ceil($row['totalamount']);
$totaldiscount	=	$totaldiscount+ceil($row['afterdiscount']);
$totalpaid		=	$totalpaid+ceil($row['paid']);
$balance		=	$balance+ceil($row['balance']);
}
if($i>0)
{
?>
<tr>
	<td colspan="9" style="text-align:center; padding:3px;">
	<label style="font-size:16px; font-weight:bold;">TOTAL CUSTOMER BALANCE ON MY TRF IS = <i class="fa fa-inr"></i> <?php echo ceil($balance);?></label>
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

