----------------------------------------
Dynamsoft Barcode Reader PHP Demo ReadMe
----------------------------------------

Introduction
This sample demonstrates how to load images from client side or download images from remote side, and transfer to server side to do barcode reading in a PHP project.

INSTALL the sample for WampServer:
1. Extract the zip and copy the "BarcodeReaderDemo" directory to "[WampServer root path]\www".
2. Enter the directory "[WampServer root path]\www\BarcodeReaderDemo\ActiveX", run register.bat as Administrator.
3. Enter the php root path and edit php.ini to add the following line:
   extension=php_com_dotnet.dll
4. Start WampServer, navigate http://[ip]:[port]/BarcodeReaderDemo/index.php to verify.

INSTALL the sample for IIS
1. Extract the zip and copy the "BarcodeReaderDemo" directory to "[inetpub root path]\wwwroot".
2. Enter the directory "[inetpub root path]\wwwroot\BarcodeReaderDemo\ActiveX", run register.bat as Administrator.
3. Enter the php root path and edit php.ini to add the following line:
   extension=php_com_dotnet.dll
4. Start IIS, navigate http://[ip]:[port]/BarcodeReaderDemo/index.php to verify.

How to extend your trial license:
1. Open the "Get Your Trial License Now" to retrieve a trial license.
2. Find string "$br = new BarcodeReader()" in the file "readbarcode.php" and replace with the new key.

If you run into any issues, please feel free to contact us at support@dynamsoft.com.

Copyright (C) Dynamsoft Corporation 2019.  All rights reserved.