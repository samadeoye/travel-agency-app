<?php
require_once 'inc/utils.php';
use AbcTravels\Tour\Tour;
use AbcTravels\Destination\Destination;
use AbcTravels\Functions;
$pageTitle = 'Tours';
$id = $title = $destinationId = $destinationName = $mapIframe = '';
$days = $price = $itenary = $inclusions = $summary = $shortName = '';
$destinationShortName = '';
$package = isset($_GET['package']) ? trim($_GET['package']) : '';
$redirect = true;
if ($package != '')
{
    $rs = Tour::getTourInfoByShortName($package);
    if ($rs)
    {
        $id = $rs['id'];
        $title = stringToTitle($rs['title']);
        $shortName = $rs['short_name'];
        $mapIframe = $rs['map'];
        $days = doTypeCastInt($rs['days']);
        $price = doTypeCastDouble($rs['price']);
        $inclusions = $rs['inclusions'];
        $summary = $rs['summary'];
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
            $itenary = Tour::getItenaryDisplay($arFields);
        }
        $destinationId = $rs['destination_id'];
        $rsx = Destination::getDestination($destinationId, ['name', 'short_name']);
        $destinationName = stringToTitle($rsx['name']);
        $destinationShortName = $rsx['short_name'];
        if (strlen($id) == 36)
        {
            $redirect = false;
        }
    }
}
if ($redirect)
{
    header('location: '.DEF_ROOT_PATH.'/tours');
}
require_once 'inc/head.php';
?>

<!-- Page Header Start !-->
<div class="page-breadcrumb-area page-bg" style="background-image: url('images/breadcrumb/bg.jpg')"></div>
<!-- Page Header End !-->
<div class="container pt-4 pb-4">
    <div class="row">
        <div class="col-md-12 font-size-15">
            <a href="<?php echo DEF_ROOT_PATH;?>"><i class="fa-solid fa-house"></i> Home</a>
            <a href="tours"> <i class="fa-solid fa-angle-right"></i> Tours</a>
            <a href="tour?package=<?php echo $destinationShortName;?>"> <i class="fa-solid fa-angle-right"></i> <?php echo $destinationName;?></a>
            <a class="active"> <i class="fa-solid fa-angle-right"></i> <?php echo $title;?></a>
        </div>
    </div>
</div>

<!-- Tour Details Area Start -->
<div class="blog-area tour-details">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 blog-details-wrapper">
                <h3 class="title font-size-20"><?php echo $title;?></h3>
                <div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="itenary-tab" data-bs-toggle="tab" data-bs-target="#itenary-tab-pane" type="button" role="tab" aria-controls="itenary-tab-pane" aria-selected="true">Itenary</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="inclusions-tab" data-bs-toggle="tab" data-bs-target="#inclusions-tab-pane" type="button" role="tab" aria-controls="inclusions-tab-pane" aria-selected="false">Inclusions</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="false">Summary</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="itenary-tab-pane" role="tabpanel" aria-labelledby="itenary-tab" tabindex="0">
                        <article class="single-post-item">
                            <div class="post-content-wrapper">
                                <div class="post-contentx">
                                    <div class="post-card-faq">
                                        <!-- Accordion Start -->
                                        <div class="accordion-wrapper style-two">
                                            <div class="accordion-box-wrapper" id="appointmentAreaStyle1FAQ">
                                                <?php echo $itenary;?>
                                            </div>
                                        </div>
                                        <!-- Accordion End -->
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="tab-pane fade" id="inclusions-tab-pane" role="tabpanel" aria-labelledby="inclusions-tab" tabindex="0">
                        <?php echo $inclusions;?>
                    </div>
                    <div class="tab-pane fade" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                        <?php echo $summary;?>
                    </div>
                </div>
            </div>
            <!-- Blog Sidebar Start -->
            <div class="col-lg-4 order-1 order-lg-2">
                <div class="sidebar-price">
                    <div class="sidebar-price-inner">
                        <div class="sidebar-price-days">
                            <!-- <h4 class="color-blue">5 DAYS</h4> -->
                            <span class="badge bg-theme text-align-left"><i class="fa-solid fa-clock"></i> <?php echo $days;?> Days</span>
                        </div>
                        <div class="sidebar-price-amount">
                            <h4><span>From</span> US$ <?php echo $price;?></h4>
                            <p>per person</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <a href="javascript:;" onclick="openEnquireNowModal()" class="enq-btn">Enquire Now</a>
                </div>
                <div class="post-card-divider"></div>
                <div class="sidebar">
                    <div class="widget widget_booking_form">
                        <h6 class="title">Book Now</h6>
                        <?php
                            echo Functions::getCommonEnquiryForm($id);
                        ?>
                    </div>
                </div>
                <div class="post-card-divider"></div>
                <div class="map-wedget">
                    <?php echo $mapIframe;?>
                </div>
                <div class="post-card-divider"></div>
                <div class="related-deals">
                    <h3 class="panel-header">Related Tours</h3>
                    <ul class="related-sidebar-link">
                        <?php
                        $rs = Tour::getRelatedTours($destinationId, $id);
                        foreach($rs as $r)
                        { ?>
                            <li><a href="tour-details?package=<?php echo $r['short_name'];?>"><?php echo stringToTitle($r['title']);?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <!-- Blog Sidebar Start -->
        </div>
    </div>
</div>
<!-- Tour Details Area Start -->

<!-- Enquire Now Modal -->
<?php
    echo AbcTravels\Functions::getEnquireNowModal('addTourEnquiry', $id);
?>

<?php
$arAdditionalJs[] = <<<EOQ
var arnNumOfPeople = [1,2,3];
function showNotification()
{
    var lblPeople = 'people';
    var lblIsAre = 'are';
    var numOfPeople = arnNumOfPeople[(Math.floor(Math.random() * arnNumOfPeople.length))];
    if (numOfPeople == 1)
    {
        lblPeople = 'person';
        lblIsAre = 'is';
    }
    throwInfo(numOfPeople + ' ' + lblPeople + ' ' + lblIsAre + ' checking this tour', 'toast-bottom-left');
}

EOQ;
$arAdditionalJsOnLoad[] = <<<EOQ
setTimeout(function() {
    showNotification();
}, 4000);

setInterval(function() {
    showNotification();
}, 25000);

setTimeout(function() {
    commonEnquiryFormWidgetId = grecaptcha.render(
        'commonEnquiryFormRecaptcha'
        , {"sitekey": gSiteKey}
    );
}, 1000);

EOQ;
require_once 'inc/foot.php';
?>