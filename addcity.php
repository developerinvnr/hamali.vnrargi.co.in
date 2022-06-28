<?php
//echo trim(decryptvalue("jri20RHCaaQ_EQUALS_"));
$t=0;
$tablename	=	"city_tbl";
$pk			=	$dbconnection->getField("master_tbl","fieldname","tablename='".$tablename."' and pk='YES'");
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="save")
	{
		$flag	=	0;
		$msgErr	=	"";
		$rs_validation	=	$dbconnection->firequery("select * from master_tbl where tablename='".$tablename."' and pk!='YES' and isrequired='required' order by displayorder");
		while($validation=mysqli_fetch_assoc($rs_validation))
		{
			if($validation['validationtype']=="required")
			{
				if(empty($_POST[$validation['fieldname']]))
				{
					$flag++;
					$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
				}
			}
		}

		
		if($flag==0)
		{
			$fields	=	$dbconnection->getFieldNames($tablename);
			$values	=	$dbconnection->getFieldValues($tablename,$sessionid);

			if($dbconnection->firequery("insert into $tablename($fields) values($values)"))
			{
				unset($fields);
				unset($values);				
				unset($flag);				
				unset($msgErr);				
				$_SESSION['success']	=	"City name added successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"City name already exist. Please check name and try again.";
			}
		}
	}
	if($_POST['acn']=="update")
	{
		$flag	=	0;
		$msgErr	=	"";
		$rs_validation	=	$dbconnection->firequery("select * from master_tbl where tablename='".$tablename."' and pk!='YES' and isrequired='required' order by displayorder");
		while($validation=mysqli_fetch_assoc($rs_validation))
		{
			if($validation['validationtype']=="required")
			{
				if(empty($_POST[$validation['fieldname']]))
				{
					$flag++;
					$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
				}
			}
		}
		if($flag==0)
		{
			$rs_fields	=	$dbconnection->firequery("select * from master_tbl where tablename='".$tablename."' and pk!='YES' and visibleinform='YES' order by displayorder");
			$updatefields="";
			$up=0;
			while($row_fields=mysqli_fetch_assoc($rs_fields))
			{
				$up++;
				if($up==1)
				{
					if($row_fields['fielddatatype']=="string")
					{
						$updatefields.=	"".$row_fields['fieldname']."='".strtoupper($_POST[$row_fields['fieldname']])."'";
					}
					else if($row_fields['fielddatatype']=="number")
					{
						$updatefields.=	"".$row_fields['fieldname']."=".$_POST[$row_fields['fieldname']]."";
					}
					else if($row_fields['fielddatatype']=="date")
					{
						$updatefields.=	"".$row_fields['fieldname']."='".date('Y\-m\-d',strtotime($_POST[$row_fields['fieldname']]))."'";
					}
					
				}
				else
				{
					if($row_fields['fielddatatype']=="string")
					{
						$updatefields.=	","."".$row_fields['fieldname']."='".strtoupper($_POST[$row_fields['fieldname']])."'";
					}
					else if($row_fields['fielddatatype']=="number")
					{
						$updatefields.=	","."".$row_fields['fieldname']."=".$_POST[$row_fields['fieldname']]."";
					}
					else if($row_fields['fielddatatype']=="date")
					{
						$updatefields.=	","."".$row_fields['fieldname']."='".date('Y\-m\-d',strtotime($_POST[$row_fields['fieldname']]))."'";
					}
				}				
			}
			if($dbconnection->firequery("update $tablename set $updatefields where ".$_REQUEST['pk']."=".trim(decryptvalue($_POST[$_REQUEST['pk']])).""))
			{
				unset($updatefields);
				unset($rs_fields);
				unset($row_fields);				
				unset($up);				
				$_SESSION['success']	=	"City name updated successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
		}		
	}
	if($_POST['acn']=="delete")
	{
		if(!$dbconnection->isRecordExist("select * from location_tbl where cityname=".trim(decryptvalue($_POST[$_REQUEST['pk']])).""))
		{			
			if($dbconnection->firequery("delete from $tablename where ".$_REQUEST['pk']."=".trim(decryptvalue($_POST[$_REQUEST['pk']])).""))
			{ 
				$_SESSION['success']	=	"City name deleted successfully!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again!";
				echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
		}
		else
		{
			$_SESSION['warning']	=	"You can not delete this city name . Since this city name is associated with some location!";
			echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
			exit;				
		}
	}

}
else
{
	$_POST['acn']	=	"save";
	if($_REQUEST['pk']!="")
	{
		$rs_update	=	$dbconnection->firequery("select * from $tablename where ".$pk."=".trim(decryptvalue($_REQUEST['pk']))."");
		while($rowupdate=mysqli_fetch_assoc($rs_update))
		{
			$rs_mast	=	$dbconnection->firequery("select * from master_tbl where tablename='".$tablename."' and pk!='YES' and visibleinform='YES' order by displayorder");
			while($rowmast=mysqli_fetch_assoc($rs_mast))
			{
				$_POST[$rowmast['fieldname']]	=	$rowupdate[$rowmast['fieldname']];
			}
		}
		$_POST['servicestatus']	=	$dbconnection->getField("city_tbl","servicestatus","cityid=".trim(decryptvalue($_REQUEST['pk']))."");
		unset($rs_update);
		unset($rowupdate);
		unset($rs_mast);
		unset($rowmast);
		$_POST['acn']	=	"update";		
	}
}
if($_REQUEST['setdefault']=='YES')
{
	$dbconnection->firequery("update city_tbl set defaultcity='NO'");	
	$dbconnection->firequery("update city_tbl set defaultcity='YES' where cityid=".trim(decryptvalue($_REQUEST['pk']))."");	
	$_SESSION['success']	=	"Default city set successfully!";
	echo '<script>document.location.href="./mainindex.php?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
	exit;
	
}
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
		<form name="frm" id="frm" action="#" method="post" enctype="multipart/form-data">
			<input type="hidden" name="acn" id="acn" value="<?php echo $_POST['acn'];?>" />
			<input type="hidden" name="pk" id="pk" value="<?php echo $pk;?>" />
			<input type="hidden" name="m" id="m" value="<?php echo $_REQUEST['m'];?>" />
			<input type="hidden" name="p" id="p" value="<?php echo $_REQUEST['p'];?>" />						
			<input type="hidden" name="<?php echo $pk;?>" id="<?php echo $pk;?>" value="<?php echo $_REQUEST['pk'];?>" />
			<input type="hidden" name="tablename" id="tablename" value="<?php echo encrypt($tablename);?>" />			
			<div class="form-group">
			<?php
			$rs_sel	=	$dbconnection->firequery("select * from master_tbl where tablename='".$tablename."' and pk!='YES' order by displayorder");
			$totalrows	=	0;
			while($row=mysqli_fetch_assoc($rs_sel))
			{
				$totalrows=$totalrows+$row['columnsize'];
				if($totalrows>12)
				{
				$totalrows=0;
				?>
				<div class="col-sm-12">&nbsp;</div>
				<?php
				}
				?>
				<div class="col-sm-<?php echo $row['columnsize'];?>">
					<label id="lab"><?php echo ucwords($row['fieldcaption']);?><?php if($row['isrequired']=="required") { ?><label id="req">*</label><?php } ?></label>
					<?php
					if($row['fieldtype']=="text")
					{
					?>
					<input type="text" class="form-control" name="<?php echo $row['fieldname'];?>" id="<?php echo $row['fieldid'];?>" value="<?php echo $_POST[$row['fieldname']];?>" placeholder="<?php echo ucwords($row['fieldcaption']);?>" onKeyPress="return OnKeyPress(this, event)" <?php echo $row['isrequired'];?> tabindex="<?php echo $t++;?>" <?php echo $row['autofocus'];?> autocomplete="<?php echo $row['autocomplete'];?>" style="text-transform:uppercase;"/>
					<?php
					}
					else if($row['fieldtype']=="textarea")
					{
					?>
					<textarea class="form-control" name="<?php echo $row['fieldname'];?>" id="<?php echo $row['fieldid'];?>" placeholder="<?php echo ucwords($row['fieldcaption']);?>" onKeyPress="<?php echo $row['onkeypress'];?>" <?php echo $row['isrequired'];?> tabindex="<?php echo $t++;?>" <?php echo $row['autofocus'];?> autocomplete="<?php echo $row['autocomplete'];?>" style="text-transform:uppercase;"><?php echo $_POST['fieldname'];?></textarea>
					<?php
					}
					else if($row['fieldtype']=="select")
					{
					?>
					<select class="form-control" name="<?php echo $row['fieldname'];?>" id="<?php echo $row['fieldid'];?>" placeholder="<?php echo ucwords($row['fieldcaption']);?>" onKeyPress="<?php echo $row['onkeypress'];?>" <?php echo $row['isrequired'];?> tabindex="<?php echo $t++;?>">
						<option value="">--<?php echo ucwords($row['fieldcaption']);?>--</option>
					<?php
						$reftable	=	$row['combotable'];
						$combopk	=	$dbconnection->getField("master_tbl","fieldname","tablename='".$reftable."' and pk='YES'");
						$rs_comb	=	$dbconnection->firequery("select * from $reftable order by ".$row['orderbyforcombotable']."");
						while($combo=mysqli_fetch_assoc($rs_comb))
						{
						?>
							<option value="<?php echo $combo[$combopk];?>" <?php if($combo[$combopk]==$_POST[$row['fieldname']]) { echo "selected"; }?>><?php echo $combo[$row['fieldname']];?></option>
						<?php
						}
					?>
					</select>
					<?php
					}
					?>
				</div>
			<?php
			}
			?>
			<div class="col-sm-2">
				<br id="forbutton" />
				<?php
				if($_POST['acn']=="save")
				{
				?>
				<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Add City Name</button>
				<?php
				}
				else
				{
				?>
				<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Update City Name</button>
				<?php
				}
				?>
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
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="9">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="form-control" onchange="GetLoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="50">50</option>							
							<option value="100">100</option>							
							<option value="300">300</option>
							<option value="500">500</option>							
						</select> records
					</div>
					<div id="tableheadsearch">
						<div class="nav-search" id="nav-search">
							<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="pagesearch" name="pagesearch" autocomplete="off" onkeyup="LoadDefault()" tabindex="<?php echo $t++;?>" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
						</div>
					</div>
					</th>
				</tr>
				<tr>
					<td style="padding:3px; text-align:center; width:25px;">Sno</td>
					<?php
					$rs_caps	=	$dbconnection->firequery("select * from master_tbl where tablename='".$tablename."' and visibleinreport='YES' order by displayorder");
					while($caps=mysqli_fetch_assoc($rs_caps))
					{
					?> 					
					<td style="padding:3px;"><?php echo ucwords($caps['searchcaption']);?></td>
					<?php
					}
					unset($rs_caps);
					unset($caps);					
					?>
					<td style="padding:3px; width:25px;" class="center"><i class="fa fa-edit"></i></td>
					<td style="padding:3px; width:25px;" class="center"><i class="fa fa-remove"></i></td>
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
    });
	
	function DeleteRecord(obj)
	{
		document.getElementById("acn").value="delete";
		document.getElementById("<?php echo $pk;?>").value=obj;
		$("#frm").submit();
	}
	
	function GetLoadDefault()
	{
		document.getElementById("pagenumber").value=1;
		LoadDefault();
	}
	
	function LoadDefault()
	{
		var pagesize	=	document.getElementById("pagesize").value;
		var inputsearch	=	document.getElementById("pagesearch").value;
		var pagenumber	=	document.getElementById("pagenumber").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();		
		$.post("ajaxpages/city.php",
			{
				processname: "city",
				pagesize: pagesize,
				searchvalue:inputsearch,
				pagenumber: pagenumber,
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

<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>

<script src="./js/bootbox.min.js"></script>
<script type="text/javascript">
	function CallBox(obj)
	{
		bootbox.confirm("Do you want to delete this record!", function(result){ if(result){ DeleteRecord(obj);} });	
	}
</script>
<script src="./assets/js/ace-elements.min.js"></script>
<script src="./assets/js/ace.min.js"></script>
<script src="./assets/js/bootstrap.min.js"></script>
