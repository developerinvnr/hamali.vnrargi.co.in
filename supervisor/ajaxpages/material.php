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

if(isset($_SESSION['supervisordetail'][0]['sessionid']))
{
	$gpno		=	$_POST['gpno'];
	$workcode	=	trim($_POST['workcode']);
	$slipdate	=	$_POST['slipdate'];

	if($slipdate=="")
	{
		if($_POST['workcode']!="")
		{
			$departs	=	$dbconnection->getField("workcode_master","departmentname","workcode=".$_POST['workcode']."");
			$exp		=	explode(",",$departs);
			if(!in_array($_SESSION['supervisordetail'][0]['departmentid'],$exp))
			{
				echo "codeerror|PLEASE CHECK WORK CODE REFERENCE LIST WHICH IS ASSIGNED TO YOU FOR SELECTED HAMALI GROUP NUMBER.";
				die();
			}
		}
		
		$rateid	=	intval($dbconnection->getField("hamaligroup_tbl","ratelistname","hgid=".$_POST['gpno'].""));		
		
		$expiry	=	$dbconnection->getField("rate_list","expirydate","rateid=".$rateid." and workcode=".$workcode."");
		
		if($expiry!="" && $expiry!="1970-01-01 00:00:00" && $expiry!="0000-00-00 00:00:00")
		{
			$today	=	date('Y\-m\-d H:i:s');
			if(strtotime(date('Y\-m\-d H:i:s',strtotime($expiry)))<=strtotime(date('Y\-m\-d H:i:s',strtotime($today))))
			{
				echo "error|Rate list expired for selected hamali group. Please check and try again!";
				exit;			
			}
		}
		
	//	exit;
		?>
		<option value="">--Material--</option>
		<?php
		if($rateid!=0)
		{
			$rs_list		=	$dbconnection->firequery("select a.workcode,a.price,b.actionvalue,b.verb,b.material,b.product,b.notation from rate_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid." and a.workcode=".trim($_POST['workcode'])."");
			$o=0;
			while($rs=mysqli_fetch_array($rs_list))
			{
				$material	=	trim($rs['material']);
			}
			if($material!="")
			{
				$mat	=	explode(",",$material);
				foreach($mat as $key=>$val)
				{
				?>
				<option value="<?php echo $mat[$key];?>"><?php echo $mat[$key];?></option>
				<?php
				}
			}
			exit;
		}
		else
		{
			echo "error|Rate list not assigned";
			exit;
		}
	}
	else
	{
		if($_POST['workcode']!="")
		{
			$departs	=	$dbconnection->getField("workcode_master","departmentname","workcode=".$_POST['workcode']."");
			$exp		=	explode(",",$departs);
			if(!in_array($_SESSION['supervisordetail'][0]['departmentid'],$exp))
			{
				echo "codeerror|PLEASE CHECK WORK CODE REFERENCE LIST WHICH IS ASSIGNED TO YOU FOR SELECTED HAMALI GROUP NUMBER.";
				die();
			}
		}
		
		$rateid	=	intval($dbconnection->getField("hamaligroup_tbl","ratelistname","hgid=".$_POST['gpno'].""));		
		
	//	exit;
		?>
		<option value="">--Material--</option>
		<?php
		if($rateid!=0)
		{
			$rs_list		=	$dbconnection->firequery("select a.workcode,a.price,b.actionvalue,b.verb,b.material,b.product,b.notation from rateexpiry_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid." and a.workcode=".trim($_POST['workcode'])." and '".date('Y\-m\-d H:i:s',strtotime($slipdate))."' between expirydate and nextexpiry");
			$o=0;
			while($rs=mysqli_fetch_array($rs_list))
			{
				$material	=	trim($rs['material']);
			}
			if($material!="")
			{
				$mat	=	explode(",",$material);
				foreach($mat as $key=>$val)
				{
				?>
				<option value="<?php echo $mat[$key];?>"><?php echo $mat[$key];?></option>
				<?php
				}
			}
			exit;
		}
		else
		{
			echo "error|Rate list not assigned";
			exit;
		}
	}
}
?>

