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
		<td colspan="7">O.P.B. : Opening Balance Payment, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;W.S.A. : Work Slip Amount, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;P.S.A. : Payment Slip Amount</td>
		<td align="right"><a href="./printing/printplantwise.php?frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn" style="background-color:orange!important;">Print</button></a></td>
	</tr>
	<tr style="background-color:#008C40; color:white;">
		<td align="center">S.No.</td>
		<td>Plant Name</td>
		<td align="center">O.B.P. On <?php echo date('d\-m\-Y',strtotime($frmdate));?></td>
		<td align="center">W.S.A. Between</td>
		<td align="center">P.S.A. Between</td>
		<td align="center">Balance Payment Amount</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<?php
	$i=0;
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl order by locationname");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
	$rs_opwork	=	$dbconnection->firequery("select a.workslipamount as workamt from workslip_tbl a left join supervisor_tbl b on b.supervisorid=a.supervisorid left join location_tbl c on c.locationid=b.locationname where date(a.workslipdate)<'".$frmdate."' and c.locationid=".$row['locationid']."");
	$opwork=	0;
	while($opw=mysqli_fetch_assoc($rs_opwork))
	{
		$opwork		=	$opw['workamt'];
	}
	$rs_oppay	=	$dbconnection->firequery("select a.paidamount as paidamt from payment_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid left join location_tbl c on c.locationid=b.locationname where date(a.paymentdate)<'".$frmdate."' and c.locationid=".$row['locationid']."");
	$oppay	=	0;
	while($opp=mysqli_fetch_assoc($rs_opwork))
	{
		$oppay		=	$opp['paidamt'];
	}
	$opbalance	=	$opwork-$oppay;
	?>
	<tr>
		<td align="center"><?php echo $i;?></td>
		<td><?php echo $row['locationname'];?></td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo $opbalance;?></td>
		<td align="center"> <i class="fa fa-inr"></i>
		<?php
		echo $wbetween =	doubleval($dbconnection->getField("workslip_tbl a left join supervisor_tbl b on b.supervisorid=a.supervisorid left join location_tbl c on c.locationid=b.locationname","sum(workslipamount)","date(a.workslipdate) between '".$frmdate."' and '".$todate."' and c.locationid=".$row['locationid'].""));
		?>
		</td>
		<td align="center"> <i class="fa fa-inr"></i>
		<?php
		echo $pbetween =	doubleval($dbconnection->getField("payment_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid left join location_tbl c on c.locationid=b.locationname","sum(paidamount)","date(a.paymentdate) between '".$frmdate."' and '".$todate."' and c.locationid=".$row['locationid'].""));
		?>
		</td>

		<td align="center"> <i class="fa fa-inr"></i> <?php echo doubleval($opbalance+$wbetween-$pbetween);?></td>
		<td align="center" style="padding:1px; width:100px;"><button type="button" class="btn" onclick="DepartmentWise(<?php echo $i;?>,<?php echo $row['locationid'];?>,'<?php echo $frmdate;?>','<?php echo $todate;?>')">DEPARTMENT WISE</button></td>
		<td align="center" style="padding:1px; width:100px;"><button type="button" class="btn" onclick="WorkWise(<?php echo $i;?>,<?php echo $row['locationid'];?>,'<?php echo $frmdate;?>','<?php echo $todate;?>')">WORK WISE</button></td>
	</tr>
	<tr id="rec<?php echo $i;?>" style="display:none; padding:20px;" class="bg-ingo text-white wrec"><td id="recd<?php echo $i;?>" colspan="8" style="width:100%;"></td></tr>	
	<?php
	}
	?>
	</table>
	<br><br>
	<table style="width:100%; border:1px solid #fff; border-collapse:collapse;">
	<tr style="background-color:#008C40; color:white;">
		<td align="left" nowrap style="border:1px solid #fff;"></td>		
		<td align="left" nowrap style="border:1px solid #fff;"></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Opening For <?php echo date('d\-m\-Y',strtotime($frmdate));?></td>
		<td align="center" nowrap colspan="4" style="border:1px solid #fff;">Transactions Between <?php echo date('d\-m\-Y',strtotime($frmdate));?> To <?php echo date('d\-m\-Y',strtotime($todate));?></td>
		<td align="center" nowrap colspan="2" style="border:1px solid #fff;">Closing</td>
	</tr>
	<tr style="background-color:#008C40; color:white;">
		<td style="border:1px solid #fff;" align="left" nowrap>Location</td>
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
	$rs_loc	=	$dbconnection->firequery("select * from location_tbl order by locationname");
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
		<td><?php echo $loc['locationname'];?></td>
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
	</table>
	<?php
}
?>
