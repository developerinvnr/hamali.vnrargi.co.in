<?php
$t=1;
?>
<div class="main-content">
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
<div class="row">
	<div class="col-xs-12">
	<?php
	?>
	<form name="pagedata" id="pagedata" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
	<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />
	
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="9">
					<div id="tablehead">
					<?php
					$rs_sup	=	$dbconnection->firequery("select * from supervisor_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']." and activestatus='ACTIVE' order by supervisorname");
					?>
					<select name="supervisor" id="supervisor" class="selectbx" onchange="GetStaffList(this.value,'staffname')" tabindex="<?php echo $t++;?>">
					<option value="">--Center Head Name--</option>
					<?php
					while($sup=mysqli_fetch_assoc($rs_sup))
					{
					?>
					<option value="<?php echo $sup['supervisorid'];?>"><?php echo ucwords($sup['supervisorname']);?></option>
					<?php
					}
					unset($rs_sel);
					unset($roo);
					?>
					</select>

					<select name="staffname" id="staffname" class="selectbx" tabindex="<?php echo $t++;?>">
					<option value="">--Staff Name--</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;
					From : <input type="date" name="frmdate" id="frmdate" class="selectbx" style="padding:0px;" value="<?php echo date('Y\-m\-01');?>"/>
					To : <input type="date" name="todate" id="todate" class="selectbx" style="padding:0px;" value="<?php echo date('Y\-m\-d');?>"/>
					<button type="button" class="btn btn-info" onclick="LoadDefault()" style="">Get Detail</button>
					<button type="button" class="btn btn-info" onclick="LoadLedger()" style="float:right;">Get New Ladger</button>					
					</div>
					</th>
				</tr>
				
			</thead>
			<tbody class="tabledata" id="printdata">
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

<div class="modal fade left" id="myModal" role="dialog" data-backdrop="static">
	<div class="modal-lg-dialog">
		<div class="modal-content" style="font-size:12px; width:100%; margin:0 auto; margin-top:0px;">
		</div>
	</div>
</div>


<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script>
    $(document).ready(function() {
        LoadDefault();
		//$("#myModal").modal("show");					
    });

	function LoadLedger()
	{
		var staffname	=	document.getElementById("staffname").value;
		var headid		=	document.getElementById("supervisor").value;		
		var frmdate		=	document.getElementById("frmdate").value;
		var todate		=	document.getElementById("todate").value;				
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;
		if(staffname!="" && headid!="")
		{
			$(".loader").show();
			$.post("ajaxpages/loadledger.php",
			{
				staffname:staffname,
				headid:headid,
				frmdate:frmdate,
				todate:todate,
				m:m,
				p:p
			},
			function(data, status){
				$(".loader").hide();
				$(".tabledata").html(data);
			});
		}
		else
		{
			bootbox.alert("Please select center head name and staff name");
		}
	}

	
	function DiscountBalance()
	{
		var ids	=	document.getElementById("selectedval").value;
		if(ids=="")
		{
			bootbox.alert("Please select at least one record for giving discount. Thanks");
		}
		else
		{
			bootbox.confirm("Do you want to clear balances as discount!",
			function(result)
			{ 
				if(result)
				{ 
					$(".loader").show();
					var headid		=	document.getElementById("supervisor").value;								
					$.post("ajaxpages/discountbalance.php",
					{
						ids:ids,
						headid:headid
					},
					function(data, status)
					{
						$(".loader").hide();
						$(".modal-content").html("");		
						$("#myModal").modal("hide");
						LoadDefault();												
					});
				}
			});	
		}
	}

	function PayAllBalance()
	{
		var ids	=	document.getElementById("selectedval").value;
		if(ids=="")
		{
			bootbox.alert("Please select at least one record for receiving payment. Thanks");
		}
		else
		{
			bootbox.confirm("Do you want to receive all balances on behalf of staff!",
			function(result)
			{ 
				if(result)
				{ 
					$(".loader").show();
					var headid		=	document.getElementById("supervisor").value;								
					$.post("ajaxpages/payallbalance.php",
					{
						ids:ids,
						headid:headid
					},
					function(data, status)
					{
						$(".loader").hide();
						$(".modal-content").html("");		
						$("#myModal").modal("hide");
						LoadDefault();												
					});
				}
			});	
		}
	}


	
	function UpdateDiscount(j,staffid,centerid)
	{	
		var headid		=	document.getElementById("supervisor").value;			
		var	balance		=	Number(document.getElementById("sbalance"+j).value);
		var	discount	=	Number(document.getElementById("sdiscount"+j).value);
		var	custid		=	Number(document.getElementById("scustid"+j).value);		
		var	remark		=	document.getElementById("sremark"+j).value;
		var frmdate		=	document.getElementById("frmdate").value;
		var todate		=	document.getElementById("todate").value;				
		if(Number(discount)<=Number(balance))
		{
			$(".loader").show();		
			$.post("ajaxpages/updatebalance.php",
			{
				staffid:staffid,
				centerid:centerid,
				remark:remark,
				discount:discount,
				headid:headid,
				custid:custid
			},
			function(data, status)
			{
				$(".loader").hide();
				var str	=	data.split("|");
				if(str[0]=="success")
				{
					$("#afterdiscount"+j).html(str[1]);
					$("#spaid"+j).html(str[2]);					
					$("#sbal"+j).html(str[3]);
					$("#sadmin"+j).html(str[4]);
					$("#sbalance"+j).val(Number(str[3]));
					$("#mg"+j).html("");
					$("#mg"+j).css("display","none");
	
					$("#mg1"+j).css("display","");
					$("#mg1"+j).html("Discount updated successfully!");
					$("#sdiscount"+j).val("");					
					$("#sremark"+j).val("");					
					setTimeout(function(){ $("#mg1"+j).css("display","none"); },3000);			
				}
				else
				{
					$("#sdiscount"+j).val("");					
					$("#sremark"+j).val("");
					$("#mg"+j).css("display","");
					$("#mg"+j).html("Discount amount can not be 0 or greater than balance amount!");
				}

			});
//			GetBalanceList(centerid,staffid,frmdate,todate);
		}
		else
		{
			$("#sdiscount"+j).val("");					
			$("#sremark"+j).val("");
			$("#mg"+j).css("display","");
			$("#mg"+j).html("Discount amount can not be 0 or greater than balance amount!");
			setTimeout(function(){ $("#mg"+j).css("display","none"); },3000);
		}
	}

	function PayBalance(j,staffid,centerid)
	{	
		
		var headid		=	document.getElementById("supervisor").value;			
		var	balance		=	Number(document.getElementById("sbalance"+j).value);
		var	pay			=	Number(document.getElementById("spay"+j).value);
		var	custid		=	Number(document.getElementById("scustid"+j).value);		
		var	remark		=	document.getElementById("spayremark"+j).value;
		var frmdate		=	document.getElementById("frmdate").value;
		var todate		=	document.getElementById("todate").value;				
		if(Number(pay)<=Number(balance))
		{
			
			$(".loader").show();		
			$.post("ajaxpages/paybalance.php",
			{
				staffid:staffid,
				centerid:centerid,
				remark:remark,
				pay:pay,
				headid:headid,
				custid:custid
			},
			function(data, status)
			{
				$(".loader").hide();
				var str	=	data.split("|");
				if(str[0]=="success")
				{
					$("#afterdiscount"+j).html(str[1]);
					$("#spaid"+j).html(str[2]);					
					$("#sbal"+j).html(str[3]);
					$("#sadmin"+j).html(str[4]);
					$("#sbalance"+j).val(Number(str[3]));
					$("#smg"+j).html("");
					$("#smg"+j).css("display","none");
	
					$("#smg1"+j).css("display","");
					$("#smg1"+j).html("Amount paid successfully!");
					$("#spay"+j).val("");					
					$("#spayremark"+j).val("");					
					setTimeout(function(){ $("#smg1"+j).css("display","none"); },3000);			
				}
				else
				{
					$("#spay"+j).val("");					
					$("#spayremark"+j).val("");
					$("#smg"+j).css("display","");
					$("#smg"+j).html("Paying amount can not be 0 or greater than balance amount!");
				}

			});
//			GetBalanceList(centerid,staffid,frmdate,todate);
		}
		else
		{
			$("#sdiscount"+j).val("");					
			$("#sremark"+j).val("");
			$("#mg"+j).css("display","");
			$("#mg"+j).html("Discount amount can not be 0 or greater than balance amount!");
			setTimeout(function(){ $("#mg"+j).css("display","none"); },3000);
		}
	}

	
	function AddCustomerId()
	{
		var selected = new Array();	
		$("input[type=checkbox]:checked").each(function () {
			selected.push(this.value);
		});
		document.getElementById("selectedval").value	=	selected;
	}
	
	function GetBalanceList(centerid,staffid,frmdate,todate)
	{
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		$(".loader").show();		
		$.post("ajaxpages/showbalancelist.php",
		{
			centerid:centerid,
			staffid:staffid,
			frmdate:frmdate,
			todate:todate,
			m:m,
			p:p
		},
		function(data, status){
			$(".loader").hide();		
			$("#myModal").modal("show");			
			$(".modal-content").html(data);
		});
	}
	function GetFullBalanceList(centerid,staffid)
	{
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		$(".loader").show();		
		$.post("ajaxpages/showfullbalancelist.php",
		{
			centerid:centerid,
			staffid:staffid,
			m:m,
			p:p
		},
		function(data, status){
			$(".loader").hide();		
			$("#myModal").modal("show");			
			$(".modal-content").html(data);
		});
	}

	function Close()
	{
		$(".modal-content").html("");		
		$("#myModal").modal("hide");
		LoadDefault();					
	}
	
	function GetStaffList(supervisorid,htmlid)
	{
	  var xhttp;    
	  xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) 
		{
		  document.getElementById(htmlid).innerHTML = this.responseText;
		}
	  };
	  xhttp.open("POST", "ajaxpages/getstafflist.php?q="+supervisorid, true);
	  xhttp.send();
	  document.getElementById("staffname").value="";
	  LoadDefault();
	}

	function LoadDefault()
	{
		var staffname	=	document.getElementById("staffname").value;
		var headid		=	document.getElementById("supervisor").value;		
		var frmdate		=	document.getElementById("frmdate").value;
		var todate		=	document.getElementById("todate").value;				
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;
		if(staffname!="" && headid!="")
		{
			$(".loader").show();
			$.post("ajaxpages/receivenew.php",
			{
				processname: "",
				staffname:staffname,
				headid:headid,
				frmdate:frmdate,
				todate:todate,
				m:m,
				p:p
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
		}
	}


	function Receive(j)
	{
		var	ppd			=	Number(document.getElementById("ppd"+j).value);
		var	amount		=	Number(document.getElementById("recamt"+j).value);
		var	remark		=	document.getElementById("remark"+j).value;
		var	insdate		=	document.getElementById("insdate"+j).value;		
		var	franchiseid	=	Number(document.getElementById("franchiseid"+j).value);
		var	balance		=	Number(document.getElementById("balance"+j).value);		
		var	centerid	=	Number(document.getElementById("centerid"+j).value);		
		var	headid		=	Number(document.getElementById("headid"+j).value);				
		var	staffid		=	Number(document.getElementById("staffid"+j).value);				
		var a			=	confirm("Are you sure. You want to submit this record");
		if(a)
		{
			if(amount!=0 && amount!="")
			{
				$("#btn"+j).attr("disabled",true);
				$(".loader").show();		
				$.post("ajaxpages/receivefromstaff.php",
				{
					remark:remark,
					amount:amount,
					franchiseid:franchiseid,
					centerid:centerid,
					headid:headid,
					insdate:insdate,
					balance:balance,
					ppd:ppd,
					staffid:staffid
				},
				function(data, status)
				{
					$(".loader").hide();
					var str	=	data.split("|");
					if(str[0]=="success")
					{
						$("#msg"+j).html("");
						$("#msg"+j).css("display","none");
						$("#msg1"+j).css("display","");
						$("#msg1"+j).html("Received!");
						$("#pd"+j).html(str[2]);
						$("#ppd"+j).val(Number(str[2]));
						$("#balance"+j).val(Number(str[1]));
						$("#bal"+j).html(str[1]);
						$("#recamt"+j).val("");					
						$("#remark"+j).val("");					
						setTimeout(function(){ $("#msg1"+j).css("display","none"); },5000);			
						$("#btn"+j).attr("disabled",true);						
					}
					else
					{
						$("#msg"+j).html("");
						$("#msg"+j).css("display","none");
						$("#msg1"+j).css("display","");
						$("#msg1"+j).html("Check Amount!");	
						$("#recamt"+j).val("");					
						$("#remark"+j).val("");					
						setTimeout(function(){ $("#msg1"+j).css("display","none"); },3000);			
						$("#btn"+j).attr("disabled",false);	
					}
				});
				//setTimeout(function(){ LoadDefault(); },1000);
			}
			else
			{
				bootbox.alert("Amount value should not be 0 or empty!");
			}
		}
		return false;
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
