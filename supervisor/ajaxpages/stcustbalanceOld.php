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

	$staffname		=	$_POST['staffname'];
	
	if($staffname!="")
	{
		$query	=	"select b.centername,b.contactnumber,a.balance as balance,c.staffname,c.mobilenumber from staffcustomer_balance a left join center_tbl b on b.centerid=a.centerid left join staff_tbl c on c.staffid=a.staffid where a.staffid=".$staffname." and c.franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." and a.balance>0 order by c.staffname";
	}
	else
	{
		$query	=	"select b.centername,b.contactnumber,a.balance as balance,c.staffname,c.mobilenumber from staffcustomer_balance a left join center_tbl b on b.centerid=a.centerid left join staff_tbl c on c.staffid=a.staffid where a.balance>0 and c.franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by c.staffname";
	}
	$rs_sel	=	$dbconnection->firequery($query);
	
	$i=0;
	$total	=	0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	if($i==1)
	{
	?>
	<tr>
		<td colspan="6" align="right">
		<a href="./printing/printstcustbalance.php?staffid=<?php echo $staffname;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a>
		</td>
	</tr>
	<tr>
		<th style="padding:3px; text-align:center; width:50px;">Sno</th>
		<th style="padding:3px;">Staff Name</th>
		<th style="padding:3px;">Center Name</th>
		<th style="padding:3px; text-align:center; width:200px;">Customer Balance</th>
	</tr>
	
	<?php
	}
	?>
	<tr>
		<td style="text-align:center;"><?php echo $i;?></td>
		<td><?php echo ucwords($row['staffname']);?><br /><?php echo $row['mobilenumber'];?></td>
		<td><?php echo ucwords($row['centername']);?><br /><?php echo $row['contactnumber'];?></td>
		<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo ceil($row['balance']);?></td>
	</tr>
	<?php
	$total	=	$total+ceil($row['balance']);
	}
	?>
	<tr style="font-size:16px;">
		<td colspan="3" align="right"><b>Total Customer Balance</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total;?></b></td>		
	</tr>
	<?php
}
?>