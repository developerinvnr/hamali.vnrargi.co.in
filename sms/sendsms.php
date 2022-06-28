<?php
function SendSms($mobileno,$message)
{
	$msg       	 	=  $message;
	$message		=	urlencode($msg);
	$msgbody   		=	$message;	
	$mobileNumber   =  $mobileno;
			
	$url="smsg.creativemindsoftwares.in/submitsms.jsp?user=SANJAY&key=9956aa46cdXX&mobile=$mobileNumber&message=$msgbody&senderid=CPLLAB&accusage=1";
	$ch = curl_init();
	if (!$ch){die("Couldn't initialize a cURL handle");}
	$ret = curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	$ret = curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt ($ch,CURLOPT_POSTFIELDS,"");
	$curlresponse = curl_exec($ch);
	if(curl_errno($ch))
	echo 'curl error : '. curl_error($ch);
	if (empty($ret)) {
	die(curl_error($ch));
	curl_close($ch);
	} 
	else 
	{
	$info = curl_getinfo($ch);
	curl_close($ch);
	}

}


?>