<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li><i class="ace-icon fa fa-home home-icon"></i><a href="#">Home</a></li>
				<?php
				if(isset($_REQUEST['m']) && !isset($_REQUEST['s']))
				{
				?>
				<li class="active"><?php echo ucwords(trim(decryptvalue($_REQUEST['m'])));?></li>
				<?php
				}
				?>
			</ul><!-- /.breadcrumb -->
			<!--
			<div class="nav-search" id="nav-search">
				<form class="form-search">
					<span class="input-icon">
						<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
						<i class="ace-icon fa fa-search nav-search-icon"></i>
					</span>
				</form>
			</div>
			-->
		</div>

		<div class="page-content">
					<?php 
					if(isset($_SESSION['successmessage'])) 
					{
					?>
					<div class="alert alert-block alert-success" style="color:green;">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>	
						</button>
						<i class="ace-icon fa fa-check" style="color:green;"></i>
						 <?php 
							echo $_SESSION['successmessage']; 
							unset($_SESSION['successmessage']);
						 ?>
					</div>
					<?php
					}
					?>

			<div class="ace-settings-container" id="ace-settings-container">
				<!-- /.ace-settings-box -->
			</div><!-- /.ace-settings-container -->
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="space-6"></div>
						<div class="vspace-12-sm"></div>
					</div>


<div class="row">
	
</div>



				</div>
			</div>
		</div>
	</div>
</div>
<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script>
    $(document).ready(function() {
        LoadDefault();
		$(".loader").hide();

    });

	function CheckAmount(val,j)
	{
		document.getElementById("amount"+j).value	=	document.getElementById("amount"+j).value.replace(/[^0-9]/g, '');
	}

	function Receive(supervisorid,franchiseid,centerid,balance,j)
	{
		var	amount	=	Number(document.getElementById("amount"+j).value);
		var	remark	=	document.getElementById("remark"+j).value;
		
		if(amount=="" || amount=="0" || amount==0)
		{
			$("#msg"+j).css("display","");
			$("#msg"+j).html("Amount value can not be blank and 0!");	
			setTimeout(function(){ LoadDefault(); },3000);	
		}
		else
		{
			$(".loader").show();		
			$.post("ajaxpages/receivesupervisor.php",
			{
				remark:remark,
				amount:amount,
				supervisorid:supervisorid,
				franchiseid:franchiseid,
				centerid:centerid,
				balance:balance			
			},
			function(data, status)
			{
				$(".loader").hide();
				if(data=="success")
				{
					$("#msg"+j).html("");
					$("#msg"+j).css("display","none");
					$("#btn"+j).prop("disabled","true");
					$("#msg1"+j).css("display","");
					$("#msg1"+j).html("Amount received successfully!");					
				}
				else
				{
					$("#msg"+j).css("display","");
					$("#msg"+j).html("Receiving amount can not be greater than balance amount!");
					document.getElementById("amount"+j).value	=	"";	
					document.getElementById("remark"+j).value	=	"";	
				}
			});
			setTimeout(function(){ LoadDefault(); },3000);
		}
	}


	function GetCenter(supervisorid,htmlid)
	{
	  var xhttp;    
	  xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) 
		{
		  document.getElementById(htmlid).innerHTML = this.responseText;
		}
	  };
	  xhttp.open("POST", "ajaxpages/centername.php?q="+supervisorid, true);
	  xhttp.send();
	}

	function LoadDefault()
	{
		var pagesize		=	document.getElementById("pagesize").value;
		var inputsearch		=	document.getElementById("pagesearch").value;
		var supervisor		=	document.getElementById("supervisor").value;		
		var centername		=	document.getElementById("centername").value;				
		var m				=	document.getElementById("m").value;
		var p				=	document.getElementById("p").value;
		$(".loader").show();		
		$.post("ajaxpages/centerwise.php",
		{
			searchvalue:inputsearch,
			supervisor:supervisor,
			centername:centername,
			m:m,
			p:p,
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
<script src="../assets/js/ace-elements.min.js"></script>
<script src="../assets/js/ace.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
