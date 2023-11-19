<?php
$arCurrentPage = getCurrentPage($pageTitle);
AbcTravels\Analytics::logUserAnalytics();
?>

<!DOCTYPE html>
<html lang="eng">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <meta charset="utf-8" />
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
  <meta name="description" content="<?php echo $arSiteSettings['name'];?> - Best place for travels and adventures "/>
  <meta name="keywords" content="travel agency, travel adventures, tourism, agency, vacation planning, trip, journey, exploration, voyage, getaway, adventure, travel guide, holiday destinations" />
  <meta name="author" content="BoomDevs" />
  <base href="<?php echo DEF_ROOT_PATH; ?>/">

  <title><?php echo $arSiteSettings['name']; ?> - <?php echo $pageTitle; ?></title>

  <link rel="icon" type="image/png" href="images/logo/favicon.png"/>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet"> -->
  <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,200;1,300;1,400;1,500;1,600&family=Luckiest+Guy&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fasthand&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/fontawesome.min.css" />
  <link rel="stylesheet" href="css/magnific-popup.css" />
  <link rel="stylesheet" href="css/slick.css" />
  <link rel="stylesheet" href="css/meanmenu.css" />
  <link rel="stylesheet" href="css/nice-select.css" />
  <link rel="stylesheet" href="css/animate.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <?php
  if (count($arAdditionalCSS) > 0)
  {
    echo implode(PHP_EOL, $arAdditionalCSS);
  }
  ?>
</head>

