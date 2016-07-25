
<!--=== Breadcrumbs v3 ===-->
<div class="breadcrumbs-v3 margin-bottom-30">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Pickup Now"); ?></h1>
        <?php
        if($this->auth->isLogin())
        {
           ?>
           <ul class="pull-right breadcrumb">
               <li><a href="<?php echo site_url("account/profile"); ?>"><?php echo lang("Dashboard"); ?></a></li>
               <li class="active"><?php echo lang("Pickup Now"); ?></li>
           </ul>
           <?php
        }
        else
        {
           ?>
           <ul class="pull-right breadcrumb">
               <li><a href="<?php echo site_url("home"); ?>"><?php echo lang("Home"); ?></a></li>
               <li class="active"><?php echo lang("Pickup Now"); ?></li>
           </ul>
        <?php } ?>
    </div>
</div>

<!--=== End Breadcrumbs v3 ===-->

<!--=== Container Part ===-->
<div class="container content">

    <div class="row">

        <div class="tag-box tag-box-v2 margin-bottom-40">
            <p><?php echo lang('online_pickup_page'); ?></p>
        </div>

        <div class="col-xs-12">
            <!-- Reg-Form -->
            <?php
            $attributes = array(
                           'id'         => 'sky-form1',
                           "class"      => "sky-form",
                           'novalidate' => "novalidate"
            );
            if(isset($request))
            {
               $id = $request->id;
            }
            else
            {
               $id = false;
            }
            echo form_open('shipment/pickupRequest/' . $id, $attributes);
            ?>
            <header><?php echo lang("Pickup Form"); ?></header>


            <fieldset>

                <section>

                    <div class="row">
                        <?php
                        if(isset($request))
                        {
                           $fhours_and_minutes = explode(":", $request->from_time);
                           $fampm = explode(" ", $hours_and_minutes[1]);

                           $thours_and_minutes = explode(":", $request->to_time);
                           $tampm = explode(" ", $thours_and_minutes[1]);
                        }
                        ?>
                        <div class="clearfix"></div>
                        <section class="col col-6">
                            <label class="select">
                                <?php
                                echo lang("From Time :");
                                ?>
                            </label>
                            <label class="select col-2" style="float:left;">
                                <select name="fh" required onchange="calTime($(this).val(), $('#fap').val());" style="width:90%;" id="fh">
                                    <option value=""><?php echo lang("Hours"); ?></option>
                                    <?php
                                    for($h = 1; $h <= 12; $h++)
                                    {
                                       echo $h;
                                       ?>
                                       <option value="<?php echo $h; ?>" <?php
                                       if(isset($fhours_and_minutes) && $fhours_and_minutes[0] == $h)
                                       {
                                          echo "selected";
                                       }
                                       ?>><?php echo $h; ?></option>
                                            <?php } ?>
                                </select>
                            </label>
                            <label class="select col-2" style="float:left;">
                                <select name="fm" required style="width:90%;" onchange="
                                       $('#tm').html('<option value=' + $(this).val() + '>' + $(this).val() + '</option>');
                                       $('#dtm').val($(this).val());">
                                    <option value=""><?php echo lang("Mins"); ?></option>
                                    <option value="00" <?php
                                    if(isset($fampm) && $fampm[0] == 00)
                                    {
                                       echo "selected";
                                    }
                                    ?>>00</option>
                                    <option value="15" <?php
                                    if(isset($fampm) && $fampm[0] == 15)
                                    {
                                       echo "selected";
                                    }
                                    ?>>15</option>
                                    <option value="30" <?php
                                    if(isset($fampm) && $fampm[0] == 30)
                                    {
                                       echo "selected";
                                    }
                                    ?>>30</option>
                                    <option value="45" <?php
                                    if(isset($fampm) && $fampm[0] == 45)
                                    {
                                       echo "selected";
                                    }
                                    ?>>45</option>

                                </select>
                            </label>
                            <label class="select col-2" style="float:left;">
                                <select name="fap" required style="width:90%;" onchange="calTime($('#fh').val(), $(this).val());" id="fap">
                                    <!--<option value=""><?php echo lang("am/pm"); ?></option>-->
                                    <option value="am" <?php
                                    if(isset($fampm) && $fampm[1] == "am")
                                    {
                                       echo "selected";
                                    }
                                    ?>><?php
                                                echo lang("AM");
                                                ?></option>
                                    <option  value="pm" <?php
                                    if(isset($fampm) && $fampm[1] == "pm")
                                    {
                                       echo "selected";
                                    }
                                    ?>><?php
                                                 echo lang("PM");
                                                 ?></option>
                                </select>
                            </label>
                        </section>

                        <section class="col col-6">
                            <label class="select">
                                <?php
                                echo lang("To Time :");
                                ?>
                            </label>
                            <label class="select col-2" style="float:left;">
                                <select name="th" style="width:90%;" disabled id="th">
                                    <option value=""><?php echo lang("Hours"); ?></option>
                                </select>
                                <input type="hidden" name="th" value="" id="dth">
                            </label>
                            <label class="select col-2" style="float:left;" >
                                <select name="tm" style="width:90%;" disabled id="tm">
                                    <option value=""><?php echo lang("Mins"); ?></option>
                                </select>
                                <input type="hidden" name="tm" value="" id="dtm">
                            </label>
                            <label class="select col-2" style="float:left;">
                                <select name="tap" style="width:90%;" disabled id="tap">
                                    <option value=""><?php echo lang("AM/PM"); ?></option>
                                </select>
                                <input type="hidden" name="tap" value="" id="dtap">
                            </label>
                        </section>

                    </div>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-calendar"></i>
                            <input type="text" placeholder="<?php echo lang("Pickup Date"); ?>" id="date"  name="pickup_date" value="<?php
                            if(isset($request))
                            {
                               echo $request->pickup_date;
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the Pickup Date");
                                ?></b>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" placeholder="<?php echo lang("Contact Name"); ?>" name="contact_name" value="<?php
                            if(isset($request))
                            {
                               echo $request->contact_name;
                            }
                            else if($this->auth->isLogin())
                            {
                               echo $this->auth->user("first_name") . " " . $this->auth->user(
                                       "last_name");
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the contact name");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="select">
                            <select name="account_type" required onchange="if ($(this).val() == 'accountnumber') {
                                       $('#accnum').show();
                                   } else {
                                       $('#accnum').hide();
                                   }">
                                <option disabled="" selected="" value="0"><?php
                                    echo lang(
                                            "Account Type");
                                    ?></option>
                                <option value="accountnumber" <?php
                                if(isset($request) && $request->account_type == "accountnumber")
                                {
                                   echo "selected";
                                }
                                else if($this->auth->isLogin())
                                {
                                   echo "selected";
                                }
                                ?>><?php echo lang("Account Number"); ?></option>
                                <option value="cash" <?php
                                if(isset($request) && $request->account_type == "cash")
                                {
                                   echo "selected";
                                }
                                ?>><?php echo lang("Cash"); ?></option>
                            </select>
                            <i></i>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the Account Type");
                                ?></b>
                        </label>
                    </section>

                    <section id="accnum" <?php
                    if(!$this->auth->isLogin() || (isset($request) && $request->account_type == "cash"))
                    {
                       ?>style="display:none;"<?php } ?>>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" placeholder="<?php echo lang("Account Number"); ?>" name="account_number" value="<?php
                            if(isset($request))
                            {
                               echo $request->account_number;
                            }
                            else if($this->auth->isLogin())
                            {
                               echo $this->auth->user("integra_account_id");
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the account number");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" placeholder="<?php echo lang("Company"); ?>" name="company" value="<?php
                            if(isset($request))
                            {
                               echo $request->company;
                            }
                            else if($this->auth->isLogin())
                            {
                               echo $this->auth->user("company");
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the company number");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" placeholder="<?php echo lang("Contact Phone No."); ?>" name="contact_phone" value="<?php
                            if(isset($request))
                            {
                               echo $request->contact_phone;
                            }
                            else if($this->auth->isLogin())
                            {
                               echo $this->auth->user("phone");
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the account Phone No.");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-envelope"></i>
                            <input type="email" placeholder="<?php echo lang("Email address"); ?>" name="email" value="<?php
                            if(isset($request))
                            {
                               echo $request->email;
                            }
                            else if($this->auth->isLogin())
                            {
                               echo $this->auth->user("email");
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to verify your Email");
                                ?></b>
                        </label>
                    </section>


                    <section>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                            <textarea id="message" name="content" rows="4" placeholder="<?php
                            echo lang(
                                    "Content");
                            ?>" required><?php
                                          if(isset($request))
                                          {
                                             echo $request->content;
                                          }
                                          ?></textarea>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the Content");
                                ?></b>
                        </label>
                    </section>
                    <div class="row">

                        <section class="col-xs-6">
                            <label class="input">
                                <i class="icon-append fa fa-gear"></i>
                                <input type="text" placeholder="<?php
                                echo lang(
                                        "Weight Value");
                                ?>" name="weight" value="<?php
                                       if(isset($request))
                                       {
                                          echo $request->weight;
                                       }
                                       ?>" required>
                                <b class="tooltip tooltip-bottom-right"><?php
                                    echo lang(
                                            "Needed to enter Weight Value");
                                    ?></b>
                            </label>
                        </section>
                        <section class="col-xs-6">
                            <label class="select">
                                <select name="weight_type" required>
                                    <option disabled="" selected="" value="0"><?php
                                        echo lang(
                                                "Approx. weight.");
                                        ?></option>
                                    <option value="1"><?php echo lang("Grams"); ?></option>
                                    <option value="2" selected><?php
                                        echo lang(
                                                "KGs");
                                        ?></option>
                                </select>
                                <i></i>
                            </label>
                        </section>

                    </div>

                    <section>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                            <textarea id="message" name="source_pickup_address"  rows="4" placeholder="<?php
                            echo lang(
                                    "Source Pickup Address");
                            ?>" required><?php
                                          if(isset($request))
                                          {
                                             echo $request->source_pickup_address;
                                          }
                                          ?></textarea>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter Source pickup address");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-area-chart"></i>
                            <input type="text" placeholder="<?php echo lang("Source City"); ?>" name="source_pickup_city" value="<?php
                            if(isset($request))
                            {
                               echo $request->source_pickup_city;
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter source city");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-area-chart"></i>
                            <input type="text" placeholder="<?php echo lang("Source Governorate"); ?>" name="source_governorate" value="<?php
                            if(isset($request))
                            {
                               echo $request->source_governorate;
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter Source Governorate");
                                ?></b>
                        </label>
                    </section>


                    <!-- DEstination-->
                    <section>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                            <textarea id="message" name="destination_pickup_address"  rows="4" placeholder="<?php
                            echo lang(
                                    "Destination Pickup Address");
                            ?>" required><?php
                                          if(isset($request))
                                          {
                                             echo $request->destination_pickup_address;
                                          }
                                          else if($this->auth->isLogin())
                                          {
                                             echo $this->auth->user("address");
                                          }
                                          ?></textarea>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter destination pickup address");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-area-chart"></i>
                            <input type="text" placeholder="<?php echo lang("Destination City"); ?>" name="destination_pickup_city" value="<?php
                            if(isset($request))
                            {
                               echo $request->destination_pickup_city;
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter destination city");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-area-chart"></i>
                            <input type="text" placeholder="<?php echo lang("Destination Governorate"); ?>" name="destination_governorate" value="<?php
                            if(isset($request))
                            {
                               echo $request->destination_governorate;
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter Destination Governorate");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-puzzle-piece"></i>
                            <input type="text" placeholder="<?php echo lang("No. of pieces"); ?>" name="no_of_pieces" value="<?php
                            if(isset($request))
                            {
                               echo $request->no_of_pieces;
                            }
                            ?>" required number>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter number of pieces");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="select">
                            <select name="product_type" required>
                                <option disabled="" selected="" value="0"><?php
                                    echo lang(
                                            "Product Type");
                                    ?></option>
                                <?php
                                $product_type = $this->config->item('product_type');
                                echo generate_select_options($product_type[$this->lang->lang()], ($request) ? $request->product_type : "");
                                ?>
                            </select>
                            <i></i>
                        </label>
                    </section>
                </section>

            </fieldset>

            <footer>
                <button class="btn-u" type="submit"><?php
                    if(isset($request))
                    {
                       echo lang("Edit");
                    }
                    else
                    {
                       echo lang("Pickup");
                    }
                    ?></button>
            </footer>
            <?php echo form_close(); ?>
            <!-- End Reg-Form -->
        </div>

    </div>

</div><!--/container-->
<!-- End Content Part -->
<script type="text/javascript">

   function calTime(hours, ampm) {
       if (ampm === 'pm') {
           tohours = parseInt(hours) + 14;
           if (tohours > 24) {
               tohours = parseInt(tohours) - 24;
               tap = 'AM';
           } else {
               tohours = parseInt(tohours) - 12;
               tap = 'PM';
           }

       } else if (ampm === 'am') {
           tohours = parseInt(hours) + 2;
           if (tohours > 12) {
               tohours = parseInt(tohours) - 12;
               tap = 'PM';
           }
           else {
               tap = 'AM';
           }

       }

       $('#th').html('<option value=' + tohours + '>' + tohours + '</option>');
       $('#dth').val(tohours);
       $('#tap').html('<option value=' + tap + '>' + tap + '</option>');
       $('#dtap').val(tap);

   }
</script>