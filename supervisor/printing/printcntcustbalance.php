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
$frmdate	=	$_REQUEST['frmdate'];
$todate		=	$_REQUEST['todate'];
?>
<html>
<head>
<title>CENTER CUSTOMER BALANCE LIST</title>
<link rel="stylesheet" href="../../css/font-awesome.css">
</head>
<center>
<body>

<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<thead>
	<tr>
		<th style="padding:3px; text-align:center; width:50px;">Sno</th>
		<th style="padding:3px; text-align:left;">Center Name</th>
		<th style="padding:3px; text-align:center; width:250px;">Between Date Customer Balance</th>
		<th style="padding:3px; text-align:center; width:250px;">Final Customer Balance</th>		
	</tr>
</thead>
<tbody>
<?php
	$cntname		=	$_REQUEST['cntid'];

	if($cntname!="")
	{
		$query	=	"select * from center_tbl where centerid=".$cntname." and franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by centername";
	}
	else
	{
		$query		=	"select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by centername";
	}
	
	$rs_sel	=	$dbconnection->firequery($query);
$i=0;
$total	=	0;	
$total2	=	0;	
while($row=mysqli_fetch_assoc($rs_sel))
{
	$i++;
	?>
	<tr>
		<td style="text-align:center;"><?php echo $i;?></td>
		<td><?php echo ucwords($row['centername']);?><br /><?php echo $row['contactnumber'];?></td>
		<td style="text-align:center;">
		<i class="fa fa-inr"></i> 
		<?php
		echo $as	=	ceil($dbconnection->getField("customertest_tbl","sum(balance)","centerid=".$row['centerid']." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and date(creationdate) between '".$frmdate."' and '".$todate."'"));
		?>
		</td>
		<td style="text-align:center;">
		<i class="fa fa-inr"></i> 
		<?php 
		echo $fnl	=	ceil($dbconnection->getField("customertest_tbl","sum(balance)","centerid=".$row['centerid']." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid'].""));
		?>
		</td>
	</tr>
	<?php
	$total	=	$total+ceil($as);
	$total2	=	$total2+ceil($fnl);	
}
?>
	<tr style="font-size:16px;">
		<td colspan="2" align="right"><b>Total Customer Balance</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total;?></b></td>	
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total2;?></b></td>			
	</tr>
</tbody>
</table>
</body>
</center>
</html>

