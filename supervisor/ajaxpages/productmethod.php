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
/*	
	foreach($_SESSION['records'] as $key=>$val)
	{
		if($_SESSION['records'][$key]['workcode']==$workcode)
		{
			echo "error|Work code already exist in your work slip. Please check and try againa.";
			exit;
		}
	}
*/	
	
	$rateid	=	intval($dbconnection->getField("hamaligroup_tbl","ratelistname","hgid=".$_POST['gpno'].""));
	if($rateid!=0)
	{
		$rs_list		=	$dbconnection->firequery("select a.workcode,a.price,b.actionvalue,b.verb,b.material,b.product,b.notation from rate_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid." and a.workcode=".$_POST['workcode']."");
		$o=0;
		while($rs=mysqli_fetch_array($rs_list))
		{
			$product	=	trim($rs['product']);
		}
		?>
		<option value="">--Product/Method--</option>
		<?php
		if($product!="")
		{
			$prod	=	explode(",",$product);
			foreach($prod as $key=>$val)
			{
			?>
			<option value="<?php echo $prod[$key];?>"><?php echo $prod[$key];?></option>
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
?>

