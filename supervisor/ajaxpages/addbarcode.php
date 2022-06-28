<?php
@session_start();
$t=15;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);

include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['staffdetail'][0]['sessionid']))
{
	$ids	=	$_POST['ids'];
	$rs_bar	=	$dbconnection->firequery("select a.containername,b.containername as contname,b.bgcolor from test_tbl a inner join container_tbl b on b.containerid=a.containername where a.testid in (".$ids.")");
	$bars	=	array();
	$conts	=	array();
	$cols	=	array();
	$i=0;
	while($bar=mysqli_fetch_assoc($rs_bar))
	{
		$cols[$i]	=	$bar['bgcolor'];	
		$conts[$i]	=	$bar['contname'];	
		$bars[$i]	=	$bar['containername'];	
		$i++;
	}
	$brs	=	array_unique($bars);
	for($j=0;$j<count($bars);$j++)
	{
		if($brs[$j]!="")
		{
			if($brs[$j]==4 || $brs[$j]==8)
			{
			?>
			<tr><td style="padding:10px 10px; background-color:<?php echo $cols[$j]?>; vertical-align:middle; <?php if($cols[$j]=="#FFFFFF") { ?> color:#000000;<?php } else { ?>
			color:#FFFFFF;<?php };?>;"><?php echo $conts[$j];?>*</td><td><input type="text" class="form-control bcode" required name="edtabarcode" id="edtabarcode" title="edtabarcode" autocomplete="off" value="<?php echo $_POST['edtabarcode'];?>" onchange="CheckBarcode(this.value,this.title)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td></tr>
			<?php
			}
			if($brs[$j]==2 || $brs[$j]==8)
			{
			?>
			<tr><td style="padding:10px 10px; background-color:<?php echo $cols[$j]?>; vertical-align:middle; <?php if($cols[$j]=="#FFFFFF") { ?> color:#000000;<?php } else { ?>
			color:#FFFFFF;<?php };?>;"><?php echo $conts[$j];?>*</td><td><input type="text" class="form-control bcode" required name="plainbarcode" id="plainbarcode" title="plainbarcode" autocomplete="off" value="<?php echo $_POST['plainbarcode'];?>" onchange="CheckBarcode(this.value,this.title)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td></tr>
			<?php
			}
			if($brs[$j]==3)
			{
			?>
			<tr><td style="padding:10px 10px; background-color:<?php echo $cols[$j]?>; vertical-align:middle; <?php if($cols[$j]=="#FFFFFF") { ?> color:#000000;<?php } else { ?>
			color:#FFFFFF;<?php };?>;"><?php echo $conts[$j];?>*</td><td><input type="text" class="form-control bcode" required name="flouridebarcode" id="flouridebarcode" autocomplete="off" title="flouridebarcode" value="<?php echo $_POST['flouridebarcode'];?>" onchange="CheckBarcode(this.value,this.title)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td></tr>
			<?php
			}
			if($brs[$j]==5)
			{
			?>
			<tr><td style="padding:10px 10px; background-color:<?php echo $cols[$j]?>; vertical-align:middle; <?php if($cols[$j]=="#FFFFFF") { ?> color:#000000;<?php } else { ?>
			color:#FFFFFF;<?php };?>;"><?php echo $conts[$j];?>*</td><td><input type="text" class="form-control bcode" required name="ptvialbarcode" id="ptvialbarcode" autocomplete="off" title="ptvialbarcode" value="<?php echo $_POST['ptvialbarcode'];?>" onchange="CheckBarcode(this.value,this.title)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td></tr>
			<?php
			}
			if($brs[$j]==6)
			{
			?>
			<tr><td style="padding:10px 10px; background-color:<?php echo $cols[$j]?>; vertical-align:middle; <?php if($cols[$j]=="#FFFFFF") { ?> color:#000000;<?php } else { ?>
			color:#FFFFFF;<?php };?>;"><?php echo $conts[$j];?>*</td><td><input type="text" class="form-control bcode" required name="heparinebarcode" id="heparinebarcode" autocomplete="off" title="heparinebarcode" value="<?php echo $_POST['heparinebarcode'];?>" onchange="CheckBarcode(this.value,this.title)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td></tr>
			<?php
			}
			if($brs[$j]==7)
			{
			?>
			<tr><td style="padding:10px 10px; background-color:<?php echo $cols[$j]?>; vertical-align:middle; <?php if($cols[$j]=="#FFFFFF") { ?> color:#000000;<?php } else { ?>
			color:#FFFFFF;<?php };?>;"><?php echo $conts[$j];?>*</td><td><input type="text" class="form-control bcode" required name="geltubebarcode" id="geltubebarcode" autocomplete="off" title="geltubebarcode" value="<?php echo $_POST['geltubebarcode'];?>" onchange="CheckBarcode(this.value,this.title)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td></tr>
			<?php
			}
			if($brs[$j]==1)
			{
			?>
			<tr><td style="padding:10px 10px; background-color:<?php echo $cols[$j]?>; vertical-align:middle; <?php if($cols[$j]=="#FFFFFF") { ?> color:#000000;<?php } else { ?>
			color:#FFFFFF;<?php };?>;"><?php echo $conts[$j];?>*</td><td><input type="text" class="form-control bcode" required name="urinebarcode" id="urinebarcode" autocomplete="off" title="urinebarcode" value="<?php echo $_POST['urinebarcode'];?>" onchange="CheckBarcode(this.value,this.title)" tabindex="<?php echo $t++;?>" onKeyPress="return OnKeyPress(this, event)" /></td></tr>
			<?php
			}
		}
	}
	if($j>0)
	{
	?>
	<tr>
		<td colspan="2">
			<div class="alert alert-block alert-success msg" style="padding:5px; display:none;">
				<label id="setmsg"></label>
			</div>
		</td>
	</tr>
	<tr>
		<td style="padding:5px; text-align:right;" colspan="3"><button type="submit" class="btn btn-info" tabindex="<?php echo $t++;?>" onclick="SubmitForm()">Submit</button></td> 
	</tr>	
	<?php
	}
	exit;
}
?>
