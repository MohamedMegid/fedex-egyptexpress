
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
        <div class="navbar mega-menu" role="navigation">
            <div class="container col-lg-12">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header mb-margin-bottom-50 col-lg-10">
                    <a class="navbar-brand" href="<?php echo site_url("home/index"); ?>">
                        <img id="logo-header" class="img img-responsive" src="<?php echo site_url("webroot/images/logo.png"); ?>" alt="Logo">
                    </a>
                </div>
                <?php
                $langClass = 'pull-right';
                if($this->lang->lang() == 'ar')
                   $langClass = 'pull-left';
                ?>
                <div class="col-lg-2  margin-bottom-50 <?php echo $langClass ?>">
                    <button  class="btn" onclick="window.print();"><i class="position-top fa fa-2x fa-print"></i></button>
                    <button  class="btn" onclick="return exportAWB();"><i class="position-top fa fa-2x fa-file-excel-o"></i></button>
                </div>
            </div>
        </div>
        <?php
        foreach($original as $awb_no)
        {
           ?>
           <div class="clearfix"></div>
           <div class="col-xs-6 tag-box tag-box-v2 mb-margin-bottom-30">
               <h3><?php echo lang("Source"); ?></h3>
               <p><?php echo $history[$awb_no]['AWBinfo']->Shipper ?></p>
               <p><?php echo $history[$awb_no]['AWBinfo']->ShipperAddress1 . "," ?>
                   <?php echo $history[$awb_no]['AWBinfo']->ShipperCity . ' , ' . $history[$awb_no]['AWBinfo']->ShipperCountry ?></p>
           </div>
           <div class="col-xs-6 tag-box tag-box-v2 mb-margin-bottom-30">
               <h3><?php echo lang("Destination"); ?></h3>
               <p><?php echo $history[$awb_no]['AWBinfo']->ConsigneeName ?></p>
               <p><?php echo $history[$awb_no]['AWBinfo']->ConsigneeAddress1 . "," ?>
                   <?php echo $history[$awb_no]['AWBinfo']->ConsigneeCity . ' , ' . $history[$awb_no]['AWBinfo']->ConsigneeCountry ?></p>
           </div>
           <div class="col-md-12 mb-margin-bottom-30">
               <div class="headline">
                   <h2><?php echo lang("AWB number : "); ?> <?php echo $awb_no ?></h2>
               </div>
           </div>
           <div class="col-md-12 mb-margin-bottom-30 text-center">
               <!-- if delivered--> <img class="img img-responsive center-block" id="img-delivered" style="display: none" src="<?php echo site_url("webroot/images/delivered.png"); ?>" >
               <!-- if in progress--> <img class="img img-responsive center-block" id="img-inprogress" src="<?php echo site_url("webroot/images/inprogress.png"); ?>" >
           </div>
           <?php
           if(isset($history[$awb_no]))
           {
              $x = 0;
              ?>
              <div class="col-md-12">
                  <!--Profile Body-->
                  <div class="profile-body">
                      <!--Timeline-->
                      <ul class="timeline-v2">
                          <?php
                          foreach($history[$awb_no] as $index => $historydetails)
                          {
                             if($historydetails->CodeID == "POD")
                             {
                                ?>
                                <script>
                                   $('#img-delivered').show();
                                   $('#img-inprogress').hide();</script>
                                <?php
                             }

                             if(!isset($historydetails->status_text))
                                continue;
                             ?>
                             <li>
                                 <time class="cbp_tmtime" datetime=""><span><?php echo Date("Y-m-d", strtotime($historydetails->TransDate)) . "   " . $historydetails->TransTime; ?></span> <span><?php echo Date("l", strtotime($historydetails->TransDate)) ?></span></time>
                                 <i class="cbp_tmicon rounded-x hidden-xs"></i>
                                 <div class="cbp_tmlabel">
                                     <h2><?php echo $historydetails->status_text; ?></h2>
                                     <p><?php echo $historydetails->Remarks; ?></p>
                                 </div>
                             </li>
                             <?php
                             if($last)
                                break;
                          }
                          ?>
                      </ul>
                      <!--End Timeline-->
                  </div>
                  <!--End Profile Body-->
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
        }
        ?>
    </div>

    <script type="text/javascript">

       function exportAWB()
       {
           var AWBNOS = '<?php echo serialize($original); ?>';
           $.post("<?php echo site_url('shipment/export'); ?>", {AWBs: AWBNOS}, function (path) {
               window.location.href = '<?php echo site_url(); ?>' + path;
           });
       }

       $(document).ready(function () {
           $(".header").hide();
           $(".breadcrumbs-v3").hide();
           $(".footer-v1").hide();
       });

    </script>

</div><!--/container-->
<!-- End Content Part -->