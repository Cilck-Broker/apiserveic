<html>
<header>
<title>pdf</title>
<meta http-equiv=”Content-Language” content=”th” />
<meta http-equiv=”Content-Type” content=”text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Sarabun', sans-serif;
        font-size: 20px;
    }
    .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 40px;
        }

        .footer {
            text-align: center;
        }
    </style>
<body>
    <p>เรียน: {{ $recipientName }}</p>
    <p>E-mail: {{ $recipientEmail }}</p>
    <p>บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) ขอนำส่งใบเตือนต่ออายุประกันมือถือ รุ่น {{ $mobileDetails }}</p>
    <p>กรุณาตรวจสอบรายละเอียดตามเอกสารแนบ</p>
    <p>ต่ออายุประกันมือถือ 7 Care Plus รายละเอียดการชำระเงิน</p>
    <p>การชำระเงินผ่านระบบ 'บริษัท โอมิเซะ จำกัด' ซึ่งได้รับการขึ้นทะเบียนให้ประกอบธุรกิจ ภายใต้การกำกับของธนาคารแห่งประเทศไทย รายละเอียดตามเอกสารแนบ</p>
    {{-- <p>กรุณาชำระเงินค่าเบี้ยประกันต่ออายุ ภายในวันที่ {{ $coverageEnd }}</p> --}}
    <p style="font-size: 18px; color: #2d34ff">กรุณาชำระเงินค่าเบี้ยประกันต่ออายุ ภายในวันที่ {{ $coverageEnd }} เวลา 16:30 </p>
    <p style="font-size: 18px; color: #c72dff"> <a href="https://renew7care.icare-insurance.com/{{ base64_encode($orderId) }}"> >> ตรวจสอบข้อมูลการต่ออายุ << </a></p> 
    <p>สอบถามข้อมูลเพิ่มเติม ติดต่อ Email: renew7care@icare-insurance.com หรือโทร : 021148704 และ Line <a href="https://page.line.me/icareinsur" target="_Bank" style="font-size: 16px;">@icareinsur </a></p>
    <div style="margin-top: 30px;"><img src="{{ asset('imgweb/logoici.png') }}" style="width: 15%; position: fixed; bottom: 0;"></div>
</body>

</html>

