<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class Home extends CI_Controller {

   public function __construct()
   {
      parent::__construct();
      $this->load->model("app");
   }

   public function index()
   {
      $info['welcome'] = $this->app->getCMS(3);
      $info['vision'] = $this->app->getCMS(4);
      $info['mission'] = $this->app->getCMS(5);
      $info['core'] = $this->app->getCMS(6);
      $info['clients'] = $this->app->getClients();
      $info['shots'] = $this->app->getShots();
      $info['slider'] = $this->app->getSlider();
      $info['panels'] = $this->app->getHomePanels();
      $this->loadview->view("home", $info);
   }

   public function emailtemplate()
   {
      $this->load->view("emails/email_corporate_aqua");
   }

   public function about_us()
   {
      $info['cms'] = $this->app->getCMS(1);
      $info['teams'] = $this->app->getTeams();
      $this->loadview->view("about_us", $info);
   }

   public function promotions()
   {
      $info['cms'] = $this->app->getCMS(2);
      $this->loadview->view("cms", $info);
   }

   public function cms($id)
   {
      $info['cms'] = $this->app->getCMS($id);
      if(!$info['cms'])
         show_404();
      $this->loadview->view("cms", $info);
   }

   public function contact_us()
   {
      if($this->input->post())
      {
// Begin the session
         session_start();

// Set the session contents
         $captch = $_SESSION['captcha_id'];
         if($captch != $this->input->post("captcha", TRUE))
         {
            error(lang('"CAPTCHA_error"'), TRUE);
            redirect("home/contact_us");
         }
         else
         {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|email');
            $this->form_validation->set_rules('phone', 'Mobile', 'trim|required|number');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            $this->form_validation->set_rules('department', 'Department', 'trim|required');


            if($this->form_validation->run() == FALSE)
            {
               error(validation_errors());
               redirect("home/contact_us");
            }
            else
            {
               $data = array(
                              "name"    => $this->input->post("name", TRUE),
                              "email"   => $this->input->post("email", TRUE),
                              "phone"   => $this->input->post("phone", TRUE),
                              "subject" => $this->input->post("subject", TRUE),
                              "message" => $this->input->post("message", TRUE),
               );
               $this->app->save_contact_inquiries($data);
               success("submit_inquiry");

               /**
                * Send Email
                */
               $email = $this->app->get_contactEmail($this->input->post("department", TRUE));
               $this->load->helper('send_email');
               $message = $this->load->view("emails/contact_inquiry", $data, TRUE);
               $send = sendEmail($email, lang('egyptexpress.com Support Center (New Contact Inquiry)'), $message, $data['email']);

               redirect('home/contact_us');
            }
         }
      }
      $info['departments'] = $this->app->getDepartments();
      $info['branches'] = $this->app->getBranches();
      $this->loadview->view("contact_us", $info);
   }

   public function help()
   {
      $info['faqs'] = $this->app->getFAQ();
      $this->loadview->view("faq", $info);
   }

   public function careers()
   {
      $info['jobs'] = $this->app->getJobs();
      $this->loadview->view("jobs", $info);
   }

   public function jobView($id)
   {
      $info['job'] = $this->app->getJobs($id);
      if(!$info['job'])
         show_404();
      $this->loadview->view("job_view", $info);
   }

   public function jobApply()
   {
      $this->load->library('form_validation');
      $this->form_validation->set_rules('name', 'Name', 'trim|required');
      $this->form_validation->set_rules('email', 'Email', 'trim|required|email');
      $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');

      $job_id = $this->input->post("job_id", TRUE);
      $name = trim($this->input->post('name', TRUE));
      $email = trim($this->input->post('email', TRUE));
      $mobile = trim($this->input->post('mobile', TRUE));

      if($this->form_validation->run() == FALSE)
      {
         error(validation_errors(), TRUE);
         redirect('home/jobView/' . $job_id);
      }

      /* upload commercial register */
      if($_FILES['cv']['name'])
      {
         $config['upload_path'] = './webroot/uploads/jobs/';
         $config['allowed_types'] = 'pdf|doc|docx';
         $config['max_size'] = '5120';
         $this->load->library('upload', $config);
         if(!$this->upload->do_upload('cv'))
         {
            error($this->upload->display_errors(), TRUE);
            redirect('home/jobView/' . $job_id);
         }
         else
         {
            $imagedata = $this->upload->data();
            $cv = $imagedata['file_name'];
         }
      }
      else
      {
         error('must_upload_cv');
         redirect('home/jobView/' . $job_id);
      }

      $job = $this->app->getJobs($job_id);
      if(!$job)
         show_404();

      $apply_data = array(
                     'job_id' => $job_id,
                     'name'   => $name,
                     'email'  => $email,
                     'mobile' => $mobile,
                     'cv'     => $cv,
      );

      $applied = $this->app->jobApply($apply_data);
      if($applied === -1)
      {
         error('applied_before');
         unlink('./webroot/uploads/jobs/' . $cv);
      }
      elseif($applied)
      {
         success('applied_successfully');
      }
      else
      {
         error('apply_failed');
      }
      redirect('home/jobView/' . $job_id);
   }

   public function services()
   {
      $data["international"] = $this->app->getServices("international");
      $data["domestic"] = $this->app->getServices("domestic");
      $this->loadview->view("services", $data);
   }

   public function setcookie()
   {
      $this->load->helper('cookie');
      $cookie = array(
                     'name'   => 'firstTime',
                     'value'  => 'once',
                     'expire' => '0',
      );
      set_cookie($cookie);
   }

   public function router()
   {
      $this->load->library("auth");
      $this->auth->router();
   }

}

/* End of file home.php */

/* Location: ./application/controllers/home.php */
