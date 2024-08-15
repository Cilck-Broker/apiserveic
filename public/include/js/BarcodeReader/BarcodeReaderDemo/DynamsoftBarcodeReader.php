<?php
 /*
 *	DynamsoftBarcodeReader.php
 *	Dynamsoft Barcode Reader PHP wrapper file.
 *
 *	Copyright (C) 2019 Dynamsoft Corporation.
 *	All Rights Reserved.
 */
 
class BarcodeFormat
{
	const All = 503317503;
	const OneD = 0x3FF;
	
	const CODE_39 = 0x1;
	const CODE_128 = 0x2;
	const CODE_93 = 0x4;
	const CODABAR = 0x8;
	const ITF = 0x10; 
	const EAN_13 = 0x20;
	const EAN_8 = 0x40;
	const UPC_A = 0x80;
	const UPC_E = 0x100;
	const INDUSTRIAL_25 = 0x200;
	
	const PDF417 = 0x2000000;
	const QR_CODE = 0x4000000;
	const DATAMATRIX = 0x8000000;
	const AZTEC = 0x10000000;
}


class EnumImagePixelFormat
{
	//0:Black = ; 1:White
	const IPF_Binary = 0;	 	
	//0:White = ; 1:Black
	const IPF_BinaryInverted = 1;	
	//8bit gray
	const IPF_GrayScaled = 2;	 
	//NV21
	const IPF_NV21 = 3;	 	
	//16bit
	const IPF_RGB_565 = 4;	 
	//16bit
	const IPF_RGB_555 = 5;	 
	//24bit
	const IPF_RGB_888 = 6;	 
	//32bit
	const IPF_ARGB_8888 = 7;
	//48bit
	const IPF_RGB_161616 = 8;	 
	//64bit
	const IPF_ARGB_16161616 = 9;	 	 
}

	// Describes the extended result type.
class EnumResultType
{
	// Specifies the standard text. This means the barcode value.
	const EDT_StandardText = 0;
	// Specifies the raw text. This means the text that includes start/stop characters = ; check digits = ; etc.
	const EDT_RawText = 1;
	// Specifies all the candidate text. This means all the standard text results decoded from the barcode.
	const EDT_CandidateText = 2;
	// Specifies the partial Text. This means part of the text result decoded from the barcode.
	const EDT_PartialText = 3;
}

	// Describes the stage when the results are returned.
class EnumTerminateStage
{
	// Prelocalized
	const ETS_Prelocalized = 0;
	// Localized
	const ETS_Localized = 1;
	// Recognized
	const ETS_Recognized = 2;
}

class BarcodeInvertMode
{
    const BIM_DarkOnLight = 0;
    const BIM_LightOnDark = 1;
}

class ColourImageConvertMode
{
    const CICM_Auto = 0;
    const CICM_Grayscale = 1;
}

class RegionPredetectionMode
{
    const RPM_Disable = 1;
    const RPM_Enable = 2;
}
	
class TextFilterMode
{
	const TFM_Disable = 1;
	const TFM_Enable = 2;
}

class PublicRuntimeSettings
{
	public $AntiDamageLevel;
	public $BarcodeFormatIds;
	public $BarcodeInvertMode;
	public $BinarizationBlockSize;
	public $ColourImageConvertMode;
	public $DeblurLevel;
	public $EnableFillBinaryVacancy;
	public $ExpectedBarcodesCount;
	public $GrayEqualizationSensitivity;
	public $LocalizationAlgorithmPriority;
	public $MaxAlgorithmThreadCount;
	public $MaxBarcodesCount;
	public $MaxDimOfFullImageAsBarcodeZone;
	public $PDFRasterDPI;
	public $RegionPredetectionMode;
	public $ScaleDownThreshold;
	public $TextFilterMode;
	public $TextureDetectionSensitivity;
	public $Timeout;
	public $Reserved;
	
	function __construct($settings)
	{
		$this->AntiDamageLevel = $settings->AntiDamageLevel;
		$this->BarcodeFormatIds = $settings->BarcodeFormatIds;
		$this->BarcodeInvertMode = $settings->BarcodeInvertMode;
		$this->BinarizationBlockSize = $settings->BinarizationBlockSize;
		$this->ColourImageConvertMode = $settings->ColourImageConvertMode;
		$this->DeblurLevel = $settings->DeblurLevel;
		$this->EnableFillBinaryVacancy = $settings->EnableFillBinaryVacancy;
		$this->ExpectedBarcodesCount = $settings->ExpectedBarcodesCount;
		$this->GrayEqualizationSensitivity = $settings->GrayEqualizationSensitivity;
		$this->LocalizationAlgorithmPriority = $settings->LocalizationAlgorithmPriority;
		$this->MaxAlgorithmThreadCount = $settings->MaxAlgorithmThreadCount;
		$this->MaxBarcodesCount = $settings->MaxBarcodesCount;
		$this->MaxDimOfFullImageAsBarcodeZone = $settings->MaxDimOfFullImageAsBarcodeZone;
		$this->PDFRasterDPI = $settings->PDFRasterDPI;
		$this->RegionPredetectionMode = $settings->RegionPredetectionMode;
		$this->Reserved = $settings->Reserved;
		$this->ScaleDownThreshold = $settings->ScaleDownThreshold;
		$this->TextFilterMode = $settings->TextFilterMode;
		$this->TextureDetectionSensitivity = $settings->TextureDetectionSensitivity;
		$this->Timeout = $settings->Timeout;
	}
}

