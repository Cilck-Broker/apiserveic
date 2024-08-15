<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script src="{{ asset('include/CSS/pdf.css') }}" defer></script>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }
        @page{
                margin-top: 100px;
                margin-bottom: 50px;
        }
        body {
            /* font-family: Arial, sans-serif; */
            font-family: 'THSarabunNew', sans-serif;
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
            margin: 0 auto;
            /* margin-bottom: 40px; */
        }

        header {
            position: fixed;
            left: 0px;
            right: 0px;
            height: 100px;
            margin-top: -100px;
        }

        footer {
            position: fixed;
            left: 0px;
            right: 0px;
            height: 100px;
            bottom: 0px;
            margin-bottom: -100px;
        }
        main { margin: 0 auto;}
        .mg0 {margin: 0 auto;}
        p{margin: 0 auto;line-height: 20px;}
        span{margin: 0 auto;line-height: 20px;}
        .text-center{text-align: center}
        .fs10{font-size: 10px;}
        .fs11{font-size: 11px;}
        .fs12{font-size: 12px;}
        .fs13{font-size: 13px;}
        .fs14{font-size: 14px;}
        .fs15{font-size: 15px;}
        .fs16{font-size: 16px;}
        .fs17{font-size: 17px;}
        .fs18{font-size: 18px;}
        .fs19{font-size: 19px;}
        .line-height-10 {line-height: 10px }
        .line-height-15 {line-height: 15px;}
        .line-height-20 {line-height: 20px;}
        /* table{border: 1px solid black;} */
        tr,td{border: 1px solid black;}
        .p05 {padding: 0px 5px;}
    </style>
    {{-- <title>{{ $title }}</title> --}}
</head>

