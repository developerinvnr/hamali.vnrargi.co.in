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
	$tablename		=	$_REQUEST['processname']."_tbl";
	$pagesize		=	$_REQUEST['pagesize'];
	$searchvalue	=	$_REQUEST['searchvalue'];
	$pagenumber		=	$_REQUEST['pagenumber'];
	$cntname		=	$_REQUEST['cntname'];
	$apprstatus		=	$_REQUEST['apprstatus'];	
	$hdname			=	$_REQUEST['hdname'];	

	$query	=	"select a.*,b.headname,c.centername from expenses_tbl a inner join expenseshead_tbl b on b.headid=a.headid inner join center_tbl c on c.centerid=a.centerid where a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and a.expensdate between '".date('Y\-m\-d',strtotime($_REQUEST['frmdate']))."' and '".date('Y\-m\-d',strtotime($_REQUEST['todate']))."' ";
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
?>
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<?php
while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
$j++;
$cols=0;
if($j==1)
{
?>
<tr>
	<th style="padding:3px; vertical-align:top; text-align:center;">Sno</th>
	<th style="padding:3px; text-align:center; vertical-align:top;">Exp. Date</th>
	<th style="padding:3px; vertical-align:top; text-align:left;">Center Name</th>					
	<th style="padding:3px; vertical-align:top; text-align:left;">Expense Head</th>
	<th style="padding:3px; vertical-align:top; text-align:left;">Remark</th>					
	<th style="padding:3px; text-align:center; vertical-align:top;" nowrap="nowrap">Approval Status</th>
	<th style="padding:3px; text-align:center; vertical-align:top;">Approved By</th>			
	<th style="padding:3px; text-align:center; vertical-align:top;">Amount</th>							
</tr>

<?php
}
?>
<tr>
	<td class="center" style="text-align:center;" id="action"><?php echo $i; $cols++;?></td>
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
</tr>
<?php
$total	=	$total+$row['amount'];
}
?>
<tr style="font-size:16px;">
	<td colspan="7" align="right"><b>Total</b>&nbsp;</td>
	<td align="center"><b><i class="fa fa-inr"></i> <?php echo $total;?></b></td>
</tr>
</table>