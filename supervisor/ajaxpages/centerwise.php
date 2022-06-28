<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",1);
ini_set("session.bug_compat_warn",1);
ini_set("session.bug_compat_42",1);

include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$t=10;
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	if($_POST['supervisor']!="")
	{
	$centers	=	$dbconnection->getField("supervisor_tbl","centers","supervisorid=".$_POST['supervisor']."");
	if($_POST['centername']=="")
	$rs_sel	=	$dbconnection->firequery("select * from center_tbl where centerid in (".$centers.") and franchisename=".$_SESSION['franchisedetail'][0]['sessionid']."");
	else
	$rs_sel	=	$dbconnection->firequery("select * from center_tbl where centerid=".$_POST['centername']." and franchisename=".$_SESSION['franchisedetail'][0]['sessionid']."");
	?>	
	<div class="col-sm-12">&nbsp;</div>
	<?php
	$k=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$k++;
	
	$business	=	0;
	$rbyyou		=	0;
	$rbyother	=	0;
	$remain		=	0;
	$cbal		=	0;
	?>
	<div class="col-sm-6">
		<div class="thumbnail search-thumbnail">
			<div class="caption">
				<div class="clearfix" style="height:340px; overflow:auto;">
				<h3 class="btn-info" style="font-size:14px; padding:4px; font-weight:bold;"><a style="text-decoration:none; color:#FFFFFF;"><i class="fa fa-building-o"></i> <?php echo $row['centername'];?></a><label style="float:right; font-weight:bold;"><i class="fa fa-mobile" style="font-size:20px; vertical-align:text-top;"></i> <?php echo $row['contactnumber'];?></label></h3>
				
				<table class="table table-bordered" style="padding:0px;">
					<tr class="btn-info">
						<td style="font-size:14px; text-align:center;"><b>Total Business</b></td>
						<td style="font-size:14px; text-align:center;" colspan="3"><b>Received</b></td>
						<td style="font-size:14px; text-align:center;"><b>Cust. Balance</b></td>
					</tr>
					<tr class="btn-info">
						<td style="font-size:14px; text-align:center;"></td>
						<td style="font-size:14px; text-align:center;"><b>By You</b></td>
						<td style="font-size:14px; text-align:center;"><b>By Other</b></td>
						<td style="font-size:14px; text-align:center;"><b>In Staff Hand</b></td>						
						<td style="font-size:14px; text-align:center;"></td>
					</tr>
					<tr>
						<td style="text-align:center; font-size:14px; font-weight:bold;">
						<i class="fa fa-inr"></i> <?php echo $business	=	doubleval($dbconnection->getField("customertest_tbl","sum(afterdiscount)","centerid=".$row['centerid']." group by centerid"));?>
						</td>
						<td style="text-align:center; font-size:14px; font-weight:bold;">
						<i class="fa fa-inr"></i> <?php echo $rbyyou	=	doubleval($dbconnection->getField("centerreceiving_tbl","sum(receivingamount)","centerid=".$row['centerid']." and supervisorid=".$_POST['supervisor'].""));?>
						</td>
						<td style="text-align:center; font-size:14px; font-weight:bold;">
						<i class="fa fa-inr"></i> <?php echo $rbyother	=	doubleval($dbconnection->getField("centerreceiving_tbl","sum(receivingamount)","centerid=".$row['centerid']." and supervisorid!=".$_POST['supervisor'].""));?>
						</td>
						<td style="text-align:center; font-size:14px; font-weight:bold;">
						<i class="fa fa-inr"></i> <?php echo $business-$rbyyou-$rbyother-doubleval($dbconnection->getField("customertest_tbl","sum(balance)","centerid=".$row['centerid'].""));?>
						</td>
						<td style="text-align:center; font-size:14px; font-weight:bold;">
						<i class="fa fa-inr"></i> <?php echo doubleval($dbconnection->getField("customertest_tbl","sum(balance)","centerid=".$row['centerid'].""));?>
						</td>
					</tr>
					<tr class="btn-info">
						<td style="text-align:center; font-size:14px; font-weight:bold;" colspan="2">Total Paid To Franchise</td>
						<td style="text-align:center; font-size:14px; font-weight:bold;" colspan="2">Total Received By You</td>
						<td style="text-align:center; font-size:14px; font-weight:bold;" colspan="2">Balance In Your Hand</td>						
					</tr>
					<tr>
						<td style="text-align:center; font-size:14px; font-weight:bold;" colspan="2">
						<i class="fa fa-inr"></i> <?php echo $paidtof	=	doubleval($dbconnection->getField("franchisereceiving_tbl","sum(receivingamount)","supervisorid=".$_POST['supervisor']." and centerid=".$row['centerid'].""));?>
						</td>
						<td style="text-align:center; font-size:14px; font-weight:bold;" colspan="2">
						<i class="fa fa-inr"></i> <?php echo $rbyyou;?>
						</td>
						<td style="text-align:center; font-size:18px; font-weight:bold;">
						<i class="fa fa-inr"></i> <?php echo $bal=$rbyyou-$paidtof;?>
						</td>						
					</tr>
					<tr>
						<td colspan="2"><input type="text" required name="amount<?php echo $k;?>" id="amount<?php echo $k;?>" min="0" onkeypress="CheckAmount(this.value,<?php echo $k;?>)" onchange="CheckAmount(this.value,<?php echo $k;?>)" placeholder="Enter amount" tabindex="<?php echo $t++;?>" class="form-control"/></td>
						<td colspan="4"><input type="text" name="remark<?php echo $k;?>" id="remark<?php echo $k;?>" placeholder="Remark detail (if any)" class="form-control" tabindex="<?php echo $t++;?>" /></td>						
					</tr>
					<tr>
						<td colspan="6">
							<label id="msg<?php echo $k;?>" class="btn-danger" style="min-height:20px; width:100%; padding:5px; color:#FFFFFF; display:none;">&nbsp;</label>
							<label id="msg1<?php echo $k;?>" class="btn-success" style="min-height:20px; width:100%; padding:5px; color:#FFFFFF; display:none;">&nbsp;</label>							
						</td>
					</tr>
					<tr>
						<td colspan="6" align="right"><button type="button" id="btn<?php echo $k;?>" class="btn btn-info form-control" tabindex="<?php echo $t++;?>" onclick="Receive(<?php echo $_POST['supervisor'];?>,<?php echo $_SESSION['franchisedetail'][0]['sessionid'];?>,<?php echo $row['centerid'];?>,<?php echo $bal;?>,<?php echo $k;?>)"><b>Receive</b></button></td>
					</tr>
				</table>
				</div>
			</div>
		</div>
	</div>	
	<?php
	}
	}
	else
	{
	?>
	<div class="col-sm-12" style="text-align:center;">--No Record Found--</div>
	<?php
	}
}
?>
