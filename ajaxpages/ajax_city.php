<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",1);
ini_set("session.bug_compat_warn",1);
ini_set("session.bug_compat_42",1);


include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['logindetail'][0]['sessionid']))
{
	$q = strtolower($_REQUEST["q"]);
	if (!$q) return;
	$rs_city	=	$dbconnection->firequery("select * from city_tbl where statename=".$q."");
	?>
	<option value="">--City Name--</option>
	<?php	
	while($city=mysqli_fetch_assoc($rs_city))
	{
	?>
	<option value="<?php echo $city['cityid'];?>"><?php echo $city['cityname'];?></option>
	<?php
	}
}

?>
