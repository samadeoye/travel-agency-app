<?php
require_once '../../inc/utils.php';
use AbcTravels\Terms\Terms;
$pageTitle = 'Terms and Conditions';

$privacyPolicy = $taxiBookings = $trainReservations = '';
$safariReservations = $tourReservations = '';
$rs = Terms::getTerms();
if ($rs)
{
    $privacyPolicy = $rs['privacy_policy'];
    $taxiBookings = $rs['taxi_bookings'];
    $trainReservations = $rs['train_reservations'];
    $safariReservations = $rs['safari_reservations'];
    $tourReservations = $rs['tour_reservations'];
}

$arAdditionalCSS[] = <<<EOQ
<script src="https://cdn.tiny.cloud/1/8cw5r79obdojgicjwig1exg8q30t07nlinsebyju9p3odcrr/tinymce/6/tinymce.min.js"></script>
EOQ;
require_once DEF_DOC_ROOT_ADMIN.'inc/head.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
        <!-- ./row -->
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="privacyPolicyTab" data-toggle="pill" href="#privacyPolicyTabContent" role="tab" aria-controls="privacyPolicyTabContent" aria-selected="true">Privacy Policy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="taxiBookingsTab" data-toggle="pill" href="#taxiBookingsTabContent" role="tab" aria-controls="taxiBookingsTabContent" aria-selected="true">Taxi Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="trainReservationsTab" data-toggle="pill" href="#trainReservationsTabContent" role="tab" aria-controls="trainReservationsTabContent" aria-selected="false">Train Reservations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="safariReservationsTab" data-toggle="pill" href="#safariReservationsTabContent" role="tab" aria-controls="safariReservationsTabContent" aria-selected="false">Safari Reservations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tourReservationsTab" data-toggle="pill" href="#tourReservationsTabContent" role="tab" aria-controls="tourReservationsTabContent" aria-selected="false">Tour Reservations</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                    <form method="post" onsubmit="return false;" id="updateTermsForm" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="updateterms">
                        <div class="tab-content">
                            <div class="pb-3">
                                <span>Use <span class="text-danger font-weight-bold">{site_name}</span> as the name of the company, <span class="text-danger font-weight-bold">{site_email}</span> as the company's email in between texts.</span>
                            </div>
                            <div class="tab-pane fade show active" id="privacyPolicyTabContent" role="tabpanel" aria-labelledby="privacyPolicyTab">
                                <div class="form-group">
                                    <label for="privacyPolicy">Details</label>
                                    <textarea name="privacyPolicy" id="privacyPolicy" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $privacyPolicy;?></textarea>
                                </div>
                                <div class="form-group" id="itenaryTabProceed">
                                    <input type="submit" value="Proceed" class="btn btn-info float-right" onclick="proceedToNextTab('privacyPolicyTab')">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="taxiBookingsTabContent" role="tabpanel" aria-labelledby="taxiBookingsTab">
                                <div class="form-group">
                                    <label for="taxiBookings">Details</label>
                                    <textarea name="taxiBookings" id="taxiBookings" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $taxiBookings;?></textarea>
                                </div>
                                <div class="form-group" id="itenaryTabProceed">
                                    <input type="submit" value="Proceed" class="btn btn-info float-right" onclick="proceedToNextTab('taxiBookingsTab')">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="trainReservationsTabContent" role="tabpanel" aria-labelledby="trainReservationsTab">
                                <div class="form-group">
                                    <label for="trainReservations">Details</label>
                                    <textarea name="trainReservations" id="trainReservations" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $trainReservations;?></textarea>
                                </div>
                                <div class="form-group" id="itenaryTabProceed">
                                    <input type="submit" value="Proceed" class="btn btn-info float-right" onclick="proceedToNextTab('trainReservationsTab')">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="safariReservationsTabContent" role="tabpanel" aria-labelledby="safariReservationsTab">
                                <div class="form-group">
                                    <label for="safariReservations">Details</label>
                                    <textarea name="safariReservations" id="safariReservations" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $safariReservations;?></textarea>
                                </div>
                                <div class="form-group" id="itenaryTabProceed">
                                    <input type="submit" value="Proceed" class="btn btn-info float-right" onclick="proceedToNextTab('safariReservationsTab')">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tourReservationsTabContent" role="tabpanel" aria-labelledby="tourReservationsTab">
                                <div class="form-group">
                                    <label for="tourReservations">Details</label>
                                    <textarea name="tourReservations" id="tourReservations" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $tourReservations;?></textarea>
                                </div>
                                <input type="submit" value="Submit" class="btn btn-success float-right" id="btnSubmit">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    
<!-- /.content -->
</div>
  
<?php
$basePath = DEF_FULL_ROOT_PATH.'/';
$arAdditionalJs[] = <<<EOQ
function addRemoveTinyMce(editorId)
{
    if (tinyMCE.get(editorId)) 
    {
        tinyMCE.EditorManager.execCommand('mceFocus', false, editorId);                    
        tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, editorId);
    } else {
        applyMCE(editorId, 'id');
    }
}
function applyMCE(editorId, typeId)
{
    if (editorId == undefined)
    {
        editorId = '';
    }
    if (typeId == undefined)
    {
        typeId = 'id';
    }

    var editorSelector = '';
    if (editorId == '')
    {
        editorSelector = 'textarea';
    }
    if (editorSelector == '')
    {
        switch(typeId)
        {
            case 'id':
                editorSelector = '#'+editorId;
            break;
            case 'class':
                editorSelector = '.'+editorId;
            break;
            default:
            editorSelector = 'textarea';
        }
    }
    
    tinymce.init({
        selector: editorSelector,//'textarea',
        branding: false,
        plugins: 'anchor autolink charmap codesample emoticons link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
          { value: 'First.Name', title: 'First Name' },
          { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant"))
    });
}

function proceedToNextTab(currentTabId)
{
    switch(currentTabId) {
        case 'privacyPolicyTab':
            $("#privacyPolicyTab").removeClass("active");
            $("#privacyPolicyTabContent").removeClass("show active");
            $("#taxiBookingsTab").addClass("active");
            $("#taxiBookingsTabContent").addClass("show active");
        break;
        case 'taxiBookingsTab':
            $("#taxiBookingsTab").removeClass("active");
            $("#taxiBookingsTabContent").removeClass("show active");
            $("#trainReservationsTab").addClass("active");
            $("#trainReservationsTabContent").addClass("show active");
        break;
        case 'trainReservationsTab':
            $("#trainReservationsTab").removeClass("active");
            $("#trainReservationsTabContent").removeClass("show active");
            $("#safariReservationsTab").addClass("active");
            $("#safariReservationsTabContent").addClass("show active");
        break;
        case 'safariReservationsTab':
            $("#safariReservationsTab").removeClass("active");
            $("#safariReservationsTabContent").removeClass("show active");
            $("#tourReservationsTab").addClass("active");
            $("#tourReservationsTabContent").addClass("show active");
        break;
    }
}
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
applyMCE();

$('#updateTermsForm #btnSubmit').click(function ()
{
    var formId = '#updateTermsForm';
    tinyMCE.triggerSave();
    var formData = new FormData(this.form);
    $.ajax({
        url: 'inc/actions',
        type: 'POST',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
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
                throwSuccess('Terms and conditions updated successfully!');
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
});
EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>
