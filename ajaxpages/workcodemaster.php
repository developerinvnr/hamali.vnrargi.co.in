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
$advanced	=	$dbconnection->getField("user_tbl","advanceworkcode","userid=".$_SESSION['datadetail'][0]['sessionid']."");
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];
	$orderby		=	$_POST['orderby'];
	
	
	$query	=	"select * from workcode_master ";
	if($searchvalue!='')
	{
		$query.=	"where (actionvalue like '%$searchvalue%' or verb like '%$searchvalue%' or material like '%$searchvalue%' or product like '%$searchvalue%' or defaultnarration like '%$searchvalue%') ";
	}
	$start	=	$pagesize*($pagenumber-1);
	$query.=	"limit $start, $pagesize";

}
$rs_sel		=	$dbconnection->firequery($query);
$rs_count	=	$dbconnection->firequery("select * from workcode_master");
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
	<td class="center" id="action" style="width:80px; text-align:center;"><?php echo $row['recordid']; $cols++;?></td>
	<?php
	if($advanced==1)
	{
	?>	
	<td><?php echo $row['actionvalue']; $cols++;?></td>
	<td><?php echo $row['verb']; $cols++;?></td>	
	<td><?php echo $row['material']; $cols++;?></td>		
	<td><?php echo $row['product']; $cols++;?></td>	
	<td><?php echo $row['operator']; $cols++;?></td>		
	<td><?php echo $row['quantity']; $cols++;?></td>		
	<td><?php echo $row['notation']; $cols++;?></td>		
	<td><?php echo $row['unit']; $cols++;?></td>		
	<td><?php echo $row['rate']; $cols++;?></td>
	<?php
	}
	?>
	<td><?php echo $row['defaultnarration']; $cols++;?></td>
	<td nowrap="nowrap">
	<?php
	$deps	=	explode(",",$row['departmentname']);
	$rs_dep	=	$dbconnection->firequery("select * from department_tbl order by departmentname");
	while($dep=mysqli_fetch_assoc($rs_dep))
	{
	?>
	<input type="checkbox" name="dep<?php echo $row['recordid'];?><?php echo $dep['departmentid'];?>" id="dep<?php echo $row['recordid'];?><?php echo $dep['departmentid'];?>" onClick="UpdateCodeDepartment(<?php echo $row['recordid'];?>,<?php echo $dep['departmentid'];?>)" <?php if(in_array($dep['departmentid'],$deps)) echo "checked"; ?>> <?php echo $dep['departmentname'];?><br>
	<?php
	}
	?>
	<label id="msg<?php echo $row['recordid'];?>" style="display:none; width:100%; padding:5px; background-color:#008c40; color:white;">Updated Successfully</label>
	</td>	
	<td class="center" id="action"><a href="./vnr_mainindex?m=<?php echo $_REQUEST['m'];?>&p=<?php echo $_REQUEST['p'];?>&pk=<?php echo encrypt($row[$_REQUEST['pk']]);?>"><i class="fa fa-edit"></i></a><?php  $cols++;?></td>
	<td class="center" id="action"><i class="fa fa-remove" onClick="CallBox('<?php echo encrypt($row[$_REQUEST['pk']]);?>')"></i><?php  $cols++;?></td>					
</tr>
<?php
}
if($j>0)
{
?>
<tr>
	<td colspan="13" style="padding:0px;">
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
	<td colspan="13" style="padding:0px; text-align:center;">
		<label style="font-size:13px; font-weight:normal; padding:10px;"><i>--No Record Found--</i></label>
	</td>
</tr>
<?php
}
?>
