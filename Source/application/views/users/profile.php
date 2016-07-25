
<!--=== Profile ===-->
<div class="profile container content">
    <div class="row">
        <?php $this->load->view("users/profile_left_side_bar.php"); ?> 

        <div class="col-md-9">
               <!--Profile Body-->
               <div class="profile-body">
                   <?php
                   if(!empty($user_shipments))
                   {
                      ?>
                      <!--Timeline-->
                      <ul class="timeline-v2">
                          <?php
                          foreach($user_shipments as $shipment)
                          {
                             ?>
                             <li>
                                 <time class="cbp_tmtime" datetime=""><span><?php echo Date("Y-m-d", strtotime($shipment->PickupDate)) ?></span> <span><?php echo Date("M", strtotime($shipment->PickupDate)) ?></span></time>
                                 <i class="cbp_tmicon rounded-x hidden-xs"></i>
                                 <div class="cbp_tmlabel">
                                     <h2><?php echo $shipment->AWBNo ?></h2>
                                     <p><ul class="list-unstyled list-inline blog-info">
                                         <li><i class="fa fa-puzzle-piece"></i> <?php echo lang("Number of pieces : ") . $shipment->NoofPieces ?> </li>
                                         <li><i class="fa fa-gear" <?php
                                             if($this->lang->lang() == "en")
                                             {
                                                ?>style="margin:0 0 0 20px;"<?php
                                                }
                                                else
                                                {
                                                   ?>style="margin:0 20px 0 0;"<?php } ?>></i> <?php echo lang("Weight : ") . $shipment->Weight ?> </li>
                                     </ul></p>
                                     <p><?php echo $shipment->GoodsDesc ?></p>
                                 </div>
                             </li>
                          <?php } ?>
                      </ul>
                      <!--End Timeline-->
                      <?php
                   }
                   else
                   {
                      ?>
                      <div class="alert alert-danger fade in">
                          <h4><?php echo lang("Oh snap! No results !"); ?></h4>
                          <p><?php echo lang("It seems you have no shipments yet .."); ?> </p>
                          <p>
                              <a href="<?php echo site_url("shipment/pickupRequest") ?>" class="btn-u btn-u-red"><?php echo lang("Pickup Now"); ?></a> 
                              <a href="<?php echo site_url("home/contact_us") ?>" class="btn-u btn-u-sea"><?php echo lang("Or Contact us"); ?></a>
                          </p>
                      </div>
                   <?php } ?>
               </div>
               <!--End Profile Body-->
        </div>
    </div><!--/end row-->
</div><!--/container-->
<!-- End Content Part -->