<?php
require_once 'inc/utils.php';
use AbcTravels\Tour\Tour;
use AbcTravels\Destination\Destination;
use AbcTravels\Functions;
$pageTitle = 'Tours';
$destinationId = $destinationName = $faqs = '';
$package = isset($_GET['package']) ? trim($_GET['package']) : '';
$tourCountry = isset($_GET['tourCountry']) ? trim($_GET['tourCountry']) : '';
$tourDuration = isset($_GET['tourDuration']) ? doTypeCastInt($_GET['tourDuration']) : 0;

if ($tourCountry != '')
{
    $package = $tourCountry;
}

$redirect = true;
if ($package != '')
{
    $rs = Destination::getDestinationInfoByShortName($package, ['id', 'name', 'faqs']);
    if ($rs)
    {
        $destinationId = $rs['id'];
        $destinationName = stringToTitle($rs['name']);
        $faqs = $rs['faqs'];
        if (strlen($destinationId) == 36)
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

<!-- Customize a Trip Modal -->
<div class="modal fade" id="customizeTripModal" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="customizeTripModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customize a Trip</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="row g-3" id="customizeTripForm" onsubmit="return false;">
                    <input type="hidden" name="action" value="customizeTrip">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="destination" class="form-label">Your Destination</label>
                            <select class="form-select" id="destination" name="destination">
                                <?php
                                    echo AbcTravels\Destination\Destination::getDestinationsDropdownOptions();
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tourDuration" class="form-label">Tour Duration</label>
                            <select class="form-select" id="tourDuration" name="tourDuration">
                            <?php
                                echo AbcTravels\Functions::getTourDaysDropdownOptions();
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="name" class="form-control" id="name" name="name">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="col-md-6">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="tel" class="form-control" id="mobile" name="mobile">
                    </div>
                    <div class="col-md-6">
                        <label for="nationality" class="form-label">Your Country</label>
                        <select id="nationality" name="nationality" class="form-select">
                        <?php
                            echo AbcTravels\Functions::getCountriesDropdownOptions();
                        ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="travellingDate" class="form-label">Travelling Date</label>
                        <?php
                            $minDate = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
                        ?>
                        <input type="date" class="form-control" id="travellingDate" name="travellingDate" min="<?php echo $minDate;?>">
                    </div>
                    <div class="col-md-12">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea class="form-control" name="message" id="message" cols="30" rows="4"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div class="googleRecaptcha" id="customizeTripFormRecaptcha"></div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12">
                            <button type="button" class="theme-btn" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="theme-btn" onclick="invokeCustomizeTripFormProcess()">Send</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

<!-- Page Header Start !-->
<div class="page-breadcrumb-area page-bg" style="background-image: url('images/breadcrumb/bg.jpg')"></div>
<!-- Page Header End !-->

<?php
echo Functions::getToursSearchForm();
?>

<div class="container pb-4">
    <div class="row">
        <div class="col-md-12 font-size-15">
            <a href="<?php echo DEF_ROOT_PATH;?>"><i class="fa-solid fa-house"></i> Home</a>
            <a href="tours"> <i class="fa-solid fa-angle-right"></i> Tours</a>
            <a class="active"> <i class="fa-solid fa-angle-right"></i> <?php echo $destinationName;?></a>
        </div>
    </div>
</div>

<div class="location-slider-area style-2">
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="section-title text-start">
                    <div class="sec-content">
                        <h2 class="title font-size-30"><?php echo $destinationName;?> Tour Packages</h2>
                        <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4" id="appToursContent">
            <?php
                echo Tour::getToursContent($destinationId, 1, $tourDuration);
            ?>
        </div>
        <div class="row py-4">
            <div class="col-md-6 text-center mx-auto">
                <?php
                    echo Tour::getToursPagination($destinationId, 1, $tourDuration);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="location-slider-area style-2">
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="section-title text-start">
                    <div class="sec-content">
                        <h2 class="title font-size-30"> Special <?php echo $destinationName;?> Packages</h2>
                        <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4" id="appSpecialToursContent">
            <?php
                echo Tour::getSpecialToursContent($destinationId, 1, $tourDuration);
            ?>
            
        </div>
        <div class="row py-4">
            <div class="col-md-6 text-center mx-auto">
                <?php
                    echo Tour::getSpecialToursPagination($destinationId, 1, $tourDuration);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="container py-5 px-3">
    <div class="row d-flex align-items-center">
        <div class="col-md-6">
            <h6 class="title">Why Book with <?php echo $arSiteSettings['name'];?>?</h6>
            <p>As a travel hub, Sri Lanka has many travel agencies. Why is <?php echo $arSiteSettings['name'];?> the best choice? We believe that a perfectly planned holiday is desiring and exciting. Therefore, by combining your pleasure points with our skill and knowledge, we create the dream Sri Lanka tour for you!</p>
            <p>So why are we so highly reputed in catering the perfect Sri Lanka tour packages?</p>
            <ul class="list-unstyled">
                <li><i class="fa-solid fa-check-to-slot color-theme"></i> Our mission is to give you the best experience for the best price</li>
                <li><i class="fa-solid fa-check-to-slot color-theme"></i> One-on-one friendly customer support throughout</li>
                <li><i class="fa-solid fa-check-to-slot color-theme"></i> 24/7 online support to keep you at ease</li>
                <li><i class="fa-solid fa-check-to-slot color-theme"></i> Our tour consultants know the attractions of all destinations in Sri Lanka</li>
                <li><i class="fa-solid fa-check-to-slot color-theme"></i> Look forward to discounts and 100% price contracts with the resorts</li>
                <li><i class="fa-solid fa-check-to-slot color-theme"></i> A range of great options to suit any budget</li>
                <li><i class="fa-solid fa-check-to-slot color-theme"></i> Flexible cancellations</li>
                <li><i class="fa-solid fa-check-to-slot color-theme"></i> Air tickets, tour bookings, transport services, visa & insurance all covered</li>
            </ul>
        </div>
        <div class="col-md-6 mx-auto enquire-now-form">
        <h6 class="title">Enquire Now</h6>
        <?php
            echo Functions::getCommonEnquiryForm($destinationId, 1);
        ?>
        </div>
    </div>
</div>

<div class="why-choose-us-area style-3">
    <img class="shape" src="images/shape/dots-black.png" alt="Shape">
    <div class="container">
        <div class="section-title justify-content-center text-center align-items-center">
            <div class="sec-content">
                <h2 class="title">How it Works</h2>
            </div>
        </div>
        <div class="row gy-4 mt-lg-65 mt-20">
            <div class="col-md-3 wow fadeInRight" data-wow-delay="0s">
                <div class="info-card style-2">
                    <i class="fa-solid fa-phone-volume abc-how-icon"></i>
                    <div class="content">
                        <h6 class="title">Enquire Now</h6>
                        <p class="desc">You can enquire by filling the web form, send an email or call us.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 wow fadeInRight" data-wow-delay=".4s">
                    <div class="info-card style-2">
                    <i class="fa-solid fa-user-tie abc-how-icon"></i>
                    <div class="content">
                        <h6 class="title">Connect With Travel Expert</h6>
                        <p class="desc">Discuss your requirement and get free expert travel advice.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 wow fadeInRight" data-wow-delay=".8s">
                <div class="info-card style-2">
                    <i class="fa-regular fa-rectangle-list abc-how-icon"></i>
                    <div class="content">
                        <h6 class="title">Receive 3 Travel Options</h6>
                        <p class="desc">Discuss changes, finalise trip plan & pay 10%.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 wow fadeInRight" data-wow-delay="1.2s">
                <div class="info-card style-2">
                    <i class="fa-regular fa-envelope-open abc-how-icon"></i>
                    <div class="content">
                        <h6 class="title">Receive Your Trip Confirmation</h6>
                        <p class="desc">100% satisfaction guaranteed.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if ($faqs != '') { ?>
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <!-- <h6 class="title">Frequently Asked Questions</h6> -->
            <div class="post-card-faq">
                <!-- Accordion Start -->
                <div class="accordion-wrapper style-two">
                    <div class="accordion-box-wrapper" id="appointmentAreaStyle1FAQ">
                        <!-- Single Accordion Start -->
                        <div class="accordion-list-item">
                            <div id="headingOne">
                                <div class="accordion-head"  role="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <h3 class="title font-size-25">Frequently Asked Questions</h3>
                                </div>
                            </div>
                            <div id="collapseOne" role="button" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#appointmentAreaStyle1FAQ">
                                <div class="accordion-item-body">
                                    <?php echo $faqs;?>
                                </div>
                            </div>
                        </div>
                        <!-- Single Accordion End -->
                    </div>
                </div>
                <!-- Accordion End -->
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php
$arAdditionalJs[] = <<<EOQ
var customizeTripFormWidgetId = 0;
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
                destinationId: '{$destinationId}',
                tourDuration: '{$tourDuration}',
                action: 'getToursPaginationData'
            },
            beforeSend: function() {
                enableDisableBtn('#appToursContent #btnEditListing', 0);
            },
            complete: function() {
                enableDisableBtn('#appToursContent #btnEditListing', 1);
            },
            success: function(data)
            {
                if(data.status == true)
                {
                    $("#appToursContent").html(data.data['list']);
                    $("#appToursPagination").html(data.data['pagination']);
                }
            }
        });
    }
}

