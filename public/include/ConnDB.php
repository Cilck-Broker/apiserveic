<?php    
Class db
{
	public function query($sql)
	{
		$objConnect = mysqli_connect("localhost","root","","bank_api_data");
                
                    mysqli_query($objConnect, "SET character_set_results=utf8");
                    mysqli_query($objConnect, "SET character_set_client='utf8'");
                    mysqli_query($objConnect, "SET character_set_connection='utf8'");
                    mysqli_query($objConnect, "collation_connection = utf8_unicode_ci");
                    mysqli_query($objConnect, "collation_database = utf8_unicode_ci");
                    mysqli_query($objConnect, "collation_server = utf8_unicode_ci");
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$obj = mysqli_query($objConnect, $sql); //or die ( $sql->error );
                mysqli_close($objConnect);
		return $obj;
	}
	
	public static function fetch_array($query)
	{
		$_systemResult = mysqli_fetch_array($query);
		return $_systemResult;
	}	
	
	public static function num_rows($query){
		$res = mysqli_num_rows($query);
		return $res;
	}
	
	public function GetLastID(){
		$objConnect = mysqli_connect("localhost","bank","bankp@ssw0rd","pvdata");
		$last_id = mysqli_insert_id($objConnect);
		return $last_id;
	}
	
	public function GetTableFieldName($DBName, $TBName){
		$str = "SELECT	COLUMN_NAME 
				FROM 	INFORMATION_SCHEMA.COLUMNS 
				WHERE 	TABLE_SCHEMA = '".$DBName."' AND TABLE_NAME = '".$TBName."';";
		return 	self::query($str);		
	}
	
	public function GetTableName($DBName){
		$str = "SELECT table_name FROM information_schema.tables
				WHERE table_schema = '".$DBName."';";
		return 	self::query($str);
	}
}    