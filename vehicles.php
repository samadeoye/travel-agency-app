<?php
require_once 'inc/utils.php';
use AbcTravels\Vehicle\Vehicle;
$pageTitle = 'Vehicles';
require_once 'inc/head.php';
?>

<!-- Page Header Start !-->
<div class="page-breadcrumb-area page-bg" style="background-image: url('images/breadcrumb/tour.jpg')"></div>
<!-- Page Header End !-->

<div class="container py-4">
    <div class="row">
        <div class="col-md-12 font-size-15">
            <a href="<?php echo DEF_ROOT_PATH;?>"><i class="fa-solid fa-house"></i> Home</a>
            <a class="active"> <i class="fa-solid fa-angle-right"></i> <?php echo $pageTitle;?></a>
        </div>
    </div>
</div>

<div class="location-slider-area style-2">
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="section-title text-start">
                    <div class="sec-content">
                        <h2 class="title font-size-30">Our Vehicles</h2>
                        <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4" id="appVehiclesContent">
            <?php
                echo Vehicle::getVehiclesContent();
            ?>
        </div>
        <div class="row py-4">
            <div class="col-md-6 text-center mx-auto">
                <?php
                    echo Vehicle::getVehiclesPagination();
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$arAdditionalJs[] = <<<EOQ
function showPagination(page)
{
    if (page <= 0)
    {
        return false;
    }
    else
    {
        $.ajax({
            url: 'inc/actions',
            type: 'POST',
            dataType: 'json',
            data: {
                page: page,
                action: 'getVehiclesPaginationData'
            },
            beforeSend: function() {
                enableDisableBtn('#appVehiclesContent #btnEditListing', 0);
            },
            complete: function() {
                enableDisableBtn('#appVehiclesContent #btnEditListing', 1);
            },
            success: function(data)
            {
                if(data.status == true)
                {
                    $("#appVehiclesContent").html(data.data['list']);
                    $("#appVehiclesPagination").html(data.data['pagination']);
                }
            }
        });
    }
}

function showFullVehicleImg(id)
{
    showModal('inc/popup/vehicle?id='+id, 'largeModal');
}

EOQ;

require_once 'inc/foot.php';
?>