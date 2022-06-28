<?php
$t=1;
@session_start();
$t=1;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
$sessionid	=	$_SESSION['datadetail'][0]['sessionid'];
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
<title>PRINT WORK SLIP</title>
<head>
<style>
td{
padding:3px;
}
</style>
</head>
<body>
<div id="printdiv">
<?php
	$rs_slip	=	$dbconnection->firequery("select * from workslip_tbl where workslipid=".$_POST['workslipid']."");
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
	$query	=	$dbconnection->firequery("select a.workcode,a.department,a.workslipid,a.narration,a.rate,a.quantity,a.total,b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipid=".$_POST['workslipid']." order by a.workslipid");

?>	
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<tr style="line-height:100px; vertical-align:middle;"><td colspan="2" align="center"><img src="./images/vnr.png" style="float:left;" /><b style="font-size:30px;">VNR SEEDS PVT. LTD.</b></td></tr>
<tr><td colspan="2" align="center"><b>HAMALI WORK STATEMENT & PAYMENT SLIP</b></td></tr>
<tr><td align="left">Work Slip Number : <b><?php echo $workslipnumber;?></b></td><td align="right">Work Slip Date & Time : <b><?php echo $workslipdate;?></b></td></tr>
<tr><td align="left">Location Name : <b><?php echo $location;?></b></td><td align="right">Hamali Group Name : <b><?php echo $groupname;?></b></td></tr>
<tr><td colspan="2">
<table style="border:1px solid #CCCCCC; border-collapse:collapse; width:100%;" border="1">
<tr style="font-size:12px;">
	<td align="center"><b>S.No.</b></td>
	<td align="center"><b>Work Code</b></td>
	<td align="left"><b>Particular</b></td>	
	<td><b>Department</b></td>		
	<td><b>Work Slip Date</b></td>
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
	<td align="center"><?php echo $i;?></td>	
	<td align="left"><?php echo $ro['narration'];?></td>	
	<td><?php echo $dbconnection->getField("department_tbl","departmentname","departmentid=".$ro['department']."");?></td>		
	<td><?php echo date('d\-m\-Y h:i A',strtotime($dbconnection->getField("workslip_tbl","workslipdate","workslipid=".$ro['workslipid']."")));?></td>		
	<td><?php echo $dbconnection->getField("workslip_tbl","workslipnumber","workslipid=".$ro['workslipid']."");?></td>		
	<td align="center"><?php echo $ro['rate'];?></td>		
	<td align="center"><?php echo $ro['quantity'];?></td>		
	<td align="center"><?php echo $ro['total'];?></td>		
	<td><?php echo $ro['firstname']." ".$ro['lastname'];?></td>		
</tr>
<?php
$total=$total+$ro['total'];
}
?>
<tr>
	<td colspan="8" align="right"><b>Total Work Slip Amount</b></td>
	<td align="center"><b><?php echo $total;?></b></td>
	<td></td>
</tr>
</table>
</td>
</tr>
<tr style="line-height:100px;">
	<td align="center" style="font-size:14px; font-weight:bold; border:none;" colspan="2">
		<label style="float:left;"><b>PREPARE BY</b></label>
		<label style="margin:0 auto;"><b>RECEIVED BY</b></label>
		<label style="float:right;"><b>AUTHORISED SIGNATORY</b></label>
	</td>
</tr>

</table>
</div>
<br>
<button type="button" class="btn" onclick="printDiv()">Print</button>
<button type="button" class="btn" onclick="Cls()">Close</button>
</body>
</html>
<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/bootbox.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
