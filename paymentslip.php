<?php
$t=1;
//unset($_SESSION['records']);
//echo encrypt("paymentslip");
if($_POST['acn']=="delete")
{
	$ids	=	$_POST['delids'];
	$rs_sel	=	$dbconnection->firequery("select * from workslip_tbl where workslipid in (".$ids.")");
	$amount	=	0;
	
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$amount		=	$row['workslipamount'];
		$rs_slip	=	$dbconnection->firequery("select * from ");
	}
}
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
			<li><a data-toggle="tab" href="#payslip" onclick="LoadPaySlipList()"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> All Payment Slip List</a></li>			
		</ul>

	
		<div class="tab-content no-border padding-24" style="border:1px solid #ddd; min-height:150px;">			

<div id="feed" class="tab-pane in active">
				<div class="row">
	<form name="pagedata" id="pagedata" method="post" action="#">
<input type="hidden" name="acn" id="acn" value="save" />
<input type="hidden" name="m" id="m" value="<?php echo encrypt("work & payment slip-get payment slip");?>" />
<input type="hidden" name="p" id="p" value="<?php echo encrypt("getpaymentslip");?>" />						
<input type="hidden" name="ids" id="ids" class="ids" value=""/>
<input type="hidden" name="delids" id="delids" class="delids" value="" />
<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="11">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="selectbx" onchange="LoadWorkSlip()" tabindex="<?php echo $t++;?>" style="width:60px;">
							<option value="10">10</option>
							<option value="2500">2500</option>
							<option value="5000">5000</option>
						</select>
<?php
$predates	=	date('Y\-m\-d',strtotime('-30 days'));
?>
						
						<input type="date" name="frmdate" id="frmdate" class="selectbx" value="<?php echo date('Y\-m\-d',strtotime($predates));?>" onchange="LoadWorkSlip()">
						<input type="date" name="todate" id="todate" class="selectbx" value="<?php echo date('Y\-m\-d');?>" onchange="LoadWorkSlip()">
						<?php
						$userslocation	=	$dbconnection->getField("user_tbl","location","userid=".$_SESSION['datadetail'][0]['sessionid']."");
						$rs_hm	=	$dbconnection->firequery("select * from hamaligroup_tbl where locationname in (".$userslocation.") order by groupname");
						?>
						<select name="gpno" id="gpno" class="selectbx" onchange="LoadWorkSlip();">
							<option value="">--Hamali Group Name--</option>
							<?php
							while($gp=mysqli_fetch_assoc($rs_hm))
							{
							?>
							<option value="<?php echo $gp['hgid'];?>"><?php echo $gp['groupname'];?></option>
							<?php
							}
							?>
						</select>
						<?php
						$rs_sec	=	$dbconnection->firequery("select * from section_tbl order by sectionname");
						?>

						<select name="deprt" id="deprt" class="selectbx" onchange="LoadWorkSlip();">
							<option value="">--Section Name--</option>
							<?php
							while($sec=mysqli_fetch_assoc($rs_sec))
							{
							?>
							<option value="<?php echo $sec['sectionid'];?>"><?php echo $sec['sectionname'];?></option>
							<?php
							}
							?>
						</select>
						<br><br>
					<?php
					if($_SESSION['datadetail'][0]['ispayment']!="PAYMENT" && ($_SESSION['datadetail'][0]['authtype']=="ADMIN" || $_SESSION['datadetail'][0]['authtype']=="SUPER ADMIN"))
					{
					?>

	<button type="button" class="btn btn-info delworkslip" style="float:right; margin-left:10px;" disabled="disabled" onclick="DeleteWorkSlip()">Delete Work Slip</button>
					<?php
					}
					?>				

						<?php
						if($_SESSION['datadetail'][0]['ispayment']=="PAYMENT")
						{
						?>
<button type="button" class="btn btn-info payslip" style="float:right;" disabled="disabled" onclick="SubmitForm()">Generate Payment Slip</button>					
						<?php
						}
						?>
		
					</div>
					</th>
				</tr>
				<tr>
					<th style="padding:3px; text-align:center;">
		<input type="checkbox" value="" name="allchk" id="allchk" onclick="CheckAll()" style="vertical-align:text-top;" />
					</th>
					<th style="padding:3px; text-align:center; width:20px;">S.NO.</th>
					<th style="padding:3px;" nowrap>Work Slip Date</th>
					<th style="padding:3px;">Work Slip Number</th>
					<th style="padding:3px;">Remark</th>
					<th style="padding:3px;">Supervisor Name</th>
					<th style="padding:3px;">Hamali Group Number & Name</th>					
					<th style="padding:3px; text-align:right;">Amount</th>
					<th style="padding:3px; text-align:center;">Payment Status</th>					
					<th style="padding:3px;" class="center"><i class="fa fa-eye"></i></th>
					<?php
					if($_SESSION['datadetail'][0]['authtype']=="ADMIN" || $_SESSION['datadetail'][0]['authtype']=="SUPER ADMIN")
					{
					?>
<th style="padding:3px; text-align:center;">
	<input type="checkbox" value="" name="allchkdel" id="allchkdel" onclick="CheckDelAll()" style="vertical-align:text-top;" />
</th>
					<?php
					}
					?>				
					
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




