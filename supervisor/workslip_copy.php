<?php
$t=1;
//unset($_SESSION['records']);
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="save")
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['workslipdate']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter workslip date</li>";
		}
		if(empty($_POST['groupnumber']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter hamali group number</li>";
		}
		if($flag==0)
		{
			$lastreference	=	intval($dbconnection->getField("workslipreference_tbl","count(*)","creationdate='".date('Y\-m\-d')."'"))+1;
			$lastreference	=	ltrim($lastreference,"0");
			$lastreference	=	date("d")."/".$lastreference;
			$etn			=	date('m');
			$slipno			=	"WRK/".date('Y')."".$etn."".$lastreference;
			$depid			=	$dbconnection->getField("supervisor_tbl","departmentname","supervisorid=".$_SESSION['supervisordetail'][0]['sessionid']."");
			$locid			=	$dbconnection->getField("supervisor_tbl","locationname","supervisorid=".$_SESSION['supervisordetail'][0]['sessionid']."");
		
			if($dbconnection->firequery("insert into workslip_tbl(workslipnumber,workslipdate,groupnumber,groupname,remark,workslipamount,creationdate,supervisorid,location,department) values('".$slipno."','".date('Y\-m\-d H:i:s',strtotime($_POST['workslipdate']))."','".$_POST['groupnumber']."','".$_POST['groupname']."','".$_POST['rem']."',".doubleval($_POST['totalvalue']).",'".date('Y\-m\-d H:i:s')."',".$_SESSION['supervisordetail'][0]['sessionid'].",".$locid.",".$depid.")"))
			{
				$slipid	=	$dbconnection->last_inserted_id();
				foreach($_SESSION['records'] as $key=>$val)
				{
					$dbconnection->firequery("insert into workslip_detail(workslipid,workcode,narration,quantity,rate,total,creationdate,supervisorid,rem1,rem2,location,department,groupnumber) values(".$slipid.",'".$_SESSION['records'][$key]['workcode']."','".$_SESSION['records'][$key]['narration']."',".doubleval($_SESSION['records'][$key]['quantity']).",".doubleval($_SESSION['records'][$key]['rate']).",".doubleval($_SESSION['records'][$key]['total']).",'".date('Y\-m\-d H:i:s')."',".$_SESSION['supervisordetail'][0]['sessionid'].",'".$_SESSION['records'][$key]['rem1']."','".$_SESSION['records'][$key]['rem2']."',".$locid.",".$depid.",".intval($_POST['groupnumber']).")");
				}
				unset($_SESSION['records']);

				$locname	=	strtoupper(substr($dbconnection->getField("location_tbl","locationname","locationid=".$locid.""),0,1));
				$ind		=	intval($dbconnection->getField("location_tbl","workslip","locationid=".$locid.""));
				$fyear		=	$dbconnection->GetFinancialYear();
				$slipnumber	=	$locname."/WRK/".$fyear."/".$ind;
				$dbconnection->firequery("update location_tbl set workslip=workslip+1 where locationid=".$locid."");
				$dbconnection->firequery("update workslip_tbl set workslipnumber='".$slipnumber."' where workslipid=".$slipid."");

				
				$dbconnection->firequery("insert into workslipreference_tbl(creationdate) values('".date('Y\-m\-d')."')");
				$_SESSION['success']	=	"Workslip detail added successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.encrypt("workslip").'&p='.encrypt("workslip").'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again.";				
			}
		}
	}
}
$_POST['acn']	=	"save";
if($_REQUEST['workslipid']!="")
{
	unset($_SESSION['records']);
	$_SESSION['records']=array();	
	$rs_sel	=	$dbconnection->firequery("select * from workslip_tbl where workslipid=".trim(decryptvalue($_REQUEST['workslipid']))."");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$_POST['groupnumber']		=	$row['groupnumber'];
		$_POST['groupname']			=	$row['groupname'];
		$_POST['rem']				=	$row['remark'];
		$_POST['workslipamount']	=	$row['workslipamount'];
		$_POST['workslipdate']		=	date('Y\-m\-',strtotime($row['workslipdate']))."T".date('H:i',strtotime($row['workslipdate']));
		$rs_detail	=	$dbconnection->firequery("select * from workslip_detail where workslipid=".trim(decryptvalue($_REQUEST['workslipid']))."");
		$i=0;
		while($ro=mysqli_fetch_assoc($rs_detail))
		{
			$_SESSION['records'][$i]['detailid']	=	$ro['detailid'];
			$_SESSION['records'][$i]['workcode']	=	$ro['workcode'];
			$_SESSION['records'][$i]['narration']	=	$ro['narration'];
			$_SESSION['records'][$i]['rem1']		=	$ro['rem1'];
			$_SESSION['records'][$i]['rem2']		=	$ro['rem2'];						
			$_SESSION['records'][$i]['rate']		=	$ro['rate'];
			$_SESSION['records'][$i]['quantity']	=	$ro['quantity'];
			$_SESSION['records'][$i]['total']		=	$ro['total'];						
			$i++;
		}
	}
	$_POST['acn']	=	"update";
}
unset($_SESSION['records']);
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
			<li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-plus-circle bigger-120" style="vertical-align:bottom;"></i> Add Work Slip</a></li>	
			<li><a data-toggle="tab" href="#feed" onclick="LoadWorkSlip()"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> All Work Slip List</a></li>
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
<form name="frm" id="frm" action="#" method="post" onsubmit="return false;">
<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
<input type="hidden" name="pk" id="pk" value="<?php echo $pk;?>" />
<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />						
<input type="hidden" name="workslipid" id="workslipid" value="workslipid" />
<input type="hidden" name="tablename" id="tablename" value="<?php echo encrypt($tablename);?>" />			

