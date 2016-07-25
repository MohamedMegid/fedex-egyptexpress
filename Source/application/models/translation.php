<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class Translation extends CI_Model {

   function __construct()
   {
      parent::__construct();
   }

   public function save_translation($new_translation)
   {
      $query = $this->db->get_where("translation", array('id' => $new_translation['id']));
      $exist = $query->row();
      if(!$exist)
      {
         $this->db->insert("translation", $new_translation);
         $this->db->cache_delete_all();
      }
      else
      {
         $this->db->where("id", $exist->id);
         unset($new_translation['id']);
         $this->db->update("translation", $new_translation);
      }
   }

   public function get_translation($lang)
   {
//      $this->db->cache_on();
      $query = $this->db->get("translation");
//      $this->db->cache_off();
      $result = $query->result_array();
      $this->load->helper("functions");
      $lang_array = array_column($result, $lang, 'id');
      return $lang_array;
   }

}

/* End of file Translation.php */

/* Location: ./application/models/Translation.php */



