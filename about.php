<?php
require_once 'inc/utils.php';
$pageTitle = 'About';
require_once 'inc/head.php';
?>

<!-- Page Header Start !-->
<div class="page-breadcrumb-area page-bg" style="background-image: url('images/breadcrumb/tour.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-wrapper">
                    <div class="page-heading">
                        <h3 class="page-title"><?php echo $pageTitle;?></h3>
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
                <div class="col-md-6 order-2 wow fadeInLeft" data-wow-delay=".4s">  
                    <div class="about-us-content-wrapper-1">
                        <div class="section-title">
                            <div class="sec-content">
                                <span class="short-title"><?php echo $pageTitle;?></span>
                                <h2 class="title">We create journeys for the excited travellers</h2>
                                <img class="bottom-shape" src="images/shape/bottom-bar.png" alt="Bottom Shape">
                            </div>
                        </div>
                        <div class="info-card style-1">
                            <div class="content text-justify">
                                <p class="desc">
                                    Welcome to <?php echo $arSiteSettings['name'];?> - Your Passport to Unforgettable Adventures!<br>
                                    At <?php echo $arSiteSettings['name'];?>, we're passionate about travel and committed to making your journeys extraordinary. We understand that every trip is a chapter in your life story, and we're here to ensure that each chapter is filled with unforgettable experiences, cultural immersion, and moments that take your breath away. 
                                </p>
                                <h3 class="font-size-20 pt-3">Our Mission</h3>
                                <p class="desc">
                                    Our mission is to inspire and facilitate travel experiences that go beyond the ordinary. We're not just about booking flights and hotels; we're about crafting immersive, tailor-made adventures that capture the essence of each destination. Whether you're seeking relaxation on a pristine beach, cultural exploration in ancient cities, or thrilling escapades in the great outdoors, we have the expertise and passion to turn your travel dreams into reality.
                                </p>
                                <h3 class="font-size-20 pt-3">Why Choose <?php echo $arSiteSettings['name'];?>?</h3>
                                <p class="desc">
                                    <ul class="list-unstyled desc">
                                        <li><i class="fas fa-check-circle"></i> Expertise: Our team consists of avid travelers with years of experience exploring the world. We've been to the destinations we recommend, and we have firsthand knowledge of what makes each place special.</li>
                                        <li><i class="fas fa-check-circle"></i> Personalization: We understand that no two travelers are the same. That's why we work closely with you to create custom itineraries that match your interests, preferences, and budget.</li>
                                        <li><i class="fas fa-check-circle"></i> Trusted Partners: We have established relationships with the best hotels, airlines, tour operators, and local guides, ensuring you receive top-quality service throughout your journey.</li>
                                        <li><i class="fas fa-check-circle"></i> Safety and Support: Your safety and comfort are our top priorities. We provide 24/7 support during your travels and have a network of contacts to assist you in case of any unforeseen circumstances.</li>
                                        <li><i class="fas fa-check-circle"></i> Sustainability: We're committed to responsible tourism and strive to minimize the impact of travel on the environment and local communities.</li>
                                    </ul>
                                </p>
                                <h3 class="font-size-20">Get in Touch</h3>
                                <p class="desc">
                                    We can't wait to start planning your next adventure. Whether you're dreaming of a romantic getaway, a family expedition, or a solo exploration, <?php echo $arSiteSettings['name'];?> is here to make it happen. Let's embark on this journey together.<br>
                                    Contact us today to begin your travel adventure. Your world of discovery awaits!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 order-xl-2 order-1 align-middle wow fadeInRight" data-wow-delay=".4s">
                    <div class="about-us-image-wrapper-1">
                        <img class="bg-shape" src="images/about-us-area/image-wrapper-bg-1.png" alt="Shape">
                        <div class="image-wrapper style-1">
                            <img src="images/about-us-area/img-1.jpg" alt=" Vacation Image">
                        </div>
                        <div class="image-wrapper style-2">
                            <img src="images/about-us-area/img-2.jpg" alt=" Vacation Image">
                        </div>
                        <div class="image-wrapper style-3">
                            <img src="images/about-us-area/img-3.jpg" alt=" Vacation Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'inc/foot.php';
?>