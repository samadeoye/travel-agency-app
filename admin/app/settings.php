<?php
require_once '../../inc/utils.php';
$pageTitle = 'Settings';
$arAdditionalCSS[] = <<<EOQ
<script src="https://cdn.tiny.cloud/1/8cw5r79obdojgicjwig1exg8q30t07nlinsebyju9p3odcrr/tinymce/6/tinymce.min.js"></script>
EOQ;
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
                        <h3 class="card-title"><i class="fas fa-cog"></i> Update Settings</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" onsubmit="return false;" id="settingsForm">
                            <input type="hidden" name="action" id="action" value="updatesettings">
                            <div class="form-group">
                                <label for="siteName">Site Name</label>
                                <input type="text" id="siteName" name="siteName" class="form-control" value="<?php echo $arUser['siteName'];?>">
                            </div>
                            <div class="form-group">
                                <label for="siteEmail">Email</label>
                                <input type="email" id="siteEmail" name="siteEmail" class="form-control" value="<?php echo $arUser['siteEmail'];?>">
                            </div>
                            <div class="form-group">
                                <label for="bookingEmail">Booking Email</label>
                                <input type="email" id="bookingEmail" name="bookingEmail" class="form-control" value="<?php echo $arUser['bookingEmail'];?>">
                            </div>
                            <div class="form-group">
                                <label for="sitePhone">Phone</label>
                                <input type="tel" id="sitePhone" name="sitePhone" class="form-control" value="<?php echo $arUser['sitePhone'];?>">
                            </div>
                            <div class="form-group">
                                <label for="sitePhoneOthers">Other Phones</label>
                                <textarea name="sitePhoneOthers" id="sitePhoneOthers" class="form-control siteTextarea" cols="30" rows="10"><?php echo $arUser['sitePhoneOthers'];?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="siteAddress">Address</label>
                                <textarea name="siteAddress" id="siteAddress" class="form-control siteTextarea" cols="30" rows="10"><?php echo $arUser['siteAddress'];?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="licenseNumber">Tourist Board License No.</label>
                                <input type="text" id="licenseNumber" name="licenseNumber" class="form-control" value="<?php echo $arUser['licenseNumber'];?>">
                            </div>
                            <div class="form-group">
                                <label for="addNotification">Set Subscription Popup?</label>
                                <select name="setSubscriptionPopup" id="setSubscriptionPopup" name="setSubscriptionPopup" class="form-control">
                                    <?php echo AbcTravels\Functions::getYesOrNoDropdown($arUser['setSubscriptionPopup']); ?>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="hotelLink">Hotel URL</label>
                                <input type="text" id="hotelLink" name="hotelLink" class="form-control" value="<?php echo $arUser['hotelLink'];?>">
                            </div> -->
                            <div class="form-group">
                                <label for="siteFacebook">Facebook URL</label>
                                <input type="text" id="siteFacebook" name="siteFacebook" class="form-control" value="<?php echo $arUser['siteFacebook'];?>">
                            </div>
                            <div class="form-group">
                                <label for="siteTwitter">Twitter URL</label>
                                <input type="text" id="siteTwitter" name="siteTwitter" class="form-control" value="<?php echo $arUser['siteTwitter'];?>">
                            </div>
                            <div class="form-group">
                                <label for="siteInstagram">Instagram URL</label>
                                <input type="text" id="siteInstagram" name="siteInstagram" class="form-control" value="<?php echo $arUser['siteInstagram'];?>">
                            </div>
                            <div class="form-group">
                                <label for="siteLinkedin">LinkedIn URL</label>
                                <input type="text" id="siteLinkedin" name="siteLinkedin" class="form-control" value="<?php echo $arUser['siteLinkedin'];?>">
                            </div>
                            <div class="form-group">
                                <label for="siteYoutube">YouTube URL</label>
                                <input type="text" id="siteYoutube" name="siteYoutube" class="form-control" value="<?php echo $arUser['siteYoutube'];?>">
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

        </div>
    </section>
    
<!-- /.content -->
</div>
  
<?php
$arAdditionalJsOnLoad[] = <<<EOQ
tinymce.init({
    selector: '.siteTextarea',
    branding: false,
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
    height: "300"
});

$('#settingsForm #btnSubmit').click(function ()
{
    var formId = '#settingsForm';
    var siteName = $(formId+' #siteName').val();
    var siteEmail = $(formId+' #siteEmail').val();
    var sitePhone = $(formId+' #sitePhone').val();
    var siteAddress = $(formId+' #siteAddress').val();

    if (siteName.length < 3 || siteName.length > 250 || siteEmail.length < 13 || siteEmail.length > 150 || sitePhone.length < 6 || sitePhone.length > 16 || siteAddress.length < 10)
    {
        throwError('Please fill all required fields with valid details.');
    }
    else
    {
        tinyMCE.triggerSave();
        var form = $("#settingsForm");
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
                    throwSuccess('Site settings updated successfully!');
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
