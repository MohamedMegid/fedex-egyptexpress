<!doctype html>
<html <?php if($this->lang->lang() == "en")
{
   echo "dir='ltr'";
}
else
{
   echo "dir='rtl'";
} ?> xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo lang("Registration Confirmation"); ?></title>

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
                                        <td  class="center" style="font-size: 16px; color: #303030; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 50px 0px; " >
                                            <a href=""><img src="<?php echo site_url("webroot/images/logo.png"); ?>" class="img img-responsive"/></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td   style="font-size: 16px; color: #303030; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 10px 0px; " >
<?php echo lang("Dear"); ?> <?php echo $first_name; ?>,
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" style="font-size: 12px; color: #687074; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 10px; " >
<?php echo lang("You are one step away before your egyptexpress.com.eg account is ready to go !"); ?><br/>
                                            <?php echo lang("Please click on the following link to activate your e-mail address."); ?>

                                            <br/><br/>
<?php echo anchor("users/confirm_register/" . $confirmation_code, site_url("users/confirm_register/" . $confirmation_code)); ?>
                                            <br/><br/>
<?php echo lang("click_or_copy"); ?>.

                                            <br/><br/>
<?php echo lang("Thank you for joining"); ?> egyptexpress.com.eg
                                            <br/><br/>
                                            <strong>egyptexpress.com.eg <?php echo lang("Team"); ?></strong>
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
                            <td width="100%" bgcolor="#a5d1da" class="center">
                                <table  border="0" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td valign="top" style="padding: 20px 10px " class="center">
                                            <a href="#"><img width="32" hight="32" src="<?php echo site_url("webroot/images/icon_facebook.png"); ?>"></a>
                                        </td>
                                        <td valign="top" style="padding: 20px 10px " class="center">
                                            <a href="#"><img width="32" hight="32" src="<?php echo site_url("webroot/images/icon_twitter.png"); ?>"></a>
                                        </td>
                                    </tr>
                                </table>
                                <table  border="0" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td  class="center" style="font-size: 16px; color: #ffffff; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 0px 10px; ">
<?php echo lang("Stay Involved With Us"); ?>
                                        </td>
                                    </tr>
                                    <td  class="center" style="font-size: 12px; color: #ffffff; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 10px; " >
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
                <td  bgcolor="#ffffff" class="center" style="font-size: 12px; color: #687074; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 50px 0px 50px; " >
<?php echo lang("email_footer_copyright") ?>
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