<?php
$t=1;
@session_start();
$t=1;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
$sessionid	=	$_SESSION['supervisordetail'][0]['sessionid'];
if(!isset($_SESSION['datadetail'][0]['sessionid']))
{
	echo '<script>document.location.href="./vnr_supervisor";</script>';
}
require("../validation/validation.php");
include("../enc/urlenc.php");
require('../db/db_connect.php');
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

?>
<html>
<title>PRINT PAY SLIP</title>
<head>
<style>
td{
padding:3px;
}
</style>
</head>
<body>
<form name="pagedata" id="pagedata" method="post" action="#">
<?php
	$rs_slip	=	$dbconnection->firequery("select * from workslip_tbl where workslipid=".$_REQUEST['slipid']."");
	while($slip=mysqli_fetch_assoc($rs_slip))
	{
		$workslipnumber		=	$slip['workslipnumber'];
		$workslipdate	=	date('d\-m\-Y, h:i A',strtotime($slip['workslipdate']));		
		$groupname	=	$dbconnection->getField("hamaligroup_tbl","groupname","hgid=".$slip['groupnumber']."");
		$remark	=	$slip['remark'];
		$paymentstatus	=	$row['paymentstatus'];		
		$slipnumber	=	$slip['payslipnumber'];
		$paymentmode=	$slip['paymentmode'];

		$department	=	$dbconnection->getField("department_tbl","departmentname","departmentid=".$slip['department']."");
		$location	=	$dbconnection->getField("location_tbl","locationname","locationid=".$slip['location']."");		
		$cdno	=	$slip['documentmober'];
	}
	
//	$ids	=	$dbconnection->getField("paymentslip_tbl","workslipids","slipid=".$_REQUEST['slipid']."");
	$query	=	$dbconnection->firequery("select a.workslipid,a.narration,a.rate,a.quantity,a.total,b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipid=".$_REQUEST['slipid']." order by a.workslipid");

?>	
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<tr style="line-height:100px; vertical-align:middle;"><td colspan="2" align="center"><img src="../images/vnr.png" style="float:left;" /><b style="font-size:30px;">VNR SEEDS PVT. LTD.</b></td></tr>
<tr><td colspan="2" align="center"><b>HAMALI WORK STATEMENT & PAYMENT SLIP</b></td></tr>
<tr><td>Work Slip Number : <b><?php echo $workslipnumber;?></b></td><td align="right">Work Slip Date & Time : <b><?php echo $workslipdate;?></b></td></tr>
<tr><td>Department Name : <b><?php echo $dbconnection->getField("department_tbl","departmentname","departmentid=".$_SESSION['supervisordetail'][0]['departmentid']."");?></b></td><td align="right">Hamali Group Name : <b><?php echo $groupname;?></b></td></tr>
<tr><td colspan="2">
<table style="border:1px solid #CCCCCC; border-collapse:collapse; width:100%;" border="1">
<tr style="font-size:12px;">
	<td align="center"><b>S.No.</b></td>
	<td><b>Particular</b></td>	
	<td><b>Work Slip No</b></td>		
	<td align="center"><b>Rate</b></td>	
	<td align="center"><b>Quantity</b></td>	
	<td align="center"><b>Total</b></td>	
	<td><b>Supervisor</b></td>	
</tr>
<?php
$i=0;
$total=0;
while($ro=mysqli_fetch_assoc($query))
{
$i++;
?>
<tr style="font-size:12px;">
	<td align="center"><?php echo $i;?></td>
	<td><?php echo $ro['narration'];?></td>	
	<td><?php echo $dbconnection->getField("workslip_tbl","workslipnumber","workslipid=".$ro['workslipid']."");?></td>		
	<td align="center"><?php echo $ro['rate'];?></td>		
	<td align="center"><?php echo $ro['quantity'];?></td>		
	<td align="center"><?php echo number_format($ro['total'],'2','.','');?></td>		
	<td><?php echo $ro['firstname']." ".$ro['lastname'];?></td>		
</tr>
<?php
$total=$total+$ro['total'];
}
?>
<tr>
	<td colspan="5" align="right"><b>Total Work Slip Amount</b></td>
	<td align="center"><b><?php echo number_format($total,'2','.','');?></b></td>
	<td></td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</body>
</html>
<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/bootbox.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
