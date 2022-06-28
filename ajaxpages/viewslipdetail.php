<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
unset($_SESSION['records']);
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$slipid		=	$_POST['workslipid'];
	$rs_sel	=	$dbconnection->firequery("select * from workslip_detail where workslipid=".$slipid."");
?>
	<table style="width:100%; border:1px solid #CCCCCC; background-color:#eee; border-collapse:collapse;" border="1">
		<tr class="bg-primary">
			<td align="center">Workcode</td>
			<td>Quantity</td>
			<td>Remark 1</td>
			<td>Remark 2</td>						
			<td>Narration
			<i class="fa fa-remove" style="float:right; color:white!important;" onclick="CloseWindow()"></i>
			</td>
		</tr>
		<?php
		$total	=	0;
		$totalqty=0;
		while($ro=mysqli_fetch_assoc($rs_sel))
		{
		?>
			<tr>
				<td align="center"><?php echo $ro['workcode'];?></td>
				<td><?php echo $ro['quantity'];?></td>
				<td><?php echo $ro['rem1'];?></td>				
				<td><?php echo $ro['rem2'];?></td>				
				<td><?php echo $ro['narration'];?></td>
			</tr>
		<?php
			$total	=	$total+$ro['total'];
			$totalqty	=	$totalqty+$ro['quantity'];
		}
		?>
		<tr class="bg-primary" style="font-size:16px; color:white;"><td colspan="5">Total Amount : <i class="fa fa-inr" style="color:#fff!important;"></i> <?php echo $total;?></td></tr>
		</table>		
		<?php
}
?>
