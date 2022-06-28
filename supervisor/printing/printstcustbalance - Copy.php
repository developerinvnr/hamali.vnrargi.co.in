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
<title>STAFF CUSTOMER BALANCE LIST</title>
<link rel="stylesheet" href="../../css/font-awesome.css">
</head>
<center>
<body>

<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<thead>
	<tr>
		<th style="padding:3px; text-align:center; width:50px;">Sno</th>
		<th style="padding:3px; text-align:left;">Staff Name</th>
		<th style="padding:3px; text-align:left;">Center Name</th>
		<th style="padding:3px; text-align:center; width:150px;">Customer Balance</th>
	</tr>
</thead>
<tbody>
<?php
	$staffname		=	$_REQUEST['staffid'];

	if($staffname!="")
	{
		$query	=	"select b.centername,b.contactnumber,a.balance as balance,c.staffname,c.mobilenumber from staffcustomer_balance a left join center_tbl b on b.centerid=a.centerid left join staff_tbl c on c.staffid=a.staffid where a.staffid=".$staffname." and c.franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." and a.balance>0 order by c.staffname";
	}
	else
	{
		$query	=	"select b.centername,b.contactnumber,a.balance as balance,c.staffname,c.mobilenumber from staffcustomer_balance a left join center_tbl b on b.centerid=a.centerid left join staff_tbl c on c.staffid=a.staffid where a.balance>0 and c.franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by c.staffname";
	}
	
	$rs_sel	=	$dbconnection->firequery($query);
$i=0;
while($row=mysqli_fetch_assoc($rs_sel))
{
	$i++;
	?>
	<tr>
		<td style="text-align:center;"><?php echo $i;?></td>
		<td><?php echo ucwords($row['staffname']);?><br /><?php echo $row['mobilenumber'];?></td>
		<td><?php echo ucwords($row['centername']);?><br /><?php echo $row['contactnumber'];?></td>
		<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo ceil($row['balance']);?></td>
	</tr>
	<?php
	$total	=	$total+ceil($row['balance']);
	
}
?>
	<tr style="font-size:16px;">
		<td colspan="3" align="right"><b>Total Customer Balance</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total;?></b></td>		
	</tr>
</tbody>
</table>
</body>
</center>
</html>

