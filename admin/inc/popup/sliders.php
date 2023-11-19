<?php
require_once '../../../inc/utils.php';
use AbcTravels\ImageSlider\ImageSlider;

$action = trim($_REQUEST['action']);

$id = $img = '';
$title = 'Add New Image Slider';
$modalId = 'defaultModal';
if($action == 'updateslider')
{
    $title = 'Update Image';

    $id = trim($_REQUEST['id']);
    $rs = ImageSlider::getSlider($id, ['img']);
    if ($rs)
    {
        $img = $rs['img'];
    }
    else
    {
        //throw error and exit
        echo '<p class="p-3">An erorr occurred while loading details. Please refresh page and try again.</p>';
        exit;
    }
}
?>

<form class="pt-3" id="addUpdateSliderForm" method="post" action="inc/actions" onsubmit="return false;" enctype="multipart/form-data">
    <div class="modal-header">
        <h5 class="modal-title"><?php echo $title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <div class="row">
            <div class="form-group col-md-12">
                <?php
                if ($img != '')
                { ?>
                    <div><img src="<?=DEF_ROOT_PATH;?>/images/hero-section/<?=$img;?>" class="adminTableImg"></div>
                <?php
                }
                ?>
                <label>Featured Image</label>
                <input type="file" class="form-control" name="sliderImage" id="sliderImage">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
    </div>
</form>

<script>
var formId = 'addUpdateSliderForm';
var modalId = '<?php echo $modalId; ?>';
$(document).ready(function() {

    $('#'+formId+' #btnSubmit').click(function(){
        var formData = new FormData(this.form);
        $.ajax({
            url: 'inc/actions',
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                enableDisableBtn('#'+formId+' #btnSubmit', 0);
            },
            complete: function() {
                enableDisableBtn('#'+formId+' #btnSubmit', 1);
            },
            success: function(data) {
                if(data.status == true) {
                    throwSuccess(data.msg);
                    closeModal(modalId, true);
                    reloadTable('slidersTable');
                }
                else {
                    toastr.error(data.msg);
                }
            }
        });
    });

});
</script>
