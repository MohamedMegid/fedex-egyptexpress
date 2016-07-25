

<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Reset Password"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("home");?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"><?php echo lang("Reset Password"); ?></li>
        </ul>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">		
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <?php
            $attributes = array(
                           'id'    => 'sky-form4',
                           "class" => "sky-form reg-page",
            );
            if(isset($confirmation_code))
            {
               echo form_open('users/resetPassword/1', $attributes);
            }
            else
            {
               echo form_open('users/resetPassword', $attributes);
            }
            ?>
            <div class="reg-header">            
                <h2><?php echo lang("Reset Your Password"); ?></h2>
            </div>
            <?php
            if(!isset($confirmation_code))
            {
               ?>
               <section>
                   <div class="input-group">
                       <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                       <input type="password" name="old_password" placeholder="<?php echo lang("Old Password"); ?>" class="form-control">
                   </div>
               </section>
               <?php
            }
            else
            {
               ?>
               <input type="hidden" value="<?php echo $confirmation_code; ?>" name="confirmation_code" />
            <?php } ?>    
            <section>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" placeholder="<?php echo lang("Password"); ?>" class="form-control">
                </div> 
            </section>
            <section>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="repassword" placeholder="<?php echo lang("Confirm Password"); ?>" class="form-control">
                </div>                    
            </section>
            <div class="row">
                <div class="col-md-6 checkbox">
                    <!--<label><input type="checkbox"> <?php echo lang("Stay signed in"); ?></label>-->                        
                </div>
                <div class="col-md-6">
                    <button class="btn-u pull-right" type="submit"><?php echo lang("Reset Password"); ?></button>                        
                </div>
            </div>

            <hr>

            <h4><?php echo lang("Don not have an account ?"); ?></h4>
            <p><?php echo lang("no worries, "); ?><a class="color-green" href="<?php echo site_url("users/register"); ?>" style="color:#f46600"><?php echo lang("click here"); ?></a> <?php echo lang("to register ."); ?></p>
           <?php echo form_close();?>          
        </div>
    </div><!--/row-->
</div>

