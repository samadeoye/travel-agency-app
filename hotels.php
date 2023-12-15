<?php
require_once 'inc/utils.php';
use AbcTravels\Hotel\Hotel;
$pageTitle = 'Hotels';
require_once 'inc/head.php';
?>

<!-- Page Header Start !-->
<div class="page-breadcrumb-area page-bg" style="background-image: url('images/breadcrumb/bg.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-wrapper">
                    <div class="page-heading">
                        <h3 class="page-title text-on-header"><?php echo $pageTitle;?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                        <h2 class="title font-size-25">Featured Hotel Rooms - Regal Reseau Hotel & Spa Negombo Beach</h2>
                        <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4" id="appRoomsContent">
            <?php
                echo Hotel::getRoomsContent();
            ?>
        </div>
        <div class="row py-4">
            <div class="col-md-6 text-center mx-auto">
                <?php
                    echo Hotel::getRoomsPagination();
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Enquire Now Modal -->
<?php
    echo AbcTravels\Functions::getEnquireNowModal('addHotelEnquiry');
?>

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
                action: 'getRoomsPaginationData'
            },
            beforeSend: function() {
                enableDisableBtn('#appRoomsContent #btnEditListing', 0);
            },
            complete: function() {
                enableDisableBtn('#appRoomsContent #btnEditListing', 1);
            },
            success: function(data)
            {
                if(data.status == true)
                {
                    $("#appRoomsContent").html(data.data['list']);
                    $("#appRoomsPagination").html(data.data['pagination']);
                }
            }
        });
    }
}

EOQ;

require_once 'inc/foot.php';
?>