<div class="row">
			<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
			<div class="form-group">	
				<div class="col-sm-3">
					<label id="lab">Work Slip Date<label id="req">*</label></label>
					<input type="datetime-local" class="form-control" name="workslipdate" id="workslipdate" value="<?php if($_POST['workslipdate']!="") echo date('Y\-m\-d',strtotime($_POST['workslipdate']))."T".date('H:i',strtotime($_POST['workslipdate'])); else echo date('Y\-m\-d')."T".date('H:i');?>" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" tabindex="<?php echo $t++;?>" autofocus/>
				</div>				
			
				<div class="col-sm-2">
					<label id="lab">Hamali Group Number<label id="req">*</label></label>
					<input type="text" class="form-control" name="groupnumber" id="groupnumber" value="<?php echo $_POST['groupnumber'];?>" placeholder="Hamali group number" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" tabindex="<?php echo $t++;?>" required onchange="GetHamaliGroupDetail(this.value)" />
				</div>
				<div class="col-sm-3">
					<label id="lab">Hamali Group Name<label id="req">&nbsp;</label></label>
					<input type="text" class="form-control" name="groupname" id="groupname" value="<?php echo $_POST['groupname'];?>" placeholder="Hamali group name" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" tabindex="<?php echo $t++;?>" readonly/>
				</div>				
				<div class="col-sm-4">
					<label id="lab">Remark<label id="req">&nbsp;</label></label>
					<input type="text" class="form-control" name="rem" id="rem" value="<?php echo $_POST['rem'];?>" placeholder="Remark if any" onKeyPress="return OnKeyPress(this, event)" autocomplete="off" tabindex="<?php echo $t++;?>"/>
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
						<td style="padding:3px;">Work Code<label id="req">*</label></td>
						<td style="padding:3px;">Quantity<label id="req">*</label></td>
						<td style="padding:3px;">Remark 1</td>
						<td style="padding:3px;">Remark 2</td>												
						<td><button class="btn" type="button" style="float:right;" onclick="GetList()">Work Code List</button></td>
					</tr>
				</thead>
				<tbody class="tabledata">
					<div class="loader"></div>
				</tbody>
			</table>
		</div>
	</div>
</div>
</form>

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
					<th colspan="11">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="form-control" onchange="GetLoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="200">200</option>							
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
					<th style="padding:3px; text-align:center; width:100px;">S.NO.</th>
					<th style="padding:3px;">Work Slip Date</th>
					<th style="padding:3px;">Work Slip Number</th>
					<th style="padding:3px;">Remark</th>
					<th style="padding:3px;">Hamali Group Number</th>					
					<th style="padding:3px;">Hamali Group Name</th>					
					<th style="padding:3px; text-align:right;">Amount</th>
					<th style="padding:3px; text-align:center;">Payment Status</th>					
					<th style="padding:3px;" class="center"><i class="fa fa-eye"></i></th>
					<th style="padding:3px;" class="center"><i class="fa fa-print"></i></th>					
					<th style="padding:3px;" class="center"><i class="fa fa-edit"></i></th>
				</tr>
			</thead>
			<tbody class="tabledatalist">
				<div class="loader"></div>			
			</tbody>
		</table>
	</div>
	</form>
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

<div id="ratelist" class="modal fade" role="dialog" style="text-align:center; margin-top:0px;">
	<div class="modal-dialog-md" style="margin-top:10px;">
		<div class="modal-content">
			<div class="modal-body">			
			<!--<i class="fa fa-remove" data-dismiss="modal" style="font-size:12px; float:right; margin-right:10px;"></i><br />	-->

				
			</div>
		</div>
	</div>
