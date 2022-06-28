<?php
$t=0;
$tablename	=	"category_tbl";
$pk			=	$dbconnection->getField("master_tbl","fieldname","tablename='".$tablename."' and pk='YES'");
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="save")
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['firstname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter your name</li>";
		}
		if(empty($_POST['gender']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select gender</li>";
		}
		if(empty($_POST['usertype']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Please select user type</li>";
		}
		if(empty($_POST['username']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter user name</li>";
		}
		if(empty($_POST['password']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter password</li>";
		}
		if(empty($_POST['confirmpassword']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter confirm password</li>";
		}
		if($_POST['password']!=$_POST['confirmpassword'])
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Both password must be same.</li>";
		}
		if(empty($_POST['mobilenumber']))
		{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Enter mobile number</li>";
		}
		else
		{
			if(!CheckMobile($_POST['mobilenumber']))
			{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Enter valid mobile number</li>";
			}
		}		
		$depname	=	"";
		$i=0;
		foreach($_POST['departmentname'] as $key=>$val)
		{
			$i++;
			if($i==1)
			{
				$depname.=	$_POST['departmentname'][$key];
			}
			else
			{
				$depname.=",".$_POST['departmentname'][$key];
			}				
		}
		if($depname=="")
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select atleast one department</li>";
		}
		if($flag==0)
		{
			if($dbconnection->firequery("insert into user_tbl(firstname,address,username,password,mobile,gender,usertype,departmentname,addedby,addedbyuser) values('".$_POST['firstname']."','".$_POST['address']."','".$_POST['username']."','".encrypt($_POST['password'])."','".$_POST['mobilenumber']."','".$_POST['gender']."','".$_POST['usertype']."','".$depname."',".$_SESSION['datadetail'][0]['sessionid'].",'".$_SESSION['datadetail'][0]['authtype']."')"))
			{
				$_SESSION['success']	=	"User detail added successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again.";
			}
		}
	}
	if($_POST['acn']=="update")
	{

		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['firstname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter your name</li>";
		}
		if(empty($_POST['gender']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select gender</li>";
		}
		if(empty($_POST['usertype']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Please select user type</li>";
		}
		if(empty($_POST['username']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter user name</li>";
		}
		if(empty($_POST['password']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter password</li>";
		}
		if(empty($_POST['confirmpassword']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter confirm password</li>";
		}
		if($_POST['password']!=$_POST['confirmpassword'])
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Both password must be same.</li>";
		}
		if(empty($_POST['mobilenumber']))
		{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Enter mobile number</li>";
		}
		else
		{
			if(!CheckMobile($_POST['mobilenumber']))
			{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Enter valid mobile number</li>";
			}
		}		
		$depname	=	"";
		$i=0;
		foreach($_POST['departmentname'] as $key=>$val)
		{
			$i++;
			if($i==1)
			{
				$depname.=	$_POST['departmentname'][$key];
			}
			else
			{
				$depname.=",".$_POST['departmentname'][$key];
			}				
		}
		if($depname=="")
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select atleast one department</li>";
		}
		
		if($flag==0)
		{
			if($dbconnection->firequery("update user_tbl set firstname='".$_POST['firstname']."',address='".$_POST['address']."',mobile='".$_POST['mobilenumber']."',username='".$_POST['username']."',usertype='".$_POST['usertype']."',password='".encrypt($_POST['password'])."',gender='".$_POST['gender']."',departmentname='".$depname."' where userid=".trim(decryptvalue($_POST['userid'])).""))
			{
				$_SESSION['success']	=	"User detail updated successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again.";
			}
		}
	}
	if($_POST['acn']=="delete")
	{
		if(!$dbconnection->isRecordExist("select * from customertest_tbl where printedbyid=".$_SESSION['datadetail'][0]['sessionid']." and printedbyuser='".$_SESSION['datadetail'][0]['authtype']."'"))
		{
			if($dbconnection->firequery("delete from user_tbl where userid=".trim(decryptvalue($_POST['userid'])).""))
			{ 
				$_SESSION['success']	=	"User detail deleted successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
		}
		else
		{
			$_SESSION['warning']	=	"User detail can not be deleted. It is mapped with some transaction.";
			echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
			exit;
		}
	}
}
$_POST['acn']	=	"save";
if($_REQUEST['userid']!="")
{
	$rs_sel	=	$dbconnection->firequery("select * from user_tbl where userid=".trim(decryptvalue($_REQUEST['userid']))."");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$_POST['firstname']		=	$row['firstname'];
		$_POST['address']		=	$row['address'];
		$_POST['username']		=	$row['username'];		
		$_POST['password']		=	trim(decryptvalue($row['password']));
		$_POST['gender']		=	$row['gender'];						
		$_POST['mobilenumber']	=	$row['mobile'];				
		$_POST['departmentname']=	$row['departmentname'];				
		$_POST['usertype']		=	$row['usertype'];						
	}
	$_POST['acn']	=	"update";
}
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
float:right;
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
			<input type="hidden" name="pk" id="pk" value="<?php echo $pk;?>" />
			<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
			<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />						
			<input type="hidden" name="userid" id="userid" value="<?php echo $_REQUEST['userid'];?>" />
			<input type="hidden" name="tablename" id="tablename" value="<?php echo encrypt($tablename);?>" />			
			<div class="form-group">
				<div class="col-sm-3">
					<label id="lab">Fisrt Name<label id="req">*</label></label>
					<input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $_POST['firstname'];?>" placeholder="First name" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Mobile Number<label id="req">*</label></label>
					<input type="text" class="form-control" name="mobilenumber" required id="mobilenumber" value="<?php echo $_POST['mobilenumber'];?>" placeholder="Mobile number" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" onchange="CheckUser()" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Gender<label id="req">&nbsp;</label></label>
					<select class="form-control" name="gender" id="gender" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>">
							<option value="MALE" <?php if($_POST['gender']=="MALE") echo "selected";?>>MALE</option>
							<option value="FEMALE" <?php if($_POST['gender']=="FEMALE") echo "selected";?>>FEMALE</option>							
					</select>
				</div>
				<div class="col-sm-3">
					<label id="lab">Address<label id="req">*</label></label>
					<input type="text" class="form-control" name="address" id="address" value="<?php echo $_POST['address'];?>" placeholder="address detail" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-12">&nbsp;</div>
				<div class="col-sm-3">
					<?php
					$rs_sel	=	$dbconnection->firequery("select * from category_tbl order by categoryname");
					?>
					<label id="lab">Department Name<label id="req">*</label></label>
					<select class="form-control multiselect" name="departmentname[]" multiple="multiple" id="departmentname" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off">
						<?php
						if($_POST['departmentname']!="")
						{
							$exp	=	explode(",",$_POST['departmentname']);
							$i=0;
							while($dep=mysqli_fetch_assoc($rs_sel))
							{
							?>
							<option value="<?php echo $dep['categoryid'];?>" <?php if(in_array($dep['categoryid'],$exp)) echo "selected";?>><?php echo $dep['categoryname'];?></option>
							<?php
							$i++;
							}
						}
						else
						{
							while($dep=mysqli_fetch_assoc($rs_sel))
							{
							?>
							<option value="<?php echo $dep['categoryid'];?>" <?php if($dep['categoryid']==$_POST['departmentname']) echo "selected"; ?>><?php echo $dep['categoryname'];?></option>
							<?php
							}
						}
						?>
					</select>
				</div>

				<div class="col-sm-3">
					<label id="lab">User Name<label id="req">*</label></label>
					<input type="text" class="form-control" name="username" id="username" value="<?php echo $_POST['username'];?>" placeholder="User name" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off" onchange="CheckUsername(this.value)"/>
				</div>			

				<div class="col-sm-3">
					<label id="lab" style="width:100%;">Password<label id="req">*</label><i class="fa fa-eye" onclick="VisiblePass()" style="float:right; font-size:14px;"></i></label>
					<input type="password" class="form-control pass" name="password" id="password" value="<?php echo $_POST['password'];?>" placeholder="Password" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off"/><br />					
				</div>
				<div class="col-sm-3">
					<label id="lab">Confirm Password<label id="req">*</label></label>
					<input type="password" class="form-control pas" name="confirmpassword" id="confirmpassword" value="<?php echo $_POST['password'];?>" placeholder="Confirm password" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" onchange="CheckPassword(this.value)"/>
				</div>
				<div class="col-sm-12"></div>
				<div class="col-sm-3">
					<label id="lab">User Type<label id="req">*</label></label>
					<select class="form-control" name="usertype" id="usertype" required placeholder="Confirm password" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>">
						<option value="">--User Type--</option>
						<option value="ADMIN" <?php if($_POST['usertype']=="ADMIN") echo "selected";?>>ADMIN</option>
						<option value="USER" <?php if($_POST['usertype']=="USER") echo "selected";?>>USER</option>						
					</select>
				</div>
				<div class="col-sm-3">
					<br />
					<?php
					if($_POST['acn']=="save")
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Add User Detail</button>
					<?php
					}
					else
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Update User Detail</button>
					<?php
					}
					?>
				</div>
			</div>		
		</form>
</div>
<div class="row"><div class="col-sm-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-12">
	<?php
	?>
	<form name="pagedata" id="pagedata" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="10">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="selectbx" onchange="GetLoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="50">50</option>							
							<option value="100">100</option>							
						</select>
						<select class="selectbx" name="depname" id="depname" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" onchange="LoadDefault()" style="padding:0px;">
							<option value="">--Department Name--</option>
							<?php
							mysqli_data_seek($rs_sel,0);
							while($dep=mysqli_fetch_assoc($rs_sel))
							{
							?>
							<option value="<?php echo $dep['categoryid'];?>"><?php echo $dep['categoryname'];?></option>
							<?php
							}
							?>
						</select>
						<select class="selectbx" name="utype" id="utype" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" onchange="LoadDefault()" style="padding:0px;">
							<option value="">--User Type--</option>
							<option value="ADMIN">ADMIN</option>
							<option value="USER">USER</option>							
						</select>
						
						
					</div>
					<div id="tableheadsearch">
						<div class="nav-search" id="nav-search">
							<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="pagesearch" name="pagesearch" autocomplete="off" onkeyup="LoadDefault()" tabindex="<?php echo $t++;?>" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
						</div>
					</div>
					</th>
				</tr>
				<tr>
					<th style="padding:3px;">Sno</th>
					<th style="padding:3px;">Department Name</th>
					<th style="padding:3px;">User Name</th>
					<th style="padding:3px;">Mobile Number</th>					
					<th style="padding:3px;">Gender</th>					
					<th style="padding:3px;">Address</th>					
					<th style="padding:3px;">Login User Name</th>					
					<th style="padding:3px;">Password</th>
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
	$('.multiselect').multiselect({
	 enableFiltering: true,
	 enableHTML: true,
	 enableCaseInsensitiveFiltering:1,
	 maxHeight:400,
	 buttonClass: 'btn btn-white btn-primary',
	 templates: {
		button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-selected-text"></span> &nbsp;<b class="fa fa-caret-down"></b></button>',
		ul: '<ul class="multiselect-container dropdown-menu"></ul>',
		filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
		filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
		li: '<li><a tabindex="0"><label></label></a></li>',
		divider: '<li class="multiselect-item divider"></li>',
		liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>'
	 }
	});


    $(document).ready(function() {
        LoadDefault();
    });

	
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
	
	function DeleteRecord(obj)
	{
		document.getElementById("acn").value="delete";
		document.getElementById("userid").value=obj;
		$("#frm").submit();
	}

	function CheckPassword(val)
	{
		var pass	=	document.getElementById("password").value;
		var cpass	=	document.getElementById("confirmpassword").value;		
		if(pass!=cpass)
		{
			$(".msg").css("display","");
			$("#setmsg").html("Both password must be same.");					
			document.getElementById("password").value="";
			document.getElementById("confirmpassword").value="";
			$("#password").focus();
		}
	}

	function CheckUser(val)
	{
		$(".loader").show();
		var	mobilenumber	=	document.getElementById("mobilenumber").value;
		$.post("ajaxpages/checkuser.php",
		{
			mobilenumber:mobilenumber,
		},
		function(data, status)
		{
			if(data=="error")
			{
				$("#mobilenumber").val("");
				bootbox.alert("Mobile number is already registered! Please check user availability!");	
			}
			$(".loader").hide();
		});
	}

	function CheckUsername(val)
	{
		$(".loader").show();
		$.post("ajaxpages/username.php",
			{
				val:val
			},
			function(data, status)
			{
				if(data=="error")
				{
					$("#username").val("");
					$("#username").focus().select();
					$(".msg").css("display","");
					$("#setmsg").html("User name is not available. Please try another user name!");					
				}
				$(".loader").hide();
			});
	}


	function LoadDefault()
	{
		var pagesize	=	document.getElementById("pagesize").value;
		var inputsearch	=	document.getElementById("pagesearch").value;
		var pagenumber	=	document.getElementById("pagenumber").value;
		var depname		=	document.getElementById("depname").value;		
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();		
		$.post("ajaxpages/user.php",
			{
				processname: "user",
				pagesize: pagesize,
				searchvalue:inputsearch,
				pagenumber: pagenumber,
				depname:depname,
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
