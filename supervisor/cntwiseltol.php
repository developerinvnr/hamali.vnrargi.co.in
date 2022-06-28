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
					<th colspan="11">
					<div id="tablehead">
						<select name="centername" id="centername" class="selectbx" onchange="LoadDefault();" tabindex="<?php echo $t++;?>">
							<option value="">--Center Head Name--</option>
							<?php
							$rs_sel	=	$dbconnection->firequery("select * from center_tbl where franchisename=".$_SESSION['franchisedetail'][0]['sessionid']."");
							while($cnt=mysqli_fetch_assoc($rs_sel))
							{
							?>
							<option value="<?php echo $cnt['centerid'];?>"><?php echo $cnt['centername'];?></option>
							<?php
							}
							unset($rs_sel);
							unset($cnt);
							?>							
						</select>
						
						From Date : <input type="date" name="frmdate" id="frmdate" class="selectbx" style="padding:0px;" value="<?php echo date('Y\-m\-d');?>" onchange="LoadDefault()" />
						To Date : <input type="date" name="todate" id="todate" class="selectbx" style="padding:0px;" value="<?php echo date('Y\-m\-d');?>" onchange="LoadDefault()" />						
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
<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script>
    $(document).ready(function() {
        LoadDefault();
    });

	function LoadDefault()
	{
		var centername		=	document.getElementById("centername").value;				
		var frmdate			=	document.getElementById("frmdate").value;
		var todate			=	document.getElementById("todate").value;		
		var m				=	document.getElementById("m").value;
		var p				=	document.getElementById("p").value;				
		$(".loader").show();		
		$.post("ajaxpages/cntwiseltol.php",
		{
			processname: "",
			centername:centername,
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
