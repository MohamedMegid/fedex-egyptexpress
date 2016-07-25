


<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Registration"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("home"); ?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"><?php echo lang("Registration"); ?></li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <?php
            $attributes = array(
                           'id'         => 'sky-form4',
                           "class"      => "sky-form",
                           'novalidate' => "novalidate"
            );

            echo form_open_multipart('users/do_register', $attributes);
            ?>
            <div class="reg-header">
                <h2><?php echo lang("Register a new account"); ?></h2>
                <p><?php echo lang("Already Signed Up? Click"); ?> <a href="<?php echo site_url("users/login"); ?>" class="color-green" style="color:#f46600"><?php echo lang("Sign In"); ?></a> <?php echo lang("to login your account.");?></p>
            </div>
            <fieldset>
                <section class="input">
                    <label class="input"><?php echo lang("First Name"); ?> <span class="color-red">*</span>
                        <input type="text" name="first_name" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Last Name"); ?> <span class="color-red">*</span>
                        <input type="text" name="last_name" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Company"); ?> <span class="color-red">*</span>
                        <input type="text" name="company" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Job Title"); ?> <span class="color-red">*</span>
                        <input type="text" name="job_title" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Phone"); ?> <span class="color-red">*</span>
                        <input type="text" name="phone" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Address"); ?> <span class="color-red">*</span>
                        <input type="text" name="address" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("How often you ship Monthly"); ?>  <span class="color-red">*</span>
                        <input type="text" name="ship_monthly" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Commercial Register"); ?> (pdf , doc , docx , jpg , jpeg , png)
                        <input type="file" name="commercial_register" class="   " >
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Profile Picture"); ?> (gif ,jpg , jpeg , png)
                        <input type="file" name="avatar" class="   " >
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Email Address"); ?> <span class="color-red">*</span>
                        <input type="email" name="email" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Password"); ?> <span class="color-red">*</span>
                        <input type="password" name="password" id="password" class="   " required>
                    </label>
                </section>
                <section class="input">
                    <label class="input"><?php echo lang("Confirm Password"); ?> <span class="color-red">*</span>
                        <input type="password" name="repassword"  class="   " equalTo = "#password">
                    </label>
                </section>

                <hr>

                <div class="row">
                    <div class="col-lg-6">
                        <label>
                            <input type="checkbox" name="read" value="1" required>
                            <?php echo lang("I read"); ?> <a href="<?php echo site_url("home/cms/7") ?>" target="_blank"  class="color-orange" style="color:#f46600"><?php echo lang("Terms and Conditions"); ?></a>
                        </label>
                    </div>
                    <div class="col-lg-6 <?php
                    if($this->lang->lang() == "ar")
                    {
                       ?>text-left<?php
                         }
                         else
                         {
                            ?>text-right<?php } ?>">
                        <button class="btn-u" type="submit"><?php echo lang("Register"); ?></button>
                    </div>
                </div>
            </fieldset>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
