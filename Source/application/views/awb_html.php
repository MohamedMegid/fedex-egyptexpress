<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="ltr">
    <head>
        <title>EGYPT EXPRESS AWB# 984654</title>
        <meta name="description" content="EGYPT EXPRESS AWB# 0127309" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" rel="stylesheet" href="<?php echo site_url('webroot/css/api_awb.css'); ?>" />
    </head>
    <body>

        <div class="awb">

            <div class="topbar">

                <span>AIR WAY BILL</span>

                <span class="date"> Date: <?php echo date('d / m / Y', strtotime($AWB->bill_date)); ?></span>

            </div><!-- top bar -->


            <div class="logobar">


                <div class="logo">
                    Egypt Express
                    <div class="clear" style="margin-top: 5px;"></div>
                    <span>Plot 2, Block 1258w Masaken Sheraton, Heliopolis - Cairo, Egypt.</span>
                </div>

                <div class="barcode">
                    <img src="data:image/jpg;base64,<?= $AWB->barcode ?>" >

                    <div class="clear"></div>
                    <span>AWB# <?php echo $AWB->AWBNO; ?></span>
                </div>


            </div><!-- logobar -->


            <div class="centerbar">

                <div class="left">
                    <div class="item">
                        <span>Account #:</span>
                        <div class="clear"></div>
                        <?php echo $AWB->fedex_account_no; ?>
                    </div>
                    <div class="item">
                        <span>Weight:</span>
                        <div class="clear"></div>
                        <?php echo $AWB->weight; ?> KG
                    </div>
                    <div class="item">
                        <span>Shipper Reference:</span>
                        <div class="clear"></div>
                        <?php echo $AWB->name; ?>

                    </div>

                    <div class="item">
                        <span>Dimension:</span>
                        <div class="clear"></div>
                        <?php
                        echo ($AWB->dimensions == '') ? lang('not defined') : str_replace('x', ' cm x ', $AWB->dimensions) . ' cm';
                        ?>
                    </div>
                </div>


                <div class="cod">
                    <span>Cash On Delivery Amount:</span>
                    <div class="clear"></div>
                    <?php echo ($AWB->COD_amount) ? $AWB->COD_amount : 0; ?> L.E.
                </div>

            </div><!--centerbar -->

            <div class="box firstbox">
                <h2>Shipper Info.</h2>

                <div class="boxitem">
                    <div class="label">Shipper:</div>
                    <div class="data"><?php echo $AWB->shipper_name; ?></div>
                </div>
                <div class="boxitem">
                    <div class="label">Name:</div>
                    <div class="data"><?php echo $AWB->shipper_name; ?></div>
                </div>
                <div class="boxitem">
                    <div class="label">Address:</div>
                    <div class="data"><?php echo $AWB->shipper_address1; ?> </div>
                </div>
                <div class="boxitem">
                    <div class="label">Phone:</div>
                    <div class="data"><?php echo $AWB->shipper_phone; ?></div>
                </div>


            </div>
            <div class="box secondbox">
                <h2>Recipient Info.</h2>


                <div class="boxitem">
                    <div class="label">Name:</div>
                    <div class="data"><?php echo $AWB->recipient_name; ?></div>
                </div>
                <div class="boxitem">
                    <div class="label">Address:</div>
                    <div class="data"><?php echo $AWB->recipient_address1; ?> <br /><?php echo (!empty($AWB->recipient_address2)) ? $AWB->recipient_address2 : ''; ?> </div>
                </div>
                <div class="boxitem">
                    <div class="label">City:</div>
                    <div class="data"><?php echo $AWB->recipient_city; ?></div>
                </div>
                <div class="boxitem">
                    <div class="label">Phone:</div>
                    <div class="data"><?php echo $AWB->recipient_phone; ?></div>
                </div>


            </div>
            <div class="box thirdbox">
                <h2>Goods Info.</h2>


                <div class="boxitem">
                    <div class="label">No. Of Pieces:</div>
                    <div class="data"><?php echo $AWB->no_of_pieces; ?></div>
                </div>
                <div class="boxitem">
                    <div class="label">Goods description:</div>
                    <div class="data"><?php echo $AWB->goods_description; ?></div>
                </div>


            </div>


        </div>

    </body>

</html>
