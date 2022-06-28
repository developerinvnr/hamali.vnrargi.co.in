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
	$cntname		=	$_POST['cntname'];
	
	if($cntname!="")
	{
		$query	=	"select b.centername,b.contactnumber,sum(a.balance) as balance from staffcustomer_balance a left join center_tbl b on b.centerid=a.centerid where a.centerid=".$cntname." order by b.centername";
	}
	else
	{
		$query		=	"select b.centername,b.contactnumber,sum(a.balance) as balance from staffcustomer_balance a left join center_tbl b on b.centerid=a.centerid where b.franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." group by a.centerid order by b.centername";
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
		<a href="./printing/printcntcustbalance.php?cntid=<?php echo $cntname;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a>
		</td>
	</tr>
	<tr>
		<th style="padding:3px; text-align:center; width:50px;">Sno</th>
		<th style="padding:3px;">Center Name</th>
		<th style="padding:3px; text-align:center; width:200px;">Customer Balance</th>
	</tr>
	
	<?php
	}
	?>
	<tr>
		<td style="text-align:center;"><?php echo $i;?></td>
		<td><?php echo ucwords($row['centername']);?><br /><?php echo $row['contactnumber'];?></td>
		<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo ceil($row['balance']);?></td>
	</tr>
	<?php
	$total	=	$total+ceil($row['balance']);
	}
	?>
	<tr style="font-size:16px;">
		<td colspan="2" align="right"><b>Total Customer Balance</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total;?></b></td>		
	</tr>
	<?php
}
?>