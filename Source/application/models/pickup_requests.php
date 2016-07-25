<?php
if(!defined('BASEPATH'))
   exit('No direct script access allowed');
class Pickup_requests extends CI_Model {
   function __construct()
   {
      parent::__construct();
   }
   public function save_shipers($shipper)
   {
   		$this->db->insert('shipper_details', $shipper);
   }
      public function save_consignee($consignee)
   {
   		$this->db->insert('shipper_details', $consignee);
   }

   public function save_awd_auto($awb_auto){
      $this->db->insert('awd_auto', $awb_auto);
   }
}