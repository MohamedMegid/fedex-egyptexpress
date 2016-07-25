
<!--=== Breadcrumbs ===-->
<div class="breadcrumbs">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("FAQs"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"><?php echo lang("FAQ"); ?></li>
        </ul>
    </div>
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container content">
    <div class="row">
        <div class="col-md-9">
            <!-- General Questions -->
            <div class="headline"><h2><?php echo lang("General Questions"); ?></h2></div>
            <div class="panel-group acc-v1 margin-bottom-40" id="accordion">

                <?php
                if($faqs)
                {
                   $question = lang_db('question');
                   $answer = lang_db('answer');
                   $i = 1;
                   foreach($faqs as $faq)
                   {
                      ?>
                      <div class="panel panel-default">
                          <div class="panel-heading">
                              <h4 class="panel-title">
                                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $faq->id ?>">
                                      <?php echo $i++ . '. ' . $faq->$question; ?>
                                  </a>
                              </h4>
                          </div>
                          <div id="collapse<?php echo $faq->id ?>" class="panel-collapse collapse <?php echo ($i == 2) ? 'in' : '' ?>">
                              <div class="panel-body">
                                  <?php echo $faq->$answer; ?>
                              </div>
                          </div>
                      </div>
                      <?php
                   }
                }
                ?>

            </div><!--/acc-v1-->
            <!-- End General Questions -->

        </div><!--/col-md-9-->

        <?php $this->load->view("short_contact"); ?>
    </div><!--/row-->
</div><!--/container-->
<!-- End Content Part -->