<?php
require_once '../../inc/utils.php';
use AbcTravels\Submission\Submission;
$pageTitle = 'Submission';

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
$type = $cdate = $details = '';
$redirect = true;
if ($id != '')
{
    $redirect = true;
    $rs = Submission::getSubmission($id);
    if ($rs)
    {
        $typeId = $rs['type_id'];
        $type = getSubmissionType($typeId);
        $cdate = getFormattedDate($rs['cdate']);
        $arDetails = json_decode($rs['details'], true);
        $details = Submission::getSubmissionDetailsHtml($typeId, $arDetails);
        $redirect = false;
    }
}
if ($redirect)
{
    header('location: '.DEF_ROOT_PATH_ADMIN.'/submissions');
}
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
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Submission Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="float-right">
                            <button class="btn btn-danger btn-sm" onclick="deleteSubmission()"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                        <span class="badge bg-info"><?php echo $type;?></span>
                        <p><strong>Date Submitted:</strong> <?php echo $cdate;?></p>
                        <?php echo $details;?>
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
$arAdditionalJs[] = <<<EOQ
function deleteSubmission()
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this submission?',
        icon: 'error',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed)
        {
            $.ajax({
                url: 'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: {
                    'id': '{$id}',
                    'action': 'deletesubmission'
                },
                success: function(data) {
                    if(data.status == true) {
                        throwSuccess('Deleted successfully');
                        goToUrl('app/submissions');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
}
EOQ;

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