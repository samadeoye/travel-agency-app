<?php
require_once '../../inc/utils.php';
use AbcTravels\Admin\Destination\Destination;
use AbcTravels\Admin\Tour\Tour;
use AbcTravels\Functions;
$pageTitle = 'Add Tour';
$formAction = 'addtour';
$rs = [];
$title = $destinationId = $days = $inclusions = $summary = $mapIframe = '';
$itenaryContent = '';
$price = $itenaryDay = $itenaryDayCount = $isSpecialPackage = $addNotification = 0;
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if (strlen($id) == 36)
{
    $formAction = 'updatetour';
    $pageTitle = 'Update Tour';
    $rs = Tour::getTour($id);
    if ($rs)
    {
        $title = $rs['title'];
        $destinationId = $rs['destination_id'];
        $days = doTypeCastInt($rs['days']);
        $price = doTypeCastDouble($rs['price']);
        $inclusions = $rs['inclusions'];
        $summary = $rs['summary'];
        $mapIframe = $rs['map'];
        $isSpecialPackage = doTypeCastInt($rs['special_package']);
        $addNotification = doTypeCastInt($rs['add_notification']);
        if ($rs['itenary_title'] != '')
        {
            $arFields = [
                'itenary_title' => $rs['itenary_title'],
                'itenary_accomodation' => $rs['itenary_accomodation'],
                'itenary_accomodation_link' => $rs['itenary_accomodation_link'],
                'itenary_meal_plan' => $rs['itenary_meal_plan'],
                'itenary_travel_time' => $rs['itenary_travel_time'],
                'itenary_transfer_mode' => $rs['itenary_transfer_mode'],
                'itenary_details' => stripslashes($rs['itenary_details'])
            ];
            $arFormData = Tour::getTourItenaryFormData($arFields);
            $itenaryDay = $arFormData['itenaryDay'];
            $itenaryDayCount = $arFormData['itenaryDayCount'];
            $itenaryContent = $arFormData['content'];
        }
    }
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
                                <a class="nav-link active" id="generalTab" data-toggle="pill" href="#generalTabContent" role="tab" aria-controls="generalTabContent" aria-selected="true">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="itenaryTab" data-toggle="pill" href="#itenaryTabContent" role="tab" aria-controls="itenaryTabContent" aria-selected="true">Itenary</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="inclusionsTab" data-toggle="pill" href="#inclusionsTabContent" role="tab" aria-controls="inclusionsTabContent" aria-selected="false">Inclusions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="summaryTab" data-toggle="pill" href="#summaryTabContent" role="tab" aria-controls="summaryTabContent" aria-selected="false">Summary</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                    <form method="post" onsubmit="return false;" id="addUpdateTourForm" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="<?php echo $formAction;?>">
                        <input type="hidden" name="id" value="<?php echo $id;?>">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="generalTabContent" role="tabpanel" aria-labelledby="generalTab">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" value="<?php echo $title;?>">
                                </div>
                                <div class="form-group">
                                    <label for="destinationId">Destination</label>
                                    <select name="destinationId" id="destinationId" name="destinationId" class="form-control">
                                        <?php
                                            echo Destination::getDestinationsDropdownOptions($destinationId);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="numberOfDays">No. of Days</label>
                                    <input type="number" id="numberOfDays" name="numberOfDays" class="form-control" value="<?php echo $days;?>">
                                </div>
                                <div class="form-group">
                                    <label for="price">Starting Price</label>
                                    <input type="number" id="price" name="price" class="form-control" value="<?php echo $price;?>">
                                </div>
                                <div class="form-group">
                                    <label for="specialPackage">Special Package?</label>
                                    <select name="specialPackage" id="specialPackage" name="specialPackage" class="form-control">
                                        <?php echo Functions::getYesOrNoDropdown($isSpecialPackage); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="addNotification">Add Notification?</label>
                                    <select name="addNotification" id="addNotification" name="addNotification" class="form-control">
                                        <?php echo Functions::getYesOrNoDropdown($addNotification); ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Featured Image</label>
                                        <input type="file" class="form-control" name="featuredImg" id="featuredImg">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo $mapIframe;?><br>
                                    <label for="mapIframe">Map Iframe</label>
                                    <input type="text" id="mapIframe" name="mapIframe" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Proceed" class="btn btn-info float-right" onclick="proceedToNextTab('generalTab')">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="itenaryTabContent" role="tabpanel" aria-labelledby="itenaryTab">
                                <button class="btn btn-primary mb-3" id="btnAddItenaryDay">Add New Day</button>
                                <?php echo $itenaryContent;?>
                                <div class="form-group" id="itenaryTabProceed">
                                    <input type="submit" value="Proceed" class="btn btn-info float-right" onclick="proceedToNextTab('itenaryTab')">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="inclusionsTabContent" role="tabpanel" aria-labelledby="inclusionsTab">
                                <div class="form-group">
                                    <label for="inclusions">Inclusions Details</label>
                                    <textarea name="inclusions" id="inclusions" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $inclusions;?></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Proceed" class="btn btn-info float-right" onclick="proceedToNextTab('inclusionsTab')">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="summaryTabContent" role="tabpanel" aria-labelledby="summaryTab">
                                <div class="form-group">
                                    <label for="summary">Summary Details</label>
                                    <textarea name="summary" id="summary" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $summary;?></textarea>
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
var itenaryDay = '{$itenaryDay}';
var itenaryDayCount = '{$itenaryDayCount}';
function getNewItenaryDay(day)
{
var itenaryDayRowId = 'itenaryDayRowId'+day;
var itenaryDayTextareaId = 'itenaryDayTextareaId'+day;
var itenaryDayTitleId = 'itenaryDayTitleId'+day;
var itenaryDayAccId = 'itenaryDayAccId'+day;
var itenaryDayAccLinkId = 'itenaryDayAccLinkId'+day;
var itenaryDayMealPlanId = 'itenaryDayMealPlanId'+day;
var itenaryDayTravelTimeId = 'itenaryDayTravelTimeId'+day;
var itenaryDayTransferModeId = 'itenaryDayTransferModeId'+day;

var newItenaryDayContent = '<div id="'+itenaryDayRowId+'">' +
'<div class="form-group">' +
'<label for="'+itenaryDayTitleId+'">Title</label>' +
'<input type="text" id="'+itenaryDayTitleId+'" name="'+itenaryDayTitleId+'" class="form-control" placeholder="e.g. Day '+day+'">' +
'</div>' +
'<div class="form-group">' +
'<label for="'+itenaryDayTextareaId+'">Details</label>' +
'<textarea class="form-control" name="'+itenaryDayTextareaId+'" id="'+itenaryDayTextareaId+'" cols="30" rows="10"></textarea>' +
'</div>' +
'<div class="form-group">' +
'<label for="'+itenaryDayAccId+'">Accomodation</label>' +
'<input type="text" id="'+itenaryDayAccId+'" name="'+itenaryDayAccId+'" class="form-control">' +
'</div>' +
'<div class="form-group">' +
'<label for="'+itenaryDayAccLinkId+'">Accomodation Link</label>' +
'<input type="text" id="'+itenaryDayAccLinkId+'" name="'+itenaryDayAccLinkId+'" class="form-control">' +
'</div>' +
'<div class="form-group">' +
'<label for="'+itenaryDayMealPlanId+'">Meal Plan</label>' +
'<input type="text" id="'+itenaryDayMealPlanId+'" name="'+itenaryDayMealPlanId+'" class="form-control">' +
'</div>' +
'<div class="form-group">' +
'<label for="'+itenaryDayTravelTimeId+'">Travel Time</label>' +
'<input type="text" id="'+itenaryDayTravelTimeId+'" name="'+itenaryDayTravelTimeId+'" class="form-control">' +
'</div>' +
'<div class="form-group">' +
'<label for="'+itenaryDayTransferModeId+'">Transfer Mode</label>' +
'<input type="text" id="'+itenaryDayTransferModeId+'" name="'+itenaryDayTransferModeId+'" class="form-control">' +
'</div>' +
'<button class="btn btn-danger btn-sm mb-1" onclick="deleteItenaryDay('+day+')"><i class="fas fa-trash"></i> Delete Row</button>' +
'<div class="progress progress-xxs mb-3">' +
'<div class="progress-bar progress-bar-danger bg-danger progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">' +
'<span class="sr-only">60% Complete (warning)</span>' +
'</div>' +
'</div>' +
'</div>';
return {
    'textareaId': itenaryDayTextareaId,
    'content': newItenaryDayContent
};
}

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
        images_upload_url: 'inc/uploadtinymceimg.php',
        //relative_urls: false,
        //remove_script_host: false,
        //convert_urls: true,

        relative_urls: true,
        document_base_url: '{$basePath}',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
          { value: 'First.Name', title: 'First Name' },
          { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant"))
    });
}