class ExtendedResult
{
	public $BarcodeFormat;
	public $BarcodeFormatString;
	public $Bytes;
	public $Confidence;
	public $ResultType;
	
	function __construct($result)
	{
		$this->BarcodeFormat = $result->BarcodeFormat;
		$this->BarcodeFormatString = $result->BarcodeFormatString;
		
		$ary = $result->Bytes;
		$count = count($ary);
		$this->Bytes = array();
		for($i = 0 ;$i < $count; $i++)
		{
			array_push($this->Bytes,(int)$ary[$i]);
		}	
		
		$this->Confidence = $result->Confidence;
		$this->ResultType = $result->ResultType;	

	}
}

class LocalizationResult
{
	public $Angle;
	public $BarcodeFormat;
	public $BarcodeFormatString;
	public $DocumentName;
	public $ModuleSize;
	public $PageNumber;
	public $RegionName;
	public $X1;
	public $Y1;
	public $X2;
	public $Y2;
	public $X3;
	public $Y3;
	public $X4;
	public $Y4;
	public $TerminateStage;
	public $ExtendedResultArray;
	
	function __construct($result)
	{
		$this->Angle = $result->Angle;
		$this->BarcodeFormat = $result->BarcodeFormat;
		$this->BarcodeFormatString = $result->BarcodeFormatString;
		$this->DocumentName = $result->DocumentName;
		$this->ModuleSize = $result->ModuleSize;
		$this->PageNumber = $result->PageNumber;
		$this->RegionName = $result->RegionName;
		$this->X1 = $result->ResultPoints[0];
		$this->Y1 = $result->ResultPoints[1];
		$this->X2 = $result->ResultPoints[2];
		$this->Y2 = $result->ResultPoints[3];
		$this->X3 = $result->ResultPoints[4];
		$this->Y3 = $result->ResultPoints[5];
		$this->X4 = $result->ResultPoints[6];
		$this->Y4 = $result->ResultPoints[7];		
		$this->TerminateStage = $result->TerminateStage;
	
		$extendedResultAry = $result->ExtendedResultSet;
		$count = $extendedResultAry->Count;
		$this->ExtendedResultArray = array();
		for($i = 0 ;$i < $count; $i++)
		{
			array_push($this->ExtendedResultArray, new ExtendedResult($extendedResultAry->Item($i)));
		}	
	}
}

class TextResult
{
	public $BarcodeBytes;
	public $BarcodeFormat;
	public $BarcodeFormatString;
	public $BarcodeText;
	public $LocalizationResult;
	
	function __construct($result)
	{
		$ary = $result->BarcodeBytes;
		$count = count($ary);
		$this->BarcodeBytes = array();
		for($i = 0 ;$i < $count; $i++)
		{
			array_push($this->BarcodeBytes,(int)$ary[$i]);
		}
						
		$this->BarcodeFormatString = $result->BarcodeFormatString;
		$this->BarcodeFormat = $result->BarcodeFormat;
		$this->BarcodeText = $result->BarcodeText;
		$this->LocalizationResult = new LocalizationResult($result->LocalizationResult);
	}
}


class BarcodeReader
{
	private $m_reader;
	private $m_settings;
	
	function __construct($key)
	{
		$this->m_reader = new COM("DynamsoftBarcodeReaderCtrl.BarcodeReader") or die("cannot build BarcodeReader com");
		$this->checkInit();
		$this->m_reader->LicenseKeys = $key;
	}
	
	private function checkInit()
	{
		if($this->m_reader == NULL)
			throw new Exception("BarcodeReader:NULL Pointer");
	}
	
	private function BuildResult($resultAry)
	{
		$count = $resultAry->Count;
		$retAry = array();
		for($i = 0 ;$i < $count; $i++)
		{
			array_push($retAry, new TextResult($resultAry->Item($i)));
		}		
		return $retAry;
	}
		
