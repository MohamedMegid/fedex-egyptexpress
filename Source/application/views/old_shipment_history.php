
<!--=== Breadcrumbs v3 ===-->
<div class="breadcrumbs-v3 margin-bottom-30">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Pickup Requests"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="index.html"><?php echo lang("Home"); ?></a></li>
            <li><a href=""><?php echo lang("Page"); ?></a></li>
            <li class="active"><?php echo lang("Pickup Requests"); ?></li>
        </ul>
    </div>
</div>
<!--=== End Breadcrumbs v3 ===-->

<!--=== Container Part ===-->
<div class="container content">
    <div class="row">
        <div class="navbar  mega-menu" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header margin-bottom-50">
                    <a class="navbar-brand" href="<?php echo site_url("home/index"); ?>">
                        <img id="logo-header" class="img img-responsive" src="<?php echo site_url("webroot/images/logo.png"); ?>" alt="Logo">
                    </a>
                </div>
            </div>
        </div>
        <h3 class="margin-bottom-50 col-xs-11"><?php echo lang("some text here"); ?></h3>
        <button  class="btn margin-bottom-50" onclick="window.print();"><i class="position-top fa fa-2x fa-print"></i></button>
        <?php
        foreach($original as $awb_no)
        {
           ?>
           <div class="tab-v1 col-xs-12">
               <ul class="nav nav-tabs">
                   <li class="active"><a data-toggle="tab" href="#home"><?php echo $awb_no ?></a></li>
               </ul>
               <?php
               if(isset($history[$awb_no]))
               {
                  $x = 0;
                  ?>
                  <div class="tab-contentt">
                      <div class="tab-pane" id="settings2">
                          <!-- Begin Table Search V2 -->
                          <div class="table-search-v2 margin-bottom-50">
                              <div class="table-responsive">
                                  <table class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                              <th><?php echo lang("Serial Number"); ?></th>
                                              <th><?php echo lang("Date"); ?></th>
                                              <th><?php echo lang("Status"); ?></th>
                                              <th><?php echo lang("Notes"); ?></th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php
                                          foreach($history[$awb_no] as $historydetails)
                                          {
                                             $x++;
                                             ?>
                                             <tr>
                                                 <td><h3><?php echo $x; ?></h3></td>

                                                 <td class="">
                                                     <h3><?php echo Date("Y-m-d", strtotime($historydetails->TransDate)) . "   " . $historydetails->TransTime; ?></h3>
                                                 </td>
                                                 <td class="">
                                                     <h3><?php echo $historydetails->status_text; ?></h3>
                                                 </td>
                                                 <td>
                                                     <div class="user-names">
                                                         <p>
                                                             <?php echo $historydetails->Remarks; ?>
                                                         </p>
                                                     </div>
                                                 </td>
                                             </tr>
                                          <?php } ?>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                          <!-- End Table Search V2 -->
                      </div>
                  </div>
                  <?php
               }
               else
               {
                  ?>
                  <div class="tab-contentt margin-bottom-50">
                      <div class="alert alert-danger fade in">
                          <strong><?php echo lang("Sorry ,"); ?></strong>

                          <?php
                          echo lang("There is no history yet.");
                          ?>
                      </div>
                  </div>
                  <?php
               }
               ?>
           </div>
        <?php } ?>
    </div>

    <script type="text/javascript">
       $(document).ready(function () {
           $(".header").hide();
           $(".breadcrumbs-v3").hide();
           $(".footer-v1").hide();
       });
    </script>

</div><!--/container-->
<!-- End Content Part -->