</div>

<script src="../assets/js/jquery-2.1.4.min.js"></script>

<script>
    $(document).ready(function() {
		$("#deleterecord").hide();	
		$("#warningmed").hide();
        LoadDefault();
    });

	function CloseWindow()
	{
		$(".wrec").css("display","none");
	}



	function GetList()
	{
		var gpno	=	document.getElementById("groupnumber").value;
		var mod		=	1;
		if(gpno!="")
		{
			$.post("ajaxpages/workcodes.php",
			{
				gpname:gpno,
				mod:mod
			},
			function(data, status){
				$("#ratelist").modal("show");
				$(".modal-body").html(data);
			});
		}
		else
		{
			bootbox.alert("Please enter hamali group number.");
		}
	}


	function LoadWorkSlip()
	{
		var pagesize	=	document.getElementById("pagesize").value;
		var inputsearch	=	document.getElementById("pagesearch").value;
		var pagenumber	=	document.getElementById("pagenumber").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();
		$.post("ajaxpages/worksliplist.php",
		{
			processname: "workcode",
			pagesize: pagesize,
			searchvalue:inputsearch,
			pagenumber: pagenumber,
			m:m,
			p:p,
			pk:pk
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledatalist").html(data);
		});
	}

	function ViewDetail(ind,workslipid)
	{
		$.post("ajaxpages/viewslipdetail.php",
		{
			ind:ind,
			workslipid:workslipid
		},
		function(data, status){
			$(".wrec").css("display","none");
			$("#rec"+ind).css("display","");
			$("#recd"+ind).html(data);
		});	
	}

	function LoadDefault()
	{
		$(".loader").show();
		$.post("ajaxpages/worksliprecord.php",
		{
			processname: "add",
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledata").html(data);
		});
	}

	function GetHamaliGroupDetail(groupnumber)
	{
		$.post("ajaxpages/getdetail.php",
		{
			groupnumber:groupnumber
		},
		function(data, status){
			if(data.trim()=="error")
			{
				$("#warningputmsg").html("");
				$("#warningputmsg").append("Enter valid hamali group number!");				
				$("#warningmed").show();									
				$(".loader").hide();					
				$("#groupname").val("");
				$("#groupnumber").val("");				
				$("#groupnumber").focus();
			}
			else
			{
				$("#groupname").val(data);
			}
		});
	}
