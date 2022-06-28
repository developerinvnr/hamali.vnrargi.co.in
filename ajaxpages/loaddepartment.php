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
	$section	=	$_POST['selected'];
	$department	=	"";
	$i=0;
	$rs_sel	=	$dbconnection->firequery("select * from section_tbl where sectionid in (".$section.")");
	while($dep=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
		if($i==1)
		{
			$department	=	$dep['departmentname'];
		}
		else
		{
			$department.=",".$dep['departmentname'];
		}		
	}
	$depart	=	explode(",",$department);
	$rs_sel=	$dbconnection->firequery("select * from department_tbl order by departmentname");
	while($dep=mysqli_fetch_assoc($rs_sel))
	{
	?>
	<option value="<?php echo $dep['departmentid'];?>" <?php if(in_array($dep['departmentid'],$depart)) echo "selected";?>><?php echo $dep['departmentname'];?></option>
	<?php
	}
}
?>
