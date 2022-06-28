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
	$orderby		=	$_POST['orderby'];
	$cpname			=	$_POST['cpname'];
	$grpname		=	$_POST['grpname'];
	
	$query	=	"select a.memberid,a.membername,a.mobilenumber,b.* from groupmember_tbl a inner join hamaligroup_tbl b on b.hgid=a.groupname ";
	$qry	=	array();
	if($cpname!="")
	{
		$qry[count($qry)]=	"b.companyname=".$cpname."";
	}
	if($grpname!="")
	{
		$qry[count($qry)]=	"b.hgid=".$grpname."";
	}
	if($searchvalue!="")
	{
		$qry[count($qry)]	=	"(a.membername like '%$searchvalue%' or a.mobilenumber like '%$searchvalue%')";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.= " and ".$str." order by creationdate";		
	}
	else
	{
		$query.= " order by creationdate";			
	}
	$start	=	$pagesize*($pagenumber-1);
	$query.=	" limit $start, $pagesize";

}
$rs_sel		=	$dbconnection->firequery($query);
$rs_count	=	$dbconnection->firequery("select * from $tablename");
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
	<td><?php echo $row['compname'];?></td>
	<td><?php echo $row['groupname'];?></td>	
	<td><?php echo $row['membername'];?></td>
	<td><?php echo $row['mobilenumber'];?></td>
	<td class="center" id="action"><a href="./vnr_mainindex?m=<?php echo $_REQUEST['m'];?>&p=<?php echo $_REQUEST['p'];?>&pk=<?php echo encrypt($row[$_REQUEST['pk']]);?>"><i class="fa fa-edit"></i></a><?php  $cols++;?></td>
	<td class="center" id="action"><i class="fa fa-remove" onClick="CallBox('<?php echo encrypt($row[$_REQUEST['pk']]);?>')"></i><?php  $cols++;?></td>					
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
