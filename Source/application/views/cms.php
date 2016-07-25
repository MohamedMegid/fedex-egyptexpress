

<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php
            $title = lang_db('title');
            echo $cms->$title;
            ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("home"); ?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"><?php echo $cms->$title; ?></li>
        </ul>
    </div>
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
       <div class="row-fluid privacy">
           <div class="col-md-8 md-margin-bottom-40">
               <?php
               $content = lang_db('content');
               echo $cms->$content;
               ?>
           </div>

           <div class="col-md-4 md-margin-bottom-40">
               <div class="responsive-video">
                   <?php if(file_exists(site_url("webroot/uploads/images/" . $cms->image))){?>
                   <img src="<?php echo site_url("webroot/uploads/images/" . $cms->image); ?>" class="img img-responsive img-thumbnail" />
                   <?php }?>
               </div>
           </div>
       </div><!--/row-fluid-->

</div><!--/container-->
<div class="clearfix" style="height:100px;"></div>
<!-- End Content Part -->