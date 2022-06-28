<?php
@session_start();

//$_SESSION['encryption-key']	=	"ABCD1234";
$_SESSION['encryption-key']	=	"SANJAYSIR";
function encrypt($pure_string) {
    $dirty = array("+", "/", "=");
    $clean = array("_PLUS_","_SLASH_", "_EQUALS_");
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $_SESSION['iv'] = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $_SESSION['encryption-key'], utf8_encode($pure_string), MCRYPT_MODE_ECB, $_SESSION['iv']);
    $encrypted_string = base64_encode($encrypted_string);
    return str_replace($dirty, $clean, $encrypted_string);
}

function decrypt($encrypted_string) { 
    $dirty = array("+", "/", "=");
    $clean = array("_PLUS_","_SLASH_", "_EQUALS_");

    $string = base64_decode(str_replace($clean, $dirty,$encrypted_string));

    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $_SESSION['encryption-key'],$string, MCRYPT_MODE_ECB, $_SESSION['iv']);
    return clear($decrypted_string);
}

function clear($string) {
   $string = str_replace(' ','-',$string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/','',$string); // Removes special chars.
}


function encryptvalue($pure_string) {
    $dirty = array("+", "/", "=");
    $clean = array("_PLUS_","_SLASH_", "_EQUALS_");
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $_SESSION['iv'] = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $_SESSION['encryption-key'], utf8_encode($pure_string), MCRYPT_MODE_ECB, $_SESSION['iv']);
    $encrypted_string = base64_encode($encrypted_string);
    return str_replace($dirty, $clean, $encrypted_string);
}

function decryptvalue($encrypted_string) { 
    $dirty = array("+", "/", "=");
    $clean = array("_PLUS_","_SLASH_", "_EQUALS_");

    $string = base64_decode(str_replace($clean, $dirty,$encrypted_string));

    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $_SESSION['encryption-key'],$string, MCRYPT_MODE_ECB, $_SESSION['iv']);
    return $decrypted_string;
}

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
//echo decrypt('_PLUS_kc7ZvaQwio_EQUALS_');

?>