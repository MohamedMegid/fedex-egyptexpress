<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

if(!function_exists('sendEmail'))
{

   function sendEmail($to, $subject, $message, $reply_to = FALSE)
   {
      $CI = & get_instance();
      $CI->load->library('email');

      $CI->email->from('no-reply@egyptexpress.com.eg', 'egyptexpress.com.eg');
      $CI->email->to($to);

      if($reply_to)
         $CI->email->reply_to($reply_to);

      $CI->email->subject($subject);
      $CI->email->message($message);

//      $CI->email->set_newline("\r\n");
      return $CI->email->send();


//      var_dump($CI->email->print_debugger());
//      die;
   }

}



/* End of file amazon_email_helper.php */
/* Location: ./system/heleprs/amazon_email_helper.php */