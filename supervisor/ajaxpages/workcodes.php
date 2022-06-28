<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();

if(isset($_SESSION['supervisordetail'][0]['sessionid']))
{
	$gpname			=	$_POST['gpname'];
	$rateid			=	$dbconnection->getField("hamaligroup_tbl","ratelistname","hgid=".$gpname."");
	$query	=	"select a.price,a.workcode,b.defaultnarration from rate_list a inner join workcode_master b on b.workcode=a.workcode where a.rateid=".$rateid." and FIND_IN_SET(".$_SESSION['supervisordetail'][0]['departmentid'].",b.departmentname)>0";

}
?>
<table style="border:1px solid #eee; border-collapse:collapse; width:100%;" border="1">
<?php
$rs_sel		=	$dbconnection->firequery($query);
$i=0;
while($row=mysqli_fetch_assoc($rs_sel))
{
$i++;
if($i==1)
{
?>
<tr>
	<td colspan="3" align="right">
		<button type="button" data-dismiss="modal" class="btn">CLOSE</button>
		<a href="./printing/printworkcodelist.php?rateid=<?php echo $rateid;?>" target="_blank"><button type="button" class="btn">TAKE PRINT</button></a>
	</td>
</tr>
<tr>
	<th style="padding:3px; text-align:center;">Work Code</th>
	<th style="padding:3px;">Particular</th>
	<th style="padding:3px; text-align:center;">Rate</th>					
</tr>

<?php
}
?>
<tr>
	<td class="center" id="action" style="width:80px; text-align:center;"><?php echo $row['workcode']; $cols++;?></td>
	<td style="text-align:left; padding:3px;"><?php echo $row['defaultnarration'];?></td>
	<td align="center"><i class="fa fa-inr"></i> <?php echo $row['price'];?></td>
</tr>
<?php
}
if($i>0)
{
}
else
{
?>
<tr>
	<td colspan="12" style="padding:0px; text-align:center;">
		<label style="font-size:13px; font-weight:normal; padding:10px;"><i>--No Record Found--</i></label>
	</td>
</tr>
<?php
}
?>
</table>