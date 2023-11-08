<?php
require_once '../../inc/utils.php';
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
            <h3>Reset Password</h3>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Enter your registered email</p>
            <form method="post" onsubmit="return false;" id="forgotPassForm">
                <input type="hidden" name="action" id="action" value="forgotpassverifyemail">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" id="btnSubmit">Proceed</button>
                    </div>
                </div>
            </form>

            <p class="my-2">
                <a href="app/login">Login</a>
            </p>

            <!-- <p class="my-2">
                <a href="app/register">Not registered?</a>
            </p> -->
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/adminlte.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<script src="assets/js/functions.js"></script>

<script>
$(document).ready(function() {
    $('#forgotPassForm #btnSubmit').click(function()
    {
        var formId = '#forgotPassForm';
        var email = $(formId+' #email').val();

        if (email.length < 13)
        {
            throwError('Please enter a valid email');
        }
        else
        {
            var form = $('#forgotPassForm');
            $.ajax({
                url: 'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function() {
                    enableDisableBtn(formId+' #btnSubmit', 0);
                },
                complete: function() {
                    enableDisableBtn(formId+' #btnSubmit', 1);
                },
                success: function(data)
                {
                    if(data.status)
                    {
                        throwSuccess('Password reset link has been sent to your email: '+ email);
                        form[0].reset();
                    }
                    else
                    {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
});
</script>

</script>
</body>
</html>