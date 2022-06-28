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
?>
<style>
td{
	border:1px solid #777;
}
</style>
<?php
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
$ind		=	$_POST['ind'];
$locationid	=	$_POST['locationid'];
$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));
	?>
<table style="width:100%; border:1px solid #777;" border="1">
<tr style="background-color:#008C40; color:white;">
	<td colspan="4" align="center">WORK CODE WISE</td>
</tr>
<tr style="background-color:#008C40; color:white;">
	<td align="center">S.No.</td>
	<td align="center">Work Code</td>
	<td align="center">Total Quantity</td>
	<td align="center">Total Amount</td>
</tr>
<?php
$i=0;
$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid=".$locationid." order by locationname");
while($row=mysqli_fetch_assoc($rs_sel))
{

$totalqty	=	0;
$totalamt	=	0;
$rs_det	=	$dbconnection->firequery("select distinct(workcode),sum(quantity) as quantity,sum(total) as total from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where b.locationname=".$row['locationid']." and date(a.creationdate) between '".$frmdate."' and '".$todate."' group by a.workcode order by a.workcode");
while($det=mysqli_fetch_assoc($rs_det))
{

$i++;
?>
<tr>
	<td align="center"><?php echo $i;?></td>
	<td align="center"><?php echo $det['workcode'];?></td>
	<td align="center"><?php echo $det['quantity'];?></td>
	<td align="center"><?php echo $det['total'];?></td>
</tr>
<?php
$totalqty=$totalqty+$det['quantity'];
$totalamt=$totalamt+$det['total'];
}
if($i>0)
{
?>
<tr style="background-color:#008C40; color:white;">
	<td colspan="2" align="right"><b>Total</b></td>
	<td align="center"><b><?php echo $totalqty;?></b></td>
	<td align="center"><b><i class="fa fa-inr"></i> <?php echo $totalamt;?></b></td>
</tr>
<?php
}
else
{
?>
<tr>
	<td colspan="4" align="center">--NO RECORD FOUND--</td>
</tr>
<?php
}
?>
<?php
}
?>
</table>
<?php
}
?>
