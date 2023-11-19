<?php
if (!isset($_SESSION['user']))
{
  blockOutToMainPage();
}
$arCurrentPage = getCurrentPageAdmin($pageTitle);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $arSiteSettings['name'];?> - <?php echo $pageTitle;?></title>
  <base href="<?php echo DEF_ROOT_PATH_ADMIN; ?>/">
  <link rel="icon" type="image/png" href="<?php echo DEF_ROOT_PATH; ?>/images/logo/favicon.png"/>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/css/adminlte.css">
  
  <?php
  if (count($arAdditionalCSS) > 0)
  {
    echo implode(PHP_EOL, $arAdditionalCSS);
  }
  ?>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


<!-- Default Modal -->
<div class="modal fade" id="defaultModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="defaultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- Small Modal -->
<div class="modal fade" id="smallModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="smallModalLabel" aria-hidden="true">
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
<div class="modal fade" id="largeModal" tabindex="-1" data-focus="false" data-keyboard="false" data-backdrop="static" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> </div>
    </div>
</div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo DEF_ROOT_PATH_ADMIN;?>/app/" class="brand-link">
      <span class="brand-text font-weight-bold"><?php echo $arSiteSettings['name'];?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="assets/img/dash_avatar.png" class="img-circle elevation-2" alt="User">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $arUser['fname'] . ' ' . $arUser['lname'];?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item py-2">
            <a href="app/" class="nav-link <?php echo $arCurrentPage['dashboard'];?>">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="app/destinations" class="nav-link <?php echo $arCurrentPage['destinations'];?>">
              <i class="nav-icon fas fa-plane-arrival"></i>
              <p>Destinations</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="javascript:;" class="nav-link <?php echo $arCurrentPage['tours'];?>">
              <i class="nav-icon fas fa-plane-departure"></i>
              <p>
                Tours
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="app/tour" class="nav-link <?php echo $arCurrentPage['addtour'];?>">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add Tour</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="app/tours" class="nav-link <?php echo $arCurrentPage['alltours'];?>">
                  <i class="fas fa-plane-departure nav-icon"></i>
                  <p>All Tours</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item py-2">
            <a href="app/submissions" class="nav-link <?php echo $arCurrentPage['submissions'];?>">
              <i class="nav-icon fas fa-download"></i>
              <p>Submissions</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="app/vehicles" class="nav-link <?php echo $arCurrentPage['vehicles'];?>">
              <i class="nav-icon fas fa-car"></i>
              <p>Vehicles</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="app/terms" class="nav-link <?php echo $arCurrentPage['termsandconditions'];?>">
              <i class="nav-icon fas fa-file-signature"></i>
              <p>Terms & Conditions</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="app/analytics" class="nav-link <?php echo $arCurrentPage['analytics'];?>">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Analytics</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="app/homesliders" class="nav-link <?php echo $arCurrentPage['homepagesliders'];?>">
              <i class="nav-icon fas fa-images"></i>
              <p>Homepage Sliders</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="app/profile" class="nav-link <?php echo $arCurrentPage['profile'];?>">
              <i class="nav-icon fas fa-user"></i>
              <p>Profile</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="app/settings" class="nav-link <?php echo $arCurrentPage['settings'];?>">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings</p>
            </a>
          </li>
          <li class="nav-item py-2">
            <a href="javascript:;" class="nav-link" id="btnLogoutSidebar">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>