<body>
    <header>
        <div style="text-align: center;"><img src="{{ asset('imgweb/logoici.png') }}" style="width:25%; text-align: center;"></div>
    </header>
    <footer class="text-center">
        <h4 class="mg0 line-height-10">บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) | iCare Insurance Public Company Limited</h4>
        <div class="mg0 fs12 line-height-10" >549/1 ชั้นที่ 1 ถนนสรรพาวุธ แขวงบางนาใต้ เขตบางนา กรุงเทพมหานคร (10260) โทร:  <a href="tel:021054689">0-2105-4689</a> Email: <a href="mail:0ici@icare-insurance.com">ici@icare-insurance.com</a></div>
        <div class="mg0 fs12 line-height-10" >549/1 Fl.1 sanphawut road, Khwang Bangna tai, Khet Bangna, Bangkok 10260 โทร:  <a href="tel:+6621054689">+66-2105-4689</a> Email: <a href="mail:0ici@icare-insurance.com">ici@icare-insurance.com</a></div>
    </footer>
    <main class="mg0">
        <div class="content mg0">
            <div class="text-center">
                <H2 class="mg0 line-height-10">ใบเตือนต่ออายุประกันภัย</H2>
                <p class="fs18">“การขยายระยะเวลาการรับประกันสินค้าอิเล็กทรอนิกส์” และ “การรับประกันภัยเสี่ยงภัยทุกชนิด”</p>
            </div>
            <div class="fs16" style="margin-top: 20px;">
                <table style="width: 100%; ">
                    <tr>
                        <td style="width: 40%" class="p05">รายละเอียดผู้เอาประกันภัย</td>
                        <td class="p05">{{ "คุณ ".$customer_firstname . " " . $customer_lastname }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">ยี่ห้อ (Brand)</td>
                        <td class="p05">{{ $mobile_brand }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">รุ่น (Model)</td>
                        <td class="p05">{{ $mobile_model." ".$mobile_memory." ".$mobile_color }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">หมายเลขเครื่อง (IMEI)</td>
                        <td class="p05">{{ $mobile_imei }}
                            <div><span style="color: red; line-height: 15px;">กด *#06# เพื่อตรวจสอบเลข IMEI หากไม่ตรงกัน</span> จะไม่สามารถต่ออายุได้ <br>กรณีมีเคลมเปลี่ยนเครื่องกรุณาแจ้งรายละเอียด IMEI ใหม่อีกครั้ง</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">อาณาเขตที่คุ้มครอง</td>
                        <td class="p05">อาณาเขตประเทศไทย</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">ทุนประกันภัย (Sum Insured)</td>
                        <td class="p05">{{ number_format($second_hand_price,2) }} บาท</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">วันเริ่มคุ้มครอง</td>
                        <td class="p05">{{ $coverage_start }} เวลา 16:30 น.</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">วันสิ้นสุด</td>
                        <td class="p05">{{ $coverage_end }} เวลา 16:30 น.</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">เบี้ยประกันภัย</td>
                        <td class="p05">{{ number_format( $totalpremium,2) }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">ร้านค้าที่ซื้อ(Dealer)</td>
                        <td class="p05">iCare Insurance Renew</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">หมายเลขบัตรประชาชน</td>
                        <td class="p05">{{ $customer_idcard }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">โทรศัพท์มือถือ</td>
                        <td class="p05">{{ $customer_phone }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">E-mail</td>
                        <td class="p05">{{ $customer_email }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">ที่อยู่</td>
                        <td class="p05">{{ $customer_address }}</td>
                    </tr>
                    <tr>
                        <td style="width: 40%" class="p05">
                            <div>การชำระเงิน</div>
                            
                        </td>
                        <td class="p05">
                            {{-- สแกนเพื่อดูรายละเอียดการชําระเงิน --}}
                            <div><b>ตรวจสอบข้อมูลต่ออายุและชำระเงินได้ที่</b></div>
                            <div style="font-size: 16px;"><a href="https://renew7care.icare-insurance.com/{{ base64_encode($orders_id) }} " target="_Bank">https://renew7care.icare-insurance.com/{{ base64_encode($orders_id) }} </a></div>
                            <div>กรุณาชําระเงินค่าเบี้ยประกันต่ออายุ ภายใน {{ $coverage_end }}</div>
                            {{-- <br>ตรวจสอบข้อมูลต่ออายุและชำระเงินได้ที่  --}}
                            <br>สอบถามข้อมูลเพิ่มเติม Line <a href="https://page.line.me/icareinsur" target="_Bank" >@icareinsur </a>หรือโทร: <a href="tel:021148704">021148704</a>
                        </td>
                    </tr>
                </table>
                <br pagebreak="true"/>
                    <div class="fs14 line-height-15">
                        บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) เป็นหนึ่งใน กลุ่มธุรกิจ ของ บริษัท คอมเซเว่น จำกัด (มหาชน) บริษัท ไอแคร์ประกันภัย จำกัด (มหาชน) เรามีความมุ่งมั่น มีความพร้อม ในการให้บริการงานประกันภัยที่ครอบคลุม
                        บริษัท คอมเซเว่น โฮลดิ้ง จำกัด เป็นหนึ่งในผู้ถือหุ้นของ บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน)
                        บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) สำนักงาน ตั้งอยู่ที่ 549/1 ชั้นที่ 1 ถนนสรรพาวุธ แขวงบางนาใต้ เขตบางนา กรุงเทพมหานคร (10260)
                        <br>บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) (ต่อไปนี้เรียกว่า “บริษัท”) เป็นบริษัทประกันวินาศภัย ตระหนัก และให้ความสำคัญ “สิทธิความเป็นส่วนตัว” ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 เพื่อความเชื่อมั่นของผู้ใช้บริการ พนักงานของบริษัทฯ คู่ค้า พันธมิตรทางธุรกิจ บุคคลภายนอก ผู้เกี่ยวข้องทุกภาคส่วน 
                        ว่าจะได้รับการคุ้มครองข้อมูลส่วนบุคคล บริษัทจึงได้กำหนดนโยบาย ข้อมูลส่วนบุคคลทั้งการเก็บรวบรวม การเก็บรักษา การใช้ การเปิดเผย รวมถึงสิทธิต่างๆ ของเจ้าของข้อมูล ทั้งช่องทางปกติ และช่องทางออนไลน์ 
                        เพื่อให้เป็นไปตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ.2562 สามารถดูรายละเอียดเพิ่มเติมได้ที่ <a href="https://www.icare-insurance.com/privacy-policy"> นโยบายส่วนตัว</a>
                    </div>
                    {{-- <img src="{{ asset('imgweb/qricipolicy.png') }}" style="width: 20%"> --}}
                </div>
            </div>
        </div>

        <div style="page-break-after: always;"></div>
            <div class="text-center">
                <img src="{{ asset('imgweb/package7careplus.png') }}" style="width: 100%">
            </div>
            <div class="text-center" style="margin-top: 30px;" >
                <img src="{{ asset('imgweb/botomiselicense.png') }}" style="width: 80%">
            </div>

            
       
    </main>
    {{-- <footer class="text-center">
        <h4 class="mg0 line-height-10">บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) | iCare Insurance Public Company Limited</h4>
        <div class="mg0 fs12 line-height-10" >549/1 ชั้นที่ 1 ถนนสรรพาวุธ แขวงบางนาใต้ เขตบางนา กรุงเทพมหานคร (10260) โทร:  <a href="tel:021054689">0-2105-4689</a> Email: <a href="mail:0ici@icare-insurance.com">ici@icare-insurance.com</a></div>
        <div class="mg0 fs12 line-height-10" >549/1 Fl.1 sanphawut road, Khwang Bangna tai, Khet Bangna, Bangkok 10260 โทร:  <a href="tel:+6621054689">+66-2105-4689</a> Email: <a href="mail:0ici@icare-insurance.com">ici@icare-insurance.com</a></div>
    </footer> --}}

    
</body>

</html>
