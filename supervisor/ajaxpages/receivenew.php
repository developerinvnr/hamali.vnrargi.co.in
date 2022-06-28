<?php
@session_start();
date_default_timezone_set('Asia/Calcutta');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
date_default_timezone_set('Asia/Calcutta');
include("../../db/db_connect.php");
include("../../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
$staffname	=	$_POST['staffname'];
$headid		=	$_POST['headid'];
$frmdate	=	date('Y\-m\-d',strtotime($_POST['frmdate']));
$todate		=	date('Y\-m\-d',strtotime($_POST['todate']));	
	
if($staffname!="")
{
	$stname		=	$dbconnection->getField("staff_tbl","staffname","staffid=".$staffname."");
	$franchiseid=	$dbconnection->getField("staff_tbl","franchisename","staffid=".$staffname."");	
	$mobile		=	$dbconnection->getField("staff_tbl","mobilenumber","staffid=".$staffname."");	
	$centers	=	$dbconnection->getField("supervisor_tbl","centers","supervisorid=".$headid."");
	$cntrs		=	explode(",",$centers);
	$stcenter	=	$dbconnection->getField("staff_tbl","collectioncenter","staffid=".$staffname."");
	$stcnt		=	explode(",",$stcenter);
	$main		=	array();
	$k=0;
	for($i=0;$i<count($stcnt);$i++)
	{
		if(in_array($stcnt[$i],$cntrs))
		{
			$main[$k]	=	$stcnt[$i];
			$k++;
		}
	}
	$k=0;
	
	$rs_sel	=	$dbconnection->firequery("select (select sum(receivingamount) from staffledger_view where staffid=".$staffname." and centerid=a.centerid and date(paymentdate) between '".$frmdate."' and '".$todate."') as pd,(select sum(payingamount) from staffledger_view where staffid=".$staffname." and centerid=a.centerid and date(paymentdate) between '".$frmdate."' and '".$todate."') as rec,(select sum(payingamount) from staffledger_view where date(paymentdate)<='".$todate."' and staffid=".$staffname." and centerid=a.centerid) as received,(select sum(receivingamount) from staffledger_view where date(paymentdate)<='".$todate."' and staffid=".$staffname." and centerid=a.centerid) as paid,(select sum(balance) from customertest_tbl where staffid=".$staffname." and centerid=a.centerid and date(creationdate) between '".$frmdate."' and '".$todate."' and balance>0) as cb,(select sum(balance) from customertest_tbl where staffid=".$staffname." and centerid=a.centerid and balance>0) as fcb,a.centername,a.centerid from center_tbl a where a.centerid in (".$stcenter.") group by a.centerid order by a.centername");
	
	$i=0;
	while($row=mysqli_fetch_assoc($rs_sel))
	{
		$i++;
		$received	=	0;
		$paid		=	0;
		$balance	=	0;
		$rec		=	0;
		$pd			=	0;
	
		if($i==1)
		{
		?>
		<tr>
			<th style="padding:3px; text-align:center;">Sno</th>
			<th style="padding:3px;">Center Name</th>
			<th style="padding:3px;">Staff Detail</th>
			<th style="padding:3px;">Received</th>					
			<th style="padding:3px;">Paid</th>
			<th style="padding:3px;">Balance Payment</th>
			<th style="width:50px;">Amount</th>
			<th style="width:220px;">Date & Remark</th>					
			<th style="padding:0px; text-align:center; width:100px;"><button class="btn btn-info" style="padding:0px 15px; width:100%;">RECEIVE</button></th>
		</tr>
		<?php
		}
		?>
	<tr>
		<td align="center"><?php echo $i;?></td>
		<td><?php echo $row['centername'];?><br />
		<a href="./printing/printstaffledger.php?staffid=<?php echo $staffname;?>&centerid=<?php echo $row['centerid'];?>&frmdate=<?php echo $_POST['frmdate'];?>&todate=<?php echo $_POST['todate'];?>" target="_blank"><button type="button" class="btn btn-info">VIEW LEDGER</button></a>			

		</td>
		<td>
		<?php echo ucwords($stname);?><br /><?php echo $mobile;?>
		</td>		
		<td><i class="fa fa-inr"></i> <?php echo $rec	=	doubleval(ceil($row['rec']));?></td>		
		<td><i class="fa fa-inr"></i> <label id="pd<?php echo $i;?>" style="font-size:12px; vertical-align:text-top;"><?php echo $pd	=	 doubleval(ceil($row['pd']));?></label>
		<br /><input type="hidden" name="ppd<?php echo $i;?>" id="ppd<?php echo $i;?>" value="<?php echo $pd;?>" />
		</td>
		<td style="font-size:16px;"> 
		<?php
		$received	=	doubleval(ceil($row['received']));


		$paid		=	doubleval(ceil($row['paid']));
		$balance	=	$received-$paid;
		?>
		<i class="fa fa-inr"></i> <label id="bal<?php echo $i;?>" style="vertical-align:text-top;"><?php echo $balance;?></label>
		<?php
		echo "<br><label style='color:red; font-size:12px;'>C.B. = <i class='fa fa-inr'></i> ".ceil($row['cb'])."</label>";
		?>
		<i class="fa fa-eye" onclick="GetBalanceList(<?php echo $row['centerid'];?>,<?php echo $staffname;?>,'<?php echo $frmdate;?>','<?php echo $todate;?>')"></i>
		<?php
		echo "<br><label style='color:red; font-size:12px;'>Final C.B. = <i class='fa fa-inr'></i> ".ceil($row['fcb'])."</label>";		
		?>
		<i class="fa fa-eye" onclick="GetFullBalanceList(<?php echo $row['centerid'];?>,<?php echo $staffname;?>)"></i>		
		</td>
		<td>
		<input type="text" name="recamt<?php echo $i;?>" id="recamt<?php echo $i;?>" style="width:75px;" class="form-control" placeholder="Amount" />
		<input type="hidden" name="balance<?php echo $i;?>" id="balance<?php echo $i;?>" value="<?php echo $balance;?>" />
		<input type="hidden" name="staffid<?php echo $i;?>" id="staffid<?php echo $i;?>" value="<?php echo $staffname;?>" />
		<input type="hidden" name="franchiseid<?php echo $i;?>" id="franchiseid<?php echo $i;?>" value="<?php echo $franchiseid;?>" />	
		<input type="hidden" name="centerid<?php echo $i;?>" id="centerid<?php echo $i;?>" value="<?php echo $row['centerid'];?>" />			
		<input type="hidden" name="headid<?php echo $i;?>" id="headid<?php echo $i;?>" value="<?php echo $headid;?>" />					
		<label id="msg<?php echo $i;?>" class="btn-danger" style="min-height:20px; width:100%; padding:5px; color:#FFFFFF; display:none;">&nbsp;</label>
		<label id="msg1<?php echo $i;?>" class="btn-success" style="min-height:20px; width:100%; padding:5px; color:#FFFFFF; display:none;">&nbsp;</label>																			
		</td>
		<td>
		<input type="datetime-local" name="insdate<?php echo $i;?>" id="insdate<?php echo $i;?>" value="<?php echo date('Y\-m\-d',strtotime($todate))."T".date('H:i');?>" readonly /><br />		
		<input type="text" name="remark<?php echo $i;?>" id="remark<?php echo $i;?>" placeholder="Enter remark" style="width:100%; margin-top:5px;" />
		</td>
		<td style="padding:0px;">
		<?php
		if($balance>0)
		{
		?>
		<button type="button" class="btn btn-info" id="btn<?php echo $i;?>" title="btn<?php echo $i;?>" style="width:100%;" onclick="Receive(<?php echo $i;?>)">RECEIVE</button>
		<?php
		}
		else
		{
		?>
		<button type="button" class="btn btn-default" disabled="disabled" style="width:100%;"><i class="fa fa-smile-o" style="font-size:18px;"></i> PAID</button>	
		<?php
		}
		?>
		</td>
	</tr>
		<?php
		$totalbalance	=	$totalbalance+$balance;
		$totalreceived	=	$totalreceived+$rec;	
		$totalpaid		=	$totalpaid+$pd;	
	}
	?>
	<tr style="font-size:18px;">
		<td colspan="3" align="right";><b>Total</b></td>
		<td><i class="fa fa-inr"></i> <?php echo $totalreceived;?></td>
		<td><i class="fa fa-inr"></i> <?php echo $totalpaid;?></td>		
		<td><i class="fa fa-inr"></i> <?php echo $totalbalance;?></td>	
		<td colspan="3"></td>
	</tr>
	<?php
}
}
?>