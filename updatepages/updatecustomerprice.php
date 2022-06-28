<?php
@session_start();
$t=9;
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set("display_errors",0);
ini_set("session.bug_compat_warn",0);
ini_set("session.bug_compat_42",0);
include("../db/db_connect.php");
include("../enc/urlenc.php");
$dbconnection = new DatabaseConnection;
$dbconnection->connect();
$l=0;
$rs_sel	=	$dbconnection->firequery("select * from test_tbl order by testname");
while($row=mysqli_fetch_assoc($rs_sel))
{
	$dbconnection->firequery("update test_tbl set customerprice=".$row['testamount']." where testid=".$row['testid']."");
}

?>