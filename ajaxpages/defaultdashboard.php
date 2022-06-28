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
$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));
	?>
	<table style="width:100%; border:1px solid #777;" border="1">
	<tr style="background-color:#008C40; color:white;">
		<td align="left" nowrap style="border:1px solid #fff; border-left:1px solid #008C40;"></td>		
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Opening For <?php echo date('d\-m\-Y',strtotime($frmdate));?></td>
		<td align="center" nowrap colspan="4" style="border:1px solid #fff;">Transactions Between <?php echo date('d\-m\-Y',strtotime($frmdate));?> To <?php echo date('d\-m\-Y',strtotime($todate));?></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Closing</td>
		<td></td>
	</tr>
	<tr style="background-color:#008C40; color:white;">
		<td style="border:1px solid #fff; border-left:1px solid #008C40;" align="left" nowrap>Location</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv. In Hand</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Bal. W.A.</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv.</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adjusted</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Work Slip</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Pay Slip</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Adv. In Hand</td>
		<td style="border:1px solid #fff;" align="center" nowrap>Bal. W.A.</td>
		<td></td>
	</tr>
	<?php
	$i=0;
	if($_SESSION['datadetail'][0]['ispayment']=="PAYMENT")
	{
		$rs_loc	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$_SESSION['datadetail'][0]['loca'].") order by locationname");
	}
	else
	{
		$rs_loc	=	$dbconnection->firequery("select * from location_tbl order by locationname");
	}
	
	while($loc=mysqli_fetch_assoc($rs_loc))
	{
	$i++;
	$advance	=	number_format($dbconnection->getField("group_statement","sum(advanceamount)","recordtype='ADVANCE' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid'].""),'2','.','');
	$balanced	=	number_format($dbconnection->getField("group_statement","sum(frmadvance)","recordtype='PAYSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid'].""),'2','.','');
	$adinhand	=	$advance-$balanced;
	
	$workamt	=	number_format($dbconnection->getField("group_statement","sum(workslipamount)","recordtype='WORKSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid'].""),'2','.','');
	$payamt	=	number_format($dbconnection->getField("group_statement","sum(payslipamount)","recordtype='PAYSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid'].""),'2','.','');
	$balancework	=	$workamt-$payamt;
	
	$advanceamount	=	number_format($dbconnection->getField("group_statement","sum(advanceamount)","recordtype='ADVANCE' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid'].""),'2','.','');

	$frmadvance	=	number_format($dbconnection->getField("group_statement","sum(frmadvance)","recordtype='PAYSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid'].""),'2','.','');

	$workslipamount	=	number_format($dbconnection->getField("group_statement","sum(workslipamount)","recordtype='WORKSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid'].""),'2','.','');
	
	$payslipamount	=	number_format($dbconnection->getField("group_statement","sum(payslipamount)","recordtype='PAYSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid'].""),'2','.','');
	
	?>
	<tr>
		<td><?php echo $loc['locationname'];?></td>
		<td align="center"><?php echo number_format(doubleval($adinhand),'2','.','');?></td>
		<td align="center"><?php echo number_format(doubleval($balancework),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($advanceamount),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($frmadvance),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($workslipamount),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($payslipamount),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($adinhand+$advanceamount-$frmadvance),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($balancework+$workslipamount-$payslipamount),'2','.','');?></td>		
		<td align="center" style="padding:1px; width:100px;"><button type="button" class="btn" onclick="DeprtWise(<?php echo $i;?>,<?php echo $loc['locationid'];?>,'<?php echo $frmdate;?>','<?php echo $todate;?>')">HAMALI GROUP WISE</button></td>
	</tr>
	<tr id="rec<?php echo $i;?>" style="display:none; padding:20px;" class="bg-ingo text-white wrec"><td id="recd<?php echo $i;?>" colspan="10" style="width:100%;"></td></tr>	
	<?php
	}
	?>
	
	</table>
	
	
	
	
	
	<br><br>
	<table style="width:100%; border:1px solid #fff; border-collapse:collapse;">
	<tr style="background-color:#008C40; color:white;">
		<td align="left" nowrap style="border:1px solid #fff; border-left:1px solid #008C40;"></td>		
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Opening For <?php echo date('d\-m\-Y',strtotime($frmdate));?></td>
		<td align="center" nowrap colspan="4" style="border:1px solid #fff;">Transactions Between <?php echo date('d\-m\-Y',strtotime($frmdate));?> To <?php echo date('d\-m\-Y',strtotime($todate));?></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Closing</td>
	</tr>
	<tr style="background-color:#008C40; color:white;">
		<td style="border:1px solid #fff; border-left:1px solid #008C40;" align="left" nowrap>Location</td>
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
	if($_SESSION['datadetail'][0]['ispayment']=="PAYMENT")
	{
		$rs_loc	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$_SESSION['datadetail'][0]['loca'].") order by locationname");
	}
	else
	{
		$rs_loc	=	$dbconnection->firequery("select * from location_tbl order by locationname");
	}
	while($loc=mysqli_fetch_assoc($rs_loc))
	{
	$rs_hamali	=	$dbconnection->firequery("select * from hamaligroup_tbl where locationname=".$loc['locationid']." order by groupname");
	while($hamali=mysqli_fetch_assoc($rs_hamali))
	{
	$advance	=	number_format($dbconnection->getField("group_statement","sum(advanceamount)","recordtype='ADVANCE' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid'].""),'2','.','');
	$balanced	=	number_format($dbconnection->getField("group_statement","sum(frmadvance)","recordtype='PAYSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid'].""),'2','.','');
	$adinhand	=	$advance-$balanced;
	
	$workamt	=	number_format($dbconnection->getField("group_statement","sum(workslipamount)","recordtype='WORKSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid'].""),'2','.','');
	$payamt	=	number_format($dbconnection->getField("group_statement","sum(payslipamount)","recordtype='PAYSLIP' and date(slipdate)<'".$frmdate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid'].""),'2','.','');
	$balancework	=	$workamt-$payamt;
	
	$advanceamount	=	number_format($dbconnection->getField("group_statement","sum(advanceamount)","recordtype='ADVANCE' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid'].""),'2','.','');

	$frmadvance	=	number_format($dbconnection->getField("group_statement","sum(frmadvance)","recordtype='PAYSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid'].""),'2','.','');

	$workslipamount	=	number_format($dbconnection->getField("group_statement","sum(workslipamount)","recordtype='WORKSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid'].""),'2','.','');
	
	$payslipamount	=	number_format($dbconnection->getField("group_statement","sum(payslipamount)","recordtype='PAYSLIP' and date(slipdate)>='".$frmdate."' and date(slipdate)<='".$todate."' and location=".$loc['locationid']." and groupid=".$hamali['hgid'].""),'2','.','');
	
	?>
	<tr>
		<td><?php echo $loc['locationname'];?></td>
		<td><?php echo $hamali['groupname'];?></td>
		<td align="center"><?php echo number_format($adinhand,'2','.','');?></td>
		<td align="center"><?php echo number_format($balancework,'2','.','');?></td>		
		<td align="center"><?php echo number_format($advanceamount,'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($frmadvance),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($workslipamount),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($payslipamount),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($adinhand+$advanceamount-$frmadvance),'2','.','');?></td>		
		<td align="center"><?php echo number_format(doubleval($balancework+$workslipamount-$payslipamount),'2','.','');?></td>		
	</tr>
	<?php
	}
	}
	?>
	</table>
	<?php
}
?>
