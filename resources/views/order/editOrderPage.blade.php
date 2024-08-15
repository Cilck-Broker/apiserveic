@extends('layouts.app')
@section('title', 'หน้าร้าน')
@section('content')

    <style>
        label {
            font-size: 12px;
        }
    </style>

    <input type="hidden" name="orders_id" id="orders_id" value="{{ $orders->orders_id }}" />
    <script src="{{ asset('js/order/editOrderPage.js') }}" defer></script>
    <script>
        $('[data-magnify]').magnify({
            headerToolbar: [
                'minimize',
                'maximize',
                'close'
            ],
            footerToolbar: [
                'prev',
                'next',
                'zoomIn',
                'zoomOut',
                'fullscreen',
                'actualSize',
                'rotateLeft',
                'rotateRight',
                'myCustomButton'
            ],
            customButtons: {
                myCustomButton: {
                text: 'custom!',
                title: 'custom!',
                click: function (context, e) {
                    alert('clicked the custom button!');
                }
                }
            },
            modalWidth: 400,
            modalHeight: 400,
            callbacks: {
                beforeChange: function (context, index) {
                console.log(context, index)
                },
                changed: function (context, index) {
                console.log(context, index)
                }
            }
        });
    </script>
    <?php
        $discount = 0;
        $totalamount = $orders->totalpremium - $discount;
    ?>

    <div id="wrapper">
        
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-body">

                                    <form action="post" name="CustomerData" id="CustomerData">
                                        <input type="hidden">
                                        <div class="form-group row fs14">
                                            <div class="col-md-6">
                                                <label for="orders_code" class="">Order Code : </label>
                                                <input type="text" class="form-control InputEditClick" id="orders_code"
                                                    name="orders_code" value="{{ $orders->orders_code }}" readonly=true>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="customer_idcard">ID Card : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="customer_idcard" name="customer_idcard"
                                                    value="{{ $orders->customer_idcard }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="customer_firstname" class="">Frist Name : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="customer_firstname" name="customer_firstname"
                                                    value="{{ $orders->customer_firstname }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="customer_lastname">Last Name : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="customer_lastname" name="customer_lastname"
                                                    value="{{ $orders->customer_lastname }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="customer_email">Email : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="customer_email" name="customer_email"
                                                    value="{{ $orders->customer_email }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="customer_phone">Phone Number : <a href="tel:{{ $orders->customer_phone }}">{{ $orders->customer_phone }}</a> </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="customer_phone" name="customer_phone"
                                                    value="{{ $orders->customer_phone }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="branch">Branch : </label>
                                                <input name="branch" id="branch" class="form-control InputEditClick"
                                                    readonly=true value="{{ $orders->branch }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="agent_code">Agent Code : </label>
                                                <input type="text" class="form-control InputEditClick" id="agent_code"
                                                    name="agent_code" readonly=true value="{{ $orders->agent_code }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="status">Status : </label>
                                                <input type="text" class="form-control InputEditClick" id="status"
                                                    name="status" readonly=true value="{{ $orders->orders_status_desc }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="customer_address">Address : </label>
                                                <textarea name="customer_address" id="customer_address" rows="3" class="form-control InputEditClick">
                                                    {{ preg_replace('/\s+/', ' ', $orders->customer_address) }}
                                                </textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <H5 for="customer" class=" fwb">Mobile Detail</H5>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="mobile_brand">Brand : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="mobile_brand" name="mobile_brand"
                                                    value="{{ $orders->mobile_brand }}" readonly=true>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="mobile_model">Model : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="mobile_model" name="mobile_model"
                                                    value="{{ $orders->mobile_model }}" readonly=true>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="mobile_memory">Memory : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="mobile_memory" name="mobile_memory"
                                                    value="{{ $orders->mobile_memory }}" readonly=true>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="mobile_color">Color : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="mobile_color" name="mobile_color"
                                                    value="{{ $orders->mobile_color }}" readonly=true>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="mobile_imei">IMEI : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="mobile_imei" name="mobile_imei"
                                                    value="{{ $orders->mobile_imei }}" >
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Coverage : </label>
                                                <input type="text" class="form-control InputEditClick"
                                                    id="" name=""
                                                    value="{{ $orders->coverage_start . ' | ' . $orders->coverage_end }}"
                                                    readonly=true>
                                            </div>
                                        </div>
                                        <div class="row mt20">
                                            <div class="col-md-12 text-center">
                                                <button type="button" class="btn btn-outline-primary" id="insertOrders"
                                                    onclick="saveCustomerData()">Save Customer Data</button>
                                                <button type="button" class="btn btn-outline-info" id="sendEmailRenew "
                                                    onclick="sendEmailRenew({{ $orders->orders_id }})">Email
                                                    Renew</button>
                                                @if($orders->status != 'S0005' && $orders->status != 'S0004' && $c_invoice == 0 )

                                                    <button type="button" class="btn btn-outline-success"
                                                        id="sendingEmailComfirm "
                                                        onclick="sendInvice({{ $orders->orders_id }})" data-toggle="modal"
                                                        data-target="#ComfirmInvoice">
                                                        Invoice
                                                    </button>
                                                @endif

                                                {{-- <button type="button" class="btn btn-outline-info"
                                                    id="sendingEmailComfirm "
                                                    onclick="sendEmailComfirm({{ $orders->orders_id }})">Email
                                                    Comfirm</button> --}}
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="closeTab()">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="mt10">
                                    <div class="col-md-12 table-responsive">
                                        <h3>File</h3> 
                                        
                                        <table class="table table-hover table-bordered fs12">
                                            <thead class="bgf6aaaa">
                                                <tr class="text-center">
                                                    <td style="width: 5%;">
                                                        <button class="btn c3ca512 fs16" id="sendingEmailComfirm " onclick="uploadFile({{ $orders->orders_id }})" data-toggle="modal" data-target="#uploadFile" >
                                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                                        </button>
                                                    </td>
                                                    <td style="width: 15%;">File Type</td>
                                                    <td>File</td>  
                                                    <td>Note</td>
                                                    <td style="width: 15%;">Create Date</td>
                                                    <td style="width: 15%;">Create By</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($ordersfile as $item)
                                                    <tr>
                                                        <td class="text-center">
                                                            {{-- @if($item->invoice_status == "001") --}}
                                                                <a class="" href="#" onclick="CancelFile('{{ $item->orders_file_id }}')" title="Delete" style="cursor: pointer; color: #ff0000;"><i class="fa-solid fa-trash"></i></a>
                                                            {{-- @endif --}}
                                                        </td>
                                                        <td class="text-center"> {{$item->orders_file_type }}</td>
                                                        <td class="text-center">
                                                            <a data-magnify="gallery" href="{{ asset($item->orders_file_path . '/' . $item->orders_file_name) }}">
                                                                <img src="{{ asset($item->orders_file_path . '/' . $item->orders_file_name) }}" style="width:150px" alt="">
                                                            </a>
                                                        </td>
                                                        <td>{{$item->orders_file_note }}</td>
                                                        <td class="text-center">{{$item->create_date }}</td>
                                                        <td class="text-center">{{$item->create_by }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class=" mt20">
                                    <div class="col-md-12 table-responsive">
                                        <h3>Invoivce</h3>
                                        <table class="table table-hover table-bordered fs12">
                                            <thead class="bgf6aaaa">
                                                <tr class="text-center">
                                                    <td>#</td>
                                                    <td><i class="fa-solid fa-credit-card"></i></td>
                                                    <td>Number</td>
                                                    <td>Converage Date</td>
                                                    <td>Package</td>
                                                    <td>Payement</td>
                                                    <td>Status</td>
                                                    <td>Expiration Date of Invoice</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($invoice as $item)
                                                    <tr>
                                                        <td class="text-center">
                                                            <div class="fs16">
                                                                @if($item->invoice_status == "001")
                                                                    <a class="" href="#" onclick="CancelInvoice('{{ $item->invoice_id }}', '{{ $item->invoice_number }}')" title="Delete" style="cursor: pointer; color: #ff0000;"><i class="fa-solid fa-trash"></i></a>
                                                                @endif
                                                                @if($item->invoice_status == "002")
                                                                    <a class="" href="#" onclick="sendEmailComfirm({{ $item->invoice_id }})" title="Email Comtirm" style="cursor: pointer; color: #001aff;"><i class="fa-solid fa-envelope"></i></a> 
                                                                @endif
                                                            </div>    
                                                        </td>
                                                        <td>
                                                            <div class="text-center fs16">
                                                                <?php if(env('APP_V') == 'stg'): ?>
                                                                    <a href="https://clickbroker.co.th/iCarePaystg/{{ base64_encode($orders->orders_id) }}" target="_blank" title="Payment Page"><i class="fa-solid fa-credit-card"></i></a>
                                                                <?php else: ?>
                                                                    <a href="https://renew7care.icare-insurance.com/{{ base64_encode($orders->orders_id) }}" target="_blank" title="Payment Page"><i class="fa-solid fa-credit-card"></i></a>
                                                                <?php endif; ?>
                                                                
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>{{ $item->invoice_number}}</div>
                                                            <div>{{ $item->invoice_date }}</div>
                                                        </td>
                                                        <td>
                                                            <div>{{ $item->renews_start_date}}</div>
                                                            <div>{{ $item->renews_end_date }}</div>
                                                        </td>
                                                        <td>
                                                            <div> {{ $item->package_name}}</div>
                                                            <div> {{ number_format($item->package_netpremium,2)."|".number_format($item->package_duty,2)."|".number_format($item->package_tax,2)."|".number_format($item->package_totalpremium,2)."|".number_format($item->package_discount,2) }}</div>
                                                            <div> {{ number_format($item->package_premiumafterdisc,2) }}</div>
                                                        </td>
                                                        <td>
                                                            <div>{{ $item->renews_start_date}}</div>
                                                            <div>{{ $item->renews_end_date }}</div>
                                                        </td>
                                                        <td>{{ $item->invoice_status_desc}}</td>
                                                        <td>{{ $item->payment_expire}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row card shadow">
                                <div class="card-body">
                                    <div class="">
                                        <div class="p5">
                                            <h5>Follow Up</h5>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 scrollable-box ">
                                                    <div id="boxFollowupShow"></div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Follow Date : </label>
                                                            <input type="date" name="orders_followup_date"
                                                                id="orders_followup_date" class="form-control "
                                                                value="{{ date('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Follow Title : </label>
                                                            <select name="orders_followup_key" id="orders_followup_key"
                                                                class="form-control" required>
                                                                <option value=""> Select Follow Title</option>
                                                                @foreach ($followup as $key => $value)
                                                                    <option value="{{ $value->follow_up_key }}">
                                                                        {{ $value->follow_up_desc }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mt10">
                                                        <div class="col-md-12">
                                                            <label>Comment : </label>
                                                            <input type="text" name="orders_followup_comment"
                                                                id="orders_followup_comment" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="row mt10">
                                                        <div class="col-md-12">
                                                            <label>Remind Date: </label>
                                                            <input type="datetime-local"
                                                                name="orders_followup_remind_date"
                                                                id="orders_followup_remind_date" class="form-control "
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="row mt10">
                                                        <div class="col-md-12">
                                                            <label>Remand Note : </label>
                                                            <input type="text" name="orders_followup_remind_note"
                                                                id="orders_followup_remind_note" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="row mt20">
                                                        <div class="col-md-12 text-center">
                                                            <button type="button" class="btn btn-outline-success"
                                                                id="insertfollowup" onclick="insertFollowup()">Save
                                                                FollowUp</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row card shadow mt20">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-12">
                                            <div>
                                                <div>
                                                    <h5>{{ $orders->package_name }}</h5>
                                                </div>
                                                <div class="c9a9a9a ">
                                                    {{ $orders->package_code . ' | ' . $orders->package_name . ' | ' . $orders->mobile_brand . ' | ' . $orders->mobile_model . ' | ' . $orders->mobile_memory }}
                                                </div>
                                                <div class="c9a9a9a ">
                                                    ราคามือสอง : {{ number_format($orders->second_hand_price) }} บาท
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="row fs14">
                                                <div class="col-md-6 col-sm-6 text-right">เบี้ยสุทธิ:</div>
                                                <div class="col-md-6 col-sm-6 text-right">
                                                    <input type="hidden" class="form-control InputEditClick"
                                                        id="txt_netpremium" name="netpremium"
                                                        value="{{ $orders->netpremium }}" readonly=true>
                                                    {{ number_format($orders->netpremium, 2) }}
                                                </div>
                                            </div>
                                            <div class="row c9a9a9a fs12">
                                                <div class="col-md-6 col-sm-6 text-right">Duty :</div>
                                                <div class="col-md-6 col-sm-6 text-right">
                                                    <input type="hidden" class="form-control InputEditClick"
                                                        id="txt_duty" name="duty" value="{{ $orders->duty }}"
                                                        readonly=true>
                                                    {{ number_format($orders->duty, 2) }}
                                                </div>
                                            </div>
                                            <div class="row c9a9a9a fs12">
                                                <div class="col-md-6 col-sm-6 text-right">Tax :</div>
                                                <div class="col-md-6 col-sm-6 text-right ">
                                                    <input type="hidden" class="form-control InputEditClick"
                                                        id="txt_tax" name="txt_tax" value="{{ $orders->tax }}"
                                                        readonly=true>
                                                    {{ number_format($orders->tax, 2) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 text-right">Total :</div>
                                                <div class="col-md-6 col-sm-6 text-right">
                                                    <input type="hidden" class="form-control InputEditClick"
                                                        id="txt_totalpremium" name="txt_totalpremium"
                                                        value="{{ $orders->totalpremium }}" readonly=true>
                                                    {{ number_format($orders->totalpremium, 2) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 text-right">Discount :</div>
                                                <div class="col-md-6 col-sm-6 text-right">
                                                    <input type="hidden" class="form-control InputEditClick"
                                                        id="txt_discount" name="txt_discount"
                                                        value="{{ $discount }}" readonly=true>
                                                    {{ number_format($discount, 2) }}
                                                </div>
                                            </div>
                                            <div class="row c1641ff fwb">
                                                <div class="col-md-6 col-md-6 text-right">Total Amount :</div>
                                                <div class="col-md-6 col-sm-6 text-right fwb">
                                                    <input type="hidden" class="form-control InputEditClick"
                                                        id="txt_totalamount" name="txt_totalamount"
                                                        value="{{ $totalamount }}" readonly=true>
                                                    <span class="fwb">{{ number_format($totalamount, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="form-group mt10">
                                        <div class="">
                                            <div id="accordion">
                                                <div class="card">
                                                    <div class="" id="headingOne">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" data-toggle="collapse"
                                                                data-target="#collapseOne" aria-expanded="true"
                                                                aria-controls="collapseOne">
                                                                >> Coverage Package
                                                            </button>
                                                        </h5>
                                                    </div>

                                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                        data-parent="#accordion">
                                                        <div class="card-body" style="white-space: pre-line;">
                                                            {{ $orders->package_coverage }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card mt10">
                                                    <div class="" id="headingTwo">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" data-toggle="collapse"
                                                                data-target="#collapseTwo" aria-expanded="false"
                                                                aria-controls="collapseTwo">
                                                                >> Condition
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                        data-parent="#accordion">
                                                        <div class="card-body" style="white-space: pre-line;">
                                                            {{ $orders->package_condition }}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-8">
                                        </div>
                                        <div class="col-md-3 text-right">
                                        </div>
                                        <div class="col-md-1 text-right">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="modal fade" id="ComfirmInvoice" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Comfirm Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="GET" name="comfirminvoice" id="comfirminvoice">
                            <input type="hidden" name="md_order_id" value="{{ $orders->orders_id }}" >
                            <input type="hidden" id="package_id" name="package_id" value="{{ $orders->packages_id }}" >
                            <input type="hidden" id="package_name" name="package_name" value="{{ $orders->package_name }}" >
                            <input type="hidden" id="package_netpremium" name="package_netpremium" value="{{ $orders->netpremium }}" >
                            <input type="hidden" id="package_duty" name="package_duty" value="{{ $orders->duty }}" >
                            <input type="hidden" id="package_tax" name="package_tax" value="{{ $orders->tax }}" >
                            <input type="hidden" id="package_totalpremium" name="package_totalpremium" value="{{ $orders->totalpremium }}" >
                            <input type="hidden" id="package_discount" name="package_discount" value="{{ $discount }}" >
                            <input type="hidden" id="package_premiumafterdisc" name="package_premiumafterdisc" value="{{ $totalamount }}" >
                            <input type="hidden" id="md_customer_firstname" name="md_customer_firstname" value="{{ $orders->customer_firstname }}" >
                            <input type="hidden" id="md_customer_lastname" name="md_customer_lastname" value="{{ $orders->customer_lastname }}" >
                            <input type="hidden" id="md_customer_email" name="md_customer_email" value="{{ $orders->customer_email }}" >
                            <input type="hidden" id="md_customer_phone" name="md_customer_phone" value="{{ $orders->customer_phone }}" >
                            <input type="hidden" id="md_orders_code" name="md_orders_code" value="{{ $orders->orders_code }}" >
                            <div class="row">
                                <div class="col-md-12">
                                    <div for="orders_code" class="">Order Code : {{ $orders->orders_code }}</div>
                                </div>
                                <div class="col-md-12">
                                    <div for="orders_code" class="">Customer : {{ $orders->customer_firstname." ".$orders->customer_lastname }}</div>
                                </div>
                                <div class="col-md-12">
                                    <div for="orders_code" class="">Email : {{ $orders->customer_email }}</div>
                                </div>
                                <div class="col-md-12">
                                    <div for="orders_code" class="">Phone : {{ $orders->customer_phone }}</div>
                                </div>
                            </div>
                
                            <div class="row mt20">
                                <div class="col-md-12">Mobile Detail: {{$orders->mobile_brand." ".$orders->mobile_model." ".$orders->mobile_memory." ".$orders->mobile_color}}</div>
                                <div class="col-md-12">Package:  {{ $orders->package_name." | ".number_format($totalamount, 2) }}</div>
                            </div> 
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="customer_firstname" class="">IMEI : </label>
                                    <input type="text" class="form-control InputEditClick" id="md_mobile_imei" name="md_mobile_imei" value="{{ $orders->mobile_imei }}" > 
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_firstname" class="">วันหมดอายุชำระเงิน : </label>
                                    <input type="date" class="form-control InputEditClick" id="md_exp_paid_date" name="md_exp_paid_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}"> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="customer_firstname" class="">Renews Start date : </label>
                                    <input type="date" class="form-control InputEditClick" id="md_renews_start_date" name="md_renews_start_date" value="{{ date('Y-m-d') }}"  > 
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_lastname">Renews End date: </label>
                                    <input type="date" class="form-control InputEditClick"id="md_renews_end_date" name="md_renews_end_date" value="{{ date('Y-m-d', strtotime(date('Y-m-d') . ' +1 year'))  }}" >
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="btnCreateInvoice()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Upload File:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="uploadFileOrder">
                        <div class="modal-body">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" id="ul_order_id" name="ul_order_id" value="{{ $orders->orders_id }}" >
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="">File Type</label>
                                    <select name="orders_file_type" id="orders_file_type" class="form-control" required>
                                        <option value=""> --- Please Select --- </option>
                                        <option value="Product">Product</option>
                                        <option value="Document">Document</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">File</label>
                                    <input type="file" class="form-control InputEditClick" id="uploadfilename" name="uploadfilename" value="{{ $orders->mobile_imei }}" required> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="">File Type</label>
                                   <input type="text" name="orders_file_note" id="orders_file_note">
                                </div>
                               
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
