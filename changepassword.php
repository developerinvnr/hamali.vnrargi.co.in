<?php
$t=1;
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="save")
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['oldpassword']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter your old password</li>";
		}
		if(empty($_POST['newpassword']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter new password</li>";
		}
		if(empty($_POST['confirmnewpassword']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter confirm password</li>";
		}
		if(!empty($_POST['confirmnewpassword']) && !empty($_POST['newpassword']))
		{
			if($_POST['newpassword']!=$_POST['confirmnewpassword'])
			{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Password and confirm password must be same</li>";
			}
		}
		if($flag==0)
		{
			if($dbconnection->isRecordExist("select * from user_tbl where password='".encrypt($_POST['oldpassword'])."' and userid=".$_SESSION['datadetail'][0]['sessionid']." and usertype='".$_SESSION['datadetail'][0]['authtype']."'"))
			{
				$dbconnection->firequery("update user_tbl set password='".encrypt($_POST['newpassword'])."' where userid=".$_SESSION['datadetail'][0]['sessionid']." and usertype='".$_SESSION['datadetail'][0]['authtype']."'");
				$_SESSION['datadetail'][0]['pass']	=	encrypt($_POST['newpassword']);
				$_SESSION['success']	=	"Your new password updated successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.encrypt("change password").'&p='.encrypt("changepassword").'";</script>';
				exit;

			}
			else
			{
				$msgErr.=	"<br><li>Your old password does not matched. Please try again!</li>";
			}
		}		
	}
}
else
{
	$_POST['acn']="save";
}
?>
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li><i class="ace-icon fa fa-home home-icon"></i><a href="./index.php" style="text-decoration:none;">Home</a></li>
				<?php
				$m		=	trim(decryptvalue($_REQUEST['m']));
				$exp	=	explode("-",$m);
				$cnt	=	count($exp);
				for($i=0;$i<$cnt;$i++)
				{
					if($i==($cnt-1))
					{
					?>
						<li class="active"><?php echo ucfirst($exp[$i]);?></li>
					<?php
					}
					else
					{
					?>
						<li><a href="#"><?php echo ucfirst($exp[$i]);?></a></li>
					<?php
					}
				}
				?>
			</ul><!-- /.breadcrumb -->
			<!--
			<div class="nav-search" id="nav-search">
				<form class="form-search">
					<span class="input-icon">
						<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
						<i class="ace-icon fa fa-search nav-search-icon"></i>								</span>
				</form>
			</div>--><!-- /.nav-search -->
		</div>

			<div class="page-content">
				<div class="ace-settings-container" id="ace-settings-container"></div>
				<div class="row">
					<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<?php 
					if(isset($_SESSION['success'])) 
					{
					?>
					<div class="alert alert-block alert-success">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>	
						</button>
						<i class="ace-icon fa fa-check green"></i>
						 <?php 
							echo $_SESSION['success']; 
							unset($_SESSION['success']);
						 ?>
					</div>
					<?php
					}
					if(isset($_SESSION['warning'])) 
					{
					?>
					<div class="alert alert-block alert-warning">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>	
						</button>
						<i class="ace-icon fa fa-check green"></i>
						 <?php 
							echo $_SESSION['warning']; 
							unset($_SESSION['warning']);
						 ?>
					</div>
					<?php
					}
					if($msgErr!="")
					{
					?>
					<div class="alert alert-block alert-warning">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>	
						</button>
						Action Result!
						 <?php 
							echo $msgErr; 
							unset($msgErr);
						 ?>
					</div>
					<?php					
					}
					?>
<div class="row">
		<form name="frm" id="frm" action="#" method="post">
			<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
			<div class="form-group">	
				<div class="col-sm-2">
					<label id="lab">Enter Old Password<label id="req">*</label></label>
					<input type="password" class="form-control" name="oldpassword" id="oldpassword" value="<?php echo $_POST['oldpassword'];?>" placeholder="Enter old password" onKeyPress="return OnKeyPress(this, event)" required autocomplete="off" tabindex="<?php echo $t++;?>" autofocus/>
				</div>
				<div class="col-sm-2">
					<label id="lab">New Password<label id="req">*</label></label>
					<input type="password" class="form-control" name="newpassword" id="newpassword" value="<?php echo $_POST['newpassword'];?>" placeholder="Enter new password" onKeyPress="return OnKeyPress(this, event)" required autocomplete="off" tabindex="<?php echo $t++;?>"/>
				</div>
				<div class="col-sm-2">
					<label id="lab">Confirm New Password<label id="req">*</label></label>
					<input type="password" class="form-control" name="confirmnewpassword" id="confirmnewpassword" value="<?php echo $_POST['confirmnewpassword'];?>" placeholder="Enter new password again" onKeyPress="return OnKeyPress(this, event)" required autocomplete="off" tabindex="<?php echo $t++;?>"/>
				</div>
				<div class="col-sm-3">
					<br id="forbutton" />
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Change Password</button>
				</div>
			</div>		
		</form>
</div>
<div class="row"><div class="col-sm-12">&nbsp;</div></div>
						<div class="hr hr32 hr-dotted"></div>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	
		
	</div>
</div>
