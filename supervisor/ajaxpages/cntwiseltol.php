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
$t=7;
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$frmdate		=	$_POST['frmdate'];
	$todate			=	$_POST['todate'];
	$centerid		=	$_POST['centername'];
	
	if($centerid=="")
	{
		$query	=	"select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']."";
	}
	else
	{
		$query	=	"select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." and centerid=".$centerid."";	
	}
	$rs_sel	=	$dbconnection->firequery($query);
	
	$i=$start;
	$j=0;
	$tamount	=	0;
	$tafter		=	0;
	$tpaid		=	0;	
	$tbal		=	0;
	$totalltol	=	0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	$j++;
	if($i==1)
	{
	?>
		<tr><td colspan="11" align="right"><a href="./printing/printcntwiseltol.php?centername=<?php echo $centerid;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a></td></tr>
		<tr>
			<th style="padding:5px; text-align:center;">Sno</th>
			<th style="padding:5px;">Center Detail</th>
			<th style="padding:5px; text-align:center;">Total Business</th>
			<th style="padding:5px; text-align:center;">After Discount</th>
			<th style="padding:5px; text-align:center;">Paid</th>					
			<th style="padding:5px; text-align:center;">Balance</th>
			<th style="padding:5px; text-align:center;">LTOL</th>					
		</tr>
	<?php
	}
	?>
	<tr>
	<td align="center" valign="top"><?php echo $i;?></td>
	<td><?php echo $row['centername'];?><br /><?php echo $row['contactnumber'];?></td>
	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($totaltest=$dbconnection->getField("center_business","sum(totalamount)","centerid=".$row['centerid']." and testdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'"));?></td>

	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $after	=	doubleval(ceil($dbconnection->getField("center_business","sum(afterdiscount)","centerid=".$row['centerid']." and testdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'")));?></td>

	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $paid	=	doubleval(ceil($dbconnection->getField("customertest_tbl","sum(paid)","centerid=".$row['centerid']." and date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'")));?></td>	

	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $balance	=	doubleval(ceil($dbconnection->getField("customertest_tbl","sum(balance)","centerid=".$row['centerid']." and date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'")));?></td>	

	<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $ltol	=	doubleval(ceil($dbconnection->getField("center_business","sum(spdiscount)","centerid=".$row['centerid']." and testdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'")));?></td>	
	</tr>
	<?php
	$tamount	=	$tamount+$totaltest;
	$tafter		=	$tafter+$after;
	$tpaid		=	$tpaid+$paid;
	$tbal		=	$tbal+$balance;
	$totalltol	=	$totalltol+$ltol;
	}
}
?>
<tr>
	<td colspan="2" style="padding:2px 10px; text-align:right; font-size:18px;"><b>Total L TO L</b></td>
	<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($tamount);?></td>
	<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($tafter);?></td>	
	<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($tpaid);?></td>	
	<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($tbal);?></td>	
	<td style="font-size:18px; font-weight:bold; text-align:center;"><i class="fa fa-inr"></i> <?php echo doubleval($totalltol);?></td>
</tr>