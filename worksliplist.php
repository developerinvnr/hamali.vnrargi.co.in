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
<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="11">
					<div id="tablehead">
					<?php
					$predates	=	date('Y\-m\-d',strtotime('-30 days'));
					?>
						<input type="date" name="frmdate" id="frmdate" class="selectbx" value="<?php echo date('Y\-m\-d',strtotime($predates));?>" onchange="LoadWorkSlip()">
						<input type="date" name="todate" id="todate" class="selectbx" value="<?php echo date('Y\-m\-d');?>" onchange="LoadWorkSlip()">
						<select name="supervisor" id="supervisor" class="selectbx" onchange="LoadWorkSlip()">
							<option value="">--All Supervisor--</option>
							<?php
							$rs_sel	=	$dbconnection->firequery("select * from supervisor_tbl order by firstname");
							while($sup=mysqli_fetch_assoc($rs_sel))
							{
							?>
							<option value="<?php echo $sup['supervisorid'];?>"><?php echo $sup['firstname'];?> <?php echo $sup['lastname'];?></option>
							<?php
							}
							?>
						</select>
						<select name="hamali" id="hamali" class="selectbx" onchange="LoadWorkSlip()">
							<option value="">--All Hamali Group--</option>
							<?php
							$rs_sel	=	$dbconnection->firequery("select * from hamaligroup_tbl order by groupname");
							while($sup=mysqli_fetch_assoc($rs_sel))
							{
							?>
							<option value="<?php echo $sup['hgid'];?>"><?php echo $sup['groupname'];?></option>
							<?php
							}
							?>
						</select>
					</div>
					<div id="tableheadsearch">
						<div class="nav-search" id="nav-search">
							<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="pagesearch" name="pagesearch" autocomplete="off" onchange="LoadWorkSlip()" tabindex="<?php echo $t++;?>" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
						</div>
					</div>
					</th>
				</tr>
				<tr>
					<th style="padding:3px; text-align:center; width:100px;">S.NO.</th>
					<th style="padding:3px;">Work Slip Date</th>
					<th style="padding:3px;">Work Slip Number</th>
					<th style="padding:3px;">Supervisor Name</th>
					<th style="padding:3px;">Hamali Group Number & Name</th>					
					<th style="padding:3px; text-align:right;">Amount</th>
					<th style="padding:3px; text-align:center;">Payment Status</th>					
					<th style="padding:3px;" class="center"><i class="fa fa-eye"></i></th>
					<th style="padding:3px;" class="center"><i class="fa fa-print"></i></th>					
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

<script src="./assets/js/jquery-2.1.4.min.js"></script>

<script>
    $(document).ready(function() {
		$("#deleterecord").hide();	
		$("#warningmed").hide();
        LoadWorkSlip();
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
		var inputsearch	=	document.getElementById("pagesearch").value;
		var frmdate		=	document.getElementById("frmdate").value;
		var todate		=	document.getElementById("todate").value;
		var supervisor	=	document.getElementById("supervisor").value;
		var hamali		=	document.getElementById("hamali").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		$(".loader").show();
		$.post("ajaxpages/worksliplist.php",
		{
			processname: "workcode",
			searchvalue:inputsearch,
			supervisor:supervisor,
			hamali:hamali,
			frmdate:frmdate,
			todate:todate,
			m:m,
			p:p
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



	
</script>

<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>

<script src="./js/bootbox.min.js"></script>
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

<script src="./assets/js/bootstrap.min.js"></script>
