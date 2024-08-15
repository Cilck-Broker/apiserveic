if "%PROCESSOR_ARCHITECTURE%"=="x86" goto x86
if "%PROCESSOR_ARCHITECTURE%"=="AMD64" goto x64
exit

:x64
regsvr32  "%~d0%~p0x64\\DynamsoftBarcodeReaderCtrlx64.dll"
regsvr32  "%~d0%~p0x86\\DynamsoftBarcodeReaderCtrlx86.dll"
exit

:x86
regsvr32  "%~d0%~p0x86\\DynamsoftBarcodeReaderCtrlx86.dll"