<!--=== Footer Version 1 ===-->
<div class="footer-v1">
    <div class="footer">
        <div class="container">
            <div class="row">
                <!-- About -->
                <div class="col-md-3 md-margin-bottom-40">
                    <a href="<?php echo site_url("home/index"); ?>"><img id="logo-footer" class="footer-logo" src="<?php echo site_url("webroot/images/footer-logo.png"); ?>" alt=""></a>
                    <p class="text-justify"><?php echo lang("footer_info"); ?></p>
                </div><!--/col-md-3-->
                <!-- End About -->

                <!-- Latest -->
                <div class="col-md-3 md-margin-bottom-40">
                    <div class="posts">
                        <div class="headline"><h2><?php echo lang("Track your shipment"); ?></h2></div>
                        <!-- Checkout-Form -->
                        <?php
                        $attributes = array(
                                       "id"     => "sky-form", "class"  => "sky-form",
                                       "target" => "_blank"
                        );
                        echo form_open_multipart("shipment/shipmentHistory", $attributes);
                        ?>
                        <fieldset>
                            <section>
                                <div class="inline-group">
                                    <label class="radio"><input type="radio" name="type" value="single" onchange="$('#bulkf').hide();
                                           $('#bulklink').hide();
                                           $('#singlef').show();
                                                                " checked><i class="rounded-x"></i><?php echo lang("Single AWB No."); ?></label>
                                    <label class="radio"><input type="radio" name="type" value="bulk" onchange="$('#singlef').hide();
                                           $('#bulkf').show();
                                           $('#bulklink').show();
                                                                "><i class="rounded-x"></i><?php echo lang("Bulk AWB No."); ?></label>
                                </div>
                            </section>

                            <section>
                                <label class="">
                                    <input type="text" id="singlef" name="awb_no" class="form-control" placeholder="<?php echo lang("Your waybill number"); ?>">
                                    <input type="file" id="bulkf" name="bulk_awb" class="" placeholder="<?php echo lang("xls file"); ?>" style="display: none">
                                    <a href="<?php echo site_url('webroot/images/example.xlsx'); ?>" target="_blank" id="bulklink" style="display: none" >Download Example</a>
                                </label>
                                <label class="checkbox-inline" for="lastScanf"> <input type="checkbox" name="lastScan" value="1" id="lastScanf"> <?php echo lang('Last Scan'); ?></label>

                            </section>

                            <button type="submit" class="btn-u btn-brd btn-brd-hover btn-u-dark"><?php echo lang("Track"); ?></button>
                        </fieldset>
                        <?php echo form_close(); ?>
                        <footer>

                            <a href="http://www.fedex.com/eg/" class="btn-link" target="_blank"><?php echo lang("International Tracking"); ?></a>
                        </footer>

                        <!-- End Checkout-Form -->
                    </div>
                </div><!--/col-md-3-->
                <!-- End Latest -->

                <!-- Link List -->
                <div class="col-md-3 md-margin-bottom-40">
                    <div class="headline"><h2><?php echo lang("Useful Links"); ?></h2></div>
                    <ul class="list-unstyled link-list">
                        <?php
                        if($this->lang->lang() == "ar")
                        {
                           $arrow = "fa-angle-left";
                        }
                        else
                        {
                           $arrow = "fa-angle-right";
                        }
                        ?>
                        <li><a href="<?php echo site_url("home/about_us"); ?>"><?php echo lang("About us"); ?></a><i class="fa <?php echo $arrow; ?>"></i></li>
                        <li><a href="<?php echo site_url("home/careers"); ?>"><?php echo lang("Careers"); ?></a><i class="fa <?php echo $arrow; ?>"></i></li>
                        <li><a href="<?php echo site_url("home/contact_us"); ?>"><?php echo lang("Contact us"); ?></a><i class="fa <?php echo $arrow; ?>"></i></li>
                        <li><a href="<?php echo site_url("shipment/calculator"); ?>"><?php echo lang("Calculator"); ?></a><i class="fa <?php echo $arrow; ?>"></i></li>
                        <li><a href="<?php echo site_url("shipment/pickupRequest"); ?>"><?php echo lang("Pickup Now"); ?></a><i class="fa <?php echo $arrow; ?>"></i></li>
                        <li><a href="http://www.fedex.com/eg/" target="_blank"><?php echo lang("International Tracking"); ?></a><i class="fa <?php echo $arrow; ?>"></i></li>
                        <li><a href="<?php echo site_url("home/help"); ?>"><?php echo lang("Help"); ?></a><i class="fa <?php echo $arrow; ?>"></i></li>
                    </ul>
                </div><!--/col-md-3-->
                <!-- End Link List -->

                <!-- Address -->
                <div class="col-md-3 map-img md-margin-bottom-40">
                    <div class="headline"><h2><?php echo lang("Contact Us"); ?></h2></div>
                    <address class="md-margin-bottom-40">
                        <span class="largephone"><span class="fa fa-phone"></span><?php echo lang("19985"); ?></span><br />
                        <?php echo lang(" Head Quarter: Plot 2, block 1258w <br />
                        Masaken Sheraton, Heliopolis, Cairo"); ?>
                        <br />
                        <?php echo lang(" Tel: +20 22687999"); ?>
                    </address>
                    <ul class="footer-socials list-inline <?php
                    if($this->lang->lang() == "ar")
                    {
                       echo "pull-right";
                    }
                    else
                    {
                       echo "pull-left";
                    }
                    ?>">
                        <li>
                            <a href="<?php echo lang("social_facebook_link"); ?>" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Facebook">
                                <i style="color:#fff;" class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo lang("social_google_link"); ?>" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Google Plus">
                                <i style="color:#fff;" class="fa fa-google-plus"></i>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo lang("social_linkedin_link"); ?>" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Linkedin">
                                <i style="color:#fff;" class="fa fa-linkedin"></i>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo lang("social_twitter_link"); ?>" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Twitter">
                                <i  style="color:#fff;"class="fa fa-twitter"></i>
                            </a>
                        </li>
                    </ul>
                </div><!--/col-md-3-->
                <!-- End Address -->
            </div>
        </div>
    </div><!--/footer-->

    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>
                        2014 &copy; <?php echo lang("All Rights Reserved."); ?>
                        <a href="<?php echo site_url("home/cms/8"); ?>"><?php echo lang("Privacy Policy"); ?></a> | <a href="<?php echo site_url("home/cms/7"); ?>"><?php echo lang("Terms of Service"); ?></a>
                    </p>
                </div>

                <!-- Social Links -->
                <div class="col-md-6">
                    Powered by: <a href="http://www.tqniatlab.com" target="_blank">Tqniat Lab</a>
                </div>
                <!-- End Social Links -->
            </div>
        </div>
    </div><!--/copyright-->
</div>
<!--=== End Footer Version 1 ===-->
</div><!--/wrapper-->
<!--JS Global Compulsory-->
<script type="text/javascript" src="<?php echo site_url("webroot/plugins/jquery/jquery-migrate.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/plugins/bootstrap/js/bootstrap.min.js"); ?>"></script>
<!--JS Implementing Plugins-->
<script type="text/javascript" src="<?php echo site_url("webroot/plugins/back-to-top.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/plugins/counter/waypoints.min.js"); ?>"></script>

<script type="text/javascript" src="<?php echo site_url("webroot/plugins/counter/jquery.counterup.min.js"); ?>"></script>
<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/plugins/gmap/gmap.js"); ?>"></script>-->
<script type="text/javascript" src="<?php echo site_url("webroot/plugins/flexslider/jquery.flexslider-min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/plugins/parallax-slider/js/modernizr.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/plugins/parallax-slider/js/jquery.cslider.js"); ?>"></script>

<!--JS Page Level-->
<script type="text/javascript" src="<?php echo site_url("webroot/js/app.js"); ?>"></script>

<script type="text/javascript" src="<?php echo site_url("webroot/js/page_contacts.js"); ?>"></script>

<script type="text/javascript"  src="<?php echo site_url("webroot/js/page_contact_advanced.js"); ?>"></script>
<!--
 JS Page Level

-->
<script type="text/javascript" src="<?php echo site_url("webroot/js/masking.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/js/jquery.mCustomScrollbar.concat.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/js/masking_1.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/js/checkout.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/js/reg.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/js/datepicker.js"); ?>"></script><!--
-->
<script type="text/javascript" src="<?php echo site_url("webroot/js/parallax-slider.js"); ?>"></script>




<!--Login Form-->
<script src="<?php echo site_url("webroot/plugins/sky-forms/version-2.0.1/js/jquery.form.min.js"); ?>"></script>
<!--Checkout Form-->
<script src="<?php echo site_url("webroot/plugins/sky-forms/version-2.0.1/js/jquery.maskedinput.min.js"); ?>"></script>
<!--Masking Form-->
<script src="<?php echo site_url("webroot/plugins/sky-forms/version-2.0.1/js/jquery.maskedinput.min.js"); ?>"></script>
<!--Datepicker Form-->
<script src="<?php echo site_url("webroot/plugins/sky-forms/version-2.0.1/js/jquery-ui.min.js"); ?>"></script>
<!--Validation Form-->
<script src="<?php echo site_url("webroot/plugins/sky-forms/version-2.0.1/js/jquery.validate.min.js"); ?>"></script>
<!--JS Customization-->
<script type="text/javascript" src="<?php echo site_url("webroot/js/custom.js"); ?>"></script>

<script type="text/javascript" src="<?php echo site_url("webroot/js/validation.js"); ?>"></script>




<script type="text/javascript">
                                       jQuery(document).ready(function () {
                                           App.init();
                                           App.initSliders();
                                           Validation.initValidation();
                                           ParallaxSlider.initParallaxSlider();
                                           Masking.initMasking();
                                           CheckoutForm.initCheckoutForm();
                                           RegForm.initRegForm();
                                           PageContactForm.initPageContactForm();
//                                           ContactPage.initMap();
                                           Datepicker.initDatepicker();
                                       });

</script>

<!--[if lt IE 9]>-->
<script src="<?php echo site_url("webroot/plugins/respond.js"); ?>"></script>
<script src="<?php echo site_url("webroot/plugins/html5shiv.js"); ?>"></script>
<script src="<?php echo site_url("webroot/js/plugins/placeholder-IE-fixes.js"); ?>"></script>
<!--<![endif]-->



<script type="text/javascript">
                                       $('#department').on('change', function () {
                                           if (this.value == 4) {
                                               $('.ourform').hide('fast');
                                               $('.web2lead').show();
                                           }
                                       });

</script>


</body>
</html>