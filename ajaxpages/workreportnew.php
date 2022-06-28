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
	$supervisors	=	trim($_POST['supervisors']);
	$workcodes		=	trim($_POST['workcodes']);
	?>
	<table style="width:100%; border:1px solid #777;" border="1">
	<tr style="background-color:#008C40; color:white;">
		<td align="left" nowrap>Location</td>
		<td align="center" nowrap>Date</td>
		<td align="left" nowrap>Section</td>
		<td align="left" nowrap>Supervisor</td>
		<td align="left" nowrap>Hamali Group</td>
		<td align="center" nowrap>Pay Slip Number</td>
		<td align="center" nowrap>Work Slip Number</td>
		<td align="left" nowrap>Work Code</td>
		<td align="left" nowrap>Narratin</td>
		<td align="center" nowrap>Rate</td>
		<td align="center" nowrap>Quantity</td>
		<td align="left" nowrap>Work Slip Amount</td>
	</tr>
	<?php
	$k=0;
	if($locations=="")
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl order by locationname");
	else
	$rs_sel	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$locations.") order by locationname");
	while($loc=mysqli_fetch_assoc($rs_sel))
	{
	$qry	=	array();
	$str	=	"";
	$grpby	=	"";
	if($departments!="")
	{
		$qry[count($qry)]="department in (".$departments.")";
	}
	if($supervisors!="")
	{
		$qry[count($qry)]="supervisorid in (".$supervisors.")";
	}
	if($hamaligroups!="")
	{
		$qry[count($qry)]="groupnumber in (".$hamaligroups.")";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query	= " and ".$str."";	
	}
	$rs_work	=	$dbconnection->firequery("select * from workslip where location=".$loc['locationid']." and workslipdate between '".$frmdate."' and '".$todate."' ".$query." order by workslipdate");
	while($wrk=mysqli_fetch_assoc($rs_work))
	{
	$k++;
	$sectionname	=	$dbconnection->getField("section_tbl","sectionname","FIND_IN_SET(".$wrk['department'].",departmentname)>0");	
	?>
	<tr <?php if($k%2==0) {?>style="background-color:#fefe;"<?php } else {?>style="background-color:#dede;"<?php } ?>>
		<td align="left"><?php echo $loc['locationname'];?></td>
		<td align="left" nowrap><?php echo date('d\-m\-Y h:i A',strtotime($wrk['workslipdate']));?></td>
		<td align="left"><?php echo $sectionname;?></td>
		<td align="left" nowrap><?php echo $wrk['firstname'];?> <?php echo $wrk['lastname'];?></td>
		<td align="left" nowrap><?php echo $wrk['groupname'];?></td>
		<td align="center"><?php if($wrk['payslipnumber']!="") echo $wrk['payslipnumber']; else echo "PENDING";?></td>
		<td align="center"><?php echo $wrk['workslipnumber'];?></td>		
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="center"></td>
		<td align="center"><b><i class="fa fa-inr"></i> <?php echo $wrk['workslipamount'];?></b></td>
	</tr>
	<?php
	$rs_slip	=	$dbconnection->firequery("select a.*,b.workcode,b.narration,b.quantity,b.rate,b.total from workslip_tbl a left join workslip_detail b on b.workslipid=a.workslipid where a.workslipid=".$wrk['workslipid']." order by a.workslipid");	
	$prewrkslip	=	"";
	$newwrkslip	=	"";
	while($wrkk=mysqli_fetch_assoc($rs_slip))
	{
	?>
	<tr <?php if($k%2==0) {?>style="background-color:#fefe;"<?php } else {?>style="background-color:#dede;"<?php } ?>>
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="center"></td>
		<td align="center"><?php echo $wrk['workslipnumber'];?></td>
		<td align="center"><?php echo $wrkk['workcode'];?></td>
		<td align="left" nowrap><?php echo $wrkk['narration'];?></td>
		<td align="center" nowrap><i class="fa fa-inr"></i> <?php echo $wrkk['rate'];?></td>
		<td align="center"><?php echo $wrkk['quantity'];?></td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo $wrkk['total'];?></td>
	</tr>
	<?php
	} //Workslip
	} //Payslip
	} //Location
	unset($loc);
	unset($rs_sel);
	?>
	</table>
	<?php
}
?>
