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
        .no-padding {
            padding: 0px !important;
        }
        html,
        body{
            height: 100%; }

        #authFrame {
            position: absolute; 
            height: 100%; 
            border: none;
        }
    </style>
</head>
<body>
    <iframe id="authFrame" src="{{$authUrl}}" width="100%"></iframe>
    <script type="text/javascript" src="//cdntest.newtechdealerservices.com/mdb/js/jquery.min.js"></script>
    <script type="text/javascript" src="//cdntest.newtechdealerservices.com/mdb/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://pym.nprapps.org/pym.v1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $("#authFrame").on('load', function(){
                console.log("redirected", document.referrer);

            });
        });
    </script>
</body>
</html>
