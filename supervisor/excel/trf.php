<?php
@session_start();
$t=1;
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

require('../../db/db_connect.php');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
	$frmdate		=	$_REQUEST['frmdate'];
	$todate			=	$_REQUEST['todate'];
	$centerid		=	$_REQUEST['centername'];
	
	if($centerid=="")
	{
		$query	=	"select a.*,b.franchisename,b.mobilenumber as franmobile,a.age,a.gender,c.centername,c.contactnumber,d.staffname,d.mobilenumber as staffmobile from customertest_tbl a inner join franchise_tbl b on b.franchiseid=a.franchiseid inner join center_tbl c on c.centerid=a.centerid inner join staff_tbl d on d.staffid=a.staffid where date(a.creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']."";
	}
	else
	{
		$query	=	"select a.*,b.franchisename,b.mobilenumber as franmobile,a.age,a.gender,c.centername,c.contactnumber,d.staffname,d.mobilenumber as staffmobile from customertest_tbl a inner join franchise_tbl b on b.franchiseid=a.franchiseid inner join center_tbl c on c.centerid=a.centerid inner join staff_tbl d on d.staffid=a.staffid where date(a.creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and a.centerid=".$centerid." and a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']."";
	}
	$rs_sel	=	$dbconnection->firequery($query);

	$columnHeader = ''; 
	$columnHeader = "Sno" . "\t" . "Trf By" . "\t" . "Trf Date" . "\t" . "Patient Detail" . "\t" . "Ref. Doc & hospital" . "\t" . "Test Detail" . "\t" . "Total Amt" . "\t" . "After Discount" . "\t" . "Paid" . "\t" . "Balance" . "\t" . "Doctor" . "\t" . "Remark" . "\t"; 
	$setData = '';
	$k=0;


	$j=0;
	$totalltol	=	0;
	$tamount	=	0;
	$tafter		=	0;
	$tpaid		=	0;	
	$tbal		=	0;
	$tdoc		=	0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	$j++;
	$rowData	=	"";
	$k++;
	
	$rowData.=	'"' . $k . '"' . "\t";
	$rowData.=	'"' . $row['staffname'] . '"' . "\t";
	$rowData.=	'"' . date('d\-m\-Y h:i A',strtotime($row['creationdate'])) . '"' . "\t";	
	$rowData.=	'"' . $row['customername']." ".$row['mobilenumber'] . '"' ."\t";
	if($row['referredbyid']!="" && $row['referredbyid']!=0)
	{
	$rowData.=	'"' . $dbconnection->getField("doctor_tbl","doctorname","doctorid=".$row['referredbyid']."") . '"' . "\t";
	}
	else
	{
	$rowData.=	'"' . $row['referredbydoctor'] . '"' . "\t";
	}
	$rs_test	=	$dbconnection->firequery("select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.customerid=".$row['customerid']."");
	$cnt	=	$dbconnection->num_rows($rs_test);
	$doccom	=	0;
	$tname	=	"";
	$d=0;
	while($roo=mysqli_fetch_assoc($rs_test))
	{
	$d++;
	if($d==1)
	{
		$tname	=	$roo['testname'];
	}
	else
	{
		$tname.=	",".$roo['testname'];	
	}
	$doccom	=	$doccom+$roo['doctorcommission'];
	}
	$rowData.=	'"' . $tname . '"';
	$rowData.=	"\t";
	$rowData.=	'"' . $row['totalamount'] . '"' . "\t";	
	$rowData.=	'"' . $row['afterdiscount'] . '"' . "\t";
	$rowData.=	'"' . $row['paid'] . '"' . "\t";	
	$rowData.=	'"' . $row['balance'] . '"' . "\t";	
	$rowData.=	'"' . $doccom . '"' . "\t";		
	$rowData.=	'"' . $row['remark'] . '"' . "\t";	

	$tamount	=	$tamount+$row['totalamount'];
	$tafter		=	$tafter+$row['afterdiscount'];
	$tpaid		=	$tpaid+$row['paid'];
	$tbal		=	$tbal+$row['balance'];
	$tdoc		=	$tdoc+$doccom;
	$setData.= trim($rowData) . "\n";
	}

	$rowData="";
	$rowData.=	'"' . '' . '"' . "\t";	
	$rowData.=	'"' . '' . '"' . "\t";	
	$rowData.=	'"' . '' . '"' . "\t";	
	$rowData.=	'"' . '' . '"' . "\t";	
	$rowData.=	'"' . '' . '"' . "\t";	
	$rowData.=	"Total \t";
	$rowData.=	'"' . $tamount . '"' . "\t";
	$rowData.=	'"' . $tafter . '"' . "\t";	
	$rowData.=	'"' . $tpaid . '"' . "\t";
	$rowData.=	'"' . $tbal . '"' . "\t";
	$rowData.=	'"' . $tdoc . '"' . "\t";	
	$setData.= trim($rowData) . "\n";
	$setData.= "\n";

	$k=0;
	$rowData="";	
	$setData.= "\n";	
	$setData.= trim($rowData) . "\n";	
	$rowData.=	'"' . 'Sno' . '"' . "\t";
	$rowData.=	'"' . 'Expense Date' . '"' . "\t";	
	$rowData.=	'"' . 'Head Name' . '"' . "\t";
	$rowData.=	'"' . 'Remark' . '"' . "\t";		
	$rowData.=	'"' . 'Amount' . '"' . "\t";
	$setData.= trim($rowData) . "\n";	
	$setData.= "\n";
	
	if($centerid=="")
	{
		$query1	=	"select a.*,b.headname from expenses_tbl a left join expenseshead_tbl b on b.headid=a.headid where a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and a.headid!=7 order by a.expensdate";
	}
	else
	{
		$query1	=	"select a.*,b.headname from expenses_tbl a left join expenseshead_tbl b on b.headid=a.headid where a.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and a.centerid=".$centerid." and a.headid!=7 order by a.expensdate";	
	}
	$rs_sel1	=	$dbconnection->firequery($query1);	

	$i=0;
	$j=0;
	$k=0;
	$totamt	=	0;
	while($row1=mysqli_fetch_assoc($rs_sel1))
	{
		$i++;
		$j++;
		$rowData	=	"";
		$k++;
		$rowData.=	'"' . $k . '"' . "\t";
		$rowData.=	'"' . date('d\-m\-Y',strtotime($row1['expensdate'])) . '"' . "\t";	
		$rowData.=	'"' . $row1['headname'] . '"' . "\t";
		$rowData.=	'"' . $row1['remark'] . '"' . "\t";		
		$rowData.=	'"' . $row1['amount'] . '"' . "\t";
		$totamt		=	$totamt+$row1['amount'];
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
	$setData.= "\n";
	
	header("Content-type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=details.xls"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
?>

