<?php
require_once '../../../inc/utils.php';
use AbcTravels\Hotel\Hotel;

$action = trim($_REQUEST['action']);

$name = $id = $img = $details = $link = '';
$title = 'Add New Room';
$modalId = 'defaultModal';
if ($action == 'updatehotelroom')
{
    $title = 'Update Room';

    $id = trim($_REQUEST['id']);
    $rs = Hotel::getHotel($id, ['name', 'img', 'details', 'link']);
    if ($rs)
    {
        $name = $rs['name'];
        $img = $rs['img'];
        $details = $rs['details'];
        $link = $rs['link'];
    }
    else
    {
        //throw error and exit
        echo '<p class="p-3">An erorr occurred while loading details. Please refresh page and try again.</p>';
        exit;
    }
}
?>

<form class="pt-3" id="addUpdateHotelRoomForm" method="post" action="inc/actions" onsubmit="return false;" enctype="multipart/form-data">
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
                <label>Room Name</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo $name; ?>">
            </div>
        </div>
        <!-- <div class="row">
            <div class="form-group col-md-12">
                <label>Room Link</label>
                <input type="text" class="form-control" name="link" id="link" value="<?php echo $link; ?>">
            </div>
        </div> -->
        <div class="row">
            <div class="form-group col-md-12">
                <?php
                if ($img != '')
                { ?>
                    <div><img src="<?=DEF_ROOT_PATH;?>/images/hotelroom/<?=$img;?>" class="adminTableImg"></div>
                <?php
                }
                ?>
                <label>Featured Image</label>
                <input type="file" class="form-control" name="featuredImg" id="featuredImg">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label for="details">Details</label>
                <textarea name="details" id="details" class="form-control wysiwygTextarea" cols="30" rows="10"><?php echo $details;?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
    </div>
</form>

<script>
var formId = 'addUpdateHotelRoomForm';
var modalId = '<?php echo $modalId; ?>';
$(document).ready(function() {
    applyMCE();

    $('#'+formId+' #btnSubmit').click(function(){
        var name = $('#'+formId+' #name').val();
        
        if (name.length < 4 || name.length > 100)
        {
            throwError('Name is invalid!');
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
                        tinymce.remove("#details");
                        closeModal(modalId, true);
                        reloadTable('hotelRoomsTable');
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
