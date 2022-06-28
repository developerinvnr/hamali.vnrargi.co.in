<?php
@session_start();
$t=10;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['staffdetail'][0]['sessionid']))
{
$ids	=	$_POST['ids'];
$rs_sel1	=	$dbconnection->firequery("select c.rateid,c.testid,c.discount,c.specialdiscount,c.customerprice,d.testname,d.testamount,d.containername,d.cpt from ratemapping_tbl a inner join rate_tbl b on a.rateid=b.rateid inner join ratelist_tbl c on c.rateid=b.rateid inner join test_tbl d on d.testid=c.testid where a.centerid=".$_POST['centerid']." and c.testid in (".$ids.") order by d.testname");

$total			=	0;
$discountable	=	0;
$i		=	0;
while($roo=mysqli_fetch_assoc($rs_sel1))
{
	$i++;
	?>
	<tr>
		<td style="padding:5px;">
		<?php echo $roo['testname'];?>
		<input type="hidden" name="rateid[]" id="rateid<?php echo $i;?>" value="<?php echo $roo['rateid'];?>" />
		<input type="hidden" name="testamount[]" id="testamount<?php echo $i;?>" value="<?php echo $roo['testamount'];?>" />		
		<input type="hidden" name="customerprice[]" id="customerprice<?php echo $i;?>" value="<?php echo $roo['customerprice'];?>" />
		<input type="hidden" name="disc[]" id="disc<?php echo $i;?>" value="<?php echo $roo['discount'];?>" />		
		<input type="hidden" name="spdiscount[]" id="spdiscount<?php echo $i;?>" value="<?php echo $roo['specialdiscount'];?>" />				
		<input type="hidden" name="cpt[]" id="cpt<?php echo $i;?>" value="<?php echo $roo['cpt'];?>" />
		<input type="hidden" name="containerid[]" id="containerid<?php echo $i;?>" value="<?php echo $roo['containername'];?>" />		
		</td>
		<td style="padding:5px;">
		<i class="fa fa-inr"></i> 
		<?php 
			if($roo['customerprice']==$roo['testamount'])
			echo $roo['customerprice'];
			else
			{
				echo $roo['customerprice'];
				?>
				(<label style="text-decoration:line-through;"><i class="fa fa-inr"></i> <?php echo $roo['testamount'];?></label>) <i class="fa fa-info-circle" title="Discount will not be applied in this test. It is already discounted price."></i>
				<?php
			}
			?>
		</td>	
	</tr>
	<?php
	$total				=	$total+$roo['customerprice'];
	
	if($roo['testamount']==$roo['customerprice'])
	{
		$discountable	=	$discountable+$roo['testamount'];
	}
}
if($i>0)
{

?>
<tr>
	<td style="padding:5px; text-align:right;"><b>Total Value</b></td>
	<td style="padding:5px;">
		<i class="fa fa-inr"></i> <b><?php echo $total;?></b><input type="hidden" name="totalvalue" id="totalvalue" value="<?php echo $total;?>" />
		<input type="hidden" name="discountable" id="discountable" value="<?php echo $discountable;?>" />
	</td>	
</tr>
<?php
if((doubleval($_POST['fd'])+doubleval($_POST['fld']))!=0 && $discountable!=0)
{
$discount	=	number_format(($discountable*$_POST['fd']/100),'2','.','');
?>
<tr>
	<td style="padding:5px; text-align:right;"><b>Discount (In %)</b></td>
	<td style="padding:0px;">
	<?php
	if($_POST['fld']==$_POST['fd'])
	{
	?>
		<input type="number" name="discount" id="discount" value="<?php echo $_POST['fd'];?>" tabindex="<?php echo $t++;?>" readonly onKeyPress="return OnKeyPress(this, event)" />
	<?php
	}
	else
	{
	?>
		<input type="number" name="discount" id="discount" value="<?php echo $_POST['fd'];?>" tabindex="<?php echo $t++;?>" onchange="CalAmount(this.value)" onKeyPress="return OnKeyPress(this, event)" />
	<?php
	}
	?>
	</td>	
</tr>
<?php
}
else
{
?>
<input type="hidden" name="discount" id="discount" value="0" readonly />
<?php
}
?>
<tr>
	<td style="padding:5px; text-align:right;"><b>Total Payable Amount</b></td>
	<td style="padding:0px;">
		<input type="text" readonly name="payableamount" id="payableamount" value="<?php if(doubleval($_POST['payableamount'])==0) echo $total-$discount; else echo doubleval($_POST['payableamount']);?>" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" />
	</td>	
</tr>
<tr>
	<td style="padding:5px; text-align:right;"><b>Paying Amount</b></td>
	<td style="padding:0px;">
		<input type="text" name="payingamount" id="payingamount" value="<?php echo doubleval($_POST['payingamount']);?>" tabindex="<?php echo $t++;?>" onchange="Balance(this.value)" onKeyPress="return OnKeyPress(this, event)"/>
	</td>	
</tr>
<tr>
	<td style="padding:5px; text-align:right;"><b>Balance Amount</b></td>
	<td style="padding:0px;">
		<input type="text" readonly name="balance" tabindex="<?php echo $t++;?>" id="balance" value="<?php if(doubleval($_POST['balance'])==0) echo $total-$discount; else echo doubleval($_POST['balance']);?>" onKeyPress="return OnKeyPress(this, event)" />
	</td>	
</tr>
<tr>
	<td style="padding:0px;" colspan="2">
		<input type="text" class="form-control" name="remark" placeholder="Enter remark here if any" tabindex="<?php echo $t++;?>" id="remark" value="<?php echo $_POST['remark'];?>" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" />
	</td>	
</tr>
<?php
}
else
{
?>
<tr>
	<td style="padding:5px; text-align:center;" colspan="2">--No Record Found--</td>
</tr>
<?php
}
}
?>