/*
	function GetWorkCode(workcode)
	{
		$.post("ajaxpages/workcode.php",
		{
			workcode:workcode
		},
		function(data, status){
			if(data.trim()=="error")
			{
				$("#warningputmsg").html("");
				$("#warningputmsg").append("Enter valid work code number!");				
				$("#warningmed").show();									
				$(".loader").hide();					
				$("#groupname").val("");
				$("#groupnumber").val("");				
				$("#groupnumber").focus();
			}
			else
			{
				$("#groupname").val(data);
			}
		});
	}
*/

	function AddButton()
	{
		$("#putmsg").html("");
		$("#deleterecord").hide();				
		$("#warningputmsg").html("");
		$("#warningmed").hide();
	
		var workcode	=	document.getElementById("workcode0").value;
		var quantity	=	document.getElementById("quantity0").value;		
		if(workcode=="")
		{
			bootbox.alert("Enter work code number");
			$("#workcode").focus();
			return;
		}
		var workcode	=	document.getElementById("workcode0").value;
		var remark		=	document.getElementById("remark0").value;
		var rem1		=	document.getElementById("rem10").value;		
		var rem2		=	document.getElementById("rem20").value;				
		var quantity	=	document.getElementById("quantity0").value;
		var rate		=	document.getElementById("rate0").value;
		var total		=	document.getElementById("total0").value;	
		$(".loader").show();		
		$.post("ajaxpages/worksliprecord.php",
		{
			process:"add",
			workcode:workcode,
			remark:remark,
			rate:rate,
			quantity:quantity,
			total:total,
			rem1:rem1,
			rem2:rem2
		},
		function(data, status)
		{
			if(data.trim()=="addfail")
			{
				$("#warningputmsg").html("");
				$("#warningputmsg").append("All * fields are mandatory and quantity 0 is not allowed. Please check values and try again. Thanks!");				
				$("#warningmed").show();									
				$(".loader").hide();		
				$("#workcode0").focus();
			}
			else
			{
				$(".loader").hide();
				$("#groupnumber").prop("readonly","true");
				$(".tabledata").html(data);
				$("#workcode0").focus();
				CalFinal();
				
			}
		});
    }


	function UpdateRecord(obj)
	{
		$("#putmsg").html("");
		$("#deleterecord").hide();
		$("#warningputmsg").html("");
		$("#warningmed").hide();

		var bj=parseInt(obj)+1;	
		var workcode	=	document.getElementById("workcode"+bj+"").value;
		if(workcode=="")
		{
			bootbox.alert("Enter workcode number");
			$("#workcode"+ind).focus();
			return;
		}
		var workcode	=	document.getElementById("workcode"+bj+"").value;
		var actionid	=	document.getElementById("actionid"+bj+"").value;
		var actionname	=	document.getElementById("actionname"+bj+"").value;
		var materialid	=	document.getElementById("materialid"+bj+"").value;
		var materialname=	document.getElementById("materialname"+bj+"").value;
		var productid	=	document.getElementById("productid"+bj+"").value;
		var productname=	document.getElementById("productname"+bj+"").value;
		var quantity	=	document.getElementById("quantity"+bj+"").value;
		var rate		=	document.getElementById("rate"+bj+"").value;
		var total		=	document.getElementById("total"+bj+"").value;	

		$(".loader").show();		
		$.post("ajaxpages/worksliprecord.php",
		{
			process:"edit",
			keyv:obj,
			workcode:workcode,
			actionid:actionid,
			actionname:actionname,
			materialid:materialid,
			materialname:materialname,
			productid:productid,
			productname:productname,
			rate:rate,
			quantity:quantity,
			total:total,
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledata").html(data);
			$("#putmsg").html("");
			$("#putmsg").append("Record updated successfully!");				
			$("#deleterecord").show();									
			$("#workcode0").focus();					
		});
    }




	function ClearEntries()
	{
		$(".loader").show();		
		$.post("ajaxpages/worksliprecord.php",
			{
				process: "clearrecord",				
			},
			function(data, status){
				$(".loader").hide();
				$("#putmsg").html("");
				$("#putmsg").append("All Records cleared successfully!");				
				$("#deleterecord").show();				
				$(".tabledata").html(data);
				$("#barcode0").focus();
			});
    }


	function DeleteRecord(obj)
	{
		$(".loader").show();		
		$.post("ajaxpages/worksliprecord.php",
			{
				keyval:obj,
				<?php
				if($_POST['othercharge']!=0 && $_POST['othercharge']!="")
				{
				?>
				othercharge:'<?php echo $_POST['othercharge'];?>',
				<?php
				}
				if($_POST['finalamount']!=0 && $_POST['finalamount']!="")
				{
				?>
				finalamount:'<?php echo $_POST['finalamount'];?>',
				<?php
				}
				?>				
			},
			function(data, status){
				if(data=="error")
				{
					$(".loader").hide();
					$("#putmsg").html("");
					$("#putmsg").append("Record can not be deleted!");				
					$("#deleterecord").show();				
					$("#workcode0").focus();
					CalFinal();				
				}
				else
				{
					$(".loader").hide();
					$("#putmsg").html("");
					$("#putmsg").append("Record deleted successfully!");				
					$("#deleterecord").show();				
					$(".tabledata").html(data);
					$("#workcode0").focus();
					CalFinal();				
				}
			});
    }
shortcut.add("Ctrl+S",function() {
	SubmitForm();
});


	
</script>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script src="../js/bootbox.min.js"></script>
<script type="text/javascript">
	function CallBox(obj)
	{
		bootbox.confirm("Do you want to remove this record from list", function(result){ if(result){ DeleteRecord(obj);} });	
	}
	function CallBoxEdit(obj)
	{
		UpdateRecord(obj);	
	}
	function CalTotal(ind)
	{
		var qty			=	Number(document.getElementById("quantity"+ind).value);
		if(isNaN(qty))
		{
			document.getElementById("quantity"+ind).value="";
			$("#quantity"+ind).focus(-1);
			return;
		}
		var rate=	Number(document.getElementById("rate"+ind).value);
		document.getElementById("total"+ind).value	=	Number(rate*qty).toFixed(2);
	}
	function CalFinal()
	{
		document.getElementById("finalamount").value	=	Number(document.getElementById("totalvalue").value)+Number(document.getElementById("othercharge").value);
	}
	function SubmitForm()
	{
		bootbox.confirm("Do you want to save this record.",function(result)
		{
			if(result)
			{ 
				$('#frm').attr('onsubmit','return true;');
				$("#frm").submit();
			} 
		});	
	}

</script>

<script src="../assets/js/bootstrap.min.js"></script>
