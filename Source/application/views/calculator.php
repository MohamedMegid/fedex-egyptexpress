
<!--=== Content Part ===-->
<div class="container content">
    <div class="row margin-bottom-10">
        <div style="text-align: justify;" class="col-xs-12 tag-box tag-box-v2 margin-bottom-40">
            <?php echo lang("calculator_page"); ?>
        </div>


        <div class="col-xs-12 margin-bottom-40">
            <!-- Reg-Form -->
            <?php
            $attributes = array(
                           "class"      => "sky-form",
                           "id"         => "sky-form4",
                           "novalidate" => "novalidate"
            );
            echo form_open('shipment/calculator', $attributes);
            ?>
            <header><?php echo lang("Shipment Cost Calculator"); ?></header>

            <fieldset>

                <section>
                    <div class="row">
                        <section class="col col-6">
                            <?php $city_name = lang_db('city'); ?>
                            <label class="select">
                                <select name="source" required>
                                    <option disabled="" selected="" value="0"><?php echo lang("Source"); ?></option>
                                    <?php
                                    foreach($cities as $city)
                                    {
                                       ?>
                                       <option <?php echo (isset($user_data['source']) && $user_data['source'] == $city->id ) ? 'selected' : '' ?> value="<?php echo $city->id ?>"><?php echo $city->$city_name; ?></option>

                                    <?php } ?>
                                </select>
                                <i></i>
                            </label>
                        </section>

                        <section class="col col-6">
                            <label class="select">
                                <select name="destination" required>
                                    <option disabled="" selected="" value="0"><?php echo lang("Destination"); ?></option>
                                    <?php
                                    foreach($cities as $city)
                                    {
                                       ?>
                                       <option <?php echo (isset($user_data['destination']) && $user_data['destination'] == $city->id ) ? 'selected' : '' ?> value="<?php echo $city->id ?>"><?php echo $city->$city_name; ?></option>

                                    <?php } ?>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </section>

                <section>
                    <div class="row">
                        <section class="col col-4">
                            <label class="input">
                                <i class="icon-append fa fa-text-width"></i>
                                <input type="text" placeholder="<?php echo lang("Width"); ?>" id="width" name="width" value="<?php echo (isset($user_data['width'])) ? $user_data['width'] : '' ?>" >
                                <b class="tooltip tooltip-bottom-right"><?php
                                    echo lang(
                                            "Needed to enter width");
                                    ?></b>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="input">
                                <i class="icon-append fa fa-text-height"></i>
                                <input type="text" placeholder="<?php echo lang("Height"); ?>" id="height" name="height" value="<?php echo (isset($user_data['height'])) ? $user_data['height'] : '' ?>" >
                                <b class="tooltip tooltip-bottom-right"><?php
                                    echo lang(
                                            "Needed to enter height");
                                    ?></b>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="input">
                                <i class="icon-append fa fa-th"></i>
                                <input type="text" placeholder="<?php echo lang("Length"); ?>" id="length" name="length" value="<?php echo (isset($user_data['length'])) ? $user_data['length'] : '' ?>" >
                                <b class="tooltip tooltip-bottom-right"><?php
                                    echo lang(
                                            "Needed to enter length");
                                    ?></b>
                            </label>
                        </section>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <section class="col col-6">
                            <label class="input">
                                <!--<i class="icon-append fa fa-envelope"></i>-->
                                <input type="number" min="0"  step="1" placeholder="<?php echo lang("Weight"); ?>" id="Weight" name="weight" value="<?php echo (isset($user_data['weight'])) ? $user_data['weight'] : '1' ?>" required>
                                <b class="tooltip tooltip-bottom-right"><?php echo lang("Needed to enter Weight"); ?></b>
                            </label>
                        </section>

                        <section class="col col-6">
                            <label class="select">
                                <select name="weight_unit" required>
                                    <option <?php echo (isset($user_data['weight_unit']) && $user_data['weight_unit'] == 2 ) ? 'selected' : '' ?> value="2" >KGs</option>
                                    <option <?php echo (isset($user_data['weight_unit']) && $user_data['weight_unit'] == 1 ) ? 'selected' : '' ?> value="1">Grams</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                </section>

                <section>
                    <section class="col col-6">
                        <button class="btn-u btn-lg calsub" type="submit">
                            <span class="calculate"><?php echo lang("CALCULATE"); ?></span>
                            <span class="cosest"><?php echo lang("COST ESTIMATE"); ?></span>
                        </button>
                    </section>
                    <section class="col col-6">
                        <div class="calresult" id="resultt">00</div>
                        <span><?php echo lang("EGP - Egyptian Pound"); ?></span>
                    </section>
                </section>
                <style>

                </style>


                <?php echo form_close(); ?>
            </fieldset>
        </div>
        <div class="clearfix"></div>
        <?php
        if($final_cost > 0)
        {
           ?>
           <blockquote class="col-xs-12">

               <footer><?php echo lang('Price Details') ?></footer>
               <ul>
                   <?php
                   $weight_in_kg = $user_data['weight'];

                   if($user_data['weight_unit'] == 1)
                      $weight_in_kg = $user_data['weight'] / 1000;

                   $base_price = $final_cost;
                   $fuel = $final_cost * .1;
                   $final_cost = $base_price + $fuel;

                   if($weight_in_kg <= 20)
                   {
                      $postal = $final_cost * 0.1;
                   }
                   else
                   {
                      $postal = 0;
                   }
                   $final_cost = $final_cost + $postal;
                   $sales = $final_cost * .1;
                   $final_cost = $final_cost + $sales;
                   ?>
                   <li><?php echo lang('Base Price = ') . round($base_price, 2); ?></li>
                   <li><?php echo lang('10% Fuel Surcharge = ') . round($fuel, 2); ?></li>
                   <?php
                   if($weight_in_kg <= 20)
                   {
                      ?>
                      <li><?php echo lang('10% Postal Fees up to 20 KG = ') . round($postal, 2); ?></li>
                      <?php
                   }
                   ?>
                   <li><?php echo lang('10% Sales Taxes = ') . round($sales, 2); ?></li>
                   <li><?php echo lang("Total = ") . round($final_cost, 2); ?></li>
                   <li><?php echo lang("This price is for cash customers / account Non holders ."); ?></li>
                   <li><?php echo lang("This price in the box is the total inclusive Price ."); ?></li>
               </ul>
           </blockquote>
        <?php } ?>
        <!--            <footer>
                        <button class="btn-u" type="submit"><?php echo lang("Calculator"); ?></button>
                    </footer>-->

        <!-- End Reg-Form -->
    </div>

