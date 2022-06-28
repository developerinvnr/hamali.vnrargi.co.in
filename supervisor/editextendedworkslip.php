<?php
$t=1;
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
			<li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-edit bigger-120" style="vertical-align:bottom;"></i> Update Work Slip</a></li>	
		</ul>

	
		<div class="tab-content no-border padding-24" style="border:1px solid #ddd; min-height:150px;">
			<div id="home" class="tab-pane in active">
				<div class="row">
					<div class="alert alert-block alert-success" id="deleterecord" style="padding:5px;">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>	
						</button>
						<i class="ace-icon fa fa-check green"></i>
						<label id="putmsg"></label>
					</div>
					<div class="alert alert-block alert-warning" id="warningmed" style="padding:5px;">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>	
						</button>
						<i class="ace-icon fa fa-remove orange"></i>
						<label id="warningputmsg"></label>
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
<?php
$rs_sel	=	$dbconnection->firequery("select * from workslip_tbl where workslipid=".$_REQUEST['slipid']."");
while($row=mysqli_fetch_assoc($rs_sel))
{
?>					
<form name="frm" id="frm" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
	<input type="hidden" name="pk" id="pk" value="<?php echo $pk;?>" />
	<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
	<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />						
	<input type="hidden" name="slipid" id="slipid" value="<?php echo $_REQUEST['slipid'];?>" />
		<div class="row">
			<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
			<div class="form-group">	
				<div class="col-sm-3">
					<label id="lab">Work Slip Date<label id="req">*</label></label>
					<input type="datetime-local" class="form-control" name="workslipdate" id="workslipdate" value="<?php echo date('Y\-m\-d',strtotime($row['workslipdate']))."T".date('H:i',strtotime($row['workslipdate']));?>" onKeyPress="return OnKeyPress(this, event)" readonly autocomplete="off" tabindex="<?php echo $t++;?>" autofocus onchange="UpRec()"/>
				</div>				
			
				<div class="col-sm-2">
					<label id="lab">Hamali Group Number<label id="req">*</label></label>
					<input type="text" class="form-control" name="groupnumber" id="groupnumber" value="<?php echo $row['groupnumber'];?>" placeholder="Hamali group number" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" tabindex="<?php echo $t++;?>" required readonly />
				</div>
				<div class="col-sm-3">
					<label id="lab">Hamali Group Name<label id="req">&nbsp;</label></label>
					<input type="text" class="form-control" name="groupname" id="groupname" value="<?php echo $row['groupname'];?>" placeholder="Hamali group name" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" tabindex="<?php echo $t++;?>" readonly/>
				</div>				
				<div class="col-sm-4">
					<label id="lab">Remark<label id="req">&nbsp;</label></label>
					<input type="text" class="form-control" name="rem" id="rem" value="<?php echo $row['remark'];?>" placeholder="Remark if any" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" tabindex="<?php echo $t++;?>" onchange="UpRec()"/>
				</div>				
			</div>		
</div>
<div class="row"><div class="col-sm-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-bordered table-hover" id="tablerecords">
				<thead>
					<tr>
						<td style="padding:0px;"><input type="text" name="workcode" id="workcode" placeholder="Work code" style="padding-left:2px; padding-top:0px; padding-bottom:0px; width:100%;; padding-right:0px; height:25px; font-size:12px;" onchange="GetWorkCode(this.value)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td>

						<td style="padding:0px;">
							<input type="text" name="quantity" id="quantity" placeholder="Quantity" tabindex="<?php echo $t++;?>" style="padding-left:2px; padding-top:0px; padding-bottom:0px; width:100%;; padding-right:0px; height:25px; font-size:12px;" onKeyPress="return OnKeyPress(this, event)" />
						</td>						

	<td style="padding:0px; width:150px;">
		<select name="rem1" class="form-control" id="rem1" autocomplete="off" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;">
			<option value="">--Material--</option>
		</select>
	</td>

	<td style="padding:0px; width:150px;">
		<select name="rem2" class="form-control" id="rem2" autocomplete="off" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;">
			<option value="">--Product/Method--</option>
		</select>
	</td>

						
						<td colspan="3" style="padding:0px;">
							<button type="button" class="btn" style="padding-left:0px; padding-top:0px; padding-right:0px; padding-bottom:0px; border:none; color:#FFFFFF; font-size:12px; font-weight:bold; width:100px; height:25px;" tabindex="<?php echo $t++;?>" onclick="AddButton()">Add</button>
						</td>
					</tr>	
					<tr>
						<td style="padding:3px; font-weight:600;">Work Code<label id="req">*</label></td>
						<td style="padding:3px; font-weight:600;">Quantity<label id="req">*</label></td>
						<td style="padding:3px; font-weight:600;">Remark 1</td>
						<td style="padding:3px; font-weight:600;">Remark 2</td>
						<td style="padding:3px; font-weight:600; text-align:center; width:25px;"><i class="fa fa-edit"></i></td>
						<td style="padding:3px; font-weight:600; text-align:center; width:25px;"><i class="fa fa-remove"></i></td>						
						<td style="padding:3px; font-weight:600;">Narration</td>						
					</tr>
				</thead>
<tbody class="tabledata">

</tbody>
			</table>
		</div>
	</div>
</div>
</form>
<?php
}
?>
</div>
</div>

