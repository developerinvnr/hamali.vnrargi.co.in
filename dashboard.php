<?php
$t=0;
//echo strlen("540,527,528,554,537,568,538,541,551,544,560,563,549,550,619,570,588,587,589,639,613,614,616,618,620,623,600,641,629,631,632,635,664,644,650,651,656,72");
?>
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li><i class="ace-icon fa fa-home home-icon"></i><a href="./mainindex.php" style="text-decoration:none;">Home</a></li>
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
		<div class="row">
		<form name="frm" id="frm" action="#" method="post">
			<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
			<input type="hidden" name="pk" id="pk" value="<?php echo $pk;?>" />
			<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
			<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />						
			<input type="hidden" name="<?php echo $pk;?>" id="<?php echo $pk;?>" value="<?php echo $_REQUEST['pk'];?>" />
			<input type="hidden" name="tablename" id="tablename" value="<?php echo encrypt($tablename);?>" />			
			<div class="form-group">
				<div class="col-sm-3">
					<label id="lab">From Date<label id="req">*</label></label>
					<input type="date" class="form-control" name="frmdate" id="frmdate" value="<?php echo date('Y\-m\-01');?>">
				</div>		
				<div class="col-sm-3">
					<label id="lab">To Date<label id="req">*</label></label>
					<input type="date" class="form-control" name="todate" id="todate" value="<?php echo date('Y\-m\-d');?>">
				</div>		
				<div class="col-sm-3">
					<br>
					<button type="button" class="btn" onclick="LoadDefault()">Get Detail</button>
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
		<table class="table" id="tablerecords">
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
<script>
    $(document).ready(function() {
       LoadDefault();
	   $(".loader").hide();
	   
    });
	function CloseWindow()
	{
		$(".wrec").css("display","none");
	}
	function ClsWind(ind)
	{
		$("#rec"+ind).css("display","none");
	}

	function DepartmentWise(ind,locationid,frmdate,todate)
	{
		LoadDefault();
		$.post("ajaxpages/departmentwise.php",
		{
			ind:ind,
			locationid:locationid,
			frmdate:frmdate,
			todate:todate
		},
		function(data, status){
			$(".wrec").css("display","none");
			$("#rec"+ind).css("display","");
			$("#recd"+ind).html(data);
		});	
	}

	function DeprtWise(ind,locationid,frmdate,todate)
	{
		$.post("ajaxpages/deprtwise.php",
		{
			ind:ind,
			locationid:locationid,
			frmdate:frmdate,
			todate:todate
		},
		function(data, status){
			$(".wrec").css("display","none");
			$("#rec"+ind).css("display","");
			$("#recd"+ind).html(data);
		});	
	}
	function WorkWise(ind,locationid,frmdate,todate)
	{
		$.post("ajaxpages/workwise.php",
		{
			ind:ind,
			locationid:locationid,
			frmdate:frmdate,
			todate:todate
		},
		function(data, status){
			$(".wrec").css("display","none");
			$("#rec"+ind).css("display","");
			$("#recd"+ind).html(data);
		});	
	}
	function DepartmentWorkWise(ind,locationid,departmentid,frmdate,todate)
	{
		$.post("ajaxpages/departmentworkwise.php",
		{
			ind:ind,
			locationid:locationid,
			frmdate:frmdate,
			todate:todate,
			departmentid:departmentid
		},
		function(data, status){
			$(".wdrec").css("display","none");
			$("#recd"+ind).css("display","");
			$("#recdd"+ind).html(data);
		});	
	}

	function LoadDefault()
	{
		var	frmdate	=	document.getElementById("frmdate").value;
		var	todate	=	document.getElementById("todate").value;		
		var m		=	document.getElementById("m").value;
		var p		=	document.getElementById("p").value;
		$(".loader").show();		
		$.post("ajaxpages/defaultdashboard.php",
		{
			m:m,
			p:p,
			frmdate:frmdate,
			todate:todate,
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
