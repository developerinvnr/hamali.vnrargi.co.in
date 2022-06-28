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

	$staffname	=	$_POST['staffname'];
	$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
	$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));	

	if($staffname!="")
	{
		$rs_staff		=	$dbconnection->firequery("select * from staff_tbl where franchisename='".$_SESSION['franchisedetail'][0]['sessionid']."' and staffid=".$staffname." order by staffname");
	}
	else
	{
		$rs_staff		=	$dbconnection->firequery("select * from staff_tbl where franchisename='".$_SESSION['franchisedetail'][0]['sessionid']."' order by staffname");
	}
	
	$j=0;
	$total	=	0;
	$total2	=	0;	
	while($staff=mysqli_fetch_assoc($rs_staff))
	{
	$j++;
	if($j==1)
	{
	?>
	<tr>
		<td colspan="6" align="right">
		<a href="./printing/printstcustbalance.php?staffid=<?php echo $staffname;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a>
		</td>
	</tr>
	<?php
	}
		$query	=	"select a.staffid,b.centerid,b.centername,b.contactnumber,a.balance as balance,c.staffname,c.mobilenumber from staffcustomer_balance a left join center_tbl b on b.centerid=a.centerid left join staff_tbl c on c.staffid=a.staffid where a.staffid=".$staff['staffid']." and c.franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." and a.balance>0 order by c.staffname";
//		$query	=	"select b.centername,b.contactnumber,a.balance as balance,c.staffname,c.mobilenumber from staffcustomer_balance a left join center_tbl b on b.centerid=a.centerid left join staff_tbl c on c.staffid=a.staffid where a.balance>0 and c.franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by c.staffname";
	$rs_sel	=	$dbconnection->firequery($query);
	$records	=	$dbconnection->num_rows($rs_sel);
	if($records>0)
	{
	?>
<!--
	<tr>
		<td colspan="6" align="right">
		<a href="./printing/printstcustbalance.php?staffid=<?php echo $staffname;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a>
		</td>
	</tr>	
-->
	<tr style="background-color:#0099CC; color:#FFFFFF; font-size:14px; font-weight:bold;"><td colspan="4" align="center"><?php echo ucwords($staff['staffname']);?></td></tr>
	<tr>
		<th style="padding:3px; text-align:center; width:50px;">Sno</th>
		<th style="padding:3px;">Center Name</th>
		<th style="padding:3px; text-align:center; width:200px;">As Per Date Customer Balance</th>
		<th style="padding:3px; text-align:center; width:250px;">Till <?php echo date('d\-m\-Y',strtotime($todate));?> Customer Balance</th>		
	</tr>
	
	<?php
	$i=0;
	$stafftotal	=	0;
	$tillbal=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	?>
	<tr>
		<td style="text-align:center;"><?php echo $i;?></td>
		<td><?php echo ucwords($row['centername']);?><br /><?php echo $row['contactnumber'];?></td>
		<td style="text-align:center;">
		<i class="fa fa-inr"></i>
		<?php
		echo $bal	=	ceil($dbconnection->getField("customertest_tbl","sum(balance)","staffid=".$row['staffid']." and centerid=".$row['centerid']." and date(creationdate) between '".$frmdate."' and '".$todate."'"));
		?>		
		</td>
		<td style="text-align:center;">
		<i class="fa fa-inr"></i>
		<?php
		echo $tbal	=	ceil($dbconnection->getField("customertest_tbl","sum(balance)","staffid=".$row['staffid']." and centerid=".$row['centerid']." and date(creationdate)<='".$todate."'"));
		?>		
		</td>
	</tr>
	<?php
	$total		=	$total+ceil($bal);
	$stafftotal	=	$stafftotal+ceil($bal);
	$total2		=	$total2+ceil($tbal);	
	$tillbal	=	$tillbal+$tbal;
	}
	?>
	<tr style="background-color:#0099CC; color:#FFFFFF; font-size:14px; font-weight:bold;">
		<td colspan="2" align="right">Total</td>
		<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $stafftotal;?></td>
		<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $tillbal;?></td>		
	</tr>	
	<?php
	}
	}
	?>
	<tr style="font-size:16px;">
		<td colspan="2" align="right"><b>Total Customer Balance</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total;?></b></td>		
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total2;?></b></td>				
	</tr>
	<?php
}
?>