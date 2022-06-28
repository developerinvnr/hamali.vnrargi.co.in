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
	$hgname			=	$_POST['hgname'];
	if($hgname=="")
	$query	=	"select a.*,b.groupname from paymentslip_tbl a left join hamaligroup_tbl b on b.hgid=a.groupnumber where a.location in (".$_SESSION['datadetail'][0]['loca'].") ";
	else
	$query	=	"select a.*,b.groupname from paymentslip_tbl a left join hamaligroup_tbl b on b.hgid=a.groupnumber where a.groupnumber=".$hgname." and a.location in (".$_SESSION['datadetail'][0]['loca'].") ";
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
$paid	=	$dbconnection->getField("payment_detail","sum(paidamount)","payslipid=".$row['slipid']."");
?>
<tr>
	<td align="center"><?php echo $i; $cols++;?></td>
	<td align="center" nowrap><?php echo date('d\-m\-Y h:i A',strtotime($row['payslipdate'])); $cols++;?></td>
	<td align="center"><?php echo $row['payslipnumber']; $cols++;?></td>	
	<td><?php echo $row['groupname']; $cols++;?></td>		
	<td><?php echo $row['remark']; $cols++;?></td>	
	<td align="center" nowrap><i class="fa fa-inr"></i> <?php echo number_format($row['totalamount'],'2','.',''); $cols++;?></td>			
	<td align="center" nowrap><i class="fa fa-inr"></i> <?php echo $balance=number_format($row['totalamount']-$paid,'2','.',''); $cols++;?></td>
	<td style="text-align:center;"><a href="./printing/printpayslip.php?slipid=<?php echo $row['slipid'];?>" target="_blank"><i class="fa fa-print"></i></a></td>	
	<td style="text-align:center;">
	<?php
	if($balance>0)
	{
	?>
	<button class="btn btn-info" type="button" onclick="PayBalance(<?php echo $row['slipid'];?>,<?php echo $i;?>,<?php echo $row['department'];?>,<?php echo $row['location'];?>,<?php echo $row['groupnumber'];?>)">PAY BALANCE</button>
	<?php
	}
	else
	{
	?>
	<button class="btn btn-info" type="button" disabled="disabled">PAID</button>
	<?php
	}
	?>
	</td>
	<td style="text-align:center;"><button class="btn btn-info" type="button" onclick="PaySlipDetail(<?php echo $row['slipid'];?>,<?php echo $i;?>)">PAY SLIP DETAIL</button></td>
	<td style="text-align:center;"><button class="btn btn-info" type="button" onclick="PaidDetail(<?php echo $row['slipid'];?>,<?php echo $i;?>)">PAID DETAIL</button></td>	
</tr>
<tr id="pay<?php echo $i;?>" style="display:none;" class="bg-ingo text-white prec">
	<td id="payd<?php echo $i;?>" colspan="11" style="width:100%;"></td>
</tr>
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
