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
	?>
	<table style="width:100%; border:1px solid #fff; border-collapse:collapse;">
	<tr style="background-color:#008C40; color:white;">
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Opening</td>
		<td align="center" nowrap colspan="3" style="border:1px solid #fff;">Transaction</td>
		<td align="center" nowrap style="border:1px solid #fff;"></td>
		<td align="center" nowrap style="border:1px solid #fff;"></td>
		<td align="center" nowrap style="border:1px solid #fff;"></td>
		<td align="center" nowrap style="border:1px solid #fff;"></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Closing</td>
	</tr>
	<tr style="background-color:#008C40; color:white;">
		<td style="border:1px solid #fff;" align="left" nowrap>Location</td>
		<td style="border:1px solid #fff;" align="left" nowrap>Hamali Group</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv. In Hand</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Bal. W.A.</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Reference</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Type</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Transaction Date</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv.</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adjusted</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Work Slip</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Pay Slip</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv. In Hand</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Bal. W.A.</td>
	</tr>
	<?php
	$k=0;
	if($locations=="")
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$_SESSION['datadetail'][0]['loca'].") order by locationname");
	else
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$locations.") order by locationname");
	while($loc=mysqli_fetch_assoc($rs_sel))
	{
	if($hamaligroups=="")
	$rs_statement	=	$dbconnection->firequery("select * from group_statement where location=".$loc['locationid']." and slipdate between '".$frmdate."' and '".$todate."' order by slipdate, groupname");
	else
	$rs_statement	=	$dbconnection->firequery("select * from group_statement where location=".$loc['locationid']." and groupid in (".$hamaligroups.") and slipdate between '".$frmdate."' and '".$todate."' order by slipdate,groupname");		
	while($sts=mysqli_fetch_assoc($rs_statement))
	{
	$advance	=	number_format($dbconnection->getField("group_statement","sum(advanceamount)","recordtype='ADVANCE' and slipdate<'".$sts['slipdate']."' and location=".$loc['locationid']." and groupid=".$sts['groupid'].""),'2','.','');
	$balanced	=	number_format($dbconnection->getField("group_statement","sum(frmadvance)","recordtype='PAYSLIP' and slipdate<'".$sts['slipdate']."' and location=".$loc['locationid']." and groupid=".$sts['groupid'].""),'2','.','');
	$adinhand	=	$advance-$balanced;
	
	$workamt	=	number_format($dbconnection->getField("group_statement","sum(workslipamount)","recordtype='WORKSLIP' and slipdate<'".$sts['slipdate']."' and location=".$loc['locationid']." and groupid=".$sts['groupid'].""),'2','.','');
	$payamt	=	number_format($dbconnection->getField("group_statement","sum(payslipamount)","recordtype='PAYSLIP' and slipdate<'".$sts['slipdate']."' and location=".$loc['locationid']." and groupid=".$sts['groupid'].""),'2','.','');
	$balancework	=	$workamt-$payamt;
	?>
	<tr>
		<td><?php echo $loc['locationname'];?></td>
		<td><?php echo $sts['groupname'];?></td>
		<td align="center"><?php echo number_format($adinhand,'2','.','');?></td>
		<td align="center"><?php echo number_format($balancework,'2','.','');?></td>		
		<td align="center"><?php echo $sts['slipnumber'];?></td>
		<td align="center"><?php echo $sts['recordtype'];?></td>
		<td align="center" nowrap><?php echo date('d\-m\-Y h:i A',strtotime($sts['slipdate']));?></td>
		<td align="center"><?php echo number_format($sts['advanceamount'],'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($sts['frmadvance']),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($sts['workslipamount']),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($sts['payslipamount']),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($adinhand+$sts['advanceamount']-$sts['frmadvance']),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($balancework+$sts['workslipamount']-$sts['payslipamount']),'2','.','');?></td>		
	</tr>
	<?php
	} //Statement
	} //Location
	unset($loc);
	unset($rs_sel);
	?>
	</table>
	<?php
}
?>
