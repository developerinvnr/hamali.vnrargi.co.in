<?php
 
	require("config.php"); 
	// Main class of task assignment moulde
	class DatabaseConnection {

		private $conn;


		function connect() 
		{

			$this->conn = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
			if (!$this->conn) {
				die("Connection failed: " . mysqli_connect_error());
			}		
			mysqli_set_charset($this->conn,"utf8");			
		}
	
	 function firequery( $query="" ) {
		if ( $query != "" ) {
			return mysqli_query($this->conn, $query);
		}
		else {
			return 0;
		}
	 }	
	 
		/* Get the id of the record that was last inserted */
		function last_inserted_id() {
		return mysqli_insert_id($this->conn);
		}
		/* public: evaluate the result (size, width) */
		function affected_rows() {
		return @mysqli_affected_rows($this->conn);
		}
		
		function num_rows( $result="" ) {
			return mysqli_num_rows($result);
		}
		
		function num_fields() {
		return @mysqli_num_fields($this->conn);
		}
		
		/* public: shorthand notation */
		function nf( $result="") {
		return $this->num_rows($result);
		}
		
		/** 
		*	Function used to Get a result row as an enumerated array 
		*/
		function mysqlFetchRow( $recordSet ) {
		return mysqli_fetch_row( $recordSet );
		}
		// EOF :: MysqlFetchRow

		/** 
		*	Function used to fetching all the data in associative array
		*/
		function mysqlFetchAssoc($recordSet) 
		{
			$data = array() ;

			while( $resultData = mysqli_fetch_assoc($recordSet) )
			{
				$data[] = $resultData ;
			}

			return $data ;
		}
		// EOF :: MysqlFetchAssoc
		
		function GetFinancialYear()
		{
			$cm	=	intval(date('m'));
			if($cm<=3)
			{
				$cy	=	date('Y')-1;
				$ny	=	date('y');
				$financialyear	=$cy."-".$ny;	
			}
			else
			{
				$cy	=	date('Y');
				$ny	=	date('y')+1;
				$financialyear	=$cy."-".$ny;					
			}			
			return $financialyear;
		}

		/** 
		*	Function used to Fetch a result row as an associative array, a numeric array, or both 
		*/
		function mysqlFetchArray($recordSet) 
		{
			$data = array() ;

			while( $resultData = mysqli_fetch_array($recordSet) )
			{
				$data[] = $resultData ;
			}

			return $data ;
		}
		// EOF :: MysqlFetchArray

		/** 
		*	Function used to Fetch a result row as an object  
		*/
		function mysqlFetchObject($recordSet) 
		{
			return $resultData = mysqli_fetch_object($recordSet) ;			   
		}


    	function getField($tn,$field,$cond) {
        $val11=null;
        try {
			if($cond!="")
			{
				$rs = mysqli_query($this->conn,"select ".$field." from ".$tn." where ".$cond);
				while($row_info= mysqli_fetch_assoc($rs)) 
				{
					$val11 = $row_info[$field];
				}
			}
        } catch(Exception $err) {
			echo $err;
        }
        return $val11;
    }
		
		function isRecordExist($tn)
		{
        $flag=0;
        $val	=	0;
        try {
            $rs = mysqli_query($this->conn,$tn);
            while($row_info= mysqli_fetch_assoc($rs)) 
            {
                $val++;
            }
            if($val>=1)
            {
            	$flag=true;
            }
        } catch(Exception $err) {
			echo $err;
        }
        return $flag;
		}
		
		
    	function getFieldSingle($tn,$field) {
        $val8=null;
        try {
            $rs = mysqli_query($this->conn,"select ".$field." from ".$tn."");
            while($row_info= mysql_fetch_assoc($rs)) 
            {
                $val8 = $row_info[$field];
            }
        } catch(Exception $err) {
			echo $err;
        }
        return $val8;
    }
		
		
		
    function getLastRowId($tn,$field,$id) {
        $val1 = null;
        try {
            $rs = mysqli_query($this->conn,"select ".$field." from ".$tn." order by ".$id." desc");
            while($row_inf= mysqli_fetch_assoc($rs)) 
            {
                $val1 = $row_inf[$field];
				break;
            }
        } catch(Exception $err) {
            echo $err;
        }
        return $val1;
    }
		

		function num_row($tn)
		{
        $val2=0;
        try {
            $rs = mysqli_query($this->conn,"select * from ".$tn);
            while($row_info= mysqli_fetch_assoc($rs)) 
            {
                $val2++;
            }
        } catch(Exception $err) {
			echo $err;
        }
        return $val2;
		
		}


		function getLastDay($dat)
		{
        $val2=0;
        try {
            $rs = mysqli_query($this->conn,"select LAST_DAY('".$dat."') as lastday");
            while($row_info= mysqli_fetch_assoc($rs)) 
            {
                $ltday	=	$row_info['lastday'];
            }
        } catch(Exception $err) {
			echo $err;
        }
        return $ltday;
		
		}



    	function DATEDIFF($date1,$date2) {
        $val9=0;
        try {
            $rs9 = mysqli_query($this->conn,"select DATEDIFF('".$date1."','".$date2."')");
            while($row_info9= mysqli_fetch_array($rs9)) 
            {
                $val9 = $row_info9[0];
            }
        } catch(Exception $err) {
			echo $err;
        }
        return $val9;
    }

	function getFieldNames($tablename)
	{
        try 
		{
            $rs_fields = mysqli_query($this->conn,"select * from master_tbl where tablename='".$tablename."' and pk!='YES' and fieldtype!='file' and visibleinform='YES' order by displayorder");
			$r=0;
			$str="";
            while($row_fields= mysqli_fetch_array($rs_fields)) 
            {
				if($r==0)
				{
					$str.=$row_fields['fieldname'];
				}
				else
				{
					$str.=",".$row_fields['fieldname'];				
				}	
				
				$r++;
            }		
			$str.=",creationdate,addedby";

        }
		catch(Exception $err) 
		{
			echo $err;
        }
		unset($rs_fields);
		unset($row_fields);

        return $str;		
	}

	function getFieldValues($tablename,$sessionid)
	{
        try 
		{
            $rs_fields = mysqli_query($this->conn,"select * from master_tbl where tablename='".$tablename."' and pk!='YES' and visibleinform='YES' order by displayorder");
			$r=0;
			$str="";
			$strval="";
            while($row_fields= mysqli_fetch_array($rs_fields)) 
            {
				$r++;
				if($r==1)
				{
					if($row_fields['fielddatatype']=="string")
					{
						$str=$_POST[$row_fields['fieldname']];
						$strval.="'".strtoupper($str)."'";
					}
					else if($row_fields['fielddatatype']=="number")
					{
						$str=doubleval($_POST[$row_fields['fieldname']]);
						$strval.="".$str."";						
					}
					else if($row_fields['fielddatatype']=="date")
					{
						$str=date('Y\-d\-m',strtotime($_POST[$row_fields['fieldname']]));
						$strval.="'".$str."'";
					}
				}
				else
				{
					if($row_fields['fielddatatype']=="string")
					{
						$str=$_POST[$row_fields['fieldname']];
						$strval.=","."'".strtoupper($str)."'";
					}
					else if($row_fields['fielddatatype']=="number")
					{
						$str=doubleval($_POST[$row_fields['fieldname']]);
						$strval.=","."".$str."";
					}
					else if($row_fields['fielddatatype']=="date")
					{
						$str=date('Y\-d\-m',strtotime($_POST[$row_fields['fieldname']]));
						$strval.=","."'".$str."'";
					}
				}	
            }
			$strval.=",'".date('Y\-m\-d H:i:s')."',".$sessionid."";
        }
		catch(Exception $err)
		{
			echo $err;
        }
		unset($rs_fields);
		unset($row_fields);
        return $strval;		
	}

}
?>
