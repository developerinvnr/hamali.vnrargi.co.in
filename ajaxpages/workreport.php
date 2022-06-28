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
?>
<style>
td{
	border:1px solid #777;
	vertical-align:top;
}
</style>
<?php
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$frmdate		=	date('Y\-m\-d H:i:s',strtotime($_POST['frmdate']));
	$todate			=	date('Y\-m\-d H:i:s',strtotime($_POST['todate']));
	$locations		=	trim($_POST['locations']);
	$departments	=	trim($_POST['departments']);
	$supervisors	=	trim($_POST['supervisors']);
	$hamaligroups	=	trim($_POST['hamaligroups']);
	?>
	<table style="width:100%; border:1px solid #777;" border="1">
	<tr style="background-color:#008C40; color:white;">
		<td align="left" nowrap>Location</td>
		<td align="left" nowrap>Date</td>
		<td align="left" nowrap>Section</td>
		<td align="left" nowrap>Supervisor</td>
		<td align="left" nowrap>Hamali Group</td>
		<td align="center" nowrap>O.P.B.</td>
		<td align="left" nowrap>Pay Slip Number</td>
		<td align="left" nowrap>Work Slip Number</td>
		<td align="left" nowrap>Work Code</td>
		<td align="left" nowrap>Narratin</td>
		<td align="center" nowrap>Rate</td>
		<td align="center" nowrap>Quantity</td>
		<td align="left" nowrap>Work Slip Amount</td>
		<td align="center" nowrap>W.S.A.</td>
		<td align="center" nowrap>P.S.A.</td>
		<td align="center" nowrap>Balance</td>
	</tr>
	<?php
	$k=0;
	if($locations=="")
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl order by locationname");
	else
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$locations.") order by locationname");
	while($loc=mysqli_fetch_assoc($rs_sel))
	{
	$rs_pay	=	$dbconnection->firequery("select * from paymentslip_tbl where location=".$loc['locationid']." and payslipdate between '".$frmdate."' and '".$todate."' order by payslipdate");
	while($pay=mysqli_fetch_assoc($rs_pay))
	{
	$k++;
	$sectionname	=	$dbconnection->getField("section_tbl","sectionname","FIND_IN_SET(".$pay['department'].",departmentname)>0");
	$supervisor	=	$dbconnection->getField("supervisor_tbl","firstname","supervisorid=".$pay['supervisorid']."");
	$hamali	=	$dbconnection->getField("hamaligroup_tbl","groupname","hgid=".$pay['groupnumber']."");
	$slipids=	$pay['workslipids'];	

	$preadvance		=	$dbconnection->getField("advance_tbl","sum(amount)","groupname=".$pay['groupnumber']." and advancedate<'".$pay['payslipdate']."' and location=".$loc['locationid']."");	
	$prededuction	=	$dbconnection->getField("payment_detail","sum(frmadvance)","groupnumber=".$pay['groupnumber']." and payslipdate<'".$pay['paymentdate']."' and location=".$loc['locationid']."");
	$baladvance		=	$preadvance-$prededuction;
	
	$prework		=	$dbconnection->getField("workslip_tbl","sum(workslipamount)","groupnumber=".$pay['groupnumber']." and workslipdate<'".$pay['payslipdate']."' and location=".$loc['locationid']." and workslipid not in (".$slipids.") and paymentstatus=1");
	$prepay			=	$dbconnection->getField("payment_detail","sum(paidamount)","groupnumber=".$pay['groupnumber']." and workslipdate<'".$pay['payslipdate']."' and location=".$loc['locationid']."");
	
	$opening		=	$prework-$prepay-$baladvance;
	?>
	<tr <?php if($k%2==0) {?>style="background-color:#fefe;"<?php } else {?>style="background-color:#dede;"<?php } ?>>
		<td align="left"><?php echo $loc['locationname'];?></td>
		<td align="left" nowrap><?php echo date('d\-m\-Y h:i A',strtotime($pay['payslipdate']));?></td>
		<td align="left"><?php echo $sectionname;?></td>
		<td align="left"><?php echo $supervisor;?></td>
		<td align="left"><?php echo $hamali;?></td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo $opening;?></td>
		<td align="left"><?php echo $pay['payslipnumber'];?></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"><b><i class="fa fa-inr"></i> <?php echo $pay['totalamount'];?></b></td>
		<td align="center"></td>
		<td align="center">P.S.A.</td>
		<td align="center">Balance</td>
	</tr>
	<?php
	$rs_slip	=	$dbconnection->firequery("select a.*,b.workcode,b.narration,b.quantity,b.rate,b.total from workslip_tbl a left join workslip_detail b on b.workslipid=a.workslipid where a.workslipid in (".$slipids.") order by a.workslipid");	
	$prewrkslip	=	"";
	$newwrkslip	=	"";
	while($wrk=mysqli_fetch_assoc($rs_slip))
	{
	?>
	<tr <?php if($k%2==0) {?>style="background-color:#fefe;"<?php } else {?>style="background-color:#dede;"<?php } ?>>
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="center"></td>
		<td align="left">
		<?php 
		$newwrkslip	=	$wrk['workslipnumber'];
		if($prewrkslip!=$newwrkslip)
		{
		echo $wrk['workslipnumber'];
		$prewrkslip=$newwrkslip;
		}
		?>
		</td>
		<td align="center"><?php echo $wrk['workcode'];?></td>
		<td align="left" nowrap><?php echo $wrk['narration'];?></td>
		<td align="center"><?php echo $wrk['rate'];?></td>
		<td align="center"><?php echo $wrk['quantity'];?></td>
		<td align="center"><?php echo $wrk['total'];?></td>

		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
	</tr>
	<?php
	} //Workslip
	} //Payslip
	} //Location
	unset($loc);
	unset($rs_sel);
	?>
	<tr>
		<td colspan="5" align="right"><b>Total</b></td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo $totalopning;?></td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo $totalwbetween;?></td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo $totalpbetween;?></td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo $totalbalance;?></td>
	</tr>
	</table>
	<?php
}
?>
