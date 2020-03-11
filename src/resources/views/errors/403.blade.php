@extends('layouts.unauthorized')

@section('styles')
    <style>
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
@endsection

@section('content')
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
                        <h1 class="card-title">404</h1>
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
                        <select id="storeSelect" class="mdb-select md-form colorful-select dropdown-dark" onchange="selectStore()">
                            @foreach(user()->possibleStores as $store)
                                <option value="{{$store->store_number}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                        <label class="mdb-main-label">Active Store</label>
                        <!--/Blue select-->
                    </div>
                </div>
            </div>
            <!-- Content -->

        </div>
        <!-- Mask & flexbox options-->

    </div>
@endsection

@section('scripts')
    <script>
        function selectStore() {
            let store_id = $('#storeSelect')[0].value;

            $.ajax({
                type: 'POST',
                url: '/ajax/user/setStore',
                data: {
                    store_id: store_id
                },
                success: function (data) {
                    console.log(data);
                }
            });
        }
    </script>
@endsection
