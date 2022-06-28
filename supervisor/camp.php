<?php
$t=0;
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="save")
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['locationname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter apartment name</li>";
		}
		if(empty($_POST['location']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select location name</li>";
		}
		if(empty($_POST['contactperson']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter contact person name</li>";
		}
		if(empty($_POST['mobilenumber']))
		{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Enter mobile number</li>";
		}
		if(empty($_POST['campmode']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select camp mode</li>";
		}
		if($_POST['campmode']=="PAID")
		{
			if(empty($_POST['campfees']))
			{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Enter camp fees</li>";
			}			
		}
		if(empty($_POST['campdate']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Please enter camp date</li>";
		}
		if(empty($_POST['campstatus']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Please provide camp status</li>";
		}
		if(empty($_POST['arrangedby']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select camp arranged by name</li>";
		}
		if(empty($_POST['totalhouse']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter number of house</li>";
		}
	
		
		if($flag==0)
		{
			if($dbconnection->firequery("insert into camp_tbl(locationname,locationid,contactperson,mobilenumber,campmode,campfees,campdate,campstatus,totalamount,totalcpt,cptpaidstatus,arrangedby,creationdate,addedby,addedbyuser,totalhouse) values('".$_POST['locationname']."',".$_POST['location'].",'".$_POST['contactperson']."','".$_POST['mobilenumber']."','".$_POST['campmode']."','".$_POST['campfees']."','".date('Y\-m\-d',strtotime($_POST['campdate']))."','".$_POST['campstatus']."','".$_POST['totalamount']."','".$_POST['totalcpt']."','".$_POST['cptstatus']."','".$_POST['arrangedby']."','".date('Y\-m\-d H:i:s')."',".$_SESSION['franchisedetail'][0]['sessionid'].",'".$_SESSION['franchisedetail'][0]['authname']."','".$_POST['totalhouse']."')"))
			{
				$_SESSION['success']	=	"Camp detail added successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
		}
	}
	if($_POST['acn']=="update")
	{

		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['locationname']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter camp location</li>";
		}
		if(empty($_POST['location']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select location name</li>";
		}
		if(empty($_POST['contactperson']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter contact person name</li>";
		}
		if(empty($_POST['mobilenumber']))
		{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Enter mobile number</li>";
		}
		if(empty($_POST['campmode']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select camp mode</li>";
		}
		if($_POST['campmode']=="PAID")
		{
			if(empty($_POST['campfees']))
			{
				$flag++;
				$msgErr.=	"<li class='text-warning'>Enter camp fees</li>";
			}			
		}
		if(empty($_POST['campdate']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Please enter camp date</li>";
		}
		if(empty($_POST['campstatus']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Please provide camp status</li>";
		}
		if(empty($_POST['arrangedby']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Select camp arranged by name</li>";
		}
		if(empty($_POST['totalhouse']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter number of house</li>";
		}
		
		
		if($flag==0)
		{
			if($dbconnection->firequery("update camp_tbl set locationname='".$_POST['locationname']."',locationid=".$_POST['location'].",contactperson='".$_POST['contactperson']."',mobilenumber='".$_POST['mobilenumber']."',campmode='".$_POST['campmode']."',campfees='".$_POST['campfees']."',campdate='".date('Y\-m\-d',strtotime($_POST['campdate']))."',campstatus='".$_POST['campstatus']."',totalamount='".$_POST['totalamount']."',totalcpt='".$_POST['totalcpt']."',cptpaidstatus='".$_POST['cptpaidstatus']."',arrangedby='".$_POST['arrangedby']."',totalhouse='".$_POST['totalhouse']."' where campid=".trim(decryptvalue($_POST['campid'])).""))
			{
				$_SESSION['success']	=	"Camp detail updated successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
		}
	}
	if($_POST['acn']=="delete")
	{
		$dbconnection->firequery("delete from camp_tbl where campid=".trim(decryptvalue($_POST['campid']))."");
		$_SESSION['success']	=	"Camp detail deleted successfully!";
		echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
		exit;
	}
}

$_POST['acn']	=	"save";
if($_REQUEST['campid']!="")
{
	$rs_sel	=	$dbconnection->firequery("select * from camp_tbl where campid=".trim(decryptvalue($_REQUEST['campid']))."");
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$_POST['locationname']	=	$row['locationname'];
		$_POST['location']		=	$row['locationid'];		
		$_POST['contactperson']	=	$row['contactperson'];		
		$_POST['mobilenumber']	=	$row['mobilenumber'];				
		$_POST['campmode']		=	$row['campmode'];						
		$_POST['campfees']		=	$row['campfees'];								
		$_POST['campdate']		=	$row['campdate'];										
		$_POST['campstatus']	=	$row['campstatus'];
		$_POST['totalamount']	=	$row['totalamount'];				
		$_POST['totalcpt']		=	$row['totalcpt'];				
		$_POST['cptpaidstatus']	=	$row['cptpaidstatus'];						
		$_POST['arrangedby']	=	$row['arrangedby'];
		$_POST['totalhouse']	=	$row['totalhouse'];		
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
			<li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-plus-circle bigger-120" style="vertical-align:bottom;"></i> Add Camp</a></li>	
			<li><a data-toggle="tab" href="#feed"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> Camp List</a></li>
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
			<input type="hidden" name="campid" id="campid" value="<?php echo $_REQUEST['campid'];?>" />
			<input type="hidden" name="tablename" id="tablename" value="<?php echo encrypt($tablename);?>" />			
			<div class="form-group">
				<div class="col-sm-3">
					<label id="lab">Apartment Name<label id="req">*</label></label>
					<input type="text" class="form-control" name="locationname" id="locationname" value="<?php echo $_POST['locationname'];?>" placeholder="Apartment name" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Location<label id="req">*</label></label>
					<?php
					$rs_loc	=	$dbconnection->firequery("select * from location_tbl order by locationname");
					?>
					<select name="location" id="location" class="form-control" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;" required>
						<option value="">--Location Name--</option>
						<?php
						while($loc=mysqli_fetch_assoc($rs_loc))
						{
						?>
						<option value="<?php echo $loc['locationid'];?>" <?php if($_POST['location']==$loc['locationid']) echo "selected";?>><?php echo $dbconnection->getField("city_tbl","cityname","cityid=".$loc['cityname']."");?> - <?php echo $loc['locationname'];?></option>
						<?php
						}
						?>
					</select>
				</div>
				
				<div class="col-sm-3">
					<label id="lab">Contact Person<label id="req">*</label></label>
					<input type="text" class="form-control" name="contactperson" id="contactperson" value="<?php echo $_POST['contactperson'];?>" placeholder="Contact person" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Contact Number<label id="req">*</label></label>
					<input type="text" class="form-control" name="mobilenumber" required id="mobilenumber" value="<?php echo $_POST['mobilenumber'];?>" placeholder="Mobile number" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-12">&nbsp;</div>	
				<div class="col-sm-3">
					<label id="lab">Camp Mode<label id="req">*</label></label>
					<select name="campmode" id="campmode" class="form-control" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;" onchange="CheckFees(this.value)" required>
						<option value="">--Camp Mode--</option>
						<option value="PAID" <?php if($_POST['campmode']=="PAID") echo "selected";?>>PAID</option>
						<option value="FREE" <?php if($_POST['campmode']=="FREE") echo "selected";?>>FREE</option>						
					</select>
				</div>
				<div class="col-sm-3">
					<label id="lab">Camp Fees<label id="req">&nbsp;</label></label>
					<input type="text" class="form-control" name="campfees" id="campfees" value="<?php echo doubleval($_POST['campfees']);?>" placeholder="Camp fees" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" readonly/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Camp Date<label id="req">*</label></label>
					<input type="date" class="form-control" name="campdate" id="campdate" value="<?php if($_POST['campdate']!="") echo date('Y\-m\-d',strtotime($_POST['campdate']));?>" placeholder="address detail" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;" required/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Camp Status<label id="req">*</label></label>
					<select name="campstatus" id="campstatus" class="form-control" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;" required>
						<option value="">--Camp Status--</option>
						<option value="DONE" <?php if($_POST['campstatus']=="DONE") echo "selected";?>>DONE</option>
						<option value="CONFIRMED" <?php if($_POST['campstatus']=="CONFIRMED") echo "selected";?>>CONFIRMED</option>
						<option value="IN PROCESS" <?php if($_POST['campstatus']=="IN PROCESS") echo "selected";?>>IN PROCESS</option>						
					</select>
				</div>			
				<div class="col-sm-12">&nbsp;</div>	
				<div class="col-sm-3">
					<label id="lab">Collection Amount<label id="req">&nbsp;</label></label>
					<input type="text" class="form-control" name="totalamount" id="totalamount" value="<?php echo $_POST['totalamount'];?>" placeholder="Total amount" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Generated CPT<label id="req">&nbsp;</label></label>
					<input type="text" class="form-control" name="totalcpt" id="totalcpt" value="<?php echo $_POST['totalcpt'];?>" placeholder="Total CPT" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">CPT Payment Status<label id="req">&nbsp;</label></label>
					<select name="cptpaidstatus" id="cptpaidstatus" class="form-control" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;">
						<option value="">--CPT Status--</option>
						<option value="PAID" <?php if($_POST['cptpaidstatus']=="PAID") echo "selected";?>>PAID</option>
						<option value="UNPAID" <?php if($_POST['cptpaidstatus']=="UNPAID") echo "selected";?>>UNPAID</option>						
					</select>
				</div>
				<div class="col-sm-3">
					<label id="lab">Arranged By<label id="req">*</label></label>
					<select name="arrangedby" id="arrangedby" class="form-control" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;" required>
						<option value="">--Arranged By--</option>
						<option value="ASHUTOSH" <?php if($_POST['arrangedby']=="ASHUTOSH") echo "selected";?>>ASHUTOSH</option>
						<option value="GAJENDRA" <?php if($_POST['arrangedby']=="GAJENDRA") echo "selected";?>>GAJENDRA</option>
						<option value="PURAN" <?php if($_POST['arrangedby']=="PURAN") echo "selected";?>>PURAN</option>
						<option value="TEAM" <?php if($_POST['arrangedby']=="TEAM") echo "selected";?>>TEAM</option>						
					</select>
				</div>
				<div class="col-sm-12">&nbsp;</div>					
				<div class="col-sm-3">
					<label id="lab">Total House<label id="req">&nbsp;</label></label>
					<input type="text" class="form-control" name="totalhouse" id="totalhouse" value="<?php echo $_POST['totalhouse'];?>" placeholder="Total house" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
		
				<div class="col-sm-9" align="right">
					<br />
					<?php
					if($_POST['acn']=="save")
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Add Camp</button>
					<?php
					}
					else
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Update Camp</button>
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
					<th colspan="13">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="selectbx" onchange="LoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="500">500</option>							
							<option value="1000">1000</option>							
							<option value="1500">1500</option>							
						</select> records
						From : <input type="date" name="frmdate" id="frmdate" style="padding:0px; vertical-align:middle;" onchange="LoadDefault()" />
						To : <input type="date" name="todate" id="todate" style="padding:0px; vertical-align:middle;" onchange="LoadDefault()" />					

						<select name="cmpstatus" id="cmpstatus" class="selectbx" onchange="LoadDefault()">
							<option value="">--Camp Status--</option>
							<option value="IN PROCESS">IN PROCESS</option>
							<option value="CONFIRMED">CONFIRMED</option>
							<option value="DONE">DONE</option>														
						</select>
						<select name="arrby" id="arrby" class="selectbx" onchange="LoadDefault()">
							<option value="">--Arranged By--</option>
							<option value="ASHUTOSH">ASHUTOSH</option>
							<option value="GAJENDRA">GAJENDRA</option>
							<option value="PURAN">PURAN</option>														
							<option value="TEAM">TEAM</option>																					
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
					<th style="padding:3px;">Apartment & Area Detail</th>
					<th style="padding:3px;">Contact Person</th>					
					<th style="padding:3px;">Camp Detail</th>
					<th style="padding:3px;">Status</th>					
					<th style="padding:3px;">Collection Amount</th>					
					<th style="padding:3px;">Generated CPT</th>					
					<th style="padding:3px;">CPT Payment Status</th>					
					<th style="padding:3px;">Arranged By</th>					
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
<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script src="../assets/js/bootstrap-multiselect.min.js"></script>
<script>
    $(document).ready(function() {
        LoadDefault();
    });

	function CheckFees(val)
	{
		if(val=="PAID")
		{
			$("#campfees").removeAttr("readonly");
		}
		else
		{
			$("#campfees").val("0");
			$("#campfees").prop("readonly","true");		
		}
	}
	function DeleteRecord(obj)
	{
		document.getElementById("acn").value="delete";
		document.getElementById("campid").value=obj;
		$("#frm").submit();
	}

	function LoadDefault()
	{
		var pagesize	=	document.getElementById("pagesize").value;
		var inputsearch	=	document.getElementById("pagesearch").value;
		var pagenumber	=	document.getElementById("pagenumber").value;
		var cmpstatus	=	document.getElementById("cmpstatus").value;		
		var frmdate		=	document.getElementById("frmdate").value;		
		var todate		=	document.getElementById("todate").value;						
		var arrby		=	document.getElementById("arrby").value;		
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();		
		$.post("ajaxpages/camplist.php",
		{
			processname: "camplist",
			pagesize: pagesize,
			searchvalue:inputsearch,
			pagenumber:pagenumber,
			cmpstatus:cmpstatus,
			arrby:arrby,
			frmdate:frmdate,
			todate:todate,
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
