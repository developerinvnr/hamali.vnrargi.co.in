<?php
$t=0;
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="save")
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['usertype']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select user type</li>";		
		}
		if(empty($_POST['firstname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter first name</li>";		
		}
		if(empty($_POST['lastname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter last name</li>";		
		}
		if(empty($_POST['address']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter address detail</li>";		
		}
		if(empty($_POST['username']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter login user name</li>";		
		}
		if(empty($_POST['mobile']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter mobile name</li>";		
		}
		if($_POST['password']!=$_POST['confirmpassword'])
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Both password must be same</li>";		
		}
				
		if($flag==0)
		{
			if($dbconnection->firequery("insert into user_tbl(firstname,lastname,address,username,password,mobile,usertype,created,addedby,addedbyuser,location,advanceworkcode,extendedworkslip,cangivewcp,cangiveews,canseepassword,userstype) values('".$_POST['firstname']."','".$_POST['lastname']."','".$_POST['address']."','".$_POST['username']."','".encrypt($_POST['password'])."','".$_POST['mobile']."','ADMIN','".date('Y\-m\-d H:i:s')."',".$_SESSION['datadetail'][0]['sessionid'].",'".$_SESSION['datadetail'][0]['authname']."','".$_POST['locations']."',".intval($_POST['advanceworkcode']).",".intval($_POST['extendedworkslip']).",".intval($_POST['cangivewcp']).",".intval($_POST['cangiveews']).",".intval($_POST['canseepassword']).",'".$_POST['usertype']."')"))
			{
				unset($fields);
				unset($values);				
				unset($flag);				
				unset($msgErr);		
				$_SESSION['success']	=	"Admin detail added successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Login user name or mobile number already exist. Please try with another user name / mobile number!";
			}
		}
	}
	if($_POST['acn']=="update")
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['usertype']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select user type</li>";		
		}
		if(empty($_POST['firstname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter first name</li>";		
		}
		if(empty($_POST['lastname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter last name</li>";		
		}
		if(empty($_POST['address']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter address detail</li>";		
		}
		if(empty($_POST['username']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter login user name</li>";		
		}
		if(empty($_POST['mobile']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter mobile name</li>";		
		}
		if($_POST['password']!=$_POST['confirmpassword'])
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Both password must be same</li>";		
		}
		$loc	=	"";
		$i=0;
		foreach($_POST['location'] as $key=>$val)
		{
			$i++;
		}
		if($i==0)
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Locaion is mandatory field.</li>";
		}		
		
		if($flag==0)
		{	
			if($dbconnection->firequery("update user_tbl set firstname='".$_POST['firstname']."',lastname='".$_POST['lastname']."',address='".$_POST['address']."',username='".$_POST['username']."',password='".encrypt($_POST['password'])."',mobile='".$_POST['mobile']."',location='".$_POST['locations']."',advanceworkcode=".intval($_POST['advanceworkcode']).",extendedworkslip=".intval($_POST['extendedworkslip']).",cangivewcp=".intval($_POST['cangivewcp']).",cangiveews=".intval($_POST['cangiveews']).",canseepassword=".intval($_POST['canseepassword']).",userstype='".$_POST['usertype']."' where userid=".trim(decryptvalue($_POST['userid'])).""))
			{
				$_SESSION['success']	=	"Admin detail updated successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
		}		
	}
	if($_POST['acn']=="delete")
	{
		if($dbconnection->firequery("delete from user_tbl where userid=".trim(decryptvalue($_POST['userid'])).""))
		{ 
			$dbconnection->firequery("delete from permission_tbl where adminid=".trim(decryptvalue($_POST['userid']))."");
			$_SESSION['success']	=	"Admin detail deleted successfully!";
			echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
			exit;
		}
		else
		{
			$_SESSION['warning']	=	"Found some problem. Please try again!";
			echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
			exit;
		}
	}

}
else
{
	$_POST['acn']	=	"save";
	if($_REQUEST['userid']!="")
	{
		$rs_sel	=	$dbconnection->firequery("select * from user_tbl where userid=".trim(decryptvalue($_REQUEST['userid']))."");
		while($rw=mysqli_fetch_assoc($rs_sel))
		{
			$_POST['usertype']			=	$rw['userstype'];
			$_POST['firstname']			=	$rw['firstname'];
			$_POST['lastname']			=	$rw['lastname'];
			$_POST['address']			=	$rw['address'];
			$_POST['username']			=	$rw['username'];
			$_POST['password']			=	trim(decryptvalue($rw['password']));
			$_POST['mobile']			=	$rw['mobile'];
			$_POST['loc']				=	$rw['location'];
			$_POST['advanceworkcode']	=	$rw['advanceworkcode'];
			$_POST['extendedworkslip']	=	$rw['extendedworkslip'];
			$_POST['cangivewcp']		=	$rw['cangivewcp'];
			$_POST['cangiveews']		=	$rw['cangiveews'];
			$_POST['canseepassword']	=	$rw['canseepassword'];
		}
		$_POST['acn']	=	"update";		
	}
}
$canseepassword	=	$dbconnection->getField("user_tbl","canseepassword","userid=".$_SESSION['datadetail'][0]['sessionid']."");
$cangivewcp		=	$dbconnection->getField("user_tbl","cangivewcp","userid=".$_SESSION['datadetail'][0]['sessionid']."");
$cangiveews		=	$dbconnection->getField("user_tbl","cangiveews","userid=".$_SESSION['datadetail'][0]['sessionid']."");

?>
<div class="main-content">
<style>
.multiselect
{
height:35px;
width:250px;
text-align:left;
}
.fa-caret-down
{
	width:100%;
	float:right;
}
.multiselect-container>li.multiselect-group-clickable label
{
	float:left;
	cursor:pointer;
	margin-left:0px;
	padding:5px;
	/*background-color:#4F99C6;*/
	background-color:#008C40;
	color:white;
	width:100%;
}
.multiselect-container>li>a>label>input[type=checkbox]
{
	float:right;
	width:210px;
	position:absolute;
}
.multiselect-all label
{
	padding:5px;
	text-transform:uppercase;
	/*background-color:#4F99C6;*/
	background-color:#008C40;
	color:white;
}

</style>

	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li><i class="ace-icon fa fa-home home-icon"></i><a href="./mainindex.php" style="text-decoration:none;">Home</a></li>
				<?php
				$m		=	trim(decryptvalue($_REQUEST['m']));
				$exp	=	explode("-",$m);
				$cnt	=	count($exp);
				for($i=0;$i<$cnt;$i++)
				{
					if($i==($cnt-1))
					{
					?>
						<li class="active"><?php echo ucwords($exp[$i]);?></li>
					<?php
					}
					else
					{
					?>
						<li><a href="#"><?php echo ucwords($exp[$i]);?></a></li>
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
<div class="tabbable">
		<ul class="nav nav-tabs padding-18">
			<li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-plus-circle bigger-120" style="vertical-align:bottom;"></i> Add Admin</a></li>	
			<li><a data-toggle="tab" href="#feed"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> Admin List</a></li>
		</ul>

	
		<div class="tab-content no-border padding-24" style="border:1px solid #ddd; min-height:150px;">
			<div id="home" class="tab-pane in active">
				<div class="row">
	<div class="alert alert-block alert-success msg" style="padding:5px; display:none;">
		<button type="button" class="close" data-dismiss="alert">
			<i class="ace-icon fa fa-times"></i>	
		</button>
		<i class="ace-icon fa fa-remove green"></i> <label id="setmsg"></label>
	</div>
					
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
						<i class="ace-icon fa fa-remove green"></i>
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
						<i class="ace-icon fa fa-remove green"></i> Action Result
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
			<input type="hidden" name="pk" id="pk" value="userid" />
			<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
			<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />						
			<input type="hidden" name="userid" id="userid" value="<?php echo $_REQUEST['userid'];?>" />
			<div class="form-group">			
				<div class="col-sm-3">
				<label id="lab">Location Name<label id="req">&nbsp;</label></label>
				<select class="form-control multiselect" name="location[]" id="location" multiple="multiple" placeholder="" onchange="AddLocation()">
				<?php
				if($_POST['loc']!="")
				{
					$exp	=	explode(",",$_POST['loc']);
				}					
				
				$rs_loc	=	$dbconnection->firequery("select * from location_tbl order by locationname");
				$val="";
				$i=0;
				$locs	=	"";
				while($locc=mysqli_fetch_assoc($rs_loc))
				{
					$i++;
					if($i==1)
					{
						$locs	=	$locc['locationid'];
					}
					else
					{
						$locs.=	",".$locc['locationid'];
					}
					if($_POST['loc']!="")
					{
					?>
					<option value="<?php echo $locc['locationid'];?>"<?php if(in_array($locc['locationid'],$exp)) { echo "selected"; }?>><?php echo $locc['locationname'];?></option>
					<?php
					}
					else
					{
					?>
					<option value="<?php echo $locc['locationid'];?>" selected><?php echo $locc['locationname'];?></option>
					<?php
					}
				}
				?>
				</select>
				<input type="hidden" name="locations" id="locations" value="<?php if($_POST['loc']!="") echo $_POST['loc']; else echo $locs;?>">
				</div>
				<div class="col-sm-3">
					<label id="lab">User Type<label id="req">*</label></label>
					<select class="form-control" name="usertype" id="usertype" onKeyPress="return OnKeyPress(this event)" required tabindex="<?php echo $t;?>">
						<option value="">--Select User Type--</option>
						<option value="ADMIN" <?php if($_POST['usertype']=="ADMIN") { echo "selected"; }?>>ADMIN</option>
						<option value="PAYMENT" <?php if($_POST['usertype']=="PAYMENT") { echo "selected"; }?>>PAYMENT PROCESSOR</option>
					</select>
				</div>
			
				<div class="col-sm-3">
					<label id="lab">First Name<label id="req">*</label></label>
					<input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $_POST['firstname'];?>" placeholder="First name" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autofocus autocomplete="off"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Last Name<label id="req">*</label></label>
					<input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $_POST['lastname'];?>" placeholder="Last name" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-12">&nbsp;</div>
				<div class="col-sm-3">
					<label id="lab">Mobile Number<label id="req">*</label></label>
					<input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo $_POST['mobile'];?>" placeholder="Mobile number" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Address<label id="req">*</label></label>
					<input type="text" class="form-control" name="address" id="address" value="<?php echo $_POST['address'];?>" placeholder="Address" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Login User Name<label id="req">*</label></label>
					<input type="text" class="form-control" name="username" id="username" value="<?php echo $_POST['username'];?>" placeholder="Mobile number" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Password<label id="req">*</label></label>
					<input type="password" class="form-control" name="password" id="password" value="<?php echo $_POST['password'];?>" placeholder="Password" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>			
				<div class="col-sm-12">&nbsp;</div>				
				<div class="col-sm-3">
					<label id="lab">Confirm Password<label id="req">*</label></label>
					<input type="password" class="form-control" name="confirmpassword" id="confirmpassword" value="<?php echo $_POST['password'];?>" placeholder="Confirm password" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;" onchange="CheckPassword(this.value)"/>
				</div>
				<?php
				if($_SESSION['datadetail'][0]['authtype']=="SUPER ADMIN")
				{
				?>
				<div class="col-sm-3">
					<label id="lab">Can Use Advance Work Code Master Format?<label id="req">&nbsp;</label></label>
					<select class="form-control" name="advanceworkcode" id="advanceworkcode" onKeyPress="return OnKeyPress(this event)" required tabindex="<?php echo $t;?>">
						<option value="0" <?php if($_POST['advanceworkcode']=="0") { echo "selected"; }?>>NO</option>
						<option value="1" <?php if($_POST['advanceworkcode']=="1") { echo "selected"; }?>>YES</option>
					</select>
				</div>
				<div class="col-sm-6">
					<label id="lab">Can Use Extended Workslip?<label id="req">&nbsp;</label></label>
					<select class="form-control" name="extendedworkslip" id="extendedworkslip" onKeyPress="return OnKeyPress(this event)" required tabindex="<?php echo $t;?>">
						<option value="0" <?php if($_POST['extendedworkslip']=="0") { echo "selected"; }?>>NO</option>
						<option value="1" <?php if($_POST['extendedworkslip']=="1") { echo "selected"; }?>>YES</option>
					</select>
				</div>
				<div class="col-sm-12">&nbsp;</div>				
				<div class="col-sm-6">
					<label id="lab">Can Give Permission of Work Code Master Format?<label id="req">&nbsp;</label></label>
					<select class="form-control" name="cangivewcp" id="cangivewcp" onKeyPress="return OnKeyPress(this event)" required tabindex="<?php echo $t;?>">
						<option value="0" <?php if($_POST['cangivewcp']=="0") { echo "selected"; }?>>NO</option>
						<option value="1" <?php if($_POST['cangivewcp']=="1") { echo "selected"; }?>>YES</option>
					</select>
				</div>
				<div class="col-sm-6">
					<label id="lab">Can Give Permission of Extended Workslip Format?<label id="req">&nbsp;</label></label>
					<select class="form-control" name="cangiveews" id="cangiveews" onKeyPress="return OnKeyPress(this event)" required tabindex="<?php echo $t;?>">
						<option value="0" <?php if($_POST['cangiveews']=="0") { echo "selected"; }?>>NO</option>
						<option value="1" <?php if($_POST['cangiveews']=="1") { echo "selected"; }?>>YES</option>
					</select>
				</div>
				<div class="col-sm-12">&nbsp;</div>				
				<?php
				}
				else
				{
				if($cangivewcp==1)
				{
				?>
				<div class="col-sm-3">
					<label id="lab">Can Use Advance Work Code Master Format?<label id="req">&nbsp;</label></label>
					<select class="form-control" name="advanceworkcode" id="advanceworkcode" onKeyPress="return OnKeyPress(this event)" required tabindex="<?php echo $t;?>">
						<option value="0" <?php if($_POST['advanceworkcode']=="0") { echo "selected"; }?>>NO</option>
						<option value="1" <?php if($_POST['advanceworkcode']=="1") { echo "selected"; }?>>YES</option>
					</select>
				</div>				
				<?php
				}
				if($cangiveews==1)
				{
				?>
				<div class="col-sm-3">
					<label id="lab">Can Use Extended Workslip Format?<label id="req">&nbsp;</label></label>
					<select class="form-control" name="extendedworkslip" id="extendedworkslip" onKeyPress="return OnKeyPress(this event)" required tabindex="<?php echo $t;?>">
						<option value="0" <?php if($_POST['extendedworkslip']=="0") { echo "selected"; }?>>NO</option>
						<option value="1" <?php if($_POST['extendedworkslip']=="1") { echo "selected"; }?>>YES</option>
					</select>
				</div>
				<?php
				}
				}
				?>
				<div class="col-sm-3">
					<label id="lab">Can See Password?<label id="req">&nbsp;</label></label>
					<select class="form-control" name="canseepassword" id="canseepassword" onKeyPress="return OnKeyPress(this event)" required tabindex="<?php echo $t;?>">
						<option value="0" <?php if($_POST['canseepassword']=="0") { echo "selected"; }?>>NO</option>
						<option value="1" <?php if($_POST['canseepassword']=="1") { echo "selected"; }?>>YES</option>
					</select>
				</div>


				<div class="col-sm-3" style="text-align:left;">
					<br id="forbutton" />
					<?php
					if($_POST['acn']=="save")
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Add New Admin/Payment Processor</button>
					<?php
					}
					else
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Update Admin/Payment Processor Detail</button>
					<?php
					}
					?>
				</div>
			</div>		
		</form>
				</div>
			</div>
	</div>
			<div id="feed" class="tab-pane">
				<div class="row">
	<form name="pagedata" id="pagedata" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="12">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="form-control" onchange="GetLoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="100">100</option>
							<option value="300">300</option>							
							<option value="500">500</option>							
						</select> records
					</div>
					<div id="tableheadsearch">
						<div class="nav-search" id="nav-search">
							<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="pagesearch" name="pagesearch" autocomplete="off" onchange="LoadDefault()" tabindex="<?php echo $t++;?>" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
						</div>
					</div>
					</th>
				</tr>
				<tr>
					<th style="padding:3px; text-align:center; width:80px;">S.NO.</th>
					<th style="padding:3px;">First Name</th>
					<th style="padding:3px;">Last Name</th>					
					<th style="padding:3px;">Mobile Number</th>
					<th style="padding:3px;">Address</th>					
					<th style="padding:3px;">User Name</th>
					<?php
					if($canseepassword==1)
					{
					?>
					<th style="padding:3px;">Password</th>					
					<?php
					}
					?>
					<th style="padding:3px;" class="center"><i class="fa fa-edit"></i></th>
					<th style="padding:3px;" class="center"><i class="fa fa-remove"></i></th>
				</tr>
			</thead>
			<tbody class="tabledata">
				<div class="loader"></div>			
			</tbody>
		</table>
	</div>
	</form>
				</div>
			</div><!-- /#feed -->	
		</div>
	</div>
						<div class="hr hr32 hr-dotted"></div>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	
		
	</div>
</div>
<script src="./assets/js/jquery-2.1.4.min.js"></script>
<script src="./assets/js/bootstrap-multiselect.min.js"></script>
<script>
    $(document).ready(function() {
        LoadDefault();
    });

	$('#location').multiselect({
	 nonSelectedText:"Location",
	 enableCaseInsensitiveFiltering:1,
	 maxHeight:300,
	 maxWidth:250,
	 buttonClass: 'btn btn-white btn-primary'
	});

	function AddLocation()
	{
		var selected = $('#location').val();
		document.getElementById("locations").value=selected;
	}

	function VisiblePass(obj)
	{
		var a	=	$('.pass').prop("type");
		if(a=="password")
		$(".pass").attr('type','text');
		else
		$(".pass").attr('type','password');

		var b	=	$('.pas').prop("type");
		if(b=="password")
		$(".pas").attr('type','text');
		else
		$(".pas").attr('type','password');
	}
	function CheckPassword(val)
	{
		var pass	=	document.getElementById("password").value;
		var cpass	=	document.getElementById("confirmpassword").value;		
		if(pass!=cpass)
		{
			$(".msg").css("display","");
			$("#setmsg").html("Password and confirm password must be same.");					
			document.getElementById("password").value="";
			document.getElementById("confirmpassword").value="";
			$("#password").focus();
		}
		else
		{
			$("#setmsg").html("");					
			$(".msg").css("display","none");
		}
	}

	function DeleteRecord(obj)
	{
		document.getElementById("acn").value="delete";
		document.getElementById("userid").value=obj;
		$("#frm").submit();
	}
	
	function GetLoadDefault()
	{
		document.getElementById("pagenumber").value=1;
		LoadDefault();
	}

	function LoadDefault()
	{
		var pagesize	=	document.getElementById("pagesize").value;
		var inputsearch	=	document.getElementById("pagesearch").value;
		var pagenumber	=	document.getElementById("pagenumber").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();		
		$.post("ajaxpages/admins.php",
			{
				processname: "admin",
				pagesize: pagesize,
				searchvalue:inputsearch,
				pagenumber: pagenumber,
				m:m,
				p:p,
				pk:pk
			},
			function(data, status){
				$(".loader").hide();
				$(".tabledata").html(data);
			});
	}
    $(document).on('click','.pagelinks',function(){
		document.getElementById("pagenumber").value=$(this).data('runid');
		LoadDefault();
    });
</script>

<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>

<script src="./js/bootbox.min.js"></script>
<script type="text/javascript">
	function CallBox(obj)
	{
		bootbox.confirm("Do you want to delete this record!", function(result){ if(result){ DeleteRecord(obj);} });	
	}
</script>
<script src="./assets/js/bootstrap.min.js"></script>
