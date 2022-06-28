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
	$centerid		=	$_POST['centername'];
	$testid			=	$_POST['testname'];	
	$frmdate		=	date('Y\-m\-d',strtotime($_POST['frmdate']));
	$todate			=	date('Y\-m\-d',strtotime($_POST['todate']));
	
	$query	=	"";
	$qry	=	array();
	$qry[count($qry)]	=	"franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']."";
	if($centerid!="")
	{
		$qry[count($qry)]	=	"centerid=".$centerid."";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
	}
	if($testid=="ALL")
	{
		if(count($qry)>0)
		{
			$rs_test	=	$dbconnection->firequery("select testid,cpt,testname,(select IFNULL(sum(cnts),0) from totalcentertest_view where testid=a.testid and ".$str." and testdate between '".$frmdate."' and '".$todate."') as totaltest from test_tbl a order by testname");
		}
		else
		{
			$rs_test	=	$dbconnection->firequery("select testid,cpt,testname,(select IFNULL(sum(cnts),0) from totalcentertest_view where testid=a.testid and testdate between '".$frmdate."' and '".$todate."') as totaltest from test_tbl a order by testname");
		}
	}
	else if($testid!="")
	{
		if(count($qry)>0)
		{
			$rs_test	=	$dbconnection->firequery("select testid,cpt,testname,(select IFNULL(sum(cnts),0) from totalcentertest_view where testid=a.testid and ".$str." and testdate between '".$frmdate."' and '".$todate."') as totaltest from test_tbl a where a.testid=".$testid." order by testname");	
		}
		else
		{
			$rs_test	=	$dbconnection->firequery("select testid,cpt,testname,(select IFNULL(sum(cnts),0) from totalcentertest_view where testid=a.testid and testdate between '".$frmdate."' and '".$todate."') as totaltest from test_tbl a where a.testid=".$testid." order by testname");	
		}
	}
	$i=0;
	$total	=	0;
	$cpt=0;
	while($row=mysqli_fetch_assoc($rs_test))
	{
		if($row['totaltest']>0)
		{
		$i++;
		if($i==1)
		{
		?>
		<tr>
			<td colspan="5" align="right"><span class="label label-info arrowed-in arrowed-in-right" style="float:left;"></span><a href="./printing/printctwstest.php?centerid=<?php echo $centerid;?>&franchiseid=<?php echo $franchiseid;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a></td>
		</tr>
		<tr>
			<th style="padding:5px; width:50px; text-align:center;">Sno</th>
			<th style="padding:5px;">Test Name</th>
			<th style="padding:5px; text-align:center; width:150px;">CPT</th>
			<th style="padding:5px; text-align:center; width:150px;">Number Of Test</th>
			<th style="padding:5px; text-align:center; width:150px;">Total CPT</th>
		</tr>
		<?php
		}	
		?>
		<tr>
			<td align="center"><?php echo $i;?></td>
			<td><?php echo $row['testname'];?></td>
			<td style="text-align:center;"><?php echo $row['cpt'];?></td>
			<td style="text-align:center;"><?php echo $row['totaltest'];?></td>
			<td style="text-align:center;"><?php echo ceil($row['totaltest']*$row['cpt']);?></td>		
		</tr>
		<?php
		$cpt=$cpt+ceil($row['totaltest']*$row['cpt']);
		$total=$total+$row['totaltest'];
		}
	}
	?>
	<tr style="font-size:18px;">
		<td colspan="3" align="right"><b>Total Number Of Test</b>&nbsp;</td>
		<td align="center"><b><?php echo $total;?></b></td>
		<td align="center"><b><?php echo $cpt;?></b></td>		
	</tr>
	<?php
}
?>
<script>
	$(".label-info").text("Total Number Of Test = <?php echo $total;?>");
</script>
