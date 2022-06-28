<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
	$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));
	$location	=	$_POST['location'];
	$supervisor	=	$_POST['supervisor'];
	$hamali		=	$_POST['hamali'];
	if($location!="" && $hamali=="" && $supervisor=="")
	{
$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid=".$location."");
?>
<table style="width:100%; border:1px solid #777; border-collapse:collapse;">
<tr style="background-color:#008C40; color:white;">
	<td align="center" style="width:25px;">S.No.</td>
	<td align="left">Location Name</td>
	<td align="center">Work Code</td>
	<td align="center">Rate</td>
	<td align="center">Quantity</td>
	<td align="center">Total</td>
</tr>
<?php
while($row=mysqli_fetch_assoc($rs_sel))
{
$rs_workcd	=	$dbconnection->firequery("select workcode,narration,sum(quantity) as qty,sum(total) as totalamt,rate from workslip_detail where location=".$row['locationid']." and date(creationdate) between '".$frmdate."' and '".$todate."' group by location,workcode,rate order by workcode");
$i=0;
$tqty	=	0;
$tamt	=	0;
while($ro=mysqli_fetch_assoc($rs_workcd))
{
$i++;
?>
<tr>
	<td align="center"><?php echo $i;?></td>
	<td align="left"><?php echo $row['locationname'];?></td>
	<td align="center"><?php echo $ro['workcode'];?></td>
	<td align="center"><?php echo $ro['rate'];?></td>
	<td align="center"><?php echo $ro['qty'];?></td>
	<td align="center"><i class="fa fa-inr"></i> <?php echo $ro['totalamt'];?></td>
</tr>
<?php
$tamt	=	$tamt+$ro['totalamt'];
$tqty	=	$tqty+$ro['qty'];
}
?>
<tr style="background-color:#008C40; color:white;">
	<td colspan="4" align="right"><b>Total</b></td>
	<td align="center"><?php echo $tqty;?></td>
	<td align="center"><i class="fa fa-inr"></i> <?php echo $tamt;?></td>	
</tr>
<?php
}
?>
</table>
<?php
	}
	else if ($location!="" && $hamali!="" && $supervisor=="")
	{
$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid=".$location."");
?>
<table style="width:100%; border:1px solid #777; border-collapse:collapse;">
<tr style="background-color:#008C40; color:white;">
	<td align="center" style="width:25px;">S.No.</td>
	<td align="left">Location Name</td>
	<td align="left">Hamali Group</td>	
	<td align="center">Work Code</td>
	<td align="center">Rate</td>
	<td align="center">Quantity</td>
	<td align="center">Total</td>
</tr>
<?php
while($row=mysqli_fetch_assoc($rs_sel))
{
$rs_workcd	=	$dbconnection->firequery("select a.workcode,a.narration,sum(a.quantity) as qty,sum(a.total) as totalamt,a.rate,b.groupname from workslip_detail a left join hamaligroup_tbl b on b.hgid=a.groupnumber where a.location=".$row['locationid']." and a.groupnumber=".$hamali." and date(a.creationdate) between '".$frmdate."' and '".$todate."' group by a.location,a.groupnumber,a.workcode,a.rate order by a.workcode");
$i=0;
$tqty	=	0;
$tamt	=	0;
while($ro=mysqli_fetch_assoc($rs_workcd))
{
$i++;
?>
<tr>
	<td align="center"><?php echo $i;?></td>
	<td align="left"><?php echo $row['locationname'];?></td>
	<td align="left"><?php echo $ro['groupname'];?></td>
	<td align="center"><?php echo $ro['workcode'];?></td>
	<td align="center"><?php echo $ro['rate'];?></td>
	<td align="center"><?php echo $ro['qty'];?></td>
	<td align="center"><i class="fa fa-inr"></i> <?php echo $ro['totalamt'];?></td>
</tr>
<?php
$tamt	=	$tamt+$ro['totalamt'];
$tqty	=	$tqty+$ro['qty'];
}
?>
<tr style="background-color:#008C40; color:white;">
	<td colspan="5" align="right"><b>Total</b></td>
	<td align="center"><?php echo $tqty;?></td>
	<td align="center"><i class="fa fa-inr"></i> <?php echo $tamt;?></td>	
</tr>
<?php
}
?>
</table>
<?php
		
	}
	else if ($location!="" && $hamali!="" && $supervisor!="")
	{
$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid=".$location."");
?>
<table style="width:100%; border:1px solid #777; border-collapse:collapse;">
<tr style="background-color:#008C40; color:white;">
	<td align="center" style="width:25px;">S.No.</td>
	<td align="left">Location Name</td>
	<td align="left">Hamali Group</td>	
	<td align="left">Supervisor Name</td>	
	<td align="center">Work Code</td>
	<td align="center">Rate</td>
	<td align="center">Quantity</td>
	<td align="center">Total</td>
</tr>
<?php
while($row=mysqli_fetch_assoc($rs_sel))
{
$rs_workcd	=	$dbconnection->firequery("select a.workcode,a.narration,sum(a.quantity) as qty,sum(a.total) as totalamt,a.rate,b.groupname,c.firstname,c.lastname from workslip_detail a left join hamaligroup_tbl b on b.hgid=a.groupnumber left join supervisor_tbl c on c.supervisorid=a.supervisorid where a.location=".$row['locationid']." and a.groupnumber=".$hamali." and a.supervisorid=".$supervisor." and date(a.creationdate) between '".$frmdate."' and '".$todate."' group by a.location,a.groupnumber,a.supervisorid,a.workcode,a.rate order by a.workcode");
$i=0;
$tqty	=	0;
$tamt	=	0;
while($ro=mysqli_fetch_assoc($rs_workcd))
{
$i++;
?>
<tr>
	<td align="center"><?php echo $i;?></td>
	<td align="left"><?php echo $row['locationname'];?></td>
	<td align="left"><?php echo $ro['groupname'];?></td>
	<td align="left"><?php echo $ro['firstname'];?> <?php echo $ro['lastname'];?></td>
	<td align="center"><?php echo $ro['workcode'];?></td>
	<td align="center"><?php echo $ro['rate'];?></td>
	<td align="center"><?php echo $ro['qty'];?></td>
	<td align="center"><i class="fa fa-inr"></i> <?php echo $ro['totalamt'];?></td>
</tr>
<?php
$tamt	=	$tamt+$ro['totalamt'];
$tqty	=	$tqty+$ro['qty'];
}
?>
<tr style="background-color:#008C40; color:white;">
	<td colspan="6" align="right"><b>Total</b></td>
	<td align="center"><?php echo $tqty;?></td>
	<td align="center"><i class="fa fa-inr text-white"></i> <?php echo $tamt;?></td>	
</tr>
<?php
}
?>
</table>
<?php		
	}
	else
	{
	?>
	<table style="width:100%; border:1px solid #777; border-collapse:collapse;">
		<tr><td align="center">-- NO RECORD FOUND--</td></tr>
	</table>
	<?php
	}

	if($location=="")
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl order by locationname");
	else
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid=".$location."");
while($row=mysqli_fetch_assoc($rs_sel))
{
?>
<table style="width:100%; border:1px solid #777; border-collapse:collapse;">
<tr style="background-color:#008C40; color:white;"><td style="padding:5px; font-size:16px;" colspan="4">LOCATION : <?php echo strtoupper($row['locationname']);?></td></tr>
<?php
if($hamali=="")
$rs_hamali	=	$dbconnection->firequery("select * from hamaligroup_tbl where locationname=".$row['locationid']." order by groupname");
else
$rs_hamali	=	$dbconnection->firequery("select * from hamaligroup_tbl where locationname=".$row['locationid']." and hgid=".$hamali."");
while($ham=mysqli_fetch_assoc($rs_hamali))
{
?>
<?php
if($supervisor=="")
$rs_dist	=	$dbconnection->firequery("select distinct(a.supervisorid),b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.groupnumber=".$ham['hgid']." and a.location=".$row['locationid']." and date(a.creationdate) between '".$frmdate."' and '".$todate."'");
else
$rs_dist	=	$dbconnection->firequery("select distinct(a.supervisorid),b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.groupnumber=".$ham['hgid']." and a.location=".$row['locationid']." and a.supervisorid=".$supervisor." and date(a.creationdate) between '".$frmdate."' and '".$todate."'");

$j=0;
while($sup=mysqli_fetch_assoc($rs_dist))
{
$j++;
if($j==1)
{
?>
<tr style="background-color:#008C40; color:white;"><td style="padding:5px 20px; font-size:14px;" colspan="4">HAMALI GROUP : <?php echo strtoupper($ham['groupname']);?></td></tr>
<?php
}
?>
<tr style="background-color:#008C40; color:white;"><td style="padding:5px 20px; font-size:12px; text-align:center;" colspan="4">SUPERVISOR : <?php echo strtoupper($sup['firstname']);?> <?php echo strtoupper($sup['lastname']);?></td></tr>
<?php
$rs_workcode	=	$dbconnection->firequery("select workcode,narration,sum(quantity) as qty,sum(total) as totalamt from workslip_detail where supervisorid=".$sup['supervisorid']." and groupnumber=".$ham['hgid']." and location=".$row['locationid']." and date(creationdate) between '".$frmdate."' and '".$todate."' group by workcode");
?>
<?php
$i=0;
$totalqty	=	0;
$totalamt	=	0;
while($wcd=mysqli_fetch_assoc($rs_workcode))
{
$i++;
if($i==1)
{
?>
<tr>
	<td align="center">Work Code</td>
	<td>Narration</td>
	<td align="center">Quantity</td>
	<td align="center">Total Value</td>
</tr>

<?php
}
?>
<tr>
	<td align="center"><?php echo $wcd['workcode'];?></td>
	<td><?php echo $wcd['narration'];?></td>
	<td align="center"><?php echo $wcd['qty'];?></td>
	<td align="center"><?php echo $wcd['totalamt'];?></td>
</tr>
<?php
$totalqty	=	$totalqty+$wcd['qty'];
$totalamt	=	$totalamt+$wcd['totalamt'];
}
?>
<tr style="background-color:#008C40; color:white;">
	<td style="text-align:right;" colspan="2"><b>Total</b></td>
	<td style="text-align:center;"><?php echo $totalqty;?></td>
	<td style="text-align:center;"><?php echo $totalamt;?></td>
</tr>
<?php
}
}
?>
</table>
<?php
}
}
?>
