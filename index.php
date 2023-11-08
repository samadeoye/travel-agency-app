<?php
require_once 'inc/utils.php';
use AbcTravels\Tour\Tour;
use AbcTravels\Functions;
$pageTitle = 'Home';
require_once 'inc/head.php';
?>

<!-- Banner Area Start !-->
<div class="slider-area style-3">
    <img class="shape-1 wow slideInLeft" src="images/shape/plus-element.png" alt="Shape">
    <img class="shape-2 wow zoomInUp" src="images/shape/dots-black-2.png" alt="Shape">
    <div class="banner-shape">
        <img class="shape" src="images/shape/banner-shape-1.png" alt="">
        <img class="bg-img" src="images/hero-section/bg-3.jpg" alt="Bg Image">
    </div>
    <div class="slider-wrapper">
        <!-- single slider start -->
        <div class="single-slider-wrapper">
            <div class="single-slider" >
                <div class="slider-overlay"></div>
                <div class="container h-100 align-self-center">
                    <div class="row h-100">
                        <div class="col-lg-6 col-md-8 align-self-center">
                            <div class="slider-content-wrapper">
                                <div class="slider-content">
                                    <span class="slider-short-title">Tour and Travels</span>
                                    <h1 class="slider-title">Experience the ultimate luxury</h1>
                                    <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                                    <div class="slider-btn-wrapper">
                                        <a href="tours" class="theme-btn ">Get started</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- single slider end -->
    </div>
</div>
<!-- Banner Area End !-->

<?php
echo Functions::getToursSearchForm();
?>

<div class="location-slider-area style-2">
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="section-title text-start">
                    <div class="sec-content">
                        <span class="short-title">Popular</span>
                        <h2 class="title">Tour Packages</h2>
                        <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4">
        <?php
            //TODO: Special packages are also listed here - confirm if to show.
            //Also, add content for Culinary tour or make as custom
            $rs = Tour::getToursHomePage(['*'], 6);
            foreach($rs as $r)
            {
                $img = !empty($r['img']) ? $r['img'] : 'boxed-bg.png';
                $imgPath = 'images/tour/'.$img;
                $title = stringToTitle($r['title']);
                ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                    <div class="location-card style-2">
                        <div class="image-wrapper">
                            <div class="image-inner tourImgWrapper">
                                <a href="tour-details?package=<?php echo $r['short_name'];?>"><img src="<?php echo $imgPath;?>" alt="<?php echo $title;?>"></a>
                            </div>
                            <div class="rating">
                                <div class="ratting-inner">
                                    <span><i class="fa-solid fa-star"></i></span>
                                    <span><i class="fa-solid fa-star"></i></span>
                                    <span><i class="fa-solid fa-star"></i></span>
                                    <span><i class="fa-solid fa-star"></i></span>
                                    <span><i class="fa-solid fa-star"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="content-wrapper">
                            <div class="content-inner">
                                <span class="font-size-15 text-secondary">From</span>
                                <h5 class="price" style="padding-bottom:0px;">$<?php echo doNumberFormat($r['price']);?> <span class="font-size-15 text-secondary">Per person</span></h5>
                                <span class="content-title"><a href="tour-details?package=<?php echo $r['short_name'];?>" class="font-size-20"><?php echo $title;?></a></span>
                                <span class="badge bg-theme"><i class="fa-solid fa-clock"></i> <?php echo $r['days'];?> Days</span>
                                <div class="time-zone">
                                    <div class="time-zone-inner">
                                        <i class="fa-solid fa-bed"></i>
                                    </div>
                                    <div class="time-zone-inner">
                                        <i class="fa-solid fa-plane-up"></i>
                                    </div>
                                    <div class="time-zone-inner">
                                        <i class="fa-solid fa-car"></i>
                                    </div>
                                    <div class="time-zone-inner">
                                        <i class="fa-solid fa-utensils"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            
        </div>
        <div class="row py-4">
            <div class="col-md-6 text-center mx-auto">
                <div class="btn-wrapper">
                    <a href="tours" class="theme-btn">More Tour Packages</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="about-us-area style-3 grey-bg">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-6 align-middle">
                <div class="about-us-wrapper">
                    <div class="about-us-content-wrapper-2 wow fadeInLeft" data-wow-delay=".4s">
                        <div class="section-title">
                            <div class="sec-content">
                                <h2 class="title">Who We Are</h2>
                                <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                            </div>
                            <div class="sec-desc">
                                <p class="desc">Welcome to <?php echo $arSiteSettings['name'];?>. We are a dedicated travel destination management company in Sri Lanka. We started off our journey from Sri Lanka in the first place, but over the years, we have expanded our reach to several countries in the World.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="about-us-wrapper">
                    <div class="about-us-content-wrapper-2 wow fadeInLeft" data-wow-delay=".4s">
                        <div class="section-title">
                            <div class="sec-content">
                                <span class="short-title">Why Choose Us?</span>
                            </div>
                            <div class="sec-desc">
                                <p class="desc">A number of people have chosen us as their number one travel destination management company and you will also choose us for the following reasons.</p>
                                <ul class="list-unstyled">
                                    <li><i class="fa-solid fa-check-to-slot color-theme"></i> We are one of the top destination management companies in Sri Lanka.</li>
                                    <li><i class="fa-solid fa-check-to-slot color-theme"></i> We are one of the leading travel agents in Sri Lanka.</li>
                                    <li><i class="fa-solid fa-check-to-slot color-theme"></i> We are one of the leading tour operators in Sri Lanka.</li>
                                    <li><i class="fa-solid fa-check-to-slot color-theme"></i> We provide custom-made & specialized tours to our clients.</li>
                                    <li><i class="fa-solid fa-check-to-slot color-theme"></i> We offer a complete travel package to travelers and travel agents.</li>
                                    <li><i class="fa-solid fa-check-to-slot color-theme"></i> We are a leading travel agent in Sri Lanka who provides tours and holiday packages to exotic destinations.</li>
                                    <li><i class="fa-solid fa-check-to-slot color-theme"></i> We have a passionate and professional team consisting of 40 representatives who possess extensive knowledge on the Sri Lanka tourism industry.</li>
                                    <li><i class="fa-solid fa-check-to-slot color-theme"></i> We offer 24/7 customer support to our clients.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="btn-wrapper">
                            <a href="about" class="theme-btn">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
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