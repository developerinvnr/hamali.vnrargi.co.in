<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
date_default_timezone_set('Asia/Calcutta');
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$t=10;

if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	if(!$dbconnection->isRecordExist("select * from rate_tbl where ratelistname='".$_POST['rate']."'") && $_POST['rate']!="")
	{
		$dbconnection->firequery("insert into rate_tbl(ratelistname,creationdate,addedby) values('".strtoupper($_POST['rate'])."','".date('Y\-m\-d H:i:s')."',".$_SESSION['datadetail'][0]['sessionid'].")");
		$lid	=	$dbconnection->last_inserted_id();
		$rs_sel	=	$dbconnection->firequery("select * from workcode_master");
		while($row=mysqli_fetch_assoc($rs_sel))
		{
			$dbconnection->firequery("insert into rate_list(rateid,workcode,price,creationdate,addedby) values(".$lid.",".$row['workcode'].",".$row['rate'].",'".date('Y\-m\-d H:i:s')."',".$_SESSION['datadetail'][0]['sessionid'].")");
		}
	}
	unset($rs_sel);
	unset($row);
	if($lid=="")
	{
		$rateid	=	$dbconnection->getField("rate_tbl","rateid","ratelistname='".$_POST['rate']."'");
		$rs_sel	=	$dbconnection->firequery("select a.*,b.actionvalue,b.verb,b.material,b.product,b.operator,b.quantity,b.notation,b.unit,b.defaultnarration from rate_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid."");
	}
	else
	{
		$rs_sel	=	$dbconnection->firequery("select a.*,b.actionvalue,b.verb,b.material,b.product,b.operator,b.quantity,b.notation,b.unit,b.defaultnarration from rate_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$lid."");	
	}
	if($_POST['ratelist']!="")
	{
		$rs_sel	=	$dbconnection->firequery("select a.*,b.actionvalue,b.verb,b.material,b.product,b.operator,b.quantity,b.notation,b.unit,b.defaultnarration from rate_list a left join workcode_master b on b.workcode=a.workcode where a.rateid=".$_POST['ratelist']."");
	}
	$i=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
	?>
	<tr>
		<td align="center"><?php echo $row['workcode'];?></td>
		<td><?php echo $row['defaultnarration'];?></td>		
		<td style="text-align:center; width:100px;">
		<input type="text" name="price<?php echo $i;?>" id="price<?php echo $i;?>" value="<?php echo $row['price'];?>" onchange="UpdatePrice(this.value,<?php echo $i;?>,<?php echo $row['recid'];?>,<?php echo $t;?>,<?php echo $row['rateid'];?>)" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>" />
		</td>
	</tr>
	<tr id="trid<?php echo $i;?>" style="display:none;"><td colspan="10"><label id="msg<?php echo $i;?>" class="bg-success" style="color:#000000; width:100%; text-align:center; font-weight:700;">&nbsp;</label></td></tr>
	<?php
	$expirydate	=	$row['expirydate'];
	}
	if($expirydate!='0000-00-00 00:00:00' && $expirydate!='1970-01-01 00:00:00')
	{
	?>
	<script>
		$("#expiry").val("<?php echo date('Y\-m\-d',strtotime($expirydate));?>T<?php echo date('H:i',strtotime($expirydate));?>");
	</script>
	<?php
	}
}
?>
