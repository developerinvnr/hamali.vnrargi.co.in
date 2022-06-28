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
	$hgid	=	trim($_REQUEST['q']);
	$i=0;
	$rs_sel	=	$dbconnection->firequery("select supervisorid from workslip_tbl where groupnumber=".$hgid." and paymentstatus=0");
	while($det=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
		if($i==1)
		{
			$spids	=	$det['supervisorid'];
		}
		else
		{
			$spids.=",".$det['supervisorid'];
		}
	}
	?>
	<option value="">--Supervisor Name--</option>
	<?php	
	$rs_sp	=	$dbconnection->firequery("select supervisorid,firstname,lastname from supervisor_tbl where supervisorid in (".$spids.")");
	while($rc=mysqli_fetch_assoc($rs_sp))
	{
	?>
	<option value="<?php echo $rc['supervisorid'];?>"><?php echo $rc['firstname'];?> <?php echo $rc['lastname'];?></option>
	<?php
	}
}

?>
