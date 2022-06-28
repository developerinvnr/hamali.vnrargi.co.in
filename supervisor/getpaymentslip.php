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
<div class="tabbable">
		<ul class="nav nav-tabs padding-18">
			<li class="active"><a data-toggle="tab" href="#feed"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> Make Payment Slip</a></li>
		</ul>

	
		<div class="tab-content no-border padding-24" style="border:1px solid #ddd; min-height:150px;">			

<div id="feed" class="tab-pane in active">
				<div class="row">
<form name="pagedata" id="pagedata" method="post" action="" onsubmit="return false;">
<input type="hidden" name="acn" id="acn" value="save" />
<input type="hidden" name="m" id="m" value="<?php echo encrypt("work & payment slip-get payment slip");?>" />
<input type="hidden" name="p" id="p" value="<?php echo encrypt("savepaymentslip");?>" />						
<input type="hidden" name="ids" id="ids" class="ids" value="<?php echo $_POST['ids'];?>" />
<input type="hidden" name="gpno" id="gpno" value="<?php echo $_POST['gpno'];?>" />
<input type="hidden" name="department" id="department" value="<?php echo $_SESSION['supervisordetail'][0]['departmentid'];?>" />
<input type="hidden" name="location" id="location" value="<?php echo $_SESSION['supervisordetail'][0]['locationid'];?>" />
<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
<?php
	$ids		=	$_POST['ids'];
	$query	=	$dbconnection->firequery("select a.workslipid,a.narration,a.rate,a.quantity,a.total,b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipid in (".$ids.") order by a.workslipid");

?>	
<table style="width:100%; border:1px solid #CCCCCC;" border="1">
<tr><td colspan="7" align="center"><h4>VNR SEEDS PVT. LTD.</h4></td></tr>
<tr><td colspan="7" align="center"><b>HAMALI WORK STATEMENT & PAYMENT SLIP</b></td></tr>
<tr><td colspan="7">Payment Slip Date : <input type="datetime-local" name="payslipdate" id="payslipdate" value="<?php echo date('Y\-m\-d')."T".date('H:i');?>" readonly style="height:25px; padding:0px; width:200px;" /> &nbsp;&nbsp;Department Name : <b><?php echo $dbconnection->getField("department_tbl","departmentname","departmentid=".$_SESSION['supervisordetail'][0]['departmentid']."");?></b>,&nbsp;&nbsp;&nbsp;&nbsp; Hamali Group Name : <b><?php echo $dbconnection->getField("hamaligroup_tbl","groupname","hgid=".$_POST['gpno']."");?></b></td></tr>
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
	<td colspan="5" align="right"><b>Total</b></td><td align="center"><b><?php echo $total;?></b></td><td><input type="hidden" name="total" id="total" value="<?php echo $total;?>" /></td></tr>
</tr>
<tr>
<td colspan="7" style="padding:0px;">
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<tr>
	<td style="padding:0px;">&nbsp;Total Amount : </td>
	<td style="padding:0px;"><input type="text" name="totalamount" id="totalamount" value="<?php echo $total;?>" readonly  style="width:100%;" onKeyPress="return OnKeyPress(this, event)" tabindex="1" autocomplete="off"/></td>
	<td style="padding:0px;">&nbsp;Paying Amount : </td>
	<td style="padding:0px;"><input type="number" name="paying" id="paying" value="0" style="width:100%;" placeholder="Paying amount" onchange="GetBalance(this.value)" onKeyPress="return OnKeyPress(this, event)" tabindex="2" autocomplete="off" /></td>
	<td style="padding:0px;">&nbsp;Balance : </td>
	<td style="padding:0px;"><input type="number" name="balance" id="balance" tabindex="3" value="<?php echo $total;?>" style="width:100%;" readonly placeholder="Balance amount" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" /></td>
</tr>
<tr>
	<td style="padding:0px;">&nbsp;Payment Mode :</td>
	<td style="padding:0px;" nowrap="nowrap">
		<select name="paymentmode" id="paymentmode" style="width:100%;" onKeyPress="return OnKeyPress(this, event)" tabindex="4">
			<option value="CASH">CASH</option>
			<option value="CHEQUE">CHEQUE</option>
			<option value="DD">DD</option>
		</select>
	</td>
	<td style="padding:0px;">&nbsp;Cheque/DD Number : </td>
	<td style="padding:0px;"><input type="text" name="cdno" id="cdno" placeholder="Cheque/DD Number" style="width:100%;" tabindex="5" onKeyPress="return OnKeyPress(this, event)"  autocomplete="off"/></td>
	<td style="padding:0px;">&nbsp;Remark : </td><td style="padding:0px;"><input type="text" name="remark" id="remark" tabindex="6" placeholder="Enter remark here (if any)" style="width:100%;"  onKeyPress="return OnKeyPress(this, event)" autocomplete="off"/></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="7">
<button class="btn btn-info" type="button" style="float:right; margin-left:10px;" onclick="window.history.back();">Back</button>		
<button class="btn btn-info" id="sub" type="button" style="float:right;" onclick="SumbitForm();" tabindex="7">Generate Payment Slip</button>
</td>
</tr>
</table>
	<div class="table-responsive">
		
	</div>
	</form>
				</div>
			</div>

</div>
</div>



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
	function GetBalance(paying)
	{
		var total	=	Number(document.getElementById("totalamount").value);
		if(paying>total)
		{
			$("#paying").focus();
			bootbox.alert("Paying amount can not be greater than total amount.");
			$("#sub").prop("disabled","disabled");			
		}
		else
		{
			document.getElementById("balance").value	=	total-paying;
			$("#sub").prop("disabled","");			
		}
	}
	function SumbitForm()
	{
		var a	=	bootbox.alert("Do you want to save this record.");
		if(a)
		{
			document.forms.pagedata.submit();
		}
	}
</script>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script src="../js/bootbox.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
