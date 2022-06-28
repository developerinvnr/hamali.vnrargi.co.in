<?php
@session_start();
date_default_timezone_set('Asia/Calcutta');
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
	$query	=	"select a.*,b.supervisorname,b.mobilenumber,c.centername,c.contactnumber from franchisereceiving_tbl a inner join supervisor_tbl b on b.supervisorid=a.supervisorid inner join center_tbl c on c.centerid=a.centerid ";
	$qry	=	array();
	if($_POST['supervisorname']!="")
	{
		$qry[count($qry)]	=	"a.supervisorid=".$_POST['supervisorname']."";
	}
	if($_POST['centername']!="")
	{
		$qry[count($qry)]	=	"a.centerid=".$_POST['centername']."";
	}

	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query.=	" where ".$str."";		
		$query.=	" and date(a.receivingdate) between '".date('Y\-m\-d',strtotime($_POST['frmdate']))."' and '".date('Y\-m\-d',strtotime($_POST['todate']))."' order by a.receivingdate desc";
	}
	else
	{
		$query.=	" where date(a.receivingdate) between '".date('Y\-m\-d',strtotime($_POST['frmdate']))."' and '".date('Y\-m\-d',strtotime($_POST['todate']))."' order by a.receivingdate desc";	
	}
	
	$rs_sel	=	$dbconnection->firequery($query);	
	$i=0;
	$total	=	0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	if($i==1)
	{
	?>
	<tr>
		<td colspan="5" align="right">
		<a href="./printing/sprec.php?supervisorid=<?php echo $_POST['supervisorname'];?>&centerid=<?php echo $_POST['centername'];?>&frmdate=<?php echo $_POST['frmdate'];?>&todate=<?php echo $_POST['todate'];?>" target="_blank"><button type="button" class="btn btn-info">Take Print</button></a>
		</td>
	</tr>
	<tr>
		<th style="padding:3px; text-align:center; width:50px;">Sno</th>
		<th style="padding:3px;">Supervisor Name</th>
		<th style="padding:3px;">Center Detail</th>
		<th style="padding:3px; text-align:center; width:200px;">Receiving Date</th>
		<th style="padding:3px; text-align:center; width:150px;">Received Amount</th>
	</tr>
	
	<?php
	}
	?>
	<tr>
		<td style="text-align:center;"><?php echo $i;?></td>
		<td><?php echo $row['supervisorname'];?><br /><?php echo $row['mobilenumber'];?></td>
		<td><?php echo $row['centername'];?><br /><?php echo $row['contactnumber'];?></td>		
		<td style="text-align:center;"><?php echo date('d\-m\-Y, h:i A',strtotime($row['receivingdate']));?></td>		
		<td style="text-align:center;"><i class="fa fa-inr"></i> <?php echo $row['receivingamount'];?></td>
	</tr>
	<?php
	$total	=	$total+$row['receivingamount'];
	}
	?>
	<tr style="font-size:16px;">
		<td colspan="4" align="right"><b>Total</b>&nbsp;&nbsp;</td>
		<td align="center"><i class="fa fa-inr"></i> <b><?php echo $total;?></b></td>		
	</tr>
	<?php
}
?>