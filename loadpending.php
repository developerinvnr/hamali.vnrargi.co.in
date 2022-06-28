 <?php 
	include_once("../../config.php");
	$department	=	$_POST['department'];
	$bcode		=	$_POST['bcode'];
	$prostatus	=	$_POST['prostatus'];
	$searchvalue=	$_POST['searchvalue'];
	$center		=	$_POST['center'];
	$frmdate	=	date('Y\-m\-d H:i:s',strtotime($_POST['frmdate']));
	$todate		=	date('Y\-m\-d H:i:s',strtotime($_POST['todate']));			

	$rs_ct	=	mysqli_query($con,"select * from customertest_detail where received=1 and isrejected=0 and finalverified=0 and creationdate between '".$frmdate."' and '".$todate."'");	
	$pdcnt	=	mysqli_num_rows($rs_ct);

	$cids	= array();
	$cond	=	0;
	if($_POST['pnd']=="")
	{
	if($_POST['urgt']!="T" && $_POST['hosp']!="T")
	{
		$cond	=	0;
		if($bcode!="")
		{
			$cond++;
			$query	=	"select * from customer_barcode where barcode='".$bcode."' and ispending=0 and isrejected=0";
		}
		if($cond==0)
		{	
			if($searchvalue!="")
			{
				if($department=="" && $prostatus=="")
				{
					$cond++;
					$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where (a.customername like '$searchvalue%') and b.urgent='' and b.outsource='' and b.ispending=0 and b.isrejected=0 and b.testdepartment in (".$_SESSION['datadetail'][0]['department'].")";// and a.creationdate between '".$frmdate."' and '".$todate."'";
				}
				else if($department!="" && $prostatus=="")
				{
					$cond++;			
					$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where (a.customername like '$searchvalue%') and b.urgent='' and b.outsource='' and b.ispending=0 and b.isrejected=0 and b.testdepartment=".$department." and b.finalverified=0";// and a.creationdate between '".$frmdate."' and '".$todate."'";
				}
				else if($department=="" && $prostatus!="")
				{
					$cond++;
					$str	=	"";
					if($prostatus=="E")
					{
						$str	=	" and entered=1";
					}	
					else if($prostatus=="V")
					{
						$str	=	" and verified=1";				
					}
					else if($prostatus=="A")
					{
						$str	=	" and finalverified=1";				
					}
					$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where (a.customername like '$searchvalue%') and b.urgent='' and b.outsource='' and b.ispending=0 and b.isrejected=0 and b.testdepartment in (".$_SESSION['datadetail'][0]['department'].")".$str;// and a.creationdate between '".$frmdate."' and '".$todate."'";
				}
				else if($department!="" && $prostatus!="")
				{
					$cond++;
					$str	=	"";
					if($prostatus=="E")
					{
						$str	=	" and entered=1";
					}	
					else if($prostatus=="V")
					{
						$str	=	" and verified=1";				
					}
					else if($prostatus=="A")
					{
						$str	=	" and finalverified=1";				
					}
					$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where (a.customername like '$searchvalue%') and b.urgent='' and b.outsource='' and b.ispending=0 and b.isrejected=0 and b.testdepartment=".$department."".$str;// and a.creationdate between '".$frmdate."' and '".$todate."'".$str;
				}
			}
		}
		if($cond==0)
		{
			if($department=="" && $prostatus=="")
			{
				$cond++;
				$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where b.isrejected=0 and b.ispending=0 and b.testdepartment in (".$_SESSION['datadetail'][0]['department'].") and b.urgent='' and b.outsource='' and a.creationdate between '".$frmdate."' and '".$todate."'";
			}
			else if($department!="" && $prostatus=="")
			{
				$cond++;			
				$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where b.isrejected=0 and b.ispending=0 and b.testdepartment=".$department." and b.urgent='' and b.finalverified=0 and b.outsource='' and a.creationdate between '".$frmdate."' and '".$todate."'";
			}
			else if($department=="" && $prostatus!="")
			{
				$cond++;
				$str	=	"";
				if($prostatus=="E")
				{
					$str	=	" and entered=1";
				}	
				else if($prostatus=="V")
				{
					$str	=	" and verified=1";				
				}
				else if($prostatus=="A")
				{
					$str	=	" and finalverified=1";				
				}
				$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where b.isrejected=0 and b.ispending=0 and b.testdepartment in (".$_SESSION['datadetail'][0]['department'].") and a.creationdate between '".$frmdate."' and '".$todate."' and b.urgent='' and b.outsource=''".$str;
			}
			else if($department!="" && $prostatus!="")
			{
				$cond++;
				$str	=	"";
				if($prostatus=="E")
				{
					$str	=	" and entered=1";
				}	
				else if($prostatus=="V")
				{
					$str	=	" and verified=1";				
				}
				else if($prostatus=="A")
				{
					$str	=	" and finalverified=1";				
				}
				$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where b.isrejected=0 and b.ispending=0 and b.testdepartment=".$department." and b.urgent='' and a.creationdate between '".$frmdate."' and '".$todate."' and b.outsource=''".$str;
			}
		}
		$rs_sql	=	mysqli_query($con,$query);
		while($cust= mysqli_fetch_assoc($rs_sql))
		{
			$cids[] = $cust['customerid'];
		}
		$cids	=	array_unique($cids);
		$cid	=	implode(",",$cids);
		if($center=="")
		{
			if($bcode=="" && $searchvalue=="")
			$sql_cust = mysqli_query($con, "select * from customertest_tbl where customerid in (".$cid.") and iscomplete=0 and creationdate between '".$frmdate."' and '".$todate."' order by creationdate,centername"); 
			else
			$sql_cust = mysqli_query($con, "select * from customertest_tbl where customerid in (".$cid.") order by creationdate desc,centername"); 
		}
		else
		{
			if($bcode=="" && $searchvalue=="")		
			$sql_cust = mysqli_query($con, "select * from customertest_tbl where customerid in (".$cid.") and iscomplete=0 and creationdate between '".$frmdate."' and '".$todate."' and centerid=".$center." order by creationdate,centername");
			else
			$sql_cust = mysqli_query($con, "select * from customertest_tbl where customerid in (".$cid.") and centerid=".$center." order by creationdate desc,centername");
		}
		$i=0;
		$oc	=	0;
		$nc	=	0;
		?>
	<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse; padding:3px; font-size:13px; color:#000000;" border="1">	
		<?php
		while($row = mysqli_fetch_assoc($sql_cust))
		{
			$i++;
			$oc	=	$row['centerid'];
			if($oc!=$nc)
			{
			?>
			<tr style="font-size:12px; background-color:#00ABD2;">
				<td colspan="7" style="padding:4px; color:#FFFFFF; font-weight:500; text-align:center;"><?php echo $row['centername'];?>
				<?php
				if($i==1)
				{
				?>
	<a href="./printing/printprocess.php?center=<?php echo $center;?>&department=<?php echo $department;?>&prostatus=<?php echo $prostatus;?>&searchvalue=<?php echo $searchvalue;?>&bcode=<?php echo $bcode;?>&frmdate=<?php echo $frmdate;?>&todate=<?php echo $todate;?>" target="_blank"><button type="button" class="btn btn-primary" style="padding:0px 12px; border-radius:0px; float:right;" id="prdata"><i class="fa fa-print"></i> Print Data</button></a>
				<?php
				}
				?>			
				</td>
			</tr>
			<tr style="font-size:12px; background-color:#00ABD2;">
				<td style="padding:4px; color:#FFFFFF; text-align:center; width:30px;"><b>S.No.</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Staff Detail</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Patient Name</b></td>
				<td style="padding:4px; color:#FFFFFF;" colspan="2"><b>Test Name</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Barcode</b></td>
				<td style="padding:4px; color:#FFFFFF; text-align:center;"></td>		
			</tr>		
			<?php
			$nc=$row['centerid'];
			}
			?>
			<tr>
			<td style="text-align:center; vertical-align:top; padding:3px;"><?php echo $i;?></td>
			<td valign="top" style="padding:2px; width:150px;">
			<b><?php echo ucwords($row['staffname']);?></b><?php if($row['staffmobile']!="") echo "<br>".$row['staffmobile'];?><br />
			<?php echo date('d\-m\-Y',strtotime($row['creationdate']));?><br />
			<?php echo date('h:i A',strtotime($row['creationdate']));?>
			<input type="hidden" name="h<?php echo $row['referencenumber'];?>" id="h<?php echo $row['referencenumber'];?>" value="<?php echo $row['customerid'];?>" />
			</td>
			<td valign="top" style="padding:3px; vertical-align:top; width:200px;">
			<?php 
			echo '<b>'."<b style='font-size:13px;'>(Ref Id- ".$row['referencenumber'].")</b><br>".$row['initials']." ".ucwords($row['customername']).'</b>';
			if($row['mobilenumber']!="") 
			echo "<br>".$row['mobilenumber'];
			if($row['age']!="")
			echo "<br><b>Age : ".$row['age']." ".$row['agetype'].'/'.$row['gender'].'</b>';
			
			echo "<br>Remark<br><b>".$row['remark'];
			echo "<br>Pt. Id - <b>".$row['customerid']."</b>";
			?>
			<!--br /><b style="font-weight:bold; font-size:13px;">Doctor Name</b><br />
			</?php echo $row['doctorname'];?-->			
			</td>
			<td style="padding:3px; vertical-align:top;" colspan="2" nowrap="nowrap">
			<?php
			if($bcode=="")
			{
			?>
			<div id="otur<?php echo $i;?>">
			<label onclick="LoadOtUr(<?php echo $row['customerid'];?>,'<?php echo $department;?>',<?php echo $i;?>)"><?php echo str_replace(",","<br>",$row['testname']); ?></label>
			</div>
			<?php
			}
			else
			{
			if($department=="")
			{
				//$rs_test	=	mysqli_query($con,"select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.ispending=0 and a.customerid=".$row['customerid']." and testdepartment in (".$_SESSION['datadetail'][0]['department'].") and a.outsource!='on' and a.urgent!='on'");
				$rs_test	=	mysqli_query($con,"select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.ispending=0 and a.customerid=".$row['customerid']." and testdepartment in (".$_SESSION['datadetail'][0]['department'].") and a.outsource!='on' and a.urgent!='on'");
			}
			else
			{
				//$rs_test	=	mysqli_query($con,"select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.ispending=0 and a.customerid=".$row['customerid']." and testdepartment=".$department." and a.finalverified=0 and a.outsource!='on' and a.urgent!='on'");		
				$rs_test	=	mysqli_query($con,"select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.ispending=0 and a.customerid=".$row['customerid']." and testdepartment=".$department." and a.outsource!='on' and a.urgent!='on' and a.finalverified=0");		
			}
			$str	=	"";
			$tt	=	0;
			$container	=	array();
			while($tst=mysqli_fetch_assoc($rs_test))
			{
				$tt++;
				if($tst['isrejected']==1)
				{
				?>
				<label style="color:red; padding:0px;"><?php echo $tst['testname'];?></label>
				<?php
				}
				else
				{
				?>
				<label style="padding:0px;"><?php echo $tst['testname'];?></label>			
				<?php
				if($tst['entered']==0 && $tst['verified']==0 && $tst['finalverified']==0 && $tst['oscreportingstatus']==0)
				{
				?>
				<label style="float:right;">
				OT <input type="checkbox" name="ot<?php echo $i;?><?php echo $tt;?>" id="ot<?php echo $i;?><?php echo $tt;?>" style="vertical-align:sub;" <?php if($tst['outsource']=='on') echo "checked";?> onclick="ChangeStatus(<?php echo $i;?>,<?php echo $tst['recordid'];?>,'<?php echo $tst['outsource'];?>','OT')"/>
	
				UR <input type="checkbox" name="ur<?php echo $i;?><?php echo $tt;?>" id="ur<?php echo $i;?><?php echo $tt;?>" style="vertical-align:sub;"  <?php if($tst['urgent']=='on') echo "checked";?> onclick="ChangeStatus(<?php echo $i;?>,<?php echo $tst['recordid'];?>,'<?php echo $tst['urgent'];?>','UR')" />			
				</label>
				<?php
				}
				}
				if($tst['entered']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $tst['enterredby'];?>">E</label>
				<?php
				}
				if($tst['verified']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $tst['verifiedby'];?>">V</label>
				<?php
				}
				if($tst['finalverified']==1 || $tst['oscreportingstatus']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $tst['finalverifiedby'];?>">A</label>
				<?php
				}
				$container[]	=	$tst['containerid'];
				echo "<br>";
			}
			}
			?>
			<label style="width:100%; padding:2px; color:#FFFFFF; display:none; background-color:#00ABD2;" id="sts<?php echo $i;?>"></label>
			<?php
			if(permission($_SESSION['datadetail'][0]['sessionid'],$_SESSION['datadetail'][0]['authtype'],146)==1)
			{
			?>
						
			<button type="button" class="btn btn-info" style="border-radius:0px; background-color:#00ABD2; line-height:20px; padding:2px 10px; font-size:12px; text-align:center; bottom:0px; position:relative;" onclick="ViewAddTestDetail(<?php echo $row['customerid'];?>,<?php echo intval($row['referredbyid']);?>,<?php echo intval($row['staffid']);?>,<?php echo intval($row['centerid']);?>,<?php echo intval($row['franchiseid']);?>)">Add Test <i class="fa fa-plus"></i></button>
			<?php
			}
			?>
			</td>
			<td style="padding:3px; vertical-align:top; width:200px;" nowrap="nowrap">
			<?php
			if($bcode=="")
			{
			?>
			<div id="brcode<?php echo $i;?>"><label style="width:100%;" onclick="LoadBrCd(<?php echo $row['customerid'];?>,'<?php echo $department;?>',<?php echo $i;?>)"><?php echo str_replace(",","<br>",$row['barcode']);?></label></div>
			<?php
			}
			else
			{
			$containers	=	implode(",",$container);
			$rs_bar	=	mysqli_query($con,"select * from customer_barcode where customerid=".$row['customerid']." and containerid in (".$containers.") and ispending=0");
			while($bar=mysqli_fetch_assoc($rs_bar))
			{
				if($bar['isrejected']==0)
				{
					if($department=="")
					$rs_m	=	mysqli_query($con,"select * from customertest_detail where customerid=".$bar['customerid']." and containerid=".$bar['containerid']."");
					else
					$rs_m	=	mysqli_query($con,"select * from customertest_detail where customerid=".$bar['customerid']." and containerid=".$bar['containerid']." and testdepartment=".$department."");
					$cnt=0;
					while($m=mysqli_fetch_assoc($rs_m))
					{
						$cnt++;
					}
					$l=0;
					if($department=="")				
					$rs_m	=	mysqli_query($con,"select * from customertest_detail where customerid=".$bar['customerid']." and containerid=".$bar['containerid']." and finalverified=1");
					else
					$rs_m	=	mysqli_query($con,"select * from customertest_detail where customerid=".$bar['customerid']." and containerid=".$bar['containerid']." and finalverified=1 and testdepartment=".$department."");
					while($m=mysqli_fetch_assoc($rs_m))
					{
						$l++;
					}
					if($l>0)
					{
					echo '<label style="color:red;">'.$bar['container_name'].'-'.$bar['barcode'].' &nbsp;<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="CAN NOT BE REJECTED"><i class="fa fa-check"></i></label></label> <i style="float:right; margin-right:5px;"><green>'.date('d M, h:i A',strtotime($bar['receivingdatetime'])).'</green></i><br/>'; 
					}
					else
					{
						if($department=="")
						$rs_m	=	mysqli_query($con,"select * from customertest_detail where customerid=".$bar['customerid']." and testdepartment=".$bar['testdep']." and containerid=".$bar['containerid']." and finalverified=0 and (urgent='on' or outsource='on')");
						else
						$rs_m	=	mysqli_query($con,"select * from customertest_detail where customerid=".$bar['customerid']." and testdepartment=".$bar['testdep']." and containerid=".$bar['containerid']." and finalverified=0 and (urgent='on' or outsource='on') and testdepartment=".$department."");
						$t=0;
						while($m=mysqli_fetch_assoc($rs_m))
						{
							$t++;
						}
						unset($rs_m);
						unset($m);				
						if($cnt!=($t+$l))
						{
							echo '<label>'.$bar['container_name'].'-'.$bar['barcode'].'</label> <input type="checkbox" class="reject" onclick="RejectSample()" value="'.$bar['recordid'].'"><i style="float:right; margin-right:5px;"><green>'.date('d M, h:i A',strtotime($bar['receivingdatetime'])).'</green></i><br/>'; 
						}
					}
				}
				else
				{
					echo '<label style="color:red;">'.$bar['container_name'].'-'.$bar['barcode'].'</label> <i style="float:right; margin-right:5px; color:red;">'.date('d M, h:i A',strtotime($bar['receivingdatetime'])).'</i><br/>'; 
				}
			$b++;
			}
			}
			?>
			</td>
			<td style="vertical-align:top; padding:3px; width:80px;">
				<button type="button" class="btn" style="padding:0px 5px; border-radius:0px; width:100%; background-color:#00ABD2; color:#FFFFFF;" onclick="ProcessSample(<?php echo $row['customerid'];?>)">Process</button>
				<button type="button" class="btn" style="padding:0px 5px; border-radius:0px; margin-top:5px; width:100%; background-color:#00ABD2; color:#FFFFFF;" onclick="ViewSaveReport(<?php echo $row['customerid'];?>)">View All</button>				
			</td>
			</tr>
			<?php			
		}
	?>
	</table>
	<?php
	}
	else
	{
		$cond	=	0;
		if($bcode!="")
		{
			$cond++;
			$query	=	"select a.customerid from customer_barcode a inner join customer_barcode b on b.customerid=a.customerid where a.barcode='".$bcode."' and a.ispending=0 and a.isrejected=0";
		}
		if($cond==0)
		{	
			if($searchvalue!="")
			{
				if($department=="" && $prostatus=="")
				{
					$cond++;
					$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where (a.customername like '%$searchvalue%') and b.urgent='' and b.ispending=0 and b.isrejected=0 and b.testdepartment in (".$_SESSION['datadetail'][0]['department'].")";// and a.creationdate between '".$frmdate."' and '".$todate."'";
				}
				else if($department!="" && $prostatus=="")
				{
					$cond++;			
					$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where (a.customername like '%$searchvalue%') and b.urgent='on' and b.outsource='' and b.ispending=0 and b.isrejected=0 and b.testdepartment=".$department." and b.finalverified=0";// and a.creationdate between '".$frmdate."' and '".$todate."'";
				}
				else if($department=="" && $prostatus!="")
				{
					$cond++;
					$str	=	"";
					if($prostatus=="E")
					{
						$str	=	" and entered=1";
					}	
					else if($prostatus=="V")
					{
						$str	=	" and verified=1";				
					}
					else if($prostatus=="A")
					{
						$str	=	" and finalverified=1";				
					}
					$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where (a.customername like '%$searchvalue%') and b.urgent='on' and b.ispending=0 and b.isrejected=0 and b.testdepartment in (".$_SESSION['datadetail'][0]['department'].")".$str;// and a.creationdate between '".$frmdate."' and '".$todate."'";
				}
				else if($department!="" && $prostatus!="")
				{
					$cond++;
					$str	=	"";
					if($prostatus=="E")
					{
						$str	=	" and entered=1";
					}	
					else if($prostatus=="V")
					{
						$str	=	" and verified=1";				
					}
					else if($prostatus=="A")
					{
						$str	=	" and finalverified=1";				
					}
					$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where (a.customername like '%$searchvalue%') and b.urgent='on' and b.ispending=0 and b.isrejected=0 and b.testdepartment=".$department."".$str;// and a.creationdate between '".$frmdate."' and '".$todate."'".$str;
				}
			}
		}
		if($cond==0)
		{
			if($department=="" && $prostatus=="")
			{
				$cond++;
				$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where b.isrejected=0 and b.ispending=0 and b.testdepartment in (".$_SESSION['datadetail'][0]['department'].") and b.urgent='on' and a.creationdate between '".$frmdate."' and '".$todate."'";
			}
			else if($department!="" && $prostatus=="")
			{
				$cond++;			
				$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where b.isrejected=0 and b.ispending=0 and b.testdepartment=".$department." and b.urgent='on' and b.finalverified=0 and a.creationdate between '".$frmdate."' and '".$todate."'";
			}
			else if($department=="" && $prostatus!="")
			{
				$cond++;
				$str	=	"";
				if($prostatus=="E")
				{
					$str	=	" and entered=1";
				}	
				else if($prostatus=="V")
				{
					$str	=	" and verified=1";				
				}
				else if($prostatus=="A")
				{
					$str	=	" and finalverified=1";				
				}
				$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where b.isrejected=0 and b.ispending=0 and b.testdepartment in (".$_SESSION['datadetail'][0]['department'].") and a.creationdate between '".$frmdate."' and '".$todate."' and b.urgent='on'".$str;
			}
			else if($department!="" && $prostatus!="")
			{
				$cond++;
				$str	=	"";
				if($prostatus=="E")
				{
					$str	=	" and entered=1";
				}	
				else if($prostatus=="V")
				{
					$str	=	" and verified=1";				
				}
				else if($prostatus=="A")
				{
					$str	=	" and finalverified=1";				
				}
				$query	=	"select a.customerid from customertest_tbl a left join customertest_detail b on b.customerid=a.customerid where b.isrejected=0 and b.ispending=0 and b.testdepartment=".$department." and b.urgent='on' and a.creationdate between '".$frmdate."' and '".$todate."'".$str;
			}
		}
		$rs_sql	=	mysqli_query($con,$query);
		while($cust= mysqli_fetch_assoc($rs_sql))
		{
			$cids[] = $cust['customerid'];
		}
		$cids	=	array_unique($cids);
		$cid	=	implode(",",$cids);
		if($center=="")
		{
			if($bcode=="" && $searchvalue=="")
			$sql_cust = mysqli_query($con, "select * from customertest_tbl where customerid in (".$cid.") and iscomplete=0 and creationdate between '".$frmdate."' and '".$todate."' order by creationdate,centername"); 
			else
			$sql_cust = mysqli_query($con, "select * from customertest_tbl where customerid in (".$cid.") order by creationdate desc,centername"); 
		}
		else
		{
			if($bcode=="" && $searchvalue=="")		
			$sql_cust = mysqli_query($con, "select * from customertest_tbl where customerid in (".$cid.") and iscomplete=0 and creationdate between '".$frmdate."' and '".$todate."' and centerid=".$center." order by creationdate,centername");
			else
			$sql_cust = mysqli_query($con, "select * from customertest_tbl where customerid in (".$cid.") and centerid=".$center." order by creationdate desc,centername");
		}
		$i=0;
		$oc	=	0;
		$nc	=	0;
		?>
	<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse; padding:3px; font-size:13px; color:#000000;" border="1">	
		<?php
		while($row = mysqli_fetch_assoc($sql_cust))
		{
			if($department=="")
			{
				//$rs_test	=	mysqli_query($con,"select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.ispending=0 and a.customerid=".$row['customerid']." and testdepartment in (".$_SESSION['datadetail'][0]['department'].") and a.outsource!='on' and a.urgent!='on'");
				$rs_test	=	mysqli_query($con,"select a.*,b.testname from customertest_detail a left join test_tbl b on b.testid=a.testid where a.ispending=0 and a.customerid=".$row['customerid']." and a.testdepartment in (".$_SESSION['datadetail'][0]['department'].") and a.urgent='on' and a.finalverified=0");
			}
			else
			{
				//$rs_test	=	mysqli_query($con,"select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.ispending=0 and a.customerid=".$row['customerid']." and testdepartment=".$department." and a.finalverified=0 and a.outsource!='on' and a.urgent!='on'");		
				$rs_test	=	mysqli_query($con,"select a.*,b.testname from customertest_detail a inner join test_tbl b on b.testid=a.testid where a.ispending=0 and a.customerid=".$row['customerid']." and testdepartment=".$department." and a.finalverified=0 and a.urgent='on' and a.finalverified=0");		
			}
			
			$cvt	=	mysqli_num_rows($rs_test);
			if($cvt>0)
			{
			$i++;
			$oc	=	$row['centerid'];
			if($oc!=$nc)
			{
			?>
			<tr style="font-size:12px; background-color:#00ABD2;">
				<td colspan="7" style="padding:4px; color:#FFFFFF; font-weight:500; text-align:center;"><?php echo $row['centername'];?>
				<?php
				if($i==1)
				{
				?>
	<a href="./printing/printprocess.php?center=<?php echo $center;?>&department=<?php echo $department;?>&prostatus=<?php echo $prostatus;?>&searchvalue=<?php echo $searchvalue;?>&bcode=<?php echo $bcode;?>" target="_blank"><button type="button" class="btn btn-primary" style="padding:0px 12px; border-radius:0px; float:right;" id="prdata"><i class="fa fa-print"></i> Print Data</button></a>
				<?php
				}
				?>			
				</td>
			</tr>
			<tr style="font-size:12px; background-color:#00ABD2;">
				<td style="padding:4px; color:#FFFFFF; text-align:center; width:30px;"><b>S.No.</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Staff Detail</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Patient Name</b></td>
				<td style="padding:4px; color:#FFFFFF;" colspan="2"><b>Test Name</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Barcode</b></td>
				<td style="padding:4px; color:#FFFFFF; text-align:center;"></td>		
			</tr>		
			<?php
			$nc=$row['centerid'];
			}
			?>
			<tr>
			<td style="text-align:center; vertical-align:top; padding:3px;"><?php echo $i;?></td>
			<td valign="top" style="padding:2px;">
			<b><?php echo ucwords($row['staffname']);?></b><?php if($row['staffmobile']!="") echo "<br>".$row['staffmobile'];?><br />
			<?php echo date('d\-m\-Y',strtotime($row['creationdate']));?><br />
			<?php echo date('h:i A',strtotime($row['creationdate']));?>
			<input type="hidden" name="h<?php echo $row['referencenumber'];?>" id="h<?php echo $row['referencenumber'];?>" value="<?php echo $row['customerid'];?>" />
			</td>
			<td valign="top" style="padding:3px; vertical-align:top; width:200px;">
			<?php 
			echo '<b>'."<b style='font-size:13px;'>(".$row['referencenumber'].")</b> ".$row['initials']." ".ucwords($row['customername']).'</b>';
			if($row['mobilenumber']!="") 
			echo "<br>".$row['mobilenumber'];
			if($row['age']!="")
			echo "<br><b>Age : ".$row['age']."/".$row['agetype'].'</b>';
			if($row['gender']!="")
			echo "<br>Gender : ".$row['gender'];
			echo "<br>Remark<br><b>".$row['remark']."</b>";
			?>
			<br /><b style="font-weight:bold; font-size:13px;">Doctor Name</b><br />
			<?php echo $row['doctorname'];?>			
			</td>
			<td style="padding:3px; vertical-align:top;" colspan="2" nowrap="nowrap">
			<?php
			$str	=	"";
			$tt	=	0;
			$container	=	array();
			while($tst=mysqli_fetch_assoc($rs_test))
			{
				$tt++;
				if($tst['isrejected']==1)
				{
				?>
				<label style="color:red; padding:0px;"><?php echo $tst['testname'];?></label>
				<?php
				}
				else
				{
				?>
				<label style="padding:0px;"><?php echo $tst['testname'];?></label>			
				<?php
				if($tst['entered']==0 && $tst['verified']==0 && $tst['finalverified']==0 && $tst['oscreportingstatus']==0)
				{
				?>
				<label style="float:right;">
				UR <input type="checkbox" name="ur<?php echo $i;?><?php echo $tt;?>" id="ur<?php echo $i;?><?php echo $tt;?>" style="vertical-align:sub;"  <?php if($tst['urgent']=='on') echo "checked";?> disabled="disabled" />			
				</label>
				<?php
				}
				}
				if($tst['entered']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $tst['enterredby'];?>">E</label>
				<?php
				}
				if($tst['verified']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $tst['verifiedby'];?>">V</label>
				<?php
				}
				if($tst['finalverified']==1 || $tst['oscreportingstatus']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $tst['finalverifiedby'];?>">A</label>
				<?php
				}
				$container[]	=	$tst['containerid'];
				echo "<br>";
			}
			?>
			<label style="width:100%; padding:2px; color:#FFFFFF; display:none; background-color:#00ABD2;" id="sts<?php echo $i;?>"></label>
			<?php
			if(permission($_SESSION['datadetail'][0]['sessionid'],$_SESSION['datadetail'][0]['authtype'],146)==1)
			{
			?>						
			<button type="button" class="btn btn-info" style="border-radius:0px; background-color:#00ABD2; line-height:20px; padding:2px 10px; font-size:12px; text-align:center; bottom:0px; position:relative;" onclick="ViewAddTestDetail(<?php echo $row['customerid'];?>,<?php echo intval($row['referredbyid']);?>,<?php echo intval($row['staffid']);?>,<?php echo intval($row['centerid']);?>,<?php echo intval($row['franchiseid']);?>)">Add Test <i class="fa fa-plus"></i></button>			
			<?php
			}
			?>
			</td>
			<td style="padding:3px; vertical-align:top; width:200px;" nowrap="nowrap">
			<?php
			$containers	=	implode(",",$container);
			$rs_bar		=	mysqli_query($con,"select * from customer_barcode where customerid=".$row['customerid']." and containerid in (".$containers.") and ispending=0 and isrejected=0");
			while($bar=mysqli_fetch_assoc($rs_bar))
			{
				echo $bar['container_name']."-".$bar['barcode']."<br>";
				$b++;
			}
			?>
			</td>
			<td style="vertical-align:top; padding:3px; width:80px;">
				<button type="button" class="btn" style="padding:0px 5px; border-radius:0px; width:100%; background-color:#00ABD2; color:#FFFFFF;" onclick="ProcessSample(<?php echo $row['customerid'];?>)">Process</button>
				<button type="button" class="btn" style="padding:0px 5px; border-radius:0px; margin-top:5px; width:100%; background-color:#00ABD2; color:#FFFFFF;" onclick="ViewSaveReport(<?php echo $row['customerid'];?>)">View All</button>								
			</td>
			</tr>
			<?php			
		}
		}
	?>
	</table>
	<?php		
	}
	}
	else
	{
		$cond	=	0;
		$str	=	"";
		if($department=="")
		{
			$str.=	" and a.testdepartment in (".$_SESSION['datadetail'][0]['department'].")";		
		}
		else
		{
			$str.=	" and a.testdepartment=".$department."";
		}
		if($prostatus=="E")
		{
			$str.=	" and entered=1";
		}	
		if($prostatus=="V")
		{
			$str.=	" and verified=1";				
		}
		if($center!="")
		{
			$str.=	" and a.centerid=".$center."";
		}
		$str.=	" group by a.recordid order by b.centername,b.creationdate,a.testdepartment";
		if($bcode!="")
		{
			$cond++;			
			$query	=	"select a.customerid,a.recordid,a.entered,a.verified,a.finalverified,a.oscreportingstatus,a.centerid,b.centername,a.urgent,a.outsource,b.customername,b.mobilenumber,b.age,b.agetype,b.initials,b.doctorname,b.gender,b.remark,b.referencenumber,b.staffname,b.staffmobile,b.creationdate,c.barcode,c.container_name,a.isrejected,d.testname from customertest_detail a left join customertest_tbl b on b.customerid=a.customerid left join customer_barcode c on c.customerid=a.customerid left join test_tbl d on d.testid=a.testid where a.received=1 and a.isrejected=0 and a.ispending=0 and a.finalverified=0 and c.barcode='".$bcode."'";
		}
		if($cond==0)
		{
			if($searchvalue!="")
			{
				$cond++;
				$query	=	"select a.customerid,a.recordid,a.entered,a.verified,a.finalverified,a.oscreportingstatus,a.centerid,b.centername,a.urgent,a.outsource,b.customername,b.mobilenumber,b.age,b.agetype,b.initials,b.doctorname,b.gender,b.remark,b.staffname,b.staffmobile,b.creationdate,b.referencenumber,c.barcode,c.container_name,a.isrejected,d.testname from customertest_detail a left join customertest_tbl b on b.customerid=a.customerid left join customer_barcode c on c.customerid=a.customerid left join test_tbl d on d.testid=a.testid where a.received=1 and a.isrejected=0 and a.ispending=0 and a.finalverified=0 and (b.customername like '$searchvalue%' or b.mobilenumber like '$searchvalue%' or b.whatsapp like '$searchvalue%')".$str;
			}
		}
		if($cond==0)
		{
			$query	=	"select a.customerid,a.recordid,a.entered,a.verified,a.finalverified,a.oscreportingstatus,a.centerid,b.centername,a.urgent,a.outsource,b.customername,b.mobilenumber,b.age,b.agetype,b.initials,b.doctorname,b.gender,b.remark,b.staffname,b.staffmobile,b.creationdate,b.referencenumber,c.barcode,c.container_name,a.isrejected,d.testname from customertest_detail a left join customertest_tbl b on b.customerid=a.customerid left join customer_barcode c on c.customerid=a.customerid left join test_tbl d on d.testid=a.testid where a.received=1 and a.isrejected=0 and a.ispending=0 and a.finalverified=0 and a.creationdate between '".$frmdate."' and '".$todate."'".$str;			
		}
		$rs_sel	=	mysqli_query($con,$query);
		$i=0;
		$oc	=	0;
		$nc	=	0;
		?>
	<table style="width:100%; border:1px solid #CCCCCC; border-collapse:collapse; padding:3px; font-size:13px; color:#000000;" border="1">	
		<?php
		while($row = mysqli_fetch_assoc($rs_sel))
		{
			$i++;
			$oc	=	$row['centerid'];
			if($oc!=$nc)
			{
			?>
			<tr style="font-size:12px; background-color:#00ABD2;">
				<td colspan="7" style="padding:4px; color:#FFFFFF; font-weight:500; text-align:center;"><?php echo $row['centername'];?>
				<?php
				if($i==1)
				{
				?>
	<a href="./printing/printprocess.php?center=<?php echo $center;?>&department=<?php echo $department;?>&prostatus=<?php echo $prostatus;?>&searchvalue=<?php echo $searchvalue;?>&bcode=<?php echo $bcode;?>" target="_blank"><button type="button" class="btn btn-primary" style="padding:0px 12px; border-radius:0px; float:right;" id="prdata"><i class="fa fa-print"></i> Print Data</button></a>
				<?php
				}
				?>			
				</td>
			</tr>
			<tr style="font-size:12px; background-color:#00ABD2;">
				<td style="padding:4px; color:#FFFFFF; text-align:center; width:30px;"><b>S.No.</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Staff Detail</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Patient Name</b></td>
				<td style="padding:4px; color:#FFFFFF;" colspan="2"><b>Test Name</b></td>
				<td style="padding:4px; color:#FFFFFF;"><b>Barcode</b></td>
				<td style="padding:4px; color:#FFFFFF; text-align:center;"></td>		
			</tr>		
			<?php
			$nc=$row['centerid'];
			}
			?>
			<tr>
			<td style="text-align:center; vertical-align:top; padding:3px;"><?php echo $i;?></td>
			<td valign="top" style="padding:2px;">
			<b><?php echo ucwords($row['staffname']);?></b><?php if($row['staffmobile']!="") echo "<br>".$row['staffmobile'];?><br />
			<?php echo date('d\-m\-Y',strtotime($row['creationdate']));?><br />
			<?php echo date('h:i A',strtotime($row['creationdate']));?>
			<input type="hidden" name="h<?php echo $row['referencenumber'];?>" id="h<?php echo $row['referencenumber'];?>" value="<?php echo $row['customerid'];?>" />
			</td>
			<td valign="top" style="padding:3px; vertical-align:top; width:200px;">
			<?php 
			echo '<b>'."<b style='font-size:13px;'>(".$row['referencenumber'].")</b> ".$row['initials']." ".ucwords($row['customername']).'</b>';
			if($row['mobilenumber']!="") 
			echo "<br>".$row['mobilenumber'];
			if($row['age']!="")
			echo "<br><b>Age : ".$row['age']."/".$row['agetype'].'</b>';
			if($row['gender']!="")
			echo "<br>Gender : ".$row['gender'];
			echo "<br>Remark<br><b>".$row['remark'];
			echo "<br>Id - <b>".$row['customerid']."</b>";
			?>
			<br /><b style="font-weight:bold; font-size:13px;">Doctor Name</b><br />
			<?php echo $row['doctorname'];?>			
			</td>
			<td style="padding:3px; vertical-align:top;" nowrap="nowrap">
			<!--
			<div id="otur<?php echo $i;?>">
			<label onclick="LoadOtUr(<?php echo $row['customerid'];?>,'<?php echo $department;?>',<?php echo $i;?>)"><?php echo str_replace(",","<br>",$row['testname']); ?></label>
			</div>
			-->
			<?php
				if($row['isrejected']==1)
				{
				?>
				<label style="color:red; padding:0px;"><?php echo $tst['testname'];?></label>
				<?php
				}
				else
				{
				?>
				<label style="padding:0px;"><?php echo $row['testname'];?></label>			
				<?php
				if($row['entered']==0 && $row['verified']==0 && $row['finalverified']==0 && $row['oscreportingstatus']==0)
				{
				?>
				<label style="float:right;">
				OT <input type="checkbox" name="ot<?php echo $i;?><?php echo $tt;?>" id="ot<?php echo $i;?><?php echo $tt;?>" style="vertical-align:sub;" <?php if($row['outsource']=='on') echo "checked";?> onclick="ChangeStatus(<?php echo $i;?>,<?php echo $row['recordid'];?>,'<?php echo $row['outsource'];?>','OT')"/>
	
				UR <input type="checkbox" name="ur<?php echo $i;?><?php echo $tt;?>" id="ur<?php echo $i;?><?php echo $tt;?>" style="vertical-align:sub;"  <?php if($row['urgent']=='on') echo "checked";?> onclick="ChangeStatus(<?php echo $i;?>,<?php echo $row['recordid'];?>,'<?php echo $row['urgent'];?>','UR')" />			
				</label>
				<?php
				}
				}
				if($row['entered']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $tst['enterredby'];?>">E</label>
				<?php
				}
				if($row['verified']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $row['verifiedby'];?>">V</label>
				<?php
				}
				if($row['finalverified']==1 || $row['oscreportingstatus']==1)
				{
				?>
				<label class="text-white" style="width:16px; background-color:#00ABD2; height:16px; padding:2px; font-size:10px; border-radius:180px; text-align:center; vertical-align:text-top; float:right; margin-right:2px;" title="<?php echo $row['finalverifiedby'];?>">A</label>
				<?php
				}
				echo "<br>";

			?>
			<label style="width:100%; padding:2px; color:#FFFFFF; display:none; background-color:#00ABD2;" id="sts<?php echo $i;?>"></label>
			<?php
			if(permission($_SESSION['datadetail'][0]['sessionid'],$_SESSION['datadetail'][0]['authtype'],146)==1)
			{
			?>			
			<button type="button" class="btn btn-info" style="border-radius:0px; background-color:#00ABD2; line-height:20px; padding:2px 10px; font-size:12px; text-align:center; bottom:0px; position:relative;" onclick="ViewAddTestDetail(<?php echo $row['customerid'];?>,<?php echo intval($row['referredbyid']);?>,<?php echo intval($row['staffid']);?>,<?php echo intval($row['centerid']);?>,<?php echo intval($row['franchiseid']);?>)">Add Test <i class="fa fa-plus"></i></button>			
			<?php
			}
			?>
			</td>
			<td style="padding:3px; vertical-align:top; width:200px;" nowrap="nowrap">
			<?php echo $row['container_name']."-".$row['barcode'];?>
			</td>
			<td style="vertical-align:top; padding:3px; width:80px;">
				<button type="button" class="btn" style="padding:0px 5px; border-radius:0px; width:100%; background-color:#00ABD2; color:#FFFFFF;" onclick="ProcessSample(<?php echo $row['customerid'];?>)">Process</button>
				<button type="button" class="btn" style="padding:0px 5px; border-radius:0px; margin-top:5px; width:100%; background-color:#00ABD2; color:#FFFFFF;" onclick="ViewSaveReport(<?php echo $row['customerid'];?>)">View All</button>				
			</td>
			</tr>
			<?php			
		}
	?>
	</table>
	<?php
	}
	?>
<script>
	$("#punit").css("display","");
	$("#pendingcnt").html("(<?php echo $pdcnt;?>)");
	$("#pendcnt").html("(<?php echo $pdcnt;?>)");	
</script>