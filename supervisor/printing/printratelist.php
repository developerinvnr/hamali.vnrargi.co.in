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
<title>PRINT RATE LIST</title>
<link rel="stylesheet" href="../css/font-awesome.css">
<style>
@media print {
  html, body {
  margin:0px;
  }
}
</style>
</head>
<center>
<body>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
	<thead>
	<tr>
		<th style="padding:5px; text-align:center; width:50px;">Sno</th>
		<th style="padding:5px; text-align:left;">Test Name</th>					
		<th style="padding:5px; text-align:center; width:150px;">MRP</th>
		<th style="padding:5px; text-align:center; width:150px;">Customer Price</th>					
		<th style="padding:5px; text-align:center; width:250px;">Your Lab To Lab Rate</th>					
	</tr>				
	</thead>
	<tbody>
	<?php
	$rs_sel	=	$dbconnection->firequery("select b.*,c.testname,c.testamount from ratemapping_tbl a left join ratelist_tbl b on b.rateid=a.rateid left join test_tbl c on c.testid=b.testid where a.centerid=".$_REQUEST['centerid']."");

	$i=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	?>
	<tr>
		<td style="text-align:center;"><?php echo $i;?></td>
		<td style="padding:5px;"><?php echo $row['testname'];?></td>
		<td style="padding:5px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $row['testamount'];?></td>		
		<td style="padding:5px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $row['customerprice'];?></td>				
		<td style="padding:5px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $row['specialdiscount'];?></td>						
	</tr>
	<?php
	}
	?>
	</tbody>
</table>
</body>
</center>
</html>

