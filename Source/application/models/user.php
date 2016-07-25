<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class User extends CI_Model {

   function __construct()
   {
      parent::__construct();
   }

   public function valid_user($email, $password)
   {
      $login_data = array(
                     "email"    => $email,
                     "password" => $password,
                     "status"   => 'active',
      );

      $query = $this->db->get_where("accounts", $login_data);
      return $query->row();
   }

   public function get_allUsers()
   {
      $this->db->order_by("name");
      $query = $this->db->get_where("users", array(
                     "status" => 'active'));
      return $query->result();
   }

   public function get_userInfo($user_id)
   {
      $query = $this->db->get_where("accounts", array(
                     "id" => $user_id));
      $result = $query->row();
      return $result;
   }

   public function save_userInfo($data)
   {
      $query = $this->db->get_where("accounts", array(
                     "email" => $data['email']));
      if($query->row())
      {
         return -1;
      }
      $this->db->set("created", 'NOW()', FALSE);
      $this->db->insert("accounts", $data);
      return $this->db->insert_id();
   }

   public function edit_userInfo($user_id, $data)
   {
      $this->db->where("id", $user_id);
      $this->db->update("accounts", $data);
   }

   public function updateUser_byEmail($email, $data)
   {

      $this->db->where("email", $email);
      $this->db->update("accounts", $data);
   }

   public function delete_user($user_id)
   {
      $this->db->where("id", $user_id);
      $this->db->delete("users");
      $result = $this->db->_error_number();

      return $result;
   }

   public function update_user_status($ids, $action)
   {
      if(!is_array($ids))
      {
         $ids = array(
                        $ids);
      }
      foreach($ids as $id)
      {
         $this->db->where("id", $id);
         $this->db->update("users", array(
                        'status' => $action));
      }
   }

   public function get_localAddress($user_id)
   {
      $query = $this->db->get_where("shopper_local_address", array(
                     "users_id" => $user_id));
      return $query->row();
   }

   public function save_localAddress($insert_data)
   {
      $this->db->insert("shopper_local_address", $insert_data);
   }

   public function update_localAddress($user_id, $data)
   {
      $this->db->where("users_id", $user_id);
      $this->db->update("shopper_local_address", $data);
   }

   public function get_confirmationUser_byEmail($email)
   {
      $query = $this->db->get_where("accounts_confirmation", array(
                     "email" => $email));
      return $query->row();
   }

   public function get_confirmationUser_byCode($confirm_code)
   {
      $query = $this->db->get_where("accounts_confirmation", array(
                     "confirmation_code" => $confirm_code));
      return $query->row();
   }

   public function delete_userConfirmation_info($confirm_code)
   {
      $this->db->where("confirmation_code", $confirm_code);
      $this->db->delete("accounts_confirmation");
   }

   public function save_confirmation_user($data)
   {
      $this->db->insert("accounts_confirmation", $data);
   }

   public function save_confirmationPassword($data)
   {
      $this->db->set("created", "NOW()", FALSE);
      $this->db->insert("password_confirmation", $data);
   }

   public function get_tempForget_password($confirm_code)
   {
      $query = $this->db->get_where("password_confirmation", array(
                     "confirmation_code" => $confirm_code));
      return $query->row();
   }

   public function get_Forget_password($account_id)
   {
      $query = $this->db->get_where("password_confirmation", array(
                     "account_id" => $account_id));
      return $query->row();
   }

   public function delete_confirmationPasswordId($account_id)
   {
      $this->db->where("account_id", $account_id);
      $this->db->delete("password_confirmation");
   }

   public function delete_confirmationPassword($confirm_code)
   {
      $this->db->where("confirmation_code", $confirm_code);
      $this->db->delete("password_confirmation");
   }

   public function get_userInfo_byEmail($email = false, $name = false, $mobile = false)
   {
      if($email)
      {
         $this->db->where('email', $email);
      }
      else if($name)
      {
         $this->db->like('first_name', $name);
         $this->db->or_like('last_name', $name);
      }
      else if($mobile)
      {
         $this->db->where('phone', $mobile);
      }


      $query = $this->db->get("accounts");
      return $query->row();
   }

}

/* End of file user.php */

/* Location: ./application/models/user.php */



