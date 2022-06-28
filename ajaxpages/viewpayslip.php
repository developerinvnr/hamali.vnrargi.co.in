<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
unset($_SESSION['records']);
if(isset($_SESSION['datadetail'][0]['sessionid']))
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
		$contact	=	$dbconnection->getField("hamaligroup_tbl","contact_one","hgid=".$slip['groupnumber']."");
		$cdno	=	$slip['documentmober'];
		$remark	=	$slip['remark'];
	}
	
//	$ids	=	$dbconnection->getField("paymentslip_tbl","workslipids","slipid=".$_REQUEST['slipid']."");
	$query	=	$dbconnection->firequery("select a.department,a.workcode,a.workslipid,a.narration,a.rate,a.quantity,a.total,b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipid in (".$ids.") order by a.workslipid");

?>	
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<tr style="line-height:100px; vertical-align:middle;"><td colspan="2" align="center"><img src="./images/vnr.png" style="float:left;" /><b style="font-size:30px;">VNR SEEDS PVT. LTD.</b></td></tr>
<tr><td colspan="2" align="center"><b>HAMALI WORK STATEMENT & PAYMENT SLIP</b></td></tr>
<tr><td>Pay Slip Number : <b><?php echo $slipnumber;?></b></td><td align="right">Payment Slip Date : <b><?php echo $payslipdate;?></b></td></tr>
<tr><td>Plant/Location Name : <b><?php echo $location;?></b></td><td align="right">Hamali Group Name : <b><?php echo $groupname;?> [<?php echo $contact;?>]</b></td></tr>
<tr><td colspan="2">
<table style="border:1px solid #CCCCCC; border-collapse:collapse; width:100%;" border="1">
<tr style="font-size:12px;">
	<td align="center"><b>S.No.</b></td>
	<td align="center" nowrap><b>Work Code</b></td>		
	<td><b>Particular</b></td>	
	<td><b>Work Slip No</b></td>	
	<td align="center"><b>Date</b></td>
	<td><b>Department</b></td>	
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
	<td align="center"><?php echo $ro['workcode'];?></td>	
	<td nowrap><?php echo $ro['narration'];?></td>	
	<td nowrap><?php echo $dbconnection->getField("workslip_tbl","workslipnumber","workslipid=".$ro['workslipid']."");?></td>		
	<td nowrap><?php echo date('d\-m\-Y h:i A',strtotime($dbconnection->getField("workslip_tbl","workslipdate","workslipid=".$ro['workslipid']."")));?></td>
	<td nowrap><?php echo $dbconnection->getField("department_tbl","departmentname","departmentid=".$ro['department']."");?></td>		
	<td nowrap align="center"><?php echo $ro['rate'];?></td>		
	<td nowrap align="center"><?php echo $ro['quantity'];?></td>		
	<td nowrap align="center"><?php echo number_format($ro['total'],'2','.','');?></td>		
	<td nowrap><?php echo $ro['firstname']." ".$ro['lastname'];?></td>		
</tr>
<?php
$total=$total+number_format($ro['total'],'2','.','');
}
?>
<tr>
	<td colspan="8" align="right"><b>Total</b></td><td align="center"><b><?php echo number_format($total,'2','.','');?></b></td><td></td></tr>
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
	<td style="text-align:center;"><?php echo number_format($pay['paidamount'],'2','.','');?></td>	
	<td style="text-align:center;"><?php echo $pay['documentnumber'];?></td>	
</tr>
<?php
$paid	=	$paid+$pay['paidamount'];
}
?>
<tr class="bg-primary"><td colspan="4" align="center" style="font-size:14px; font-weight:bold;">BALANCE AMOUNT : <?php echo number_format(doubleval($total)-doubleval($paid),'2','.','');?></td></tr>
</table>	
	</td>
</tr>
<tr><td colspan="7" align="center"><button type="button" class="btn btn-info" onclick="Cls(<?php echo $_POST['ind'];?>)">Close</button></td></tr>
</table>

<?php
}
?>
