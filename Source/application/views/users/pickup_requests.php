<style>
    hr{
        margin:10px 0 !important;
    }
    h5{
        color: #f46600;
    }
</style>
<!--=== Breadcrumbs v3 ===-->
<div class="breadcrumbs-v3 margin-bottom-30">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Pickup Requests"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("account/profile"); ?>"><?php echo lang("Dashboard"); ?></a></li>
            <li class="active"><?php echo lang("Pickup Requests"); ?></li>
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
                    <!-- Begin Table Search V2 -->
                    <div class="table-search-v2 margin-bottom-50">
                        <div class="table-responsive">
                            <?php
                            if($requests)
                            {
                               ?>
                               <table class="table table-bordered table-striped">
                                   <thead>
                                       <tr>
                                           <th><?php echo lang("Id"); ?></th>
                                           <th><?php echo lang("Pickup date"); ?></th>
                                           <th><?php echo lang("Account type"); ?></th>
                                           <th><?php echo lang("Number of pieces"); ?></th>
                                           <th><?php echo lang("Product type"); ?></th>
                                           <th><?php echo lang("Status"); ?></th>
                                           <th><?php echo lang("Created date"); ?></th>
                                           <th><?php echo lang("Action"); ?></th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <?php
                                       foreach($requests as $request)
                                       {
                                          ?>
                                          <tr>
                                              <td><h3><?php echo $request->id ?></h3></td>
                                              <td>
                                                  <div class="user-names">
                                                      <span><?php echo $request->pickup_date ?></span>
                                                  </div>
                                              </td>
                                              <td class="">
                                                  <h3><?php echo ($request->account_type == "accountnumber") ? lang("Account Number") : lang("Cash") ?></h3>
                                              </td>
                                              <td class="">
                                                  <h3><?php echo $request->no_of_pieces ?></h3>
                                              </td>
                                              <td class="">
                                                  <h3><?php echo $request->product_type ?></h3>
                                              </td>
                                              <td class="">
                                                  <h3><?php echo lang($request->status) ?></h3>
                                              </td>
                                              <td class="">
                                                  <h3><?php echo $request->created ?></h3>
                                              </td>
                                              <td>
                                                  <ul class="list-inline table-buttons">
                                                      <?php
                                                      if($request->status != "picked")
                                                      {
                                                         ?>
                                                         <li><a href="<?php echo site_url("shipment/cancelrequest/" . $request->id); ?>" type="button" class="btn-u btn-u-sm btn-u-dark" title="delete" style="color: #fff;"><i class="fa fa-trash-o"></i></a></li>
                                                         <li><a href="<?php echo site_url("shipment/pickupRequest/" . $request->id); ?>" type="button" class="btn-u btn-u-sm btn-u-dark" title="edit" style="color: #fff;"><i class="fa fa-edit"></i></a></li>
                                                      <?php } ?>
                                                      <!-- Modal -->
                                                      <li><button class="btn-u btn-u-sm btn-u-dark" data-toggle="modal" data-target="#myModal" title="view"><i class="fa fa-outdent"></i></button></li>
                                                      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                          <div class="modal-dialog">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                      <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                                      <h4 id="myModalLabel" class="modal-title"><?php echo "# " . $request->id; ?></h4>
                                                                      <h4 id="myModalLabel" class="modal-title" style="color:#f46600;"><?php echo lang("$request->status"); ?></h4>
                                                                  </div>
                                                                  <div class="row modal-body">
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("From Time"); ?></h5>
                                                                          <p><?php echo $request->from_time; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("To Time"); ?></h5>
                                                                          <p><?php echo $request->to_time; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-12">
                                                                          <h5><?php echo lang("Contact Name"); ?></h5>
                                                                          <p><?php echo $request->contact_name; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Account Type"); ?></h5>
                                                                          <p><?php echo ($request->account_type == "accountnumber") ? lang("Account Number") : lang("Cash") ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <?php
                                                                          if($request->account_number)
                                                                          {
                                                                             ?>
                                                                             <h5><?php echo lang("Account Number"); ?></h5>
                                                                             <p><?php echo $request->account_number; ?></p>
                                                                             <hr>
                                                                          <?php } ?>
                                                                      </div>
                                                                      <div class="col-xs-12">
                                                                          <h5><?php echo lang("Company"); ?></h5>
                                                                          <p><?php echo $request->company; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Contact Phone"); ?></h5>
                                                                          <p><?php echo $request->contact_phone; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Email"); ?></h5>
                                                                          <p><?php echo $request->email; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-12">
                                                                          <h5><?php echo lang("Content"); ?></h5>
                                                                          <p><?php echo $request->content; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Weight"); ?></h5>
                                                                          <p><?php echo $request->weight . " Kgm"; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Number of pieces"); ?></h5>
                                                                          <p><?php echo $request->from_time; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-12">
                                                                          <h5><?php echo lang("Pickup Address"); ?></h5>
                                                                          <p><?php echo $request->pickup_address; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Pickup City"); ?></h5>
                                                                          <p><?php echo $request->pickup_city; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Governorate"); ?></h5>
                                                                          <p><?php echo $request->governorate; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-12">
                                                                          <h5><?php echo lang("Product Type"); ?></h5>
                                                                          <p><?php echo $request->product_type; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Pickup Date"); ?></h5>
                                                                          <p><?php echo $request->pickup_date; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                      <div class="col-xs-6">
                                                                          <h5><?php echo lang("Created"); ?></h5>
                                                                          <p><?php echo $request->created; ?></p>
                                                                          <hr>
                                                                      </div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                      <button data-dismiss="modal" class="btn-u btn-u-default" type="button">Close</button>
                                                                      <?php
                                                                      if($request->status != "picked")
                                                                      {
                                                                         ?>
                                                                         <a href="<?php echo site_url("shipment/cancelrequest/" . $request->id); ?>" type="button" class="btn-u" title="delete" style="color: #fff;"><?php echo lang("Delete"); ?></a>
                                                                         <a href="<?php echo site_url("shipment/pickupRequest/" . $request->id); ?>" type="button" class="btn-u" title="edit" style="color: #fff;"><?php echo lang("Edit"); ?></a>
                                                                      <?php } ?>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <!-- End Modal -->
                                                  </ul>
                                              </td>
                                          </tr>
                                       <?php } ?>
                                   </tbody>
                               </table>

                               <?php
                               echo '<div class="clearfix"></div><nav>
  <ul class="pagination"> ' . $this->pagination->create_links() . '</ul></nav>';
                            }
                            else
                            {
                               ?>
                               <div class="alert alert-danger fade in">
                                   <h4><?php echo lang("Oh snap! No results !"); ?></h4>
                                   <p><?php echo lang("It seems you have no pickup requests yet or your pickup requests have been canceled .. "); ?> </p>
                                   <p>
                                       <a href="<?php echo site_url("shipment/pickupRequest") ?>" class="btn-u btn-u-red"><?php echo lang("Pickup Now"); ?></a>
                                       <a href="<?php echo site_url("home/contact_us") ?>" class="btn-u btn-u-sea"><?php echo lang("Or Contact us"); ?></a>
                                   </p>
                               </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- End Table Search V2 -->
                </div>
            </div>
        </div>
    </div>
</div><!--/container-->
<!-- End Content Part -->