<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$t=10;

if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$rateid	=	$_POST['rateid'];
	$rs_sel	=	$dbconnection->firequery("select a.*,b.actionvalue,b.verb,b.material,b.product,b.operator,b.quantity,b.notation,b.unit,b.defaultnarration from rateexpiry_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid."");

	$i=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
	?>
	<tr>
		<td align="center"><?php echo $row['workcode'];?></td>
		<td><?php echo $row['defaultnarration'];?></td>		
		<td style="text-align:center; width:100px;"><?php echo $row['price'];?></td>
		<td style="text-align:center; width:150px;"><?php echo date('d\-m\-Y h:i A',strtotime($row['nextexpiry']));?></td>
	</tr>
	<?php
	}
	if($i==0)
	{
	?>
	<tr><td colspan="4" align="center"><i>--NO RECORD FOUND--</i></td></tr>
	<?php
	}
}
?>
