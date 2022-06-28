<?php
$t=1;
if($_SERVER['REQUEST_METHOD']=="POST")
{
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

	<div class="tabbable">
		<ul class="nav nav-tabs padding-18">
			<li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-plus-circle bigger-120" style="vertical-align:bottom;"></i> Add Doctor Rate List</a></li>	
			<li><a data-toggle="tab" href="#feed"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> View Doctor Rate List</a></li>
		</ul>
	
		<div class="tab-content no-border padding-24" style="border:1px solid #ddd; min-height:150px;">
			<div id="home" class="tab-pane in active">
				<div class="row">

	<div class="alert alert-block alert-warning msg" style="padding:5px; display:none;">
		<button type="button" class="close" data-dismiss="alert">
			<i class="ace-icon fa fa-times"></i>	
		</button>
		<i class="ace-icon fa fa-remove green"></i> <label id="setmsg"></label>
	</div>

				
<form name="frm" id="frm" action="#" method="post" enctype="multipart/form-data">
	<input type="hidden" name="acn" id="acn" value="save" />
	<div class="form-group">
		<div class="col-sm-3">
			<label id="lab">Center Name<label id="req">*</label></label>
			<select class="form-control" name="centername" id="centername" tabindex="1" autocomplete="off" onKeyPress="return OnKeyPress(this, event)" onchange="GetDoctor(this.value,'doctorname')">
				<option value="">--Center Name--</option>
				<?php
				$rs_center	=	$dbconnection->firequery("select * from center_tbl where activestatus='ACTIVE' and franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by centername");
				while($cnt=mysqli_fetch_assoc($rs_center))
				{
				?>
				<option value="<?php echo $cnt['centerid'];?>"><?php echo $cnt['centername'];?></option>
				<?php
				}
				?>
			</select>
		</div>		
		<div class="col-sm-3" id="dis">
			<label id="lab">Doctor Name<label id="req">*</label></label>
			<select class="form-control" name="doctorname" id="doctorname" tabindex="2" autocomplete="off" onKeyPress="return OnKeyPress(this, event)">
				<option value="">--Doctor Name--</option>
			</select>
		</div>

		<div class="col-sm-3" id="dis">
			<label id="lab">Copy Rate List (Same As) Doctor/Lab Name<label id="req">&nbsp;</label></label>
			<select class="form-control" name="copyname" id="copyname" tabindex="3" autocomplete="off" onKeyPress="return OnKeyPress(this, event)">
				<option value="">--Rate List Name--</option>
				<?php
				$rs_rate	=	$dbconnection->firequery("select a.*,b.rateid from doctor_tbl a inner join doctorrate_list b on b.doctorid=a.doctorid where b.franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." group by b.doctorid");
				while($rate=mysqli_fetch_assoc($rs_rate))
				{
				?>
				<option value="<?php echo $rate['doctorid'];?>"><?php echo $rate['doctorname'];?></option>
				<?php
				}
				?>
			</select>
		</div>

		<div class="col-sm-2">
			<br id="forbutton" />
			<button type="button" class="btn btn-info rtlist" tabindex="4" onclick="CreateRateList()">Create Doctor Rate List</button>
		</div>
		<div class="col-sm-12">&nbsp;</div>


		<table class="table table-bordered" id="createratelist">
			<thead>
			</thead>
			<tbody class="createratelist">
				<div class="loader"></div>			
			</tbody>
		</table>
	</div>		
</form>
				
				</div>
			</div>
			<div id="feed" class="tab-pane">
				<div class="row">


	<div class="alert alert-block alert-success msg" style="padding:5px; display:none;">
		<button type="button" class="close" data-dismiss="alert">
			<i class="ace-icon fa fa-times"></i>	
		</button>
		<i class="ace-icon fa fa-remove green"></i> <label id="setmsg"></label>
	</div>
				
	<form name="pagedata" id="pagedata" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="12">
					<div id="tablehead">
						<select name="cntname" id="cntname" class="selectbx" onchange="GetDoctorName(this.value,'docname')" tabindex="<?php echo $t++;?>">
						<option value="">--Center Name--</option>
						<?php
						$rs_cnt	=	$dbconnection->firequery("select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." order by centername");
						while($cnt=mysqli_fetch_assoc($rs_cnt))
						{
						?> 
							<option value="<?php echo $cnt['centerid'];?>"><?php echo ucwords($cnt['centername']);?></option>
						<?php
						}
						unset($rs_cnt);
						unset($cnt);
						?>
						</select>
						<select name="docname" id="docname" class="selectbx" onchange="LoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="">--Doctor Name--</option>
						</select>
						<select name="catname" id="catname" class="selectbx" onchange="LoadDefault()" tabindex="<?php echo $t++;?>">
						<option value="">--Department Name--</option>
						<?php
						$rs_cat	=	$dbconnection->firequery("select * from category_tbl where relatedwithprice='YES' order by categoryname");
						while($cat=mysqli_fetch_assoc($rs_cat))
						{
						?> 
							<option value="<?php echo $cat['categoryid'];?>"><?php echo ucwords($cat['categoryname']);?></option>
						<?php
						}
						unset($rs_cat);
						unset($cat);
						?>
						</select>
					</div>
					<div id="tableheadsearch">
						<div class="nav-search" id="nav-search">
							<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="pagesearch" name="pagesearch" autocomplete="off" onchange="LoadDefault()" tabindex="<?php echo $t++;?>" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
						</div>
					</div>
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
        //LoadDefault();
		$(".loader").hide();
    });

	function GetDoctor(centerid,htmlid)
	{
	  var xhttp;    
	  xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) 
		{
		  document.getElementById(htmlid).innerHTML = this.responseText;
		}
	  };
	  xhttp.open("POST", "ajaxpages/doctornm.php?q="+centerid, true);
	  xhttp.send();
	}
	function GetDoctorName(centerid,htmlid)
	{
	  var xhttp;    
	  xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) 
		{
		  document.getElementById(htmlid).innerHTML = this.responseText;
		}
	  };
	  xhttp.open("POST", "ajaxpages/doctornames.php?q="+centerid, true);
	  xhttp.send();
	}


	function CreateRateList()
	{
		var doctorname	=	document.getElementById("doctorname").value;
		var centername	=	document.getElementById("centername").value;		
		var copyname	=	document.getElementById("copyname").value;				
		if(doctorname!="")
		{
			$(".rtlist").prop("disabled","disabled");		
			$(".loader").show();					
			$.post("ajaxpages/makedoctorratelist.php",
			{
				doctorname:doctorname,
				centername:centername,
				copyname:copyname
			},
			function(data, status){
				$(".loader").hide();
				if(data=="error")
				{
					$(".msg").css("display","");
					$("#setmsg").html("RATE LIST NOT ASSIGNED TO SELECTED CENTER. PLEASE ASK YOUR ADMIN TO SET RATE LIST FOR SELECTED CENTER.");					
					$(".rtlist").prop("disabled","");
				}
				else
				{
					$(".rtlist").prop("disabled","");
					$(".createratelist").html(data);
				}
			});
		}
		else
		{
			alert("Please select doctor name.");
		}
	}

	function UpdateDoctorPrice(val,ind)
	{
		var categoryid	=	document.getElementById("categoryname"+ind).value;
		var doctorname	=	document.getElementById("doctorname").value;
		var centername	=	document.getElementById("centername").value;
		var copyname	=	document.getElementById("copyname").value;		
		$.post("ajaxpages/doctorpercent.php",
		{
			doctorname:doctorname,
			centername:centername,
			copyname:copyname,
			val:val,
			categoryid:categoryid		
		},
		function(data, status){
			$(".loader").hide();
			CreateRateList();
		});
	}
	function UpdateDocInr(j,rid)
	{
		var rateamount	=	document.getElementById("rateamount"+j).value;
		$(".loader").show();		
		$.post("ajaxpages/updatecommission.php",
		{
			rateamount:rateamount,
			rid:rid
		},
		function(data, status){
			$(".loader").hide();
			CreateRateList();		
		});
					
	}

	function UpdateDocInr1(j,rid)
	{
		var rateamount	=	document.getElementById("amt"+j).value;
		$(".loader").show();		
		$.post("ajaxpages/updatecommission.php",
		{
			rateamount:rateamount,
			rid:rid
		},
		function(data, status){
			$(".loader").hide();
			LoadDefault();		
		});
					
	}

	function TestStatus(rid,act)
	{
		$(".loader").show();		
		$.post("ajaxpages/deactivate.php",
		{
			rid:rid,
			act:act
		},
		function(data, status){
			$(".loader").hide();
			if(data=="success")
			{
				bootbox.alert("Test status changed successfully.");
			}
			else
			{
				bootbox.alert("Found some problem. Please check record.");
			}
			LoadDefault();					
		});
	}
	
	function CopyRateList(centerid,doctorid)
	{
		var dcname	=	document.getElementById("dcname").value;
		if(dcname!="")
		{
			$(".cpy").prop("disabled","disabled");
			$(".loader").show();
			$.post("ajaxpages/copyratelist.php",
			{
				dcname:dcname,
				doctorid:doctorid,
				centerid:centerid
			},
			function(data, status){
				$(".loader").hide();
				document.location.href="./mainindex.php?m=<?php echo encrypt("doctor rate list-create doctor rate list");?>&p=<?php echo encrypt("doctorratelist");?>";
			});		
		}
	}

	function DeleteRate(doctorid,centerid)
	{
		$.post("ajaxpages/deleteratelist.php",
		{
			doctorid:doctorid,
			centerid:centerid
		},
		function(data, status){
			$(".loader").hide();
			document.location.href="./mainindex.php?m=<?php echo encrypt("doctor rate list-create doctor rate list");?>&p=<?php echo encrypt("doctorratelist");?>";
		});		
	}
	
	function LoadDefault()
	{
		var inputsearch	=	document.getElementById("pagesearch").value;
		var docname		=	document.getElementById("docname").value;		
		var cntname		=	document.getElementById("cntname").value;				
		var catname		=	document.getElementById("catname").value;
		if(cntname!="" && docname!="")
		{
			$(".loader").show();		
			$.post("ajaxpages/doctorratelist.php",
			{
				processname: "doctorratelistnew",
				searchvalue:inputsearch,
				docname:docname,
				cntname:cntname,
				catname:catname
			},
			function(data, status){
				$(".loader").hide();
				$(".tabledata").html(data);
			});
		}
		else
		{
			$(".loader").hide();
			$(".tabledata").html("");
			alert("Please select center and doctor name.");
			return false;
		}
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

	function RemoveThisTest(obj)
	{
		bootbox.confirm("Do you want to delete this record!", function(result){ if(result){ RemoveTest(obj);} });	
	}
	function DeleteRateList(doctorid,centerid)
	{
		bootbox.confirm("Do you want to delete this rate list!", function(result){ if(result){ DeleteRate(doctorid,centerid);} });	
	}

</script>
<script src="../assets/js/bootstrap.min.js"></script>
