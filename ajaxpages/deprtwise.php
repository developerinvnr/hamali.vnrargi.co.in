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
}
</style>
<?php
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
$ind		=	$_POST['ind'];
$locationid	=	$_POST['locationid'];
$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));
	?>
	<table style="width:100%; border:1px solid #fff; border-collapse:collapse;">
	<tr style="background-color:#008C40; color:white;">
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Opening For <?php echo date('d\-m\-Y',strtotime($frmdate));?></td>
		<td align="center" nowrap colspan="4" style="border:1px solid #fff;">Transactions Between <?php echo date('d\-m\-Y',strtotime($frmdate));?> To <?php echo date('d\-m\-Y',strtotime($todate));?></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Closing</td>
	</tr>
	<tr style="background-color:#008C40; color:white;">
		<td style="border:1px solid #fff;" align="left" nowrap>Hamali Group</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv. In Hand</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Bal. W.A.</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv.</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adjusted</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Work Slip</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Pay Slip</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv. In Hand</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Bal. W.A.</td>
	</tr>
	<?php
	$rs_loc	=	$dbconnection->firequery("select * from location_tbl where locationid=".$locationid."");
	while($loc=mysqli_fetch_assoc($rs_loc))
	{
	$rs_hamali	=	$dbconnection->firequery("select * from hamaligroup_tbl where locationname=".$loc['locationid']." order by groupname");
	while($hamali=mysqli_fetch_assoc($rs_hamali))
	{
	$advance	=	$dbconnection->getField("group_statement","sum(advanceamount)","recordtype='ADVANCE' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid']."");
	$balanced	=	$dbconnection->getField("group_statement","sum(frmadvance)","recordtype='PAYSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid']."");
	$adinhand	=	$advance-$balanced;
	
	$workamt	=	$dbconnection->getField("group_statement","sum(workslipamount)","recordtype='WORKSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid']."");
	$payamt	=	$dbconnection->getField("group_statement","sum(payslipamount)","recordtype='PAYSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid']."");
	$balancework	=	$workamt-$payamt;
	
	$advanceamount	=	$dbconnection->getField("group_statement","sum(advanceamount)","recordtype='ADVANCE' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid']."");

	$frmadvance	=	$dbconnection->getField("group_statement","sum(frmadvance)","recordtype='PAYSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid']."");

	$workslipamount	=	$dbconnection->getField("group_statement","sum(workslipamount)","recordtype='WORKSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid']."");
	
	$payslipamount	=	$dbconnection->getField("group_statement","sum(payslipamount)","recordtype='PAYSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid']."");
	
	?>
	<tr>
		<td><?php echo $hamali['groupname'];?></td>
		<td align="center"><?php echo $adinhand;?></td>
		<td align="center"><?php echo $balancework;?></td>		
		<td align="center"><?php echo $advanceamount;?></td>		
		<td align="center"><?php echo doubleval($frmadvance);?></td>		
		<td align="center"><?php echo doubleval($workslipamount);?></td>		
		<td align="center"><?php echo doubleval($payslipamount);?></td>		
		<td align="center"><?php echo doubleval($adinhand+$advanceamount-$frmadvance);?></td>		
		<td align="center"><?php echo doubleval($balancework+$workslipamount-$payslipamount);?></td>		
	</tr>
	<?php
	}
	}
	?>
	<tr><td colspan="10" align="center"><button type="button" class="btn" onclick="ClsWind(<?php echo $ind;?>)">CLOSE</button></td></tr>
	</table>
<?php
}
?>
