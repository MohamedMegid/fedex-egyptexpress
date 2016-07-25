
<!--=== Profile ===-->
<div class="profile container content">
    <div class="row">

        <div class="col-md-12">
            <?php
            if($puNo)
            {
               ?>
               <div class="service-block service-block-purple">
                   <i class="icon-custom icon-lg rounded icon-color-light icon-line icon-badge"></i>
                   <h2 class="heading-md"><?php echo lang("Thank you ! Your Pickup number :"); ?> </h2>
                   <ul style="list-style: none;font-size: 16px;">
                       <li style="color:#fff;"><?php echo $puNo; ?></li>
                   </ul>
               </div>
            <?php } ?>
        </div>
    </div><!--/end row-->
</div><!--/container-->
<!-- End Content Part -->