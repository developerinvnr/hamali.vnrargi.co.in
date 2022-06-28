<?php
@session_start();

function DeleteAvailability($ids,$dbcon)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$dbcon->firequery("delete from availability_tbl where avid=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "Availability name deleted successfully!";	
			echo '<script>document.location.href="./mainindex.php?route='.encrypt("add availability").'&page='.encrypt("available").'";</script>';
			exit;
		}
	}
	else
	{

		$_SESSION['warning'] = "No records selected to delete";
		echo '<script>document.location.href="./mainindex.php?route='.encrypt("add availability").'&page='.encrypt("available").'";</script>';
		exit;
	}
}


function DeleteFacility($ids,$dbcon)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$dbcon->firequery("delete from facility_tbl where facilityid=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "Facility name deleted successfully!";	
			echo '<script>document.location.href="./mainindex.php?route='.encrypt("add facility").'&page='.encrypt("facility").'";</script>';
			exit;
		}
	}
	else
	{

		$_SESSION['warning'] = "No records selected to delete";
		echo '<script>document.location.href="./mainindex.php?route='.encrypt("add facility").'&page='.encrypt("facility").'";</script>';
		exit;
	}
}


function DeleteUsers($ids,$dbcon)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$dbcon->firequery("delete from registration_tbl where regid=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "User detail deleted successfully!";	
			echo '<script>document.location.href="./mainindex.php?route='.encrypt("users list").'&page='.encrypt("userlist").'";</script>';
			exit;
		}
	}
	else
	{

		$_SESSION['warning'] = "No records selected to delete";
		echo '<script>document.location.href="./mainindex.php?route='.encrypt("users list").'&page='.encrypt("userlist").'";</script>';
		exit;
	}
}


function DeleteOwnerA($ids,$dbcon)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$imgpath	=	$dbcon->getField("owner_tbl","profilepic","ownerid=".trim(decryptvalue($ids[$i]))."");
			if($imgpath!='')
			{
				unlink("./profilepic/".trim(decryptvalue($imgpath)));
			}
			$dbcon->firequery("delete from owner_tbl where ownerid=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "Owner detail deleted successfully!";	
			echo '<script>document.location.href="./mainindex.php?route='.encrypt("approved list").'&page='.encrypt("approvedlist").'";</script>';
			exit;
		}
	}
	else
	{

		$_SESSION['warning'] = "No records selected to delete";
		echo '<script>document.location.href="./mainindex.php?route='.encrypt("approved list").'&page='.encrypt("approvedlist").'&id='.$pageid.'";</script>';
		exit;
	}
}



function ApproveOwner($ids,$dbcon)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$dbcon->firequery("update owner_tbl set userstatus='ACTIVE' where ownerid=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "Owner detail approved successfully!";	
			echo '<script>document.location.href="./mainindex.php?route='.encrypt("approval list").'&page='.encrypt("approvallist").'";</script>';
			exit;
		}
	}
	else
	{

		$_SESSION['warning'] = "No records selected to approve";
		echo '<script>document.location.href="./mainindex.php?route='.encrypt("approval list").'&page='.encrypt("approvallist").'&id='.$pageid.'";</script>';
		exit;
	}
}


function DeleteOwner($ids,$dbcon)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$imgpath	=	$dbcon->getField("owner_tbl","profilepic","ownerid=".trim(decryptvalue($ids[$i]))."");
			if($imgpath!='')
			{
				unlink("./profilepic/".trim(decryptvalue($imgpath)));
			}
			$dbcon->firequery("delete from owner_tbl where ownerid=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "Owner detail deleted successfully!";	
			echo '<script>document.location.href="./mainindex.php?route='.encrypt("approval list").'&page='.encrypt("approvallist").'";</script>';
			exit;
		}
	}
	else
	{

		$_SESSION['warning'] = "No records selected to delete";
		echo '<script>document.location.href="./mainindex.php?route='.encrypt("approval list").'&page='.encrypt("approvallist").'&id='.$pageid.'";</script>';
		exit;
	}
}


