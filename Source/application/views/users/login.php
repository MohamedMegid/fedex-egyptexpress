

<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Login"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("home"); ?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"><?php echo lang("Login"); ?></li>
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

            echo form_open('users/validate_login', $attributes);
            ?>
            <div class="reg-header">            
                <h2><?php echo lang("Login to your account"); ?></h2>
            </div>
            <section>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="text" id="emailid" name="email" placeholder="<?php echo lang("Email"); ?>" class="form-control">
                </div>  
            </section>
            <section>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" placeholder="<?php echo lang("Password"); ?>" class="form-control">
                </div>                    
            </section>
            <div class="row">
                <div class="col-md-6 checkbox">
                    <!--<label><input type="checkbox"> <?php echo lang("Stay signed in"); ?></label>-->                        
                </div>
                <div class="col-md-6">
                    <button class="btn-u <?php
                    if($this->lang->lang() == "ar")
                    {
                       ?>pull-left<?php
                            }
                            else
                            {
                               ?>pull-right<?php } ?>" type="submit"><?php echo lang("Login"); ?></button>                        
                </div>
            </div>

            <hr>

            <h4><?php echo lang("Forget your Password ?"); ?></h4>
            <p><?php echo lang("no worries, "); ?><a class="color-green" href="<?php echo site_url("users/forgetPassword") ?>" style="color:#f46600"><?php echo lang("click here"); ?></a> <?php echo lang("to reset your password."); ?></p>
            <?php echo form_close(); ?>           
        </div>
    </div><!--/row-->

</div><!--/container-->
<!-- End Content Part -->

<script type="text/javascript">
   $(document).ready(function () {
       $("#emailid").focus();
   });
</script>