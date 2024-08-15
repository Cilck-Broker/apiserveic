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

        @page {
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

        main {
            margin: 0 auto;
        }

        .mg0 {
            margin: 0 auto;
        }

        p {
            margin: 0 auto;
            line-height: 20px;
        }

        span {
            margin: 0 auto;
            line-height: 20px;
        }

        .text-center {
            text-align: center
        }

        .fs10 {
            font-size: 10px;
        }

        .fs11 {
            font-size: 11px;
        }

        .fs12 {
            font-size: 12px;
        }

        .fs13 {
            font-size: 13px;
        }

        .fs14 {
            font-size: 14px;
        }

        .fs15 {
            font-size: 15px;
        }

        .fs16 {
            font-size: 16px;
        }

        .fs17 {
            font-size: 17px;
        }

        .fs18 {
            font-size: 18px;
        }

        .fs19 {
            font-size: 19px;
        }

        .line-height-10 {
            line-height: 10px
        }

        .line-height-15 {
            line-height: 15px;
        }

        .line-height-20 {
            line-height: 20px;
        }

        /* table{border: 1px solid black;} */
        tr,
        td {
            border: 1px solid black;
        }

        .p05 {
            padding: 0px 5px;
        }

        .watermark {
            position: fixed;
            top: 45%;
            transform: rotate(-50deg);
            opacity: 0.3;
            font-size: 50px;
        }
    </style>
    {{-- {{$coverage_end}} --}}
    {{-- <title>{{ $title }}</title> --}}
</head>

<body>
    <header>
        <div style="text-align: center;"><img src="{{ asset('imgweb/logoici.png') }}"
                style="width:25%; text-align: center;"></div>
    </header>
    <footer class="text-center">
        <h4 class="mg0 line-height-10">บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) | iCare Insurance Public Company Limited
        </h4>
        <div class="mg0 fs12 line-height-10">549/1 ชั้นที่ 1 ถนนสรรพาวุธ แขวงบางนาใต้ เขตบางนา กรุงเทพมหานคร (10260)
            โทร: <a href="tel:021054689">0-2105-4689</a> Email: <a
                href="mail:0ici@icare-insurance.com">ici@icare-insurance.com</a></div>
        <div class="mg0 fs12 line-height-10">549/1 Fl.1 sanphawut road, Khwang Bangna tai, Khet Bangna, Bangkok 10260
            โทร: <a href="tel:+6621054689">+66-2105-4689</a> Email: <a
                href="mail:0ici@icare-insurance.com">ici@icare-insurance.com</a></div>
    </footer>
    {{-- <div class="watermark">iCare Insurance Public Company Limited</div> --}}
    <main class="mg0">
        <div class="content mg0">
            <div class="text-center">
                <H2 class="mg0 line-height-10">ใบรับสิทธิประโยชน์การรับประกันภัย</H2>
            </div>
            <div style="margin-top: 10px;" class="mg0 line-height-10">
                <div>เรียนท่านผู้มีอุปการะคุณ</div>
                <div>บริษัท ไอแคร์ประกันภัย จํากัด (มหาชน) บริษัท “ผู้รับประกันภัย”
                    ขอแจ้งให้ทราบว่าทรัพย์สินดังรายละเอียดที่ปรากฏนี้</div>
                <div>เป็นทรัพย์สินที่ได้รับประกันภัยโดยบริษัท</div>
            </div>
            <div class="fs16" style="margin-top: 10px;">
                <h3>รายละเอียดทรัพย์สิน (อุปกรณ์และส่วนควบไม่คุ้มครอง) :</h5>
                    <table style="width: 100%; ">
                        <tr>
                            <td style="width: 40%" class="p05">ใบรับสิทธิประกัน </td>
                            <td class="p05 fwb fs18">
                                <b>{{ $invoice_number }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">ยี่ห้อ (Brand) : / รุ่น (Model) :</td>
                            <td class="p05">
                               <b>{{ $mobile_brand . ' ' . $mobile_model . ' ' . $mobile_memory . ' ' . $mobile_color }}</b> 
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">หมายเลขเครื่อง (IMEI)</td>
                            <td class="p05"><b>{{ $mobile_imei }}</b></td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">ระยะเวลาเอาประกันภัย (Period of Insurance) :</td>
                            <td class="p05">
                                <b>เริ่มต้น : {{ $renews_start_date }} เวลา 16:30 น. สิ้นสุด
                                {{ $renews_end_date }} เวลา 16:30 น. เวลา 16:30 น.</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">ร้านค้าที่ซื้อสินค้า (Authorized Dealer) :</td>
                            <td class="p05"><b>iCare Insurance Renew</b></td>
                        </tr>
                    </table>
                    <h3 style="margin-top: 15px;">รายละเอียดข้อมูลผู้ซื้อ :</h3>
                    <table style="width: 100%; margin-top: 5px;">
                        <tr>
                            <td style="width: 40%" class="p05">ชื่อ (Name) :</td>
                            <td class="p05"><b>{{ 'คุณ ' . $customer_firstname . ' ' . $customer_lastname }}</b></td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">หมายเลขบัตรประชาชน</td>
                            <td class="p05"><b>{{ $customer_idcard }} </b></td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">E-mail</td>
                            <td class="p05"><b>{{ $customer_email }}</b></td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">ที่อยู่</td>
                            <td class="p05"><b>{{ $customer_address }}</b></td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">โทรศัพท์มือถือ</td>
                            <td class="p05"><b>{{ $customer_phone }}</b></td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">อาณาเขตที่คุ้มครอง</td>
                            <td class="p05"><b>อาณาเขตประเทศไทย</b></td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">ทุนประกันภัย (Sum Insured)</td>
                            <td class="p05"><b>{{ number_format($second_hand_price, 2) }} บาท</b></td>
                        </tr>
                        <tr>
                            <td style="width: 40%" class="p05">Package</td>
                            <td class="p05">
                                <b>
                                    <div>{{ $package_name }}</div>
                                    <div>{{ "เบี้ยสุทธิ์: ".number_format($package_netpremium,2)." อากร: ".number_format($package_duty,2)." ภาษี: ".number_format($package_tax,2)." เบี้ยรวม: ".number_format($package_premiumafterdisc,2) }} บาท</div>
                                </b>
                            </td>
                        </tr>
                    </table>
                    <div class="fs16 line-height-10" style="color: red">
                        เงื่อนไขบังคับก่อน
                        <ul>
                            <li>
                                1. ไมว่ากรณีใด การจ่ายค่าสินไหมทดแทนของบริษัทจะเป็นไปเพื่อ คุณสมบัติที่เทียบเท่า
                                หรือใกล้เคียงกับสินค้าเดิมของผู้เอาประกันภัย
                                กรณีบริษัทไม่สามารถหาสิ่งทดแทนที่มีคุณสมบัติเทียบเท่า
                                หรือใกล้เคียงกับสินค้าของผู้เอาประกันภัยได้บริษัทจะชดเชยเป็นเงิน
                                และไม่ว่ากรณีใดจะต้องไม่เกินกว่าวงเงินสูงสุดของทุนประกันภัยให้แก่ผู้เอาประกันภัยเท่านั้น
                            </li>
                            <li>
                                2. จากกรณขี้อ 1 สามารถแจ้งความเสียหายได้2 ครั้ง ดังต่อไปนี้
                                <ul>
                                    <li>2.1. ความเสียหายที่ เกิดจากการใช้งานปกติ 1 ครั้ง (หมวดที่ 1)</li>
                                    <li>2.2. ความเสียหายที่ เกิดจากอุบัติเหตุ 1 ครั้ง (หมวดที่ 2)</li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                    <div class="fs14 line-height-10">
                        บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) (ต่อไปนี้เรียกว่า “บริษัท”) เป็นบริษัทประกันวินาศภัย
                        ตระหนัก และให้ความสำคัญ “สิทธิความเป็นส่วนตัว” ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562
                        เพื่อความเชื่อมั่นของผู้ใช้บริการ พนักงานของบริษัทฯ คู่ค้า พันธมิตรทางธุรกิจ บุคคลภายนอก
                        ผู้เกี่ยวข้องทุกภาคส่วน
                        ว่าจะได้รับการคุ้มครองข้อมูลส่วนบุคคล บริษัทจึงได้กำหนดนโยบาย ข้อมูลส่วนบุคคลทั้งการเก็บรวบรวม
                        การเก็บรักษา การใช้ การเปิดเผย รวมถึงสิทธิต่างๆ ของเจ้าของข้อมูล ทั้งช่องทางปกติ
                        และช่องทางออนไลน์
                        เพื่อให้เป็นไปตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ.2562 สามารถดูรายละเอียดเพิ่มเติมได้ที่
                        <a href="https://www.icare-insurance.com/privacy-policy"> นโยบายส่วนตัว</a>
                    </div>
                    {{-- <img src="{{ asset('imgweb/qricipolicy.png') }}" style="width: 20%"> --}}
            </div>
        </div>
        </div>

        {{-- <div style="page-break-after: always;"></div>
            <div class="text-center">
                <img src="{{ asset('imgweb/package7careplus.png') }}" style="width: 100%">
            </div>
            <div class="text-center" style="margin-top: 30px;" >
                <img src="{{ asset('imgweb/botomiselicense.png') }}" style="width: 80%">
            </div> --}}



    </main>
    {{-- <footer class="text-center">
        <h4 class="mg0 line-height-10">บริษัท ไอแคร์ ประกันภัย จำกัด (มหาชน) | iCare Insurance Public Company Limited</h4>
        <div class="mg0 fs12 line-height-10" >549/1 ชั้นที่ 1 ถนนสรรพาวุธ แขวงบางนาใต้ เขตบางนา กรุงเทพมหานคร (10260) โทร:  <a href="tel:021054689">0-2105-4689</a> Email: <a href="mail:0ici@icare-insurance.com">ici@icare-insurance.com</a></div>
        <div class="mg0 fs12 line-height-10" >549/1 Fl.1 sanphawut road, Khwang Bangna tai, Khet Bangna, Bangkok 10260 โทร:  <a href="tel:+6621054689">+66-2105-4689</a> Email: <a href="mail:0ici@icare-insurance.com">ici@icare-insurance.com</a></div>
    </footer> --}}


</body>

</html>
