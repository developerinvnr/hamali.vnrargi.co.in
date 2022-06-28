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
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$slipid		=	$_POST['slipid'];
	$rs_sel	=	$dbconnection->firequery("select * from payment_detail where payslipid=".$slipid."");
?>
	<table style="width:100%; border:1px solid #CCCCCC; background-color:#eee; border-collapse:collapse;" border="1">
		<tr class="bg-primary">
			<td align="center">Payment Date</td>
			<td align="center">Payment Mode</td>
			<td align="center">Paid Amount</td>
			<td align="center">Cheque/DD Number</td>
		</tr>
		<?php
		$total	=	0;
		$totalqty=0;
		while($ro=mysqli_fetch_assoc($rs_sel))
		{
		?>
			<tr>
				<td align="center"><?php echo date('d\-m\-Y, h:i A',strtotime($ro['paymentdate']));?></td>
				<td align="center"><?php echo $ro['paymentmode'];?></td>
				<td align="center"><?php echo number_format($ro['paidamount'],'2','.','');?></td>
				<td align="center"><?php echo $ro['documentnumber'];?></td>				
			</tr>
		<?php
			$total		=	$total+$ro['paidamount'];
		}
		?>
		<tr class="bg-primary" style="font-size:16px;"><td colspan="5">Total Paid Amount : <i class="fa fa-inr"></i> <?php echo number_format($total,'2','.','');?>
		<button type="button" class="btn btn-info" onclick="Cls(<?php echo $_POST['ind'];?>)" style="float:right;">Close</button>
		</td></tr>
		</table>		
		<?php
}
?>
