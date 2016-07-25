<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Job Description"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo lang("Home"); ?></a></li>
            <li><a href="<?php echo site_url('home/careers'); ?>"><?php echo lang("careers"); ?></a></li>
            <li class="active"><?php echo lang("Job Description"); ?></li>
        </ul>
    </div>
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Job Description ===-->
<div class="job-description">
    <div class="container content">
        <div class="title-box-v2">
            <h2><?php
                $title = lang_db('title');
                echo $job->$title;
                ?></h2>
            <!--<p>Pellentesque et erat ac massa cursus porttitor eget sed magna.</p>-->
        </div>
        <div class="row">
            <!-- Left Inner -->
            <div class="col-md-7">
                <div class="left-inner">
                    <img src="<?php echo site_url(thumb("webroot/uploads/jobs/" . $job->image, 263, 148)) ?>" alt="<?php echo $job->$title; ?>">
                    <h3><?php echo $job->$title; ?></h3>

                    <ul class="social-icons position-top">
                        <li><a class="social_facebook" target="_blank" data-original-title="Facebook" href="https://www.facebook.com/sharer/sharer.php?u="<?php echo current_url(); ?>></a></li>
                        <li><a class="social_googleplus" target="_blank" data-original-title="Google Plus" href="https://plus.google.com/share?url=<?php echo current_url(); ?>"></a></li>
                        <li><a class="social_twitter" target="_blank" data-original-title="Twitter" href="https://twitter.com/home?status=<?php echo current_url(); ?>"></a></li>
                    </ul>
                    <div class="overflow-h">
                        <ul class="list-unstyled">
                            <li><span class="color-green"><?php echo lang("Applied:"); ?></span> <?php echo $job->applied; ?></li>
                            <li><span class="color-green"><?php echo lang("Position:"); ?></span> <?php
                                $position = lang_db('position');
                                echo $job->$position;
                                ?></li>
                            <li><span class="color-green"><?php echo lang("Required:"); ?></span>  <?php echo $job->experience . lang(' - years of experience'); ?></li>
                            <li><span class="color-green"><?php echo lang("Gender:"); ?></span> <?php echo lang(ucfirst($job->gender)); ?></li>

                        </ul>

                    </div>


                    <hr>

                    <h2><?php echo lang("Job Description"); ?></h2>
                    <p><?php
                        $desc = lang_db('desc');
                        echo $job->$desc;
                        ?> </p>
                </div>
            </div>
            <!-- End Left Inner -->

            <!-- Right Inner -->
            <div class="col-md-5">
                <!-- Reg-Form -->
                <?php
                $attributes = array(
                               "class"      => "sky-form",
                               "id"         => "sky-form4",
                               "novalidate" => "novalidate"
                );
                echo form_open_multipart('home/jobApply', $attributes, array('job_id' => $job->id))
                ?>
                <header><?php echo lang("Apply form"); ?></header>

                <fieldset>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" placeholder="<?php echo lang("Name"); ?>" name="name" required>
                            <b class="tooltip tooltip-bottom-right"><?php echo lang("Needed to enter your name"); ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-envelope"></i>
                            <input type="email" placeholder="<?php echo lang("Email address"); ?>" name="email">
                            <b class="tooltip tooltip-bottom-right"><?php echo lang("Needed to verify your account"); ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-phone"></i>
                            <input type="text"  placeholder="<?php echo lang("Mobile"); ?>" name="mobile" required>
                            <b class="tooltip tooltip-bottom-right"><?php echo lang("Needed to enter your mobile number"); ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-file-pdf-o "></i>
                            <input type="file" placeholder="<?php echo lang("Upload CV"); ?>" name="cv" required>
                            <b class="tooltip tooltip-bottom-right"><?php echo lang("Needed to upload your CV"); ?></b>
                        </label>
                    </section>
                </fieldset>
                <footer>
                    <button class="btn-u" type="submit"><?php echo lang("Apply"); ?></button>
                </footer>
                <?php echo form_close(); ?>
                <!-- End Reg-Form -->
            </div>
            <!-- End Right Inner -->
        </div>
    </div>
</div><!--/container-->
<!-- End Content Part -->