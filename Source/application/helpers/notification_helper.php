<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

/**
 *  just echo this on master page $this->session->flashdata('notification');
 * also incloyde the following js files
 * <script type="text/javascript">
 * function notifyMessage(type,msg){
  showNotification({
  type : type,
  message: msg,
  autoClose: true,
  duration: 5
  });
  }

  </script>
 * <script src="<?php echo site_url('webroot/js/jquery_notification_v.1.js'); ?>"></script>
 */
if(!function_exists('error'))
{

   function error($message = NULL, $ignor_lang = false)
   {

      if(!$ignor_lang)
         $message = lang($message);
      $CI = & get_instance();
      $notify = " <script> ";
      $notify .= " notifyMessage('error', '$message')";
      $notify .= " </script>";
      $CI->session->set_flashdata('notification', $notify);
   }

}

if(!function_exists('success'))
{

   function success($message = NULL, $ignor_lang = false)
   {
      if(!$ignor_lang)
         $message = lang($message);
      $CI = & get_instance();
      $notify = "<script> ";
      $notify .= " notifyMessage('success', ' $message')";
      $notify .= " </script>";
      $CI->session->set_flashdata('notification', $notify);
   }

}

// ------------------------------------------------------------------------

/* End of file notification_helper.php */
/* Location: ./system/heleprs/notification_helper.php */