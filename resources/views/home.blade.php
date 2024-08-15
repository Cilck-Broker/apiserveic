@extends('layouts.app')
@section('title','หน้าร้าน')
@section('content')
<!-- @guest
  <script>
    window.location.href = "{{URL::to('/home')}}"
  </script>
@endguest  -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <script src="{{ asset('js/home.js') }}" defer></script>
    

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

                                    <label for="order_code">Orders Code : </label>
                                    <input type="text" class="form-control" id="order_code" name="order_code">

                                </div>
                                <div class="col-md-3">
                                    <label for="phone_no">Phone No. : </label>
                                    <input type="text" class="form-control" id="phone_no" name="phone_no">
                                </div>

                                <div class="col-md-3">
                                    <label for="id_card">ID Card : </label>
                                    <input type="text" class="form-control" id="id_card" name="id_card">
                                </div>

                                <div class="col-md-3">
                                    <label for="email">Email : </label>
                                    <input type="text" class="form-control" id="email" name="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="customer">เริ่ม วันสิ้นสุด : </label>
                                    <!-- <input type="date" class="form-control" id="s_coverage_end" name="s_coverage_end" value="{{ date('Y-m-d') }}"> -->
                                    <input type="date" class="form-control" id="s_coverage_end" name="s_coverage_end" value="">
                                </div>

                                <div class="col-md-3">
                                    <label for="phone_no">ถึง วันสิ้นสุด : </label>
                                    <!-- <input type="date" class="form-control" id="e_coverage_end" name="e_coverage_end" value="{{ date('Y-m-d', strtotime('+7 days')) }}"> -->
                                    <input type="date" class="form-control" id="e_coverage_end" name="e_coverage_end" value="">
                                </div>

                                <div class="col-md-3">
                                    <label for="email">imei : </label>
                                    <input type="text" class="form-control" id="iemi" name="iemi">
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="email">Status : </label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">All Status</option>
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="email">Customer Name : </label>
                                    <input type="text" class="form-control" id="customer" name="customer">
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="email">Agent : </label>
                                    <select class="form-control" id="agent_code" name="agent_code">
                                        <option value="">All</option>
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-success" onclick="getData('Search')">ค้นหา</button>
                                    <button class="btn btn-danger" onclick="location.reload()">ยกเลิกค้นหา</button>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12" >

                                    <table class="table table-hover table-bordered">
                                        <thead class="bg39 fwb cFFFFFF"> 
                                            <tr class="">
                                                <th class="text-center">#</th>
                                                <th class="text-center">order Code</th>
                                                <th class="text-center">Customer name</th>
                                                <th class="text-center">Phone</th>
                                                {{-- <th class="text-center">Address</th> --}}
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Model</th>
                                                <th class="text-center">Coverage End</th>
                                                <th class="text-center">IMEI</th>
                                                <th class="text-center">Agent</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataRow" class="fs14 table-sm"></tbody>
                                    </table>
                                    <div class="" id="paginationLinks"></div>
                                </div>
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
