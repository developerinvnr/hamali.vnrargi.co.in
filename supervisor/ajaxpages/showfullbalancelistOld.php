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

$rs_el	=	$dbconnection->firequery("select * from customertest_tbl where centerid=".$centerid." and staffid=".$staffid." and balance>0 order by creationdate");

?>
<table class="table table-bordered">
	<tr>
		<td style="text-align:center; width:50px;"><b>Sno</b></td>
		<td><b>Customer Detail</b></td>			
		<td><b>Test Detail</b></td>			
		<td><b>Remark</b></td>			
		<td><b>Amount Detail</b></td>			
		<td><b>Balance</b></td>
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
		<td style="padding:2px; text-align:center;"><?php echo $i;?></td>
		<td style="padding:2px;">
		<?php
		echo strtoupper($ow['customername']);
		echo "<br>".$ow['mobilenumber'];
		?>
		</td>
		<td style="padding:2px;"><?php echo $ow['testname'];?></td>
		<td style="padding:2px;"><?php echo $ow['remark'];?></td>
		<td style="padding:2px;">
		Total Amount : <i class="fa fa-inr"></i> <?php echo $ow['totalamount'];?><br />
		After Discount : <i class="fa fa-inr"></i> <label id="afterdiscount<?php echo $i;?>"><?php echo $ow['afterdiscount'];?></label><br />
		Paid : <i class="fa fa-inr"></i> <label id="paid<?php echo $i;?>"><?php echo $ow['paid'];?></label>
		</td>
		<td style="padding:2px;"><i class="fa fa-inr"></i> <label id="bal<?php echo $i;?>"><?php echo $ow['balance'];?></label>
			<input type="hidden" name="balance<?php echo $i;?>" id="balance<?php echo $i;?>" value="<?php echo $ow['balance'];?>" />
		</td>
		<td style="padding:2px;">
			<input type="text" name="discount<?php echo $i;?>" id="discount<?php echo $i;?>" placeholder="Discount amount" /><br />
			<button type="button" class="btn btn-info" onclick="UpdateDiscount(<?php echo $i;?>)">Update Discount</button>
		</td>
	</tr>
	<?php
	$bal	=	$bal+$ow['balance'];
	}
	?>
	<tr><td colspan="5" align="right"><b>Total</b></td><td><b><i class="fa fa-inr"></i> <?php echo $bal;?></b></td><td></td><td></td></tr>	
	<tr><td colspan="8" align="center"><button type="button" class="btn btn-info" onclick="Close()">Close</button></td></tr>
</table>
<?php
}
?>
