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

?>
<html>
<head>
<title>PRINT RECEIVING LIST</title>
<link rel="stylesheet" href="../../css/font-awesome.css">
</head>
<center>
<body>

<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
<thead>
	<tr><td colspan="5" align="center"><b>SUPERVISOR RECEIVING LIST BETWEEN <?php echo date('d\-m\-Y',strtotime($_REQUEST['frmdate']));?> TO <?php echo date('d\-m\-Y',strtotime($_REQUEST['todate']));?></b></td></tr>
	<tr>
		<th style="padding:3px; text-align:center; width:50px;">Sno</th>
		<th style="padding:3px; text-align:left;">Supervisor Name</th>
		<th style="padding:3px; text-align:left;">Center Name</th>
		<th style="padding:3px; text-align:center; width:200px;">Receiving Date</th>
		<th style="padding:3px; text-align:center; width:150px;">Received Amount</th>
	</tr>
</thead>
<tbody>
<?php
	$query	=	"select a.*,b.supervisorname,b.mobilenumber,c.centername,c.contactnumber from franchisereceiving_tbl a inner join supervisor_tbl b on b.supervisorid=a.supervisorid inner join center_tbl c on c.centerid=a.centerid ";
	$qry	=	array();
	if($_REQUEST['supervisorid']!="")
	{
		$qry[count($qry)]	=	"a.supervisorid=".$_REQUEST['supervisorid']."";
	}
	if($_REQUEST['centerid']!="")
	{
		$qry[count($qry)]	=	"a.centerid=".$_REQUEST['centerid']."";
	}

	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.=	" where ".$str."";		
		$query.=	" and date(a.receivingdate) between '".date('Y\-m\-d',strtotime($_REQUEST['frmdate']))."' and '".date('Y\-m\-d',strtotime($_REQUEST['todate']))."'";
	}
	else
	{
		$query.=	" where date(a.receivingdate) between '".date('Y\-m\-d',strtotime($_REQUEST['frmdate']))."' and '".date('Y\-m\-d',strtotime($_REQUEST['todate']))."'";	
	}
	$rs_sel	=	$dbconnection->firequery($query);	
	$i=0;
	$total	=	0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	?>
	<tr>
		<td style="text-align:center; padding:3px;"><?php echo $i;?></td>
		<td style="padding:3px;"><?php echo $row['supervisorname'];?><br /><?php echo $row['mobilenumber'];?></td>
		<td style="padding:3px;"><?php echo $row['centername'];?><br /><?php echo $row['mobilenumber'];?></td>
		<td style="text-align:center; padding:3px;"><?php echo date('d\-m\-Y, h:i A',strtotime($row['receivingdate']));?></td>		
		<td style="text-align:center; padding:3px;"><i class="fa fa-inr"></i> <?php echo $row['receivingamount'];?></td>
	</tr>
	<?php
	$total	=	$total+$row['receivingamount'];
	
}
?>
	<tr style="font-size:16px;">
		<td colspan="4" align="right"><b>Total</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total;?></b></td>		
	</tr>
</tbody>
</table>
</body>
</center>
</html>

