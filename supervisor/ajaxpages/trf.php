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
$t=7;
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$frmdate		=	$_POST['frmdate'];
	$todate			=	$_POST['todate'];
	$centerid		=	$_POST['centername'];
	$staffname		=	$_POST['staffname'];
	
	if($centerid=="")
	{
		if($staffname=="")
		{
			$query	=	"select * from customertest_tbl where date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']."";
		}
		else
		{
			$query	=	"select * from customertest_tbl where date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and staffid=".$staffname."";		
		}
	}
	else
	{
		if($staffname=="")
		{
			$query	=	"select * from customertest_tbl where date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and centerid=".$centerid." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']."";
		}
		else
		{
			$query	=	"select * from customertest_tbl where date(creationdate) between '".date('Y\-m\-d',strtotime($frmdate))."' and '".date('Y\-m\-d',strtotime($todate))."' and centerid=".$centerid." and franchiseid=".$_SESSION['franchisedetail'][0]['sessionid']." and staffid=".$staffname."";
		}
	}
	$rs_sel	=	$dbconnection->firequery($query);
	
	$i=$start;
	$j=0;
	$totalltol	=	0;
	$totalref	=	0;
	$tamount	=	0;
	$tafter		=	0;
	$tpaid		=	0;	
	$tbal		=	0;
	
	if($centerid!="")
	{
		$rs_doc		=	$dbconnection->firequery("select a.doctorid,b.doctorname from doctorratemapping_tbl a inner join doctor_tbl b on b.doctorid=a.doctorid where a.centerid=".$centerid."");
	}
	
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	$j++;
	if($i==1)
	{
	?>
		<tr>
		<td colspan="12" align="right">
			<a href="./excel/trf.php?centername=<?php echo $centerid;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-info">Get In Excel</button></a>
			<a href="./printing/trfentries.php?centername=<?php echo $centerid;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-info">Print</button></a>
			<a href="./printing/paid.php?centername=<?php echo $centerid;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>&staffid=<?php echo $row['staffid'];?>" target="_blank"><button type="button" class="btn btn-info">Paid List</button></a>
			<a href="./printing/unpaid.php?centername=<?php echo $centerid;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>&staffid=<?php echo $row['staffid'];?>" target="_blank"><button type="button" class="btn btn-info">Un Paid List</button></a>			
		</td>
		</tr>
		<tr>
			<th style="padding:5px; text-align:center;">Sno</th>
			<th style="padding:5px;">TRF By</th>
			<th style="padding:5px;">Patient Detail</th>
			<th style="padding:5px;">Ref. Doc & Hosp.</th>					
			<th style="padding:5px;">Urgent/Outsource</th>					
			<th style="padding:5px;">Test Detail</th>
			<th style="padding:5px;">Total Amount</th>
			<th style="padding:5px;">After Discount</th>
			<th style="padding:5px;">Paid</th>					
			<th style="padding:5px;">Balance</th>
			<th style="padding:5px;">LTOL</th>
			<th style="padding:5px;">Ref</th>			
		</tr>
	<?php
	}
	?>
	<tr>
		<td><?php echo $i;?></td>
		<td valign="top"><?php echo ucwords($row['staffname']);?><?php if($row['staffmobile']!="") echo "<br>".$row['staffmobile'];?><br /><b><?php echo $row['centername'];?></b><br />
		<?php echo date('d\-m\-Y h:i A',strtotime($row['creationdate']));?>
		</td>
		<td valign="top"><?php echo ucwords($row['customername']);?><?php if($row['mobilenumber']!="") echo "<br>".$row['mobilenumber'];?><?php if($row['age']!="") echo "<br>Age : ".$row['age'];?><?php if($row['gender']!="") echo "<br>".$row['gender'];?></td>
	<td valign="top">
	<?php 
		echo ucwords($row['doctorname']);
		if($row['address']!="")
		echo "<br>".$row['address'];
		if($centerid!="")
		{
		?>
		<br />
		<select name="referredbyid<?php echo $i;?>" id="referredbyid<?php echo $i;?>" onKeyPress="return OnKeyPress(this, event)" tabindex="<?php echo $t++;?>">			
			<option value="">--Doctor Name--</option>
			<?php			
			while($ro=mysqli_fetch_assoc($rs_doc))
			{
			?>
			<option value="<?php echo $ro['doctorid'];?>" <?php if($_POST['referredbyid']==$ro['doctorid']) echo "selected";?>><?php echo $ro['doctorname'];?></option>
			<?php
			}
			?>
		</select>
		<br />
		<button class="btn btn-info" type="button" onclick="SetPrice(<?php echo $row['customerid'];?>,<?php echo $i;?>,<?php echo $centerid;?>)" style="border-radius:0px; margin-top:5px; width:100%;">Set Price</button><br />
		<label style="display:none; color:green; font-weight:bold; font-size:12px;" class="msg<?php echo $i;?>"></label>
		<?php
		mysqli_data_seek($rs_doc,0);
		}
		?>
	</td>
	<td valign="top">
	<?php 
		if($row['outsource']=="YES")
		{
			echo "YES";
			echo "<br>".date('d\-m\-Y',strtotime($row['outsourcedate']));
		}
	?>
	<?php 
	if($row['urgent']=="YES")
	{
		echo "YES";
		echo "<br>".date('h:i A',strtotime($row['urgenttime']))."<br>";
	}
	if($row['remark']!="")
	echo "Remark<br>".$row['remark'];
	?>
	</td>
	<td valign="top" nowrap="nowrap"><?php	echo $row['testname'];?></td>
	<td><?php echo $row['totalamount'];?></td>
	<td><?php echo $row['afterdiscount'];?>
	<?php if(doubleval($row['admindiscount'])>0)
	{
		echo "<br>Additional Discount = ".doubleval($row['admindiscount']); 
	} 
	?>
	</td>	
	<td><?php echo $row['paid'];?></td>	
	<td><?php echo $row['balance'];?></td>
	<td><?php echo $row['totalltol'];?></td>
	<td><?php echo $row['refamt'];?></td>	
	</tr>
	<?php
	$totalltol	=	$totalltol+$row['totalltol'];	
	$tamount	=	$tamount+$row['totalamount'];
	$tafter		=	$tafter+$row['afterdiscount'];
	$tpaid		=	$tpaid+$row['paid'];
	$tbal		=	$tbal+$row['balance'];	
	$totalref	=	$totalref+$row['refamt'];		
	}
}
?>
<tr>
	<td colspan="6" style="padding:2px 10px; text-align:right; font-size:18px;"><b>Total L TO L</b></td>
	<td style="font-size:18px; font-weight:bold;"><?php echo $tamount;?></td>
	<td style="font-size:18px; font-weight:bold;"><?php echo $tafter;?></td>	
	<td style="font-size:18px; font-weight:bold;"><?php echo $tpaid;?></td>	
	<td style="font-size:18px; font-weight:bold;"><?php echo $tbal;?></td>	
	<td style="font-size:18px; font-weight:bold;"><?php echo $totalltol;?></td>
	<td style="font-size:18px; font-weight:bold;"><?php echo $totalref;?></td>	
</tr>