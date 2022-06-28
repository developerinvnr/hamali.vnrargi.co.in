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
	$tablename		=	$_POST['processname']."_tbl";
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];
	$canseepassword	=	$dbconnection->getField("user_tbl","canseepassword","userid=".$_SESSION['datadetail'][0]['sessionid']."");
	if($_SESSION['datadetail'][0]['authtype']=="SUPER ADMIN")
	$query	=	"select * from user_tbl ";
	else
	$query	=	"select * from user_tbl where usertype!='SUPER ADMIN' ";
	$qry	=	array();
	if($searchvalue!="")
	{
		$qry[count($qry)]	=	"(firstname like '%$searchvalue%' or lastname like '%$searchvalue%' or a.mobile like '%$searchvalue%')";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		if($_SESSION['datadetail'][0]['authtype']=="SUPER ADMIN")
		$query.= "where ".$str." order by created";		
		else
		$query.= "and ".$str." order by created";		
	}
	else
	{
		$query.= " order by created";			
	}
	$start	=	$pagesize*($pagenumber-1);
	$query.=	" limit $start, $pagesize";
}
$rs_sel		=	$dbconnection->firequery($query);
$rs_count	=	$dbconnection->firequery("select * from user_tbl");
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
	<td class="center" id="action"><?php echo $i;?></td>
	<td><?php echo $row['firstname'];?></td>
	<td><?php echo $row['lastname'];?></td>	
	<td><?php echo $row['mobile'];?></td>
	<td><?php echo $row['address'];?></td>
	<td><?php echo $row['username'];?></td>
	<?php
	if($canseepassword==1)
	{
	?>
	<td><?php echo trim(decryptvalue($row['password']));?></td>	
	<?php
	}
	?>
	<td class="center" id="action"><a href="./vnr_mainindex?m=<?php echo $_REQUEST['m'];?>&p=<?php echo $_REQUEST['p'];?>&userid=<?php echo encrypt($row['userid']);?>"><i class="fa fa-edit"></i></a><?php  $cols++;?></td>
	<td class="center" id="action">
	<?php
	if($row['usertype']!="SUPER ADMIN")
	{
	?>
	<i class="fa fa-remove" onClick="CallBox('<?php echo encrypt($row['userid']);?>')"></i><?php  $cols++;?>
	<?php
	}
	else
	{
	?>
	<i class="fa fa-info" title="SUPER ADMIN CAN NOT BE DELETED"></i>
	<?php
	}
	?>
	</td>					
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
		<label style="font-size:13px; font-weight:normal; padding:10px;"><i>--No Record Found In Searching Criteria--</i></label>
	</td>
</tr>
<?php
}
?>
