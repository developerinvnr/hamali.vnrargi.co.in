<?php
$t=1;
if($_SERVER['REQUEST_METHOD']=="POST")
{
	$lastreference	=	intval($dbconnection->getField("paymentslipreference_tbl","count(*)","creationdate='".date('Y\-m\-d')."'"))+1;
	$lastreference	=	ltrim($lastreference,"0");
	$lastreference	=	date("d")."/".$lastreference;
	$etn			=	date('m');
	$slipno			=	"PAY/".date('Y')."".$etn."".$lastreference;
	if($dbconnection->firequery("insert into paymentslip_tbl(payslipnumber,payslipdate,department,location,groupnumber,totalamount,remark,workslipids,creationdate,supervisorid) values('".$slipno."','".date('Y\-m\-d H:i:s',strtotime($_POST['payslipdate']))."',".intval($_POST['department']).",".intval($_POST['location']).",".intval($_POST['gpno']).",".doubleval($_POST['totalamount']).",'".$_POST['remark']."','".$_POST['ids']."','".date('Y\-m\-d H:i:s')."',".$_SESSION['supervisordetail'][0]['sessionid'].")"))
	{
		$lid	=	$dbconnection->last_inserted_id();
		
		$dbconnection->firequery("insert into payment_detail(payslipid,paymentmode,documentnumber,paidamount,paymentdate,creationdate,supervisorid,location,department,groupnumber) values(".$lid.",'".$_POST['paymentmode']."','".$_POST['cdno']."',".doubleval($_POST['paying']).",'".date('Y\-m\-d H:i:s',strtotime($_POST['payslipdate']))."','".date('Y\-m\-d H:i:s')."',".$_SESSION['supervisordetail'][0]['sessionid'].",".intval($_POST['location']).",".intval($_POST['department']).",".intval($_POST['gpno']).")");
		
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