<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="watervale project">
  <meta name="author" content="1wayit.com">
  <meta name="keyword" content="amazing wook word fantastic stuff">
  <link rel="shortcut icon" href="img/favicon.png">
  <title><?php echo $title ?></title>
  <!-- Bootstrap CSS -->
  <link href="<?php echo $this->config->item('assets_path');?>/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets_path');?>/css/bootstrap-theme.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets_path');?>/css/elegant-icons-style.css" rel="stylesheet" />
  <link href="<?php echo $this->config->item('assets_path');?>/css/font-awesome.min.css" rel="stylesheet" />
  <link href="<?php echo $this->config->item('assets_path');?>/css/jquery-jvectormap-1.2.2.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets_path');?>/css/style-responsive.css" rel="stylesheet" />
  <link href="<?php echo $this->config->item('assets_path');?>/css/xcharts.min.css" rel=" stylesheet">
  <link href="<?php echo $this->config->item('assets_path');?>/css/jquery-ui-1.10.4.min.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets_path');?>alert/dist/css/iziToast.min.css" rel="stylesheet">
  <script src="<?php echo $this->config->item('assets_path');?>bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo $this->config->item('assets_path');?>alert/dist/js/iziToast.min.js"></script>
  <link href="<?php echo $this->config->item('assets_path');?>/css/widgets.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets_path');?>/css/style.css" rel="stylesheet">

  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />

</head>

<body>
  <!-- container section start -->
  <section id="container" class="">
    <header class="header dark-bg">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>

      <!--logo start-->
      <a href="<?php echo base_url();?>" class="logo">WATERVALE  <span class="lite">APP</span></a>
      <!--logo end-->
      <div class="nav search-row" id="top_menu">
        <!--  search form start -->
        <ul class="nav top-menu">
          <li>
              <input class="globalSearch form-control" placeholder="Search" type="text">
          </li>
          <li class="globalSearchList"></li>
        </ul>
        <!--  search form end -->
      </div>

      <div class="top-nav notification-row">
        <!-- notificatoin dropdown start-->
        <ul class="nav pull-right top-menu">


          <!-- inbox notificatoin start-->
          <li id="mail_notificatoin_bar" class="dropdown">
            <a  href="<?php echo base_url();?>notifications">
              <i class="fa fa-bell"></i>
              <span class="badge bg-important"><?php echo getNotificationCount(); ?></span>
            </a>
          </li>
          <!-- inbox notificatoin end -->
          <!-- user login dropdown start-->
          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
              <span class="profile-ava">
                <img alt="">
              </span>
              <span class="username">
                <?php
                $loginSessionData = $this->session->userdata('clientData');
                if (!empty($loginSessionData)){ ?>
                  <?php echo $loginSessionData['userName']; ?>

                </span>
              <?php } ?>

              <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
              <div class="log-arrow-up"></div>
              <li class="eborder-top">
                <a href="<?php echo base_url();?>my-profile"><i class="icon_profile"></i> My Profile</a>
              </li>

              <li>
                <a href="<?php echo base_url();?>logout"><i class="icon_key_alt"></i> Log Out</a>
              </li>

            </ul>
          </li>
          <!-- user login dropdown end -->
        </ul>
        <!-- notificatoin dropdown end-->
      </div>
    </header>
    <!--header end-->

    <!--sidebar start-->
    <aside>
      <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->

        <ul class="sidebar-menu">
          <?php if ($loginSessionData['userType'] != 1): ?>
            <li>
              <a class="" href="<?php echo base_url();?>home">
                <i class="fa fa-home"></i>
                <span>Home</span>
              </a>
            </li>
          <?php endif; ?>
           <li>
          <a class="" href="<?php echo base_url();?>dashboard">
          <i class="fa fa-tachometer"></i>
          <span>Dashboard</span>
          </a>
      </li>


      <li class="sub-menu">


        <a data-toggle="collapse" href="#ordersnav" role="button" aria-expanded="false" aria-controls="ordersnav">
          <i class="fa fa-copy"></i>
          <span>Orders</span>
          <span class="menu-arrow arrow_carrot-right"></span>
        </a>
        <div class="collapse" id="ordersnav">
          <ul class="sub">
  					  <li><a href="<?php echo base_url();?>sales">Sales</a></li>
              <li><a href="<?php echo base_url();?>production-processing">Production Processing</a></li>
              <li><a href="<?php echo base_url();?>approval">Approval</a></li>
              <li><a href="<?php echo base_url();?>dispatch-processing">Dispatch Processing</a></li>
              <li><a href="<?php echo base_url();?>customer-follow-up-orders">Customer Follow-Up</a></li>
            </ul>
        </div>

        </li>
        <li>
           <a class="" href="<?php echo base_url();?>customers-list">
             <i class="fa fa-users"></i>
             <span>Customer List</span>
           </a>
        </li>


      <li>
         <a class="" href="<?php echo base_url();?>items">
           <i class="fa fa-copy"></i>
           <span>Items List</span>
         </a>
      </li>


      <?php if ($loginSessionData['userType'] == 1): ?>
         <li>
        <a class="" href="<?php echo base_url();?>users">
          <i class="fa fa-users"></i>
          <span>Users</span>
        </a>
      </li>
      <?php endif ?>



      <li class="sub-menu">


        <a data-toggle="collapse" href="#settingsss" role="button" aria-expanded="false" aria-controls="settingsss">
          <i class="fa fa-copy"></i>
          <span>Settings</span>
          <span class="<?php if(isset($parentUrlActive) && $parentUrlActive == "Setting Management") echo "menu-arrow arrow_carrot-down"; else echo "menu-arrow arrow_carrot-right";?> "></span>
        </a>
        <div class="collapse" id="settingsss">
          <ul class="sub">
            <li><a href="<?php echo base_url();?>delivery-method">Delivery Method</a></li>
            <li><a href="<?php echo base_url();?>pricing-method">Pricing Policy Method</a></li>
            <li><a href="<?php echo base_url();?>categories">Category</a></li>
            <li><a href="<?php echo base_url();?>unit-of-measurement">Unit of measurement</a></li>
            <li><a href="<?php echo base_url();?>regions">Regions</a></li>
            <li><a href="<?php echo base_url();?>transport-charges">Transport Charges</a></li>
            <!-- <li><a href="<?php echo base_url();?>production-output">Production Output</a></li> -->
            <li><a href="<?php echo base_url();?>add-new-production-output">Production Output</a></li>
            <li><a href="<?php echo base_url();?>loading-sheets">Loading Sheets</a></li>
            <li><a href="<?php echo base_url();?>block-types">Block Types</a></li>
            </ul>
        </div>

        </li>



</ul>
<!-- sidebar menu end-->
</div>
</aside>
<!--sidebar end-->
<section id="main-content">
  <section class="wrapper">
    <!--overview start-->
    <div class="row">
      <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-users"></i> <?php echo $breadcrumbs ?></h3>
          <?php echo $breadcrumb ?>
      </div>
    </div>
    <div class="loader_div" style="display:none">
      <div class="spinner">
          <div class="rect1"></div>
          <div class="rect2"></div>
          <div class="rect3"></div>
          <div class="rect4"></div>
          <div class="rect5"></div>
        </div>
    </div>
