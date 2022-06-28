<?php
$t=1;
if($_SERVER['REQUEST_METHOD']=="POST")
{
	$lastreference	=	intval($dbconnection->getField("paymentslipreference_tbl","count(*)","creationdate='".date('Y\-m\-d')."'"))+1;
	$lastreference	=	ltrim($lastreference,"0");
	$lastreference	=	date("d")."/".$lastreference;
	$etn			=	date('m');
	$slipno			=	"PAY/".date('Y')."".$etn."".$lastreference;
	if($dbconnection->firequery("insert into paymentslip_tbl(payslipnumber,payslipdate,department,location,groupnumber,totalamount,advancededuction,remark,workslipids,creationdate,supervisorid) values('".$slipno."','".date('Y\-m\-d H:i:s',strtotime($_POST['payslipdate']))."',".intval($_POST['department']).",".intval($_POST['location']).",".intval($_POST['gpno']).",".doubleval($_POST['totalamount']).",".doubleval($_POST['frmadvance']).",'".$_POST['remark']."','".$_POST['ids']."','".date('Y\-m\-d H:i:s')."',".intval($_POST['spname']).")"))
	{
		$lid	=	$dbconnection->last_inserted_id();

		$locname	=	strtoupper(substr($dbconnection->getField("location_tbl","locationname","locationid=".$_POST['location'].""),0,1));
		$ind		=	intval($dbconnection->getField("location_tbl","payslip","locationid=".$_POST['location'].""));
		$fyear		=	$dbconnection->GetFinancialYear();
		$slipnumber	=	$locname."/".$fyear."/".$ind;
		$dbconnection->firequery("update location_tbl set payslip=payslip+1 where locationid=".$_POST['location']."");
		$dbconnection->firequery("update paymentslip_tbl set payslipnumber='".$slipnumber."' where slipid=".$lid."");


		
		$dbconnection->firequery("insert into payment_detail(payslipid,paymentmode,documentnumber,paidamount,frmadvance,paymentdate,creationdate,supervisorid,location,department,groupnumber) values(".$lid.",'".$_POST['paymentmode']."','".$_POST['cdno']."',".doubleval($_POST['paying']+$_POST['frmadvance']).",".doubleval($_POST['frmadvance']).",'".date('Y\-m\-d H:i:s',strtotime($_POST['payslipdate']))."','".date('Y\-m\-d H:i:s')."',".intval($_POST['spname']).",".intval($_POST['location']).",".intval($_POST['department']).",".intval($_POST['gpno']).")");
		
		$dbconnection->firequery("insert into paymentslipreference_tbl(creationdate) values('".date('Y\-m\-d')."')");		
		$ids	=	explode(",",$_POST['ids']);
		foreach($ids as $key=>$val)
		{
			$dbconnection->firequery("update workslip_tbl set paymentstatus=1 where workslipid=".$ids[$key]."");
		}
		echo '<script>document.location.href="./vnr_mainindex?m='.encrypt("savepaymentslip").'&p='.encrypt("printpaymentslip").'&slipno='.$lid.'";</script>';
		exit;		
	}
}
?>