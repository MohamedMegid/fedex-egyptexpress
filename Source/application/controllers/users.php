<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class Users extends CI_Controller {

   public function __construct()
   {
      parent::__construct();
      $this->load->model('user');
      $this->load->library('auth');
   }

   public function login()
   {
      if(!$this->auth->isLogin())
         $this->loadview->view('users/login');
      else
         redirect();
   }

   public function validate_login()
   {
      $this->load->library('form_validation');
      $this->form_validation->set_rules('email', 'Email', 'trim|required|email');
      $this->form_validation->set_rules('password', 'Password', 'trim|required');


      if($this->form_validation->run() == FALSE)
      {
         error(validation_errors(), TRUE);
         $this->loadview->view('login_form');
      }
      else
      {
         $email = trim($this->input->post('email', TRUE));
         $password = trim($this->input->post('password', TRUE));
         $password = sha1($password . $this->config->item('encryption_key'));
         $user_info = $this->user->valid_user($email, $password);
         if($user_info)
         {
            /*
             * missing check the user status of expiration on enter and all function
             */
            $this->auth->login($user_info);
            $this->auth->navigator();
         }
         else
         {
            error("credentials_error");
            redirect("users/login");
         }
      }
   }

   public function logout()
   {
      $this->auth->logout();
      redirect("users/login");
   }

   public function register()
   {
      /*
       * load the registeration form
       */
      if(!$this->auth->isLogin())
         $this->loadview->view("users/registration");
      else
         redirect($_SERVER['HTTP_REFERER']);
   }

   public function do_register()
   {
      /*
       * get the posted data
       * validate posted data
       * generated sha1(uniqued ) for confirmation code
       * insert the data to confirmation table
       * send email to user to confirm
       * echo confirm the email message
       */
      if($this->input->post())
      {
         $this->load->library('form_validation');
         $this->form_validation->set_rules('first_name', 'first Name', 'trim|required');
         $this->form_validation->set_rules('last_name', 'last Name', 'trim|required');
         $this->form_validation->set_rules('address', 'Address', 'trim|required');
         $this->form_validation->set_rules('company', 'Company', 'trim|required');
         $this->form_validation->set_rules('job_title', 'Job title', 'trim|required');
         $this->form_validation->set_rules('ship_monthly', 'ship monthly', 'trim|required');
         $this->form_validation->set_rules('email', 'Email', 'trim|required|email');
         $this->form_validation->set_rules('password', 'Password', 'trim|required');
         $this->form_validation->set_rules('repassword', 'Re Password', 'trim|required|matches[password]');
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
         $email = $this->input->post("email", TRUE);
         $password = $this->input->post("password", TRUE);
         $password = sha1($password . $this->config->item('encryption_key'));
         $this->load->helper('string');
         $confirmation_code = random_string('unique');

         $data = array(
                        "first_name"        => $name,
                        "last_name"         => $last_name,
                        "company"           => $company,
                        "job_title"         => $job_title,
                        "phone"             => $phone,
                        "address"           => $address,
                        "ship_monthly"      => $ship_monthly,
                        "email"             => $email,
                        "password"          => $password,
                        "confirmation_code" => $confirmation_code,
                        "status"            => "pending"
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

         if($this->user->get_confirmationUser_byEmail($email))
         {
            error("register_waiting_confirmation");
            redirect("users/login");
         }
         elseif($this->user->get_userInfo_byEmail($email))
         {
            error("registered_before");
            redirect("users/login");
         }
         $this->user->save_confirmation_user($data);

         $message = $this->load->view("emails/confirm_register", $data, TRUE);


         /**
          * send Email
          */
         var_dump($email);
         die;
         $this->load->helper('send_email');
         $send = sendEmail($email, lang('Egyptexpress.com.eg Support Center (Confirm Registration)'), $message);
         if($send)
         {
            unset($data['confirmation_code']);
            $this->user->save_userInfo($data);

            success("register_success_waitting_confirmation");
         }
         else
         {
            error("registeration_wrong");
         }
         redirect("users/login");
      }
   }

   /**
    *
    * @param varchar $confirm_code
    */
   public function confirm_register($confirm_code)
   {
      /*
       * valide the confirm code
       * if invalid  echo error message
       * if valid
       * insert into users table
       * delete from users confirmation temp table
       * log user in
       * redirect to subscription form
       */

      $user_confirm_info = $this->user->get_confirmationUser_byCode($confirm_code);

      if(!$user_confirm_info)
      {
         error("invalid_confirmation");
         redirect("users/register");
      }
      else
      {

         $update = $this->user->updateUser_byEmail($user_confirm_info->email, array(
                        'status' => "active"));

         $this->user->delete_userConfirmation_info($confirm_code);
         /**
          * send Email to admin
          */
         $this->load->helper('send_email');
         $this->load->model('app');

         $message = $this->load->view("emails/new_account_notification", $user_confirm_info, TRUE);
         $new_account_notification = $this->app->get_emailAddress(1);
         $send = sendEmail($new_account_notification, lang('Egyptexpress.com.eg Support Center (New Account Registered)'), $message);

         success("registered_successfully");
         redirect("users/login");
      }
   }

   public function resetPassword($if_forget_password = false)
   {
      if(!$if_forget_password)
      {
         $this->auth->forceLogin();
      }

      if($this->input->post())
      {
         $this->load->library('form_validation');

         if($this->input->post("confirmation_code", TRUE))
         {
            $this->form_validation->set_rules('confirmation_code', 'Confirmation code', 'trim|required');
         }
         else
         {
            $old_password = $this->input->post("old_password", TRUE);
            $old_password = sha1($old_password . $this->config->item('encryption_key'));
            $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
         }

         $this->form_validation->set_rules('password', 'Password', 'trim|required');
         $this->form_validation->set_rules('repassword', 'Re-Password', 'trim|required|matches[password]');
         if($this->form_validation->run() == FALSE)
         {
            error(validation_errors());
            if($if_forget_password)
            {
               redirect("users/resetPassword");
            }
            else
            {
               redirect("account/profile_settings");
            }
         }

         if(!$if_forget_password)
         {
            $user_info = $this->user->get_userInfo($this->auth->user("id"));

            $new_password = $this->input->post("password", TRUE);
            if(strcmp($user_info->password, $old_password) == 0)
            {
               $new_password = sha1($new_password . $this->config->item('encryption_key'));
               $update_data = array(
                              "password" => $new_password);
               $this->user->edit_userInfo($this
                       ->auth->user("id"), $update_data);
               success("pass_updated_successfully");
               redirect("account/profile_settings");
            }
            else
            {
               error("invalid_old_password");
               redirect("account/profile_settings");
            }
         }
         else
         {
            $new_password = $this->input->post("password", TRUE);
            $new_password = sha1($new_password . $this->config->item('encryption_key'));
            $confirmation_code = $this->input->post("confirmation_code", TRUE);

            $forget_password_user_info = $this->user->get_tempForget_password(trim($confirmation_code));
            if($forget_password_user_info)
            {
               $user_info = $this->user->get_userInfo($forget_password_user_info->account_id);

               $update_data = array(
                              "password" => $new_password);
               $this->user->edit_userInfo($forget_password_user_info->account_id, $update_data);
               $this->user->delete_confirmationPassword($confirmation_code);
               success("pass_updated_successfully");
               redirect("users/login");
            }
         }
      }

      $this->loadview->view("users/reset_password");
   }

   public function forgetPassword()
   {
      /*
       * if not post load foreget form
       * get the posted email
       * generate temp uniqued confirmtion code
       * save in temp table
       * send the user email to confirm resette the password
       * echo check youe email message
       */
      if($this->auth->isLogin())
      {
         redirect("users/resetPassword");
      }

      if($this->input->post())
      {
         $this->load->library('form_validation');
         $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

         if($this->form_validation->run() == FALSE)
         {
            error(validation_errors());
            redirect("users/forgetPassword");
         }

         $email = $this->input->post("email", TRUE);
         $user_info = $this->user->get_userInfo_byEmail($email);

         if($user_info)
         {
            $this->load->helper('string');
            $confirm_code = random_string('unique');
            $data = array(
                           "account_id"        => $user_info->id,
                           "confirmation_code" => $confirm_code,
            );
            $forgetbefore = $this->user->get_Forget_password($user_info->id);
            if($forgetbefore)
            {
               $this->user->delete_confirmationPasswordId($user_info->id);
            }

            $this->user->save_confirmationPassword($data);

            /**
             * Send Email
             */
            $this->load->helper('send_email');
            $confirmation_data['confirmation_code'] = $confirm_code;
            $confirmation_data['name'] = $user_info->name;
            $message = $this->load->view("emails/forget_password", $confirmation_data, TRUE);
            $send = sendEmail($email, lang('egyptexpress.com Support Center (Reset Password)'), $message);
            if($send)
            {
               success("reset_pass_email_error");
            }
            else
            {
               print_r("not sended");
               error("reset_pass_wrong");
            }
            redirect('users/login');
         }
         else
         {
            error("invalid_email");
            redirect("users/forgetPassword");
         }
      }
      else
      {
         $this->loadview->view("users/forget_password");
      }
   }

   /**
    *
    * @param varchar $confirm_code
    */
   public function confirm_forgetPassword($confirm_code = FALSE)
   {
      /*
       * check the confirm code
       * get the user old password
       * if valid
       * log user in
       * go to reset password (TRUE)
       */
      if($confirm_code)
      {
         $forget_password_user_info = $this->user->get_tempForget_password(trim($confirm_code));
         if($forget_password_user_info)
         {
            $user_info = $this->user->get_userInfo($forget_password_user_info->account_id);
            if($user_info)
            {
               $data['confirmation_code'] = $confirm_code;
//               $this->auth->login($user_info);
//               $this->user->delete_confirmationPassword($confirm_code);
               $this->loadview->view("users/reset_password", $data); // if old password set put it in hidden variable
            }
         }
         else
         {
            error("invalid_confirmation");
            redirect("users/login");
         }
      }
      else
      {
         error("invalid_confirmation");
         redirect("users/login");
      }
   }

}

/* End of file users.php */

/* Location: ./application/controllers/users.php */