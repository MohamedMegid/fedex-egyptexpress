
<!--=== Breadcrumbs v3 ===-->
<div class="breadcrumbs-v3 margin-bottom-30">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("My Shipment"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("account/profile"); ?>"><?php echo lang("Dashboard"); ?></a></li>
            <li class="active"><?php echo lang("My Shipment"); ?></li>
        </ul>
    </div>
</div>
<!--=== End Breadcrumbs v3 ===-->

<!--=== Container Part ===-->
<div class="container content">
    <div class="row">
        <!--Left Sidebar-->
        <?php $this->load->view("users/profile_left_side_bar.php"); ?> 
        <!--End Left Sidebar-->

        <div class="tab-v1 col-md-9">              
            <div class="tab-contentt">
                <div class="tab-pane" id="settings2">
                    <?php
                    if($user_shipments)
                    {
                       ?>
                       <!-- Begin Table Search V2 -->
                       <div class="table-search-v2 margin-bottom-50">
                           <div class="table-responsive">
                               <table class="table table-bordered table-striped">
                                   <thead>
                                       <tr>
                                           <th><?php echo lang("AWB"); ?></th>
                                           <th><?php echo lang("Pickup Date"); ?></th>
                                           <th><?php echo lang("Weight"); ?></th>
                                           <th><?php echo lang("No. Of Pieces"); ?></th>
                                           <th><?php echo lang("Description"); ?></th>
                                           <th><?php echo lang("Status"); ?></th>
                                           <th><?php echo lang("Action"); ?></th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <?php
                                       foreach($user_shipments as $shipment)
                                       {
                                          ?>
                                          <tr>
                                              <td><h3><?php echo $shipment->AWBNo; ?></h3></td>
                                              <td><h3><?php echo Date("Y-M-d", strtotime($shipment->PickupDate)) ?></h3></td>
                                              <td class="">
                                                  <span><?php echo $shipment->Weight; ?></span>
                                              </td>
                                              <td class="">
                                                  <span><?php echo $shipment->NoofPieces; ?></span>
                                              </td>
                                              <td class="">
                                                  <span><?php echo $shipment->GoodsDesc; ?></span>
                                              </td>
                                              <td>
                                                  <?php
                                                  if($shipment->status == "D")
                                                  {
                                                     ?>
                                                     <span class="label label-success"><?php echo lang("Deliverd"); ?></span>
                                                     <?php
                                                  }
                                                  else
                                                  {
                                                     ?>
                                                     <span class="label label-orange"><?php echo lang("Pending"); ?></span>
                                                  <?php } ?>
                                              </td>
                                              <td>
                                                  <?php
                                                  echo form_open("shipment/shipmentHistory", array(
                                                                 "target" => "_blank"));
                                                  echo form_hidden("type", "single");
                                                  echo form_hidden("awb_no", $shipment->AWBNo);
                                                  ?>
                                                  <ul class="list-inline table-buttons">
                                                      <li><Button type="submit" class="btn-u btn-u-sm btn-u-dark" style="color: #fff;" title="History"><span class="fa fa-history"></span></Button></li>
                                                  </ul>
                                                  <?php echo form_close(); ?>
                                              </td>
                                          </tr>
                                       <?php } ?>
                                   </tbody>
                               </table>
                           </div>    
                       </div>    
                       <!-- End Table Search V2 -->
                       <?php
                       echo '<div class="clearfix"></div><nav>
  <ul class="pagination"> ' . $this->pagination->create_links() . '</ul></nav>';
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
            </div>
        </div>
    </div>
</div><!--/container-->
<!-- End Content Part -->