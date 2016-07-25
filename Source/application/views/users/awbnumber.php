
<!--=== Profile ===-->
<div class="profile container content">
    <div class="row">
        <?php $this->load->view("users/profile_left_side_bar.php"); ?>

        <div class="col-md-9">
            <?php
            if(isset($AWBNos))
            {
               ?>
               <div class="service-block service-block-purple">
                   <i class="icon-custom icon-lg rounded icon-color-light icon-line icon-badge"></i>
                   <h2 class="heading-md"><?php echo lang("Thank you ! Your AWB number(s) :"); ?> </h2>
                   <ul style="list-style: none;font-size: 16px;">
                       <?php
                       foreach($AWBNos as $AWBNo)
                       {
                          ?>
                          <li style="color:#fff;"><?php echo $AWBNo; ?></li>
                       <?php } ?>
                   </ul>
               </div>
            <?php } ?>
        </div>
    </div><!--/end row-->
</div><!--/container-->
<!-- End Content Part -->

<script type="text/javascript">

   window.location.href = "<?php echo site_url("shipment/downloadPDF/$filename"); ?>";

</script>