<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>LSP - Login</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="assets/images/icon.png">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>


<body class="fixed-left">

    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">

        <div class="card">
            <div class="card-body">

                <h3 class="text-center mt-0 m-b-15">
                    <a href="index.html" class="logo logo-admin"><img src="assets/images/icon.png" height="100"
                            alt="logo"></a>
                </h3>

                <div class="p-3">
                    <div class="form-group row">
                        <div class="col-12">
                            <input class="form-control" type="text" name="email" id="email"
                                placeholder="Email">
                            <small class="text-danger pl-1" id="error-email"></small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <input class="form-control" type="password" name="password" id="password"
                                placeholder="Password">
                            <small class="text-danger pl-1" id="error-password"></small>
                        </div>
                    </div>

                    <div class="form-group text-center row m-t-20">
                        <div class="col-12">
                            <button class="btn btn-danger btn-block waves-effect waves-light" type="button"
                                onclick="login()">Log
                                In</button>
                        </div>
                    </div>

                    <div class="form-group m-t-10 mb-0 row">
                        <div class="col-sm-5">
                            <a href="{{ route('register') }}" class="text-muted"><i class="mdi mdi-account-circle"></i>
                                <small>Create an account ?</small></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>

    <script src="js_custom/auth.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>

</html>
