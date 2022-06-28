<?php
@session_start();
$t=7;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if($_POST['process']=="clearrecord")
{
	unset($_SESSION['records']);
}
if(isset($_SESSION['supervisordetail'][0]['sessionid']))
{
$i=0;
if(isset($_POST['keyval']))
{
	$iid=intval($_POST['keyval']);
	unset($_SESSION['records'][$iid]);
	$_SESSION['records']=array_values($_SESSION['records']);
}
if($_POST['workcode']!="" && $_POST['rate']!="" && $_POST['quantity']!="" && $_POST['total']!="")
{
	if($_POST['process']=='add')
	{
		$max=count($_SESSION['records']);
		if(is_array($_SESSION['records']))
		{
			$_SESSION['records'][$max]['workcode']		=	$_POST['workcode'];
			$_SESSION['records'][$max]['actionid']		=	$_POST['actionid'];
			$_SESSION['records'][$max]['actionname']	=	$_POST['actionname'];				
			$_SESSION['records'][$max]['materialid']	=	$_POST['materialid'];
			$_SESSION['records'][$max]['materialname']	=	$_POST['materialname'];				
			$_SESSION['records'][$max]['productid']		=	$_POST['productid'];
			$_SESSION['records'][$max]['productname']	=	$_POST['productname'];				
			$_SESSION['records'][$max]['rate']			=	$_POST['rate'];
			$_SESSION['records'][$max]['quantity']		=	$_POST['quantity'];
			$_SESSION['records'][$max]['total']			=	$_POST['total'];						
		}
		else
		{
			$_SESSION['records']=array();
			$_SESSION['records'][0]['workcode']		=	$_POST['workcode'];
			$_SESSION['records'][0]['actionid']		=	$_POST['actionid'];
			$_SESSION['records'][0]['actionname']	=	$_POST['actionname'];				
			$_SESSION['records'][0]['materialid']	=	$_POST['materialid'];
			$_SESSION['records'][0]['materialname']	=	$_POST['materialname'];				
			$_SESSION['records'][0]['productid']	=	$_POST['productid'];
			$_SESSION['records'][0]['productname']	=	$_POST['productname'];				
			$_SESSION['records'][0]['rate']			=	$_POST['rate'];
			$_SESSION['records'][0]['quantity']		=	$_POST['quantity'];
			$_SESSION['records'][0]['total']		=	$_POST['total'];						
		}
	}
	if($_POST['process']=="edit")
	{
		$iid=intval($_POST['keyv']);
		$_SESSION['records'][$iid]['workcode']		=	$_POST['workcode'];
		$_SESSION['records'][$iid]['actionid']		=	$_POST['actionid'];
		$_SESSION['records'][$iid]['actionname']	=	$_POST['actionname'];				
		$_SESSION['records'][$iid]['materialid']	=	$_POST['materialid'];
		$_SESSION['records'][$iid]['materialname']	=	$_POST['materialname'];				
		$_SESSION['records'][$iid]['productid']		=	$_POST['productid'];
		$_SESSION['records'][$iid]['productname']	=	$_POST['productname'];				
		$_SESSION['records'][$iid]['rate']			=	$_POST['rate'];
		$_SESSION['records'][$iid]['quantity']		=	$_POST['quantity'];
		$_SESSION['records'][$iid]['total']			=	$_POST['total'];						
	}
}
else
{
	if($_POST['process']=="add")
	{
		echo "addfail";
		die();
	}
	if($_POST['process']=="edit")
	{
		echo "editfail";
		die();
	}
}

if(is_array($_SESSION['records']))
{
	$j=0;
	$totalvalue=0;
	foreach($_SESSION['records'] as $key=>$val)
	{
	$j++;
	?>
<tr>
	<td style="padding:0px;">
		<input type="text" name="workcode[]" class="pname form-control" value="<?php echo $_SESSION['records'][$key]['workcode'];?>" id="workcode<?php echo $j;?>"placeholder="Enter work code (Autocomplete)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" title="<?php echo $j;?>" readonly/>
	</td>
	<td style="padding:0px;">
		<input type="text" name="actionname[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['actionname'];?>" id="actionname<?php echo $j;?>" placeholder="Enter action name" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" title="<?php echo $j;?>" readonly/>
		<input type="hidden" name="actionid[]" value="<?php echo $_SESSION['records'][$key]['actionid'];?>" id="actionid<?php echo $j;?>" title="<?php echo $j;?>"/>		
	</td>
	<td style="padding:0px;">
		<input type="text" name="materialname[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['materialname'];?>" id="materialname<?php echo $j;?>" placeholder="Enter material name" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" title="<?php echo $j;?>" readonly/>
		<input type="hidden" name="materialid[]" value="<?php echo $_SESSION['records'][$key]['materialid'];?>" id="materialid<?php echo $j;?>" title="<?php echo $j;?>"/>		
	</td>
	<td style="padding:0px;">
		<input type="text" name="productname[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['productname'];?>" id="productname<?php echo $j;?>" placeholder="Enter product name" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" title="<?php echo $j;?>" readonly/>
		<input type="hidden" name="productid[]" value="<?php echo $_SESSION['records'][$key]['productid'];?>" id="productid<?php echo $j;?>" title="<?php echo $j;?>"/>		
	</td>
	<td style="padding:0px; width:70px;">
		<input type="text" name="quantity[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['quantity'];?>" id="quantity<?php echo $j;?>" placeholder="Quantity" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" onchange="CalTotal(<?php echo $j;?>)" />
	</td>
	<td style="padding:0px; width:70px;">
		<input type="text" name="rate[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['rate'];?>" id="rate<?php echo $j;?>" placeholder="Rate" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" onchange="CalTotal(<?php echo $j;?>)" />
	</td>
	<td style="padding:0px; width:70px;">
		<input type="text" name="total[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['total'];?>" id="total<?php echo $j;?>" placeholder="Total" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly onfocus="CallBoxEdit('<?php echo $key;?>')" />
	</td>
	<td style="width:25px;" class="center"><i class="fa fa-edit" onClick="CallBoxEdit('<?php echo $key;?>')"></i></td>	
	<td style="width:25px;" class="center"><i class="fa fa-remove" onClick="CallBox('<?php echo $key;?>')"></i></td>	
</tr>

	<?php
	$totalvalue	=	$totalvalue+$_SESSION['records'][$key]['total'];
	}
}
if($_POST['total']=="" || $_POST['total']==0)
{
	$_POST['total']	=	$totalvalue;
}
?>
<tr>
	<td style="padding:0px; width:100px;">
		<input type="text" name="workcode[]" class="form-control" id="workcode<?php echo $i;?>" placeholder="Work code" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" autocomplete="off" onchange="GetWorkCode(this.value)"/>
	</td>
	<td style="padding:0px;">
		<input type="text" name="actionname[]" class="form-control" id="actionname<?php echo $i;?>" placeholder="Action name" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly/>
		<input type="hidden" name="actionid[]" id="actionid<?php echo $i;?>"/>
	</td>
	<td style="padding:0px;">
		<input type="text" name="materialname[]" class="form-control" id="materialname<?php echo $i;?>" placeholder="Material name" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly/>
		<input type="hidden" name="materialid[]" id="materialid<?php echo $i;?>"/>
	</td>
	<td style="padding:0px;">
		<input type="text" name="productname[]" class="form-control" id="productname<?php echo $i;?>" placeholder="Product name" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly/>
		<input type="hidden" name="productid[]" id="productid<?php echo $i;?>"/>
	</td>

	<td style="padding:0px; width:70px;">
		<input type="text" name="quantity[]" class="form-control" id="quantity<?php echo $i;?>" placeholder="Quantity" autocomplete="off" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" onchange="CalTotal(<?php echo $i;?>)"  />
	</td>
	<td style="padding:0px; width:70px;">
		<input type="text" name="rate[]" class="form-control" id="rate<?php echo $i;?>" placeholder="Rate" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" onchange="CalTotal(<?php echo $i;?>)" readonly="" />
	</td>
	<td style="padding:0px; width:80px;">
		<input type="text" name="total[]" class="form-control" id="total<?php echo $i;?>" placeholder="Total" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly onfocus="AddButton()" />
	</td>
	<td style="width:0px; padding:0px;" colspan="2"><button type="button" class="btn" style="padding-left:0px; padding-top:0px; padding-right:0px; padding-bottom:0px; border:none; color:#FFFFFF; font-size:12px; font-weight:bold; width:100%; height:25px;" onclick="AddButton()" tabindex="<?php echo $t++;?>">Add</button></td>
</tr>
<?php
if($j>0)
{
?>
<tr>
	<td style="padding:0px; font-size:12px; vertical-align:middle;" colspan="6" align="right"><b>Total Value <i class="fa fa-inr"></i>&nbsp;&nbsp;</b></td>
	<td><b><input type="text" name="totalvalue" id="totalvalue" value="<?php echo ceil($totalvalue);?>" readonly tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="height:25px; width:100px;" /></b></td><td colspan="2"></td>
</tr>
<tr><td colspan="9">&nbsp;</td></tr>
<tr>
	<td style="padding:0px; font-size:14px;" colspan="9">
<button type="button" class="btn" style="padding-left:0px; padding-top:0px; padding-right:0px; padding-bottom:0px; border:none; color:#FFFFFF; font-size:12px; font-weight:bold; width:150px; height:25px; float:right;" onclick="SubmitForm()" tabindex="<?php echo $t++;?>">SUBMIT</button>	
	</td>
</tr>

<?php
}
}
?>
<script>
function GetWorkCode(workcode)
{
	var gpno	=	document.getElementById("groupnumber").value;
	if(gpno!="")
	{
		$.post("ajaxpages/workcode.php",
		{
			workcode:workcode,
			gpno:gpno
		},
		function(data, status){
			var str	=	data.split("|");
			if(str[0].trim()=="error")
			{
				$("#warningputmsg").html("");
				$("#warningputmsg").append(str[1]);				
				$("#warningmed").show();									
				$(".loader").hide();					
				$("#workcode0").val("");
				$("#workcode0").focus();
			}
			else
			{
				$("#actionid0").val(str[1]);
				$("#actionname0").val(str[2]);
				$("#materialid0").val(str[3]);
				$("#materialname0").val(str[4]);
				$("#productid0").val(str[5]);
				$("#productname0").val(str[6]);
				$("#rate0").val(str[7]);
			}
		});
	}
	else
	{
		bootbox.alert("Please enter hamali group number!");
		$("#workcode0").focus();
		return;
	}
}
</script>