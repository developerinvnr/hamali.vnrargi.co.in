<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
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
	$rs_sel	=	$dbconnection->firequery("select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by centername");
	else
	$rs_sel	=	$dbconnection->firequery("select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." and centerid=".$centerid." order by centername");	
	
	$totalamt	=	0;
	$totafter	=	0;
	$totpaid	=	0;
	$totbal		=	0;
	$totref		=	0;
	$totexp		=	0;
	$totinhand	=	0;
	$i=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
		if($i==1)
		{
		?>
		<tr>
			<td><b>Center Name</b></td>
			<td align="right"><b>Total Amount</b></td>
			<td align="right"><b>After Discount</b></td>
			<td align="right"><b>Paid</b></td>
			<td align="right"><b>Balance</b></td>
			<td align="right"><b>Reference</b></td>
			<td align="right"><b>Expenses</b></td>
			<td align="right"><b>In Hand</b></td>
		</tr>
		<?php
		}

		$rs_cust	=	$dbconnection->firequery("select sum(totalamount) as totalamt,sum(afterdiscount) as afterdiscountamt,sum(paid) as paid,sum(balance) as balance,
(select sum(doctorcommission) from customertest_detail where date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and centerid=".$row['centerid']." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid'].") as dtr,(select sum(amount) from expenses_tbl where centerid=".$row['centerid']." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and expensdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."') as expenses from customertest_tbl where centerid=".$row['centerid']." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'");
		
		while($ro=mysqli_fetch_assoc($rs_cust))
		{	
		?>
		<tr>
			<td><?php echo $row['centername'];?></td>
			<td style="text-align:right;"><i class="fa fa-inr"></i> <?php echo doubleval($ro['totalamt']);?></td>
			<td style="text-align:right;"><i class="fa fa-inr"></i> <?php echo doubleval($ro['afterdiscountamt']);?></td>
			<td style="text-align:right;"><i class="fa fa-inr"></i> <?php echo doubleval($ro['paid']);?></td>
			<td style="text-align:right;"><i class="fa fa-inr"></i> <?php echo doubleval($ro['balance']);?></td>
			<td style="text-align:right;"><i class="fa fa-inr"></i> <?php echo doubleval($ro['dtr']);?></td>
			<td style="text-align:right;"><i class="fa fa-inr"></i> <?php echo doubleval($ro['expenses']);?></td>
			<td style="text-align:right;"><i class="fa fa-inr"></i> <?php echo doubleval($ro['afterdiscountamt']-$ro['expenses']);?></td>												
		</tr>
		<?php
		$totalamt	=	$totalamt+$ro['totalamt'];
		$totafter	=	$totafter+$ro['afterdiscountamt'];
		$totpaid	=	$totpaid+$ro['paid'];
		$totbal		=	$totbal+$ro['balance'];
		$totdtr		=	$totdtr+$ro['dtr'];
		$totexp		=	$totexp+$ro['expenses'];
		$totinhand	=	$totinhand+$ro['afterdiscountamt']-$ro['expenses'];
		}
	}
	?>
	<tr>
		<td></td>
		<td style="text-align:right; font-weight:bold; font-size:14px;"><i class="fa fa-inr"></i> <?php echo $totalamt;?></td>
		<td style="text-align:right; font-weight:bold; font-size:14px;"><i class="fa fa-inr"></i> <?php echo $totafter;?></td>		
		<td style="text-align:right; font-weight:bold; font-size:14px;"><i class="fa fa-inr"></i> <?php echo $totpaid;?></td>		
		<td style="text-align:right; font-weight:bold; font-size:14px;"><i class="fa fa-inr"></i> <?php echo $totbal;?></td>		
		<td style="text-align:right; font-weight:bold; font-size:14px;"><i class="fa fa-inr"></i> <?php echo $totdtr;?></td>		
		<td style="text-align:right; font-weight:bold; font-size:14px;"><i class="fa fa-inr"></i> <?php echo $totexp;?></td>		
		<td style="text-align:right; font-weight:bold; font-size:14px;"><i class="fa fa-inr"></i> <?php echo $totinhand;?></td>		
	</tr>
	<?php
}	
?>