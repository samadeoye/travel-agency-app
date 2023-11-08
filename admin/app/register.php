<?php
require_once '../../inc/utils.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?php echo DEF_ROOT_PATH_ADMIN; ?>/">
  <title><?php echo $arSiteSettings['name'];?> - Register</title>

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
            <a class="h2"><?php echo $arSiteSettings['name'];?></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign up to start your session</p>
            <form method="post" onsubmit="return false;" id="registerForm">
                <input type="hidden" name="action" id="action" value="register">
                <div class="input-group mb-3">
                    <input type="fname" class="form-control" id="fname" name="fname" placeholder="First Name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="lname" class="form-control" id="lname" name="lname" placeholder="Last Name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password1" name="password1" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" id="btnSubmit">Sign Up</button>
                    </div>
                </div>
            </form>

            <p class="my-2">
                <a href="app/login">Already registered?</a>
            </p>
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
    $('#registerForm #btnSubmit').click(function()
    {
        var formId = '#registerForm';
        var fname = $(formId+' #fname').val();
        var lname = $(formId+' #lname').val();
        var email = $(formId+' #email').val();
        var password1 = $(formId+' #password1').val();
        var password2 = $(formId+' #password2').val();

        if ((fname.length < 3 || lname.length < 3) || (fname.length > 50 || lname.length > 50))
        {
            throwError('Name is invalid');
        }
        else if (email.length < 13 || email.length > 100)
        {
            throwError('Email is incorrect');
        }
        else if (password1.length < 6)
        {
            throwError('Password must contain at least 6 characters');
        }
        else if (password1 != password2)
        {
            throwError('Passwords do not match');
        }
        else
        {
            var $form = $("#registerForm");
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
                        throwSuccess('Registration successful! Logging you in...');
                        $form[0].reset();
                        //redirect to dashboard
                        window.location.href = "app/";
                    }
                    else
                    {
                        if (data.info !== undefined)
                        {
                            throwError(data.msg, 'alert_register', 'notice');
                        }
                        else
                        {
                            throwError(data.msg, 'alert_register', 'error');
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