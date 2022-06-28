<?php
	class DatabaseConnection {

		private $link;


		function connect() 
		{


			$this->conn = mysqli_connect("localhost", "hamaliDb","hamaliuser","hamali@192");
			if (!$this->conn) {
				die("Connection failed: " . mysqli_connect_error());
			}		

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
		return $this->Last_ID ;
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
            $rs = mysqli_query($this->conn,"select ".$field." from ".$tn." where ".$cond);
            while($row_info= mysqli_fetch_assoc($rs)) 
            {
                $val11 = $row_info[$field];
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


}
?>
