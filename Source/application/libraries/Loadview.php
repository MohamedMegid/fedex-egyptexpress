<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class Loadview {

   var $CI;

   /**
    * Constructor
    */
   public function __construct()
   {
      $this->CI = & get_instance();
   }

   /**
    *
    * @param string $view_name
    * @param array $info
    */
   public function view($view_name, $info = NULL)
   {

      $this->CI->load->view("header", $info);
      $this->CI->load->view($view_name, $info);
      $this->CI->load->view("footer");


//      $this->CI->output->enable_profiler(TRUE);
   }

}

// END CI_LoadView class

/* End of file Loadview.php */
/* Location: ./system/libraries/Loadview.php */