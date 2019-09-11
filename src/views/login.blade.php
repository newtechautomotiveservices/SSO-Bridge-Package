<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
        <!-- Bootstrap core CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.2/css/mdb.min.css" rel="stylesheet">
        <style media="screen">
            .vertical-center {
                min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
                min-height: 100vh; /* These two lines are counted as one :-)       */

                display: flex;
                align-items: center;
            }
            .error {
              color: red;
            }
        </style>
    </head>
    <body class="bg-dark">
        <div class="container py-5  vertical-center">
            <div class="col-md-6 mx-auto">
              
                <!-- form card login -->
                <div class="card rounded-0">
                    <div class="card-header">
                        <h3 class="mb-0">Login</h3>
                    </div>
                    <div class="card-body">
                          <div class="form">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control form-control-lg rounded-0" id="emailInput" required="">
                                <small class="error" id="usernameError" style="display:none;">Oops, you missed this one.</small>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control form-control-lg rounded-0" id="passwordInput" required="" autocomplete="new-password">
                                <small class="error" id="passwordError" style="display:none;">Enter your password too!</small>
                            </div>
                            <small class="error" id="invalidError" style="display:none;">Incorrect email or password!</small>
                            <button type="submit" onclick="authenticate()" class="btn btn-success btn-lg float-right" id="btnLogin">Login</button>
                        </div>
                    </div>
                    <!--/card-block-->
                </div>
                <!-- /form card login -->
            </div>
        </div>
        <!-- JQuery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.2/js/mdb.min.js"></script>
        <script type="text/javascript">

            $( document ).ready(function() {
                              // Get the input field
              var input1 = $("#emailInput")[0];
              var input2 = $("#passwordInput")[0];

              // Execute a function when the user releases a key on the keyboard
              input1.addEventListener("keyup", function(event) {
                // Number 13 is the "Enter" key on the keyboard
                if (event.keyCode === 13) {
                  // Cancel the default action, if needed
                  event.preventDefault();
                  // Trigger the button element with a click
                  $('#btnLogin')[0].click();
                }
              }); 
              // Execute a function when the user releases a key on the keyboard
              input2.addEventListener("keyup", function(event) {
                // Number 13 is the "Enter" key on the keyboard
                if (event.keyCode === 13) {
                  // Cancel the default action, if needed
                  event.preventDefault();
                  // Trigger the button element with a click
                  $('#btnLogin')[0].click();
                }
              }); 
            });


            function authenticate () {
                let email = $('#emailInput')[0].value;
                let password = $('#passwordInput')[0].value;

                let usernameError = $('#usernameError')[0];
                let passwordError = $('#passwordError')[0];
                let invalidError = $('#invalidError')[0];

                usernameError.style.display = "none";
                passwordError.style.display = "none";
                invalidError.style.display = "none";

                if (email == "") {
                    usernameError.style.display = "block";
                } else {
                  if (password == "") {
                    passwordError.style.display = "block";
                  } else {
                      $.ajaxSetup({
                         headers: {
                             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                         }
                     });
                      jQuery.ajax({
                         url: "{{'/ssoauth/ajax' . config('ssoauth.main.login_route')}}",
                         method: 'post',
                         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                         data: {
                            "_token": "{{ csrf_token() }}",
                            email: email,
                            password: password
                         },
                         success: function(result){
                          console.log(result);
                             if (result == "true") {
                                 window.location = "{!! config('ssoauth.main.home_route') !!}"
                             } else {
                                 invalidError.style.display = "block";
                             }
                         }
                     });
                  }
                }

          }
        </script>
        <!--/container-->

    </body>
</html>
