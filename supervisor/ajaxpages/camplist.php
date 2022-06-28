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
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$tablename		=	$_POST['processname']."_tbl";
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];
	$arrby			=	$_POST['arrby'];
	$cmpstatus		=	$_POST['cmpstatus'];
	$frmdate		=	$_POST['frmdate'];
	$todate			=	$_POST['todate'];	

	
	$query	=	"select a.*,b.locationname as areaname from camp_tbl a inner join location_tbl b on b.locationid=a.locationid where a.addedby=".$_SESSION['franchisedetail'][0]['sessionid']." ";
	$qry	=	array();	
	if($searchvalue!='')
	{
		$qry[count($qry)]=	" (a.locationname like '%$searchvalue%' or a.contactperson like '%$searchvalue%' or a.mobilenumber like '%$searchvalue%' or a.arrangedby like '%$searchvalue%') ";
	}
	if($cmpstatus=="")
	{
		$qry[count($qry)]=" (a.campstatus='IN PROCESS' or a.campstatus='CONFIRMED')";
	}
	if($cmpstatus!="")
	{
		$qry[count($qry)]=" a.campstatus='".$cmpstatus."'";
	}
	if($arrby!="")
	{
		$qry[count($qry)]=" a.arrangedby='".$arrby."'";
	}
	if($frmdate!="" && $todate!="")
	{
		$qry[count($qry)]=" a.campdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."'";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.= "and ".$str."";		
	}
	$query.=	" order by a.campdate ";
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
$totalcoll	=	0;
$totalcpt	=	0;
$j=0;
while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
$j++;
$cols=0;
$totalcoll	=	$totalcoll+doubleval($row['totalamount']);
$totalcpt	=	$totalcpt+doubleval($row['totalcpt']);
?>
<tr>
	<td class="center" id="action"><?php echo $i; $cols++;?></td>
	<td>
	Apartment : <?php echo strtoupper($row['locationname']); $cols++;?><br />
	Area : <?php echo strtoupper($row['areaname']);?>
	</td>
	<td>
	<?php echo strtoupper($row['contactperson']); $cols++;?><br />
	<?php echo $row['mobilenumber']; $cols++;?>
	</td>
	<td>
	Mode : <?php echo $row['campmode'];?><br />
	Fees : <?php echo doubleval($row['campfees']);?><br />
	Date : <?php echo date('d\-m\-Y',strtotime($row['campdate']));?>
	</td>			
	<td><?php echo $row['campstatus'];?></td>				
	<td><?php echo $row['totalamount'];?></td>				
	<td><?php echo $row['totalcpt'];?></td>				
	<td><?php echo $row['cptpaidstatus'];?></td>				
	<td><?php echo $row['arrangedby'];?></td>				
	<td class="center" id="action"><a href="./mainindex.php?m=<?php echo $_REQUEST['m'];?>&p=<?php echo $_REQUEST['p'];?>&campid=<?php echo encrypt($row['campid']);?>"><i class="fa fa-edit"></i></a><?php  $cols++;?></td>
	
	<td class="center" id="action"><i class="fa fa-remove" onClick="CallBox('<?php echo encrypt($row['campid']);?>')"></i><?php  $cols++;?></td>			
</tr>
<?php
}
?>
<tr>
	<td colspan="5" align="right"><b>Total</b>&nbsp;&nbsp;</td>
	<td align="center"><b><?php echo $totalcoll;?></b></td>
	<td align="center"><b><?php echo $totalcpt;?></b></td>	
	<td colspan="4">&nbsp;</td>
</tr>
<?php
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
		<label style="font-size:13px; font-weight:normal; padding:10px;"><i>--No Record Found In Searching Criteria--</i></label>
	</td>
</tr>
<?php
}
?>
