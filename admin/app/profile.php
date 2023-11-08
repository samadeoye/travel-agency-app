<?php
require_once '../../inc/utils.php';
$pageTitle = 'Profile';
require_once DEF_DOC_ROOT_ADMIN.'inc/head.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $pageTitle;?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo DEF_ROOT_PATH_ADMIN;?>/app/">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $pageTitle;?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user"></i> Update Profile</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" onsubmit="return false;" id="profileForm">
                            <input type="hidden" name="action" id="action" value="updateprofile">
                            <div class="form-group">
                                <label for="fname">First Name</label>
                                <input type="text" id="fname" name="fname" class="form-control" value="<?php echo $arUser['fname'];?>">
                            </div>
                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" id="lname" name="lname" class="form-control" value="<?php echo $arUser['lname'];?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" id="email" name="email" class="form-control" value="<?php echo $arUser['email'];?>" readonly>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Save Changes" class="btn btn-success float-right" id="btnSubmit">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-lock"></i> Change Password</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" onsubmit="return false;" id="changePasswordForm">
                            <input type="hidden" name="action" value="changepassword">
                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" id="currentPassword" name="currentPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" id="newPassword" name="newPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm New Password</label>
                                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Save Changes" class="btn btn-success float-right" id="btnSubmitChangePass">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

        </div>
    </section>
    
<!-- /.content -->
</div>
  
<?php
$arAdditionalJsOnLoad[] = <<<EOQ
$('#profileForm #btnSubmit').click(function ()
{
    var formId = '#profileForm';
    var fname = $(formId+' #fname').val();
    var lname = $(formId+' #lname').val();

    if (fname.length < 3 || lname.length < 3)
    {
        throwError('Please fill all required fields');
    }
    else
    {
        var form = $("#profileForm");
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
                if(data.status == true)
                {
                    throwSuccess('Profile updated successfully!');
                    $(formId+' #fname').val(data.data['fname']);
                    $(formId+' #lname').val(data.data['lname']);
                }
                else
                {
                    if(data.info !== undefined)
                    {
                        throwInfo(data.msg);
                    }
                    else
                    {
                        throwError(data.msg);
                    }
                }
            }
        });
    }
});

$('#changePasswordForm #btnSubmitChangePass').click(function ()
{
    var formId = '#changePasswordForm';
    var currentPassword = $(formId+' #currentPassword').val();
    var newPassword = $(formId+' #newPassword').val();
    var confirmPassword = $(formId+' #confirmPassword').val();

    if (currentPassword.length < 6 || newPassword.length < 6 || confirmPassword.length < 6)
    {
        throwError('Password must contain at least 6 characters');
    }
    else if (newPassword != confirmPassword)
    {
        throwError('Passwords do not match');
    }
    else
    {
        var form = $("#changePasswordForm");
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
                    throwSuccess('Password changed successfully!');
                    form[0].reset();
                }
                else
                {
                    if(data.info !== undefined)
                    {
                        throwInfo(data.msg);
                    }
                    else
                    {
                        throwError(data.msg);
                    }
                }
            }
        });
    }
});
EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>
