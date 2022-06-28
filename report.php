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
<!--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
-->
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
			<input type="hidden" name="<?php echo $pk;?>" id="<?php echo $pk;?>" value="<?php echo $_REQUEST['pk'];?>" />
			<input type="hidden" name="tablename" id="tablename" value="<?php echo encrypt($tablename);?>" />
			<div class="form-group">
<?php
$predates	=	date('Y\-m\-d',strtotime('-30 days'));
?>
			
<div class="col-sm-3">
<label id="lab">From Date<label id="req">&nbsp;</label></label>
<input type="date" name="frmdate" id="frmdate" class="form-control" value="<?php echo date('Y\-m\-d',strtotime($predates));?>">
</div>

<div class="col-sm-3">
<label id="lab">To Date<label id="req">&nbsp;</label></label>
<input type="date" name="todate" id="todate" class="form-control" value="<?php echo date('Y\-m\-d');?>">
</div>

<div class="col-sm-3">
<label id="lab">Plant Name<label id="req">&nbsp;</label></label>
<select class="form-control multiselect" name="location[]" id="location" multiple="multiple" placeholder="" onchange="AddLocation()">
<?php
$rs_loc	=	$dbconnection->firequery("select * from location_tbl where locationid in (".$_SESSION['datadetail'][0]['loca'].") order by locationname");
$val="";
$i=0;
while($loc=mysqli_fetch_assoc($rs_loc))
{
	$i++;
?>
	<option value="<?php echo $loc['locationid'];?>"><?php echo $loc['locationname'];?></option>
<?php
}
?>
</select>
<input type="hidden" name="locations" id="locations" value="<?php echo $val;?>">
</div>

<div class="col-sm-3">
<label id="lab">Department & Section Name<label id="req">&nbsp;</label></label>
<select class="form-control multiselect" name="department[]" id="department" multiple="multiple" onchange="AddDepartment()">
<?php
$rs_sec	=	$dbconnection->firequery("select * from section_tbl order by sectionname");
$val="";
$i=0;
while($sec=mysqli_fetch_assoc($rs_sec))
{
	$i++;
?>
<optgroup label="<?php echo "<input type='checkbox'> ".$sec['sectionname'];?>" class="group-<?php echo $i;?> optgrp">
<?php
$rs_dep	=	$dbconnection->firequery("select * from department_tbl where departmentid in (".$sec['departmentname'].") order by departmentname");
while($dep=mysqli_fetch_assoc($rs_dep))
{
?>
<option value="<?php echo $dep['departmentid'];?>"><?php echo $dep['departmentname'];?></option>
<?php
}
?>
</optgroup>
<?php
}
?>
</select>

<input type="hidden" name="departments" id="departments" value="<?php echo $val;?>">
</div>
<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-3">
<label id="lab">Supervisor Name<label id="req">&nbsp;</label></label>
<select class="form-control multiselect" name="supervisor[]" id="supervisor" multiple="multiple" onchange="AddSupervisor()">
<?php
$rs_dep	=	$dbconnection->firequery("select * from supervisor_tbl where locationname in (".$_SESSION['datadetail'][0]['loca'].") order by firstname");
$val="";
$i=0;
while($dep=mysqli_fetch_assoc($rs_dep))
{
	$i++;
?>
	<option value="<?php echo $dep['supervisorid'];?>"><?php echo $dep['firstname'];?> <?php echo $dep['lastname'];?></option>
<?php
}
?>
</select>
<input type="hidden" name="supervisors" id="supervisors" value="<?php echo $val;?>">
</div>

<div class="col-sm-3">
<label id="lab">Hamali Group<label id="req">&nbsp;</label></label>
<select class="form-control multiselect" name="hamaligroup[]" id="hamaligroup" multiple="multiple" onchange="AddHamaliGroup()">
<?php
$rs_dep	=	$dbconnection->firequery("select * from hamaligroup_tbl where locationname in (".$_SESSION['datadetail'][0]['loca'].") order by groupname");
while($dep=mysqli_fetch_assoc($rs_dep))
{
?>
	<option value="<?php echo $dep['hgid'];?>"><?php echo $dep['groupname'];?></option>
<?php
}
?>
</select>
<input type="hidden" name="hamaligroups" id="hamaligroups" value="<?php echo $val;?>">
</div>
<div class="col-sm-3"><br><button type="button" class="btn" onclick="LoadDefault()" style="width:100%;">Get Detail</button></div>


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
    $(document).ready(function() {
        //LoadDefault();
		$(".loader").hide();			
    });

	
	$('#location').multiselect({
	 includeSelectAllOption:1,
	 enableCaseInsensitiveFiltering:1,
	 maxHeight:300,
	 maxWidth:250,
	 buttonClass: 'btn btn-white btn-primary'
	});

	$('#department').multiselect({
	 enableClickableOptGroups:1,
	 enableHTML:1,
	 maxHeight:400,
	 minWidth:400,
	 buttonClass: 'btn btn-white'
	});

	$('#supervisor').multiselect({
	 includeSelectAllOption:1,
	 enableFiltering: true,		
	 enableCaseInsensitiveFiltering:1,
	 maxHeight:300,
	 maxWidth:250,
	 buttonClass: 'btn btn-white btn-primary'
	});

	$('#hamaligroup').multiselect({
	 includeSelectAllOption:1,
	 enableFiltering: true,		
	 enableCaseInsensitiveFiltering:1,
	 maxHeight:300,
	 maxWidth:250,
	 buttonClass: 'btn btn-white btn-primary'
	});

	function CallMe()
	{
		alert("Gajendra Sahu");
	}
	
	function AddLocation()
	{
		var selected = $('#location').val();
		document.getElementById("locations").value=selected;
	}
	
	function AddDepartment()
	{
		var selected = $('#department').val();
		document.getElementById("departments").value=selected;
	}
	function AddSupervisor()
	{
		var selected = $('#supervisor').val();
		document.getElementById("supervisors").value=selected;
	}
	function AddHamaliGroup()
	{
		var selected = $('#hamaligroup').val();
		document.getElementById("hamaligroups").value=selected;
	}
	function AddWorkCode()
	{
		var selected = $('#workcode').val();
		document.getElementById("workcodes").value=selected;
	}

	function LoadDefault()
	{
		var frmdate			=	document.getElementById("frmdate").value;
		var todate			=	document.getElementById("todate").value;
		var locations		=	document.getElementById("locations").value;
		var departments		=	document.getElementById("departments").value;
		var supervisors		=	document.getElementById("supervisors").value;
		var hamaligroups	=	document.getElementById("hamaligroups").value;
		var m				=	document.getElementById("m").value;
		var p				=	document.getElementById("p").value;				
		var pk				=	document.getElementById("pk").value;						
		$(".loader").show();		
		$.post("ajaxpages/loadreport.php",
		{
			frmdate:frmdate,
			todate:todate,
			locations:locations,
			departments:departments,
			supervisors:supervisors,
			hamaligroups:hamaligroups,
			m:m,
			p:p,
			pk:pk
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledata").html(data);
		});
	}
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
