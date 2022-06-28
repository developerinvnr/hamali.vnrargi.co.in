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
	$q	=	strtolower($_REQUEST["q"]);
	$rs_sub	=	$dbconnection->firequery("select * from hamaligroup_tbl where companyname=".$q."");
	?>
	<option value="">--Hamali Group Name--</option>
	<?php	
	$q	=	strtolower($_REQUEST["q"]);	
	if (!$q) return;
	
	while($sub=mysqli_fetch_assoc($rs_sub))
	{
	?>
	<option value="<?php echo $sub['hgid'];?>"><?php echo $sub['groupname'];?></option>
	<?php
	}
}
?>

