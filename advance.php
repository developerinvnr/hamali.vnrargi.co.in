<?php
$t=0;

$tablename	=	"advance_tbl";
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
			else if($validation['validationtype']=="email")
			{
				if(empty($_POST[$validation['fieldname']]))
				{
					$flag++;
					$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
				}
				else 
				{
					if(!CheckEmail($_POST[$validation['fieldname']]))
					{
						$flag++;
						$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
					}
				}
			}
			else if($validation['validationtype']=="mobile")
			{
				if(empty($_POST[$validation['fieldname']]))
				{
					$flag++;
					$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
				}
				else 
				{
					if(!CheckMobile($_POST[$validation['fieldname']]))
					{
						$flag++;
						$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
					}
				}
			}
		}
		if($_POST['paymentmode']=="")
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Payment mode is required field</li>";		
		}
		if($flag==0)
		{
			$location	=	$dbconnection->getField("hamaligroup_tbl","locationname","hgid=".$_POST['groupname']."");
			$fields		=	$dbconnection->getFieldNames($tablename);
			$values		=	$dbconnection->getFieldValues($tablename,$sessionid);			
			if($dbconnection->firequery("insert into advance_tbl(groupname,advancedate,amount,paymentmode,cdno,bankname,creationdate,remark,addedby,location) values(".$_POST['groupname'].",'".date('Y\-m\-d H:i:s',strtotime($_POST['advancedate']))."',".doubleval($_POST['amount']).",'".$_POST['paymentmode']."','".$_POST['cdno']."','".$_POST['bankname']."','".date('Y\-m\-d H:i:s')."','".$_POST['remark']."',".$_SESSION['datadetail'][0]['sessionid'].",".intval($location).")"))
			{
				$lastid		=	$dbconnection->last_inserted_id();
				$locname	=	strtoupper(substr($dbconnection->getField("location_tbl","locationname","locationid=".$location.""),0,1));
				$ind		=	intval($dbconnection->getField("location_tbl","advanceslip","locationid=".$location.""));
				$fyear		=	$dbconnection->GetFinancialYear();
				$slipnumber	=	$locname."/ADV/".$fyear."/".$ind;
				$dbconnection->firequery("update location_tbl set advanceslip=advanceslip+1 where locationid=".$location."");
				$dbconnection->firequery("update advance_tbl set slipnumber='".$slipnumber."' where advanceid=".$lastid."");
				unset($fields);
				unset($values);				
				unset($flag);				
				unset($msgErr);			
				$_SESSION['success']	=	"Advance payment detail added successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'&lastid='.$lastid.'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again!";
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
			else if($validation['validationtype']=="email")
			{
				if(empty($_POST[$validation['fieldname']]))
				{
					$flag++;
					$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
				}
				else 
				{
					if(!CheckEmail($_POST[$validation['fieldname']]))
					{
						$flag++;
						$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
					}
				}
			}
			else if($validation['validationtype']=="mobile")
			{
				if(empty($_POST[$validation['fieldname']]))
				{
					$flag++;
					$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
				}
				else 
				{
					if(!CheckMobile($_POST[$validation['fieldname']]))
					{
						$flag++;
						$msgErr.=	"<li class='text-warning'>".ucwords($validation['fieldcaption'])."</li>";
					}
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
			if($dbconnection->firequery("update advance_tbl set groupname=".$_POST['groupname'].",advancedate='".date('Y\-m\-d',strtotime($_POST['advancedate']))."',amount=".doubleval($_POST['amount']).",paymentmode='".$_POST['paymentmode']."',cdno='".$_POST['cdno']."',remark='".$_POST['remark']."' where advanceid=".trim(decryptvalue($_POST[$_REQUEST['pk']])).""))
			{
				$_SESSION['success']	=	"Advance payment detail updated successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Found some problem. Please try again!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
		}		
	}
	if($_POST['acn']=="delete")
	{
		if($dbconnection->firequery("delete from advance_tbl where advanceid=".trim(decryptvalue($_POST[$_REQUEST['pk']])).""))
		{ 
			$_SESSION['success']	=	"Advance payment detail deleted successfully!";
			echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
			exit;
		}
		else
		{
			$_SESSION['warning']	=	"Found some problem. Please try again!";
			echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
			exit;
		}
	}

}
else
{
	$_POST['acn']	=	"save";
	if($_REQUEST['pk']!="")
	{
		$rs_update	=	$dbconnection->firequery("select * from advance_tbl where ".$pk."=".trim(decryptvalue($_REQUEST['pk']))."");
		while($rowupdate=mysqli_fetch_assoc($rs_update))
		{
			$_POST['groupname']	=	$rowupdate['groupname'];
			$_POST['advancedate']	=	$rowupdate['advancedate'];			
			$_POST['amount']	=	$rowupdate['amount'];						
			$_POST['paymentmode']	=	$rowupdate['paymentmode'];						
			$_POST['cdno']	=	$rowupdate['cdno'];						
			$_POST['bankname']	=	$rowupdate['bankname'];						
			$_POST['remark']	=	$rowupdate['remark'];						
			
		}
		$_POST['acn']	=	"update";		
	}
}
?>
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
float:right;
}
</style>

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
			<li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-plus-circle bigger-120" style="vertical-align:bottom;"></i> Add Advance Payment Detail</a></li>	
			<li><a data-toggle="tab" href="#feed" onclick="LoadDefault();"><i class="orange ace-icon fa fa-list bigger-120" style="vertical-align:bottom;"></i> Advance Payment List</a></li>
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
					else if($row['fieldtype']=="date")
					{
					?>
					<input type="datetime-local" class="form-control" name="<?php echo $row['fieldname'];?>" id="<?php echo $row['fieldid'];?>" value="<?php if($_POST['advancedate']=="") echo date('Y\-m\-d').'T'.date('H:i'); else echo date('Y\-m\-d',strtotime($_POST['advancedate'])).'T'.date('H:i',strtotime($_POST['advancedate']));?>" placeholder="<?php echo ucwords($row['fieldcaption']);?>" onKeyPress="return OnKeyPress(this, event)" <?php echo $row['isrequired'];?> tabindex="<?php echo $t++;?>" <?php echo $row['autofocus'];?> autocomplete="<?php echo $row['autocomplete'];?>" style="text-transform:uppercase;"/>
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
					<select class="form-control" name="<?php echo $row['fieldname'];?>" id="<?php echo $row['fieldid'];?>" placeholder="<?php echo ucwords($row['fieldcaption']);?>" onKeyPress="return OnKeyPress(this, event)" <?php echo $row['isrequired'];?> tabindex="<?php echo $t++;?>">
						<option value="">--<?php echo ucwords($row['fieldcaption']);?>--</option>
					<?php
						$userslocation	=	$dbconnection->getField("user_tbl","location","userid=".$_SESSION['datadetail'][0]['sessionid']."");					
						$reftable	=	$row['combotable'];
						$combopk	=	$dbconnection->getField("master_tbl","fieldname","tablename='".$reftable."' and pk='YES'");
						$rs_comb	=	$dbconnection->firequery("select * from $reftable where locationname in (".$userslocation.") order by ".$row['orderbyforcombotable']."");
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
				<div class="col-sm-3">
					<label id="lab">Payment Mode<label id="req">*</label></label>				
					<select class="form-control" name="paymentmode" id="paymentmode" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>">
						<option value="">--Payment Mode--</option>
						<option value="CASH" <?php if($_POST['paymentmode']=="CASH") echo "selected";?>>CASH</option>
						<option value="CHEQUE" <?php if($_POST['paymentmode']=="CHEQUE") echo "selected";?>>CHEQUE</option>
						<option value="DD" <?php if($_POST['paymentmode']=="DD") echo "selected";?>>DD</option>						
					</select>					
				</div>
				<div class="col-sm-12">&nbsp;</div>
				<div class="col-sm-3">
					<label id="lab">Cheque/DD Number<label id="req">&nbsp;</label></label>				
					<input type="text" class="form-control" name="cdno" id="cdno" value="<?php echo $_POST['cdno'];?>" placeholder="Cheque/DD Number" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Bank Name<label id="req">&nbsp;</label></label>				
					<input type="text" class="form-control" name="bankname" id="bankname" value="<?php echo $_POST['bankname'];?>" placeholder="Bank Name" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
				<div class="col-sm-3">
					<label id="lab">Remark<label id="req">&nbsp;</label></label>				
					<input type="text" class="form-control" name="remark" id="remark" value="<?php echo $_POST['remark'];?>" placeholder="remark" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" autocomplete="off"/>
				</div>
			
				<div class="col-sm-3">
					<br id="forbutton" />
					<?php
					if($_POST['acn']=="save")
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Add Payment Detail</button>
					<?php
					}
					else
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Update Payment Detail</button>
					<?php
					}
					?>
				</div>
			</div>		
		</form>
				</div>
			</div>
	</div>
			<div id="feed" class="tab-pane">
				<div class="row">
	<form name="pagedata" id="pagedata" action="#" method="post" onsubmit="return false;">
	<input type="hidden" name="pagenumber" id="pagenumber" value="1" />
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="tablerecords">
			<thead>
				<tr>
					<th colspan="11">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="form-control" onchange="GetLoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="200">200</option>							
							<option value="500">500</option>														
						</select> records
						<?php
						$rs_hm	=	$dbconnection->firequery("select * from hamaligroup_tbl  order by groupname");
						?>
						<select name="hgname" id="hgname" class="selectbx" onchange="LoadDefaul()">
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
					<div id="tableheadsearch">
						<div class="nav-search" id="nav-search">
							<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="pagesearch" name="pagesearch" autocomplete="off" onchange="LoadDefault()" tabindex="<?php echo $t++;?>" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
						</div>
					</div>
					</th>
				</tr>
				<tr>
					<th style="padding:3px; text-align:center; width:120px;">S.NO.</th>
					<th style="padding:3px; text-align:center;">ADVANCE SLIP NUMBER</th>
					<th style="padding:3px; text-align:center;">ADVANCE PAYMENT DATE</th>
					<th style="padding:3px;">GROUP NAME</th>					
					<th style="padding:3px;">AMOUNT</th>					
					<th style="padding:3px;">PAYMENT DETAIL</th>					
					<th style="padding:3px;">REMARK</th>										
					<th style="padding:3px;" class="center"><i class="fa fa-edit"></i></th>
					<!--<th style="padding:3px;" class="center"><i class="fa fa-remove"></i></th>-->
					<th style="padding:3px;" class="center"><i class="fa fa-print"></i></th>					
				</tr>
			</thead>
			<tbody class="tabledata">
				<div class="loader"></div>			
			</tbody>
		</table>
	</div>
	</form>
				</div>
			</div><!-- /#feed -->	
		</div>
	</div>
						<div class="hr hr32 hr-dotted"></div>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.page-content -->
	
		
	</div>
