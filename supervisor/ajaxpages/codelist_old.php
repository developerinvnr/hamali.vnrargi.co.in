<?php
@session_start();
$t=7;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

$i=0;
$rs_det	=	$dbconnection->firequery("select * from workslip_detail where workslipid=".$_POST['slipid']."");
while($det=mysqli_fetch_assoc($rs_det))
{
$i++;
?>
<tr>
	<td style="padding:0px; width:85px;">
		<input type="text" name="workcode<?php echo $i;?>" id="workcode<?php echo $i;?>" value="<?php echo $det['workcode'];?>" readonly tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; width:100%;" autocomplete="off" />
	</td>
	<td style="padding:0px; width:85px;">
		<input type="text" name="quantity<?php echo $i;?>" id="quantity<?php echo $i;?>" value="<?php echo $det['quantity'];?>" placeholder="Quantity" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; width:100%;" autocomplete="off"/>
		<input type="hidden" name="rate" id="rate" value="<?php echo $det['rate'];?>" />
		<input type="hidden" name="remark" id="remark" value="<?php echo $det['narration'];?>" />
	</td>

	<td style="padding:0px; width:100px;">
		<input type="text" name="rem1<?php echo $i;?>" id="rem1<?php echo $i;?>" value="<?php echo $det['rem1'];?>" placeholder="Remark 1" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; width:100%;" autocomplete="off" />
	</td>
	<td style="padding:0px; width:100px;">
		<input type="text" name="rem2<?php echo $i;?>" id="rem2<?php echo $i;?>" value="<?php echo $det['rem2'];?>" placeholder="Remark 2" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; width:100%;" autocomplete="off" />
	</td>
	<td style="padding:0px; width:25px; text-align:center;"><i class="fa fa-edit" tabindex="<?php echo $t++;?>" onclick="UpdateRecord(<?php echo $det['detailid'];?>,<?php echo $det['workslipid'];?>,<?php echo $i;?>,<?php echo $det['total'];?>,<?php echo $det['rate'];?>)"></i></td>
	
	<td style="padding:0px; width:25px; text-align:center;">
	<i class="fa fa-remove" tabindex="<?php echo $t++;?>" onclick="RemoveRecord(<?php echo $det['detailid'];?>,<?php echo $det['workslipid'];?>,<?php echo $det['total'];?>)"></i>
	</td>	
	
	<td style="padding:0px;">
		<input type="text" name="narration<?php echo $i;?>" id="narration<?php echo $i;?>" value="<?php echo $det['narration'];?>" placeholder="Narration" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; width:100%;" autocomplete="off" />
	</td>
	
</tr>
<?php
}
?>