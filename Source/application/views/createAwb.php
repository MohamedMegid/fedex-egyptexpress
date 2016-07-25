
<!--=== Breadcrumbs v3 ===-->
<div class="breadcrumbs-v3 margin-bottom-30">
    <div class="container">
        <h1 class="pull-left"><?php echo lang("Create AWB"); ?></h1>
        <ul class="pull-right breadcrumb">
            <li><a href="<?php echo site_url("home"); ?>"><?php echo lang("Home"); ?></a></li>
            <li class="active"><?php echo lang("Create AWB"); ?></li>
        </ul>
    </div>
</div>

<!--=== End Breadcrumbs v3 ===-->

<!--=== Container Part ===-->
<div class="container content">

    <div class="row">
        <div class="col-xs-12" style="display: none" id="bulkupload">

            <?php
            $attributes = array(
                           'id'         => 'sky-form1',
                           "class"      => "sky-form",
                           'novalidate' => "novalidate"
            );
            echo form_open_multipart('shipment/bulkAWB/', $attributes);
            ?>
            <header><?php echo lang("Upload Bulk AWB"); ?>
                <button class="btn btn-primary pull-right" onclick="$('#bulkupload').hide(500);
                       $('#createform').show();
                       return false;"><?php echo lang('Create AWB Form') ?></button></header>
            <fieldset>
                <section>
                    <label class="input">
                        <i class="icon-append fa fa-file-excel-o"></i>
                        <input type="file"  name="bulk_awb" required>
                        <b class="tooltip tooltip-bottom-right"><?php
                            echo lang("Needed to upload AWB excel file");
                            ?></b>
                    </label>
                    <a href="<?php echo site_url("webroot/images/Sample Sheet.xlsx");?>" style="color:#3071A9;"><?php echo lang("Download Sample Excel Sheet"); ?></a>
                </section>
            </fieldset>
            <footer>
                <button class="btn-u" type="submit"><?php echo lang("Submit"); ?></button>
            </footer>
            <?php echo form_close(); ?>

        </div>

        <div class="col-xs-12" id="createform">
            <!-- Reg-Form -->



            <?php
            $attributes = array(
                           'id'         => 'sky-form4',
                           "class"      => "sky-form",
                           'novalidate' => "novalidate"
            );
            echo form_open('shipment/createAWB/', $attributes);
            ?>
            <header><?php echo lang("Create AWB Form"); ?>
                <button class="btn btn-primary pull-right" onclick="$('#createform').hide(500);
                       $('#bulkupload').show();
                       return false;"><?php echo lang('Bulk Upload') ?></button>
            </header>
            <fieldset>

                <section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" placeholder="<?php echo lang("Recipient Name"); ?>" name="recipient_name" id="recipient_autocomplete" value="" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang("Needed to enter the recipient name");
                                ?></b>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" placeholder="<?php echo lang("Recipient Phone"); ?>" name="recipient_phone" id="recipient_phone" value="" required number>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang("Needed to enter the recipient Phone");
                                ?></b>
                        </label>
                    </section>
                    <section>
                        <label class="select">
                            <select name="recipient_city" id="recipient_city" required>
                                <option disabled="" selected="" value="0"><?php echo lang("Recipient City"); ?></option>
                                <?php
                                if($cities)
                                {
                                   foreach($cities as $city)
                                   {
                                      ?>
                                      <option value="<?php echo $city->code ?>"><?php echo $city->name ?></option>
                                      <?php
                                   }
                                }
                                ?>

                            </select>
                            <i></i>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-comment"></i>
                            <input type="text" placeholder="<?php echo lang("First Recipient Address"); ?>" name="recipient_address1" id="recipient_address1" value="" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter first recipient address");
                                ?></b>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-comment"></i>
                            <input type="text" placeholder="<?php echo lang("Second Recipient Address"); ?>" name="recipient_address2" id="recipient_address2" value="" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter second recipient address");
                                ?></b>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-comment"></i>
                            <input type="text" placeholder="<?php echo lang("Amount"); ?>" name="COD_amount" value=""  number>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter code amount");
                                ?></b>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-puzzle-piece"></i>
                            <input type="text" placeholder="<?php echo lang("No. of pieces"); ?>" name="no_of_pieces" value="" required number>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter number of pieces");
                                ?></b>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-gear"></i>
                            <input type="text" placeholder="<?php
                            echo lang(
                                    "Weight Value");
                            ?>" name="weight" value="" required>
                            <b class="tooltip tooltip-bottom-right"><?php
                                echo lang(
                                        "Needed to enter Weight Value");
                                ?></b>
                        </label>
                    </section>
                    <section>
                        <div class="row">
                            <section class="col col-4">
                                <label class="input">
                                    <i class="icon-append fa fa-text-width"></i>
                                    <input type="text" placeholder="<?php echo lang("Width"); ?>" id="width" name="width" value="<?php echo (isset($user_data['width'])) ? $user_data['width'] : '' ?>" >
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="input">
                                    <i class="icon-append fa fa-text-height"></i>
                                    <input type="text" placeholder="<?php echo lang("Height"); ?>" id="height" name="height" value="<?php echo (isset($user_data['height'])) ? $user_data['height'] : '' ?>" >
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="input">
                                    <i class="icon-append fa fa-th"></i>
                                    <input type="text" placeholder="<?php echo lang("Length"); ?>" id="length" name="length" value="<?php echo (isset($user_data['length'])) ? $user_data['length'] : '' ?>" >
                                </label>
                            </section>
                        </div>
                    </section>
                    <section>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                            <textarea id="message" name="goods_description"  rows="4" placeholder="<?php
                            echo lang(
                                    "Goods Description");
                            ?>" required></textarea>
                        </label>
                    </section>
                    <section>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                            <textarea id="message" name="notes"  rows="4" placeholder="<?php
                            echo lang(
                                    "Notes");
                            ?>"></textarea>
                        </label>
                    </section>
                </section>

            </fieldset>

            <footer>
                <button class="btn-u" type="submit"><?php echo lang("Submit"); ?></button>
            </footer>
            <?php echo form_close(); ?>
            <!-- End Reg-Form -->
        </div>

    </div>

</div><!--/container-->
<!-- End Content Part -->

<script type="text/javascript" src="<?php echo site_url("webroot/autocomplete/js/jquery-1.9.1.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/autocomplete/js/jquery.autocomplete.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo site_url("webroot/autocomplete/js/awb_autocomplete.js"); ?>"></script>
