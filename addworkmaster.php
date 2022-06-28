<?php
$t=0;
$tablename	=	"workcode_master";
$pk			=	$dbconnection->getField("master_tbl","fieldname","tablename='".$tablename."' and pk='YES'");
$advanced	=	$dbconnection->getField("user_tbl","advanceworkcode","userid=".$_SESSION['datadetail'][0]['sessionid']."");
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['acn']=="save")
	{
	if($advanced==1)
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
		$department	=	"";
		$i=0;
		foreach($_POST['departmentname'] as $key=>$val)
		{
			$i++;
			if($i==1)
			{
				$department.=	$_POST['departmentname'][$key];
			}
			else
			{
				$department.=",".$_POST['departmentname'][$key];
			}				
		}
		if($department=="")
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Department name is mandatory field</li>";
		}		
		
		if($flag==0)
		{
			$fields	=	$dbconnection->getFieldNames($tablename);
			$values	=	$dbconnection->getFieldValues($tablename,$sessionid);			
			if($dbconnection->firequery("insert into workcode_master(workcode,actionvalue,verb,material,product,operator,quantity,notation,unit,rate,defaultnarration) values(".$_POST['workcode'].",'".$_POST['actionvalue']."','".$_POST['verb']."','".$_POST['material']."','".$_POST['product']."','".$_POST['operator']."',".doubleval($_POST['quantity']).",'".$_POST['notation']."','".$_POST['unit']."',".doubleval($_POST['rate']).",'".$_POST['defaultnarration']."')"))
			{
				$lid	=	$dbconnection->last_inserted_id();			
				$rs_rt	=	$dbconnection->firequery("select * from rate_tbl order by rateid");
				while($rt=mysqli_fetch_assoc($rs_rt))
				{
					$dbconnection->firequery("insert into rate_list(rateid,workcode,price,creationdate,addedby) values(".intval($rt['rateid']).",".intval($_POST['workcode']).",".doubleval($_POST['rate']).",'".date('Y\-m\-d H:i:s')."',".$_SESSION['datadetail'][0]['sessionid'].")");
				}
				unset($fields);
				unset($values);				
				unset($flag);				
				unset($msgErr);
				$dbconnection->firequery("update workcode_master set departmentname='".$department."' where recordid=".$lid."");				
				$_SESSION['success']	=	"Work code master added successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Action, material and product/method combination is already available. Please try again!";
			}
		}
	}
	else
	{
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['defaultnarration']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter default narration</li>";
		}
		
		$department	=	"";
		$i=0;
		foreach($_POST['departmentname'] as $key=>$val)
		{
			$i++;
			if($i==1)
			{
				$department.=	$_POST['departmentname'][$key];
			}
			else
			{
				$department.=",".$_POST['departmentname'][$key];
			}				
		}
		if($department=="")
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Department name is mandatory field</li>";
		}		
		
		if($flag==0)
		{
			if($dbconnection->firequery("insert into workcode_master(workcode,defaultnarration) values(".$_POST['workcode'].",'".$_POST['defaultnarration']."')"))
			{
				$lid	=	$dbconnection->last_inserted_id();			
				$rs_rt	=	$dbconnection->firequery("select * from rate_tbl order by rateid");
				while($rt=mysqli_fetch_assoc($rs_rt))
				{
					$dbconnection->firequery("insert into rate_list(rateid,workcode,price,creationdate,addedby) values(".intval($rt['rateid']).",".intval($_POST['workcode']).",0,'".date('Y\-m\-d H:i:s')."',".$_SESSION['datadetail'][0]['sessionid'].")");
				}
				unset($fields);
				unset($values);				
				unset($flag);				
				unset($msgErr);
				$dbconnection->firequery("update workcode_master set departmentname='".$department."' where recordid=".$lid."");				
				$_SESSION['success']	=	"Work code master added successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Action, material and product/method combination is already available. Please try again!";
			}
		}
	}		
	}
	if($_POST['acn']=="update")
	{
	if($advanced==1)
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
		$department	=	"";
		$i=0;
		foreach($_POST['departmentname'] as $key=>$val)
		{
			$i++;
			if($i==1)
			{
				$department.=	$_POST['departmentname'][$key];
			}
			else
			{
				$department.=",".$_POST['departmentname'][$key];
			}				
		}
		if($department=="")
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Department name is mandatory field</li>";
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
						$updatefields.=	"".$row_fields['fieldname']."='".$_POST[$row_fields['fieldname']]."'";
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
						$updatefields.=	","."".$row_fields['fieldname']."='".$_POST[$row_fields['fieldname']]."'";
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
			if($dbconnection->firequery("update $tablename set $updatefields,defaultnarration='".$_POST['defaultnarration']."' where ".$_REQUEST['pk']."=".trim(decryptvalue($_POST[$_REQUEST['pk']])).""))
			{
				unset($updatefields);
				unset($rs_fields);
				unset($row_fields);				
				unset($up);
				$dbconnection->firequery("update workcode_master set departmentname='".$department."' where ".$_REQUEST['pk']."=".trim(decryptvalue($_POST[$_REQUEST['pk']]))."");
				$_SESSION['success']	=	"Work code master updated successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Action, material and product/method combination is already available. Please try again!";
			}
		}		
	}
	else
	{	
		$flag	=	0;
		$msgErr	=	"";
		if(empty($_POST['defaultnarration']))
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Enter default narration</li>";
		}
		$department	=	"";
		$i=0;
		foreach($_POST['departmentname'] as $key=>$val)
		{
			$i++;
			if($i==1)
			{
				$department.=	$_POST['departmentname'][$key];
			}
			else
			{
				$department.=",".$_POST['departmentname'][$key];
			}				
		}
		if($department=="")
		{
			$flag++;
			$msgErr.=	"<li class='text-warning'>Department name is mandatory field</li>";
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
						$updatefields.=	"".$row_fields['fieldname']."='".$_POST[$row_fields['fieldname']]."'";
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
						$updatefields.=	","."".$row_fields['fieldname']."='".$_POST[$row_fields['fieldname']]."'";
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
			if($dbconnection->firequery("update $tablename set defaultnarration='".$_POST['defaultnarration']."' where ".$_REQUEST['pk']."=".trim(decryptvalue($_POST[$_REQUEST['pk']])).""))
			{
				unset($updatefields);
				unset($rs_fields);
				unset($row_fields);				
				unset($up);
				$dbconnection->firequery("update workcode_master set departmentname='".$department."' where ".$_REQUEST['pk']."=".trim(decryptvalue($_POST[$_REQUEST['pk']]))."");
				$_SESSION['success']	=	"Work code master updated successfully!";
				echo '<script>document.location.href="./vnr_mainindex?m='.$_REQUEST['m'].'&p='.$_REQUEST['p'].'";</script>';
				exit;
			}
			else
			{
				$_SESSION['warning']	=	"Action, material and product/method combination is already available. Please try again!";
			}
		}		
	}
	}
	if($_POST['acn']=="delete")
	{
		if($dbconnection->firequery("delete from $tablename where ".$_REQUEST['pk']."=".trim(decryptvalue($_POST[$_REQUEST['pk']])).""))
		{ 
			$_SESSION['success']	=	"Work master record deleted successfully!";
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
		$rs_update	=	$dbconnection->firequery("select * from $tablename where ".$pk."=".trim(decryptvalue($_REQUEST['pk']))."");
		while($rowupdate=mysqli_fetch_assoc($rs_update))
		{
			$rs_mast	=	$dbconnection->firequery("select * from master_tbl where tablename='".$tablename."' and pk!='YES' and visibleinform='YES' order by displayorder");
			while($rowmast=mysqli_fetch_assoc($rs_mast))
			{
				$_POST[$rowmast['fieldname']]	=	$rowupdate[$rowmast['fieldname']];
			}
		}
		$_POST['defaultnarration']	=	$dbconnection->getField("workcode_master","defaultnarration","recordid=".trim(decryptvalue($_REQUEST['pk']))."");
		unset($rs_update);
		unset($rowupdate);
		unset($rs_mast);
		unset($rowmast);
		$_POST['acn']	=	"update";		
	}
}
if($_POST['acn']=="save")
{
	$rs_wc	=	$dbconnection->firequery("select * from workcode_master order by workcode desc limit 1");
	while($wc=mysqli_fetch_assoc($rs_wc))
	{
		$_POST['workcode']	=	$wc['workcode']+1;
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
			<?php
			if($advanced==1)
			{
			?>
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
					<input type="text" class="form-control" name="<?php echo $row['fieldname'];?>" id="<?php echo $row['fieldid'];?>" value="<?php echo $_POST[$row['fieldname']];?>" placeholder="<?php echo ucwords($row['fieldcaption']);?>" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" <?php echo $row['autofocus'];?> autocomplete="<?php echo $row['autocomplete'];?>" style="text-transform:uppercase;"/>
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
					<select class="form-control multiselect" name="<?php echo $row['fieldname'];?>[]" id="<?php echo $row['fieldid'];?>" multiple="multiple" placeholder="<?php echo ucwords($row['fieldcaption']);?>" onKeyPress="<?php echo $row['onkeypress'];?>" tabindex="<?php echo $t++;?>" <?php echo $row['onchange'];?>>
					<?php
						if($_POST['departmentname']!="")
						{
							$exp	=	explode(",",$_POST['departmentname']);
						}					
						$reftable	=	$row['combotable'];
						$combopk	=	$dbconnection->getField("master_tbl","fieldname","tablename='".$reftable."' and pk='YES'");
						$rs_comb	=	$dbconnection->firequery("select * from $reftable order by ".$row['orderbyforcombotable']."");
						while($combo=mysqli_fetch_assoc($rs_comb))
						{
						?>
							<option value="<?php echo $combo[$combopk];?>" <?php if(in_array($combo[$combopk],$exp)) { echo "selected"; }?>><?php echo $combo[$row['fieldname']];?></option>
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
				<div class="col-sm-12">&nbsp;</div>
				<div class="col-sm-5">
					<label id="lab">Default Narration<label id="req">*</label></label>
					<input type="text" class="form-control" name="defaultnarration" id="defaultnarration" value="<?php echo $_POST['defaultnarration'];?>" placeholder="Default narration" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-3">
					<br id="forbutton" />
					<?php
					if($_POST['acn']=="save")
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Add Work Code</button>
					<?php
					}
					else
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Update Work Code</button>
					<?php
					}
					?>
				</div>
			</div>		
			<?php
			}
			else
			{
			?>
			<div class="form-group">
				<div class="col-sm-1">
					<label id="lab">Work Code<label id="req">*</label></label>
					<input type="text" class="form-control" name="workcode" id="workcode" value="<?php echo $_POST['workcode'];?>" placeholder="Workcode" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" readonly autocomplete="off" style="text-transform:uppercase;"/>
				</div>
				<div class="col-sm-5">
					<label id="lab">Default Narration<label id="req">*</label></label>
					<input type="text" class="form-control" name="defaultnarration" id="defaultnarration" value="<?php echo $_POST['defaultnarration'];?>" placeholder="Default narration" onKeyPress="return OnKeyPress(this, event)" required tabindex="<?php echo $t++;?>" autocomplete="off" style="text-transform:uppercase;"/>
				</div>
			
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
				<?php
				}
				?>
				<?php
				if($row['fieldtype']=="select")
				{
				?>
				<div class="col-sm-<?php echo $row['columnsize'];?>">
					<label id="lab"><?php echo ucwords($row['fieldcaption']);?><?php if($row['isrequired']=="required") { ?><label id="req">*</label><?php } ?></label>
					<select class="form-control multiselect" name="<?php echo $row['fieldname'];?>[]" id="<?php echo $row['fieldid'];?>" multiple="multiple" placeholder="<?php echo ucwords($row['fieldcaption']);?>" onKeyPress="<?php echo $row['onkeypress'];?>" tabindex="<?php echo $t++;?>" <?php echo $row['onchange'];?>>
					<?php
						if($_POST['departmentname']!="")
						{
							$exp	=	explode(",",$_POST['departmentname']);
						}					
						$reftable	=	$row['combotable'];
						$combopk	=	$dbconnection->getField("master_tbl","fieldname","tablename='".$reftable."' and pk='YES'");
						$rs_comb	=	$dbconnection->firequery("select * from $reftable order by ".$row['orderbyforcombotable']."");
						while($combo=mysqli_fetch_assoc($rs_comb))
						{
						?>
							<option value="<?php echo $combo[$combopk];?>" <?php if(in_array($combo[$combopk],$exp)) { echo "selected"; }?>><?php echo $combo[$row['fieldname']];?></option>
						<?php
						}
					?>
					</select>
				</div>
					<?php
					}
					?>
			<?php
			}
			?>
				<div class="col-sm-3">
					<br id="forbutton" />
					<?php
					if($_POST['acn']=="save")
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Add Work Code</button>
					<?php
					}
					else
					{
					?>
					<button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>">Update Work Code</button>
					<?php
					}
					?>
				</div>
			</div>		
			<?php
			}
			?>
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
					<th colspan="13">
					<div id="tablehead">
						Display &nbsp;<select name="pagesize" id="pagesize" class="form-control" onchange="GetLoadDefault()" tabindex="<?php echo $t++;?>">
							<option value="200">200</option>							
							<option value="300">300</option>
							<option value="500">500</option>							
						</select> records
					</div>
					<div id="tableheadsearch">
						<div class="nav-search" id="nav-search">
							<span class="input-icon"><input type="text" placeholder="Search ..." class="nav-search-input" id="pagesearch" name="pagesearch" autocomplete="off" onchange="LoadDefault()" tabindex="<?php echo $t++;?>" /><i class="ace-icon fa fa-search nav-search-icon"></i></span>
						</div>
					</div>
					</th>
				</tr>
				<tr>
					<th style="padding:3px; text-align:center;">Work Code</th>
					<?php
					if($advanced==1)
					{
					?>
					<th style="padding:3px;">Action</th>
					<th style="padding:3px;">Verb</th>
					<th style="padding:3px;">Material</th>
					<th style="padding:3px;">Product</th>
					<th style="padding:3px;">Operator</th>
					<th style="padding:3px;">Quantity</th>
					<th style="padding:3px;">Notation</th>
					<th style="padding:3px;">Unit</th>
					<th style="padding:3px;">Rate</th>
					<?php
					}
					?>
					<th style="padding:3px;">Default Narration</th>					
					<th style="padding:3px;">Department</th>					
					<th style="padding:3px;" class="center"><i class="fa fa-edit"></i></th>
					<th style="padding:3px;" class="center"><i class="fa fa-remove"></i></th>
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
<script src="./assets/js/bootstrap-multiselect.min.js"></script>
<script>
    $(document).ready(function() {
        LoadDefault();
    });

	$('.multiselect').multiselect({
	 enableFiltering: true,
	 enableHTML: true,
	 enableCaseInsensitiveFiltering:1,
	 maxHeight:400,
	 buttonClass: 'btn btn-white btn-primary',
	 templates: {
		button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-selected-text"></span> &nbsp;<b class="fa fa-caret-down"></b></button>',
		ul: '<ul class="multiselect-container dropdown-menu"></ul>',
		filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
		filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
		li: '<li><a tabindex="0"><label></label></a></li>',
		divider: '<li class="multiselect-item divider"></li>',
		liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>'
	 }
	});

	
	function UpdateCodeDepartment(recordid,depid)
	{
		var sts	=	$("#dep"+recordid+""+depid).is(":checked");
		if(sts)
		{
			sts	=	'T';
		}
		else
		{
			sts	=	'F';
		}
		$(".loader").show();		
		$.post("ajaxpages/updatecodedepartment.php",
		{
			recordid:recordid,
			depid:depid,
			sts:sts
		},
		function(data, status){
			$(".loader").hide();
			$("#msg"+recordid).css("display","");
			setTimeout(function() { $("#msg"+recordid).css("display","none"); },3000);
		});


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
		var pagenumber	=	document.getElementById("pagenumber").value;
		var m			=	document.getElementById("m").value;
		var p			=	document.getElementById("p").value;				
		var pk			=	document.getElementById("pk").value;						
		$(".loader").show();		
		$.post("ajaxpages/workcodemaster.php",
			{
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
<script src="./assets/js/bootstrap.min.js"></script>
