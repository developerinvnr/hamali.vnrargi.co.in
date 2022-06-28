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
if(isset($_SESSION['franchisedetail'][0]['sessionid']))
{
	$centername		=	$_POST['centername'];
	$doctorname		=	$_POST['doctorname'];
	$copyname		=	$_POST['copyname'];
	if(!$dbconnection->isRecordExist("select * from doctorrate_list where centerid=".$centername." and doctorid=".$doctorname.""))
	{
		if($copyname=="")
		{
			$rs_ratelist	=	$dbconnection->firequery("select * from ratemappingnew_tbl where centerid=".$centername."");
			$r=0;
			while($rt=mysqli_fetch_assoc($rs_ratelist))
			{
				$r++;
				$rateid	=	$rt['rateid'];
				$franchiseid=	$dbconnection->getField("center_tbl","franchisename","centerid=".$centername."");
				$rs_test	=	$dbconnection->firequery("select * from test_tbl");
				while($row=mysqli_fetch_assoc($rs_test))
				{
					$dbconnection->firequery("insert into doctorrate_list(franchiseid,centerid,rateid,doctorid,testid,categoryid,ratepercent,rateamount,creationdate) values(".$franchiseid.",".$centername.",".$rateid.",".$doctorname.",".$row['testid'].",".$row['categoryname'].",0,0,'".date('Y\-m\-d H:i:s')."')");
				}
			}
			if($r==0)
			{
				echo "error";
				exit;
			}
		}
		else
		{
			$rs_sel	=	$dbconnection->firequery("select * from doctorrate_list where doctorid=".$copyname."");
			while($rate=mysqli_fetch_assoc($rs_sel))
			{
				$dbconnection->firequery("insert into doctorrate_list(franchiseid,centerid,rateid,doctorid,testid,categoryid,ratepercent,rateamount,creationdate,isactive) values(".$rate['franchiseid'].",".$centername.",".$rate['rateid'].",".$doctorname.",".$rate['testid'].",".$rate['categoryid'].",".$rate['ratepercent'].",".$rate['rateamount'].",'".date('Y\-m\-d H:i:s')."',".$rate['isactive'].")");
			}
		}
	}	
	$rs_category	=	$dbconnection->firequery("select * from category_tbl where relatedwithprice='YES' order by categoryname");
	$j=0;
	while($row=mysqli_fetch_assoc($rs_category))
	{
		$j++;
		?>
		<tr class="bg-info">
			<td colspan="9" style="padding:0px 5px; font-weight:bold; text-align:center; font-size:16px;"><?php echo $row['categoryname'];?>
			<input type="hidden" name="categoryname<?php echo $j;?>" id="categoryname<?php echo $j;?>" value="<?php echo $row['categoryid'];?>" />
			<input type="text" name="percent<?php echo $j;?>" id="percent<?php echo $i;?>" style="float:right; padding:2px;" placeholder="Update percentage" onchange="UpdateDoctorPrice(this.value,<?php echo $j;?>)"/>
			</td>
		</tr>
		<tr style="font-weight:bold;" class="bg bg-info">
			<td align="center" style="padding:2px;">S.NO.</td>
			<td style="padding:2px;">TEST NAME</td>
			<td align="center" style="padding:2px;">TEST AMOUNT</td>
			<td align="center" style="padding:2px;">L TO L</td>
			<td style="padding:2px; width:50px;">PERCENT</td>
			<td style="padding:2px; width:50px;">AMOUNT</td>
			<td style="padding:2px; text-align:center;">SAVED ON LTOL</td>			
		</tr>		
		<?php
		$i=0;
		$rs_sel		=	$dbconnection->firequery("select a.recordid,a.ratepercent,a.rateamount,b.testamount,b.ltol,c.testname  from doctorrate_list a inner join ratelistnew_tbl b on b.rateid=a.rateid inner join test_tbl c on c.testid=a.testid where a.categoryid=".$row['categoryid']." and a.centerid=".$centername." and a.doctorid=".$doctorname." and a.testid=b.testid order by c.testname");
		while($tst=mysqli_fetch_assoc($rs_sel))
		{
			$i++;
			?>
			<tr>
				<td class="center" id="action" style="padding:2px;"><?php echo $i;?></td>
				<td style="padding:2px;"><?php echo $tst['testname'];?></td>
				<td style="padding:2px; text-align:center;"><?php echo $tst['testamount'];?></td>
				<td style="padding:2px; text-align:center;"><?php echo $tst['ltol'];?></td>				
				<td style="padding:0px; text-align:center;"><?php echo $tst['ratepercent'];?></td>
				<td style="padding:0px;"><input type="text" name="rateamount<?php echo $j;?><?php echo $i;?>" id="rateamount<?php echo $j;?><?php echo $i;?>" value="<?php echo $tst['rateamount'];?>" style=" width:100px; padding:2px;" onchange="UpdateDocInr(<?php echo $j;?><?php echo $i;?>,<?php echo $tst['recordid'];?>)" /></td>
				<td style="padding:2px; text-align:center;"><?php echo $tst['testamount']-$tst['ltol']-$tst['rateamount'];?></td>				
			</tr>
			<?php
		}
		?>
		<tr><td colspan="7">&nbsp;</td></tr>
		<?php
	}
}
?>
