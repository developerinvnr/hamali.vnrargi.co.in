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
<table style="width:100%; border:1px solid #777;" border="1">
<tr style="background-color:#008C40; color:white;">
	<td colspan="7" align="center">DEPARTMENT WISE</td>
</tr>

<tr style="background-color:#008C40; color:white;">
	<td align="center">S.No.</td>
	<td>Department Name</td>
	<td align="center">O.B.P. On <?php echo date('d\-m\-Y',strtotime($frmdate));?></td>
	<td align="center">W.S.A. Between</td>
	<td align="center">P.S.A. Between</td>
	<td align="center">Balance Payment Amount</td>
	<td align="center">&nbsp;</td>
</tr>
<?php
$i=0;
$k=1000;
$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid=".$locationid." order by locationname");
while($row=mysqli_fetch_assoc($rs_sel))
{

$rs_dep	=	$dbconnection->firequery("select * from department_tbl order by departmentname");
while($dep=mysqli_fetch_assoc($rs_dep))
{

$i++;
$k++;
$rs_opwork	=	$dbconnection->firequery("select a.workslipamount as workamt from workslip_tbl a left join supervisor_tbl b on b.supervisorid=a.supervisorid left join location_tbl c on c.locationid=b.locationname where date(a.workslipdate)<'".$frmdate."' and c.locationid=".$row['locationid']." and b.departmentname=".$dep['departmentid']."");
$opwork=	0;
while($opw=mysqli_fetch_assoc($rs_opwork))
{
	$opwork		=	$opw['workamt'];
}
$rs_oppay	=	$dbconnection->firequery("select a.paidamount as paidamt from payment_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid left join location_tbl c on c.locationid=b.locationname where date(a.paymentdate)<'".$frmdate."' and c.locationid=".$row['locationid']." and b.departmentname=".$dep['departmentid']."");
$oppay	=	0;
while($opp=mysqli_fetch_assoc($rs_opwork))
{
	$oppay		=	$opp['paidamt'];
}
$opbalance	=	$opwork-$oppay;
?>
<tr>
	<td align="center"><?php echo $i;?></td>
	<td><?php echo $dep['departmentname'];?></td>
	<td align="center"><i class="fa fa-inr"></i> <?php echo $opbalance;?></td>
	<td align="center"> <i class="fa fa-inr"></i>
	<?php
	echo $wbetween =	doubleval($dbconnection->getField("workslip_tbl a left join supervisor_tbl b on b.supervisorid=a.supervisorid left join location_tbl c on c.locationid=b.locationname","sum(workslipamount)","date(a.workslipdate) between '".$frmdate."' and '".$todate."' and c.locationid=".$row['locationid']." and b.departmentname=".$dep['departmentid'].""));
	?>
	</td>
	<td align="center"> <i class="fa fa-inr"></i>
	<?php
	echo $pbetween =	doubleval($dbconnection->getField("payment_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid left join location_tbl c on c.locationid=b.locationname","sum(paidamount)","date(a.paymentdate) between '".$frmdate."' and '".$todate."' and c.locationid=".$row['locationid']." and b.departmentname=".$dep['departmentid'].""));
	?>
	</td>

	<td align="center"> <i class="fa fa-inr"></i> <?php echo doubleval($opbalance+$wbetween-$pbetween);?></td>
	<td align="center" style="padding:1px; width:100px;"><button type="button" class="btn" onclick="DepartmentWorkWise(<?php echo $k;?>,<?php echo $row['locationid'];?>,<?php echo $dep['departmentid'];?>,'<?php echo $frmdate;?>','<?php echo $todate;?>')">WORK WISE</button></td>
</tr>
<tr id="recd<?php echo $k;?>" style="display:none;" class="bg-ingo text-white wdrec"><td id="recdd<?php echo $k;?>" colspan="8" style="width:100%;"></td></tr>	
<?php
}
}
?>
</table>
<?php
}
?>
