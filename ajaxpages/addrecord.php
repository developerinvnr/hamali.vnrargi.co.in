<?php
@session_start();
$t=5;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if($_POST['process']=="clearrecord")
{
	unset($_SESSION['records']);
}
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
$i=0;
if(isset($_POST['keyval']))
{
	$iid=intval($_POST['keyval']);
	unset($_SESSION['records'][$iid]);
	$_SESSION['records']=array_values($_SESSION['records']);	
}
if($_POST['process']=="edit")
{
	$iid=intval($_POST['vl']);
	$_SESSION['records'][$iid]['quantity']=$_POST['quantity'];
}

if($_POST['reagentname']!="" && $_POST['quantity']!="" && $_POST['reagentid']!="")
{
	if($_POST['process']=='add')
	{
		if(is_array($_SESSION['records']))
		{
			$max=count($_SESSION['records']);
			$_SESSION['records'][$max]['reagentid']		=	$_POST['reagentid'];
			$_SESSION['records'][$max]['reagentname']	=	$_POST['reagentname'];
			$_SESSION['records'][$max]['quantity']		=	$_POST['quantity'];
		}
		else{
			$_SESSION['records']=array();
			$_SESSION['records'][0]['reagentid']	=	$_POST['reagentid'];
			$_SESSION['records'][0]['reagentname']	=	$_POST['reagentname'];
			$_SESSION['records'][0]['quantity']		=	$_POST['quantity'];
		}
	}
}
else
{
	if($_POST['process']=="add")
	{
		echo "addfail";
		die();
	}
}
?>
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
<tr style="background-color:#448DB8; color:#FFFFFF;">
	<td><b>Reagent Name</b></td>
	<td><b>Quantity</b></td>
	<td></td>
	<td></td>	
</tr>
<?php
$j=0;
if(is_array($_SESSION['records']))
{
	$totalvalue=0;
	$total	=	0;
	foreach($_SESSION['records'] as $key=>$val)
	{
	$j++;
	?>
<tr>
	<td style="padding:0px;">
		<input type="text" name="reagentname[]" class="reagentname form-control" value="<?php echo $_SESSION['records'][$key]['reagentname'];?>" id="reagentname<?php echo $j;?>" placeholder="Enter reagent name (Autocomplete)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; border-radius:0px;" title="<?php echo $j;?>" readonly/>
		<input type="hidden" name="reagentid[]" id="reagentid<?php echo $j;?>" value="<?php echo $_SESSION['records'][$key]['reagentid'];?>" />
	</td>
	<td style="padding:0px; width:75px;">
		<input type="text" name="quantity[]" class="form-control" value="<?php echo $_SESSION['records'][$key]['quantity'];?>" id="quantity<?php echo $j;?>" placeholder="Quantity" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; width:100%;" onchange="EditRecord(<?php echo $key;?>,<?php echo $j;?>)" />	
	</td>
	<td style="width:25px; padding:0px;" align="center"><i class="fa fa-edit" onClick="EditRecord(<?php echo $key;?>,<?php echo $j;?>)" title="CHANGE QUANTITY AND CLICK ON THIS LINK"></i></td>
	<td style="width:25px; padding:0px;" align="center"><i class="fa fa-remove" onClick="DeleteRecord('<?php echo $key;?>')"></i></td>	
</tr>
	<?php
	}
}
?>
<tr>
	<td style="padding:0px;">
		<input type="text" name="reagentname[]" class="form-control" id="reagentname<?php echo $i;?>" placeholder="Enter item name (Autocomplete)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; border-radius:0px; width:100%;" autofocus/>
		<input type="hidden" name="reagentid[]" id="reagentid<?php echo $i;?>" value="" />
	</td>
	<td style="padding:0px; width:75px;">
		<input type="text" name="quantity[]" class="form-control" id="quantity<?php echo $i;?>" placeholder="Quantity" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" style="padding-left:2px; padding-top:0px; padding-bottom:0px; padding-right:0px; height:25px; font-size:12px; border-radius:0px; width:75px;" autocomplete="off"/>	
	</td>
	<td style="padding:0px;" colspan="2"><button style="padding-left:0px; padding-top:0px; padding-right:0px; padding-bottom:0px; background-color:#9191FF; border:none; color:#FFFFFF; font-size:12px; border-radius:0px; font-weight:bold; width:100%; height:25px; background-color:#448DB8;" onclick="AddButton()" onfocus="AddButton()" tabindex="<?php echo $t++;?>">Add</button></td>
</tr>
<tr><td colspan="4" style="padding:0px;"><label style="background-color:#448DB8; color:#FFFFFF; text-transform:uppercase; width:100%; text-align:center; display:none;" id="msg"></label></td></tr>
<?php
}
if($j>0)
{
?>
</table>
<br /><br />
<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse;" border="1">
	<tr>
		<td width="150px">&nbsp;Stock Entry Date :</td>
		<td width="100px;" style="padding:0px;"><input type="date" class="form-control" name="entrydate" id="entrydate" value="<?php echo date('Y\-m\-d');?>" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td>
		<td style="padding:0px;"><input type="text" class="form-control" name="remark" id="remark" placeholder="Enter remark if any" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td>		
		<td style="padding:0px; width:130px; text-align:center;">&nbsp;&nbsp;<button type="submit" style="background-color:#448DB8; border:0px; color:#FFFFFF; padding:5px 22px;" tabindex="<?php echo $t++;?>">Submit Data</button></td>
	</tr>
</table>
<?php
}
?>
<script type="text/javascript" src="./js/jquery.min183.js"></script>
<script type="text/javascript" src="./js/jquery-ui.js"></script>
<script type="text/javascript" src='./js/jquery.autocomplete.js'></script>

<script type='text/javascript'>  
 var $jq = jQuery.noConflict();  
</script> 

<script type="text/javascript">
$jq().ready(function() {

	$jq("#reagentname0").autocomplete("autocomplete/reagentName.php", {
		width: 800,
		matchContains: true,
		mustMatch: true,
		selectFirst: true
	});
	$jq("#reagentname0").result(function(event,data,formatted) {
		$("#reagentname0").val(data[1]);
		$("#reagentid0").val(data[2]);		
		
	});
});
</script>
