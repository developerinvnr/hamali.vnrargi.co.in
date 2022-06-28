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
	$centername		=	$_POST['centername'];
	$frmdate		=	$_POST['frmdate'];
	$todate			=	$_POST['todate'];

	$i=0;	
	$k=0;
	if($centername!="")
	{
	if(strtotime($frmdate)<strtotime($todate))
	{
	while(strtotime($frmdate)<=strtotime($todate))
	{
	$k++;
	if($k==1)
	{
	?>
		<tr>
			<td colspan="6" style="text-align:left;">
			<?php
			$received	=	$dbconnection->getField("staffledger_view","sum(payingamount)","date(paymentdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and centerid=".$centername."");
			$paid		=	$dbconnection->getField("staffledger_view","sum(receivingamount)","date(paymentdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and centerid=".$centername."");			
			$closing	=	$received-$paid;
			$ldate	=	date('Y\-m\-d',strtotime($frmdate));
			$ldate	=	date('Y\-m\-d', strtotime($ldate.'-1 day'));				
			?>
			<label style="font-size:14px; font-weight:bold;">CLOSING BALANCE FOR <?php echo date('d\-m\-Y',strtotime($ldate));?> = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
			<a href="./printing/printsummary.php?centerid=<?php echo $centername;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-info" onclick="PrintData()" style="float:right;">Print</button></a>
			</td>
		</tr>
		<tr>
			<th style="padding:3px; text-align:center;">Sno</th>
			<th style="padding:3px;">Date</th>
			<th style="padding:3px;">Received</th>
			<th style="padding:3px;">Paid</th>
			<th style="padding:3px;">Balance</th>
		</tr>
		<?php
	}
		$rs_led	=	$dbconnection->firequery("select sum(paid) as paid,sum(received) as received,paymentdate,centerid from staffledger_summary where centerid=".$centername." and date(paymentdate)='".date('Y\-m\-d',strtotime($frmdate))."' group by date(paymentdate)");
		while($row=mysqli_fetch_assoc($rs_led))
		{
		$rec	=	0;
		$pd		=	0;
		$i++;
		?>
		<tr>
			<td align="center"><?php echo $i;?></td>			
			<td><?php echo date('d\-m\-Y',strtotime($row['paymentdate']));?></td>
			<td><i class="fa fa-inr"></i> <?php echo $rec		=	doubleval(ceil($row['received'])); ?></td>
			<td><i class="fa fa-inr"></i> <?php echo $pd		=	doubleval(ceil($row['paid'])); ?></td>
			<td><i class="fa fa-inr"></i> <?php echo $closing	=	ceil($closing+$rec-$pd);?></td>		
		</tr>
		<?php
		}
		$frmdate	=	date('Y\-m\-d',strtotime($frmdate));
		$frmdate	=	date('Y\-m\-d', strtotime($frmdate.'+1 day'));			
	}
	if($i>0)
	{
	?>
	<tr>
		<td colspan="6" style="text-align:left;">
		<label style="font-size:14px; font-weight:bold;">AS PER SELECTED DATE RANGE CLOSING BALANCE ON <?php echo date('d\-m\-Y',strtotime($todate));?> IS = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
		</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td colspan="6" style="text-align:right;">
		<?php
		$received	=	$dbconnection->getField("staffledger_view","sum(payingamount)","date(paymentdate)>'".date('Y\-m\-d',strtotime($todate))."' and centerid=".$centername."");
		$paid		=	$dbconnection->getField("staffledger_view","sum(receivingamount)","date(paymentdate)>'".date('Y\-m\-d',strtotime($todate))."' and centerid=".$centername."");			
		$closing	=	$closing+$received-$paid;
		?>
		<label style="font-size:16px; font-weight:bold;">FINAL CLOSING BALANCE TO RECEIVE = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
		</td>
	</tr>
	<?php
	}
	else
	{
	?>
	<tr>
		<td colspan="9" style="padding:0px; text-align:center;">
			<label style="font-size:13px; font-weight:normal; padding:10px;"><i>--No Record Found--</i></label>
		</td>
	</tr>
	<?php
	}
	}
	else
	{
	?>
	<tr>
		<td colspan="9" style="padding:0px; text-align:center;">
			<label style="font-size:13px; font-weight:normal; padding:10px;"><i>--No Record Found--</i></label>
		</td>
	</tr>
	<?php
	}
}
?>
