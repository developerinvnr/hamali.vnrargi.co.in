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

	function generate_combinations(array $data, array &$all = array(), array $group = array(), $value = null, $i = 0)
	{
		$keys = array_keys($data);
		if (isset($value) === true) {
			array_push($group, $value);
		}
	
		if ($i >= count($data)) {
			array_push($all, $group);
		} else {
			$currentKey     = $keys[$i];
			$currentElement = $data[$currentKey];
			foreach ($currentElement as $val) {
				generate_combinations($data, $all, $group, $val, $i + 1);
			}
		}
		return $all;
	}


	$var1	=	array();
	$wids	=	array();
	$var2	=	array();
	$mids	=	array();
	$var3	=	array();
	$pids	=	array();		
	
	$rs_sel	=	$dbconnection->firequery("select * from workcode_tbl order by actionname");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$var1[]	=	$row['actionname']." ".$row['verb'];
		$wids[]	=	$row['workcodeid'];
	}
	unset($rs_sel);
	unset($row);

	$rs_sel	=	$dbconnection->firequery("select * from material_tbl order by materialname");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$var2[]	=	$row['materialname'];
		$mids[]	=	$row['materialid'];			
	}
	unset($rs_sel);
	unset($row);

	$rs_sel	=	$dbconnection->firequery("select * from product_tbl order by productname");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$var3[]	=	$row['productname']." ".$row['operatorname']." ".$row['unit'];
		$pids[]	=	$row['productid'];
	}
	unset($rs_sel);
	unset($row);		
	
	$data	= array($var1,$var2,$var3);
	$data1	= array($wids,$mids,$pids);
	$combos	=	generate_combinations($data);
	$ids	=	generate_combinations($data1);	
	$count	=	count($combos);
	for($i=0; $i<$count;$i++)
	{
	?>
	<tr>
	<?php
		for($j=0;$j<3;$j++)
		{
			if($j==0)
			{
				$workcodeid	=	$ids[$i][$j];
				$actionname	=	$combos[$i][$j];
			}
			if($j==1)
			{
				$materialid	=	$ids[$i][$j];
				$materialname	=	$combos[$i][$j];
			}
			if($j==2)
			{
				$productid	=	$ids[$i][$j];			
				$productname=	$combos[$i][$j];
			}
			?>
			<td><?php echo $combos[$i][$j];?></td>
			<?php
		}
		$dbconnection->firequery("insert into workcode_master(workcodeid,actionname,materialid,materialname,productid,productname,creationdate,addedby) values(".$workcodeid.",'".$actionname."',".$materialid.",'".$materialname."',".$productid.",'".$productname."','".date('Y\-m\-d H:i:s')."',".$_SESSION['datadetail'][0]['sessionid'].")");
		$workcodeid	=	0;
		$materialid	=	0;
		$productid	=	0;
		$actionname	=	"";
		$materialname=	"";
		$productname=	"";
	?>
	</tr>
	<?php
	}

}
?>
