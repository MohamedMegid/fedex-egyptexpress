
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

                    <div class="row text-center">
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

                        <section class="col-lg-4" style="margin-top: 24px;">
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

                        <section class="col-lg-4">
                            <label class="select">
                                <?php
                                echo lang("From Time :");
                                ?>
                            </label>
                            <label class="select col-lg-4" >
                                <select name="fh" required onchange="calTime($(this).val(), $('#fap').val());"  id="fh">
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
                            <label class="select col-lg-4">
                                <select name="fm" required  onchange="
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
                            <label class="select col-lg-4">
                                <select name="fap" required onchange="calTime($('#fh').val(), $(this).val());" id="fap">
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

                        <section class="col-lg-4">
                            <label class="select">
                                <?php
                                echo lang("To Time :");
                                ?>
                            </label>
                            <label class="select col-lg-4">
                                <select name="th" disabled id="th">
                                    <option value=""><?php echo lang("Hours"); ?></option>
                                </select>
                                <input type="hidden" name="th" value="" id="dth">
                            </label>
                            <label class="select col-lg-4" >
                                <select name="tm"  disabled id="tm">
                                    <option value=""><?php echo lang("Mins"); ?></option>
                                </select>
                                <input type="hidden" name="tm" value="" id="dtm">
                            </label>
                            <label class="select col-lg-4" >
                                <select name="tap" disabled id="tap">
                                    <option value=""><?php echo lang("AM/PM"); ?></option>
                                </select>
                                <input type="hidden" name="tap" value="" id="dtap">
                            </label>
                        </section>

                    </div>

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
                    <div class="row">
                        <section class="col-xs-6">
                            <label class="select">
                                <select name="product" required>
                                    <option disabled="" selected="" value="0"><?php
                                        echo lang("Product");
                                        ?></option>
                                    <option value="DOX"><?php echo lang("DOX"); ?></option>
                                    <option value="XPS"><?php echo lang("XPS"); ?></option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col-xs-6">
                            <label class="select">
                                <select name="service" required>
                                    <option disabled="" selected="" value="0"><?php
                                        echo lang("Service");
                                        ?></option>
                                    <option value="AFN"><?php echo lang("AFTERNOON DELIVEREY"); ?></option>
                                    <option value="cod"><?php echo lang("CASH ON DLIVERY"); ?></option>
                                    <option value="DE"><?php echo lang("DOMESTIC ECONOMY"); ?></option>
                                    <option value="EGB"><?php echo lang("Egyptbox"); ?></option>
                                    <option value="EGY1"><?php echo lang("EgyptOne"); ?></option>
                                    <option value="FRG"><?php echo lang("FREIGHT"); ?></option>
                                    <option value="FOV"><?php echo lang("FREIGHT ON VALUE"); ?></option>
                                    <option value="FDR"><?php echo lang("FRIDAY DELIVERY"); ?></option>
                                    <option value="ROV"><?php echo lang("INSURANCE ON VALUE"); ?></option>
                                    <option value="ICS"><?php echo lang("INVOICE COLLECTION SERVICE"); ?></option>
                                    <option value="MRM"><?php echo lang("MAIL ROOM MANAGEMENT"); ?></option>
                                    <option value="MSR"><?php echo lang("MAIL SHOT SERVICE"); ?></option>
                                    <option value="NDS"><?php echo lang("NEXT DAY  EARLY LEVEL SERVICE"); ?></option>
                                    <option value="OCC"><?php echo lang("OCCASSION"); ?></option>
                                    <option value="ONS"><?php echo lang("ONLINE SHIPMENT SERVICE"); ?></option>
                                    <option value="ODA"><?php echo lang("OUTSIDE AREA SERVICE"); ?></option>
                                    <option value="PD"><?php echo lang("PER DAY WAREHOSUING"); ?></option>
                                    <option value="PM"><?php echo lang("PER MONTH WAREHOUSING"); ?></option>
                                    <option value="PW"><?php echo lang("PER WEEK WAREHOUSE CHARGE"); ?></option>
                                    <option value="PY"><?php echo lang("PER YEAR WARE HOUSING"); ?></option>
                                    <option value="CAS"><?php echo lang("PREPAID"); ?></option>
                                    <option value="PRY"><?php echo lang("PRIORITY"); ?></option>
                                    <option value="RTS"><?php echo lang("RETURN SERVICE"); ?></option>
                                    <option value="RDR"><?php echo lang("RIDER"); ?></option>
                                    <option value="SDS"><?php echo lang("SAME DAY SERVICE"); ?></option>
                                    <option value="PAC"><?php echo lang("SPECIAL PACKAGE"); ?></option>
                                    <option value="SPY"><?php echo lang("SPEEDY(QUICK SERVICE)"); ?></option>
                                    <option value="3PL"><?php echo lang("THIRD PARTY LOGISTICS"); ?></option>
                                    <option value="VBD"><?php echo lang("VALUE BOX DIAMOND (24 KG)"); ?></option>
                                    <option value="VBG"><?php echo lang("VALUE BOX GOLD (12 KG)"); ?></option>
                                    <option value="VBP"><?php echo lang("VALUE BOX PLATINUM (18 KG)"); ?></option>
                                    <option value="VBS"><?php echo lang("VALUE BOX SILVER ( 6 KG)"); ?></option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col-xs-6">
                            <label class="select">
                                <select name="origin" required>
                                    <option disabled="" selected="" value="0"><?php
                                        echo lang("Origin");
                                        ?></option>
                                    <option value="RAM"><?php echo lang("10th Of Ramadan"); ?></option>
                                    <option value="OCT"><?php echo lang("6th of October"); ?></option>
                                    <option value="ACCM"><?php echo lang("Accumulated PUP"); ?></option>
                                    <option value="AAC"><?php echo lang("Al Areesh"); ?></option>
                                    <option value="ALX"><?php echo lang("ALEXANDRIA"); ?></option>
                                    <option value="alm"><?php echo lang("ALMENIA"); ?></option>
                                    <option value="sha"><?php echo lang("ALSHARKIA"); ?></option>
                                    <option value="ASU"><?php echo lang("ASSUIT"); ?></option>
                                    <option value="ASW"><?php echo lang("Aswan"); ?></option>
                                    <option value="beh"><?php echo lang("BEHARA"); ?></option>
                                    <option value="BNS"><?php echo lang("Beni-Sueif"); ?></option>
                                    <option value="BRG"><?php echo lang("Borg el Arab"); ?></option>
                                    <option value="CAI"><?php echo lang("CAIRO"); ?></option>
                                    <option value="Cus"><?php echo lang("CUSTOMS"); ?></option>
                                    <option value="DHB"><?php echo lang("Dahab"); ?></option>
                                    <option value="DAK"><?php echo lang("DAKAHLIA"); ?></option>
                                    <option value="DAM"><?php echo lang("DAMANHOUR"); ?></option>
                                    <option value="QDX"><?php echo lang("Damietta"); ?></option>
                                    <option value="DCB"><?php echo lang("DOMESTIC COURIER BASE"); ?></option>
                                    <option value="GOU"><?php echo lang("El Gouna"); ?></option>
                                    <option value="MEN"><?php echo lang("EL MONFIA"); ?></option>
                                    <option value="ELW"><?php echo lang("EL WADI"); ?></option>
                                    <option value="DEX"><?php echo lang("EXCEPTION"); ?></option>
                                    <option value="DEX JUM"><?php echo lang("EXCEPTION JUMIA"); ?></option>
                                    <option value="JUM"><?php echo lang("EXPRESS"); ?></option>
                                    <option value="FAY"><?php echo lang("FAYUOM"); ?></option>
                                    <option value="Fed"><?php echo lang("FEDEX"); ?></option>
                                    <option value="Fgar"><?php echo lang("FEDEX GAREDN"); ?></option>
                                    <option value="Fhel"><?php echo lang("FEDEX HELIOPOLIS"); ?></option>
                                    <option value="Fmad"><?php echo lang("FEDEX MAADI"); ?></option>
                                    <option value="Fmoh"><?php echo lang("FEDEX MOHANDSEEN"); ?></option>
                                    <option value="GAR"><?php echo lang("Garden City"); ?></option>
                                    <option value="GHA"><?php echo lang("GHARBEYA"); ?></option>
                                    <option value="GIZ"><?php echo lang("GIZA"); ?></option>
                                    <option value="GUM"><?php echo lang("GUMIA PROJECT"); ?></option>
                                    <option value="HEL"><?php echo lang("Helopolis"); ?></option>
                                    <option value="HUR"><?php echo lang("Hurghada"); ?></option>
                                    <option value="QIV"><?php echo lang("Ismailia"); ?></option>
                                    <option value="JUS"><?php echo lang("JUMIA SORT"); ?></option>
                                    <option value="KAD"><?php echo lang("KAFER EL DAWAR"); ?></option>
                                    <option value="KAZ"><?php echo lang("KAFER EL ZAYAT"); ?></option>
                                    <option value="KAF"><?php echo lang("KAFR EL SHIKH"); ?></option>
                                    <option value="KAL"><?php echo lang("KALIUOB"); ?></option>
                                    <option value="QOS"><?php echo lang("Kosseir"); ?></option>
                                    <option value="LXR"><?php echo lang("Luxor"); ?></option>
                                    <option value="MAD"><?php echo lang("Maadi"); ?></option>
                                    <option value="QEK"><?php echo lang("Mahalla"); ?></option>
                                    <option value="QSU"><?php echo lang("Mansoura"); ?></option>
                                    <option value="MRA"><?php echo lang("MARSA ALLAM"); ?></option>
                                    <option value="MUH"><?php echo lang("Marsa Matrouh"); ?></option>
                                    <option value="EMY"><?php echo lang("Minya"); ?></option>
                                    <option value="MOH"><?php echo lang("MOHANDISEEN"); ?></option>
                                    <option value="OBR"><?php echo lang("OBOUR"); ?></option>
                                    <option value="OTH"><?php echo lang("OUT STATION LOC"); ?></option>
                                    <option value="PSD"><?php echo lang("Port Said"); ?></option>
                                    <option value="QU"><?php echo lang("QUNA"); ?></option>
                                    <option value="RHB"><?php echo lang("Rehab City"); ?></option>
                                    <option value="RHEL"><?php echo lang("RETAIL.HEL"); ?></option>
                                    <option value="SPRO"><?php echo lang("S-project"); ?></option>
                                    <option value="SDT"><?php echo lang("Sadat"); ?></option>
                                    <option value="SFG"><?php echo lang("Safaga"); ?></option>
                                    <option value="SSH"><?php echo lang("Sharm El Sheikh"); ?></option>
                                    <option value="QHX"><?php echo lang("Sohag"); ?></option>
                                    <option value="SPH"><?php echo lang("SPECIAL HANDLING"); ?></option>
                                    <option value="SUZ"><?php echo lang("Suez"); ?></option>
                                    <option value="TAB"><?php echo lang("TABA"); ?></option>
                                    <option value="QTT"><?php echo lang("Tanta"); ?></option>
                                    <option value="WH"><?php echo lang("WARE HOUS"); ?></option>
                                    <option value="QZZ"><?php echo lang("Zakazeek"); ?></option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col-xs-6">
                            <label class="select">
                                <select name="destination" required>
                                    <option disabled="" selected="" value="0"><?php
                                        echo lang("Destination");
                                        ?></option>
                                    <option value="RAM"><?php echo lang("10th Of Ramadan"); ?></option>
                                    <option value="OCT"><?php echo lang("6th of October"); ?></option>
                                    <option value="ACCM"><?php echo lang("Accumulated PUP"); ?></option>
                                    <option value="AAC"><?php echo lang("Al Areesh"); ?></option>
                                    <option value="ALX"><?php echo lang("ALEXANDRIA"); ?></option>
                                    <option value="alm"><?php echo lang("ALMENIA"); ?></option>
                                    <option value="sha"><?php echo lang("ALSHARKIA"); ?></option>
                                    <option value="ASU"><?php echo lang("ASSUIT"); ?></option>
                                    <option value="ASW"><?php echo lang("Aswan"); ?></option>
                                    <option value="beh"><?php echo lang("BEHARA"); ?></option>
                                    <option value="BNS"><?php echo lang("Beni-Sueif"); ?></option>
                                    <option value="BRG"><?php echo lang("Borg el Arab"); ?></option>
                                    <option value="CAI"><?php echo lang("CAIRO"); ?></option>
                                    <option value="Cus"><?php echo lang("CUSTOMS"); ?></option>
                                    <option value="DHB"><?php echo lang("Dahab"); ?></option>
                                    <option value="DAK"><?php echo lang("DAKAHLIA"); ?></option>
                                    <option value="DAM"><?php echo lang("DAMANHOUR"); ?></option>
                                    <option value="QDX"><?php echo lang("Damietta"); ?></option>
                                    <option value="DCB"><?php echo lang("DOMESTIC COURIER BASE"); ?></option>
                                    <option value="GOU"><?php echo lang("El Gouna"); ?></option>
                                    <option value="MEN"><?php echo lang("EL MONFIA"); ?></option>
                                    <option value="ELW"><?php echo lang("EL WADI"); ?></option>
                                    <option value="DEX"><?php echo lang("EXCEPTION"); ?></option>
                                    <option value="DEX JUM"><?php echo lang("EXCEPTION JUMIA"); ?></option>
                                    <option value="JUM"><?php echo lang("EXPRESS"); ?></option>
                                    <option value="FAY"><?php echo lang("FAYUOM"); ?></option>
                                    <option value="Fed"><?php echo lang("FEDEX"); ?></option>
                                    <option value="Fgar"><?php echo lang("FEDEX GAREDN"); ?></option>
                                    <option value="Fhel"><?php echo lang("FEDEX HELIOPOLIS"); ?></option>
                                    <option value="Fmad"><?php echo lang("FEDEX MAADI"); ?></option>
                                    <option value="Fmoh"><?php echo lang("FEDEX MOHANDSEEN"); ?></option>
                                    <option value="GAR"><?php echo lang("Garden City"); ?></option>
                                    <option value="GHA"><?php echo lang("GHARBEYA"); ?></option>
                                    <option value="GIZ"><?php echo lang("GIZA"); ?></option>
                                    <option value="GUM"><?php echo lang("GUMIA PROJECT"); ?></option>
                                    <option value="HEL"><?php echo lang("Helopolis"); ?></option>
                                    <option value="HUR"><?php echo lang("Hurghada"); ?></option>
                                    <option value="QIV"><?php echo lang("Ismailia"); ?></option>
                                    <option value="JUS"><?php echo lang("JUMIA SORT"); ?></option>
                                    <option value="KAD"><?php echo lang("KAFER EL DAWAR"); ?></option>
                                    <option value="KAZ"><?php echo lang("KAFER EL ZAYAT"); ?></option>
                                    <option value="KAF"><?php echo lang("KAFR EL SHIKH"); ?></option>
                                    <option value="KAL"><?php echo lang("KALIUOB"); ?></option>
                                    <option value="QOS"><?php echo lang("Kosseir"); ?></option>
                                    <option value="LXR"><?php echo lang("Luxor"); ?></option>
                                    <option value="MAD"><?php echo lang("Maadi"); ?></option>
                                    <option value="QEK"><?php echo lang("Mahalla"); ?></option>
                                    <option value="QSU"><?php echo lang("Mansoura"); ?></option>
                                    <option value="MRA"><?php echo lang("MARSA ALLAM"); ?></option>
                                    <option value="MUH"><?php echo lang("Marsa Matrouh"); ?></option>
                                    <option value="EMY"><?php echo lang("Minya"); ?></option>
                                    <option value="MOH"><?php echo lang("MOHANDISEEN"); ?></option>
                                    <option value="OBR"><?php echo lang("OBOUR"); ?></option>
                                    <option value="OTH"><?php echo lang("OUT STATION LOC"); ?></option>
                                    <option value="PSD"><?php echo lang("Port Said"); ?></option>
                                    <option value="QU"><?php echo lang("QUNA"); ?></option>
                                    <option value="RHB"><?php echo lang("Rehab City"); ?></option>
                                    <option value="RHEL"><?php echo lang("RETAIL.HEL"); ?></option>
                                    <option value="SPRO"><?php echo lang("S-project"); ?></option>
                                    <option value="SDT"><?php echo lang("Sadat"); ?></option>
                                    <option value="SFG"><?php echo lang("Safaga"); ?></option>
                                    <option value="SSH"><?php echo lang("Sharm El Sheikh"); ?></option>
                                    <option value="QHX"><?php echo lang("Sohag"); ?></option>
                                    <option value="SPH"><?php echo lang("SPECIAL HANDLING"); ?></option>
                                    <option value="SUZ"><?php echo lang("Suez"); ?></option>
                                    <option value="TAB"><?php echo lang("TABA"); ?></option>
                                    <option value="QTT"><?php echo lang("Tanta"); ?></option>
                                    <option value="WH"><?php echo lang("WARE HOUS"); ?></option>
                                    <option value="QZZ"><?php echo lang("Zakazeek"); ?></option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col-lg-4">
                            <label class="input">
                                <i class="icon-append fa fa-puzzle-piece"></i>
                                <input type="text" placeholder="<?php echo lang("No. of pieces"); ?>" name="no_of_pieces" value="<?php
                                if(isset($request))
                                {
                                   echo $request->no_of_pieces;
                                }
                                ?>" required number id="numberpfpieces">
                                <b class="tooltip tooltip-bottom-right"><?php
                                    echo lang(
                                            "Needed to enter number of pieces");
                                    ?></b>
                            </label>
                        </section>
                        <section class="col-lg-4">
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
                                       ?>" required id="wvalue">
                                <b class="tooltip tooltip-bottom-right"><?php
                                    echo lang(
                                            "Needed to enter Weight Value");
                                    ?></b>
                            </label>
                        </section>
                        <section class="col-lg-4">
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
                        <label class="input">
                            <i class="icon-append fa fa-map-marker"></i>
                            <input type="text" placeholder="<?php echo lang("Pickup Location"); ?>" name="pickup_location" value="<?php
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
                                        "Needed to enter the pickup location");
                                ?></b>
                        </label>
                    </section>



                    <section>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                            <textarea id="message" name="spl_inst" rows="4" placeholder="<?php
                            echo lang(
                                    "Special Instructions");
                            ?>" required><?php
                                          if(isset($request))
                                          {
                                             echo $request->spl_inst;
                                          }
                                          ?></textarea>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the special instrucations");
                                ?></b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-maxcdn"></i>
                            <input type="text" placeholder="<?php echo lang("LandMark"); ?>" name="LandMark" value="<?php
                            if(isset($request))
                            {
                               echo $request->company;
                            }
                            ?>" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter the landmark ");
                                ?></b>
                        </label>
                    </section>

                    <div class="row text-center">
                        <div class="col-lg-6">
                            <h4><?php echo lang("Shipper Details"); ?></h4>
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-user"></i>
                                    <input type="text" id="shiper_autocomplete" placeholder="<?php echo lang("Shipper Name"); ?>" name="shipper_name" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->contact_phone;
                                    }
                                    ?>" required>
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the shipper name");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-pencil"></i>
                                    <input type="text" placeholder="<?php echo lang("Shipper Contact Person"); ?>" name="shipper_cperson" id="shipper_cperson" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->contact_phone;
                                    }
                                    ?>" required>
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the shipper contact person");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-road"></i>
                                    <input type="text" placeholder="<?php echo lang("Shipper Address1"); ?>" id="shipper_address1" name="shipper_address1" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->shipper_address1;
                                    }
                                    ?>" required>
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the shipper address1");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-road"></i>
                                    <input type="text" placeholder="<?php echo lang("Shipper Address2"); ?>" id="shipper_address2" name="shipper_address2" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->shipper_address2;
                                    }
                                    ?>" required>
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the shipper address2");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-inbox"></i>
                                    <input type="text" placeholder="<?php echo lang("Shipper Tel/SMS"); ?>" id="shipper_tel" name="shipper_tel" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->shipper_tel;
                                    }
                                    ?>" required id="sh-tel">
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the shipper tel");
                                        ?></b>
                                </label>
                            </section>
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-fax"></i>
                                    <input type="text" placeholder="<?php echo lang("Shipper Fax"); ?>" id="shipper_fax" name="shipper_fax" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->shipper_fax;
                                    }
                                    ?>" required id="sh-fax">
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the shipper fax");
                                        ?></b>
                                </label>
                            </section>
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-phone"></i>
                                    <input type="text" placeholder="<?php echo lang("Shipper mobile"); ?>" id="shipper_mobile" name="shipper_mobile" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->shipper_mobile;
                                    }
                                    ?>" required id="sh-mob">
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the shipper mobile");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="select">
                                    <select id="shipper_city" name="shipper_city" required>
                                        <option disabled="" selected="" value="0"><?php
                                            echo lang("Shipper City");
                                            ?></option>
                                        <option value="RAM"><?php echo lang("10th Of Ramadan"); ?></option>
                                        <option value="OCT"><?php echo lang("6th of October"); ?></option>
                                        <option value="ACCM"><?php echo lang("Accumulated PUP"); ?></option>
                                        <option value="AAC"><?php echo lang("Al Areesh"); ?></option>
                                        <option value="ALX"><?php echo lang("ALEXANDRIA"); ?></option>
                                        <option value="alm"><?php echo lang("ALMENIA"); ?></option>
                                        <option value="sha"><?php echo lang("ALSHARKIA"); ?></option>
                                        <option value="ASU"><?php echo lang("ASSUIT"); ?></option>
                                        <option value="ASW"><?php echo lang("Aswan"); ?></option>
                                        <option value="beh"><?php echo lang("BEHARA"); ?></option>
                                        <option value="BNS"><?php echo lang("Beni-Sueif"); ?></option>
                                        <option value="BRG"><?php echo lang("Borg el Arab"); ?></option>
                                        <option value="CAI"><?php echo lang("CAIRO"); ?></option>
                                        <option value="Cus"><?php echo lang("CUSTOMS"); ?></option>
                                        <option value="DHB"><?php echo lang("Dahab"); ?></option>
                                        <option value="DAK"><?php echo lang("DAKAHLIA"); ?></option>
                                        <option value="DAM"><?php echo lang("DAMANHOUR"); ?></option>
                                        <option value="QDX"><?php echo lang("Damietta"); ?></option>
                                        <option value="DCB"><?php echo lang("DOMESTIC COURIER BASE"); ?></option>
                                        <option value="GOU"><?php echo lang("El Gouna"); ?></option>
                                        <option value="MEN"><?php echo lang("EL MONFIA"); ?></option>
                                        <option value="ELW"><?php echo lang("EL WADI"); ?></option>
                                        <option value="DEX"><?php echo lang("EXCEPTION"); ?></option>
                                        <option value="DEX JUM"><?php echo lang("EXCEPTION JUMIA"); ?></option>
                                        <option value="JUM"><?php echo lang("EXPRESS"); ?></option>
                                        <option value="FAY"><?php echo lang("FAYUOM"); ?></option>
                                        <option value="Fed"><?php echo lang("FEDEX"); ?></option>
                                        <option value="Fgar"><?php echo lang("FEDEX GAREDN"); ?></option>
                                        <option value="Fhel"><?php echo lang("FEDEX HELIOPOLIS"); ?></option>
                                        <option value="Fmad"><?php echo lang("FEDEX MAADI"); ?></option>
                                        <option value="Fmoh"><?php echo lang("FEDEX MOHANDSEEN"); ?></option>
                                        <option value="GAR"><?php echo lang("Garden City"); ?></option>
                                        <option value="GHA"><?php echo lang("GHARBEYA"); ?></option>
                                        <option value="GIZ"><?php echo lang("GIZA"); ?></option>
                                        <option value="GUM"><?php echo lang("GUMIA PROJECT"); ?></option>
                                        <option value="HEL"><?php echo lang("Helopolis"); ?></option>
                                        <option value="HUR"><?php echo lang("Hurghada"); ?></option>
                                        <option value="QIV"><?php echo lang("Ismailia"); ?></option>
                                        <option value="JUS"><?php echo lang("JUMIA SORT"); ?></option>
                                        <option value="KAD"><?php echo lang("KAFER EL DAWAR"); ?></option>
                                        <option value="KAZ"><?php echo lang("KAFER EL ZAYAT"); ?></option>
                                        <option value="KAF"><?php echo lang("KAFR EL SHIKH"); ?></option>
                                        <option value="KAL"><?php echo lang("KALIUOB"); ?></option>
                                        <option value="QOS"><?php echo lang("Kosseir"); ?></option>
                                        <option value="LXR"><?php echo lang("Luxor"); ?></option>
                                        <option value="MAD"><?php echo lang("Maadi"); ?></option>
                                        <option value="QEK"><?php echo lang("Mahalla"); ?></option>
                                        <option value="QSU"><?php echo lang("Mansoura"); ?></option>
                                        <option value="MRA"><?php echo lang("MARSA ALLAM"); ?></option>
                                        <option value="MUH"><?php echo lang("Marsa Matrouh"); ?></option>
                                        <option value="EMY"><?php echo lang("Minya"); ?></option>
                                        <option value="MOH"><?php echo lang("MOHANDISEEN"); ?></option>
                                        <option value="OBR"><?php echo lang("OBOUR"); ?></option>
                                        <option value="OTH"><?php echo lang("OUT STATION LOC"); ?></option>
                                        <option value="PSD"><?php echo lang("Port Said"); ?></option>
                                        <option value="QU"><?php echo lang("QUNA"); ?></option>
                                        <option value="RHB"><?php echo lang("Rehab City"); ?></option>
                                        <option value="RHEL"><?php echo lang("RETAIL.HEL"); ?></option>
                                        <option value="SPRO"><?php echo lang("S-project"); ?></option>
                                        <option value="SDT"><?php echo lang("Sadat"); ?></option>
                                        <option value="SFG"><?php echo lang("Safaga"); ?></option>
                                        <option value="SSH"><?php echo lang("Sharm El Sheikh"); ?></option>
                                        <option value="QHX"><?php echo lang("Sohag"); ?></option>
                                        <option value="SPH"><?php echo lang("SPECIAL HANDLING"); ?></option>
                                        <option value="SUZ"><?php echo lang("Suez"); ?></option>
                                        <option value="TAB"><?php echo lang("TABA"); ?></option>
                                        <option value="QTT"><?php echo lang("Tanta"); ?></option>
                                        <option value="WH"><?php echo lang("WARE HOUS"); ?></option>
                                        <option value="QZZ"><?php echo lang("Zakazeek"); ?></option>
                                    </select>
                                    <i></i>
                                </label>

                            </section>
                        </div>

                        <div class="col-lg-6">
                            <h4><?php echo lang("Consignee Details"); ?></h4>
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-user"></i>
                                    <input type="text" id="consignee_autocomplete" placeholder="<?php echo lang("Consignee Name"); ?>" name="consignee_name" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->consignee_name;
                                    }
                                    ?>" required>
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the consignee name");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-pencil"></i>
                                    <input type="text" placeholder="<?php echo lang("Consignee Contact Person"); ?>" id="consignee_cperson" name="consignee_cperson" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->consignee_cperson;
                                    }
                                    ?>" required>
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the consignee contact person");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-road"></i>
                                    <input type="text" placeholder="<?php echo lang("Consignee Address1"); ?>" id="consignee_address1" name="consignee_address1" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->consignee_address1;
                                    }
                                    ?>" required>
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the consignee address1");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-road"></i>
                                    <input type="text" placeholder="<?php echo lang("Consignee Address2"); ?>" id="consignee_address2" name="consignee_address2" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->consignee_address2;
                                    }
                                    ?>" required>
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the consignee address2");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-inbox"></i>
                                    <input type="text" placeholder="<?php echo lang("Consignee Tel/SMS"); ?>" id="consignee_tel" name="consignee_tel" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->consignee_tel;
                                    }
                                    ?>" required id="con-tel">
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the consignee tel");
                                        ?></b>
                                </label>
                            </section>
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-fax"></i>
                                    <input type="text" placeholder="<?php echo lang("Consignee Fax"); ?>" id="consignee_fax" name="consignee_fax" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->consignee_fax;
                                    }
                                    ?>" required id="con-fax">
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the consignee fax");
                                        ?></b>
                                </label>
                            </section>
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-phone"></i>
                                    <input type="text" placeholder="<?php echo lang("Consignee mobile"); ?>" id="consignee_mobile" name="consignee_mobile" value="<?php
                                    if(isset($request))
                                    {
                                       echo $request->consignee_mobile;
                                    }
                                    ?>" required id="con-mob">
                                    <b class="tooltip tooltip-bottom-right"><?php
                                        echo lang(
                                                "Needed to enter the consignee mobile");
                                        ?></b>
                                </label>
                            </section>

                            <section>
                                <label class="select">
                                    <select id="consignee_city" name="consignee_city" required>
                                        <option disabled="" selected="" value="0"><?php
                                            echo lang("Consignee City");
                                            ?></option>
                                        <option value="RAM"><?php echo lang("10th Of Ramadan"); ?></option>
                                        <option value="OCT"><?php echo lang("6th of October"); ?></option>
                                        <option value="ACCM"><?php echo lang("Accumulated PUP"); ?></option>
                                        <option value="AAC"><?php echo lang("Al Areesh"); ?></option>
                                        <option value="ALX"><?php echo lang("ALEXANDRIA"); ?></option>
                                        <option value="alm"><?php echo lang("ALMENIA"); ?></option>
                                        <option value="sha"><?php echo lang("ALSHARKIA"); ?></option>
                                        <option value="ASU"><?php echo lang("ASSUIT"); ?></option>
                                        <option value="ASW"><?php echo lang("Aswan"); ?></option>
                                        <option value="beh"><?php echo lang("BEHARA"); ?></option>
                                        <option value="BNS"><?php echo lang("Beni-Sueif"); ?></option>
                                        <option value="BRG"><?php echo lang("Borg el Arab"); ?></option>
                                        <option value="CAI"><?php echo lang("CAIRO"); ?></option>
                                        <option value="Cus"><?php echo lang("CUSTOMS"); ?></option>
                                        <option value="DHB"><?php echo lang("Dahab"); ?></option>
                                        <option value="DAK"><?php echo lang("DAKAHLIA"); ?></option>
                                        <option value="DAM"><?php echo lang("DAMANHOUR"); ?></option>
                                        <option value="QDX"><?php echo lang("Damietta"); ?></option>
                                        <option value="DCB"><?php echo lang("DOMESTIC COURIER BASE"); ?></option>
                                        <option value="GOU"><?php echo lang("El Gouna"); ?></option>
                                        <option value="MEN"><?php echo lang("EL MONFIA"); ?></option>
                                        <option value="ELW"><?php echo lang("EL WADI"); ?></option>
                                        <option value="DEX"><?php echo lang("EXCEPTION"); ?></option>
                                        <option value="DEX JUM"><?php echo lang("EXCEPTION JUMIA"); ?></option>
                                        <option value="JUM"><?php echo lang("EXPRESS"); ?></option>
                                        <option value="FAY"><?php echo lang("FAYUOM"); ?></option>
                                        <option value="Fed"><?php echo lang("FEDEX"); ?></option>
                                        <option value="Fgar"><?php echo lang("FEDEX GAREDN"); ?></option>
                                        <option value="Fhel"><?php echo lang("FEDEX HELIOPOLIS"); ?></option>
                                        <option value="Fmad"><?php echo lang("FEDEX MAADI"); ?></option>
                                        <option value="Fmoh"><?php echo lang("FEDEX MOHANDSEEN"); ?></option>
                                        <option value="GAR"><?php echo lang("Garden City"); ?></option>
                                        <option value="GHA"><?php echo lang("GHARBEYA"); ?></option>
                                        <option value="GIZ"><?php echo lang("GIZA"); ?></option>
                                        <option value="GUM"><?php echo lang("GUMIA PROJECT"); ?></option>
                                        <option value="HEL"><?php echo lang("Helopolis"); ?></option>
                                        <option value="HUR"><?php echo lang("Hurghada"); ?></option>
                                        <option value="QIV"><?php echo lang("Ismailia"); ?></option>
                                        <option value="JUS"><?php echo lang("JUMIA SORT"); ?></option>
                                        <option value="KAD"><?php echo lang("KAFER EL DAWAR"); ?></option>
                                        <option value="KAZ"><?php echo lang("KAFER EL ZAYAT"); ?></option>
                                        <option value="KAF"><?php echo lang("KAFR EL SHIKH"); ?></option>
                                        <option value="KAL"><?php echo lang("KALIUOB"); ?></option>
                                        <option value="QOS"><?php echo lang("Kosseir"); ?></option>
                                        <option value="LXR"><?php echo lang("Luxor"); ?></option>
                                        <option value="MAD"><?php echo lang("Maadi"); ?></option>
                                        <option value="QEK"><?php echo lang("Mahalla"); ?></option>
                                        <option value="QSU"><?php echo lang("Mansoura"); ?></option>
                                        <option value="MRA"><?php echo lang("MARSA ALLAM"); ?></option>
                                        <option value="MUH"><?php echo lang("Marsa Matrouh"); ?></option>
                                        <option value="EMY"><?php echo lang("Minya"); ?></option>
                                        <option value="MOH"><?php echo lang("MOHANDISEEN"); ?></option>
                                        <option value="OBR"><?php echo lang("OBOUR"); ?></option>
                                        <option value="OTH"><?php echo lang("OUT STATION LOC"); ?></option>
                                        <option value="PSD"><?php echo lang("Port Said"); ?></option>
                                        <option value="QU"><?php echo lang("QUNA"); ?></option>
                                        <option value="RHB"><?php echo lang("Rehab City"); ?></option>
                                        <option value="RHEL"><?php echo lang("RETAIL.HEL"); ?></option>
                                        <option value="SPRO"><?php echo lang("S-project"); ?></option>
                                        <option value="SDT"><?php echo lang("Sadat"); ?></option>
                                        <option value="SFG"><?php echo lang("Safaga"); ?></option>
                                        <option value="SSH"><?php echo lang("Sharm El Sheikh"); ?></option>
                                        <option value="QHX"><?php echo lang("Sohag"); ?></option>
                                        <option value="SPH"><?php echo lang("SPECIAL HANDLING"); ?></option>
                                        <option value="SUZ"><?php echo lang("Suez"); ?></option>
                                        <option value="TAB"><?php echo lang("TABA"); ?></option>
                                        <option value="QTT"><?php echo lang("Tanta"); ?></option>
                                        <option value="WH"><?php echo lang("WARE HOUS"); ?></option>
                                        <option value="QZZ"><?php echo lang("Zakazeek"); ?></option>
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                        </div>
                    </div>

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
       if (ampm === "am" && parseInt(hours) === 10) {
           tohours = 12;
           tap = "PM";
       }
       else if (ampm === "pm" && parseInt(hours) === 10) {
           tohours = 12;
           tap = "AM";
       }
       else if (ampm === 'pm') {
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


   $(document).ready(function () {
       $("#phonee , #numberpfpieces , #wvalue ,  #sh-tel , #sh-mob , #con-fax , #sh-fax , #con-tel , #con-mob").keydown(function (e) {
           // Allow: backspace, delete, tab, escape, enter and .
           if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                   // Allow: Ctrl+A
                           (e.keyCode == 65 && e.ctrlKey === true) ||
                           // Allow: Ctrl+C
                                   (e.keyCode == 67 && e.ctrlKey === true) ||
                                   // Allow: Ctrl+X
                                           (e.keyCode == 88 && e.ctrlKey === true) ||
                                           // Allow: home, end, left, right
                                                   (e.keyCode >= 35 && e.keyCode <= 39)) {
                                       // let it happen, don't do anything
                                       return;
                                   }
                                   // Ensure that it is a number and stop the keypress
                                   if (((e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                       e.preventDefault();
                                   }
                               });
                   });
</script>
<script type="text/javascript" src="<?php echo site_url("webroot/autocomplete/js/jquery-1.9.1.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/autocomplete/js/jquery.autocomplete.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/autocomplete/js/pickup-autocomplete.js"); ?>"></script>