<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class App extends CI_Model {

   public $requests_counter = 0;

   function __construct()
   {
      parent::__construct();
   }

   public function getJobs($id = FALSE)
   {
      if($id)
      {
         $this->db->where("id", $id);
      }
      $this->db->order_by("id", 'desc');
      $query = $this->db->get("jobs");

      if($id)
         return $query->row();
      else
         return $query->result();
   }

   public function jobApply($data)
   {

      $applied_before = $this->db->get_where("job_candidates", array('job_id' => $data['job_id'],
                     'email'  => $data['email']));
      $applied_before = $applied_before->row();
      if($applied_before)
         return -1;


      $apply = $this->db->insert("job_candidates", $data);
      if($apply)
      {
         $this->db->set("applied", "applied + 1", FALSE);
         $this->db->where("id", $data['job_id']);
         $this->db->update("jobs");
      }
      return $apply;
   }

   public function getFAQ()
   {
      $query = $this->db->get("faq");
      return $query->result();
   }

   public function save_contact_inquiries($data)
   {
      $this->db->insert("contact_inquiries", $data);
   }

   public function add_pickup_request($data)
   {
      $this->db->insert("pickup_requests", $data);
      return $this->db->insert_id();
   }

   public function get_pickup_request($id)
   {
      $this->db->where("id", $id);
      $this->db->where("account_id", $this->auth->user("id"));
      $query = $this->db->get("pickup_requests");
      return $query->row();
   }

   public function get_my_pickup_request($limit = 1, $start_paination = 0)
   {
      $q = $this->db->query("SELECT COUNT(*) as counter FROM pickup_requests WHERE account_id = " . $this->auth->user('id') . " AND status != 'cancelled'");
      $this->requests_counter = $q->row()->counter;
      $this->db->where("account_id", $this->auth->user("id"));
      $this->db->where("status !=", "cancelled");
      $this->db->order_by("id", "DESC");
      $query = $this->db->get("pickup_requests", $limit, $start_paination);
      return $query->result();
   }

   public function edit_pickup_request($id, $data)
   {
      $this->db->where("id", $id);
      $this->db->where("account_id", $this->auth->user("id"));
      $query = $this->db->update("pickup_requests", $data);
      return $this->db->affected_rows();
   }

   public function getCMS($id)
   {
      $this->db->where("id", $id);
      $query = $this->db->get("cms");
      return $query->row();
   }

   public function getTeams()
   {
      $query = $this->db->get("our_teams");
      return $query->result();
   }

   public function getClients()
   {
      $query = $this->db->get("our_clients");
      return $query->result();
   }

   public function getShots()
   {
      $query = $this->db->get("latest_shots");
      return $query->result();
   }

   public function getSlider()
   {
      $query = $this->db->get("slider");
      return $query->result();
   }

   public function getHomePanels()
   {
      $query = $this->db->get("home_page_panels");
      return $query->result();
   }

   public function getBranches()
   {
      $query = $this->db->get("branches");
      return $query->result();
   }

   public function getDepartments()
   {
      $query = $this->db->get("contactus_departments");
      return $query->result();
   }

   public function getServices($type)
   {
      $this->db->where("type", $type);
      $query = $this->db->get("services");
      return $query->result();
   }

   public function getIntegraStatus()
   {
      $query = $this->db->get("integra_status");
      return $query->result();
   }

   public function get_emailAddress($id)
   {
      $this->db->where("id", $id);
      $query = $this->db->get("notification_emails");
      $result = $query->row();
      return $result->emails;
   }

   public function get_contactEmail($id = 1)
   {
      $this->db->where("id", $id);
      $query = $this->db->get("contactus_departments");
      $result = $query->row();
      return $result->emails;
   }

   public function get_cityZones($id = FALSE)
   {
      if($id)
         $this->db->where("id", $id);
      $city_order = lang_db('city');
      $this->db->order_by($city_order);
      $query = $this->db->get("zones");
      if($id)
         return $query->row();
      else
         return $query->result();
   }

   public function get_zoneCost($weight)
   {
      $this->db->where("weight", $weight);
      $query = $this->db->get("zones_prices");
      return $query->row_array();
   }

   public function saveAWBLog($data)
   {
      $this->db->insert("AWB_log", $data);
      return $this->db->insert_id();
   }

   public function updateAWBLog($id)
   {
      $data["status"] = 0;
      $this->db->where("AWB_no", $id);
      $this->db->update("AWB_log", $data);
   }

   public function getCities()
   {
      $query = $this->db->get("cities");
      return $query->result();
   }

   public function get_cityByCode($code)
   {
      $this->db->where("code", $code);
      $query = $this->db->get("cities");
      return $query->row()->name;
   }

}

/* End of file app.php */

/* Location: ./application/models/app.php */



