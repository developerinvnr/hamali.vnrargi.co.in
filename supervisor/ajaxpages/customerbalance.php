<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
date_default_timezone_set('Asia/Calcutta');
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$pagesize		=	$_POST['pagesize'];
	$searchvalue	=	$_POST['searchvalue'];
	$pagenumber		=	$_POST['pagenumber'];
	$centername		=	$_POST['centername'];
	$frmdate		=	date('Y\-m\-d',strtotime($_POST['frmdate']));
	$todate			=	date('Y\-m\-d',strtotime($_POST['todate']));	
	
	$query	=	"select a.*,b.centername,b.contactnumber from customertest_tbl a left join center_tbl b on b.centerid=a.centerid left join staff_tbl c on c.staffid=a.staffid where a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and a.centerid in (".$centername.") and balance>0 and date(a.creationdate) between '".$frmdate."' and '".$todate."' ";
	$qry	=	array();
	if($searchvalue!='')
	{
		$qry[count($qry)]=	"(a.customername like '%$searchvalue%' or a.referencenumber like '%$searchvalue%' or a.mobilenumber like '%$searchvalue%' or a.address like '%$searchvalue%' or b.centername like '%$searchvalue%' or c.staffname like '%$searchvalue%')";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.= " and ".$str."";		
	}
	$query1=$query;
	$start	=	$pagesize*($pagenumber-1);
	$query.=	" order by a.creationdate desc limit $start, $pagesize";

}

$rs_sel		=	$dbconnection->firequery($query);

$rs_count	=	$dbconnection->firequery($query1);

$counter	=	$dbconnection->num_rows($rs_count);

$pagescount	=	ceil($counter/$pagesize);

$fields		=	$dbconnection->num_rows($rs_count);
$i=$start;
$j=0;
$balance	=	0;
$total			=	0;
$totaldiscount	=	0;
$totalpaid	=	0;

while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
$j++;
$cols=0;

if($j==1)
{
?>
<tr><td colspan="8" align="right"><a href="./printing/printcustomerbalance.php?centerid=<?php echo $centername;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a></td></tr>								
<tr>
	<th style="padding:3px;">Sno</th>
	<th style="padding:3px;">Collected By</th>					
	<th style="padding:3px;">Customer Detail</th>
	<th style="padding:3px;">Test Detail</th>
	<th style="padding:3px;">Total Amount</th>					
	<th style="padding:3px;">After Discount</th>										
	<th style="padding:3px;">Paid</th>										
	<th style="padding:3px;">Balance</th>
</tr>

<?php
}
?>
<tr>
	<td class="center" id="action"><?php echo $i; $cols++;?></td>
	<td>
	<?php 
		$rs_stf	=	$dbconnection->firequery("select * from staff_tbl where staffid=".$row['staffid']."");
		while($st=mysqli_fetch_assoc($rs_stf))
		{
			echo ucwords($st['staffname']);
			echo "<br>".$st['mobilenumber'];
			echo "<br>Date : ".date('d\-m\-Y',strtotime($row['creationdate']));
		}
		unset($rs_stf);
		unset($st);
	?>
	</td>	
	<td><?php echo $row['customername']."<br>".$row['mobilenumber']."<br>Age : ".$row['age']."<br>Gender : ".$row['gender'];?></td>
	<td>
	<?php
	$rs_det	=	$dbconnection->firequery("select b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.customerid=".$row['customerid']."");
	while($roo=mysqli_fetch_assoc($rs_det))
	{
		echo $roo['testname']."<br>";
	}
	?>
	</td>
	<td><i class="fa fa-inr"></i> <?php echo $row['totalamount'];?></td>
	<td><i class="fa fa-inr"></i> <?php echo ceil($row['afterdiscount']);?></td>
	<td><i class="fa fa-inr"></i> <?php echo $row['paid'];?></td>
	<td><i class="fa fa-inr"></i> <?php echo ceil($row['balance']);?></td>		
</tr>
<?php
$total			=	$total+$row['totalamount'];
$totaldiscount	=	$totaldiscount+ceil($row['afterdiscount']);
$totalpaid		=	$totalpaid+$row['paid'];
$balance	=	$balance+ceil($row['balance']);
}
?>
<tr>
	<td colspan="4" style="padding:3px; text-align:right;"><b>Total</b></td>
	<td style="padding:3px;"><i class="fa fa-inr"></i> <?php echo $total;?></td>
	<td style="padding:3px;"><i class="fa fa-inr"></i> <?php echo $totaldiscount;?></td>	
	<td style="padding:3px;"><i class="fa fa-inr"></i> <?php echo $totalpaid;?></td>	
	<td style="padding:3px;"><i class="fa fa-inr"></i> <?php echo $balance;?></td>	
</tr>
<tr>
	<td colspan="9" style="padding:0px; text-align:center;">
		<h3>Total Customer Balance <i class="fa fa-inr"></i> <?php echo $balance;?></h3>
	</td>
</tr>

<?php
if($j>0)
{
?>
<tr>
	<td colspan="9" style="padding:0px;">
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

?>
