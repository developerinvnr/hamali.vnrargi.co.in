<?php
@session_start();
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

$t=1;
require("../../validation/validation.php");
include("../../enc/urlenc.php");
include("../../db/db_connect.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$dd 	    = Date("d");
$mm			= Date("m");
$yy			= Date("Y");
$currentDate= $yy."-".$mm."-".$dd;

$frmdate		=	$_REQUEST['frmdate'];
$todate			=	$_REQUEST['todate'];
$centerid		=	$_REQUEST['centerid'];

	$query	=	"";
	$qry	=	array();
	if($centerid!="")
	{
		$qry[count($qry)]	=	"centerid=".$centerid."";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.= " and ".$str."";		
	}

	$rs_test	=	$dbconnection->firequery("select distinct(a.testid),b.testname,b.cpt from totalcentertest_view a inner join test_tbl b on b.testid=a.testid order by b.testname");	
	$i=0;
	$total	=	0;
	$cpt=0;

?>
<html>
<head>
<title>PRINT CENTER WISE TEST DETAIL</title>
<link rel="stylesheet" href="../css/font-awesome.css">
</head>
<center>
<body>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
	<thead>
	<tr><td colspan="11" align="center"><b>CENTER WISE TEST DETAIL BETWEEN <?php echo date('d\-m\-Y',strtotime($frmdate));?> To <?php echo date('d\-m\-Y',strtotime($todate));?></b><label style="float:right;">PRINTED ON : <?php echo date('d\-m\-Y');?></label></td></tr>
	<tr>
		<th style="padding:5px; width:50px; text-align:center;">Sno</th>
		<th style="padding:5px; text-align:left;">Test Name</th>
		<th style="padding:5px; text-align:center; width:150px;">Number Of Test</th>
		<th style="padding:5px; text-align:center; width:150px;">CPT</th>							
	</tr>
	</thead>
	<tbody>
	<?php
	while($row=mysqli_fetch_assoc($rs_test))
	{
	if(count($qry)>0)
	$ct	= intval($dbconnection->getField("totalcentertest_view","sum(cnts)","testdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and testid=".$row['testid']." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." ".$query.""));
	else
	$ct	= intval($dbconnection->getField("totalcentertest_view","sum(cnts)","testdate between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and testid=".$row['testid']." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid'].""));
	$total	=	$total+$ct;
	if($ct>0)
	{	
	$i++;
	?>
	<tr>
		<td align="center"><?php echo $i;?></td>
		<td><?php echo $row['testname'];?></td>
		<td style="text-align:center;"><?php echo $ct;?></td>
		<td style="text-align:center;"><?php echo $ct*$row['cpt'];?></td>		
	</tr>
	<?php	
	$cpt=$cpt+$ct*$row['cpt'];			
	}
	}
	?>
	</tbody>
	<tr style="font-size:18px;">
		<td colspan="2" align="right"><b>Total Number Of Test</b>&nbsp;</td>
		<td align="center"><b><?php echo $total;?></b></td>
		<td align="center"><b><?php echo $cpt;?></b></td>				
	</tr>
</table>
</body>
</center>
</html>

