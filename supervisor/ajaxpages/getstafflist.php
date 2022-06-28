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

	$centers	=	$dbconnection->getField("supervisor_tbl","centers","supervisorid=".$q."");
	$staff		=	array();
	$center		=	explode(",",$centers);
	$j=0;
	for($i=0;$i<count($center);$i++)
	{
		$rs_sel	=	$dbconnection->firequery("select * from staff_tbl where FIND_IN_SET(".$center[$i].",collectioncenter)>0");
		while($stf=mysqli_fetch_assoc($rs_sel))
		{
			$staff[$j]	=	$stf['staffid'];
			$j++;
		}
	}
	unset($rs_sel);
	unset($stf);
	$staff	=	array_unique($staff);
	$staff	=	implode(",",$staff);
	$rs_sel	=	$dbconnection->firequery("select * from staff_tbl where staffid in (".$staff.") order by staffname");	
	?>
	<option value="">--Staff Name--</option>
	<?php
	while($staff=mysqli_fetch_assoc($rs_sel))
	{
	?>
	<option value="<?php echo $staff['staffid'];?>"><?php echo ucwords($staff['staffname']);?></option>
	<?php
	}
}

?>
