<?php
require_once '../utils.php';
use AbcTravels\Vehicle\Vehicle;

$id = trim($_REQUEST['id']);

$name = $img = '';
$throwError = true;
if ($id != '')
{
    if (strlen($id) == 36)
    {
        $rs = Vehicle::getVehicle($id, ['name', 'img']);
        if ($rs)
        {
            $throwError = false;
            $name = stringToTitle($rs['name']);
            $img = $rs['img'];
        }
    }
}
if ($throwError)
{
    //throw error and exit
    echo '<p class="p-3">An erorr occurred while loading details. Please refresh page and try again.</p>';
    exit;
}
?>

<div class="modal-header">
    <h5 class="modal-title"><?php echo $name; ?></h5>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <i class="fas fa-times"></i>
    </button>
</div>
<div class="modal-body">
    <?php
        if ($img != '')
        { ?>
            <div class="text-center">
                <img style="max-width:100%;" src="<?=DEF_ROOT_PATH;?>/images/vehicle/<?=$img;?>">
            </div>
        <?php
        }
    ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>