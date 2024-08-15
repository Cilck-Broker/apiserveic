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
    <p>บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) ขอนำส่งใบยืนยันการรับประกันโทรศัพท์มือถือ</p>
    <p>รุ่น {{ $mobileDetails }}</p>
    <p style="font-size: 18;"><a href="{{ asset('conditioninsurmobile.pdf') }}">รายละเอียดและเงื่อนไขความคุ้มครองตามเอกสารแนบ</a></p>
    <p>สอบถามข้อมูลเพิ่มเติม ติดต่อ Email: renew7care@icare-insurance.com หรือโทร : 021148704</p>
</body>

</html>

