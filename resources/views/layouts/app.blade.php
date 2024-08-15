<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=800">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '7 Care Plus') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="stylesheet" href="{{ asset('include/CSS/bootstrap-4.3.1-dist/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('include/jquery.magnify.css') }}">


    <script src="{{ asset('include/CSS/popper.min.js') }}"></script>
    <script src="{{ asset('include/CSS/bootstrap-4.3.1-dist/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('include/dom-to-image-master/src/dom-to-image.js') }}"></script>
    <script src="{{ asset('include/js/jquery-3.1.0.min.js') }}"></script>

    <script src="{{ asset('include/jquery.magnify.js') }}"></script>
    <link href='https://fonts.googleapis.com/css?family=Prompt' rel='stylesheet'>

    <!-- DataTable -->
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('include/DataTables/datatables.min.css') }}"/> -->
    <!-- <script type="text/javascript" src="{{ asset('include/DataTables/datatables.min.js') }}"></script> -->

    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js" defer></script> -->

    <!-- datepicker -->
    <link href="{{ asset('include/CSS/bootstrap-datepicker3/datepicker3.css') }}" rel="stylesheet" media="screen">
    <script src="{{ asset('include/CSS/bootstrap-datepicker3/bootstrap-datepicker.js') }}" charset="UTF-8"></script>

    <script>
        let sub_url = "{{ ENV('NOTE_URL') }}";
       
        $(document).ready(function() {
            let agent_rule = $("#agent_rule").val();
            if(agent_rule != 'Admin'){
                $("#link_invoice").remove();
            }
        });

        function close_modal() {
            $("#myModal").hide();
            $("#show_modal").html("");
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/w3.css') }}">
    <link rel="stylesheet" href="{{ asset('include/CSS/click.css') }}">
    <link rel="stylesheet" href="{{ asset('include/sweetalert2/dist/sweetalert2.min.css') }}">
    <script src="{{ asset('include/sweetalert2/dist/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('include/print.min.js') }}"></script>

    <!-- Fonts -->
    <!-- <link rel="stylesheet" href="{{ asset('include/CSS/font-awesome-4.7.0/css/font-awesome.min.css') }}"> -->
    <link href="{{ asset('include/fontawesome-free-6.4.2/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('include/fontawesome-free-6.4.2/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('include/fontawesome-free-6.4.2/css/solid.css') }}" rel="stylesheet">

    <!-- Styles -->
    <!-- <link rel="stylesheet" href="/css/app.css"> -->
    <style>
        body {
            font-family: 'Prompt', sans-serif;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .full-height {
            height: 50vh;
        }

        


        /* The Close Button */
        .close_dev5 {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close_dev5:hover,
        .close_dev5:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

</head>

<body class="w3-light-grey">
    {{-- <div id="myModal" class="modal">
        <div class="modal-content_dev5">
            <span class="close_dev5 text-right" onclick="close_modal()">&times;</span>
            <div id="show_modal"></div>
        </div>
    </div> --}}

    @if(auth()->check())
        <input type="text" id="agent_rule" value="{{ auth()->user()->agent_rule }}">
    @else
        <input type="text" id="agent_rule" value="">
    @endif
    


    <div id="app">
        <div id="topbar" class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="">

                <nav class="navbar navbar-expand-md navbar-dark fixed-top " style="background-color: #46982f;">
                    <a class="navbar-brand" href="#">7Care+</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <ul class="navbar-nav mr-auto">

                            <?php if(env('APP_V') == 'stg'): ?>
                                <li class="nav-item active">
                                    <a class="nav-link" href="/icarestg/home">Home <span class="sr-only">(current)</span></a>
                                </li>

                                <li class="nav-item" id="link_invoice">
                                    <a class="nav-link" href="/icarestg/invoice">Invoice</a>
                                </li>

                            <?php else: ?>
                                <li class="nav-item active">
                                    <a class="nav-link" href="/icare/home">Home <span class="sr-only">(current)</span></a>
                                </li>

                                <li class="nav-item" id="link_invoice">
                                    <a class="nav-link" href="/icare/invoice">Invoice</a>
                                </li>

                            <?php endif; ?>
                           
                        </ul>
                        <div class="form-inline mt-2 mt-md-0">
                            <ul class="navbar-nav mr-auto">
                                
                                <li class="nav-item active">
                                <?php if(env('APP_V') == 'stg'): ?>                                    
                                    <a class="nav-link" href="/icarestg/logout">Logout</a>
                                <?php else: ?>                                    
                                    <a class="nav-link" href="/icare/logout">Logout</a>
                                <?php endif; ?>
                                    
                                </li>
                            </ul>
                            {{-- <div class="dropdown">
                                <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                                    id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown"
                                    aria-expanded="false">
                                    <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle"
                                        height="25" alt="Black and White Portrait of a Man" loading="lazy" />
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="navbarDropdownMenuAvatar">
                                    <li>
                                        <a class="dropdown-item" href="#">My profile</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Settings</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Logout</a>
                                    </li>
                                </ul>
                            </div> --}}
                        </div>

                    </div>
                </nav>

            </div>
        </div>

        <main class="py-4 mt30">
            @yield('content')
        </main>


    </div>
    <!-- Footer -->
    <footer class="page-footer font-small blue">

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3 c9a9a9a">© 2023 Copyright by Click Broker</div>
        <!-- Copyright -->

    </footer>
    <!-- Footer -->
</body>

</html>
