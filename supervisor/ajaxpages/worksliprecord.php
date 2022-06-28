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

if($_POST['workcode']!="" && $_POST['quantity']!="" && $_POST['quantity']!="0")
{
	if($_POST['process']=='add')
	{
		$max=count($_SESSION['records']);
		if(is_array($_SESSION['records']))
		{
			$_SESSION['records'][$max]['workcode']		=	$_POST['workcode'];
			$_SESSION['records'][$max]['narration']		=	$_POST['remark'];
			$_SESSION['records'][$max]['quantity']		=	$_POST['quantity'];
			$_SESSION['records'][$max]['rate']			=	$_POST['rate'];
			$_SESSION['records'][$max]['total']			=	$_POST['rate']*$_POST['quantity'];						
			$_SESSION['records'][$max]['rem1']			=	$_POST['rem1'];			
			$_SESSION['records'][$max]['rem2']			=	$_POST['rem2'];						
		}
		else
		{
			$_SESSION['records']=array();
			$_SESSION['records'][0]['workcode']		=	$_POST['workcode'];
			$_SESSION['records'][0]['narration']	=	$_POST['remark'];
			$_SESSION['records'][0]['quantity']		=	$_POST['quantity'];
			$_SESSION['records'][0]['rate']			=	$_POST['rate'];
			$_SESSION['records'][0]['total']		=	$_POST['rate']*$_POST['quantity'];
			$_SESSION['records'][0]['rem1']			=	$_POST['rem1'];
			$_SESSION['records'][0]['rem2']			=	$_POST['rem2'];
		}
	}
	if($_POST['process']=="edit")
	{
		$iid=intval($_POST['keyv']);
		$_SESSION['records'][$iid]['workcode']		=	$_POST['workcode'];
		$_SESSION['records'][$iid]['narration']		=	$_POST['remark'];
		$_SESSION['records'][$iid]['rate']			=	$_POST['rate'];
		$_SESSION['records'][$iid]['quantity']		=	$_POST['quantity'];
		$_SESSION['records'][$iid]['total']			=	$_POST['rate']*$_POST['quantity'];
		$_SESSION['records'][$iid]['rem1']			=	$_POST['rem1'];
		$_SESSION['records'][$iid]['rem2']			=	$_POST['rem2'];		
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
?>
<tr>
	<td style="padding:0px; width:100px;">
		<input type="text" name="workcode[]" class="form-control" id="workcode<?php echo $i;?>" placeholder="Work code" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" autocomplete="off" onchange="GetWorkCode(this.value)"/>
	</td>
	<td style="padding:0px; width:70px;">
		<input type="text" name="quantity[]" class="form-control" id="quantity<?php echo $i;?>" placeholder="Quantity" autocomplete="off" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" onchange="CalTotal(<?php echo $i;?>)"  />

		<input type="hidden" name="remark[]" class="form-control" id="remark<?php echo $i;?>" placeholder="Remark" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly="" />

		
		<input type="hidden" name="rate[]" class="form-control" id="rate<?php echo $i;?>" placeholder="Rate" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" onchange="CalTotal(<?php echo $i;?>)" readonly="" />
		
		<input type="hidden" name="total[]" class="form-control" id="total<?php echo $i;?>" placeholder="Total" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly onfocus="AddButton()" />

	</td>
	<td style="padding:0px; width:100px;">
		<input type="text" name="rem1[]" class="form-control" id="rem1<?php echo $i;?>" placeholder="Remark 1" autocomplete="off" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" />
	</td>
	<td style="padding:0px; width:100px;">
		<input type="text" name="rem2[]" class="form-control" id="rem2<?php echo $i;?>" placeholder="Remark 2" autocomplete="off" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;"/>
	</td>
	<td style="width:0px; padding:0px;"><button type="button" class="btn" style="padding-left:0px; padding-top:0px; padding-right:0px; padding-bottom:0px; border:none; color:#FFFFFF; font-size:12px; font-weight:bold; width:100px; height:25px;" onclick="AddButton()" tabindex="<?php echo $t++;?>">Add</button></td>
</tr>

<?php
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
	<td style="padding:0px; width:70px;">
		<input type="text" name="quantity[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['quantity'];?>" id="quantity<?php echo $j;?>" placeholder="Quantity" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" onchange="CalTotal(<?php echo $j;?>)" readonly />

		<input type="hidden" name="rate[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['rate'];?>" id="rate<?php echo $j;?>" placeholder="Rate" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;"/>

		<input type="hidden" name="total[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['total'];?>" id="total<?php echo $j;?>" placeholder="Total" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly onfocus="CallBoxEdit('<?php echo $key;?>')" />

	</td>

	<td style="padding:0px; width:70px;">
		<input type="text" name="rem1[]" readonly class="form-control" id="rem1<?php echo $i;?>" value="<?php echo $_SESSION['records'][$key]['rem1'];?>" placeholder="Remark 1" autocomplete="off" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" />
	</td>
	<td style="padding:0px; width:70px;">
		<input type="text" name="rem2[]" readonly class="form-control" id="rem2<?php echo $i;?>" value="<?php echo $_SESSION['records'][$key]['rem2'];?>" placeholder="Remark 2" autocomplete="off" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;"/>
	</td>

	
	<td style="padding:0px;">
	<?php echo $_SESSION['records'][$key]['narration'];?>
		<input type="hidden" name="remark[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['narration'];?>" id="remark<?php echo $j;?>" placeholder="Remark" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px;" readonly />
	
	</td>
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
<?php
if($j>0)
{
?>
<tr>
	<td style="padding:0px; font-size:14px;" colspan="5">
<input type="hidden" name="totalvalue" id="totalvalue" value="<?php echo ceil($totalvalue);?>" readonly tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="height:25px; width:100px;" />	
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
			else if(str[0].trim()=="codeerror")
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
				$("#warningputmsg").html("");
				$("#warningmed").hide();
				$("#workcode0").val(str[0]);
				$("#remark0").val(str[3]);
				$("#rate0").val(str[2]);
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