<div id="payslip" class="tab-pane">
				<div class="row">
	<form name="pagedata" id="pagedata" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="pagenumber1" id="pagenumber1" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="12">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize1" id="pagesize1" class="selectbx" onchange="LoadPaySlipList()" tabindex="<?php echo $t++;?>">
							<option value="50">50</option>							
							<option value="500">500</option>														
						</select>
						<?php
						$userslocation	=	$dbconnection->getField("user_tbl","location","userid=".$_SESSION['datadetail'][0]['sessionid']."");
						$rs_hm	=	$dbconnection->firequery("select * from hamaligroup_tbl where locationname in (".$userslocation.") order by groupname");
						?>
						<select name="hgname" id="hgname" class="selectbx" onchange="LoadPaySlipList()">
							<option value="">--Hamali Group Name--</option>
							<?php
							while($gp=mysqli_fetch_assoc($rs_hm))
							{
							?>
							<option value="<?php echo $gp['hgid'];?>"><?php echo $gp['groupname'];?></option>
							<?php
							}
							?>
						</select>
					</div>
					</th>
				</tr>
				<tr>
					<th style="padding:3px; text-align:center; width:20px;">S.NO.</th>
					<th style="padding:3px; text-align:center;">DATE & TIME</th>
					<th style="padding:3px; text-align:center;" nowrap>PAY SLIP NUMBER</th>					
					<th style="padding:3px;">GROUP NAME</th>					
					<th style="padding:3px;">REMARK</th>
					<th style="padding:3px; text-align:center;">AMOUNT</th>
					<th style="padding:3px; text-align:center;">BALANCE</th>					
					<th style="padding:3px;" class="center"><i class="fa fa-print"></i></th>
					<th style="padding:3">&nbsp;</th>
					<th style="padding:3">&nbsp;</th>
					<th style="padding:3">&nbsp;</th>										
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
<script src="./assets/js/jquery-2.1.4.min.js"></script>

