@extends('layouts.app')
@section('title', 'หน้าร้าน')
@section('content')
    <!-- @guest
                          <script>
                              window.location.href = "{{ URL::to('/home') }}"
                          </script>
            @endguest  -->
    <style>
        label {
            font-size: 12px;
        }
    </style>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />


    <div id="wrapper">

        <!-- Content Wrapper -->
        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <div class="card border-0 shadow rounded-3 my-5">
                        <div class="card-body p-4 p-sm-5">
                            <h5 class="card-title text-center mb-5 fw-light fs-5">Sign In</h5>
                            <form id="login-form" method="post" >
                                <div class="form-floating mb-3">
                                    {{-- <label for="floatingInput">Agnet Code</label> --}}
                                    <input type="text" class="form-control" id="agnet_code" placeholder="Agent Code">
                                </div>
                                <div class="form-floating mb-3">
                                    {{-- <label for="floatingPassword">Agent Password</label> --}}
                                    <input type="password" class="form-control" id="agent_password" placeholder="Agent Password">
                                </div>
                                <div class="d-grid text-center">
                                    <button class="btn btn-outline-primary" type="button" onclick="LoginAgent()">Sign in</button>
                                </div>
                                <hr class="my-4">
                                
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        function LoginAgent() {
            // console.log("login");
            var agnet_code = $("#agnet_code").val();
            var agent_password = $("#agent_password").val();
            // console.log(agnet_code);
            if (!agnet_code) {
                alert("Please Input Agent Code");
                $("#agnet_code").focus();
            }else if(!agent_password){
                alert("Please Please Input Agent Password");
                $("#agent_password").focus();
            }else{
                $.ajax({
                    url: sub_url+"/login",
                    type: "POST",
                    data: {
                        _token: $("input[name='_token']").val(),
                        agnet_code: agnet_code,
                        agent_password: agent_password
                    },
                    dataType: "json", 
                    success: function (res) {
                        // console.log(res);
                        if (res.code === 200) {
                            alert(res.message);
                            window.location.href = sub_url+'/home'; 
                        } else if (res.code === 401) {
                            alert(res.message);
                        }
                    },
                });
            }

        }

    </script>


@endsection
