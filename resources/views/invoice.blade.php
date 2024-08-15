@extends('layouts.app')
@section('title','หน้าร้าน')
@section('content')
<!-- @guest
  <script>
    window.location.href = "{{URL::to('/home')}}"
  </script>
@endguest  -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />


    <script src="{{ asset('js/invoice.js') }}" defer></script>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <div class="container-fluid">

                    <div class="card shadow">
                        <div class="card-body">

                            <div class="form-group row">
                                <div class="col-md-3">


                                    <label for="invoice_code">IDENTIFIER : </label>
                                    <input type="text" class="form-control" id="invoice_code" name="invoice_code">

                                </div>
                                <div class="col-md-3">
                                    <label for="customer">เริ่ม วันสิ้นสุด : </label>
                                    <input type="date" class="form-control" id="s_paid_date" name="s_paid_date" value="">

                                </div>

                                <div class="col-md-3">
                                    <label for="phone_no">ถึง วันสิ้นสุด : </label>

                                    <input type="date" class="form-control" id="e_paid_date" name="e_paid_date" value="">

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 text-center">

                                    <button class="btn btn-info" onclick="exportExcel()">Export</button>
                                    <button class="btn btn-success" onclick="getInvoiceDataList()">ค้นหา</button>

                                    <button class="btn btn-danger" onclick="location.reload()">ยกเลิกค้นหา</button>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12" style="overflow-x: auto;">

                                    <table class="table table-hover table-bordered  fs12">

                                        <thead class="bg39 fwb cFFFFFF">
                                            <tr class="">
                                                <th class="text-center">IDENTIFIER</th>
                                                <th class="text-center">PREFIX</th>
                                                <th class="text-center">FIRST_NAME</th>
                                                <th class="text-center">LAST_NAME</th>
                                                <th class="text-center">Identifier_ID</th>
                                                <th class="text-center">Address</th>
                                                <th class="text-center">BRAND</th>
                                                <th class="text-center">PRODUCT</th>
                                                <th class="text-center">MODEL</th>
                                                <th class="text-center">RAM</th>
                                                <th class="text-center">IMEI</th>
                                                <th class="text-center">SN</th>
                                                <th class="text-center">EFF_DATE</th>
                                                <th class="text-center">EXP_DATE</th>
                                                <th class="text-center">DEVICE_PRICE</th>
                                                <th class="text-center">PREMIUM</th>
                                                <th class="text-center">STAMP</th>
                                                <th class="text-center">VAT</th>
                                                <th class="text-center">GROSSPREMIUM</th>
                                                <th class="text-center">INVOICE_CODE</th>
                                                <th class="text-center">CHANNEL</th>
                                                <th class="text-center"><nobr>Paid Date</nobr></th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataRow" class="fs12 table-sm">


                                        </tbody>
                                    </table>
                                    

                                </div>
                                <div class="" id="paginate"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- <footer class="footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2021</span>
            </div>
        </div>
    </footer> -->

@endsection