</div>
</div>
						<div class="hr hr32 hr-dotted"></div>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	
		
	</div>
</div>
<script src="../assets/js/jquery-2.1.4.min.js"></script>

<script>
$(document).ready(function() {
	$(".loader").hide();
	$("#deleterecord").hide();	
	$("#warningmed").hide();
	LoadDefault();
});

function UpRec()
{
	var slipid		=	document.getElementById("slipid").value;
	var slipdate	=	document.getElementById("workslipdate").value;	
	var remark		=	document.getElementById("rem").value;		
	$.post("ajaxpages/uprec.php",
	{
		slipid:slipid,
		slipdate:slipdate,
		remark:remark
	},
	function(data, status)
	{
		$("#putmsg").html("");
		$("#putmsg").append("Work slip detail updated successfully!");				
		$("#deleterecord").show();
		setTimeout(function() { $("#putmsg").html(""); $("#deleterecord").hide(); },3000);
		LoadDefault();			
	});
}

function LoadDefault()
{
	$(".loader").show();
	var slipid	=	document.getElementById("slipid").value;		
	$.post("ajaxpages/extendedcodelist.php",
	{
		slipid:slipid
	},
	function(data, status){
		$(".loader").hide();
		$(".tabledata").html(data);
	});
}

function GetWorkCode(workcode)
{
	var gpno		=	document.getElementById("groupnumber").value;
	var slipdate	=	document.getElementById("workslipdate").value;
	if(gpno!="")
	{
		$.post("ajaxpages/material.php",
		{
			workcode:workcode,
			gpno:gpno,
			slipdate:slipdate
		},
		function(data, status){
			var str	=	data.split("|");
			if(str[0].trim()=="error")
			{
				$("#warningputmsg").html("");
				$("#warningputmsg").append(str[1]);				
				$("#warningmed").show();									
				$(".loader").hide();					
				$("#workcode").val("");
				$("#workcode").focus();
			}
			else if(str[0].trim()=="codeerror")
			{
				$("#warningputmsg").html("");
				$("#warningputmsg").append(str[1]);				
				$("#warningmed").show();									
				$(".loader").hide();					
				$("#workcode").val("");
				$("#workcode").focus();				
			}
			else
			{
				$("#rem1").html(data);
			}
		});
		GetProductMethod(workcode);
	}
	else
	{
		bootbox.alert("Please enter hamali group number!");
		$("#workcode").focus();
		$("#workcode").val("");		
		return;
	}
}

