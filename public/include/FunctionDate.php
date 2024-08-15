<?php
class fnDate{
	public function GetDateTimeServer(){
		date_default_timezone_set("Asia/Bangkok");
		return date("Y-m-d H:i:s");
	}

	public function GetDateServer(){
		date_default_timezone_set("Asia/Bangkok");
		return date("Y-m-d");
	}

	public function THDatetoServerDate($THDate){
		$Day = substr($THDate, 0,2);
		$Month = substr($THDate, 3,2);
		$THYear = substr($THDate, 6,4);
		$Year = $THYear - 543;
		$serverDate = $Year.'-'.$Month.'-'.$Day;
		return $serverDate;
	}

	public function KBankDatetoServerDate($Date){
		$Day = substr($Date, 0,2);
		$Month = substr($Date, 3,2);
		$Year = substr($Date, 6,2);
		$Year = '20'.$Year;
		$serverDate = $Year.'-'.$Month.'-'.$Day;
		return $serverDate;
	}

	public function KBankDatetoServerDate2($Date){
		$Day = substr($Date, 0,2);
		$Month = substr($Date, 3,2);
		$Year = substr($Date, 6,4);
		$serverDate = $Year.'-'.$Month.'-'.$Day;
		return $serverDate;
	}

	public function SCBDatetoServerDate($Date){
		$Day = substr($Date, 0,2);
		$Month = substr($Date, 3,2);
		$Year = substr($Date, 6,4);
		$serverDate = $Year.'-'.$Month.'-'.$Day;
		return $serverDate;
	}
}
?>
