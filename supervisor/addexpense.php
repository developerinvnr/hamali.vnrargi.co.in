<?php
$t=0;
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="save")
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['expdate']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter expense date</li>";
		}
		if(empty($_POST['centername']))
		{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Select center name</li>";
		}
		if(empty($_POST['headname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select expenses head name</li>";
		}
		if(empty($_POST['amount']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter amount value</li>";
		}
		if(empty($_POST['remark']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter remark</li>";
		}
	
		
		if($flag==0)
		{
			if($dbconnection->firequery("insert into expenses_tbl(headid,amount,remark,expensdate,centerid,franchiseid,approvalstatus,centerheadid,staffid,creationdate,approvaldatetime) values(".$_POST['headname'].",".doubleval($_POST['amount']).",'".$_POST['remark']."','".date('Y\-m\-d',strtotime($_POST['expdate']))."',".$_POST['centername'].",".$_SESSION['franchisedetail'][0]['sessionid'].",'APPROVED',0,0,'".date('Y\-m\-d H:i:s')."','".date('Y\-m\-d H:i:s')."')"))
			{
				$_SESSION['success']	=	"Expense detail added successfully!";
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
		if(empty($_POST['expdate']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter expense date</li>";
		}
		if(empty($_POST['centername']))
		{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Select center name</li>";
		}
		if(empty($_POST['headname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select expenses head name</li>";
		}
		if(empty($_POST['amount']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter amount value</li>";
		}
		if(empty($_POST['remark']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter remark</li>";
		}
		
		
		if($flag==0)
		{
			if($dbconnection->firequery("update expenses_tbl set headid=".$_POST['headname'].",amount=".doubleval($_POST['amount']).",remark='".$_POST['remark']."',expensdate='".date('Y\-m\-d',strtotime($_POST['expdate']))."',centerid=".$_POST['centername']." where expenseid=".trim(decryptvalue($_POST['expenseid'])).""))
			{
				$_SESSION['success']	=	"Expense detail updated successfully!";
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
		if($dbconnection->firequery("delete from expenses_tbl where expenseid=".trim(decryptvalue($_POST['expenseid'])).""))
		{
			$_SESSION['success']	=	"Expense detail deleted successfully!";
			echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
			exit;
		}
	}
}

$_POST['acn']	=	"save";
if($_REQUEST['expenseid']!="")
{
	$rs_sel	=	$dbconnection->firequery("select * from expenses_tbl where expenseid=".trim(decryptvalue($_REQUEST['expenseid']))."");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$_POST['headname']	=	$row['headid'];
		$_POST['centername']=	$row['centerid'];		
		$_POST['amount']	=	$row['amount'];				
		$_POST['remark']	=	$row['remark'];						
		$_POST['expdate']	=	$row['expensdate'];								
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
						<i class="ace-icon fa fa-remove green"></i> Action Result
						 <?php 
							echo $msgErr; 
							unset($msgErr);
						 ?>
					</div>
					<?php					
					}
					?>

<div class="tabbable">
		<ul class="nav nav-tabs padding-18">
			<li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-plus-circle bigger-120" style="vertical-align:bottom;"></i> Add Expenses</a></li>	
			<li><a data-toggle="tab" href="#feed"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> Expenses List</a></li>
		</ul>
	
		<div class="tab-content no-border padding-24" style="border:1px solid #ddd; min-height:500px;">
			<div id="home" class="tab-pane in active">
				<div class="row" style="min-height:500px;">
<div class="table-responsive" style="min-height:500px;">
	<div class="alert alert-block alert-success msg" style="padding:5px; display:none;">
		<button type="button" class="close" data-dismiss="alert">
			<i class="ace-icon fa fa-times"></i>	
		</button>
		<i class="ace-icon fa fa-remove green"></i> <label id="setmsg"></label>
	</div>

<form name="frm" id="frm" action="#" method="post" enctype="multipart/form-data">
			<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
			<input type="hidden" name="pk" id="pk" value="<?php echo $pk;?>" />
			<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
			<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />						
			<input type="hidden" name="expenseid" id="expenseid" value="<?php echo $_REQUEST['expenseid'];?>" />
			<input type="hidden" name="tablename" id="tablename" value="<?php echo encrypt($tablename);?>" />			
			<div class="form-group">
				<div class="col-sm-3">
					<label id="lab">Expense Date<label id="req">*</label></label>
					<input type="date" class="form-control" name="expdate" id="expdate" required onKeyPress="return OnKeyPress(this,event)" tabindex="<?php echo $t++;?>" value="<?php if($_POST['expdate']!="") echo date('Y\-m\-d',strtotime($_POST['expdate'])); else echo date('Y\-m\-d');?>" autofocus>
				</div>		
				<div class="col-sm-3">
					<label id="lab">Center Name<label id="req">*</label></label>
					<select class="form-control" name="centername" id="centername" required onKeyPress="return OnKeyPress(this,event)" tabindex="<?php echo $t++;?>">
					<option value="">--Center Name--</option>
					<?php
					$rs_center	=	$dbconnection->firequery("select centerid,centername from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by centername");
					while($cnt=mysqli_fetch_assoc($rs_center))
					{
					?>
					<option value="<?php echo $cnt['centerid'];?>" <?php if($_POST['centername']==$cnt['centerid']) echo "selected"; ?>><?php echo $cnt['centername'];?></option>
					<?php
					}
					unset($rs_center);
					unset($cnt);
					?>
					</select>
				</div>		

				<div class="col-sm-3">
					<label id="lab">Expense Head<label id="req">*</label></label>
					<select class="form-control" name="headname" id="headname" required onKeyPress="return OnKeyPress(this,event)" tabindex="<?php echo $t++;?>">
					<option value="">--Expense Head Name--</option>
					<?php
					$rs_head	=	$dbconnection->firequery("select headid,headname from expenseshead_tbl order by headname");
					while($head=mysqli_fetch_assoc($rs_head))
					{
					?>
					<option value="<?php echo $head['headid'];?>" <?php if($head['headid']==$_POST['headname']) echo "selected"; ?>><?php echo $head['headname'];?></option>
					<?php
					}
					unset($rs_head);
					unset($head);
					?>
					</select>
				</div>		
				<div class="col-sm-3">
					<label id="lab">Amount<label id="req">*</label></label>
					<input type="text" class="form-control" name="amount" id="amount" value="<?php echo doubleval($_POST['amount']);?>" placeholder="Amount" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-12">&nbsp;</div>
				<div class="col-sm-9">
					<label id="lab">Remark<label id="req">*</label></label>
					<input type="text" class="form-control" name="remark" required id="remark" value="<?php echo $_POST['remark'];?>" placeholder="Remark" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-3">
					<br />
					<?php
					if($_POST['acn']=="save")
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Add Expense</button>
					<?php
					}
					else
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Update Expense</button>
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
					<th colspan="10">
					<div id="tablehead">
						From : <input type="date" id="frmdate" name="frmdate" class="selectbx" style="padding:0px;" value="<?php echo date('Y\-m\-01');?>" />
						To : <input type="date" id="todate" name="todate" style="padding:0px;" value="<?php echo date('Y\-m\-d');?>"  class="selectbx" />

						<select class="selectbx" name="cntname" id="cntname" required onKeyPress="return OnKeyPress(this,event)" tabindex="<?php echo $t++;?>">
						<option value="">--Center Name--</option>
						<?php
						$rs_center	=	$dbconnection->firequery("select centerid,centername from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by centername");
						while($cnt=mysqli_fetch_assoc($rs_center))
						{
						?>
						<option value="<?php echo $cnt['centerid'];?>" <?php if(in_array($cnt['centerid'],$exp)) echo "selected"; ?>><?php echo $cnt['centername'];?></option>
						<?php
						}
						unset($rs_center);
						unset($cnt);
						?>
						</select>

						<select class="selectbx" name="hdname" id="hdname" required onKeyPress="return OnKeyPress(this,event)" tabindex="<?php echo $t++;?>">
						<option value="">--Head Name--</option>
						<?php
						$rs_head	=	$dbconnection->firequery("select headid,headname from expenseshead_tbl order by headname");
						while($head=mysqli_fetch_assoc($rs_head))
						{
						?>
						<option value="<?php echo $head['headid'];?>"><?php echo $head['headname'];?></option>
						<?php
						}
						unset($rs_head);
						unset($head);
						?>
						</select>

						<select name="apprstatus" id="apprstatus" class="selectbx">
							<option value="">--Approval Status--</option>
							<option value="APPROVED">APPROVED</option>
							<option value="PENDING">PENDING</option>
						</select>
						<button type="button" class="btn btn-info" style="border-radius:0px; float:right;" onclick="LoadDefault()">Get Detail</button>
					</div>
					<br /><br />
					<div id="tableheadsearch">
						<div class="nav-search" id="nav-search">
							<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="pagesearch" name="pagesearch" autocomplete="off" onkeyup="LoadDefault()" tabindex="<?php echo $t++;?>" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
						</div>
					</div>
					<br />
					</th>
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
<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script src="../assets/js/bootstrap-multiselect.min.js"></script>
<script>
    $(document).ready(function() {
        //LoadDefault();
		$(".loader").hide();
    });

	function DeleteRecord(obj)
	{
		document.getElementById("acn").value="delete";
		document.getElementById("expenseid").value=obj;
		$("#frm").submit();
	}

	function GetLoadDefault()
	{
		document.getElementById("pagenumber").value=1;
		LoadDefault();
	}
	
	function LoadDefault()
	{
		var inputsearch	=	document.getElementById("pagesearch").value;
		var pagenumber	=	document.getElementById("pagenumber").value;
		var frmdate		=	document.getElementById("frmdate").value;
		var todate		=	document.getElementById("todate").value;
		var cntname		=	document.getElementById("cntname").value;				
		var apprstatus	=	document.getElementById("apprstatus").value;		
		var hdname		=	document.getElementById("hdname").value;				
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();		
		$.post("ajaxpages/expenselist.php",
		{
			processname: "expenses",
			searchvalue:inputsearch,
			pagenumber: pagenumber,
			frmdate:frmdate,
			todate:todate,
			hdname:hdname,			
			cntname:cntname,
			apprstatus:apprstatus,
			m:m,
			p:p,
			pk:pk
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledata").html(data);
		});
	}

	function Approve(expenseid)
	{

		bootbox.confirm("You want to approve this expense detail!", function(result)
		{ 
			if(result)
			{
				$(".loader").show();		
				$.post("ajaxpages/approveexpense.php",
				{
					expenseid:expenseid
				},
				function(data, status){
					LoadDefault();
				});
			}
		});	
	}




    $(document).on('click','.pagelinks',function(){
		document.getElementById("pagenumber").value=$(this).data('runid');
		LoadDefault();
    });
</script>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script src="../js/bootbox.min.js"></script>
<script type="text/javascript">
	function CallBox(obj)
	{
		bootbox.confirm("Do you want to delete this record!", function(result){ if(result){ DeleteRecord(obj);} });	
	}
</script>
<script src="../assets/js/bootstrap.min.js"></script>