</div>


</div><!--/container-->
<!-- End Content Part -->
<link rel="stylesheet" href="<?php echo site_url("webroot/css/jquery-ui.min.css"); ?>">
<script type="text/javascript" src="<?php echo site_url("webroot/js/jquery-ui.min.js"); ?>"></script>

<script type="text/javascript">
<?php
if(isset($final_cost) && !empty($final_cost))
{
   ?>
      $(window).load(function () {
          $('html, body').animate({scrollTop: $('#resultt').offset().top}, 1000);
          counter(<?php echo round($final_cost, 2); ?>);
          shake();
      });
<?php } ?>
   function shake() {
       $("#resultt").effect("shake",
         {times: 5}, 1500);
   }
   function counter(settimmer) {
//       settimmer = parseInt(settimmer);
       if (settimmer <= 10) {
           evalue = 1;
       } else if (settimmer <= 500) {
           evalue = 5;
       } else if (settimmer > 500 && settimmer <= 1000) {
           evalue = 10;
       } else if (settimmer > 1000) {
           evalue = 50;
       }

       var intervalID = window.setInterval(function () {
           var timeCounter = $(".calresult").html();
           var updateTime = eval(timeCounter) + eval(evalue);
           settimmer = eval(settimmer);
           $(".calresult").html(updateTime);
           if (updateTime >= settimmer) {
               window.clearInterval(intervalID);
               intervalID = 0;

               $(".calresult").html(settimmer);
           }
       }, 1);
   }
</script>

