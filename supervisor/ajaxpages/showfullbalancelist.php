<?php
@session_start();
date_default_timezone_set('Asia/Calcutta');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
date_default_timezone_set('Asia/Calcutta');
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
$staffid	=	$_POST['staffid'];
$centerid	=	$_POST['centerid'];
$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));	

$rs_el	=	$dbconnection->firequery("select * from customertest_tbl where centerid=".$centerid." and staffid=".$staffid." and balance>0 order by creationdate");

?>
<table class="table table-bordered">
	<tr style="font-size:14px;">
		<td colspan="9" style="padding:0px;">
		<b>Center Name : </b><?php echo strtoupper($dbconnection->getField("center_tbl","centername","centerid=".$centerid.""));?>, <b>Staff Name : </b><?php echo strtoupper($dbconnection->getField("staff_tbl","staffname","staffid=".$staffid.""));?>
		<button type="button" class="btn btn-info" onclick="Close()" style="float:right;">Close</button>
		<input type="hidden" name="selectedval" id="selectedval" />
		<button type="button" class="btn btn-info" onclick="PayAllBalance()" style="float:right; margin-right:10px;">PAY ALL BALANCE</button>
		<button type="button" class="btn btn-info" onclick="DiscountBalance()" style="float:right; margin-right:10px;">DISCOUNT ALL BALANCE</button>
		</td>
	</tr>
	<tr>
		<td align="center"><b>Sno</b></td>
		<td><b>Customer Detail</b></td>
		<td><b>Test Detail</b></td>
		<td><b>Remark</b></td>
		<td><b>Amount Detail</b></td>
		<td nowrap="nowrap"><b>Balance</b> <input type="checkbox" value="" name="allchk" id="allchk" onclick="CheckAll()" style="vertical-align:text-top;" /></td>
		<td><b>Give Discount</b></td>
		<td><b>Pay Balance</b></td>		
	</tr>
	<?php
	$i=0;
	$bal	=	0;
	while($ow=mysqli_fetch_assoc($rs_el))
	{
	$i++;
	?>
	<tr>
		<td align="center"><?php echo $i;?>	
			<input type="hidden" name="scustid<?php echo $i;?>" id="scustid<?php echo $i;?>" value="<?php echo $ow['customerid'];?>" />	
		</td>
		<td style="padding:2px;">
		<b><?php echo strtoupper($ow['customername']);?></b>
		<?php echo "<br>".$ow['mobilenumber'];?>
		<?php echo "<br>".date('d\-m\-Y h:i A',strtotime($ow['creationdate']));?>		
		</td>
		<td style="padding:2px;"><?php echo $ow['testname'];?></td>
		<td style="padding:2px;"><?php echo $ow['remark'];?></td>
		<td style="padding:2px;" nowrap="nowrap">
		Total Amount : <i class="fa fa-inr"></i> <?php echo $ow['totalamount'];?><br />
		After Discount : <i class="fa fa-inr"></i> <label id="afterdiscount<?php echo $i;?>"><?php echo $ow['afterdiscount'];?></label><br />
		Paid : <i class="fa fa-inr"></i> <label id="spaid<?php echo $i;?>"><?php echo $ow['paid'];?></label>
		<br /><br /><b>Additional Discount : <label id="sadmin<?php echo $i;?>"><?php echo $ow['admindiscount'];?></label></b>
		</td>
		<td style="padding:2px;" nowrap="nowrap">
		<input type="checkbox" class="custbox" name="scustomerid<?php echo $i;?>" id="scustomerid<?php echo $i;?>" value="<?php echo $ow['customerid'];?>" onclick="AddCustomerId()" style="vertical-align:text-top;" />
		<i class="fa fa-inr"></i> <label id="sbal<?php echo $i;?>" style="vertical-align:text-top;"><?php echo $ow['balance'];?></label>			
		<input type="hidden" name="sbalance<?php echo $i;?>" id="sbalance<?php echo $i;?>" value="<?php echo $ow['balance'];?>" />			
		</td>
		<td style="padding:2px; width:200px;">
			<label id="mg<?php echo $i;?>" class="btn-danger" style="min-height:20px; width:100%; padding:5px; color:#FFFFFF; display:none;">&nbsp;</label>
			<label id="mg1<?php echo $i;?>" class="btn-success" style="min-height:20px; width:100%; padding:5px; color:#FFFFFF; display:none;">&nbsp;</label>	
		
			<input type="text" name="sdiscount<?php echo $i;?>" id="sdiscount<?php echo $i;?>" placeholder="Discount amount" style="width:100%;" /><br />
			<input type="text" name="sremark<?php echo $i;?>" id="sremark<?php echo $i;?>" placeholder="Remark here" style="width:100%;" /><br />			
			<button type="button" class="btn btn-info" style="width:100%;" onclick="UpdateDiscount(<?php echo $i;?>,<?php echo $staffid;?>,<?php echo $centerid;?>)">Update Discount</button>
		</td>
		<td style="padding:2px; width:200px;">
			<label id="smg<?php echo $i;?>" class="btn-danger" style="min-height:20px; width:100%; padding:5px; color:#FFFFFF; display:none;">&nbsp;</label>
			<label id="smg1<?php echo $i;?>" class="btn-success" style="min-height:20px; width:100%; padding:5px; color:#FFFFFF; display:none;">&nbsp;</label>	
			<input type="text" name="spay<?php echo $i;?>" id="spay<?php echo $i;?>" placeholder="Paying amount" style="width:100%;" /><br />
			<input type="text" name="spayremark<?php echo $i;?>" id="spayremark<?php echo $i;?>" placeholder="Remark here" style="width:100%;" /><br />			
			<button type="button" class="btn btn-info" style="width:100%;" onclick="PayBalance(<?php echo $i;?>,<?php echo $staffid;?>,<?php echo $centerid;?>)">Pay Balance</button>
		</td>
	</tr>
	<?php
	$bal	=	$bal+$ow['balance'];
	}
	?>
	<tr><td colspan="6" align="right"><b>Total</b></td><td><b><i class="fa fa-inr"></i> <?php echo $bal;?></b></td><td></td><td></td></tr>	
	<tr><td colspan="9" align="center"><button type="button" class="btn btn-info" onclick="Close()">Close</button></td></tr>
</table>
<?php
}
?>
<script>
		$("#allchk").click(function () {
			$(".custbox").prop('checked', $(this).prop('checked'));

			var selected = new Array();	
			$("input[type=checkbox]:checked").each(function () {
				selected.push(this.value);
			});
			document.getElementById("selectedval").value	=	selected;
		});

</script>