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
	<option value="">--Staff Name--</option>
	<?php

	if(!$q) return;
	$rs_sel	=	$dbconnection->firequery("select * from staff_tbl where FIND_IN_SET(".$q.",collectioncenter)>0");
	while($stf=mysqli_fetch_assoc($rs_sel))
	{
	?>
	<option value="<?php echo $stf['staffid'];?>"><?php echo $stf['staffname'];?></option>
	<?php
	}
	unset($stf);
	unset($rs_sel);
}
?>