<body>
  <!-- Default Modal -->
  <div class="modal fade" id="defaultModal" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="defaultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content"> </div>
    </div>
  </div>

  <!-- Small Modal -->
  <div class="modal fade" id="smallModal" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content"> </div>
    </div>
  </div>

  <!-- Extra Large Modal -->
  <div class="modal fade" id="extraLargeModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="extraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"> </div>
    </div>
  </div>

  <!-- Large Modal -->
  <div class="modal fade" id="largeModal" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content"> </div>
    </div>
  </div>

  <!-- Header Start !-->
  <header class="header-area">
    <!-- Header Top Start -->
    <div class="header-top">
      <div class="bg-shape">
        <img src="images/shape/top-navbar.png" alt="Bg Shape">
      </div>
      <div class="container position-relative">
      <div class="row">
          <div class="col-xl-8 col-lg-9 d-flex align-items-center justify-content-start">
          <div class="header-top-info">
              <div class="header-contact-info">
                  <span><a href="tel:<?php echo $arSiteSettings['phone'];?>"><i class="fa-solid fa-phone"></i><?php echo $arSiteSettings['phone'];?></a></span>
                  <span><a href="mailto:<?php echo $arSiteSettings['email'];?>"><i class="fa-solid fa-envelope"></i><?php echo $arSiteSettings['email'];?></a></span>
                  <span><span class="contact-info-item"><i class="fa-solid fa-clock"></i>24 x 7 Service</span></span>
              </div>
          </div>
          </div>
          <div class="col-xl-4 col-lg-3 d-flex align-items-center justify-content-end">
          <div class="header-top-info">
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
      </div>
    </div>
    <!-- Header Top End -->
    <!-- Header Nav Menu Start -->
    <div class="header-menu-area sticky-header">
        <div class="container">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-6 col-6 d-flex align-items-center">
                  <!-- <div class="logo">
                    <a href="<?php echo DEF_ROOT_PATH;?>" class="standard-logo"><img src="images/logo/nav-logo.png" alt="logo"/></a>
                    <a href="<?php echo DEF_ROOT_PATH;?>" class="sticky-logo"><img src="images/logo/nav-logo.png" alt="logo"/></a>
                    <a href="<?php echo DEF_ROOT_PATH;?>" class="retina-logo"><img src="images/logo/nav-logo.png" alt="logo"/></a>
                  </div> -->
                  <div class="logo">
                    <a style="width:9em;" href="<?php echo DEF_ROOT_PATH;?>" class="standard-logo fw-bold"><?php echo $arSiteSettings['name'];?></a>
                    <a style="width:9em;" href="<?php echo DEF_ROOT_PATH;?>" class="sticky-logo fw-bold"><?php echo $arSiteSettings['name'];?></a>
                    <a style="width:9em;" href="<?php echo DEF_ROOT_PATH;?>" class="retina-logo fw-bold"><?php echo $arSiteSettings['name'];?></a>
                  </div>
                </div>
                <div class="col-xl-10 col-lg-10 col-md-6 col-6 d-flex align-items-center justify-content-end">
                  <div class="menu d-inline-block">
                    <nav id="main-menu" class="main-menu">
                      <ul>
                        <li class="<?php echo $arCurrentPage['home'];?>">
                          <a href="<?php echo DEF_ROOT_PATH;?>">Home</a>
                        </li>
                        <li class="<?php echo $arCurrentPage['about'];?>"><a href="about">About</a></li>
                        <li class="<?php echo $arCurrentPage['tours'];?>"><a href="tours">Tours</a></li>
                        <li class="<?php echo $arCurrentPage['contact'];?>"><a href="contact">Contact</a></li>
                        <li class="<?php echo $arCurrentPage['vehicles'];?>"><a href="vehicles">Vehicles</a></li>
                        <?php
                        if ($arSiteSettings['hotel_link'] != '')
                        { ?>
                          <li><a href="<?php echo $arSiteSettings['hotel_link'];?>" target="_blank">Hotels</a></li>
                        <?php
                        }
                        ?>
                        <li><a href="javascript:;">Trains<span class="menu-badge badge bg-danger">coming soon</span></a></li>
                      </ul>
                    </nav>
                  </div>
                  <!-- Mobile Menu Toggle Button Start !-->
                  <div class="mobile-menu-bar d-lg-none text-end">
                    <a href="javascript:;" class="mobile-menu-toggle-btn"><i class="fal fa-bars"></i></a>
                  </div>
                  <!-- Mobile Menu Toggle Button End !-->
                </div>
            </div>
        </div>
    </div>
    <!-- Header Nav Menu End -->
  </header>
  <!-- Header End !-->

  <!-- Menu Sidebar Section Start -->
  <div class="menu-sidebar-area">
    <div class="menu-sidebar-wrapper">
      <div class="menu-sidebar-close">
        <button class="menu-sidebar-close-btn" id="menu_sidebar_close_btn">
          <i class="fal fa-times"></i>
        </button>
      </div>
      <div class="menu-sidebar-content">
        <div class="menu-sidebar-logo">
          <!-- <a href="<?php echo DEF_ROOT_PATH;?>"><img src="images/logo/nav-logo.png" alt="logo"/></a> -->
          <a href="<?php echo DEF_ROOT_PATH;?>" class="fw-bold"><?php echo $arSiteSettings['name'];?></a>
        </div>
        <div class="mobile-nav-menu"></div>
        <div class="menu-sidebar-content">
          <div class="menu-sidebar-single-widget">
            <h5 class="menu-sidebar-title">Contact Info</h5>
            <div class="header-contact-info">
              <span><i class="fa-solid fa-location-dot"></i><?php echo $arSiteSettings['address'];?></span>
              <span><a href="mailto:<?php echo $arSiteSettings['email'];?>"><i
                class="fa-solid fa-envelope"></i><?php echo $arSiteSettings['email'];?></a> </span>
              <span><a href="tel:<?php echo $arSiteSettings['phone'];?>"><i class="fa-solid fa-phone"></i><?php echo $arSiteSettings['phone'];?></a></span>
            </div>
            <div class="social-profile">
              <a href="<?php echo $arSiteSettings['facebook'];?>"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="<?php echo $arSiteSettings['instagram'];?>"><i class="fa-brands fa-instagram"></i></a>
              <a href="<?php echo $arSiteSettings['twitter'];?>"><i class="fa-brands fa-twitter"></i></a>
              <a href="<?php echo $arSiteSettings['linkedin'];?>"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Menu Sidebar Section Start -->
  <div class="body-overlay"></div>