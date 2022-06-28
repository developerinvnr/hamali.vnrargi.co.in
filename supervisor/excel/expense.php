<?php
@session_start();
$t=1;
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

require('../../db/db_connect.php');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
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
	
	$rs_sel	=	$dbconnection->firequery($query);

	$columnHeader = ''; 
	$columnHeader = "Sno" . "\t" . "Expense Date" . "\t" . "Head Name" . "\t" . "Remark" . "\t" . "Amount" . "\t";
	$setData = '';
	$k=0;
	$i=0;
	$j=0;
	$k=0;
	$totamt	=	0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
		$j++;
		$rowData	=	"";
		$k++;
		$rowData.=	'"' . $k . '"' . "\t";
		$rowData.=	'"' . date('d\-m\-Y',strtotime($row['expensdate'])) . '"' . "\t";	
		$rowData.=	'"' . $row['headname'] . '"' . "\t";
		$rowData.=	'"' . $row['remark'] . '"' . "\t";		
		$rowData.=	'"' . $row['amount'] . '"' . "\t";
		$totamt		=	$totamt+$row['amount'];
		$setData.= trim($rowData) . "\n";	
	}

	$rowData="";
	$setData.= trim($rowData) . "\n";
	$rowData="";
	$rowData.=	'"' . '' . '"' . "\t";	
	$rowData.=	'"' . '' . '"' . "\t";	
	$rowData.=	'"' . '' . '"' . "\t";	
	$rowData.=	"Total \t";
	$rowData.=	'"' . $totamt . '"' . "\t";
	$setData.= trim($rowData) . "\n";
	$setData.= "\n";
	header("Content-type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=expense.xls"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
?>