function DeleteExpenses($ids,$dbcon,$pageid)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$dbcon->firequery("delete from expenses_tbl where eid=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "Expenses deleted successfully!";	
			echo '<script>document.location.href="./mainindex.php?route='.encrypt("add expenses").'&page='.encrypt("addexpenses").'&id='.$pageid.'";</script>';
			exit;
		}
	}
	else
	{

		$_SESSION['warning'] = "No records selected to delete";
		echo '<script>document.location.href="./mainindex.php?route='.encrypt("add expenses").'&page='.encrypt("addexpenses").'&id='.$pageid.'";</script>';
		exit;
	}
}


function DeleteRoom($ids,$dbcon,$pageid)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$dbcon->firequery("delete from room_tbl where id=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "Room deleted successfully!";	
			echo '<script>document.location.href="./mainindex.php?route='.encrypt("add room").'&page='.encrypt("newroom").'&id='.$pageid.'";</script>';
			exit;
		}
	}
	else
	{

		$_SESSION['warning'] = "No records selected to delete";
		echo '<script>document.location.href="./mainindex.php?route='.encrypt("add room").'&page='.encrypt("newroom").'&id='.$pageid.'";</script>';
		exit;
	}
}

function DeleteUser($ids,$dbcon,$pageid)
{
	if($ids!=null)
	{
		$ids = explode(",",$ids);
		$nums = count($ids);
		$flag=0;
		for($i=0;$i<$nums;$i++)
		{	
			$flag++;
			$dbcon->firequery("delete from user_tbl where userid=".trim(decryptvalue($ids[$i]))."");	
		}
		if($flag>0)
		{
			$_SESSION['success'] = "Record deleted successfully!";	
			echo '<script>document.location.href="./index.php?route='.encrypt("add user").'&page='.encrypt("newuser").'&id='.$pageid.'";</script>';
			exit;
		}
	}
	else
	{
		$_SESSION['warning'] = "No records selected to delete";
		echo '<script>document.location.href="./index.php?route='.encrypt("add user").'&page='.encrypt("newuser").'&id='.$pageid.'";</script>';
		exit;
	}
}


function compress_image($source_url, $destination_url, $quality) {
	$info = getimagesize($source_url);
 
	if($info['mime'] == 'image/jpeg') 
	{
	$image = imagecreatefromjpeg($source_url);
	imagejpeg($image, $destination_url, $quality);
	}
	else if($info['mime'] == 'image/gif') 
	{
	$image = imagecreatefromgif($source_url);
	imagegif($image, $destination_url, $quality);	
	}
	else if($info['mime'] == 'image/png') 
	{
	$image = imagecreatefromgif($source_url);
	imagegif($image, $destination_url, $quality);	
	}

	return $destination_url;
}


	function TodaysDate()
	{
   		$dd 	     	= Date("d");
    	$mm				= Date("m");
    	$yy				= Date("Y");
    	$currentDate	= $dd."-".$mm."-".$yy;
		return $currentDate;
	}


function number_to_words($x)
{

$number = $x;
   $no = round($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? '' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  return $result;
  
}



function GetBalance()
{

$postData = array( 
'user' => $username, 
'key' => $authKey, 
'senderid' => $senderId, 
'accusage' => $accusage 
); 

//API URL 
$url="smsg.creativemindsoftwares.in/getbalance.jsp?user=THESINGH&key=72a3e11652XX&accusage=1"; 

// init the resource 
$ch = curl_init(); 
curl_setopt_array($ch, array( 
CURLOPT_URL => $url, 
CURLOPT_RETURNTRANSFER => true, 
CURLOPT_POST => true, 
CURLOPT_POSTFIELDS => $postData 
//,CURLOPT_FOLLOWLOCATION => true 
)); 


//Ignore SSL certificate verification 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 


//get response 
$output = curl_exec($ch); 

//Print error if any 
if(curl_errno($ch)) 
{ 
echo 'error:' . curl_error($ch); 
} 

curl_close($ch); 

echo round($output); 

}
	
?>