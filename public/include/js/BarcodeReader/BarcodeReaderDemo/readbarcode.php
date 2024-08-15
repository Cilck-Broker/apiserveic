<?php
ini_set("display_errors","On");
error_reporting(E_ALL);
include 'dynamsoftbarcodereader.php';

function readBarcode($path, $type) {
	try { 	
		$br = new BarcodeReader('t0068NQAAAC4mNId1VskUE/etL2oX31VUYbHPZuPThJDUThFdFxhmhvlbbybt5RwhrW0q3eIBTSrR52zDmMgJbTNkj+AY+KE=');
	} catch (exception $exp) { 	  
		echo $exp->getMessage() . '<br/>';
		echo '<p> Your barcode reader component is not registerd correctly, please refer to ReadMe.txt for details.</p>';
		exit;
	}
    
	try {  
		$tpSettings = $br->getRuntimeSettings('');
		$tpSettings->BarcodeFormatIds = $type;
		$br->updateRuntimeSettings($tpSettings);  
		$resultAry = $br->decodeFile($path, '');	
		$cnt = count($resultAry);
		if($cnt > 0) {
			echo '<p>Total barcode(s) found:' . $cnt . '.</p><br/>';
			for ($i = 0; $i < $cnt; $i++) {
				$result = $resultAry[$i];
				echo '<p>Barcode ' . ($i+1) . ':</p>';
				echo "<p>Type: $result->BarcodeFormatString</p>";
				echo "<p>Value: $result->BarcodeText </p><br/>"; 
			}
		}
		else {
			echo '<p>No barcodes found.</p>';
		}	
	} catch(Exception $exp) {
		echo '<p>' . $exp->getMessage() . '</p>';
		exit;
	}

}

function imagefromURL($image,$rename){
	$ch = curl_init($image);	
	$timeout = 5;
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
	$rawdata=curl_exec ($ch);
	
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($code > 200) {
		curl_close($ch);
		return FALSE;
	}
	
	curl_close ($ch);
		
	$fp = fopen($rename,'wb');
	fwrite($fp, $rawdata); 
	fclose($fp);

	return TRUE;
}
	
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = intval($val);
    switch($last) {

        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

$post_max_size = ini_get("post_max_size");
$maxsize = return_bytes($post_max_size);

if($_SERVER['CONTENT_LENGTH'] > $maxsize) {
	echo "Post data size is bigger than " . $post_max_size;
	exit;
}

if(!array_key_exists("uploadFlag", $_POST))	{
		echo "The input file is not specificed.";
		exit;
}

$flag = (int)$_POST["uploadFlag"];
$btype = (int)$_POST["barcodetype"];

// get current working directory
$root = getcwd();
// tmp dir for receiving uploaded barcode images
$tmpDir = $root . "/uploads/";
if (!file_exists($tmpDir)) {
	mkdir($tmpDir);
}

if ($flag) {
	if(!empty($_FILES["fileToUpload"]["tmp_name"]))	{
		$file = basename($_FILES["fileToUpload"]["tmp_name"]);
		$tmpname = date("Y_m_d_H_i_s_") . rand()%1000;
		
		if ($file != NULL && $file != "") {
			$target_file = $tmpDir . $tmpname;
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {     
				readBarcode($target_file, $btype);					
			  unlink($target_file);
			} else {
				echo "Fail to upload file.";
			}
		} else {
		  echo "Fail to upload file.";
		}
	}
	else {
		echo "Fail to upload file.";
	}
} else {	
	if (!empty($_POST["fileToDownload"]) && $_POST["fileToDownload"] != "") {
		$url_file = $_POST["fileToDownload"];
		$tmpname = date("Y_m_d_H_i_s_") . rand()%1000;
		$target_file = $tmpDir . $tmpname;	
		
		if( imagefromURL($url_file, $target_file)) {
			readBarcode($target_file, $btype);		
			unlink($target_file);
		} else {
			echo "Fail to download file.";
		}
	} else {
		echo "Fail to download file.";
	}
}

?>
