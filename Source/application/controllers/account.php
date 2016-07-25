<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class Account extends CI_Controller {

   public function __construct()
   {
      parent::__construct();
      // validate login
      if(!$this->auth->isLogin())
      {
         redirect();
      }
      $this->load->model("user");
   }

   public function profile()
   {
      
      if($this->session->userdata("AWBNo")){
         $data["AWBNo"] = $this->session->userdata("AWBNo");
         $this->session->unset_userdata('AWBNo');
      }
      $user_info = $this->user->get_userInfo($this->auth->user("id"));

      if($user_info->integra_account_id)
      {
         $this->load->model("integra_model");
         $data["user_shipments"] = $this->integra_model->getMyShipments($user_info->integra_account_id, 10);
      }
      else
      {
         $data["user_shipments"] = NULL;
      }
      $this->loadview->view("users/profile", $data);
   }
   public function profile_settings()
   {

      if($this->input->post())
      {
         $this->load->library('form_validation');
         $this->form_validation->set_rules('first_name', 'first Name', 'trim|required');
         $this->form_validation->set_rules('last_name', 'last Name', 'trim|required');
         $this->form_validation->set_rules('address', 'Address', 'trim|required');
         $this->form_validation->set_rules('company', 'Company', 'trim|required');
         $this->form_validation->set_rules('job_title', 'Job title', 'trim|required');
         $this->form_validation->set_rules('ship_monthly', 'ship monthly', 'trim|required');
         $this->form_validation->set_rules('phone', 'Phone', 'trim');

         if($this->form_validation->run() == FALSE)
         {
            error(validation_errors(), TRUE);
            redirect("users/register");
         }
         // get posted data
         $name = $this->input->post("first_name", TRUE);
         $last_name = $this->input->post("last_name", TRUE);
         $company = $this->input->post("company", TRUE);
         $job_title = $this->input->post("job_title", TRUE);
         $phone = $this->input->post("phone", TRUE);
         $address = $this->input->post("address", TRUE);
         $ship_monthly = $this->input->post("ship_monthly", TRUE);

         $data = array(
                        "first_name"   => $name,
                        "last_name"    => $last_name,
                        "company"      => $company,
                        "job_title"    => $job_title,
                        "phone"        => $phone,
                        "address"      => $address,
                        "ship_monthly" => $ship_monthly,
         );


         /* upload commercial register */
         if($_FILES['commercial_register']['name'])
         {
            $config['upload_path'] = './webroot/uploads/accounts/';
            $config['allowed_types'] = 'pdf|doc|docx|jpg|png|jpeg';
            $config['max_size'] = '10000';
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('commercial_register'))
            {
               error($this->upload->display_errors(), TRUE);
               redirect("users/register");
            }
            else
            {
               $imagedata = $this->upload->data();
               $data['commercial_register'] = $imagedata['file_name'];
            }
         }


         /* upload avatar */
         if($_FILES['avatar']['name'])
         {
            $config['upload_path'] = './webroot/uploads/accounts/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '8192';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('avatar'))
            {
               error($this->upload->display_errors(), TRUE);
               redirect("users/register");
            }
            else
            {
               $imagedata = $this->upload->data();
               $data['avatar'] = $imagedata['file_name'];
            }
         }

         $this->user->edit_userInfo($this->auth->user("id"), $data);
         $user_info = $this->user->get_userInfo($this->auth->user("id"));
         $this->auth->login($user_info);

         success("data_updates_successfully");
         redirect('account/profile_settings');
      }
      $this->loadview->view("users/profile_settings");
   }

}

/* End of file account.php */

/* Location: ./application/controllers/account.php */