<?php
$t=0;
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
<div class="tabbable">
		<ul class="nav nav-tabs padding-18">
			<li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-plus-circle bigger-120" style="vertical-align:bottom;"></i> Make New Rate List</a></li>	
			<li><a data-toggle="tab" href="#feed"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> Expired Rate List</a></li>
		</ul>

	
		<div class="tab-content no-border padding-24" style="border:1px solid #ddd; min-height:150px;">
			<div id="home" class="tab-pane in active">
				<div class="row">
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
				<div class="col-sm-5">
					<label id="lab">Enter Rate List Name<label id="req">*</label></label>
					<input type="text" class="form-control" name="ratelistname" id="ratelistname" placeholder="Rate list name" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autofocus autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-2">
					<br id="forbutton" />
					<button type="button" class="btn btn-info" tabindex="<?php echo $t++;?>" onclick="LoadDefault()" style="width:100%;">PREPARE RATE LIST</button>
				</div>
				<div class="col-sm-12">&nbsp;</div>
				<div class="col-sm-2">
					<label id="lab">Select Rate List<label id="req">*</label></label>
					<select class="form-control" name="ratelist" id="ratelist" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" onchange="LoadDefault()">
						<option value="">--Select Rate List--</option>
						<?php
						$rs_rate	=	$dbconnection->firequery("select * from rate_tbl where rateid in (select rateid from rate_list where expirydate>'".date('Y\-m\-d H:i:s')."' or expirydate='0000-00-00 00:00:00' or expirydate='1970-01-01 ') order by ratelistname");
						while($rt=mysqli_fetch_assoc($rs_rate))
						{
						?>
						<option value="<?php echo $rt['rateid'];?>"><?php echo $rt['ratelistname'];?></option>
						<?php
						}
						unset($rs_rate);
						unset($rt);
						?>						
					</select>
				</div>	
				<div class="col-sm-3">
					<label id="lab">Select Rate List & Set Expiration Date<label id="req">*</label></label>
