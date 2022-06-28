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
	border:1px solid #000;
	vertical-align:top;
}
</style>
<?php
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$frmdate		=	date('Y\-m\-d H:i:s',strtotime($_POST['frmdate']));
	$todate			=	date('Y\-m\-d H:i:s',strtotime($_POST['todate']));
	$locations		=	trim($_POST['locations']);
	$hamaligroups	=	trim($_POST['hamaligroups']);
	$departments	=	trim($_POST['departments']);
	$supervisors	=	trim($_POST['supervisors']);
	
	?>
	<table style="width:100%; border:1px solid #fff; border-collapse:collapse;">
	<tr style="background-color:#008C40; color:white;">
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="left" nowrap style="border:1px solid #fff;"></td>		
		<td align="left" nowrap style="border:1px solid #fff;"></td>				
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Opening</td>
		<td align="center" nowrap colspan="8" style="border:1px solid #fff;">Transaction</td>
		<td align="center" nowrap colspan="4" style="border:1px solid #fff;">Financial Transaction</td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Closing</td>
	</tr>
	<tr style="background-color:#008C40; color:white;">
		<td style="border:1px solid #fff;" align="center" nowrap>S.No.</td>
		<td style="border:1px solid #fff;" align="left" nowrap>Location</td>
		<td style="border:1px solid #fff;" align="left" nowrap>Department</td>
		<td style="border:1px solid #fff;" align="left" nowrap>Section</td>
		<td style="border:1px solid #fff;" align="left" nowrap>Supervisor</td>
		<td style="border:1px solid #fff;" align="left" nowrap>Hamali Group</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv. In Hand</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Bal. W.A.</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Type</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Transaction Date</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Slip Number</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Work Code</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Narration</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Rate</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Quantity</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Total</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv.</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adjusted</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Work Slip</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Pay Slip</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv. In Hand</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Bal. W.A.</td>
	</tr>
	<?php
	$k=0;
	$totadvance		=	0;
	$totadjusted	=	0;
	$totalwork		=	0;
	$totalpay		=	0;
	$qry	=	array();
	if($departments!="")
	{
		$qry[count($qry)]="a.department in (".$departments.")";
	}
	if($supervisors!="")
	{
		$qry[count($qry)]="a.supervisor in (".$supervisors.")";
	}
	if($hamaligroups!="")
	{
		$qry[count($qry)]="a.groupid in (".$hamaligroups.")";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query	= " and ".$str."";	
	}
	$i=0;
	if($locations=="")
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$_SESSION['datadetail'][0]['loca'].") order by locationname");
	else
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$locations.") order by locationname");
	while($loc=mysqli_fetch_assoc($rs_sel))
	{
	$rs_statement	=	$dbconnection->firequery("select a.*,b.firstname,b.lastname,c.departmentname from group_statement a left join supervisor_tbl b on b.supervisorid=a.supervisor left join department_tbl c on c.departmentid=a.department where a.location=".$loc['locationid']." and a.slipdate between '".$frmdate."' and '".$todate."' ".$query." order by a.slipdate,a.groupname");
	while($sts=mysqli_fetch_assoc($rs_statement))
	{
	$i++;
	$advance	=	number_format($dbconnection->getField("group_statement","sum(advanceamount)","recordtype='ADVANCE' and slipdate<'".$sts['slipdate']."' and location=".$loc['locationid']." and groupid=".$sts['groupid'].""),'2','.','');
	
	$balanced	=	number_format($dbconnection->getField("group_statement","sum(frmadvance)","recordtype='PAYSLIP' and slipdate<'".$sts['slipdate']."' and location=".$loc['locationid']." and groupid=".$sts['groupid'].""),'2','.','');
	
	$adinhand	=	$advance-$balanced;
	
	$workamt	=	number_format($dbconnection->getField("group_statement","sum(workslipamount)","recordtype='WORKSLIP' and slipdate<'".$sts['slipdate']."' and location=".$loc['locationid']." and groupid=".$sts['groupid'].""),'2','.','');
	
	$payamt	=	number_format($dbconnection->getField("group_statement","sum(payslipamount)","recordtype='PAYSLIP' and slipdate<'".$sts['slipdate']."' and location=".$loc['locationid']." and groupid=".$sts['groupid'].""),'2','.','');
	
	$balancework	=	$workamt-$payamt;
	?>
	<tr>
		<td nowrap align="center"><?php echo $i;?></td>
		<td nowrap><?php echo $loc['locationname'];?></td>
		<td nowrap><?php echo $dbconnection->getField("section_tbl","sectionname","FIND_IN_SET(".$sts['department'].",departmentname)>0");?></td>
		<td nowrap><?php echo $sts['departmentname'];?></td>
		<td nowrap><?php echo $sts['firstname'];?> <?php echo $sts['lastname'];?></td>
		<td nowrap><?php echo $sts['groupname'];?></td>
		<td nowrap align="center"><?php echo number_format($adinhand,'2','.','');?></td>
		<td nowrap align="center"><?php echo number_format($balancework,'2','.','');?></td>		
		<td nowrap align="center"><?php if($sts['paymenttype']=='OLD') echo "BALANCE PAYMENT"; else echo $sts['recordtype'];?></td>
		<td nowrap align="center" nowrap><?php echo date('d\-m\-Y h:i A',strtotime($sts['slipdate']));?></td>
		<td nowrap align="center">
			<?php 
			if($sts['recordtype']=="WORKSLIP")
			{
			?>
			<label onclick="GetWorkSlip(<?php echo $sts['slipid'];?>)" style="font-size:12px;"><?php echo $sts['slipnumber'];?></label>
			<?php
			}
			else if($sts['recordtype']=="PAYSLIP")
			{
			?>
			<label onclick="GetPaySlip('<?php echo $sts['slipid'];?>')" style="font-size:12px;"><?php echo $sts['slipnumber'];?></label>
			<?php
			}
			else
			{
				echo $sts['slipnumber'];
			}
			?>
		</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td nowrap align="center"><?php echo number_format($sts['advanceamount'],'2','.','');?></td>		
		<td nowrap align="center"><?php echo number_format(doubleval($sts['frmadvance']),'2','.','');?></td>		
		<td nowrap align="center"><?php echo number_format(doubleval($sts['workslipamount']),'2','.','');?></td>		
		<td nowrap align="center"><?php echo number_format(doubleval($sts['payslipamount']),'2','.','');?></td>		
		<td nowrap align="center"><?php echo number_format(doubleval($adinhand+$sts['advanceamount']-$sts['frmadvance']),'2','.','');?></td>		
		<td nowrap align="center"><?php echo number_format(doubleval($balancework+$sts['workslipamount']-$sts['payslipamount']),'2','.','');?></td>		
	</tr>
	<?php
	$totadvance		=	$totadvance+number_format(doubleval($sts['advanceamount']),'2','.','');
	$totadjusted	=	$totadjusted+number_format(doubleval($sts['frmadvance']),'2','.','');
	$totalwork		=	$totalwork+number_format(doubleval($sts['workslipamount']),'2','.','');
	$totalpay		=	$totalpay+number_format(doubleval($sts['payslipamount']),'2','.','');
	if($sts['recordtype']=="WORKSLIP")
	{
		$rs_work	=	$dbconnection->firequery("select * from workslip_detail where workslipid=".$sts['slipid']." order by workcode");
		while($work=mysqli_fetch_assoc($rs_work))
		{
			$i++;
		?>
		<tr>
		<td nowrap align="center"><?php echo $i;?></td>		
		<td nowrap><?php echo $loc['locationname'];?></td>
		<td nowrap><?php echo $dbconnection->getField("section_tbl","sectionname","FIND_IN_SET(".$sts['department'].",departmentname)>0");?></td>
		<td nowrap><?php echo $sts['departmentname'];?></td>
		<td nowrap><?php echo $sts['firstname'];?> <?php echo $sts['lastname'];?></td>
		<td><?php echo $sts['groupname'];?></td>
			<td></td>
			<td></td>
			<td nowrap align="center"><?php if($sts['paymenttype']=='OLD') echo "BALANCE PAYMENT"; else echo $sts['recordtype'];?></td>
			<td nowrap align="center" nowrap><?php echo date('d\-m\-Y h:i A',strtotime($sts['slipdate']));?></td>
			<td nowrap align="center">
			<?php 
			if($sts['recordtype']=="WORKSLIP")
			{
			?>
			<label onclick="GetWorkSlip(<?php echo $sts['slipid'];?>)" style="font-size:12px;"><?php echo $sts['slipnumber'];?></label>
			<?php
			}
			else if($sts['recordtype']=="PAYSLIP")
			{
			?>
			<label onclick="GetPaySlip('<?php echo $sts['slipnumber'];?>')" style="font-size:12px;"><?php echo $sts['slipnumber'];?></label>
			<?php
			}
			else
			{
				echo $sts['slipnumber'];
			}
			?>
			</td>
			<td align="center" nowrap><?php echo $work['workcode'];?></td>
			<td nowrap><?php echo $work['narration'];?></td>
			<td nowrap align="center"><?php echo $work['rate'];?></td>
			<td nowrap align="center"><?php echo $work['quantity'];?></td>
			<td nowrap align="center"><?php echo number_format($work['total'],'2','.','');?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php
		$totquantity	=	$totquantity+doubleval($work['quantity']);		
		}
	} // Workslip Detail
	} //Statement
	} //Location
	unset($loc);
	unset($rs_sel);
	?>
	<tr>
		<td colspan="14" align="right"><b>Total Transaction Detail</b></td>
		<td align="center"><b><?php echo $totquantity;?></b></td>
		<td align="center">&nbsp;</td>
		<td align="center"><b><?php echo number_format($totadvance,'2','.','');?></b></td>
		<td align="center"><b><?php echo number_format($totadjusted,'2','.','');?></b></td>
		<td align="center"><b><?php echo number_format($totalwork,'2','.','');?></b></td>
		<td align="center"><b><?php echo number_format($totalpay,'2','.','');?></b></td>
		<td colspan="2"></td>
	</tr>
	</table>
	<?php
}
?>
