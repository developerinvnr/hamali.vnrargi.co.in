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
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$q = strtolower($_REQUEST["q"]);
	?>
	<option value="">--Center Name--</option>
	<?php

	if(!$q) return;
	$centers	=	$dbconnection->getField("supervisor_tbl","centers","supervisorid=".$_REQUEST['q']."");
	$rs_center	=	$dbconnection->firequery("select * from center_tbl where centerid in (".$centers.") order by centername");
	while($center=mysqli_fetch_assoc($rs_center))
	{
	?>
	<option value="<?php echo $center['centerid'];?>"><?php echo $center['centername'];?></option>
	<?php
	}
}

?>
