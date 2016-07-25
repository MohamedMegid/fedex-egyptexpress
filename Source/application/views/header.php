<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" > <!--<![endif]-->
    <head>
        <title><?php echo lang("EgyptExpress | Welcome..."); ?></title>

        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Favicon -->
        <link rel="shortcut icon" href="favicon.ico">

        <!--CSS Global Compulsory-->
        <link rel="stylesheet" href="<?php echo site_url("webroot/plugins/bootstrap/css/bootstrap.min.css"); ?>">
        <?php
        if($this->lang->lang() == "ar")
        {
           ?>
           <link rel="stylesheet" href="<?php echo site_url("webroot/plugins/bootstrap/css/bootstrap-rtl.min.css"); ?>">
        <?php } ?>

        <link rel="stylesheet" href="<?php echo site_url("webroot/css/jquery_notification.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/header-default.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/footer-v1.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/sky-forms.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/custom-sky-forms.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/page_log_reg_v1.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/page_job.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/app.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/page_job_inner1.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/profile.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/feature_timeline2.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/page_search_inner_tables.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/jquery.mCustomScrollbar.css"); ?>" />
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/style.css"); ?>">

        <!--CSS Implementing Plugins-->
        <link rel="stylesheet" href="<?php echo site_url("webroot/plugins/line-icons/line-icons.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/plugins/font-awesome/css/font-awesome.min.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/plugins/flexslider/flexslider.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("webroot/plugins/parallax-slider/css/parallax-slider.css"); ?>">

        <!--CSS Theme-->
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/orange.css"); ?>">

        <!--CSS Customization-->
        <link rel="stylesheet" href="<?php echo site_url("webroot/css/custom.css"); ?>">
        <?php
        if($this->lang->lang() == "ar")
        {
           ?>
           <link rel="stylesheet" href="<?php echo site_url("webroot/css/style_ar.css"); ?>">
        <?php } ?>
        <script type="text/javascript" src="<?php echo site_url("webroot/js/jquery.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url("webroot/js/jquery_notification_v.1.js"); ?>"></script>
        <script type="text/javascript">


           function notifyMessage(type, msg) {
               showNotification({
                   type: type,
                   message: msg,
                   autoClose: true,
                   duration: 5
               });
           }

        </script>
        <!-- autocomplete-->
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo site_url("webroot/autocomplete/style.css"); ?>">
    </head>

    <body>
        <?php echo $this->session->flashdata('notification'); ?>


        <?php
        $this->load->helper('cookie');
        if(!get_cookie("firstTime"))
        {
           ?>
           <div class="jumbotron" id="localtracking">
               <p><a class="btn-link pull-right" href="JavaScript:void(0)" onclick="goup();">X</a></p>
               <h1 style="font-size: 30px;"><?php echo lang("Hello ,");?></h1>
               <p><?php echo lang("This is local tracking .. if you want the international tracking click on the button below .");?></p>
               <p><a class="btn btn-primary btn-small" href="http://www.fedex.com/eg/" role="button"><?php echo lang("International Tracking");?></a></p>
           </div>
        <?php } ?>
        <div class="wrapper">
            <!--=== Header ===-->
            <div class="header">
                <!-- Topbar -->
                <div class="topbar">
                    <div class="container">
                        <!-- Topbar Navigation -->

                        <ul class="loginbar footer-socials list-inline <?php
                        if($this->lang->lang() == "ar")
                        {
                           echo "pull-left";
                        }
                        else
                        {
                           echo "pull-right";
                        }
                        ?>">
                            <li>
                                <a href="<?php echo lang("social_facebook_link"); ?>" class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Facebook">
                                    <i class="fa fa-facebook upsocial" style="color:#4D70A8;"></i>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo lang("social_google_link"); ?>" class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Google Plus">
                                    <i class="fa fa-google-plus upsocial" style="color:#E3401D;"></i>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo lang("social_linkedin_link"); ?>" class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Linkedin">
                                    <i class="fa fa-linkedin upsocial" style="color:#1687B1;"></i>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo lang("social_twitter_link"); ?>" class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Twitter">
                                    <i class="fa fa-twitter upsocial" style="color:#01BAF2;"></i>
                                </a>
                            </li>
                            <!--                        </ul>
                            
                                                    <ul class="loginbar <?php
                            if($this->lang->lang() == "ar")
                            {
                               echo "pull-left";
                            }
                            else
                            {
                               echo "pull-right";
                            }
                            ?>">-->
                            <!--<li>-->
                                <!--<i class="fa fa-globe"></i>-->
                                <?php
