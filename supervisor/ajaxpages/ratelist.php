<?php
@session_start();
date_default_timezone_set('Asia/Calcutta');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$pagesearch	=	$_POST['pagesearch'];
	if($_POST['pagesearch']=="")
	$rs_sel	=	$dbconnection->firequery("select b.*,c.testname,c.testamount from ratemapping_tbl a left join ratelist_tbl b on b.rateid=a.rateid left join test_tbl c on c.testid=b.testid where a.centerid=".$_POST['centername']."");
	else
	$rs_sel	=	$dbconnection->firequery("select b.*,c.testname,c.testamount from ratemapping_tbl a left join ratelist_tbl b on b.rateid=a.rateid left join test_tbl c on c.testid=b.testid where a.centerid=".$_POST['centername']." and c.testname like '%$pagesearch%'");
	$i=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	if($i==1)
	{
	?>
	<tr><td colspan="8" align="right"><a href="./printing/printratelist.php?centerid=<?php echo $_POST['centername'];?>" target="_blank"><button type="button" class="btn btn-info">Print Rate List</button></a></td></tr>
	<tr>
		<th style="padding:5px; text-align:center; width:50px;">Sno</th>
		<th style="padding:5px;">Test Name</th>					
		<th style="padding:5px; text-align:center; width:150px;">MRP</th>
		<th style="padding:5px; text-align:center; width:150px;">Customer Price</th>					
		<th style="padding:5px; text-align:center; width:150px;">Your Lab To Lab Rate</th>					
	</tr>				
	<?php
	}
	?>
	<tr>
		<td style="text-align:center;"><?php echo $i;?></td>
		<td style="padding:5px;"><?php echo $row['testname'];?></td>
		<td style="padding:5px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $row['testamount'];?></td>		
		<td style="padding:5px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $row['customerprice'];?></td>				
		<td style="padding:5px; text-align:center;"><i class="fa fa-inr"></i> <?php echo $row['specialdiscount'];?></td>						
	</tr>
	<?php
	}
	if($i==0)
	{
	?>
	<tr><td colspan="5" align="center">--No Record Found--</td></tr>
	<?php
	}
}

?>
