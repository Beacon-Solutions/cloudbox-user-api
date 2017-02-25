<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beacon Solutions Login</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container-fluid login-vertical">
    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4 main">
            <h1 class="text-center">Beacon Solutions</h1>
            <div class="well bs-component shadow-well">
                <form class="form-horizontal" method="POST" action="{{url('login')}}">
                    <fieldset>
                        <legend>User Login</legend>
                        <div class="form-group">
                            <label for="inputUsername" class="col-lg-2 control-label">Username</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="inputUsername" placeholder="Username"
                                       name="username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" id="inputPassword" placeholder="Password"
                                       name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="submit" class="btn btn-primary" name="_submit">Login</button>
                                <button id="forgotPassword" type="button" class="btn btn-warning"
                                        name="forgot_password">Forgot Password
                                </button>
                            </div>

                        </div>
                        @if (isset($error) && $error == true)
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong> Username or Password is incorrect</strong>
                            </div>
                        @endif
                    </fieldset>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                </form>
            </div>
        </div>
        <div class="col-md-4">
        </div>
    </div>

</div>

<div class="modal fade" id="modelForgotPassword" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">WARNING : You Are About To Reset Your Password</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="formForgotPassword">
                    <fieldset>

                        <div id="recoverInputGroup" class="form-group">
                            <label for="inputUsername" class="col-lg-2 control-label">Username</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="inputUsernameReset" placeholder="Username"
                                       name="recover_username" autocomplete="off" value="" required>
                            </div>
                        </div>

                        <input type="hidden" id="csrfToken" name="_token" value="{{ csrf_token() }}"/>
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button id="resetPassword" type="button" class="btn btn-danger" name="reset_password">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                        <div id="passwordResetInfo" class="alert alert-dismissible alert-success text-center" hidden>
                            <strong id="passwordResetInfoMsg"></strong>
                        </div>
                    </fieldset>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<script>
    $('#forgotPassword').click(function () {
        $('#modelForgotPassword').modal('show');
    });

    $('#resetPassword').click(function () {

        if ($.trim($('#inputUsernameReset').val()) == '') {
            $('#recoverInputGroup').addClass('has-error');
            return;
        }

        $('#recoverInputGroup').removeClass('has-error');

        $.post("{{url('/users/reset/password')}}",
                $('#formForgotPassword').serialize(),
                function (data, status) {
                    $('#passwordResetInfoMsg').text(data['msg']);
                    $('#passwordResetInfo').show();
                    setTimeout(function () {
                        $('#modelForgotPassword').modal('hide');
                        location.reload();
                    }, 10000);
                })
    });
</script>

<script src="js/custom.js"></script>


</body>
</html>