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
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];
	$orderby		=	$_POST['orderby'];
	
	
	$query	=	"select * from workcode_master ";
	if($searchvalue!='')
	{
		$query.=	"where (actionvalue like '%$searchvalue%' or verb like '%$searchvalue%' or material like '%$searchvalue%' or product like '%$searchvalue%') ";
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
	<td><?php echo $row['defaultnarration'];?></td>
	<td align="center"><i class="fa fa-inr"></i> <?php echo $row['rate'];?></td>
</tr>
<?php
}
if($j>0)
{
?>
<tr>
	<td colspan="12" style="padding:0px;">
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
	<td colspan="12" style="padding:0px; text-align:center;">
		<label style="font-size:13px; font-weight:normal; padding:10px;"><i>--No Record Found--</i></label>
	</td>
</tr>
<?php
}
?>
