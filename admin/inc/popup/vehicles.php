<?php
require_once '../../../inc/utils.php';
use AbcTravels\Vehicle\Vehicle;

$action = trim($_REQUEST['action']);

$name = $id = $passengers = $img = $otherDetails = '';
$title = 'Add New Vehicle';
$modalId = 'defaultModal';
if($action == 'updatevehicle')
{
    $title = 'Update Vehicle';

    $id = trim($_REQUEST['id']);
    $rs = Vehicle::getVehicle($id, ['name', 'passengers', 'img', 'other_details']);
    if ($rs)
    {
        $name = $rs['name'];
        $passengers = $rs['passengers'];
        $img = $rs['img'];
        $otherDetails = $rs['other_details'];
    }
    else
    {
        //throw error and exit
        echo '<p class="p-3">An erorr occurred while loading details. Please refresh page and try again.</p>';
        exit;
    }
}
?>

<form class="pt-3" id="addUpdateVehicleForm" method="post" action="inc/actions" onsubmit="return false;" enctype="multipart/form-data">
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
                <label>Vehicle Name</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo $name; ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label>No. of Passengers</label>
                <input type="text" class="form-control" name="passengers" id="passengers" value="<?php echo $passengers; ?>" placeholder="e.g 4-6">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <?php
                if ($img != '')
                { ?>
                    <div><img src="<?=DEF_ROOT_PATH;?>/images/vehicle/<?=$img;?>" class="adminTableImg"></div>
                <?php
                }
                ?>
                <label>Featured Image</label>
                <input type="file" class="form-control" name="featuredImg" id="featuredImg">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label for="otherDetails">Other Details</label>
                <textarea name="otherDetails" id="otherDetails" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $otherDetails;?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
    </div>
</form>

<script>
var formId = 'addUpdateVehicleForm';
var modalId = '<?php echo $modalId; ?>';
$(document).ready(function() {
    applyMCE();

    $('#'+formId+' #btnSubmit').click(function(){
        var name = $('#'+formId+' #name').val();
        var passengers = $('#'+formId+' #passengers').val();
        
        if (name.length < 4 || name.length > 100)
        {
            throwError('Name is invalid!');
            return false;
        }
        else if (passengers.length == 0 || passengers.length > 250)
        {
            throwError('Passengers input is invalid!');
            return false;
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
                    enableDisableBtn('#'+formId+' #btnSubmit', 0);
                },
                complete: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 1);
                },
                success: function(data) {
                    if(data.status == true) {
                        throwSuccess(data.msg);
                        tinymce.remove("#otherDetails");
                        closeModal(modalId, true);
                        reloadTable('vehiclesTable');
                    }
                    else {
                        toastr.error(data.msg);
                    }
                }
            });
        }
    });

});
</script>
