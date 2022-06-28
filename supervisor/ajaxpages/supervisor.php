<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",1);
ini_set("session.bug_compat_warn",1);
ini_set("session.bug_compat_42",1);


include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$tablename		=	$_POST['processname']."_tbl";
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];

	
	$query	=	"select * from supervisor_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." ";
	$qry	=	array();	
	if($searchvalue!='')
	{
		$qry[count($qry)]=	" (supervisorname like '%$searchvalue%' or mobilenumber like '%$searchvalue%' or address like '%$searchvalue%' or email like '%$searchvalue%' or  like '%$searchvalue%') ";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.= " ".$str."";		
	}

	if($orderby!='')
	{
		$exp	=	explode("-",$orderby);
		$query.=	" order by $exp[0] $exp[1] ";
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
while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
$j++;
$cols=0;
?>
<tr>
	<td class="center" id="action"><?php echo $i; $cols++;?></td>
	<td><?php echo ucfirst($row['supervisorname']); $cols++;?><br />
	<?php echo $row['mobilenumber']; $cols++;?><br />
	<?php echo $row['email']; $cols++;?><br />
	<?php echo "User Name : ".$row['username']; $cols++;?><br />	
	<?php echo "Password : ".$row['password']; $cols++;?><br />	
	</td>
	<td><?php echo $row['gender']; $cols++;?></td>			
	<td><?php echo $row['address']; $cols++;?></td>			
	<td>
	<?php
	$rs_cen	=	$dbconnection->firequery("select * from center_tbl where centerid in (".$row['centers'].")");
	while($cnt=mysqli_fetch_assoc($rs_cen))
	{
		echo "<li>".$cnt['centername']." [".$cnt['contactnumber']."]</li>";
	}
	unset($rs_cen);
	unset($cnt);
	?>
	</td>				
	<td class="center" id="action">
	<?php
	if($row['activestatus']=="ACTIVE")
	{
	?>
	<button class="btn btn-danger" onclick="SupervisorStatus(<?php echo $row['supervisorid'];?>,'D')">BLOCK</button>
	<?php
	}
	else
	{
	?>
	<button class="btn btn-info" onclick="SupervisorStatus(<?php echo $row['supervisorid'];?>,'A')">ACTIVATE</button>	
	<?php
	}
	?>
	</td>			
	<td class="center" id="action"><a href="./mainindex.php?m=<?php echo $_REQUEST['m'];?>&p=<?php echo $_REQUEST['p'];?>&supervisorid=<?php echo encrypt($row['supervisorid']);?>"><i class="fa fa-edit"></i></a><?php  $cols++;?></td>
	
	<td class="center" id="action"><i class="fa fa-remove"></i><?php  $cols++;?></td>			
</tr>
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
