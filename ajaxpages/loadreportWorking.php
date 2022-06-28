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
?>
<style>
td{
	border:1px solid #777;
}
</style>
<?php
if(isset($_SESSION['datadetail'][0]['sessionid']))
{
	$frmdate		=	date('Y\-m\-d',strtotime($_POST['frmdate']));
	$todate			=	date('Y\-m\-d',strtotime($_POST['todate']));
	$locations		=	trim($_POST['locations']);
	$departments	=	trim($_POST['departments']);
	$supervisors	=	trim($_POST['supervisors']);
	$hamaligroups	=	trim($_POST['hamaligroups']);
	?>
	<table style="width:100%; border:1px solid #777;" border="1">
	<tr style="background-color:#008C40; color:white;">
		<td align="left">Plant</td>
		<td align="left">Department</td>
		<td align="left">Section</td>
		<td align="left">Supervisor</td>
		<td align="left">Hamali Groups</td>
		<td align="center">O.P.B.</td>
		<td align="center">W.S.A.</td>
		<td align="center">P.S.A.</td>
		<td align="center">Balance</td>
	</tr>
	<?php
	$qry	=	array();
	$qry1	=	array();
	$str	=	"";
	$grpby	=	"";
	if($locations!="")
	{
		if(count($qry)==0)
		$grpby.="group by e.locationid";
		else
		$grpby.=",e.locationid";
		$qry[count($qry)]="e.locationid in (".$locations.")";
		$qry1[count($qry1)]="location in (".$locations.")";
	}
	if($departments!="")
	{
		if(count($qry)==0)
		$grpby.="group by d.departmentid";
		else
		$grpby.=",d.departmentid";
		$qry[count($qry)]="c.departmentname in (".$departments.")";
		$qry1[count($qry1)]="department in (".$departments.")";
	}
	if($supervisors!="")
	{
		if(count($qry)==0)
		$grpby.="group by c.supervisorid";
		else
		$grpby.=",c.supervisorid";
		$qry[count($qry)]="c.supervisorid in (".$supervisors.")";
		$qry1[count($qry1)]="supervisorid in (".$supervisors.")";
	}
	if($hamaligroups!="")
	{
		if(count($qry)==0)
		$grpby.="group by b.hgid";
		else
		$grpby.=",b.hgid";
		$qry[count($qry)]="b.hgid in (".$hamaligroups.")";
		$qry1[count($qry1)]="groupnumber in (".$hamaligroups.")";
	}
	if(count($qry)>0)
	{
		$str	=	implode(" and ",$qry);
		$query	= " and ".$str."";	
	}
	if(count($qry1)>0)
	{
		$str1	=	implode(" and ",$qry1);
		$query1	= " and ".$str1."";	
	}
	$i=0;
	//$rs_sel	=	$dbconnection->firequery("select e.locationid,e.locationname as locname,d.departmentid,d.departmentname as depname,a.supervisorid,c.firstname,c.lastname,a.groupnumber,a.groupname as gpname,sum(a.workslipamount) as wamt from workslip_tbl a left join hamaligroup_tbl b on b.hgid=a.groupnumber left join supervisor_tbl c on c.supervisorid=a.supervisorid left join department_tbl d on d.departmentid=c.departmentname left join location_tbl e on e.locationid=c.locationname where date(a.creationdate) between '".$frmdate."' and '".$todate."' ".$query." ".$grpby." order by e.locationname,d.departmentname,c.firstname,b.groupname");
	
	$rs_sel	=	$dbconnection->firequery("select e.locationid,e.locationname as locname,d.departmentid,d.departmentname as depname,a.supervisorid,c.firstname,c.lastname,a.groupnumber,a.groupname as gpname,sum(a.workslipamount) as wamt from workslip_tbl a left join hamaligroup_tbl b on b.hgid=a.groupnumber left join supervisor_tbl c on c.supervisorid=a.supervisorid left join department_tbl d on d.departmentid=c.departmentname left join location_tbl e on e.locationid=c.locationname where date(a.creationdate) between '".$frmdate."' and '".$todate."' ".$query." ".$grpby." order by e.locationname,d.departmentname,c.firstname,b.groupname");
	
	$preloc	=	"";
	$newloc	=	"";
	$presec	=	"";
	$newsec	=	"";
	$predep	=	"";
	$newdep	=	"";
	$presup	=	"";
	$newsup	=	"";
	$pregrp	=	"";
	$newgrp	=	"";
	
	while($row=mysqli_fetch_assoc($rs_sel))
	{
	$i++;
	$opbalance	=	0;
	$opwork		=	0;
	$oppay		=	0;
	$rs_opwork	=	$dbconnection->firequery("select a.workslipamount as wamt from workslip_tbl a left join hamaligroup_tbl b on b.hgid=a.groupnumber left join supervisor_tbl c on c.supervisorid=a.supervisorid left join department_tbl d on d.departmentid=c.departmentname left join location_tbl e on e.locationid=c.locationname where date(a.creationdate)<'".$frmdate."' ".$query."");
	$opwork=	0;
	while($opw=mysqli_fetch_assoc($rs_opwork))
	{
		$opwork		=	$opw['wamt'];
	}
	$rs_oppay	=	$dbconnection->firequery("select paidamount as paidamt from payment_detail where date(paymentdate)<'".$frmdate."' ".$query1."");
	$oppay	=	0;
	while($opp=mysqli_fetch_assoc($rs_opwork))
	{
		$oppay		=	$opp['paidamt'];
	}
	$opbalance	=	$opwork-$oppay;

	
	if($locations!="")
	{
	$preloc	=	$row['locationid'];
	}
	if($departments!="")
	{
	$presec	=	$dbconnection->getField("section_tbl","sectionname","FIND_IN_SET(".$row['departmentid'].",departmentname)>0");
	}
	if($departments!="")
	{
	$predep	=	$row['departmentid'];
	}
	$presup	=	$row['supervisorid'];
	$pregrp	=	$row['groupnumber'];
	?>
	<tr>
		<td>
		<?php 
		if($locations=="" && $i==1)
		{
			echo "All";
			$preloc="All";
			$newloc="All";
		}
		else
		{
		if($preloc!=$newloc)
		{
		echo $row['locname'];
		}
		}
		?>
		</td>
		<td>
		<?php 
		if($presec=="" && $i==1)
		{
			echo "All";
			$presec="All";
			$newsec="All";
		}		
		else
		{
		if($presec!=$newsec || $preloc!=$newloc)
		{
		echo $presec;
		}
		$newsec	=	$presec;
		$newloc	=	$preloc;
		}
		?>
		</td>
		<td>
		<?php
		if($departments=="" || ($presec==$newsec && $preloc!=$newloc))
		{
			echo "All";
			$predep="All";
			$newdep="All";
		}		
		else
		{
		if($predep!=$newdep)
		{
		echo $row['depname'];
		}
		$newdep	=	$predep;
		}
		?>
		</td>
		<td>
		<?php 
		if($supervisors=="" || ($predep==$newdep && $presec==$newsec && $preloc!=$newloc))
		{
			echo "All";
			$presup="All";
			$newsup="All";
		}		
		else
		{
		if($presup!=$newsup)
		{
			echo $row['firstname']." ".$row['lastname'];
		}
		//$newsup	=	$presup;
		}
		?>
		</td>
		<td>
		<?php 
		if($hamaligroups=="" || ($presup==$newsup && $predep==$newdep && $presec==$newsec && $preloc!=$newloc))
		{
			echo "All";
			$pregrp="All";
			$newgrp="All";
		}		
		else
		{
		if($pregrp!=$newgrp || $presup!=$newsup)
		{
		echo $row['gpname'];
		}
		$newgrp	=	$pregrp;
		$newsup=$presup;
		}
		?>
		</td>
		<td align="center"><i class="fa fa-inr"></i> <?php echo $opbalance;?></td>
		<td align="center"> <i class="fa fa-inr"></i><?php echo $row['wamt'];?></td>
		<td align="center"> <i class="fa fa-inr"></i></td>

		<td align="center"> <i class="fa fa-inr"></i> <?php echo doubleval($opbalance+$wbetween-$pbetween);?></td>
	</tr>
	<tr id="rec<?php echo $i;?>" style="display:none; padding:20px;" class="bg-ingo text-white wrec"><td id="recd<?php echo $i;?>" colspan="8" style="width:100%;"></td></tr>	
	<?php
	}
	?>
	</table>
	<?php
}
?>
