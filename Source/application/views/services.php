<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Services"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("home"); ?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"><?php echo lang("Services"); ?></li>
        </ul>
    </div>
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <!-- Service Blcoks -->
    <div class="row margin-bottom-10">
        <div class="col-xs-12 tag-box tag-box-v2 margin-bottom-40">
            <?php echo lang('services_page'); ?> 
        </div>


        <?php
        $title = lang_db("title");
        $brief = lang_db("brief");
        $desc = lang_db("desc");
        if($international)
        {
           ?>
           <div class="headline"><h2><?php echo lang("International Services"); ?></h2></div>
           <?php
           $x = 0;
           foreach($international as $service)
           {
              $x++;
              ?>
              <div class="col-md-3 col-sm-6">
                  <div class="service-block newheight <?php echo $service->color; ?>">
                      <h2 class="heading-md"><b><?php echo $service->$title; ?></b></h2>
                      <p class="content"><?php echo $service->$brief; ?> </p>
                      <button class="btn btn-sm <?php echo $service->color; ?>" data-target="<?php echo ".inter" . $x ?>" style="text-decoration: underline;" data-toggle="modal"><?php echo lang("Read More"); ?></button>
                  </div>
                  <div aria-hidden="true" aria-labelledby="mySmallModalLabel" role="dialog" tabindex="-1" class="modal fade bs-example-modal-lg <?php echo "inter" . $x ?>" style="display: none;">
                      <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                  <h4 class="modal-title" id="myLargeModalLabel"><?php echo $service->$title; ?></h4>
                              </div>
                              <div class="modal-body">
                                  <p style="color:#555;"><?php echo $service->$desc; ?></p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <?php
           }
        }
        ?>
    </div>
    <div class="row margin-bottom-10">
        <?php
        if($domestic)
        {
           ?>
           <div class="headline"><h2><?php echo lang("Domestic Services"); ?></h2></div>
           <?php
           $y = 0;
           foreach($domestic as $one)
           {
              $y++;
              ?>
              <div class="col-md-3 col-sm-6">
                  <div class="service-block newheight <?php echo $one->color; ?>">
                      <h2 class="heading-md"><b><?php echo $one->$title; ?></b></h2>
                      <p class="content"><?php echo $one->$brief; ?> </p>
                      <button class="btn btn-sm <?php echo $one->color; ?>" data-target="<?php echo ".dome" . $y ?>" style="text-decoration: underline;" data-toggle="modal"><?php echo lang("Read More"); ?></button>
                  </div>
                  <div aria-hidden="true" aria-labelledby="mySmallModalLabel" role="dialog" tabindex="-1" class="modal fade bs-example-modal-lg <?php echo "dome" . $y ?>" style="display: none;">
                      <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                  <h4 class="modal-title" id="myLargeModalLabel"><?php echo $one->$title; ?></h4>
                              </div>
                              <div class="modal-body">
                                  <p style="color:#555;"><?php echo $one->$desc; ?></p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <?php
           }
        }
        ?>
    </div>
    <!-- End Service Blcoks -->
</div><!--/container-->
<!-- End Content Part -->

<script>
    (function($){
        $(window).load(function(){
            $(".content").mCustomScrollbar();
        });
    })(jQuery);
</script>