function showSpecialPagination(page)
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
                destinationId: '{$destinationId}',
                tourDuration: '{$tourDuration}',
                action: 'getSpecialToursPaginationData'
            },
            beforeSend: function() {
                enableDisableBtn('#appSpecialToursContent #btnEditListing', 0);
            },
            complete: function() {
                enableDisableBtn('#appSpecialToursContent #btnEditListing', 1);
            },
            success: function(data)
            {
                if(data.status == true)
                {
                    $("#appSpecialToursContent").html(data.data['list']);
                    $("#appSpecialToursPagination").html(data.data['pagination']);
                }
            }
        });
    }
}

function openCustomizeTripModal()
{
    $('#customizeTripModal').modal('show');
    getRecaptcha();
}

function getRecaptcha()
{
    if (customizeTripFormWidgetId == 0)
    {
        customizeTripFormWidgetId = grecaptcha.render(
            'customizeTripFormRecaptcha'
            , {"sitekey": gSiteKey}
        );
    }
}

function invokeCustomizeTripFormProcess()
{
    var formId = '#customizeTripForm';
    var name = $(formId+' #name').val();
    var email = $(formId+' #email').val();
    var mobile = $(formId+' #mobile').val();
    var nationality = $(formId+' #nationality').val();
    var destination = $(formId+' #destination').val();

    if (name.length < 3 || email.length < 13 || mobile.length < 6 || nationality.length < 3 || destination.length < 3)
    {
        throwError('Please fill all required fields with valid details.', 'toast-top-right');
    }
    else
    {
        var form = $("#customizeTripForm");
        $.ajax({
            url: 'inc/actions',
            type: 'POST',
            dataType: 'json',
            data: form.serialize(),
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
                    throwSuccess('Customization sent successfully!', 'toast-top-right');
                    form[0].reset();
                    grecaptcha.reset(customizeTripFormWidgetId);
                    closeModal('customizeTripModal', false);
                }
                else
                {
                    if(data.info !== undefined)
                    {
                        throwInfo(data.msg, 'toast-top-right');
                    }
                    else
                    {
                        throwError(data.msg, 'toast-top-right');
                    }
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