function GetProductMethod(workcode)
{

	var gpno	=	document.getElementById("groupnumber").value;
	if(gpno!="")
	{
		$.post("ajaxpages/productmethod.php",
		{
			workcode:workcode,
			gpno:gpno
		},
		function(data, status){
			var str	=	data.split("|");
			if(str[0].trim()=="error")
			{
				$("#warningputmsg").html("");
				$("#warningputmsg").append(str[1]);				
				$("#warningmed").show();									
				$(".loader").hide();					
				$("#workcode").val("");
				$("#workcode").focus();
			}
			else
			{
				$("#rem2").html(data);				
			}
		});
	}
	else
	{

		bootbox.alert("Please enter hamali group number!");
		$("#workcode").focus();
		return;
	}
}

	function AddButton()
	{
		$("#putmsg").html("");
		$("#deleterecord").hide();				
		$("#warningputmsg").html("");
		$("#warningmed").hide();
	
		var workcode	=	document.getElementById("workcode").value;
		var gpno	=	document.getElementById("groupnumber").value;
		if(workcode=="")
		{
			bootbox.alert("Enter work code number");
			$("#workcode").focus();
			return;
		}
		var rem1		=	document.getElementById("rem1").value;		
		var rem2		=	document.getElementById("rem2").value;				
		var quantity	=	document.getElementById("quantity").value;
		var slipid		=	document.getElementById("slipid").value;
		$(".loader").show();		
		$.post("ajaxpages/addnewextendedcode.php",
		{
			workcode:workcode,
			quantity:quantity,
			rem1:rem1,
			rem2:rem2,
			gpno:gpno,
			slipid:slipid
		},
		function(data, status)
		{
			$("#putmsg").html("");
			$("#putmsg").append("New work code added successfully!");				
			$("#deleterecord").show();
			$("#workcode").val("");
			$("#quantity").val("");			
			$("#rem1").val("");			
			$("#rem2").val("");			
			$("#rate").val("");			
			$("#workcode").focus();			
			LoadDefault();			
		});
    }

	function RemoveRecord(did,slipid,total)
	{
		bootbox.confirm("Do you want to remove this record from list", function(result)
		{
			if(result)
			{ 
				$("#putmsg").html("");
				$("#deleterecord").hide();				
				$("#warningputmsg").html("");
				$("#warningmed").hide();
				$(".loader").show();		
				$.post("ajaxpages/removerecord.php",
				{
					slipid:slipid,
					did:did,
					total:total
				},
				function(data, status)
				{
					$("#putmsg").html("");
					$("#putmsg").append("Work code removed from slip record!");				
					$("#deleterecord").show();
					$("#workcode").focus();			
					LoadDefault();			
				});
			} 
		});
    }

	function UpdateRecord(did,slipid,ind,total,rate)
	{
		bootbox.confirm("Do you want to update this record", function(result)
		{
			if(result)
			{
				$("#putmsg").html("");
				$("#deleterecord").hide();				
				$("#warningputmsg").html("");
				$("#warningmed").hide();
				$(".loader").show();		
				var workcode	=	document.getElementById("workcode"+ind).value;
				var quantity	=	document.getElementById("quantity"+ind).value;
				var rem1		=	document.getElementById("rem1"+ind).value;				
				var rem2		=	document.getElementById("rem2"+ind).value;								
				$.post("ajaxpages/updateextendedrecord.php",
				{
					slipid:slipid,
					did:did,
					total:total,
					quantity:quantity,
					rem1:rem1,
					rem2:rem2,
					rate:rate,
					workcode:workcode
				},
				function(data, status)
				{
					$("#putmsg").html("");
					$("#putmsg").append("Record updated successfully!");				
					$("#deleterecord").show();
					$("#workcode").focus();			
					LoadDefault();			
				});
			} 
		});
    }
	
</script>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script src="../js/bootbox.min.js"></script>

<script src="../assets/js/bootstrap.min.js"></script>
