<?php
@session_start();
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
	$query	=	"select a.price,a.workcode,a.expirydate,b.defaultnarration from rate_list a inner join workcode_master b on b.workcode=a.workcode where a.rateid=".$_REQUEST['rateid']." and FIND_IN_SET(".$_SESSION['supervisordetail'][0]['departmentid'].",b.departmentname)>0";
}
?>
<html>
<title>Work Code List</title>
<body>
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
	<thead>
		<tr>
			<th style="padding:3px; text-align:center;">Work Code</th>
			<th style="padding:3px; text-align:left;">Particular</th>
			<th style="padding:3px; text-align:center;">Rate</th>					
			<th style="padding:3px; text-align:center;">Expiry</th>					
		</tr>
	</thead>
	<tbody>
<?php
$rs_sel		=	$dbconnection->firequery($query);
$i=0;
$j=0;
while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
$j++;
$cols=0;
?>
<tr>
	<td class="center" id="action" style="width:80px; text-align:center; padding:3px;"><?php echo $row['workcode']; $cols++;?></td>
	<td style="padding:3px;"><?php echo $row['defaultnarration'];?></td>
	<td align="center" style="padding:3px;"><i class="fa fa-inr"></i> <?php echo $row['price'];?></td>
	<td align="center"><?php if($row['expirydate']!='1970-01-01' && $row['expirydate']!='0000-00-00') echo date('d\-m\-Y',strtotime($row['expirydate']));?></td>
</tr>
<?php
}
?>
	</tbody>
</table>
</body>
</html>
