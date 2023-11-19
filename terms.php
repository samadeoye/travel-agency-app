<?php
require_once 'inc/utils.php';
use AbcTravels\Terms\Terms;
$pageTitle = 'Terms and Conditions';

$privacyPolicy = $taxiBookings = $trainReservations = '';
$safariReservations = $tourReservations = '';
$rs = Terms::getTerms();
if ($rs)
{
    $privacyPolicy = str_replace('{site_name}', $arSiteSettings['name'], $rs['privacy_policy']);
    $privacyPolicy = str_replace('{site_email}', $arSiteSettings['email'], $privacyPolicy);
    $taxiBookings = str_replace('{site_name}', $arSiteSettings['name'], $rs['taxi_bookings']);
    $taxiBookings = str_replace('{site_email}', $arSiteSettings['email'], $taxiBookings);
    $trainReservations = str_replace('{site_name}', $arSiteSettings['name'], $rs['train_reservations']);
    $trainReservations = str_replace('{site_email}', $arSiteSettings['email'], $trainReservations);
    $safariReservations = str_replace('{site_name}', $arSiteSettings['name'], $rs['safari_reservations']);
    $safariReservations = str_replace('{site_email}', $arSiteSettings['email'], $safariReservations);
    $tourReservations = str_replace('{site_name}', $arSiteSettings['name'], $rs['tour_reservations']);
    $tourReservations = str_replace('{site_email}', $arSiteSettings['email'], $tourReservations);
}

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

<div class="container p-4">
    <div class="row">
        <div class="col-md-12 font-size-15">
            <a href="<?php echo DEF_ROOT_PATH;?>"><i class="fa-solid fa-house"></i> Home</a>
            <a class="active"> <i class="fa-solid fa-angle-right"></i> <?php echo $pageTitle;?></a>
        </div>
    </div>
</div>

<div class="about-us-area style-1" style="background-image: url('images/about-us-area/bg-1.png')">
    <img class="shape-1 wow zoomInDown" src="images/shape/dots.png" alt="Shape">
    <div class="container">
        <div class="about-us-wrapper">
            <div class="row d-flex align-items-center">
                <div class="col-md-12 order-2 wow fadeInLeft" data-wow-delay=".4s">  
                    <div>
                        <ul class="nav nav-tabs" role="tablist">
                            <?php
                            if ($privacyPolicy != '')
                            { ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab1" data-bs-toggle="tab" data-bs-target="#tab1Pane" type="button" role="tab" aria-controls="tab1Pane" aria-selected="true">Privacy Policy</button>
                                </li>
                            <?php
                            }
                            if ($taxiBookings != '')
                            { ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab2" data-bs-toggle="tab" data-bs-target="#tab2Pane" type="button" role="tab" aria-controls="tab2Pane" aria-selected="false">Taxi Bookings</button>
                                </li>
                            <?php
                            }
                            if ($trainReservations != '')
                            { ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab3" data-bs-toggle="tab" data-bs-target="#tab3Pane" type="button" role="tab" aria-controls="tab3Pane" aria-selected="false">Train Reservations</button>
                                </li>
                            <?php
                            }
                            if ($safariReservations != '')
                            { ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab4" data-bs-toggle="tab" data-bs-target="#tab4Pane" type="button" role="tab" aria-controls="tab4Pane" aria-selected="false">Safari Reservations</button>
                                </li>
                            <?php
                            }
                            if ($tourReservations != '')
                            { ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab5" data-bs-toggle="tab" data-bs-target="#tab5Pane" type="button" role="tab" aria-controls="tab5Pane" aria-selected="false">Tour Reservations</button>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="tab1Pane" role="tabpanel" aria-labelledby="tab1">
                            <?php echo $privacyPolicy; ?>
                        </div>

                        <div class="tab-pane fade" id="tab2Pane" role="tabpanel" aria-labelledby="tab2">
                            <?php echo $taxiBookings; ?>
                        </div>

                        <div class="tab-pane fade" id="tab3Pane" role="tabpanel" aria-labelledby="tab3">
                            <?php echo $trainReservations; ?>
                        </div>

                        <div class="tab-pane fade" id="tab4Pane" role="tabpanel" aria-labelledby="tab4">
                            <?php echo $safariReservations; ?>
                        </div>

                        <div class="tab-pane fade" id="tab5Pane" role="tabpanel" aria-labelledby="tab5">
                            <?php echo $tourReservations; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$arAdditionalJsOnLoad[] = <<<EOQ
$("#tab5").click(function(){
    $("#tab4Pane").hide();
});
$("#tab4").click(function(){
    $("#tab4Pane").show();
});
EOQ;
require_once 'inc/foot.php';
?>