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
if(isset($_SESSION['supervisordetail'][0]['sessionid']))
{
	$ids		=	$_POST['ids'];
	$query	=	$dbconnection->firequery("select a.narration,a.rate,a.quantity,a.total,b.firstname,b.lastname from workslip_detail a left join supervisor_tbl b on b.supervisorid=a.supervisorid where a.workslipid in (".$ids.") order by a.workslipid");
?>
<table style="width:100%; border:1px solid #CCCCCC;" border="1">
<tr>
	<td>S.No.</td>
	<td>Particular</td>	
	<td>Rate</td>	
	<td>Quantity</td>	
	<td>Total</td>	
	<td>Supervisor Name</td>	
</tr>
<?php
$i=0;
while($ro=mysqli_fetch_assoc($query))
{
$i++;
?>
<tr>
	<td><?php echo $i;?></td>
	<td><?php echo $ro['narration'];?></td>	
	<td><?php echo $ro['rate'];?></td>		
	<td><?php echo $ro['quantity'];?></td>		
	<td><?php echo $ro['total'];?></td>		
	<td><?php echo $ro['firstname']." ".$ro['lastname'];?></td>		
</tr>
<?php
}
?>
</table>
<?php
}
?>