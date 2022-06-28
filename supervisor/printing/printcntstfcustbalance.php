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
<title>CENTER CUSTOMER BALANCE LIST</title>
<link rel="stylesheet" href="../../css/font-awesome.css">
</head>
<center>
<body>

<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<tbody>
<?php
	$cntname		=	$_POST['centerid'];
	
	$totalcustomerbalance	=	0;
	if($cntname=="")
	{
		$rs_sel	=	$dbconnection->firequery("select distinct(a.centerid),b.centername,b.contactnumber from staffcustomer_balance a inner join center_tbl b on b.centerid=a.centerid where a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." order by b.centername");
	}
	else
	{
		$rs_sel	=	$dbconnection->firequery("select distinct(a.centerid),b.centername,b.contactnumber from staffcustomer_balance a inner join center_tbl b on b.centerid=a.centerid where a.centerid=".$cntname." and a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." order by b.centername");	
	}
	
	$i=0;
	while($cnt=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	$rs_bal	=	$dbconnection->firequery("select a.*,b.staffname,b.mobilenumber from staffcustomer_balance a inner join staff_tbl b on b.staffid=a.staffid where a.centerid=".$cnt['centerid']." and a.balance>0 and a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']."");
	$k=0;
	$sttotal	=	0;
	$records	=	$dbconnection->num_rows($rs_bal);
	if($records>0)
	{
	while($row=mysqli_fetch_assoc($rs_bal))
	{
		$k++;
		if($k==1)
		{
		?>
	<tr>
		<td colspan="3" style="text-align:center; color:#000; font-size:14px; font-weight:bold;"><?php echo $cnt['centername'];?> [<?php echo $cnt['contactnumber'];?>]</td>
	</tr>
		<tr>
			<th style="padding:3px; text-align:center; width:50px;">Sno</th>
			<th style="padding:3px; text-align:left;">Staff Name</th>
			<th style="padding:3px; text-align:center; width:200px;">Customer Balance</th>
		</tr>
		<?php
		}
		?>
		<tr>
			<td style="text-align:center; vertical-align:top;"><?php echo $k;?></td>
			<td><?php echo ucwords($row['staffname']);?><br /><?php echo $row['mobilenumber'];?></td>
			<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo ceil($row['balance']);?></td>
		</tr>		
		<?php
		$sttotal	=	$sttotal+ceil($row['balance']);
		$totalcustomerbalance=$totalcustomerbalance+ceil($row['balance']);
	}
	?>
	<tr style="color:#000; font-size:16px; font-weight:bold; border:7px solid #999999; border-left-style:none; border-right-style:none; border-top-style:none;"><td colspan="2" align="right">Total&nbsp;</td><td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $sttotal;?></td></tr>
	<?php
	}
	}
	
	
	?>
	<tr style="font-size:16px;">
		<td colspan="3" align="center"><b>Total Customer Balance</b>&nbsp;&nbsp;<i class="fa fa-inr"></i> <b><?php echo $totalcustomerbalance;?></b></td>
	</tr>
</tbody>
</table>
</body>
</center>
</html>

