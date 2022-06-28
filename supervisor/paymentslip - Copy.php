<?php
$t=1;
//unset($_SESSION['records']);
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
			<li class="active"><a data-toggle="tab" href="#feed" onclick="LoadWorkSlip()"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> All Work Slip List</a></li>
		</ul>

	
		<div class="tab-content no-border padding-24" style="border:1px solid #ddd; min-height:150px;">			

<div id="feed" class="tab-pane in active">
				<div class="row">
	<form name="pagedata" id="pagedata" action="#" method="post" onsubmit="return false;">
<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
<input type="hidden" name="pk" id="pk" value="<?php echo $pk;?>" />
<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />						
<input type="hidden" name="workslipid" id="workslipid" value="workslipid" />
<input type="hidden" name="tablename" id="tablename" value="<?php echo encrypt($tablename);?>" />			
<input type="hidden" name="ids" id="ids" class="ids" />
	<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="10">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="form-control" onchange="GetLoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="200">200</option>							
							<option value="500">500</option>														
						</select> records
						<button type="button" class="btn btn-info payslip" style="float:right;" disabled="disabled" onclick="GetPaymentSlip()">Generate Payment Slip</button>					
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
<script src="../assets/js/jquery-2.1.4.min.js"></script>

<script>
    $(document).ready(function() {
		$("#deleterecord").hide();	
		$("#warningmed").hide();
        LoadWorkSlip();
    });

	function SelectSlip()
	{
		var selected = new Array();	
		$(".slips:checkbox:checked").each(function () {
			selected.push(this.value);
		});
		$("#ids").val(selected);
		if(selected!="")
		{
			$(".payslip").prop("disabled","");
		}
		else
		{
			$(".payslip").prop("disabled","disabled");
		}
	}
	function GetPaymentSlip()
	{
		var ids		=	document.getElementById("ids").value;
		alert(ids);
		$(".loader").show();
		$.post("ajaxpages/getpaymentslip.php",
		{
			ids:ids,
			m:m,
			p:p,
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledatalist").html(data);
		});		
	}

	function LoadWorkSlip()
	{
		var pagesize	=	document.getElementById("pagesize").value;
		var pagenumber	=	document.getElementById("pagenumber").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();
		$.post("ajaxpages/pendingworksliplist.php",
		{
			processname: "workcode",
			pagesize: pagesize,
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


</script>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script src="../js/bootbox.min.js"></script>
<script type="text/javascript">
	function SubmitForm()
	{
		bootbox.confirm("Do you want to save this record.",function(result)
		{
			if(result)
			{ 
				$('#pagedata').attr('onsubmit','return true;');
				$("#pagedata").submit();
			} 
		});	
	}
</script>

<script src="../assets/js/bootstrap.min.js"></script>
