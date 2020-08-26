<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="name">
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <meta name="robots" content="noindex, nofollow" />

    <link href="//cdntest.newtechdealerservices.com/mdb/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdntest.newtechdealerservices.com/mdb/css/mdb.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <style>
        .text-gray {
            color: gray;
        }

        .no-padding {
            padding: 0px !important;
        }
        /* Required height of parents of the Full Page Intro and Intro itself */
        html,
        body,
        .view {
            height: 100%; }

        /* Navbar animation */
        .navbar {
            background-color: rgba(0, 0, 0, 0.2); }

        .top-nav-collapse {
            background-color: #1C2331; }

        /* Adding color to the Navbar on mobile */
        @media only screen and (max-width: 768px) {
            .navbar {
                background-color: #1C2331; } }

        /* Footer color for sake of consistency with Navbar */
        .page-footer {
            background-color: #1C2331; }
    </style>
</head>
<body class="bg-dark view">
<div class="view">

    <!-- Mask & flexbox options-->
    <div class="mask rgba-black-light d-flex justify-content-center align-items-center">

        <!-- Content -->
        <div class="text-center mx-5 wow fadeIn">
            <div class="card">
                <div class="card-header">
                    Error
                </div>
                <div class="card-body">
                    <h1 class="card-title">403</h1>
                    <p>{{ $exception->getMessage() }}</p>
                    <a onclick="history.back(-1)" class="btn btn-outline-dark btn-md">Go back</a>
                    <a href="/logout" class="btn btn-outline-dark btn-md">Sign Out</a>
                    <hr>
                    <p class="card-text">If you think you are seeing this by mistake please contact an administrator.</p>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <!--Blue select-->
                    @if(isset(\Session::get('sso')['possibleUsers']))
                        <select id="userSelect" class="mdb-select md-form colorful-select dropdown-dark" onchange="selectStore()">
                            @foreach(\Session::get('sso')['possibleUsers'] as $potential)
                                <option value='{{ $potential->id }}' {{ $potential->id == Session::get('sso')['id'] ? 'selected' : '' }}>{{ $potential->display }}</option>
                            @endforeach
                        </select>

                    @else
                        <select id="storeSelect" class="mdb-select md-form colorful-select dropdown-dark" onchange="selectStore()" disabled>
                            <option value="unavailable">Stores Unavaiable</option>
                        </select>
                    @endif

                    <label class="mdb-main-label">Active Store</label>
                    <!--/Blue select-->
                </div>
            </div>
        </div>
        <!-- Content -->

    </div>
    <!-- Mask & flexbox options-->

</div>

<script type="text/javascript" src="//cdntest.newtechdealerservices.com/mdb/js/jquery.min.js"></script>
<script type="text/javascript" src="//cdntest.newtechdealerservices.com/mdb/js/popper.min.js"></script>
<script type="text/javascript" src="//cdntest.newtechdealerservices.com/mdb/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//cdntest.newtechdealerservices.com/mdb/js/mdb.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://use.fontawesome.com/f34294da8c.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    function selectStore() {
        let user_id = $('#userSelect')[0].value;

        $.ajax({
            type: 'GET',
            url: '/ssobridge/changeUser/noauth/' + user_id,
            success: function (data) {
                console.log(data);
                window.location.reload()
            }
        });
    }
</script>
<script>

    $(document).ready(function() {
        $('.mdb-select').materialSelect();
        $(".button-collapse").sideNav();
    });

    function nav_click(location) {
        window.location.href = location;
    }
</script>
</body>
</html>