function deleteItenaryDay(day)
{
    $("#itenaryDayRowId"+day).remove();
    itenaryDayCount--;
}

function proceedToNextTab(currentTabId)
{
    switch(currentTabId) {
        case 'generalTab':
            $("#generalTab").removeClass("active");
            $("#generalTabContent").removeClass("show active");
            $("#itenaryTab").addClass("active");
            $("#itenaryTabContent").addClass("show active");
        break;
        case 'itenaryTab':
            $("#itenaryTab").removeClass("active");
            $("#itenaryTabContent").removeClass("show active");
            $("#inclusionsTab").addClass("active");
            $("#inclusionsTabContent").addClass("show active");
        break;
        case 'inclusionsTab':
            $("#inclusionsTab").removeClass("active");
            $("#inclusionsTabContent").removeClass("show active");
            $("#summaryTab").addClass("active");
            $("#summaryTabContent").addClass("show active");
        break;
    }
}
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
applyMCE();

$("#btnAddItenaryDay").click(function(){
    var numberOfDays = parseInt($("#numberOfDays").val());
    if (numberOfDays.length == 0)
    {
        throwError('Please enter the number of days in the previous tab');
    }
    else if (itenaryDayCount >= numberOfDays)
    {
        throwError('You cannot add rows beyond the number of days');
    }
    else
    {
        itenaryDay++;
        itenaryDayCount++;
        var arNewDay = getNewItenaryDay(itenaryDay);
        //$("#itenaryTabContent").append(arNewDay['content']);
        $(arNewDay['content']).insertBefore("#itenaryTabProceed");
        addRemoveTinyMce(arNewDay['textareaId']);
    }
});


$('#addUpdateTourForm #btnSubmit').click(function ()
{
    var formId = '#addUpdateTourForm';
    var title = $(formId+' #title').val();
    var destinationId = $(formId+' #destinationId').val();
    var numberOfDays = $(formId+' #numberOfDays').val();
    var price = $(formId+' #price').val();

    if (title.length < 3 || destinationId.length < 36 || numberOfDays.length == 0 || price.length < 2)
    {
        throwError('Please fill all required fields');
    }
    else if (parseFloat(price) == 0)
    {
        throwError('Price cannot be 0');
    }
    else
    {
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
                    if ('{$id}'.length != 36)
                    {
                        throwSuccess('Tour added successfully!');
                        window.location.href = data.data.url;
                    }
                    else
                    {
                        throwSuccess('Tour updated successfully!');
                    }
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