<input type="datetime-local" class="form-control" name="expiry" id="expiry" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>"/>
				</div>
				<div class="col-sm-2">
					<br id="forbutton" />
					<button type="button" class="btn btn-info" tabindex="<?php echo $t++;?>" onclick="UpdateExpiry()" style="width:100%;">Update Expiry</button>
				</div>
				<div class="col-sm-2">
					<br id="forbutton" />
					<button type="button" class="btn btn-info" tabindex="<?php echo $t++;?>" onclick="ExpiryNow()" style="width:100%;">Expire Now</button>
				</div>
				
			</div>		
		</form>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row">
	<form name="pagedata" id="pagedata" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th style="padding:3px; text-align:center; width:100px;">Work Code</th>
					<th style="padding:3px;">Particular</th>
					<th style="padding:3px; text-align:center; width:100px;">Rate</th>
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
			<div id="feed" class="tab-pane">
			<div class="row">
		<form name="frm1" id="frm1" action="#" method="post">
			<div class="form-group">
				<div class="col-sm-4" style="padding:0px;">
					<label id="lab">Select Rate List<label id="req">*</label></label>
					<select class="form-control" name="rtlist" id="rtlist" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" onchange="LoadExpired()">
						<option value="">--Select Rate List--</option>
						<?php
						$rs_rate	=	$dbconnection->firequery("select * from rateexpiry_tbl where nextexpiry<'".date('Y\-m\-d H:i:s')."' order by ratelistname");
						while($rt=mysqli_fetch_assoc($rs_rate))
						{
						?>
						<option value="<?php echo $rt['rateid'];?>"><?php echo $rt['ratelistname'];?> [Expired : <?php echo date('d\-m\-Y h:i A',strtotime($rt['nextexpiry']));?>]</option>
						<?php
						}
						unset($rs_rate);
						unset($rt);
						?>						
					</select>
				</div>		
			</div>		
		</form>
		
			</div>
		<div class="row">&nbsp;</div>	
		<div class="row">
	<form name="pagedata1" id="pagedata1" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="pagenumber1" id="pagenumber1" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th style="padding:3px; text-align:center; width:100px;">Work Code</th>
					<th style="padding:3px;">Particular</th>
					<th style="padding:3px; text-align:center; width:100px;">Rate</th>
					<th style="padding:3px; text-align:center; width:100px;">Expired Date</th>
				</tr>
			</thead>
			<tbody class="tabledata1">
				<div class="loader"></div>			
			</tbody>
		</table>
	</div>
	</form>
		
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
       // LoadDefault();
	   LoadExpired();
	   $(".loader").hide();
	   
    });
	function LoadExpired()
	{
		var	rateid	=	document.getElementById("rtlist").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;
		$(".loader").show();		
		$.post("ajaxpages/expiredratelist.php",
		{
			m:m,
			p:p,
			rateid:rateid
		},
		function(data, status){
			$(".loader").hide();
			$(".tabledata1").html(data);
		});
	}

	function ExpiryNow()
	{
		var	ratelist	=	document.getElementById("ratelist").value;
		var	expiry		=	document.getElementById("expiry").value;
		if(ratelist!="")
		{
		bootbox.confirm("Make sure that you want to set selected rate list as expired.", function(result)
		{ 
		if(result)
		{
			if(ratelist!="" && expiry!="")
			{
				$(".loader").show();
				$.post("ajaxpages/expirenow.php",
				{
					ratelist:ratelist,
					expiry:expiry
				},
				function(data, status){
					if(data.trim()=="success")
					{
						$(".loader").hide();
						
						setTimeout(function(){ window.location.href=""; },2000);
					}
					else
					{
						$(".loader").hide();
						bootbox.alert("Found some problem. Please try again.");
					}
				});
			}
			else
			{
				bootbox.alert("Please select ratelist and enter rate list expiry date.");
			}
		} });
		}
		else
		{
			bootbox.alert("Please select rate list that you wish to set as expired now."); 
		}
	}

	
	function LoadDefault()
	{
		var	rate		=	document.getElementById("ratelistname").value;
		var	ratelist	=	document.getElementById("ratelist").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;
		if((rate=="" && ratelist!="") || (rate!="" && ratelist==""))
		{
			$(".loader").show();		
			$.post("ajaxpages/makeratelist.php",
			{
				m:m,
				p:p,
				rate:rate,
				ratelist:ratelist
			},
			function(data, status){
				$(".loader").hide();
				$("#expiry").prop("readonly","");
				$(".tabledata").html(data);
			});
		}
		else
		{
			bootbox.alert("Please enter rate list name.");
		}
	}

	function UpdateExpiry()
	{
		var	ratelist	=	document.getElementById("ratelist").value;
		var	expiry		=	document.getElementById("expiry").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;
		bootbox.confirm("Please make sure that you want to set expiry date for selected rate list.", function(result)
		{ 
		if(result)
		{
			if(ratelist!="" && expiry!="")
			{
				$(".loader").show();
				$.post("ajaxpages/updateexpiry.php",
				{
					m:m,
					p:p,
					expiry:expiry,
					ratelist:ratelist
				},
				function(data, status){
					var str	=	data.split("|");
					if(str[0].trim()=="success")
					{
						$(".loader").hide();
						bootbox.alert("Expiry date updated successfully!");
						LoadDefault();
					}
					else
					{
						$(".loader").hide();
						bootbox.alert("Update failed. Expiry date must be one or more day(s) ahead from last expiry date.");
					}
				});
			}
			else
			{
				bootbox.alert("Please select ratelist and enter rate list expiry date.");
			}
		} });
	}

	function UpdatePrice(val,ind,recid,tab,rateid)	
	{
		if(val!=0 && val!="")
		{
			$(".loader").show();		
			$.post("ajaxpages/updateprice.php",
			{
				val:val,
				recid:recid,
				rateid:rateid
			},
			function(data, status){
				if(data=="success")
				{
					$(".loader").hide();
					$("#trid"+ind).css("display","");
					$("#msg"+ind).html("PRICE UPDATED SUCCESSFULLY!");
					setTimeout(function() { $("#msg"+ind).html("PRICE UPDATED SUCCESSFULLY!"); $("#trid"+ind).css("display","none");  },3000);
					$("#price"+ind).focus();
				}
				if(data=="error")
				{
					$(".loader").hide();
					$("#trid"+ind).css("display","");
					$("#msg"+ind).html("PRICE COULD NOT BE UPDATED. PLEASE TRY AGAIN!");					
					setTimeout(function() { $("#msg"+ind).html("PRICE UPDATED SUCCESSFULLY!"); $("#trid"+ind).css("display","none");  },3000);
					$("#price"+ind).focus();					
				}
			});		
		}
		else
		{
			bootbox.alert("Price can not be a blank or 0.");
			return;
		}
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
