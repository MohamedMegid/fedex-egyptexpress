<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

if(!function_exists('hcurrent'))
{

   function hcurrent($method = array(), $segment = 2)
   {
      $CI = & get_instance();
      if(!is_array($method))
      {
         $method = array($method);
      }

      if(in_array($CI->uri->segment($segment), $method))
      {
         return "active";
      }
      else
      {
         return;
      }
   }

}


// ------------------------------------------------------------------------

/* End of file menu_highlight_helper.php */
/* Location: ./application/heleprs/menu_highlight_helper.php */