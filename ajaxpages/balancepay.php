<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
date_default_timezone_set('Asia/Calcutta');
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$slipid			=	$_POST['slipid'];
	$department		=	$_POST['department'];
	$location		=	$_POST['location'];
	$groupnumber	=	$_POST['groupnumber'];
	$totalamount	=	$dbconnection->getField("paymentslip_tbl","totalamount","slipid=".$slipid."");	
	$rs_sel			=	$dbconnection->firequery("select * from payment_detail where payslipid=".$slipid."");
?>
	<table style="width:100%; border:1px solid #CCCCCC; background-color:#eee; border-collapse:collapse;" border="1">
		<tr class="bg-primary">
			<td align="center">Payment Date</td>
			<td align="center">Cheque/DD Number</td>
			<td align="center">Payment Mode</td>
			<td align="center">Paid Amount</td>
		</tr>
		<?php
		$total	=	0;
		$totalqty=0;
		while($ro=mysqli_fetch_assoc($rs_sel))
		{
		?>
			<tr>
				<td align="center"><?php echo date('d\-m\-Y, h:i A',strtotime($ro['paymentdate']));?></td>
				<td align="center"><?php echo $ro['documentnumber'];?></td>				
				<td align="center"><?php echo $ro['paymentmode'];?></td>
				<td align="center"><?php echo $ro['paidamount'];?></td>
			</tr>
		<?php
			$total		=	$total+$ro['paidamount'];
		}
		?>
		<tr><td colspan="3" align="right"><b>Total Paid Amount</b></td><td align="center"><i class="fa fa-inr"></i> <?php echo $total;?></td></tr>
<tr>
<td colspan="4">
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
	<tr>
		<td>Payment Date</td>
		<td>
		<input type="datetime-local" name="paydate" id="paydate" value="<?php echo date('Y\-m\-d').'T'.date("H:i");?>" style="width:100%;" />
		</td>
		<td>Payment Mode</td>
		<td>
		<input type="hidden" name="slipid" id="slipid" value="<?php echo $slipid;?>" />
		<input type="hidden" name="department" id="department" value="<?php echo $department;?>" />
		<input type="hidden" name="location" id="location" value="<?php echo $location;?>" />
		<input type="hidden" name="groupnumber" id="groupnumber" value="<?php echo $groupnumber;?>" />
		<select name="paymode" id="paymode" style="width:100%;" onKeyPress="return OnKeyPress(this, event)" tabindex="4">
			<option value="CASH">CASH</option>
			<option value="CHEQUE">CHEQUE</option>
			<option value="DD">DD</option>
		</select>
		</td>
		<td>Cheque/DD No</td>
		<td>
		<input type="text" name="cdn" id="cdn" />
		</td>
	</tr>
	<tr>
		<td>Balance Amount</td>
		<td><input type="text" name="bal" id="bal" readonly value="<?php echo $totalamount-$total;?>" style="width:100%;" /></td>
		<td>Paying Amount</td>
		<td><input type="text" name="pay" id="pay" value="0" style="width:100%;" onchange="CheckBalanceAmount(this.value)" /></td>
		<td colspan="2">
		<button class="btn btn-info" <?php if(($totalamount-$total)==0) { ?> disabled="disabled"<?php } ?> id="pbal" type="button" onclick="PayBalanceAmount(<?php echo $_POST['ind'];?>)" style="width:100%; text-align:center;">PAY BALANCE AMOUNT</button>
		</td>
	</tr>
	<tr class="bg-primary">
		<td colspan="6" align="center"><button type="button" class="btn btn-info" onclick="Cls()">Close</button></td>
	</tr>
</table>
</td>
</tr>
		</table>		
		<?php
}
?>
<script>
	function CheckBalanceAmount(pay)
	{
		var bal	=	Number(document.getElementById("bal").value);
		if(pay>bal)
		{
			$("#pay").focus();
			bootbox.alert("Paying amount can not be greater than balance amount and minus values.");
			$("#pbal").prop("disabled","disabled");			
		}
		else
		{
			if(pay<0)
			{
				$("#pay").focus();
				document.getElementById("pay").value=0;
				bootbox.alert("Paying amount can not be a minus values.");
				$("#pbal").prop("disabled","disabled");			
			}
			else
			{
				$("#pbal").prop("disabled","");			
			}
		}		
	}
</script>
