<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
unset($_SESSION['records']);
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$tablename		=	$_POST['processname']."_tbl";
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];
	$gpno			=	$_POST['gpno'];
	$deprt			=	$_POST['deprt'];	
	$frmdate		=	date('Y\-m\-d',strtotime($_POST['frmdate']));
	$todate			=	date('Y\-m\-d',strtotime($_POST['todate']));
	if($deprt!="")
	{
		$dipids	=	$dbconnection->getField("section_tbl","departmentname","sectionid=".$deprt."");		
	}
	if($gpno=="")
	{
		if($dipids=="")
		{
			$query	=	"select a.*,b.firstname,b.lastname from workslip_tbl a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipdate between '".$frmdate."' and '".$todate."' and a.location in (".$_SESSION['datadetail'][0]['loca'].") order by a.workslipdate ";
		}
		else
		{
			$query	=	"select a.*,b.firstname,b.lastname from workslip_tbl a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipdate between '".$frmdate."' and '".$todate."' and a.location in (".$_SESSION['datadetail'][0]['loca'].") and a.department in (".$dipids.") order by a.workslipdate ";			
		}
	}
	else
	{
		if($dipids=="")
		{
			$query	=	"select a.*,b.firstname,b.lastname from workslip_tbl a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipdate between '".$frmdate."' and '".$todate."' and a.groupnumber=".$gpno." and a.location in (".$_SESSION['datadetail'][0]['loca'].") order by a.workslipdate ";
		}
		else
		{
			$query	=	"select a.*,b.firstname,b.lastname from workslip_tbl a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipdate between '".$frmdate."' and '".$todate."' and a.groupnumber=".$gpno." and a.location in (".$_SESSION['datadetail'][0]['loca'].") and a.department in (".$dipids.") order by a.workslipdate ";			
		}
	}
	$query1=$query;
	$start	=	$pagesize*($pagenumber-1);
	$query.=	"limit $start, $pagesize";
}

$rs_sel		=	$dbconnection->firequery($query);
$rs_count	=	$dbconnection->firequery($query1);
$counter	=	$dbconnection->num_rows($rs_count);
$pagescount	=	ceil($counter/$pagesize);

$fields		=	$dbconnection->num_rows($rs_count);
$i=$start;
$j=0;
$k=0;
$spids	=	"";
while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
$j++;
if($k==0 && $row['paymentstatus']==0)
{
$k++;
$spids	=	$row['supervisorid'];
}
else if($row['paymentstatus']==0)
{
$k++;
$spids.=",".$row['supervisorid'];
}
$cols=0;
?>
<tr>
	<td align="center">
	<?php
	if($row['paymentstatus']==0)
	{
	?>
	<input type="checkbox" class="slips" onclick="SelectSlip()" name="slipid<?php echo $i;?>" id="slipid<?php echo $i;?>" value="<?php echo $row['workslipid'];?>"/>
	<?php
	}
	?>	
	</td>
	<td align="center">
	<?php echo $i; $cols++;?>
	</td>
	<td nowrap><?php echo date('d\-m\-Y h:i A',strtotime($row['workslipdate'])); $cols++;?></td>
	<td nowrap><?php echo $row['workslipnumber']; $cols++;?></td>	
	<td><?php echo $row['remark']; $cols++;?></td>		
	<td><?php echo $row['firstname']; $cols++;?> <?php echo $row['lastname']; $cols++;?></td>		
	<td><?php echo $row['groupnumber']; $cols++;?> - <?php echo $row['groupname']; $cols++;?></td>		
	<td style="text-align:right;" nowrap><i class="fa fa-inr"></i> <?php echo number_format($row['workslipamount'],'2','.',''); $cols++;?></td>
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
</tr>
<tr id="rec<?php echo $i;?>" style="display:none;" class="bg-ingo text-white wrec"><td id="recd<?php echo $i;?>" colspan="10" style="width:100%;"></td></tr>
<?php
}
if($j>0)
{
?>
<tr>
	<td colspan="10" style="padding:0px;">
		<label style="float:left; margin-top:10px; margin-left:10px; font-size:12px;"><i>Showing <?php echo $start+1;?> To <?php echo $start+$j;?> of <?php echo $counter;?> entries</i></label>
		<ul class="pagination" style="padding:0px; margin-top:10px; float:right;">
		  <?php
		  if($pagenumber==1)
		  {
		  ?>
		  <li><a style="cursor:wait; background-color:white!important; color:black;"><i class="ace-icon fa fa-angle-double-left"></i> Previous</a></li>
		  <?php
		  }
		  else
		  {
		  ?>
		  <li><a id="<?php echo $pagenumber-1;?>" class="pagelinks" style="background-color:white!important; color:black;" data-runid="<?php echo $pagenumber-1;?>"><i class="ace-icon fa fa-angle-double-left"></i> Previous</a></li>
		  <?php
		  }
		  for($i=1;$i<=$pagescount;$i++)
		  {
		  ?>
		  <li <?php if($pagenumber==$i) { ?>class="active"<?php } ?>><a id="<?php echo $i;?>" data-runid="<?php echo $i;?>" class="pagelinks" <?php if($pagenumber!=$i) { ?>style="background-color:white!important; color:black;"<?php } ?>><?php echo $i;?></a></li>		  
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
		  <li><a id="<?php echo $pagenumber+1;?>" class="pagelinks" style="background-color:white!important; color:black;" data-runid="<?php echo $pagenumber+1;?>">Next <i class="ace-icon fa fa-angle-double-right"></i></a></li>  
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
