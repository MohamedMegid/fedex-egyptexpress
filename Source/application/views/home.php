<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php
        if($slider)
        {
           $y = 0;
           foreach($slider as $one)
           {
              ?>
              <li data-target="#carousel-example-generic" data-slide-to="<?php echo $y; ?>" class="<?php
              if($y == 0)
              {
                 echo "active";
              }
              ?>"></li>
                  <?php
                  $y++;
               }
            }
            ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <?php
        $title = lang_db("title");
        $content = lang_db("content");
        if($slider)
        {
           $x = 1;
           foreach($slider as $one)
           {
              ?>
              <div class="item <?php
              if($x == 1)
              {
                 echo "active";
              }
              ?>">
                  <img src="<?php echo site_url("webroot/uploads/images/" . $one->image); ?>" alt="">
              </div>
              <?php
              $x++;
           }
        }
        ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!--=== Content Part ===-->
<div class="container content">

    <!-- Info Blokcs -->
    <div class="row margin-bottom-30">
        <!-- Welcome Block -->
        <div class="col-md-8 md-margin-bottom-40">
            <div class="headline"><h2><?php echo $welcome->$title ?></h2></div>
            <div class="row">
                <div class="col-sm-4">
                    <img class="img-responsive margin-bottom-20" src="<?php echo site_url("webroot/uploads/images/" . $welcome->image) ?>" alt="">
                </div>
                <div class="col-sm-8">
                    <p><?php echo $welcome->$content ?></p>
                </div>
            </div>

        </div><!--/col-md-8-->
        <!-- Latest Shots -->
        <div class="col-md-4">
            <!-- Checkout-Form -->
            <?php
            $attributes = array(
                           "id"     => "sky-form1", "class"  => "sky-form",
                           "target" => "_blank"
            );
            echo form_open_multipart("shipment/shipmentHistory", $attributes);
            ?>                <header><?php echo lang("Track your shipment"); ?></header>
            <fieldset>
                <section>
                    <div class="inline-group">
                        <label class="radio"><input type="radio" name="type" value="single" onchange="$('#bulk').hide();
                               $('#bulklinkh').hide();
                               $('#single').show();
                                                    " checked><i class="rounded-x"></i><?php echo lang("Single AWB No."); ?></label>
                        <label class="radio"><input type="radio" name="type" value="bulk" onchange="$('#single').hide();
                               $('#bulk').show();
                               $('#bulklinkh').show();
                                                    "><i class="rounded-x"></i><?php echo lang("Bulk AWB No."); ?></label>
                    </div>
                </section>

                <section>
                    <label class="input">
                        <input type="text" id="single" name="awb_no" class="" placeholder="<?php echo lang("Your waybill number"); ?>">
                        <input type="file" id="bulk" name="bulk_awb" class="" placeholder="<?php echo lang("xls file"); ?>" style="display: none;">
                        <a href="<?php echo site_url('webroot/images/example.xlsx'); ?>" target="_blank" id="bulklinkh" style="display: none" >Download Example</a>
                    </label>
                    <label class="checkbox-inline" for="lastScan"> <input type="checkbox" name="lastScan" value="1" id="lastScan"> <?php echo lang('Last Scan'); ?></label>

                </section>
                <section>
                    <button type="submit" class="btn-u"><?php echo lang("Track"); ?></button>
                </section>
            </fieldset>

            <footer>
                <a href="http://www.fedex.com/eg/" class="btn btn-link" target="_blank" style="padding:6px 0;"><?php echo lang("International Tracking"); ?></a>
            </footer>
            <?php echo form_close(); ?>
            <!-- End Checkout-Form -->
        </div><!--/col-md-4-->
    </div>
    <!-- End Info Blokcs -->




    <!-- Info Blokcs -->
    <div class="row margin-bottom-30">
        <!-- Welcome Block -->
        <div class="col-md-8 md-margin-bottom-40">
            <!-- Tabs v1 -->
            <div class="tab-v1">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home"><?php echo lang("Vision"); ?> </a></li>
                    <li><a data-toggle="tab" href="#profile"><?php echo lang("Mission"); ?></a></li>
                    <li><a data-toggle="tab" href="#messages"><?php echo lang("Core values"); ?></a></li>
                </ul>
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?php echo site_url("webroot/uploads/images/" . $vision->image) ?>" class="img-responsive img-tab-space" alt="">
                            </div>
                            <div class="col-md-8">
                                <h4><?php echo $vision->$title; ?></h4>
                                <p><?php echo $vision->$content; ?> </p>
                            </div>
                        </div>
                    </div>
                    <div id="profile" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?php echo site_url("webroot/uploads/images/" . $mission->image) ?>" class="img-responsive img-tab-space" alt="">
                            </div>
                            <div class="col-md-8">
                                <h4><?php echo $mission->$title; ?></h4>
                                <p><?php echo $mission->$content; ?> </p>                            </div>
                        </div>
                    </div>
                    <div id="messages" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?php echo site_url("webroot/uploads/images/" . $core->image) ?>" class="img-responsive img-tab-space" alt="">
                            </div>
                            <div class="col-md-8">
                                <h4><?php echo $core->$title; ?></h4>
                                <?php echo $core->$content; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/tab-v1-->
            <!-- End Tabs v1 -->
        </div><!--/col-md-8-->


        <?php
        if($shots)
        {
           ?>
           <!-- Latest Shots -->
           <div class="col-md-4">
               <div class="headline" style="margin: -9px 0 25px;"><h2><?php echo lang("Latest Shots"); ?></h2></div>
               <div id="myCarousel" class="carousel slide carousel-v1">
                   <div class="carousel-inner">
                       <?php
                       $x = 1;
                       foreach($shots as $shot)
                       {
                          ?>
                          <div class="item <?php
                          if($x == 1)
                          {
                             echo "active";
                          }
                          ?>">
                              <img src="<?php echo site_url("webroot/uploads/images/" . $shot->image) ?>" alt="">
                              <div class="carousel-caption">
                                  <p><?php echo $shot->$title; ?></p>
                              </div>
                          </div>
                          <?php
                          $x++;
                       }
                       ?>
                   </div>

                   <div class="carousel-arrow">
                       <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                           <i class="fa fa-angle-left"></i>
                       </a>
                       <a class="right carousel-control" href="#myCarousel" data-slide="next">
                           <i class="fa fa-angle-right"></i>
                       </a>
                   </div>
               </div>
           </div><!--/col-md-4-->
        <?php } ?>
    </div>
    <!-- End Info Blokcs -->


    <!-- Recent Works -->
    <!--<div class="headline"><h2>Services</h2></div>-->
    <?php
    if($panels)
    {
       ?>
       <div class="row margin-bottom-20">
           <?php
           foreach($panels as $panel)
           {
              ?>
              <div class="col-md-3 col-sm-6">
                  <div class="thumbnails thumbnail-style thumbnail-kenburn">
                      <div class="thumbnail-img">
                          <div class="overflow-hidden">
                              <img class="img-responsive" src="<?php echo site_url("webroot/uploads/images/" . $panel->image) ?>" alt="">
                          </div>
                          <a class="btn-more hover-effect" href="<?php echo prep_url($panel->url) ?>"><?php echo lang("read more +"); ?></a>
                      </div>
                      <div class="caption">
                          <h3><a class="hover-effect" href="<?php echo prep_url($panel->url) ?>"><?php echo $panel->$title; ?></a></h3>
                          <p>
                              <?php
                              $brief = lang_db("brief");
                              echo $panel->$brief;
                              ?>
                          </p>
                      </div>
                  </div>
              </div>
           <?php } ?>
       </div>
       <!-- End Recent Works -->
       <?php
    }
    ?>
    <?php
    if($clients)
    {
       ?>
       <!-- Our Clients -->
       <div <?php
       if($this->lang->lang() == "ar")
       {
          ?>id="clients-flexslider2"<?php
           }
           else
           {
              ?>id="clients-flexslider"<?php } ?> class="flexslider home clients">
           <div class="headline"><h2><?php echo lang("Our Clients"); ?></h2></div>
           <ul class="slides">
               <?php
               foreach($clients as $client)
               {
                  ?>
                  <li>
                      <a href="#">
                          <img src="<?php echo site_url("webroot/uploads/images/" . $client->off_image) ?>" alt="">
                          <img src="<?php echo site_url("webroot/uploads/images/" . $client->on_image) ?>" class="color-img" alt="">
                      </a>
                  </li>
               <?php } ?>
           </ul>
       </div><!--/flexslider-->
       <!-- End Our Clients -->
    <?php } ?>

</div><!--/container-->
<!-- End Content Part -->

