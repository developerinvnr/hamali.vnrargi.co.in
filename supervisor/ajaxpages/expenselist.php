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
	$cntname		=	$_POST['cntname'];
	$apprstatus		=	$_POST['apprstatus'];	
	$hdname			=	$_POST['hdname'];	

	$query	=	"select a.*,b.headname,c.centername from expenses_tbl a inner join expenseshead_tbl b on b.headid=a.headid inner join center_tbl c on c.centerid=a.centerid where a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and a.expensdate between '".date('Y\-m\-d',strtotime($_POST['frmdate']))."' and '".date('Y\-m\-d',strtotime($_POST['todate']))."' ";
	$qry	=	array();	
	if($searchvalue!='')
	{
		$qry[count($qry)]=	" (a.remark like '%$searchvalue%' or b.headname like '%$searchvalue%') ";
	}
	if($cntname!="")
	{
		$qry[count($qry)]=	" a.centerid=".$cntname." ";	
	}
	if($hdname!="")
	{
		$qry[count($qry)]=	" a.headid=".$hdname." ";	
	}
	if($apprstatus!="")
	{
		$qry[count($qry)]=	" a.approvalstatus='".$apprstatus."' ";	
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.= " and ".$str." order by a.expensdate";		
	}
	else
	{
		$query.=	" order by a.expensdate";
	}
	$query1=$query;

}
$rs_sel		=	$dbconnection->firequery($query);
$i=$start;
$j=0;
$total	=	0;
while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
$j++;
$cols=0;
if($j==1)
{
?>
<tr>
<td colspan="10" align="right">
<a href="./excel/expense.php?cntname=<?php echo $cntname;?>&hdname=<?php echo $hdname;?>&apprstatus=<?php echo $apprstatus;?>&frmdate=<?php echo date('Y\-m\-d',strtotime($_POST['frmdate']));?>&todate=<?php echo date('Y\-m\-d',strtotime($_POST['todate']));?>" target="_blank"><button type="button" class="btn btn-info">Get In Excel</button></a>
<a href="./printing/printexpense.php?cntname=<?php echo $cntname;?>&hdname=<?php echo $hdname;?>&apprstatus=<?php echo $apprstatus;?>&frmdate=<?php echo date('Y\-m\-d',strtotime($_POST['frmdate']));?>&todate=<?php echo date('Y\-m\-d',strtotime($_POST['todate']));?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a>
</td>
</tr>
<tr>
	<th style="padding:3px; vertical-align:top;">Sno</th>
	<th style="padding:3px; text-align:center; vertical-align:top;">Exp. Date</th>
	<th style="padding:3px; vertical-align:top;">Center Name</th>					
	<th style="padding:3px; vertical-align:top;">Expense Head</th>
	<th style="padding:3px; vertical-align:top;">Remark</th>					
	<th style="padding:3px; text-align:center; vertical-align:top;" nowrap="nowrap">Approval Status</th>
	<th style="padding:3px; text-align:center; vertical-align:top;">Approved By</th>			
	<th style="padding:3px; text-align:center; vertical-align:top;">Amount</th>							
	<th style="padding:3px; vertical-align:top;" class="center"><i class="fa fa-edit"></i></th>
	<th style="padding:3px; vertical-align:top;" class="center"><i class="fa fa-remove"></i></th>
</tr>

<?php
}
?>
<tr>
	<td class="center" id="action"><?php echo $i; $cols++;?></td>
	<td style="text-align:center; width:80px;"><?php echo date('d\-m\-Y',strtotime($row['expensdate'])); $cols++;?></td>
	<td style="width:220px;"><?php echo $row['centername']; $cols++;?></td>			
	<td style="width:150px;"><?php echo $row['headname']; $cols++;?></td>
	<td style="width:300px;"><?php echo $row['remark']; $cols++;?></td>	
	<td style="width:80px;" align="center"><?php echo $row['approvalstatus']; $cols++;?></td>	
	<td style="width:120px;" align="center">
	<?php
	if($row['approvalstatus']=="APPROVED" && $row['centerheadid']==0)
	{
		echo "FRANCHISE";
	}
	else
	{
	?>
	<button type="button" class="btn btn-success" onclick="Approve(<?php echo $row['expenseid'];?>)">APPROVE</button>
	<?php
	}
	?>
	</td>	
	<td align="center" style="width:80px;"><i class="fa fa-inr"></i> <?php echo $row['amount']; $cols++;?></td>	
	<td class="center" id="action"><a href="./mainindex.php?m=<?php echo $_REQUEST['m'];?>&p=<?php echo $_REQUEST['p'];?>&expenseid=<?php echo encrypt($row['expenseid']);?>"><i class="fa fa-edit"></i></a><?php  $cols++;?></td>
	
	<td class="center" id="action"><i class="fa fa-remove" onclick="CallBox('<?php echo encrypt($row['expenseid']);?>')"></i><?php  $cols++;?></td>			
</tr>
<?php
$total	=	$total+$row['amount'];
}
?>
<tr style="font-size:16px;">
	<td colspan="7" align="right"><b>Total</b>&nbsp;</td>
	<td align="center"><b><i class="fa fa-inr"></i> <?php echo $total;?></b></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>	
</tr>