//                                $Language = array('en' => array('target' => 'ar',
//                                                              'title'  => 'العربية'),
//                                               'ar' => array('target' => 'en',
//                                                              'title'  => 'English'));
                                ?>
                                <!--<a href="<?php echo site_url($this->lang->switch_uri($Language[$this->lang->lang()]['target'])) ?>"><?php echo ($Language[$this->lang->lang()]['title']); ?></a>-->

                            <!--</li>-->
                            <li class="topbar-devider"></li>
                            <li><a href="http://www.fedex.com/eg/"><?php echo lang("International Tracking"); ?></a></li>
                            <li class="topbar-devider"></li>
                            <li><a href="<?php echo site_url("home/help"); ?>"><?php echo lang("Help"); ?></a></li>
                            <li class="topbar-devider"></li>
                            <li><a href="<?php echo site_url("home/contact_us"); ?>"><?php echo lang("Contact us"); ?></a></li>
                            <?php
                            if(!$this->auth->isLogin())
                            {
                               ?>
                               <li class="topbar-devider"></li>
                               <li><a href="<?php echo site_url("users/register"); ?>"><?php echo lang("Register"); ?></a></li>
                               <li class="topbar-devider"></li>
                               <li><a href="<?php echo site_url("users/login"); ?>"><?php echo lang("Login"); ?></a></li>
                               <?php
                            }
                            else
                            {
                               ?>
                               <li class="topbar-devider"></li>
                               <li><a href="<?php echo site_url("account/profile"); ?>" style="color:#f46600;"><?php echo lang("Welcome , ") . $this->auth->user("first_name"); ?></a></li>
                               <li class="topbar-devider"></li>
                               <li><a href="<?php echo site_url("users/logout"); ?>"><?php echo lang("Logout"); ?></a></li>
                            <?php } ?>
                            <li class="topbar-devider"></li>
                            <li class="lifedexlogo">
                                <a class="<?php
                                if($this->lang->lang() == "ar")
                                {
                                   echo "pull-left";
                                }
                                else
                                {
                                   echo "pull-right";
                                }
                                ?>" href="http://www.fedex.com/eg/" target="_blank">
                                    <img  class="img-responsive fedexlogo" src="<?php echo site_url("webroot/images/logoold.png"); ?>" alt="Logo" >
                                </a>
                            </li>
                        </ul>
                        <!-- End Topbar Navigation -->
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- End Topbar -->
                <!-- Navbar -->
                <div class="navbar navbar-default mega-menu" role="navigation">
                    <div class="container">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header headerbrand specificmargin">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="fa fa-bars"></span>
                            </button>
                            <a class="navbar-brand" href="<?php echo site_url("home/index"); ?>">
                                <img id="logo-header" class="img img-responsive" src="<?php echo site_url("webroot/images/logo.png"); ?>" alt="Logo">
                            </a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse navbar-responsive-collapse">
                            <ul class="nav navbar-nav">

                                <li class="<?php
                                echo ($this->uri->segment(2) == '' || $this->uri->segment(3) == 'index') ? 'active' : ''
                                ?>">
                                    <a href="<?php echo site_url("home/index"); ?>" class="" data-toggle="">
                                        <?php echo lang("Home"); ?>
                                    </a>
                                </li>
                                <li class="<?php
                                echo hcurrent('about_us', 3);
                                ?>">
                                    <a href="<?php echo site_url("home/about_us"); ?>" class="" data-toggle="">
                                        <?php echo lang("About us"); ?>
                                    </a>
                                </li>
                                <li class="<?php
                                echo hcurrent('services', 3);
                                ?>">
                                    <a href="<?php echo site_url("home/services"); ?>" class="" data-toggle="">
                                        <?php echo lang("Services"); ?>
                                    </a>
                                </li>
                                <li class="<?php
                                echo hcurrent('promotions', 3);
                                ?>">
                                    <a href="<?php echo site_url("home/promotions"); ?>" class="" data-toggle="">
                                        <?php echo lang("Promotions"); ?>
                                    </a>
                                </li>
                                <li class="<?php
                                echo hcurrent('calculator', 3);
                                ?>">
                                    <a href="<?php echo site_url("shipment/calculator"); ?>" class="" data-toggle="">
                                        <?php echo lang("Calculator"); ?>
                                    </a>
                                </li>
                                <li class="<?php
                                echo hcurrent(array('careers',
                                               'jobView'), 3);
                                ?>">
                                    <a href="<?php echo site_url("home/careers"); ?>" class="" data-toggle="">
                                        <?php echo lang("Careers"); ?>
                                    </a>
                                </li>
                                <li class="<?php
                                echo hcurrent('pickupRequest', 3);
                                ?>">
                                    <a href="<?php echo site_url("shipment/pickupRequest"); ?>" class="" data-toggle="">
                                        <?php echo lang("Online Pickup"); ?>
                                    </a>
                                </li>

                            </ul>
                        </div><!--/navbar-collapse-->

                    </div>
                </div>
                <!-- End Navbar -->
            </div>
            <!--=== End Header ===-->
            <script>
               $(window).load(function () {
                   $("#localtracking").animate({
                       opacity: 1,
                       top: "+=150",
                   }, 100, function () {
                   }).show(1500);

               });
               function goup() {
                   $("#localtracking").hide(1500).animate({
                       opacity: 1,
                       top: "-=150",
                   }, 100, function () {
                   });

                   $.post("<?php echo site_url("home/setcookie"); ?>", function (data) {
                   });
               }
            </script>
