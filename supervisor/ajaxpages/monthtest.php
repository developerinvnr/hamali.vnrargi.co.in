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
if(isset($_SESSION['centerdetail'][0]['sessionid']))
{
	$mtname		=	str_pad($_POST['mtname'], 2, '0', STR_PAD_LEFT);
	$yr			=	$_POST['yr'];
	$frmdate	=	date(''.$yr.'\-'.$mtname.'\-01');
	$todate		=	date('Y-m-t', strtotime($frmdate));
	$firstdate	=	date('d',strtotime(date(''.$yr.'\-'.$mtname.'\-01')));
	$lastdate	=	date('t',strtotime($todate));
	$i			=	0;
	$fdate		=	$firstdate;
	$centerid	=	$_SESSION['centerdetail'][0]['sessionid'];
	$franchiseid=	$_SESSION['centerdetail'][0]['franchiseid'];	

	//$firstdate++;
	//$firstdate	=	str_pad($firstdate, 2, '0', STR_PAD_LEFT);
	$pagesearch	=	$_POST['pagesearch'];
	if($pagesearch=="")
	$rs_test	=	$dbconnection->firequery("select distinct(a.testid),b.testname from totalcentertest_view a inner join test_tbl b on b.testid=a.testid where a.centerid=".$_SESSION['centerdetail'][0]['sessionid']." and a.franchiseid=".$_SESSION['centerdetail'][0]['franchiseid']."");
	else
	$rs_test	=	$dbconnection->firequery("select distinct(a.testid),b.testname from totalcentertest_view a inner join test_tbl b on b.testid=a.testid where a.centerid=".$_SESSION['centerdetail'][0]['sessionid']." and a.franchiseid=".$_SESSION['centerdetail'][0]['franchiseid']." and b.testname like '%$pagesearch%'");
	
	while($row=mysqli_fetch_assoc($rs_test))
	{
		$i++;
		if($i==1)
		{
		$total	=	array();
		?>
		<tr>
			<th style="padding:3px;">Test Name</th>
			<?php
			while($firstdate<=$lastdate)
			{
			?>
			<th style="padding:2px; text-align:center;"><?php echo intval($firstdate);?></th>
			<?php
			$firstdate++;
			}
			?>
		</tr>
		<?php
		}
		$firstdate	=	$fdate;		
		?>
		<tr>
			<td style="width:150px;"><?php echo $row['testname'];?></td>
			<?php
			while($firstdate<=$lastdate)
			{
				$rdate	=	date(''.$yr.'\-'.$mtname.'\-'.$firstdate.'');
				if(strtotime(date('Y\-m\-d'))>=strtotime($rdate))
				{
				$ct	=	0;
				?>
				<td style="padding:2px; text-align:center;">
					<?php $ct	= intval($dbconnection->getField("totalcentertest_view","cnts","centerid=".$centerid." and franchiseid=".$franchiseid." and testdate='".date('Y\-m\-d',strtotime($rdate))."' and testid=".$row['testid'].""));
					if($ct!=0)
					{
						echo $ct;
					}
					?>
				</td>
				<?php
				}
				else
				{
				?>
				<td></td>
				<?php
				}
				$firstdate++;
			}
			?>
		</tr>		
		<?php
	}
}
?>
