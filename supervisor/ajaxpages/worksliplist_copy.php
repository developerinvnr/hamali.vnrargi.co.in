<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
unset($_SESSION['records']);
if(isset($_SESSION['supervisordetail'][0]['sessionid']))
{
	$tablename		=	$_POST['processname']."_tbl";
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];
	$query	=	"select * from workslip_tbl where supervisorid=".$_SESSION['supervisordetail'][0]['sessionid']." ";
	if($searchvalue!='')
	{
		$query.=	"and (groupname like '%$searchvalue%' or remark like '%$searchvalue%') ";
	}
	$query1=$query;
	$start	=	$pagesize*($pagenumber-1);
	$query.=	"limit $start, $pagesize";

}
$rs_sel		=	$dbconnection->firequery($query);
$rs_count	=	$dbconnection->firequery("$query");
$counter	=	$dbconnection->num_rows($rs_count);
$pagescount	=	ceil($counter/$pagesize);
$fields		=	$dbconnection->num_rows($rs_count);
$i=$start;
$j=0;
while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
$j++;
$cols=0;
?>
<tr>
	<td class="center" id="action"><?php echo $i; $cols++;?></td>
	<td><?php echo date('d\-m\-Y h:i A',strtotime($row['workslipdate'])); $cols++;?></td>
	<td><?php echo $row['workslipnumber']; $cols++;?></td>	
	<td><?php echo $row['remark']; $cols++;?></td>		
	<td><?php echo $row['groupnumber']; $cols++;?></td>		
	<td><?php echo $row['groupname']; $cols++;?></td>		
	<td style="text-align:right;"><i class="fa fa-inr"></i> <?php echo $row['workslipamount']; $cols++;?></td>
	<td style="text-align:center;">
	<?php
	if($row['paymentstatus']==0)
	{
		echo "PENDING";
	}
	else
	{
		echo "PAID";
	}
	?>
	</td>
	<td style="text-align:center;"><i class="fa fa-eye" onclick="ViewDetail(<?php echo $i;?>,<?php echo $row['workslipid'];?>)"></i></td>
	<td style="text-align:center;"><a href="./printing/printworkslip.php?slipid=<?php echo $row['workslipid'];?>" target="_blank"><i class="fa fa-print"></i></a></td>	
	<td style="text-align:center;">
	<?php
	if($row['paymentstatus']==0)
	{
	?>
		<a href="./vnr_mainindex?m=<?php echo $_REQUEST['m'];?>&p=<?php echo encrypt("editworkslip");?>&slipid=<?php echo $row['workslipid'];?>"><i class="fa fa-edit"></i></a>
	<?php
	}
	else
	{
	?>
	<i class="fa fa-info-circle" title="PAYMENT SLIP HAS BEEN GENERATED FOR THIS WORKSLIP. YOU CAN NOT EDIT THIS WORK SLIP."></i>
	<?php
	}
	?>
	</td>		
</tr>
<tr id="rec<?php echo $i;?>" style="display:none;" class="bg-ingo text-white wrec"><td id="recd<?php echo $i;?>" colspan="11" style="width:100%;"></td></tr>
<?php
}
if($j>0)
{
?>
<tr>
	<td colspan="11" style="padding:0px;">
		<label style="float:left; margin-top:10px; margin-left:10px; font-size:12px;"><i>Showing <?php echo $start+1;?> To <?php echo $start+$j;?> of <?php echo $counter;?> entries</i></label>
		<ul class="pagination" style="padding:0px; margin-top:10px; float:right;">
		  <?php
		  if($pagenumber==1)
		  {
		  ?>
		  <li><a style="cursor:wait;"><i class="ace-icon fa fa-angle-double-left"></i> Previous</a></li>
		  <?php
		  }
		  else
		  {
		  ?>
		  <li><a id="<?php echo $pagenumber-1;?>" class="pagelinks" data-runid="<?php echo $pagenumber-1;?>"><i class="ace-icon fa fa-angle-double-left"></i> Previous</a></li>
		  <?php
		  }
		  for($i=1;$i<=$pagescount;$i++)
		  {
		  ?>
		  <li <?php if($pagenumber==$i) { ?>class="active"<?php } ?>><a id="<?php echo $i;?>" data-runid="<?php echo $i;?>" class="pagelinks"><?php echo $i;?></a></li>		  
		  <?php
		  }
		  if($pagenumber==$pagescount)
		  {
		  ?>
		  <li><a style="cursor:wait;">Next <i class="ace-icon fa fa-angle-double-right"></i></a></li>  
		  <?php
		  }
		  else
		  {
		  ?>
		  <li><a id="<?php echo $pagenumber+1;?>" class="pagelinks" data-runid="<?php echo $pagenumber+1;?>">Next <i class="ace-icon fa fa-angle-double-right"></i></a></li>  
		  <?php
		  }
		  ?>
		</ul>
	</td>
</tr>
<?php
}
else
{
?>
<tr>
	<td colspan="10" style="padding:0px; text-align:center;">
		<label style="font-size:13px; font-weight:normal; padding:10px;"><i>--No Record Found In Searching Criteria--</i></label>
	</td>
</tr>
<?php
}
?>
