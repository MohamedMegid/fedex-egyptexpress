<!doctype html>
<html <?php
if($this->lang->lang() == "en")
{
   echo "dir='ltr'";
}
else
{
   echo "dir='rtl'";
}
?> xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Responsive Email Template</title>

        <style type="text/css">
            .ReadMsgBody {width: 100%; background-color: #ffffff;}
            .ExternalClass {width: 100%; background-color: #ffffff;}
            body     {width: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;font-family: Arial, Helvetica, sans-serif}
            table {border-collapse: collapse;}

            @media only screen and (max-width: 640px)  {
                body[yahoo] .deviceWidth {width:440px!important; padding:0;}
                body[yahoo] .center {text-align: center!important;}
            }

            @media only screen and (max-width: 479px) {
                body[yahoo] .deviceWidth {width:280px!important; padding:0;}
                body[yahoo] .center {text-align: center!important;}
            }
        </style>
    </head>
    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" style="font-family: Arial, Helvetica, sans-serif">

        <!-- Wrapper -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td width="100%" valign="top" bgcolor="#ffffff" style="padding-top:20px">

                    <!--Start Three Blocks-->
                    <table width="700" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
                        <tr>
                            <td width="100%" bgcolor="#f7f7f7" >
                                <!-- Top  -->
                                <table width="100%"  border="0" cellpadding="0" cellspacing="0" align="center" >
                                    <tr>
                                        <td   style="font-size: 16px; color: #303030; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 50px 0px; " >
                                            <a href=""><img src="<?php echo site_url("webroot/images/logo.png"); ?>" class="img img-responsive"/></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td   style="font-size: 16px; color: #303030; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 10px 0px; " >
                                            <?php echo lang("Dear Admin"); ?> ,
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <h3><?php
                                                if(isset($update))
                                                {
                                                   ?>
                                                   <?php echo lang("A pickup request has been updated with the following information :"); ?>
                                                   <?php
                                                }
                                                else
                                                {
                                                   ?>
                                                   <?php echo lang("New pickup request has been inserted with the following information :"); ?>
                                                <?php } ?></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Order Number :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $EnqRefNo; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("From Time :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $from_time; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("To Time :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $to_time; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Contact Name :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $contact_name; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Account Type :"); ?> <span style="font-size:14px;color:#687074"> <?php echo ($account_type == "accountnumber") ? "Account Number" : "Cash"; ?></span></div>
                                        </td>
                                    </tr>
                                    <?php
                                    if($account_type == "accountnumber")
                                    {
                                       ?>
                                       <tr>
                                           <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                               <div style="font-size: 16px; color:#f46600"><?php echo lang("Account Number :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $account_number; ?></span></div>
                                           </td>
                                       </tr>
                                    <?php } ?>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Company :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $company; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Contact Phone :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $contact_phone; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Email :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $email; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Content :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $content; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Weight :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $weight . "Kgms"; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Source Pickup Address :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $source_pickup_address; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Source Pickup City :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $source_pickup_city; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Source Governorate :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $source_governorate; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Destination Pickup Address :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $destination_pickup_address; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Destination Pickup City :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $destination_pickup_city; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Destination Governorate :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $destination_governorate; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("No. of Pieces :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $no_of_pieces; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Pickup Date :"); ?> <span style="font-size:14px;color:#687074"> <?php echo $pickup_date; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 12px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                            <div style="font-size: 16px; color:#f46600"><?php echo lang("Product Type :"); ?> <span style="font-size:14px;color:#687074"><?php echo $product_type; ?></span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"  style="font-size: 13px; color: #687074; font-weight: bold;  font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:20px 10px; " >
                                            <br/>
                                            <strong>egyptexpress.com</strong>
                                        </td>
                                    </tr>
                                </table><!--End Top-->
                            </td>
                        </tr>
                    </table>
                    <!--End Three Blocks -->

                    <!--Start Weekly Prize-->
                    <table width="700" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
                        <tr>
                            <td width="100%" bgcolor="#a5d1da" >
                                <table  border="0" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td valign="top" style="padding: 20px 10px " >
                                            <a href="#"><img width="32" hight="32" src="<?php echo site_url("webroot/images/icon_facebook.png"); ?>"></a>
                                        </td>
                                        <td valign="top" style="padding: 20px 10px " >
                                            <a href="#"><img width="32" hight="32" src="<?php echo site_url("webroot/images/icon_twitter.png"); ?>"></a>
                                        </td>
                                    </tr>
                                </table>
                                <table  border="0" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td   style="font-size: 16px; color: #ffffff; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 0px 10px; ">
                                            <?php echo lang("Stay Involved With Us"); ?>
                                        </td>
                                    </tr>
                                    <td   style="font-size: 12px; color: #ffffff; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding:0px 10px; " >
                                        <?php echo lang("email_footer"); ?>
                                    </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--Weekly Prize-->

        <!-- Footer -->
        <table width="700"  border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth"  >
            <tr>
                <td  bgcolor="#ffffff"  style="font-size: 12px; color: #687074; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 50px 0px 50px; " >
                    <?php echo lang("email_footer_copyright")?>
                </td>
            </tr>
        </table>
        <!--End Footer-->
    </td>
</tr>
</table>
<!-- End Wrapper -->
</body>
</html>
