<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
unset($_SESSION['records']);
if(isset($_SESSION['supervisordetail'][0]['sessionid']))
{
	$slipid		=	$_POST['slipid'];
?>
<style>
td{
padding:3px;
}
</style>
<?php
	$rs_slip	=	$dbconnection->firequery("select * from paymentslip_tbl where slipid=".$slipid."");
	while($slip=mysqli_fetch_assoc($rs_slip))
	{
		$ids		=	$slip['workslipids'];
		$slipnumber	=	$slip['payslipnumber'];
		$paymentmode=	$slip['paymentmode'];
		$payslipdate=	date('d\-m\-Y, h:i A',strtotime($slip['payslipdate']));		
		$department	=	$dbconnection->getField("department_tbl","departmentname","departmentid=".$slip['department']."");
		$location	=	$dbconnection->getField("location_tbl","locationname","locationid=".$slip['location']."");		
		$groupname	=	$dbconnection->getField("hamaligroup_tbl","groupname","hgid=".$slip['groupnumber']."");
		$cdno	=	$slip['documentmober'];
		$remark	=	$slip['remark'];
	}
	
//	$ids	=	$dbconnection->getField("paymentslip_tbl","workslipids","slipid=".$_REQUEST['slipid']."");
	$query	=	$dbconnection->firequery("select a.workslipid,a.narration,a.rate,a.quantity,a.total,b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipid in (".$ids.") order by a.workslipid");

?>	
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<tr style="line-height:100px; vertical-align:middle;"><td colspan="2" align="center"><img src="../images/vnr.png" style="float:left;" /><b style="font-size:30px;">VNR SEEDS PVT. LTD.</b></td></tr>
<tr><td colspan="2" align="center"><b>HAMALI WORK STATEMENT & PAYMENT SLIP</b></td></tr>
<tr><td>Pay Slip Number : <b><?php echo $slipnumber;?></b></td><td align="right">Payment Slip Date : <b><?php echo $payslipdate;?></b></td></tr>
<tr><td>Department Name : <b><?php echo $department;?></b></td><td align="right">Hamali Group Name : <b><?php echo $groupname;?></b></td></tr>
<tr><td colspan="2">
<table style="border:1px solid #CCCCCC; border-collapse:collapse; width:100%;" border="1">
<tr style="font-size:12px;">
	<td align="center"><b>S.No.</b></td>
	<td><b>Particular</b></td>	
	<td><b>Work Slip No</b></td>		
	<td align="center"><b>Rate</b></td>	
	<td align="center"><b>Quantity</b></td>	
	<td align="center"><b>Total</b></td>	
	<td><b>Supervisor Name</b></td>	
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
	<td align="center"><?php echo $ro['total'];?></td>		
	<td><?php echo $ro['firstname']." ".$ro['lastname'];?></td>		
</tr>
<?php
$total=$total+$ro['total'];
}
?>
<tr>
	<td colspan="5" align="right"><b>Total</b></td>
	<td align="center"><b><?php echo $total;?></b></td>
	<td></td>
</tr>
</table>
</td>
</tr>
<tr>
	<td colspan="7" align="center"><b>PAYMENT DETAIL</b></td></tr>
<tr>
<tr>
	<td colspan="7">
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<tr style="font-size:12px;">
	<td style="padding:0px; text-align:center;">&nbsp;Payment Date</td>	
	<td style="padding:0px; text-align:center;">&nbsp;Payment Mode</td>
	<td style="padding:0px; text-align:center;">&nbsp;Paid Amount</td>	
	<td style="padding:0px; text-align:center;">&nbsp;Cheque/DD Number</td>	
</tr>
<?php
$paid	=	0;
$rs_pay	=	$dbconnection->firequery("select * from payment_detail where payslipid=".$slipid." order by paymentdate");
while($pay=mysqli_fetch_assoc($rs_pay))
{
?>
<tr style="font-size:12px;">
	<td style="text-align:center;"><?php echo date('d\-m\-Y h:i a',strtotime($pay['paymentdate']));?></td>
	<td style="text-align:center;"><?php echo $pay['paymentmode'];?></td>
	<td style="text-align:center;"><?php echo $pay['paidamount'];?></td>	
	<td style="text-align:center;"><?php echo $pay['documentnumber'];?></td>	
</tr>
<?php
$paid	=	$paid+$pay['paidamount'];
}
?>
<tr class="bg-primary"><td colspan="4" align="center" style="font-size:14px; font-weight:bold;">BALANCE AMOUNT : <?php echo $total-$paid;?></td></tr>
</table>	
	</td>
</tr>
<tr><td colspan="7" align="center"><button type="button" class="btn btn-info" onclick="Cls(<?php echo $_POST['ind'];?>)">Close</button></td></tr>
</table>

<?php
}
?>