</div>

<div id="slipdetail" class="modal fade" role="dialog" style="text-align:center; margin-top:0px;">
	<div class="modal-dialog-md" style="margin-top:10px;">
		<div class="modal-content">
			<div class="modal-body">			

				
			</div>
		</div>
	</div>
</div>

<script src="./assets/js/jquery-2.1.4.min.js"></script>
<script>
    $(document).ready(function() {
        <?php
		if($_REQUEST['lastid']!="")
		{
		?>
		GetVoucher(<?php echo $_REQUEST['lastid'];?>);
		<?php
		}
		?>
    });

	function printDiv()
	{
	  var divToPrint=document.getElementById('printdiv');
	  var newWin=window.open('WORK SLIP DETAIL','Print-Window');
	  newWin.document.open();
	  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
	  newWin.document.close();
	  setTimeout(function(){newWin.close();},10);
	}

	function GetVoucher(advanceid)
	{
		$.post("ajaxpages/showvoucher.php",
		{
			advanceid:advanceid,
		},
		function(data, status){
			$("#slipdetail").modal("show");
			$(".modal-body").html(data);
		});
	}
	function Cls()
	{
		$(".modal-body").html("");
		$("#slipdetail").modal("hide");
	}


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
		var hgname		=	document.getElementById("hgname").value;		
		var pagenumber	=	document.getElementById("pagenumber").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();		
		$.post("ajaxpages/advance.php",
			{
				processname: "advance",
				pagesize: pagesize,
				searchvalue:inputsearch,
				pagenumber: pagenumber,
				hgname:hgname,
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
<script src="./assets/js/bootstrap.min.js"></script>
