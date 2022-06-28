<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
date_default_timezone_set('Asia/Calcutta');
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['centerdetail'][0]['sessionid']))
{
	$tablename		=	$_POST['processname']."_tbl";
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];
	$i=0;
	$totalbalance	=	0;
	$totalreceived	=	0;
	$totalpaid	=	0;
	if($searchvalue=="")	
	$rs_sel	=	$dbconnection->firequery("select * from staff_tbl where FIND_IN_SET(".$_SESSION['centerdetail'][0]['sessionid'].",collectioncenter)>0");	
	else
	$rs_sel	=	$dbconnection->firequery("select * from staff_tbl where FIND_IN_SET(".$_SESSION['centerdetail'][0]['sessionid'].",collectioncenter)>0 and staffname like '%$searchvalue%'");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	$received	=	0;
	$paid		=	0;
	$balance	=	0;
	?>
	<tr>
		<td align="center"><?php echo $i;?></td>
		<td><?php echo $_SESSION['centerdetail'][0]['authname'];?></td>		
		<td><?php echo $row['staffname'];?></td>		
		<td><?php echo $row['mobilenumber'];?></td>
		<td><i class="fa fa-inr"></i> <?php echo $received	=	doubleval($dbconnection->getField("staffbalance_view","received","staffid=".$row['staffid']." and centerid=".$_SESSION['centerdetail'][0]['sessionid'].""));?></td>		
		<td><i class="fa fa-inr"></i> <?php echo $paid	=	doubleval($dbconnection->getField("staffbalance_view","paid","staffid=".$row['staffid']." and centerid=".$_SESSION['centerdetail'][0]['sessionid'].""));?></td>	
		<td style="font-size:16px;"><i class="fa fa-inr"></i> <?php echo $balance	=	$received-$paid;?></td>	
		<td style="padding:0px;">
		<?php
		if($balance>0)
		{
		?>
		<button type="button" class="btn btn-info" id="btn<?php echo $j;?>" title="btn<?php echo $j;?>" style="width:100%;" onclick="Receive('<?php echo encrypt($row['staffid']);?>','<?php echo encrypt($row['franchisename']);?>','<?php echo encrypt($_SESSION['centerdetail'][0]['sessionid']);?>',<?php echo $balance;?>,<?php echo $j;?>)">RECEIVE</button>
		<?php
		}
		else
		{
		?>
		<button type="button" class="btn btn-default" disabled="disabled" style="width:100%;"><i class="fa fa-smile-o" style="font-size:18px;"></i> PAID</button>	
		<?php
		}
		?>
		</td>
	</tr>
	<?php
	$totalbalance	=	$totalbalance+$balance;
	$totalreceived	=	$totalreceived+$received;	
	$totalpaid		=	$totalpaid+$paid;		
	}
	?>
	<tr style="font-size:18px;">
		<td colspan="4" align="right";><b>Total</b></td>
		<td><i class="fa fa-inr"></i> <?php echo $totalreceived;?></td>
		<td><i class="fa fa-inr"></i> <?php echo $totalpaid;?></td>		
		<td><i class="fa fa-inr"></i> <?php echo $totalbalance;?></td>	
		<td></td>	
	</tr>
	<?php
	
}
?>
