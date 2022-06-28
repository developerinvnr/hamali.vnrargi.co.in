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

$i=0;
$j=0;
$totalltol	=	0;
$tamount	=	0;
$tafter		=	0;
$tpaid		=	0;	
$tbal		=	0;

?>
<html>
<head>
<title>PRINT TRF ENTRIES</title>
<link rel="stylesheet" href="../css/font-awesome.css">
</head>
<center>
<body>
<table style="width:100%; border-collapse:collapse; border:1px solid #666;" border="1">
	<thead>
	<tr><td colspan="11" align="center"><b>TRF ENTRIES</b><label style="float:right;">PRINTED ON : <?php echo date('d\-m\-Y');?></label></td></tr>
	<tr>
		<th style="padding:5px; text-align:center;">Sno</th>
		<th style="padding:5px;">TRF By</th>
		<th style="padding:5px;">Patient Detail</th>
		<th style="padding:5px;">Ref. Doc & Hosp.</th>					
		<th style="padding:5px;">Urgent/Outsource</th>					
		<th style="padding:5px;">Test Detail</th>
		<th style="padding:5px;">Total Amount</th>
		<th style="padding:5px;">After Discount</th>
		<th style="padding:5px;">Paid</th>					
		<th style="padding:5px;">Balance</th>
		<th style="padding:5px;">LTOL</th>					
	</tr>
	</thead>
	<tbody>
	<?php
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	?>
	<tr>
		<td align="center" valign="top"><?php echo $i;?></td>
		<td valign="top"><?php echo $row['staffname'];?><?php if($row['staffmobile']!="") echo "<br>".$row['staffmobile'];?><br /><b><?php echo $row['centername'];?><?php if($row['contactnumber']!="") echo "<br>".$row['contactnumber'];?></b><br />
		<?php echo date('d\-m\-Y h:i A',strtotime($row['creationdate']));?>
		</td>
		<td valign="top"><?php echo $row['customername'];?><?php if($row['mobilenumber']!="") echo "<br>".$row['mobilenumber'];?><?php if($row['age']!="") echo "<br>Age : ".$row['age'];?><?php if($row['gender']!="") echo "<br>".$row['gender'];?></td>
	<td valign="top">
	<?php 
		if($row['referredbyid']!="" && $row['referredbyid']!=0)
		{
			echo $dbconnection->getField("doctor_tbl","doctorname","doctorid=".$row['referredbyid']."");
		}
		if($row['referredbydoctor']!="")
		{
			echo $row['referredbydoctor'];
		}
		if($row['address']!="")
		echo "<br>".$row['address'];?>
	</td>
	<td valign="top">
	<?php 
		if($row['outsource']=="YES")
		{
			echo "YES";
			echo "<br>".date('d\-m\-Y',strtotime($row['outsourcedate']));
		}
	?>
	<?php 
	if($row['urgent']=="YES")
	{
		echo "YES";
		echo "<br>".date('h:i A',strtotime($row['urgenttime']));
	}
	if($row['remark']!="")
	echo "<br>Remark<br>".$row['remark'];
	?>
	</td>
	<td valign="top">
	<?php
	$rs_test	=	$dbconnection->firequery("select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.customerid=".$row['customerid']."");
	$cnt	=	$dbconnection->num_rows($rs_test);
	$ltol	=	0;
	while($roo=mysqli_fetch_assoc($rs_test))
	{
		echo $roo['testname']."<br>";
		$ltol	=	$ltol+$roo['spdiscount'];
	}
	$totalltol	=	$totalltol+$ltol;
	?>
	</td>
	<td valign="top" align="center"><?php echo $row['totalamount'];?></td>
	<td valign="top" align="center"><?php echo $row['afterdiscount'];?>
	<?php if(doubleval($row['admindiscount'])>0)
	{
		echo "<br>Additional Discount = ".doubleval($row['admindiscount']); 
	} 
	?>
	</td>	
	<td valign="top" align="center"><?php echo $row['paid'];?></td>	
	<td valign="top" align="center"><?php echo $row['balance'];?></td>
	<td valign="top" align="center"><?php echo $ltol;?></td>
	</tr>
	<?php
	$tamount	=	$tamount+$row['totalamount'];
	$tafter		=	$tafter+$row['afterdiscount'];
	$tpaid		=	$tpaid+$row['paid'];
	$tbal		=	$tbal+$row['balance'];	
	}	
	?>
	</tbody>
	<tr>
		<td colspan="6" style="padding:2px 10px; text-align:right; font-size:18px;"><b>Total L TO L</b></td>
		<td style="font-size:18px; font-weight:bold; text-align:center;"><?php echo $tamount;?></td>
		<td style="font-size:18px; font-weight:bold; text-align:center;"><?php echo $tafter;?></td>	
		<td style="font-size:18px; font-weight:bold; text-align:center;"><?php echo $tpaid;?></td>	
		<td style="font-size:18px; font-weight:bold; text-align:center;"><?php echo $tbal;?></td>	
		<td style="font-size:18px; font-weight:bold; text-align:center;"><?php echo $totalltol;?></td>
	</tr>	
</table>
</body>
</center>
</html>

