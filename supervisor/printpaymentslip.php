<?php
$t=1;
?>
<div class="main-content">
<style>
td{
padding:3px;
}
</style>
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li><i class="ace-icon fa fa-home home-icon"></i><a href="./index.php" style="text-decoration:none;">Home</a></li>
				<?php
				$m		=	trim(decryptvalue($_REQUEST['m']));
				$exp	=	explode("-",$m);
				$cnt	=	count($exp);
				for($i=0;$i<$cnt;$i++)
				{
					if($i==($cnt-1))
					{
					?>
						<li class="active"><?php echo ucwords($exp[$i]);?></li>
					<?php
					}
					else
					{
					?>
						<li><a href="#"><?php echo ucwords($exp[$i]);?></a></li>
					<?php
					}
				}
				?>
			</ul><!-- /.breadcrumb -->
			<!--
			<div class="nav-search" id="nav-search">
				<form class="form-search">
					<span class="input-icon">
						<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
						<i class="ace-icon fa fa-search nav-search-icon"></i>								</span>
				</form>
			</div>--><!-- /.nav-search -->
		</div>

			<div class="page-content">
				<div class="ace-settings-container" id="ace-settings-container"></div>
				<div class="row">
					<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->

<form name="pagedata" id="pagedata" method="post" action="#">
<input type="hidden" name="acn" id="acn" value="save" />
<input type="hidden" name="m" id="m" value="<?php echo $_POST['m'];?>" />
<input type="hidden" name="p" id="p" value="<?php echo $_POST['p'];?>" />						
<input type="hidden" name="ids" id="ids" class="ids" value="<?php echo $_POST['ids'];?>" />
<input type="hidden" name="gpno" id="gpno" value="<?php echo $_POST['gpno'];?>" />
<input type="hidden" name="department" id="department" value="<?php echo $_SESSION['supervisordetail'][0]['departmentid'];?>" />
<input type="hidden" name="location" id="location" value="<?php echo $_SESSION['supervisordetail'][0]['locationid'];?>" />
<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
<?php
	$rs_slip	=	$dbconnection->firequery("select * from paymentslip_tbl where slipid=".$_REQUEST['slipno']."");
	while($slip=mysqli_fetch_assoc($rs_slip))
	{
		$ids		=	$slip['workslipids'];
		$paymentmode=	$slip['paymentmode'];
		$payslipdate=	date('d\-m\-Y, h:i A',strtotime($slip['payslipdate']));		
		$department	=	$dbconnection->getField("department_tbl","departmentname","departmentid=".$slip['department']."");
		$location	=	$dbconnection->getField("location_tbl","locationname","locationid=".$slip['location']."");		
		$groupname	=	$dbconnection->getField("hamaligroup_tbl","groupname","hgid=".$slip['groupnumber']."");
		$cdno	=	$slip['documentmober'];
		$remark	=	$slip['remark'];
	}
	
	$ids	=	$dbconnection->getField("paymentslip_tbl","workslipids","slipid=".$_REQUEST['slipno']."");
	$query	=	$dbconnection->firequery("select a.workslipid,a.narration,a.rate,a.quantity,a.total,b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipid in (".$ids.") order by a.workslipid");

?>	
<table style="width:100%; border:1px solid #CCCCCC;" border="1">
<tr><td colspan="7" align="center"><h4>VNR SEEDS PVT. LTD.</h4></td></tr>
<tr><td colspan="7" align="center"><b>HAMALI WORK STATEMENT & PAYMENT SLIP</b></td></tr>
<tr><td colspan="7">Payment Slip Date : <b><?php echo $payslipdate;?></b> &nbsp;&nbsp;Department Name : <b><?php echo $department;?></b>,&nbsp;&nbsp;&nbsp;&nbsp; Hamali Group Name : <b><?php echo $groupname;?></b></td></tr>
<tr>
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
<tr>
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
	<td colspan="5" align="right"><b>Total</b></td><td align="center"><b><?php echo $total;?></b></td><td></td></tr>
</tr>
<tr>
	<td colspan="7" align="center"><b>PAYMENT DETAIL</b></td></tr>
<tr>
<tr>
	<td colspan="7">
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<tr>
	<td style="padding:0px; text-align:center;">&nbsp;<b>Payment Date</b></td>	
	<td style="padding:0px; text-align:center;">&nbsp;<b>Payment Mode</b></td>
	<td style="padding:0px; text-align:center;">&nbsp;<b>Paid Amount</b></td>	
	<td style="padding:0px; text-align:center;">&nbsp;<b>Cheque/DD Number</b></td>	
</tr>
<?php
$paid	=	0;
$rs_pay	=	$dbconnection->firequery("select * from payment_detail where payslipid=".$_REQUEST['slipno']." order by paymentdate");
while($pay=mysqli_fetch_assoc($rs_pay))
{
?>
<tr>
	<td style="text-align:center;"><?php echo date('d\-m\-Y, h:i A',strtotime($pay['paymentdate']));?></td>
	<td style="text-align:center;"><?php echo $pay['paymentmode'];?></td>
	<td style="text-align:center;"><?php echo $pay['paidamount'];?></td>	
	<td style="text-align:center;"><?php echo $pay['documentnumber'];?></td>	
</tr>
<?php
$paid	=	$paid+$pay['paidamount'];
}
?>
<tr>
	<td colspan="4" align="center" style="font-size:14px;"><b>Balance Amount : <i class="fa fa-inr"></i> <?php echo $total-$paid;?></b></td>
</tr>
</table>	
	</td>
</tr>
<tr>
<td colspan="7" style="text-align:center;">
<a href="./printing/printpayslip.php?slipid=<?php echo $_REQUEST['slipno'];?>" target="_blank"><button class="btn btn-info" type="button">PRINT PAYMENT SLIP</button></a>		
</td>
</tr>
</table>
</form>

					


						<div class="hr hr32 hr-dotted"></div>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	
		
	</div>
</div>
<script src="../assets/js/jquery-2.1.4.min.js"></script>

<script>
    $(document).ready(function() {
		$("#deleterecord").hide();	
		$("#warningmed").hide();
    });

</script>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script src="../js/bootbox.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
