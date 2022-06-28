<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$query	=	"select * from workcode_master ";
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
	<td class="center" id="action" style="width:80px; text-align:center; padding:3px;"><?php echo $row['recordid']; $cols++;?></td>
	<td style="padding:3px;"><?php echo $row['actionvalue'];?> <?php echo $row['verb'];?> <?php echo $row['material'];?> <?php echo $row['product'];?> <?php echo $row['notation'];?>
	</td>
	<td align="center" style="padding:3px;"><i class="fa fa-inr"></i> <?php echo $row['rate'];?></td>
</tr>
<?php
}
?>
	</tbody>
</table>
</body>
</html>
