<?php
require_once 'inc/utils.php';
$pageTitle = 'Contact';
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

<div class="contact-info-area">
    <div class="container">
        <div class="row gx-xl-5 gy-4">
            <div class="col-xl-4 col-md-6">
                <div class="icon-card style-2">
                    <div class="icon">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div class="content">
                        <h2 class="title">Contact number</h2>
                        <div class="info">
                            <a href="tel:<?php echo $arSiteSettings['phone'];?>" class="desc"><?php echo $arSiteSettings['phone'];?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="icon-card style-2">
                    <div class="icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div class="content">
                        <h2 class="title">Email address</h2>
                        <div class="info">
                            <a href="mailto:<?php echo $arSiteSettings['email'];?>" class="desc"><?php echo $arSiteSettings['email'];?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="icon-card style-2">
                    <div class="icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="content">
                        <h2 class="title">Office address</h2>
                        <div class="info">
                            <span class="address-desc"><?php echo $arSiteSettings['address'];?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact-form-area">
    <div class="container">
        <div class="row gy-5">
            <div class="col-md-7 mx-auto">
                <p class="text-center p-1">Send us your enquiry and we will respond as soon as possible.</p>
                <div class="comment-respond">
                    <form method="post" class="comment-form" id="contactForm" onsubmit="return false;">
                        <input type="hidden" name="action" value="addContact">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="contacts-name">
                                    <label for="name">Full Name</label>
                                    <input name="name" id="name" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contacts-email">
                                    <label for="email">Email</label>
                                    <input name="email" id="email" type="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contacts-phone">
                                    <label for="mobile">Phone Number</label>
                                    <input name="mobile" id="mobile" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contacts-subject">
                                    <label for="subject">Subject</label>
                                    <input name="subject" id="subject" type="text">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contacts-message">
                                    <label for="message">Your Message</label>
                                    <textarea name="message" id="message" cols="20" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php echo AbcTravels\Functions::getTermsAndConditionCheckbox(); ?>
                            </div>
                            <div class="col-md-12">
                                <div class="googleRecaptcha" id="contactFormRecaptcha"></div>
                            </div>
                            <div class="col-12">
                                <button class="theme-btn" type="submit" id="btnSubmit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$arAdditionalJs[] = <<<EOQ
var contactFormWidgetId = 0;
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
$('#contactForm #btnSubmit').click(function ()
{
    var formId = '#contactForm';
    var name = $(formId+' #name').val();
    var email = $(formId+' #email').val();
    var mobile = $(formId+' #mobile').val();
    var message = $(formId+' #message').val();
    var termsConditionsChecked = $(formId+' #termsConditions').is(':checked');

    if (!termsConditionsChecked)
    {
        throwError('Please read and check the Terms and Conditions', 'toast-top-right');
    }
    else if (name.length < 3 || email.length < 13 || mobile.length < 6 || message.length < 20)
    {
        throwError('Please fill all required fields with valid details.', 'toast-top-right');
    }
    else
    {
        var form = $("#contactForm");
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
                    throwSuccess('Message sent successfully!', 'toast-top-right');
                    form[0].reset();
                    grecaptcha.reset(contactFormWidgetId);
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
});

setTimeout(function() {
    contactFormWidgetId = grecaptcha.render(
        'contactFormRecaptcha'
        , {"sitekey": gSiteKey}
    );
}, 1000);

EOQ;
require_once 'inc/foot.php';
?>