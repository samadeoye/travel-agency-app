<?php
require_once '../../inc/utils.php';
if (isset($_SESSION['user']))
{
    header('Location: '.DEF_ROOT_PATH_ADMIN.'/app/');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?php echo DEF_ROOT_PATH_ADMIN; ?>/">
  <title><?php echo $arSiteSettings['name'];?> - Login</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/css/adminlte.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a class="h2 font-weight-bold"><?php echo $arSiteSettings['name'];?></a>
            <h3>Login</h3>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form method="post" onsubmit="return false;" id="loginForm">
                <input type="hidden" name="action" id="action" value="login">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" id="btnSubmit">Sign In</button>
                    </div>
                </div>
            </form>

            <p class="my-2">
                <a href="app/forgotpass">I forgot my password</a>
            </p>

            <!-- <p class="my-2">
                <a href="app/register">Not registered?</a>
            </p> -->
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/adminlte.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<script src="assets/js/functions.js"></script>

<script>
$(document).ready(function() {
    $('#loginForm #btnSubmit').click(function ()
    {
        var formId = '#loginForm';
        var email = $(formId+' #email').val();
        var password = $(formId+' #password').val();

        if (email.length < 13 || email.length > 100)
        {
            throwError('Email is invalid');
        }
        else if (password.length < 6)
        {
            throwError('Password is invalid');
        }
        else
        {
            var $form = $("#loginForm");
            $.ajax({
                url: 'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: $form.serialize(),
                beforeSend: function() {
                    enableDisableBtn(formId+' #btnSubmit', 0);
                },
                complete: function() {
                    enableDisableBtn(formId+' #btnSubmit', 1);
                },
                success: function(data)
                {
                    if(data.status == true)
                    {
                        throwSuccess('Login successful! Logging you in...', 'alert_login', 'success');
                        $form[0].reset();
                        //redirect to dashboard
                        window.location.href = 'app/';
                    }
                    else
                    {
                        if(data.info !== undefined)
                        {
                            showAlert(data.msg, 'alert_login', 'notice');
                        }
                        else
                        {
                            showAlert(data.msg, 'alert_login', 'error');
                        }
                    }
                }
            });
        }
    });
});
</script>
</body>
</html>