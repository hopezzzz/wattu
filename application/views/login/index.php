<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
    <meta name="author" content="GeeksLabs">
    <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Watervale</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $this->config->item('assets_path'); ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- bootstrap theme -->
    <link href="<?php echo site_url(); ?>assets/css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="<?php echo site_url(); ?>assets/css/elegant-icons-style.css" rel="stylesheet" />
    <link href="<?php echo site_url(); ?>assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="<?php echo site_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo site_url(); ?>assets/css/style-responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo $this->config->item('assets_path');?>alert/dist/css/iziToast.min.css">
    <script src="<?php echo $this->config->item('assets_path');?>bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo $this->config->item('assets_path');?>alert/dist/js/iziToast.min.js"></script>
    <!-- <scrtip type="text/javascript">
      jQuery(document).ready(function() {
          alert("io");
          <!-- function myfunction(){
            alert("ok");
          } -->
      });
    </script> -->
</head>
<body>
  <div class="login-img3-body">
    <div class="container">
      <?php echo form_open('login', array('name' => 'login', 'method' => 'post', 'class' => 'login-form', 'id' => "login-form","autocomplete" => "off")); ?>
        <div class="login-wrap">
			       <h2>Watervale App</h2>
               <?php echo validation_errors(); ?>
            <!--p class="login-img"><i class="icon_lock_alt"></i></p-->
            <div class="input-group form-group">
              <span class="input-group-addon"><i class="icon_profile"></i></span>
              <input type="email" class="form-control" name="email" placeholder="Email Address" autocomplete="off" >
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                  <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
            </div>
            <div class="input-group form-group">
              <a style="color: #984de2 !important;" id="kl" href="javascript:void(0)" >Forget Password</a>
            </div>
            <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
        </div>
      </form>
    </div>

    <input type="hidden" value="<?php echo base_url(); ?>" id="site_url">
    <div id="forget-pass">
      <div class="container">
        <?php echo form_open('forget-password', array('name' => 'forget-password', 'method' => 'post', 'class' => 'login-form', 'id' => "forget-form","autocomplete" => "off")); ?>
          <div class="login-wrap">
  			       <h2>FORGOT PASSWORD</h2>
                 <?php echo validation_errors(); ?>
              <!--p class="login-img"><i class="icon_lock_alt"></i></p-->
              <div class="input-group form-group">
                <span class="input-group-addon"><i class="icon_profile"></i></span>
                <input type="email" class="form-control" name="email" placeholder="Email Address" autocomplete="off" >
              </div>
              <div class="input-group form-group" style="padding-bottom: 7px !important;">
                <a style="color: #984de2 !important;" id="backToLogin" href="javascript:void(0)" >Back To Login</a>
              </div>
              <button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
    <!-- jQuery 3 -->

    <script src="<?php echo $this->config->item('assets_path');?>bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo $this->config->item('assets_path');?>/js/jquery.form.js"></script>
    <script src="<?php echo $this->config->item('assets_path');?>/js/alert.js"></script>
    <script src="<?php echo $this->config->item('assets_path');?>/js/jquery.toast.js"></script>
    <script src="<?php echo $this->config->item('assets_path');?>/js/jquery.validate.js"></script>
    <script src="<?php echo $this->config->item('assets_path');?>/js/jquery.validate.min.js"></script>
    <script src="<?php echo $this->config->item('assets_path');?>/js/form-validate.js"></script>
    <script src="<?php echo $this->config->item('assets_path');?>/js/jquery.mask.js"></script>
    <script src="<?php echo $this->config->item('assets_path'); ?>js/custom.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo $this->config->item('assets_path'); ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  </body>
</html>
