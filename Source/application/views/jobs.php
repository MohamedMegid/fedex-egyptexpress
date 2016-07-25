
<!--=== Job Img ===-->
<div class="job-img margin-bottom-30">
    <div class="job-banner">
        <h2><?php echo lang('Discover the Companies You would love to Work for ...'); ?></h2>
    </div>

</div>
<!--=== End Job Img ===-->

<!--=== Content Part ===-->
<div class="container content">
    <!-- Begin Service Block -->
    <div class="row margin-bottom-40">
        <div class="col-xs-12 tag-box tag-box-v2 margin-bottom-40">
            <p style="text-align: justify;"><?php echo lang('careers_page'); ?></p>
        </div>
    </div>

    <div class="headline margin-bottom-35"><h2><?php echo lang('Latest Jobs'); ?></h2></div>

    <!-- Easy Blocks v2 -->
    <div class="row">

        <?php
        if($jobs)
        {
           foreach($jobs as $job)
           {
              $title = lang_db('title');
              ?>
              <!-- Begin Easy Block -->
              <div class="col-md-3 col-sm-6 md-margin-bottom-40">
                  <div class="easy-block-v2 blockheight">
                      <div class="easy-bg-v2 rgba-default"><?php echo lang("New"); ?></div>
                      <a href=" <?php echo site_url("home/jobView/" . $job->id . "/" . $job->$title); ?>"><img alt="<?php
                          echo $job->$title;
                          ?>" src="<?php echo site_url(thumb("webroot/uploads/jobs/" . $job->image, 263, 148)) ?>"></a>
                      <h3 style="min-height: 30px;
                          max-height: 60px;overflow: auto;"><?php
                          echo $job->$title;
                          ?></h3>
                      <ul class="list-unstyled">
                          <li style="min-height: 20px;
                              max-height: 40px;overflow: auto;"><span class="color-green"><?php echo lang("Position:"); ?></span> <?php
                              $position = lang_db('position');
                              echo $job->$position;
                              ?></li>
                          <li><span class="color-green"><?php echo lang("Required:"); ?></span>  <?php echo $job->experience . lang(' - years of experience'); ?></li>
                          <li><span class="color-green"><?php echo lang("Gender:"); ?></span> <?php echo lang(ucfirst($job->gender)); ?></li>
                      </ul>
                      <a class="btn-u btn-u-sm" href="<?php echo site_url("home/jobView/" . $job->id . "/" . $job->$title); ?>"><?php echo lang("View More"); ?></a>
                  </div>
              </div>
              <!-- End Begin Easy Block -->
              <?php
           }
        }
        ?>

    </div>
    <!-- End Easy Blocks v2 -->
</div>
<!--=== End Content Part ===-->

<!--=== Job Partners ===-->
<div class="container content job-partners">
</div><!--/container-->
<!-- End Content Part -->