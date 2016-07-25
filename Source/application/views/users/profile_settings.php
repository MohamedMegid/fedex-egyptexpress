
<!--=== Profile ===-->
<div class="profile">   
    <div class="container content">
        <div class="row">
            <!--Left Sidebar-->
            <?php $this->load->view("users/profile_left_side_bar.php"); ?>  
            <!--End Left Sidebar-->

            <div class="col-md-9">
                <!--Profile Body-->
                <div class="profile-body margin-bottom-20">
                    <div class="tab-v1">
                        <ul class="nav nav-justified nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#profile"><?php echo lang("Edit Profile"); ?></a></li>
                            <li><a data-toggle="tab" href="#passwordTab"><?php echo lang("Change Password"); ?></a></li>
                        </ul>          
                        <div class="tab-content">
                            <div id="profile" class="profile-edit tab-pane fade in active">
                                <h2 class="heading-md"><?php echo lang("Manage your profile settings ."); ?></h2>
                                </br>
                                <?php
                                $attributes = array(
                                               'id'    => 'sky-form4',
                                               "class" => "sky-form",
                                );

                                echo form_open_multipart('account/profile_settings', $attributes);
                                ?>
                                <dl class="dl-horizontal">
                                    <dt><strong><?php echo lang("Your first name"); ?> </strong></dt>
                                    <dd class="fname">
                                        <?php echo $this->auth->user("first_name"); ?> 

                                        <span>
                                            <a class="<?php
                                            if($this->lang->lang() == "ar")
                                            {
                                               ?>pull-left<?php
                                               }
                                               else
                                               {
                                                  ?>pull-right<?php } ?>" href="JavaScript:void(0)" onclick="$('.fname').hide();
                                                         $('#fname').show();">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                    </dd>
                                    <dd style="display:none;" id="fname">
                                        <section>
                                            <label class="input">
                                                <input type="text" placeholder="<?php echo lang("First name"); ?>" name="first_name" value="<?php echo $this->auth->user("first_name"); ?>" required>
                                            </label>
                                        </section>
                                    </dd>


                                    <hr>


                                    <dt><strong><?php echo lang("Your last name"); ?> </strong></dt>
                                    <dd class="lname">
                                        <?php echo $this->auth->user("last_name"); ?> 

                                        <span>
                                            <a class="<?php
                                            if($this->lang->lang() == "ar")
                                            {
                                               ?>pull-left<?php
                                               }
                                               else
                                               {
                                                  ?>pull-right<?php } ?>" href="JavaScript:void(0)" onclick="$('.lname').hide();
                                                         $('#lname').show();">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                    </dd>
                                    <dd style="display:none;" id="lname">
                                        <section>
                                            <label class="input">
                                                <input type="text" placeholder="<?php echo lang("Last name"); ?>" name="last_name" value="<?php echo $this->auth->user("last_name"); ?>" required>
                                            </label>
                                        </section>
                                    </dd>


                                    <hr>


                                    <dt><strong><?php echo lang("Company"); ?> </strong></dt>
                                    <dd class="co">
                                        <?php echo $this->auth->user("company"); ?> 

                                        <span>
                                            <a class="<?php
                                            if($this->lang->lang() == "ar")
                                            {
                                               ?>pull-left<?php
                                               }
                                               else
                                               {
                                                  ?>pull-right<?php } ?>" href="JavaScript:void(0)" onclick="$('.co').hide();
                                                         $('#co').show();">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                    </dd>
                                    <dd style="display:none;" id="co">
                                        <section>
                                            <label class="input">
                                                <input type="text" placeholder="<?php echo lang("Company"); ?>" name="company" value="<?php echo $this->auth->user("company"); ?>" required>
                                            </label>
                                        </section>
                                    </dd>


                                    <hr>


                                    <dt><strong><?php echo lang("Job Title"); ?> </strong></dt>
                                    <dd class="jt">
                                        <?php echo $this->auth->user("job_title"); ?> 

                                        <span>
                                            <a class="<?php
                                            if($this->lang->lang() == "ar")
                                            {
                                               ?>pull-left<?php
                                               }
                                               else
                                               {
                                                  ?>pull-right<?php } ?>" href="JavaScript:void(0)" onclick="$('.jt').hide();
                                                         $('#jt').show();">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                    </dd>
                                    <dd style="display:none;" id="jt">
                                        <section>
                                            <label class="input">
                                                <input type="text" placeholder="<?php echo lang("job Title"); ?>" name="job_title" value="<?php echo $this->auth->user("job_title"); ?>" required>
                                            </label>
                                        </section>
                                    </dd>


                                    <hr>


                                    <dt><strong><?php echo lang("Phone"); ?> </strong></dt>
                                    <dd class="ph">
                                        <?php echo $this->auth->user("phone"); ?> 

                                        <span>
                                            <a class="<?php
                                            if($this->lang->lang() == "ar")
                                            {
                                               ?>pull-left<?php
                                               }
                                               else
                                               {
                                                  ?>pull-right<?php } ?>" href="JavaScript:void(0)" onclick="$('.ph').hide();
                                                         $('#ph').show();">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                    </dd>
                                    <dd style="display:none;" id="ph">
                                        <section>
                                            <label class="input">
                                                <input type="text" placeholder="<?php echo lang("Phone"); ?>" name="phone" value="<?php echo $this->auth->user("phone"); ?>" required>
                                            </label>
                                        </section>
                                    </dd>


                                    <hr>


                                    <dt><strong><?php echo lang("Address"); ?> </strong></dt>
                                    <dd class="ad">
                                        <?php echo $this->auth->user("address"); ?> 

                                        <span>
                                            <a class="<?php
                                            if($this->lang->lang() == "ar")
                                            {
                                               ?>pull-left<?php
                                               }
                                               else
                                               {
                                                  ?>pull-right<?php } ?>" href="JavaScript:void(0)" onclick="$('.ad').hide();
                                                         $('#ad').show();">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                    </dd>
                                    <dd style="display:none;" id="ad">
                                        <section>
                                            <label class="input">
                                                <input type="text" placeholder="<?php echo lang("Address"); ?>" name="address" value="<?php echo $this->auth->user("address"); ?>" required>
                                            </label>
                                        </section>
                                    </dd>


                                    <hr>


                                    <dt><strong><?php echo lang("How often you ship Monthly"); ?> </strong></dt>
                                    <dd class="sm">
                                        <?php echo $this->auth->user("ship_monthly"); ?> 

                                        <span>
                                            <a class="<?php
                                            if($this->lang->lang() == "ar")
                                            {
                                               ?>pull-left<?php
                                               }
                                               else
                                               {
                                                  ?>pull-right<?php } ?>" href="JavaScript:void(0)" onclick="$('.sm').hide();
                                                         $('#sm').show();">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </span>
                                    </dd>
                                    <dd style="display:none;" id="sm">
                                        <section>
                                            <label class="input">
                                                <input type="text" placeholder="<?php echo lang("Last name"); ?>" name="ship_monthly" value="<?php echo $this->auth->user("ship_monthly"); ?>" required>
                                            </label>
                                        </section>
                                    </dd>


                                    <hr>


                                    <dt><strong><?php echo lang("Commercial Register"); ?> </strong></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <input type="file" name="commercial_register" value="">
                                            </label>
                                        </section>
                                    </dd>


                                    <hr>


                                    <dt><strong><?php echo lang("Profile Picture"); ?> </strong></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <input type="file" name="avatar" value="">
                                            </label>
                                        </section>
                                    </dd>

                                    <hr>
                                </dl>
                                <button type="button" class="btn-u btn-u-default" onclick="reshow();"><?php echo lang("Cancel"); ?></button>
                                <button type="submit" class="btn-u"><?php echo lang("Save Changes"); ?></button>
                                <?php echo form_close(); ?>
                            </div>

                            <div id="passwordTab" class="profile-edit tab-pane fade">
                                <h2 class="heading-md"><?php echo lang("Manage your Security Settings"); ?></h2>
                                <p><?php echo lang("Change your password."); ?></p>
                                </br>
                                <?php
                                $attributes = array(
                                               'id'         => 'sky-form1',
                                               "class"      => "sky-form",
                                               'novalidate' => "novalidate"
                                );

                                echo form_open('users/resetPassword', $attributes);
                                ?>
                                <dl class="dl-horizontal">
                                    <dt><?php echo lang("Enter your old password"); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="old_password" placeholder="<?php echo lang("Old Password"); ?>" required>
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                                            </label>
                                        </section>
                                    </dd>
                                    <dt><?php echo lang("Enter your new password"); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-lock"></i>
                                                <input type="password" id="password" name="password" placeholder="<?php echo lang("New Password"); ?>" required>
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                                            </label>
                                        </section>
                                    </dd>
                                    <dt><?php echo lang("Confirm Password"); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="repassword" placeholder="<?php echo lang("Confirm password"); ?>" equalTo = "#password">
                                                <b class="tooltip tooltip-bottom-right"><?php echo lang("Don't forget your password"); ?></b>
                                            </label>
                                        </section>
                                    </dd>    
                                </dl>
                                <button type="button" class="btn-u btn-u-default"><?php echo lang("Cancel"); ?></button>
                                <button class="btn-u" type="submit"><?php echo lang("Save Changes"); ?></button>
                                </form>    
                            </div>
                        </div>
                    </div>    
                </div>
                <!--End Profile Body-->
            </div>
        </div><!--/end row-->
    </div><!--/container-->    

</div><!--/container-->
<!-- End Content Part -->
<script type="text/javascript">
   function reshow() {
       $("#lname").hide();
       $("#fname").hide();
       $("#co").hide();
       $("#jt").hide();
       $("#ph").hide();
       $("#ad").hide();
       $("#sm").hide();
       $(".lname").show();
       $(".fname").show();
       $(".co").show();
       $(".jt").show();
       $(".ph").show();
       $(".ad").show();
       $(".sm").show();
   }
</script>
