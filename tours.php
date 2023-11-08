<?php
require_once 'inc/utils.php';
use AbcTravels\Destination\Destination;
use AbcTravels\Functions;
$pageTitle = 'Tours';
require_once 'inc/head.php';
?>

<!-- Page Header Start !-->
<div class="page-breadcrumb-area page-bg" style="background-image: url('images/breadcrumb/tour.jpg')"></div>
<!-- Page Header End !-->

<?php
echo Functions::getToursSearchForm();
?>

<div class="container pb-4">
    <div class="row">
        <div class="col-md-12 font-size-15">
            <a href="<?php echo DEF_ROOT_PATH;?>"><i class="fa-solid fa-house"></i> Home</a>
            <a class="active"> <i class="fa-solid fa-angle-right"></i> Tour Packages</a>
        </div>
    </div>
</div>

<div class="location-slider-area style-2">
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="section-title text-start">
                    <div class="sec-content">
                        <h2 class="title">Tour Packages</h2>
                        <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4" id="appDestinationsContent">
            <?php
                echo Destination::getDestinationsContent();
            ?>
        </div>
        <div class="row py-4">
            <div class="col-md-6 text-center mx-auto">
                <?php
                    echo Destination::getDestinationsPagination();
                ?>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 px-3">
<div class="row">
<div class="col-md-6 mx-auto enquire-now-form">
<h6 class="title">Enquire Now</h6>
<?php
echo Functions::getCommonEnquiryForm();
?>
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
                action: 'getDestinationsPaginationData'
            },
            beforeSend: function() {
                enableDisableBtn('#appDestinationsContent #btnEditListing', 0);
            },
            complete: function() {
                enableDisableBtn('#appDestinationsContent #btnEditListing', 1);
            },
            success: function(data)
            {
                if(data.status == true)
                {
                    $("#appDestinationsContent").html(data.data['list']);
                    $("#appDestinationsPagination").html(data.data['pagination']);
                }
            }
        });
    }
}
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
setTimeout(function() {
    commonEnquiryFormWidgetId = grecaptcha.render(
        'commonEnquiryFormRecaptcha'
        , {"sitekey": gSiteKey}
    );
}, 1000);
EOQ;

require_once 'inc/foot.php';
?>