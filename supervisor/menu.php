<ul class="nav nav-list">
	<li class="<?php if($p=="dashboard") {?>active<?php } ?>">
		<a href="vnr_mainindex"><i class="menu-icon fa fa-tachometer" style="color:#008c40!important;"></i><span class="menu-text"> Dashboard </span></a><b class="arrow"></b></li>

	<li class="<?php if($p=="editextendedworkslip" || $p=="editworkslip" || $p=="workslip" || $p=="paymentslip" || $p=="advance" || $p=="getpaymentslip" || $p=="savepaymentslip" || $p=="printpaymentslip" || $p=="workslipextended") {?>active open<?php } ?>">
		<a href="#" class="dropdown-toggle"><i class="menu-icon fa fa-money" style="color:#008c40!important;"></i><span class="menu-text">Work & Payment Slip</span><b class="arrow fa fa-angle-down"></b></a><b class="arrow"></b>
		<ul class="submenu">
			<?php
			$worksliptype	=	$dbconnection->getField("supervisor_tbl","worksliptype","supervisorid=".$_SESSION['supervisordetail'][0]['sessionid']."");
			if($worksliptype=="NORMAL")
			{
			?>
			<li class="<?php if($p=="workslip" || $p=="editworkslip") {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt("work & payment slip-add work slip");?>&p=<?php echo encrypt("workslip");?>"><i class="menu-icon fa fa-caret-right" style="color:#008c40!important;"></i>Add Work Slip</a><b class="arrow"></b></li>
			<?php
			}
			else
			{
			?>
			<li class="<?php if($p=="workslipextended" || $p=="editextendedworkslip") {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt("work & payment slip-add work slip");?>&p=<?php echo encrypt("workslipextended");?>"><i class="menu-icon fa fa-caret-right" style="color:#008c40!important;"></i>Add Work Slip</a><b class="arrow"></b></li>
			<?php
			}
			?>
			<!--
			<li class="<?php if($p=="paymentslip" || $p=="getpaymentslip" || $p=="savepaymentslip" || $p=="printpaymentslip") {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt("work & payment slip-add payment slip");?>&p=<?php echo encrypt("paymentslip");?>"><i class="menu-icon fa fa-caret-right" style="color:#008c40!important;"></i>Make Payment Slip</a><b class="arrow"></b></li>

			<li class="<?php if($p=="advance") {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt("work & payment slip-advance");?>&p=<?php echo encrypt("advance");?>"><i class="menu-icon fa fa-caret-right" style="color:#008c40!important;"></i>Add Group Advance</a><b class="arrow"></b></li>
			-->
		</ul>
	</li>

	<li class="<?php if($p=="workcodelist") {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt("work code list");?>&p=<?php echo encrypt("workcodelist");?>"><i class="menu-icon fa fa-list" style="color:#008c40!important;"></i><span class="menu-text">Work Code List</span></a><b class="arrow"></b></li>

	<li class="<?php if($p=="changepassword") {?>active<?php } ?>"><a href="./vnr_mainindex?m=<?php echo encrypt("change password");?>&p=<?php echo encrypt("changepassword");?>"><i class="menu-icon fa fa-lock" style="color:#008c40!important;"></i><span class="menu-text">Change Password</span></a><b class="arrow"></b></li>
	<li class=""><a href="./vnr_logout"><i class="menu-icon fa fa-power-off" style="color:#008c40!important;"></i><span class="menu-text">Logout</span></a><b class="arrow"></b></li>					
</ul>
