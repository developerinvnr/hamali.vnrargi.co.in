<ul class="nav nav-list">

	<li class="<?php if($p=="dashboard") {?>active<?php } ?>">
		<a href="vnr_mainindex"><i class="menu-icon fa fa-tachometer" style="color:#008c40!important;"></i><span class="menu-text"> Dashboard </span></a><b class="arrow"></b></li>

<?php
	$rs_main	=	$dbconnection->firequery("select a.menuid,b.menuname,b.menuicon,b.pagecondition from permission_tbl a inner join main_menu b on b.menuid=a.menuid where a.adminid=".$_SESSION['datadetail'][0]['sessionid']." and a.submenuid=0 and a.permission=1 order by b.displayorder");
	while($mn=mysqli_fetch_assoc($rs_main))
	{
	$query	=	"";
	$query	=	"select b.submenuname,b.displayorder,b.pagename,b.menuicon from permission_tbl a inner join submenu_tbl b on b.submenuid=a.submenuid where a.adminid=".$_SESSION['datadetail'][0]['sessionid']." and a.submenuid!=0 and a.menuid=".$mn['menuid']." and a.permission=1 order by b.displayorder";
	if($dbconnection->isRecordExist($query))
	{
	$cond	=	array();
	$rs_submenu	=	$dbconnection->firequery("select * from submenu_tbl where menuid=".$mn['menuid']."");
	while($mm=mysqli_fetch_assoc($rs_submenu))
	{
		$cond[]	=	$mm['pagename'];
	}
	?>
	<li class="<?php if(in_array($p,$cond)) {?>active open<?php } ?>">
		<a href="#" class="dropdown-toggle"><i class="menu-icon <?php echo $mn['menuicon'];?>" style="color:#008c40!important;"></i><span class="menu-text"><?php echo $mn['menuname'];?></span><b class="arrow fa fa-angle-down"></b></a><b class="arrow"></b>
		<ul class="submenu">
		<?php
		$rs_qr	=	$dbconnection->firequery($query);
		while($sub=mysqli_fetch_assoc($rs_qr))
		{
		?>
			<li class="<?php if($p==$sub['pagename']) {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt($mn['menuname']."-".$sub['submenuname']);?>&p=<?php echo encrypt($sub['pagename']);?>"><i class="menu-icon <?php echo $sub['menuicon'];?>" style="color:#008c40!important;"></i><?php echo $sub['submenuname'];?></a><b class="arrow"></b></li>
		<?php
		}
		?>
		</ul>
	</li>
	<?php
	}
	else
	{
	}
	}
	?>

	<!--<li class="<?php if($p=="setpermission") {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt($mn['menuname']."-".$sub['submenuname']);?>&p=<?php echo encrypt("setpermission");?>"><i class="menu-icon <?php echo $sub['menuicon'];?>" style="color:#008c40!important;"></i>Set Permission</a><b class="arrow"></b></li>-->
	<li class="<?php if($p=="changepassword") {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt("change password");?>&p=<?php echo encrypt("changepassword");?>"><i class="menu-icon fa fa-lock" style="color:#008c40!important;"></i><span class="menu-text">Change Password</span></a><b class="arrow"></b></li>
	<li class=""><a href="./vnr_logout"><i class="menu-icon fa fa-power-off" style="color:#008c40!important;"></i><span class="menu-text">Logout</span></a><b class="arrow"></b></li>					
</ul>
