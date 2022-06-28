<?php
function addToCart($pid)
{
	echo '<script>document.location.href="./index.php";</script>';
	exit;
}

function CheckName($var)
{
	if(!preg_match("/^[a-zA-Z_ ]*$/",$var))
	{
		return 0;
	}
	else
	{
		return 1;
	}
}

function CheckUrl($var)
{
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$var)) 
	{
		return 0;
    }
	else
	{
		return 1;
	}
}



function CheckEmail($var)
{
	if(filter_var($var, FILTER_VALIDATE_EMAIL)) 
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

function CheckMobile($var)
{
	if(!preg_match("/^([0-9]){10,11}$/",$var))
	{
		return 0;
	}
	else
	{
		return 1;
	}
}

function CheckNumber($var)
{
	if(!is_numeric($var)) 
	{
		return 0;
	} 
	else 
	{
		return 1;
	}
}	

function CheckValidDate($var)
{
	if(preg_match('/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/', $var))
	{
	    return 1;
	}
	else
	{
	    return 0;
	}
}


function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}


function getTodaysDate()
{
$dd 	     	    = Date("d");
$mm				    = Date("m");
$yy				    = Date("Y");
$currentDate	    = $yy."-".$mm."-".$dd;
return $currentDate;
}


function CheckItem($pid)
{
	$max=intval(count($_SESSION['cartitem']));
	for($j=0;$j<$max;$j++)
	{
		if($_SESSION['cartitem'][$j]['pid']==$pid)
		{
			return "true";
		}
	}
}


function money($num){
    $explrestunits = "" ;
	$nm=$num;
	if($nm<0)
	{
		$num=abs($num);
	}
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
	if($nm<0)
	{
	    return "-".$thecash;
	}
	else
	{
		return	$thecash;
	}
}


function SendMessages($mobileno,$message)
{
            $msg        =  $message;
			$message	=	urlencode($msg);
			$msgbody   	=	$message;	
            $mobileNumber    =  $mobileno;
			
			 $url="http://mysms.creativemindsoftwares.in/sendSMS?username=creative&message=$message&sendername=CMSOFT&smstype=TRANS&numbers=$mobileNumber&apikey=c8272557-7f4a-457e-8956-cc69d716c5ec";
 			 $ch = curl_init();
			 if (!$ch){die("Couldn't initialize a cURL handle");}
 			 $ret = curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			 $ret = curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

 
			  curl_setopt ($ch,CURLOPT_POSTFIELDS,"");


			 $curlresponse = curl_exec($ch); // execute
			if(curl_errno($ch))
				echo 'curl error : '. curl_error($ch);
			 if (empty($ret)) {
		    // some kind of an error happened
			    die(curl_error($ch));
			    curl_close($ch); // close cURL handler
			 } else {
			    $info = curl_getinfo($ch);
			    curl_close($ch); // close cURL handler
			    //echo "<br>";
//				echo $curlresponse;    //echo "Message Sent Succesfully" ;
   
			 }

}

function SendVerificationMail($email,$firmname,$subject,$msg)
{
	$mail = new PHPMailer;
	$mail->isSMTP();              
	$mail->Host = 'mail.bnc-corporation.com';
	$mail->SMTPAuth = true;
	$mail->Username = "noreply@bnc-corporation.com";
	$mail->Password = "noreply#2017";
	$mail->setFrom('noreply@bnc-corporation.com', 'Support BNC-Corporation');
	$mail->addReplyTo('noreply@bnc-corporation.com', 'Support BNC-Corporation');
	$mail->addAddress($email,$firmname);
	$mail->Subject = $subject;
	$mail->msgHTML($msg);
//	$mail->send();
}

function generate_combinations(array $data, array &$all = array(), array $group = array(), $value = null, $i = 0)
{
    $keys = array_keys($data);
    if (isset($value) === true) {
        array_push($group, $value);
    }

    if ($i >= count($data)) {
        array_push($all, $group);
    } else {
        $currentKey     = $keys[$i];
        $currentElement = $data[$currentKey];
        foreach ($currentElement as $val) {
            generate_combinations($data, $all, $group, $val, $i + 1);
        }
    }
    return $all;
}

?>