	function decodeFile($strFileName, $strTemplateName)
	{
		$this->checkInit();
		$resultAry = $this->m_reader->DecodeFile($strFileName, $strTemplateName);
		return $this->BuildResult($resultAry);
	}
			
	function decodeBase64String($strBase64, $strTemplateName)
	{
		$this->checkInit();
		$resultAry = $this->m_reader->DecodeBase64String($strBase64, $strTemplateName);
		return $this->BuildResult($resultAry);
	}
	
	function getAllLocalizationResults()
	{
		$this->checkInit();
		$resultAry = $this->m_reader->GetAllLocalizationResults();

		$count = $resultAry->Count;
		$retAry = array();
		for($i = 0 ;$i < $count; $i++)
		{
			array_push($retAry, new LocalizationResult($resultAry->Item($i)));
		}		
		return $retAry;
	}
	
	function getAllParameterTemplateNames()
	{
		$this->checkInit();
		return $this->m_reader->GetAllParameterTemplateNames();
	}
	
	function initRuntimeSettingsWithString($strJsonFileContent, $emSettingPriority)
	{
		$this->checkInit();
		$this->m_reader->InitRuntimeSettingsWithString($strJsonFileContent, $emSettingPriority);
	}
	
	function initRuntimeSettingsWithFile($strJsonFileName, $emSettingPriority)
	{
		$this->checkInit();
		$this->m_reader->InitRuntimeSettingsWithFile($strJsonFileName, $emSettingPriority);
	}
	
	function getRuntimeSettings()
	{
		$this->checkInit();
		$this->m_settings = $this->m_reader->GetRuntimeSettings();
		return new PublicRuntimeSettings($this->m_settings);
	}
	
	function updateRuntimeSettings($settings)
	{
		$this->checkInit();
		
		$this->m_settings->AntiDamageLevel = $settings->AntiDamageLevel;
		$this->m_settings->BarcodeFormatIds = $settings->BarcodeFormatIds;
		$this->m_settings->BarcodeInvertMode = $settings->BarcodeInvertMode;
		$this->m_settings->BinarizationBlockSize = $settings->BinarizationBlockSize;
		$this->m_settings->ColourImageConvertMode = $settings->ColourImageConvertMode;
		$this->m_settings->DeblurLevel = $settings->DeblurLevel;
		$this->m_settings->EnableFillBinaryVacancy = $settings->EnableFillBinaryVacancy;
		$this->m_settings->ExpectedBarcodesCount = $settings->ExpectedBarcodesCount;
		$this->m_settings->GrayEqualizationSensitivity = $settings->GrayEqualizationSensitivity;
		$this->m_settings->LocalizationAlgorithmPriority = $settings->LocalizationAlgorithmPriority;
		$this->m_settings->MaxAlgorithmThreadCount = $settings->MaxAlgorithmThreadCount;
		$this->m_settings->MaxBarcodesCount = $settings->MaxBarcodesCount;
		$this->m_settings->MaxDimOfFullImageAsBarcodeZone = $settings->MaxDimOfFullImageAsBarcodeZone;
		$this->m_settings->PDFRasterDPI = $settings->PDFRasterDPI;
		$this->m_settings->RegionPredetectionMode = $settings->RegionPredetectionMode;
		$this->m_settings->Reserved = $settings->Reserved;
		$this->m_settings->ScaleDownThreshold = $settings->ScaleDownThreshold;
		$this->m_settings->TextFilterMode = $settings->TextFilterMode;
		$this->m_settings->TextureDetectionSensitivity = $settings->TextureDetectionSensitivity;
		$this->m_settings->Timeout = $settings->Timeout;
		
		$this->m_reader->UpdateRuntimeSettings($this->m_settings);
	}	
	
	function resetRuntimeSettings()
	{
		$this->checkInit();
		return $this->m_reader->resetRuntimeSettings();
	}
	
	function appendTplStringToRuntimeSettings($strJsonFileContent, $emSettingPriority)
	{
		$this->checkInit();
		$this->m_reader->AppendTplStringToRuntimeSettings($strJsonFileContent, $emSettingPriority);
	}
	
	function appendTplFileToRuntimeSettings($strJsonFileName, $emSettingPriority)
	{
		$this->checkInit();
		$this->m_reader->AppendTplFileToRuntimeSettings($strJsonFileName, $emSettingPriority);
	}
	
	function outputSettingsToString($strSettingsName)
	{
		$this->checkInit();
		return $this->m_reader->outputSettingsToString($strSettingsName);
	}	
	
	function outputSettingsToFile($strSettingsName, $strFilePath)
	{
		$this->checkInit();
		$this->m_reader->OutputSettingsToFile($strSettingsName, $strFilePath);
	}
	
}

?>