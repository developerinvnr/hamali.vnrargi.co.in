<?php
session_start();
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
date_default_timezone_set('Asia/Calcutta');
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['centerdetail'][0]['sessionid']))
{
	$todaysdate	=	date('Y\-m\-d',strtotime($_POST['todaysdate']));
	$centers	=	$dbconnection->getField("staff_tbl","collectioncenter","staffid=".$_SESSION['staffdetail'][0]['sessionid']."");
	$rs_center	=	$dbconnection->firequery("select a.*,b.franchisename as franname,b.franchiseid from center_tbl a inner join franchise_tbl b on b.franchiseid=a.franchisename where a.centerid in (".$centers.")");
	$i=0;
	while($row=mysqli_fetch_assoc($rs_center))
	{
	?>
	<div class="infobox infobox-blue infobox-dark" style="width:550px; height:190px; margin-top:10px; text-align:center; border-radius:5px; cursor:pointer; float:left;">
		<div style="padding:10px;">
			<h4><?php echo $row['franname'];?></h4>
			<?php echo $row['centername'];?><br /><br />
			<label class="btn-primary" style="position:absolute; bottom:66px; left:0; width:20%; padding:5px; text-align:center; cursor:pointer;">Sample = <?php echo $dbconnection->getField("customertest_tbl","count(*)","staffid=".$_SESSION['staffdetail'][0]['sessionid']." and date(creationdate)='".date('Y\-m\-d',strtotime($_POST['todaysdate']))."' and centerid=".$row['centerid']."");?></label>
			
			<label class="btn-primary" style="position:absolute; bottom:66px; left:113px; width:45%; padding:5px; text-align:center; cursor:pointer;"><i class="fa fa-inr"></i> <?php echo doubleval($dbconnection->getField("customerreceipt_tbl","sum(payingamount)","staffid=".$_SESSION['staffdetail'][0]['sessionid']." and date(paymentdate)='".date('Y\-m\-d',strtotime($_POST['todaysdate']))."' and centerid=".$row['centerid'].""));?> Received From Customer</label>			

			<label class="btn-primary" style="position:absolute; bottom:66px; right:0; width:34%; padding:5px; text-align:center; cursor:pointer;"><i class="fa fa-inr"></i> <?php echo doubleval($dbconnection->getField("customertest_tbl","sum(balance)","staffid=".$_SESSION['staffdetail'][0]['sessionid']." and date(creationdate)='".date('Y\-m\-d',strtotime($_POST['todaysdate']))."' and centerid=".$row['centerid'].""));?> Customer Balance</label>						
			

			<label class="btn-primary" style="position:absolute; bottom:33px; left:0; width:100%; padding:5px; text-align:center; cursor:pointer;">TOTAL CUSTOMER BALANCE FOR <?php echo $row['centername'];?> = <i class="fa fa-inr"></i> <?php echo doubleval($dbconnection->getField("customertest_tbl","sum(balance)","staffid=".$_SESSION['staffdetail'][0]['sessionid']." and centerid=".$row['centerid'].""));?></label>			

			<label class="btn-primary" style="position:absolute; bottom:0px; left:0; width:100%; padding:5px; text-align:center; cursor:pointer;">YOU NEED TO PAY <i class="fa fa-inr"></i> <?php echo floor(doubleval($dbconnection->getField("staffledger_view","sum(payingamount)","staffid=".$_SESSION['staffdetail'][0]['sessionid']." and centerid=".$row['centerid'].""))-doubleval($dbconnection->getField("staffledger_view","sum(receivingamount)","staffid=".$_SESSION['staffdetail'][0]['sessionid']." and centerid=".$row['centerid']."")));?> TO <?php echo $row['centername'];?></label>
			
		</div>
	</div>
	<?php
	}
}
?>
	<div class="infobox infobox-blue infobox-dark" style="width:97%; height:40px; text-align:center; border-radius:5px; margin-top:10px; cursor:pointer; float:left;">
		<div style="padding:0px;">
			<label class="btn-primary" style="width:100%; padding:5px; text-align:center; cursor:pointer;"><b>TOTAL DUE AMOUNT ON YOU IS</b> &nbsp;<b style="font-size:16px;"><i class="fa fa-inr"></i> <?php echo floor(doubleval($dbconnection->getField("staffledger_view","sum(payingamount)","staffid=".$_SESSION['staffdetail'][0]['sessionid'].""))-doubleval($dbconnection->getField("staffledger_view","sum(receivingamount)","staffid=".$_SESSION['staffdetail'][0]['sessionid']."")));?></b></label>
			
		</div>
	</div>
