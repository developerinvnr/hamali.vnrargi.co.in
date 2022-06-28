<?php
@session_start();
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

$t=1;
require("../../validation/validation.php");
include("../../enc/urlenc.php");
include("../../db/db_connect.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$dd 	    = Date("d");
$mm			= Date("m");
$yy			= Date("Y");
$currentDate= $yy."-".$mm."-".$dd;

$staffname	=	$_REQUEST['staffname'];
$headid		=	$_REQUEST['headid'];
$frmdate	=	date('Y\-m\-d',strtotime($_REQUEST['frmdate']));
$todate		=	date('Y\-m\-d',strtotime($_REQUEST['todate']));

$stname		=	$dbconnection->getField("staff_tbl","staffname","staffid=".$staffname."");
$centers	=	$dbconnection->getField("staff_tbl","collectioncenter","staffid=".$staffname."");
$rs_cnt		=	$dbconnection->firequery("select centerid,centername from center_tbl where centerid in (".$centers.") order by centername");
$i=0;
while($cnt=mysqli_fetch_assoc($rs_cnt))
{
?>
<tr class="">
	<td colspan="8">
	<b><?php echo strtoupper($cnt['centername']);?> [ <?php echo strtoupper($stname);?> ]</b>
	<?php
	$received	=	$dbconnection->getField("customerreceipt_tbl","sum(payingamount)","date(paymentdate)<'".$frmdate."' and staffid=".$staffname." and centerid=".$cnt['centerid']."");
	$paid		=	$dbconnection->getField("centerreceiving_tbl","sum(receivingamount)","date(receivingdate)<'".$frmdate."' and staffid=".$staffname." and centerid=".$cnt['centerid']."");			
	$closing	=	$received-$paid;
	$ldate	=	date('Y\-m\-d',strtotime($frmdate));
	$ldate	=	date('Y\-m\-d', strtotime($ldate.'-1 day'));
	?>
	<label style="font-size:14px; font-weight:bold; float:right;">CLOSING BALANCE FOR <?php echo date('d\-m\-Y',strtotime($ldate));?> = <i class="fa fa-inr"></i> <?php echo $closing;?></label>
	</td>
</tr>
<?php
	$startdate	=	$frmdate;
	$i=0;
	while(strtotime($startdate)<=strtotime($todate))
	{
		$i++;
		if($i==1)
		{
		?>
		<tr style="font-weight:bold;">
			<td width="50px;" align="center">S.No.</td>	
			<td align="center">Date</td>				
			<td align="center">Total Mrp</td>					
			<td align="center">Total After Discount</td>					
			<td align="center">Total Received</td>
			<td align="center">In Hand</td>			
			<td align="center">Total Paid</td>
			<td align="center">Closing Balance</td>				
		</tr>
		<?php
		}
		$rs_led	=	$dbconnection->firequery("select (select sum(totalamount) from customertest_tbl where date(creationdate)='".$startdate."' and staffid=".$staffname." and centerid=".$cnt['centerid'].") as totalamount,(select sum(afterdiscount) from customertest_tbl where date(creationdate)='".$startdate."' and staffid=".$staffname." and centerid=".$cnt['centerid'].") as afterdiscount,(select sum(payingamount) from customerreceipt_tbl where date(paymentdate)='".$startdate."' and staffid=".$staffname." and centerid=".$cnt['centerid'].") as received,(select sum(receivingamount) from centerreceiving_tbl where date(receivingdate)='".$startdate."' and staffid=".$staffname." and centerid=".$cnt['centerid'].") as paid");
		$j=0;
		while($roo=mysqli_fetch_assoc($rs_led))
		{
			?>
			<tr>
				<td align="center"><?php echo $i;?></td>
				<td align="center"><?php echo date('d\-m\-Y',strtotime($startdate));?></td>				
				<td align="center"><?php echo doubleval($roo['totalamount']);?></td>				
				<td align="center"><?php echo doubleval($roo['afterdiscount']);?></td>				
				<td align="center"><?php echo doubleval($roo['received']);?></td>
				<td align="center"><?php echo $inhand=doubleval($roo['received']+$closing);?></td>
				<td align="center"><?php echo doubleval($roo['paid']);?></td>
				<td align="center"><?php echo $closing=doubleval($inhand)-doubleval($roo['paid']);?></td>				
			</tr>
			<?php
		}
		$startdate	=	date('Y\-m\-d', strtotime($startdate.'+1 day'));		
	}
?>
<tr style="line-height:25px;"><td colspan="8">&nbsp;</td></tr>
<?php
}

?>


