
<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("About Us"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("home"); ?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"> <?php
                $title = lang_db('title');
                echo $cms->$title;
                ?></li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <div class="row margin-bottom-40">
        <div class="col-md-8 md-margin-bottom-40" style="text-align: justify">
            <?php
            $content = lang_db('content');
            echo $cms->$content;
            ?>
        </div>

        <div class="col-md-3 md-margin-bottom-40">
            <div class="responsive-video">
                <img src="<?php echo site_url("webroot/uploads/images/" . $cms->image); ?>" class="img img-responsive img-thumbnail" />
            </div>
        </div>
    </div><!--/row-->


    <?php
    if($teams && 1 == 2)
    {
       ?>
       <!-- Meer Our Team -->
       <div class="headline"><h2><?php echo lang("Meet Our Team"); ?></h2></div>
       <div class="row team">


           <?php
           foreach($teams as $team)
           {
              ?>
              <div class="col-sm-3">
                  <div class="thumbnail-style aboutitem">
                      <img class="img-responsive" src="<?php echo site_url("webroot/uploads/images/" . $team->image) ?>" alt="" />
                      <h3><a><?php
                              $name = lang_db('name');
                              echo $team->$name;
                              ?></a> <small><?php
                              $title = lang_db('title');
                              echo $team->$title;
                              ?></small></h3>
                      <p class="content"><?php
                          $bio = lang_db('bio');
                          echo $team->$bio;
                          ?></p>
                      <ul class="list-unstyled list-inline team-socail">
                          <?php
                          if($team->facebook)
                          {
                             ?>
                             <li><a href="<?php echo prep_url($team->facebook); ?>"><i class="fa fa-facebook"></i></a></li>
                          <?php } ?>
                          <?php
                          if($team->twitter)
                          {
                             ?>
                             <li><a href="<?php echo prep_url($team->twitter); ?>"><i class="fa fa-twitter"></i></a></li>
                          <?php } ?>
                          <?php
                          if($team->linkedin)
                          {
                             ?>
                             <li><a href="<?php echo prep_url($team->linkedin); ?>"><i class="fa fa-google-plus"></i></a></li>
                                  <?php } ?>
                      </ul>
                  </div>
              </div>
           <?php } ?>
       </div><!--/team-->
       <!-- End Meer Our Team -->
    <?php } ?>

</div><!--/container-->
<!-- End Content Part -->
<script>
   (function ($) {
       $(window).load(function () {
           $(".content").mCustomScrollbar();
       });
   })(jQuery);
</script>