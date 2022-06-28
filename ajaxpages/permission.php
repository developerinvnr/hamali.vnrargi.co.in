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
	$rs_main	=	$dbconnection->firequery("select * from main_menu order by displayorder");
	while($mn=mysqli_fetch_assoc($rs_main))
	{
		$dbconnection->firequery("insert into permission_tbl(menuid,adminid) values(".$mn['menuid'].",".intval($_POST['userid']).")");
		$rs_sel	=	$dbconnection->firequery("select * from submenu_tbl where menuid=".$mn['menuid']." order by displayorder");
		while($ins=mysqli_fetch_assoc($rs_sel))
		{
			$dbconnection->firequery("insert into permission_tbl(menuid,submenuid,adminid) values(".$mn['menuid'].",".$ins['submenuid'].",".$_POST['userid'].")");
		}
	}
	unset($mn);
	unset($rs_main);
	unset($ins);
	unset($rs_sel);
	$rs_menu	=	$dbconnection->firequery("select a.*,b.menuname from permission_tbl a inner join main_menu b on b.menuid=a.menuid where a.submenuid=0 and a.adminid=".$_POST['userid']." order by b.displayorder");
	$c=0;
	while($mn=mysqli_fetch_assoc($rs_menu))
	{
	$c++;
	?>
	<tr style="background-color:#008c40!important; color:#FFFFFF;">
		<td><input type="checkbox" name="main<?php echo $c;?>" id="main<?php echo $c;?>" class="main<?php echo $c;?>" onclick="SetMenuPermission(<?php echo $mn['id'];?>,<?php echo $mn['permission'];?>)" style="vertical-align:text-top; margin-right:5px;" <?php if($mn['permission']==1) echo "checked";?> /><?php echo strtoupper($mn['menuname']);?></td>
	</tr>
	<tr>
		<td>
		<?php
		$rs_sub	=	$dbconnection->firequery("select a.*,b.submenuname from permission_tbl a inner join submenu_tbl b on b.submenuid=a.submenuid where a.menuid=".$mn['menuid']." and a.adminid=".$_POST['userid']." order by b.displayorder");
		$d=0;
		while($sub=mysqli_fetch_assoc($rs_sub))
		{
		$d++;
		?>
		<label style="width:300px; float:left; margin-right:10px; padding:5px;"><input type="checkbox" name="main<?php echo $c;?><?php echo $d;?>" id="main<?php echo $c;?><?php echo $d;?>" class="main<?php echo $c;?><?php echo $d;?>" <?php if($sub['permission']==1) echo "checked";?> onclick="SetSubMenuPermission(<?php echo $sub['id'];?>,<?php echo $sub['permission'];?>)" /> <?php echo $sub['submenuname'];?></label>
		<?php
		}
		?>
		</td>
	</tr>
	<?php
	}
}
?>
