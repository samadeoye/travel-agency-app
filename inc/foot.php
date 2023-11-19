<!--- Start Footer !-->
<footer class="footer bg-light-black">
    <div class="footer-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-widget">
                        <div class="footer-widget-logo">
                            <div class="footer-logo">
                                <!-- <a href="<?php echo DEF_ROOT_PATH;?>"><img src="images/logo/footer-logo.png" alt="Footer Logo"/></a> -->
                                <a href="<?php echo DEF_ROOT_PATH;?>" class="fw-bold"><?php echo $arSiteSettings['name'];?></a>
                            </div>
                        </div>
                        <div class="footer-widget-contact">
                            <p class="desc">Your number one travel destination management company</p>
                            <div class="footer-contact">
                                <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                            </div>
                            <div class="contact-text">
                                <span><?php echo $arSiteSettings['address'];?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="footer-widget-menu-wrapper">
                        <div class="footer-widget widget_nav_menu">
                            <h2 class="footer-widget-title"><?php echo $arSiteSettings['name'];?></h2>
                            <ul class="menu">
                                <li><a href="<?php echo DEF_ROOT_PATH;?>">Home</a></li>
                                <li><a href="about">About</a></li>
                                <li><a href="vehicles">Vehicles</a></li>
                                <?php
                                if ($arSiteSettings['hotel_link'] != '')
                                { ?>
                                <li><a href="<?php echo $arSiteSettings['hotel_link'];?>" target="_blank">Hotels</a></li>
                                <?php
                                }
                                ?>
                                <li><a href="terms">Terms & Conditions</a></li>
                                <li><a href="javascript:;">Trains<span class="menu-badge badge bg-danger">coming soon</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="footer-widget-menu-wrapper">
                        <div class="footer-widget widget_nav_menu">
                            <h2 class="footer-widget-title">Support</h2>
                            <ul class="menu">
                                <li><a href="contact">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="footer-widget-menu-wrapper">
                        <div class="footer-widget widget_nav_menu">
                            <h2 class="footer-widget-title">Tour Packages</h2>
                            <ul class="menu">
                                <li><a href="tours">Tours</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-widget">
                        <!-- <h2 class="footer-widget-title">Call</h2> -->
                        <div class="footer-widget-contact">
                            <div class="footer-contact">
                                <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                            </div>
                            <div class="contact-text">
                                <a class="text-light" href="tel:<?php echo $arSiteSettings['phone'];?>"><?php echo $arSiteSettings['phone'];?></a>
                            </div>
                            <?php echo $arSiteSettings['phone_others'];?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-widget">
                        <!-- <h2 class="footer-widget-title">Email</h2> -->
                        <div class="footer-widget-contact">
                            <div class="footer-contact">
                                <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                            </div>
                            <div class="contact-text">
                                <a class="text-light" href="mailto:<?php echo $arSiteSettings['email'];?>"><?php echo $arSiteSettings['email'];?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex">
                        <div class="footer-widget">
                            <h2 class="footer-widget-title">Follow us</h2>
                            <div class="footer-widget-social">
                                <div class="social-profile">
                                    <a href="<?php echo $arSiteSettings['facebook'];?>"><i class="fa-brands fa-facebook-f"></i></a>
                                    <a href="<?php echo $arSiteSettings['instagram'];?>"><i class="fa-brands fa-instagram"></i></a>
                                    <a href="<?php echo $arSiteSettings['twitter'];?>"><i class="fa-brands fa-twitter"></i></a>
                                    <a href="<?php echo $arSiteSettings['linkedin'];?>"><i class="fa-brands fa-linkedin-in"></i></a>
                                    <a href="<?php echo $arSiteSettings['youtube'];?>"><i class="fa-brands fa-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <?php
                            if ($arSiteSettings['license_number'] != '')
                            {?>
                                <span class="mt-1 color-p font-size-15">Tourist Board License No: <?php echo $arSiteSettings['license_number'];?></span>
                            <?php
                            }
                        ?>
                    </div>
                    <div class="d-flex">
                        <div class="my-2">
                            <span class="font-size-15">We Accept These International Payment Systems</span>
                            <div>
                                <img class="img-responsive" src="images/payment/apple-p-f.png" alt="Apple Pay">
                                <img class="img-responsive" src="images/payment/google-p-f.png" alt="Google Pay">
                                <img class="img-responsive" src="images/payment/pay-pal.png" alt="PayPal">
                                <img class="img-responsive" src="images/payment/ali-pay.png" alt="Alipay">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="my-2">
                            <span class="font-size-15">We Accept These Credit & Debit Cards</span>
                            <div>
                                <img class="img-responsive" src="images/payment/visa-card.png" alt="Visa">
                                <img class="img-responsive" src="images/payment/master_card.png" alt="Mastercard">
                                <img class="img-responsive" src="images/payment/american-express.png" alt="American Express">
                                <img class="img-responsive" src="images/payment/union-pay-card.png" alt="Union Pay">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-area">
        <div class="container">
            <div class="footer-bottom-wrapper">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="copyright-text">
                            <p>Copyright © <?php echo date('Y');?> All Rights Reserved. <?php echo $arSiteSettings['name'];?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </footer>
  <!--- End Footer !-->

    <!-- Scroll Up Section Start -->
    <div id="scrollTop" class="scrollup-wrapper">
        <div class="scrollup-btn">
            <i class="fa-solid fa-arrow-up"></i>
        </div>
    </div>
    <!-- Scroll Up Section End -->

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.nice-select.min.js"></script>
  <script src="js/slick.min.js"></script>
  <script src="js/jquery.counterup.min.js"></script>
  <script src="js/waypoints.js"></script>
  <script src="js/jquery.meanmenu.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/inview.min.js"></script>
  <script src="js/wow.js"></script>
  <script src="js/tilt.jquery.js"></script>
  <script src="js/isotope.min.js"></script>
  <script src="js/jquery.imagesloaded.min.js"></script>
  <script src="js/select2.min.js"></script>
  <script src="js/jquery-ui.min.js"></script>
  <script src="js/custom.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="js/functions.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php
if (count($arAdditionalJsScripts) > 0)
{
  echo implode(PHP_EOL, $arAdditionalJsScripts);
}
?>

<script>
var gSiteKey = '<?php echo DEF_GOOGLE_SITE_KEY;?>';
var commonEnquiryFormWidgetId = 0;
<?php
if (count($arAdditionalJs) > 0)
{
  echo implode(PHP_EOL, $arAdditionalJs);
}
?>
</script>

<script>
$(document).ready(function() {
    <?php
    if (count($arAdditionalJsOnLoad) > 0)
    {
        echo implode(PHP_EOL, $arAdditionalJsOnLoad);
    }
    ?>

    $("#commonEnquiryForm #btnSubmit").click(function(){
        var formId = '#commonEnquiryForm';
        var name = $(formId+' #name').val();
        var email = $(formId+' #email').val();
        var mobile = $(formId+' #mobile').val();
        var nationality = $(formId+' #nationality').val();
        var destination = $(formId+' #destination').val();
        var numAdult = $(formId+' #numAdult').val();

        if (name.length < 3 || email.length < 13 || mobile.length < 6 || nationality.length < 3 || destination.length < 3 || numAdult.length < 1)
        {
            throwError('Please fill all required fields with valid details.', 'toast-top-right');
        }
        else
        {
            var form = $("#commonEnquiryForm");
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
                        grecaptcha.reset(commonEnquiryFormWidgetId);
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
    
});
</script>

</body>
</html>