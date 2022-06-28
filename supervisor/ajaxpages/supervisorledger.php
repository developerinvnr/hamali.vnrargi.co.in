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
	$supervisorname	=	$_POST['supervisorname'];
	$centername		=	$_POST['centername'];
	$frmdate		=	$_POST['frmdate'];
	$todate			=	$_POST['todate'];	

	if($supervisorname!="" && (strtotime($frmdate)<=strtotime($todate)))
	{
		?>
		<tr>
			<td colspan="6" style="text-align:left;">
			<?php
			if($centername=="")
			{
				$received	=	$dbconnection->getField("supervisor_receive_paid","sum(received)","date(receivingdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and supervisorid=".$supervisorname."");
				$paid		=	$dbconnection->getField("supervisor_receive_paid","sum(paid)","date(receivingdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and supervisorid=".$supervisorname."");			
				$closing	=	$received-$paid;
			}
			else
			{
				$received	=	$dbconnection->getField("supervisor_receive_paid","sum(received)","date(receivingdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and centerid=".$centername." and supervisorid=".$supervisorname."");
				$paid		=	$dbconnection->getField("supervisor_receive_paid","sum(paid)","date(receivingdate)<'".date('Y\-m\-d',strtotime($frmdate))."' and centerid=".$centername." and supervisorid=".$supervisorname."");			
				$closing	=	$received-$paid;
			}
			$ldate	=	date('Y\-m\-d',strtotime($frmdate));
			$ldate	=	date('Y\-m\-d', strtotime($ldate.'-1 day'));			
			
			
			?>
			<label style="font-size:14px; font-weight:bold;">CLOSING BALANCE FOR <?php echo date('d\-m\-Y',strtotime($ldate));?> = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
			<a href="./printing/printchledger.php?supervisorid=<?php echo $supervisorname;?>&centerid=<?php echo $centername;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-info" style="float:right;">Print</button></a>
			</td>
		</tr>
		<tr>
			<th style="padding:3px; text-align:center;">Sno</th>
			<th style="padding:3px;">Particular</th>
			<th style="padding:3px;">Date</th>
			<th style="padding:3px;">Received From Staff</th>
			<th style="padding:3px;">Paid To Franchise</th>
			<th style="padding:3px;">Balance</th>
		</tr>
		<?php
		if($centername=="")
		{
		$rs_led	=	$dbconnection->firequery("select a.staffid,a.receivingamount,a.paid as pd,a.receivingdate,a.centerid,a.supervisorid from supervisor_ledger a left join staff_tbl b on b.staffid=a.staffid where date(a.receivingdate)>='".date('Y\-m\-d',strtotime($frmdate))."' and date(a.receivingdate)<='".date('Y\-m\-d',strtotime($todate))."' and a.supervisorid=".$supervisorname." order by date(a.receivingdate)");
		}
		else
		{
		$rs_led	=	$dbconnection->firequery("select a.staffid,a.receivingamount,a.paid as pd,a.receivingdate,a.centerid,a.supervisorid from supervisor_ledger a left join staff_tbl b on b.staffid=a.staffid where date(a.receivingdate)>='".date('Y\-m\-d',strtotime($frmdate))."' and date(a.receivingdate)<='".date('Y\-m\-d',strtotime($todate))."' and a.supervisorid=".$supervisorname." and a.centerid=".$centername." order by date(a.receivingdate)");
		}

		
		$i=0;
		while($row=mysqli_fetch_assoc($rs_led))
		{
		$rec	=	0;
		$pd		=	0;
		$i++;
		?>
		<tr>
			<td align="center"><?php echo $i;?></td>
			<td>
			<?php
			if($row['staffid']!="")
			{
				echo "Received From ".$dbconnection->getField("staff_tbl","staffname","staffid=".$row['staffid']."");
			}
			else
			{
			echo "Paid To Franchise";
			}
			?>
			</td>
			<td><?php echo date('d\-m\-Y, h:i A',strtotime($row['receivingdate']));?></td>
			<td><i class="fa fa-inr"></i> <?php echo $rec		=	doubleval(ceil($row['receivingamount'])); ?></td>
			<td><i class="fa fa-inr"></i> <?php echo $pd		=	doubleval(ceil($row['pd'])); ?></td>
			<td><i class="fa fa-inr"></i> <?php echo $closing	=	ceil($closing+$rec-$pd);?></td>		
		</tr>
		<?php
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
			if($centername=="")
			{
				$received	=	$dbconnection->getField("supervisor_receive_paid","sum(received)","date(receivingdate)>'".date('Y\-m\-d',strtotime($todate))."' and supervisorid=".$supervisorname."");
				$paid		=	$dbconnection->getField("supervisor_receive_paid","sum(paid)","date(receivingdate)>'".date('Y\-m\-d',strtotime($todate))."' and supervisorid=".$supervisorname."");			
				$closing	=	$closing+$received-$paid;
			}
			else
			{
				$received	=	$dbconnection->getField("supervisor_receive_paid","sum(received)","date(receivingdate)>'".date('Y\-m\-d',strtotime($todate))."' and centerid=".$centername." and supervisorid=".$supervisorname."");
				$paid		=	$dbconnection->getField("supervisor_receive_paid","sum(paid)","date(receivingdate)>'".date('Y\-m\-d',strtotime($todate))."' and centerid=".$centername." and supervisorid=".$supervisorname."");			
				$closing	=	$closing+$received-$paid;
			}
			?>
			<label style="font-size:16px; font-weight:bold;">FINAL CLOSING BALANCE FOR [<?php echo strtoupper($dbconnection->getField("supervisor_tbl","supervisorname","supervisorid=".$supervisorname.""));?>] TO GIVE FRANCHISE  = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
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
?>
