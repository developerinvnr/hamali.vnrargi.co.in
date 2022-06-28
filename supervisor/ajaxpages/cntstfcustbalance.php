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
	$cntname	=	$_POST['cntname'];
	$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
	$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));
	
	$totalcustomerbalance	=	0;
	if($cntname=="")
	{
		$rs_sel	=	$dbconnection->firequery("select distinct(a.centerid),b.centername,b.contactnumber from staffcustomer_balance a inner join center_tbl b on b.centerid=a.centerid where a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." order by b.centername");
	}
	else
	{
		$rs_sel	=	$dbconnection->firequery("select distinct(a.centerid),b.centername,b.contactnumber from staffcustomer_balance a inner join center_tbl b on b.centerid=a.centerid where a.centerid=".$cntname." and a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." order by b.centername");	
	}
	
	$i=0;
	while($cnt=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	if($i==1)
	{
	?>
	<tr>
		<td colspan="6" align="right">
		<a href="./printing/printcntstfcustbalance.php?centerid=<?php echo $centername;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a>
		</td>
	</tr>
	<?php
	}
	$rs_bal	=	$dbconnection->firequery("select a.*,b.staffname,b.mobilenumber from staffcustomer_balance a inner join staff_tbl b on b.staffid=a.staffid where a.centerid=".$cnt['centerid']." and a.balance>0 and a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']."");
	$k=0;
	$sttotal	=	0;
	$total2	=	0;
	$records	=	$dbconnection->num_rows($rs_bal);
	if($records>0)
	{
	while($row=mysqli_fetch_assoc($rs_bal))
	{
		$k++;
		if($k==1)
		{
		?>
	<tr>
		<td colspan="4" style="text-align:center; background-color:#0099CC; color:#FFFFFF; font-size:14px; font-weight:bold;"><?php echo $cnt['centername'];?> [<?php echo $cnt['contactnumber'];?>]</td>
	</tr>
		<tr>
			<th style="padding:3px; text-align:center; width:50px;">Sno</th>
			<th style="padding:3px;">Staff Name</th>
			<th style="padding:3px; text-align:center; width:250px;">Between Date Customer Balance</th>
			<th style="padding:3px; text-align:center; width:250px;">Final Customer Balance</th>			
		</tr>
		<?php
		}
		?>
		<tr>
			<td style="text-align:center;"><?php echo $k;?></td>
			<td><?php echo ucwords($row['staffname']);?><br /><?php echo $row['mobilenumber'];?></td>
			<td style="text-align:center;">
			<i class="fa fa-inr"></i> 
			<?php 
			echo $bal	=	ceil($dbconnection->getField("customertest_tbl","sum(balance)","centerid=".$row['centerid']." and staffid=".$row['staffid']." and date(creationdate) between '".$frmdate."' and '".$todate."'"));
			?>
			</td>
			<td style="text-align:center;">
			<i class="fa fa-inr"></i> 
			<?php 
			echo $fbal	=	ceil($dbconnection->getField("customertest_tbl","sum(balance)","centerid=".$row['centerid']." and staffid=".$row['staffid'].""));
			?>
			</td>
		</tr>		
		<?php
		$sttotal	=	$sttotal+ceil($bal);
		$total2		=	$total2+ceil($fbal);
		$totalcustomerbalance=$totalcustomerbalance+ceil($row['balance']);
	}
	?>
	<tr style="background-color:#0099CC; color:#FFFFFF; font-size:14px; font-weight:bold;">
		<td colspan="2" align="right">Total</td>
		<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $sttotal;?></td>
		<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $total2;?></td>		
	</tr>
	<?php
	}
	}
	
	
	?>
	<tr style="font-size:16px;">
		<td colspan="3" align="right"><b>Total Customer Balance</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $totalcustomerbalance;?></b></td>		
	</tr>
	<?php
}
?>