<style>
    .active{
        color: #f46600;
    }
</style>
<!--Left Sidebar-->
<div class="col-md-3 md-margin-bottom-40">
    <img class="img-responsive profile-img margin-bottom-20" src="<?php echo site_url(thumb("webroot/uploads/accounts/" . $this->auth->user("avatar"), 265, 200, FALSE)); ?>" alt="">

    <ul class="list-group sidebar-nav-v1 margin-bottom-40" id="sidebar-nav-1">
        <li class="list-group-item">
            <a href="<?php echo site_url("account/profile"); ?>" class="<?php echo hcurrent("profile", 3) ?>"><i class="fa fa-bar-chart-o"></i> <?php echo lang("Dashboard"); ?></a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo site_url("account/profile_settings"); ?>" class="<?php echo hcurrent("profile_settings", 3) ?>"><i class="fa fa-user"></i> <?php echo lang("Profile"); ?></a>
        </li>
        <li class="list-group-item">
            <a href="<?php echo site_url("shipment/myRequests"); ?>" class="<?php echo hcurrent("myRequests", 3) ?>"><i class="fa fa-group"></i> <?php echo lang("My Pickup requests"); ?></a>
        </li>  
        <li class="list-group-item">
            <a href="<?php echo site_url("shipment/pickupRequest"); ?>" class="<?php echo hcurrent("pickupRequest", 3) ?>"><i class="fa fa-cubes"></i> <?php echo lang("Pickup Now"); ?></a>
        </li>
        <?php
        if($this->auth->user("integra_account_id"))
        {
           ?>
           <li class="list-group-item">
               <a href="<?php echo site_url("shipment/my_shipments"); ?>" class="<?php echo hcurrent("my_shipments", 3) ?>"><i class="fa fa-group"></i> <?php echo lang("My Shipments"); ?></a>
           </li>                            
           <li class="list-group-item">
               <a href="<?php echo site_url("shipment/createAWB"); ?>" class="<?php echo hcurrent("createformAWB", 3) ?>"><i class="fa fa-group"></i> <?php echo lang("Create AWB Number"); ?></a>
           </li>                            
        <?php } ?>

        <li class="list-group-item">
            <a href="<?php echo site_url("users/logout"); ?>" class="<?php echo hcurrent("logout", 3) ?>"><i class="fa fa-comments"></i> <?php echo lang("Logout"); ?></a>
        </li>                                        
    </ul>   

    <hr>

    <div class="margin-bottom-50"></div>

    <!--Datepicker-->
    <form action="" id="sky-form2" class="sky-form">
        <div id="inline-start"></div>
    </form> 
    <!--End Datepicker-->
</div>
<!--End Left Sidebar-->