<script>
    $(document).ready(function() {
		$("#deleterecord").hide();	
		$("#warningmed").hide();
        LoadWorkSlip();
    });




	$("#allchk").click(function () {
		$(".slips").prop('checked', $(this).prop('checked'));
		var selected = new Array();	
		$(".slips:checkbox:checked").each(function () {
			selected.push(this.value);
		});
		document.getElementById("ids").value	=	selected;
		SelectSlip();
	});

	$("#allchkdel").click(function () {
		$(".delslips").prop('checked', $(this).prop('checked'));
		var selected = new Array();	
		$(".delslips:checkbox:checked").each(function () {
			selected.push(this.value);
		});
		document.getElementById("delids").value	=	selected;
		SelectSlipDel();
	});
	function SelectSlipDel()
	{
		var gpno		=	document.getElementById("gpno").value;			
		var selected = new Array();	
		$(".delslips:checkbox:checked").each(function () {
			selected.push(this.value);
		});
		$("#delids").val(selected);
		if(selected!="" && gpno!="")
		{
			$(".delworkslip").prop("disabled","");
		}
		else
		{
			$(".delworkslip").prop("disabled","disabled");
		}
	}

	function SelectSlip()
	{
		var gpno		=	document.getElementById("gpno").value;			
		var selected = new Array();	
		$(".slips:checkbox:checked").each(function () {
			selected.push(this.value);
		});
		$("#ids").val(selected);
		if(selected!="" && gpno!="")
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
		$.post("ajaxpages/getpaymentslip.php",
		{
			m:m,
			p:p
		},
		function(data, status){
			$(".tabledatalist").html(data);
		});		
	}

	function LoadWorkSlip()
	{
		var ids		=	document.getElementById("ids").value;
		var pagesize	=	document.getElementById("pagesize").value;
		var gpno		=	document.getElementById("gpno").value;		
		var deprt		=	document.getElementById("deprt").value;		
		var frmdate		=	document.getElementById("frmdate").value;		
		var todate		=	document.getElementById("todate").value;		
		var pagenumber	=	document.getElementById("pagenumber").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		$(".loader").show();
		$.post("ajaxpages/pendingworksliplist.php",
		{
			processname: "workcode",
			pagesize: pagesize,
			pagenumber: pagenumber,
			m:m,
			p:p,
			gpno:gpno,
			deprt:deprt,
			frmdate:frmdate,
			todate:todate,
			ids:ids
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledatalist").html(data);
		});		
	}

	function LoadPaySlipList()
	{
		var pagesize	=	document.getElementById("pagesize1").value;
		var hgname		=	document.getElementById("hgname").value;		
		var pagenumber	=	document.getElementById("pagenumber1").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		$(".loader").show();
		$.post("ajaxpages/paymentsliplist.php",
		{
			pagesize: pagesize,
			pagenumber: pagenumber,
			m:m,
			p:p,
			hgname:hgname
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledata").html(data);
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
	
	function PayBalance(slipid,ind,department,location,groupnumber)
	{
		$.post("ajaxpages/balancepay.php",
		{
			ind:ind,
			slipid:slipid,
			department:department,
			location:location,
			groupnumber:groupnumber
		},
		function(data, status){
			$(".prec").css("display","none");
			$("#pay"+ind).css("display","");
			$("#payd"+ind).html(data);
		});		
	}
	function PaySlipDetail(slipid,ind)
	{
		$.post("ajaxpages/viewpayslip.php",
		{
			ind:ind,
			slipid:slipid
		},
		function(data, status){
			$(".prec").css("display","none");
			$("#pay"+ind).css("display","");
			$("#payd"+ind).html(data);
		});			
	}
	function PaidDetail(slipid,ind)
	{
		$.post("ajaxpages/viewpaiddetail.php",
		{
			ind:ind,
			slipid:slipid
		},
		function(data, status){
			$(".prec").css("display","none");
			$("#pay"+ind).css("display","");
			$("#payd"+ind).html(data);
		});			
	}
	function Cls(ind)
	{
		$("#payd"+ind).html("");		
		$("#pay"+ind).css("display","");
		$(".prec").css("display","none");		
	}
	function CloseWindow()
	{
		$(".wrec").css("display","none");
	}
	
	function PayBalanceAmount(ind)
	{
		$("#pbal").prop("disabled","disabled");
		var slipid		=	Number(document.getElementById("slipid").value);		
		var paydate		=	document.getElementById("paydate").value;		
		var paymode		=	document.getElementById("paymode").value;				
		var pay			=	Number(document.getElementById("pay").value);				
		var cdn			=	document.getElementById("cdn").value;				
		var bal			=	document.getElementById("bal").value;
		var department	=	document.getElementById("department").value;
		var location	=	document.getElementById("location").value;
		var groupnumber	=	document.getElementById("groupnumber").value;
		$.post("ajaxpages/payamount.php",
		{
			slipid:slipid,
			paydate:paydate,
			paymode:paymode,
			pay:pay,
			cdn:cdn,
			ind:ind,
			department:department,
			location:location,
			groupnumber:groupnumber
		},
		function(data, status){
			alert("Record saved successfully.");
			PaidDetail(slipid,ind);
		});		
		
	}
    $(document).on('click','.pagelinks',function(){
		document.getElementById("pagenumber").value=$(this).data('runid');
		LoadWorkSlip();
    });
	
</script>

<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>

<script src="./js/bootbox.min.js"></script>
<script type="text/javascript">
	function SubmitForm()
	{
		bootbox.confirm("Do you want to generate payment slip for selected workslip(s).",function(result)
		{
			if(result)
			{ 
				$('#pagedata').attr('onsubmit','return true;');
				$("#pagedata").submit();
			} 
		});	
	}

	function DeleteWorkSlip()
	{
		bootbox.confirm("Do you want to delete selected workslip detail(s).",function(result)
		{
			if(result)
			{
				document.getElementById("acn").value="delete";
				document.getElementById("p").value="7VItoXedIiMPcE66OBOgqw_EQUALS__EQUALS_";
				$('#pagedata').attr('onsubmit','return true;');
				$("#pagedata").submit();
			} 
		});	
	}

	function GetSupervisor(hgid,htmlid)
	{
	  var xhttp;    
	  xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) 
		{
		  document.getElementById(htmlid).innerHTML = this.responseText;
		}
	  };
	  xhttp.open("POST", "ajaxpages/getsupervisor.php?q="+hgid, true);
	  xhttp.send();
	  LoadDefault();
	}

	function LoadMoreData(j)
	{
		var ids			=	document.getElementById("ids").value;
		var pagesize	=	Number(document.getElementById("pagesize").value)+Number(j);
		var gpno		=	document.getElementById("gpno").value;		
		var deprt		=	document.getElementById("deprt").value;		
		var frmdate		=	document.getElementById("frmdate").value;		
		var todate		=	document.getElementById("todate").value;		
		var pagenumber	=	document.getElementById("pagenumber").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		$(".loader").show();
		$.post("ajaxpages/loadmoredata.php",
		{
			processname: "workcode",
			pagesize: pagesize,
			pagenumber: pagenumber,
			m:m,
			p:p,
			gpno:gpno,
			deprt:deprt,
			frmdate:frmdate,
			todate:todate,
			ids:ids
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledatalist").html(data);
		});		
	}
	
</script>

<script src="./assets/js/bootstrap.min.js"></script>
