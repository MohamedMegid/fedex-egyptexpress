<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class Shipment extends CI_Controller {

   public function __construct()
   {
      parent::__construct();
      $this->load->model("app");
   }

   public function pickupRequest($id = false)
   {
      // custom save autocomplete
      $user_id = '';
      if($this->auth->user("id")){
         $user_id = $this->auth->user("id");
      }
      
      $shipper = array(
         
         'user_id' => $user_id,
         'name' => $this->input->post('shipper_name'),
         'cperson' => $this->input->post('shipper_cperson'),
         'address1' => $this->input->post('shipper_address1'),
         'address2'=> $this->input->post('shipper_address2'),
         'tel' => $this->input->post('shipper_tel'),
         'fax' => $this->input->post('shipper_fax'),
         'mobile' => $this->input->post('shipper_mobile'),
         'city' => $this->input->post('shipper_city'),
         'type' => 1
      );
      $this->load->model('pickup_requests');
      if (!empty($this->input->post('shipper_name'))){
         $this->pickup_requests->save_shipers($shipper);
      }
      $consignee = array(
         
         'user_id' => $user_id,
         'name' => $this->input->post('consignee_name'),
         'cperson' => $this->input->post('consignee_cperson'),
         'address1' => $this->input->post('consignee_address1'),
         'address2'=> $this->input->post('consignee_address2'),
         'tel' => $this->input->post('consignee_tel'),
         'fax' => $this->input->post('consignee_fax'),
         'mobile' => $this->input->post('consignee_mobile'),
         'city' => $this->input->post('consignee_city'),
         'type' => 2
      );
      $this->load->model('pickup_requests');

      if (!empty($this->input->post('consignee_name'))){
         $this->pickup_requests->save_consignee($consignee);
      }
      
      // end saving autocomplete case
      if($id)
      {
         $this->auth->forceLogin();
      }
      if($this->input->post())
      {

         // get posted data
         $fh = $this->input->post('fh', TRUE);
         $fm = $this->input->post('fm', TRUE);
         $fap = $this->input->post('fap', TRUE);

         $th = $this->input->post('th', TRUE);
         $tm = $this->input->post('tm', TRUE);
         $tap = $this->input->post('tap', TRUE);


         $from_time = $fh . ":" . $fm . " " . $fap;

         $to_time = $th . ":" . $tm . " " . $tap;
         $account_type = $this->input->post('account_type', TRUE);
         if($account_type == "accountnumber")
         {
            $account_number = $this->input->post('account_number', TRUE);
         }

         $weight_type = $this->input->post('weight_type', TRUE);
         $weight = $this->input->post('weight', TRUE);



         if($weight_type == 1)
         {
            $weight = $weight / 1000;
         }

         $data = array(
                        "from_time"                  => $from_time,
                        "to_time"                    => $to_time,
                        "contact_name"               => $this->input->post("shipper_name", TRUE),
                        "account_type"               => $account_type,
                        "company"                    => 'Fedex',
                        "email"                      => 'admin@egyptexpress.com',
                        "content"                    => $this->input->post("spl_inst", TRUE),
                        "weight"                     => $weight,
                        "source_pickup_address"      => $this->input->post("shipper_address1", TRUE),
                        "source_pickup_city"         => $this->input->post("shipper_city", TRUE),
                        "source_governorate"         => $this->input->post("shipper_city", TRUE),
                        "destination_pickup_address" => $this->input->post("consignee_address1", TRUE),
                        "destination_pickup_city"    => $this->input->post("consignee_city", TRUE),
                        "destination_governorate"    => $this->input->post("consignee_city", TRUE),
                        "no_of_pieces"               => $this->input->post("no_of_pieces", TRUE),
                        "pickup_date"                => $this->input->post("pickup_date", TRUE),
                        "product_type"               => $this->input->post("product", TRUE),
                        "contact_phone"              => $this->input->post("shipper_tel", TRUE),
                        "status"                     => "pending"
         );

         if($account_type == "accountnumber")
         {
            $data["account_number"] = $account_number;
         }
         else
         {
            //526940067 -
            $data["account_number"] = 526940067;
         }


         $pickup_request_emails = $this->app->get_emailAddress(2);

         if($id)
         {
            $edit = $this->app->edit_pickup_request($id, $data);
            if($edit > 0)
            {
               /**
                * send Email
                */
               $this->load->helper('send_email');
               $data["update"] = "update";
               $message = $this->load->view("emails/pickup_request", $data, TRUE);

               $send = sendEmail($pickup_request_emails, lang('Egyptexpress.com.eg Support Center (Update Pickup Request)'), $message);
               success("pickuprequest_submitted_successfully");
            }
            else
            {
               error("error_data_insertion");
            }
            redirect("shipment/myRequests/");
         }
         else
         {
            $insertid = $this->app->add_pickup_request($data);
            if($insertid)
            {
               // save in integra
               if($fap == 'pm')
                  $fh = $fh + 12;
               $EnqTime = $fh . ":" . $fm;
               $EnqMast = array(
                              'EnqRefNo'   => 'web' . $insertid,
                              'EnqDate'    => date('d M Y', strtotime($data['pickup_date'])),
                              'EnqTime'    => $EnqTime,
                              'CustName'   => $data['contact_name'],
                              'ClientCode' => $data['account_number'],
               );

               $EnqBookingRequest = array(
                              'EnqRefNo'         => $EnqMast['EnqRefNo'],
                              'BookType'         => $data['product_type'],
                              'PCS'              => $data['no_of_pieces'],
                              'Weight'           => $data['weight'],
                              'LandMark'         => $this->input->post("LandMark", TRUE),
                              'BService'         => $this->input->post("service", TRUE),
                              'BOrigin'          => $this->input->post("origin", TRUE),
                              'BDest'            => $this->input->post("destination", TRUE),
                              'BpickupLoc'       => $this->input->post("pickup_location", TRUE),
                              'PickupDate1'      => $data['pickup_date'],
                              'PickUpTime1'      => $EnqMast['EnqTime'],
                              'PickupDate2'      => date('m/d/Y'),
                              'PickUpTime2'      => date('H:i'),
                              'BComments'        => $this->input->post("spl_inst", TRUE),
                              'SendersName'      => $this->input->post("shipper_name", TRUE),
                              'SendersAddress1'  => $this->input->post("shipper_address1", TRUE),
                              'SendersAddress2'  => $this->input->post("shipper_address2", TRUE),
                              'ReceiversName'    => $this->input->post("consignee_name", TRUE),
                              'RecieverAddress1' => $this->input->post("consignee_address1", TRUE),
                              'RecieverAddress2' => $this->input->post("consignee_address2", TRUE),
                              'SendersTel'       => $this->input->post("shipper_tel", TRUE),
                              'ReceiversTel'     => $this->input->post("consignee_tel", TRUE),
                              'SendersFax'       => $this->input->post("shipper_fax", TRUE),
                              'ReceiversFax'     => $this->input->post("consignee_fax", TRUE),
                              'SendersCPerson'   => $this->input->post("shipper_cperson", TRUE),
                              'ReceiversCPerson' => $this->input->post("consignee_cperson", TRUE),
                              'SendersMobile'    => $this->input->post("shipper_mobile", TRUE),
                              'SendersCity'      => $this->input->post("shipper_city", TRUE),
                              'ReceiversMobile'  => $this->input->post("consignee_mobile", TRUE),
                              'RecieverCity'     => $this->input->post("consignee_city", TRUE),
               );

               $this->load->model("integra_model");
               $result = $this->integra_model->save_pickupRequest($EnqMast, $EnqBookingRequest);
               /**
                * send Email
                */
               $data["EnqRefNo"] = $EnqMast['EnqRefNo'];
               $this->load->helper('send_email');
               $message = $this->load->view("emails/pickup_request", $data, TRUE);
               $send = sendEmail($pickup_request_emails, lang('Egyptexpress.com.eg Support Center (New Pickup Request)'), $message);
//               success("pickuprequest_submitted_successfully");
               redirect('shipment/pickupNumber/' . $EnqMast['EnqRefNo']);
            }
            else
            {
               error("error_data_insertion");
            }
            redirect("shipment/pickupRequest/");
         }
      }
      $info = NULL;
      if($id)
      {
         $info["request"] = $editone = $this->app->get_pickup_request($id);
         if(!$editone)
         {
            show_404();
         }
         if($info["request"]->status == "picked" || $info["request"]->status == "cancelled")
         {
            redirect("shipment/myRequests");
         }
      }
      $this->loadview->view("pickupnow", $info);
   }

//    public function pickupRequest($id = false)
//   {
//      if($id)
//      {
//         $this->auth->forceLogin();
//      }
//      if($this->input->post())
//      {
//         $this->load->library('form_validation');
//         $this->form_validation->set_rules('fh', 'from Hours', 'trim|required');
//         $this->form_validation->set_rules('fm', 'from Minuts', 'trim|required');
//         $this->form_validation->set_rules('fap', 'from AmPm', 'trim|required');
//         $this->form_validation->set_rules('th', 'from Hours', 'trim|required');
//         $this->form_validation->set_rules('tm', 'from Minuts', 'trim|required');
//         $this->form_validation->set_rules('tap', 'from AmPm', 'trim|required');
//         $this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required');
//         $this->form_validation->set_rules('account_type', 'Account Type', 'trim|required');
//         $this->form_validation->set_rules('company', 'Company Name', 'trim|required');
//         $this->form_validation->set_rules('contact_phone', 'Phone Number', 'trim|required');
//         $this->form_validation->set_rules('email', 'Email Address', 'trim|required|email');
//         $this->form_validation->set_rules('content', 'Content Text', 'trim|required');
//         $this->form_validation->set_rules('weight', 'Weight Value', 'trim|required');
//         $this->form_validation->set_rules('source_pickup_address', 'Source Pickup Address', 'trim|required');
//         $this->form_validation->set_rules('source_pickup_city', 'Source Pickup City', 'trim|required');
//         $this->form_validation->set_rules('source_governorate', 'Source Governorate Name', 'trim|required');
//         $this->form_validation->set_rules('destination_pickup_address', 'Destination Pickup Address', 'trim|required');
//         $this->form_validation->set_rules('destination_pickup_city', 'Destination Pickup City', 'trim|required');
//         $this->form_validation->set_rules('destination_governorate', 'Destination Governorate Name', 'trim|required');
//         $this->form_validation->set_rules('no_of_pieces', 'number Pieces', 'trim|required|integer');
//         $this->form_validation->set_rules('pickup_date', 'Pickup Date', 'trim|required');
//         $this->form_validation->set_rules('product_type', 'Product Type', 'trim|required');
//
//
//         if($this->form_validation->run() == FALSE)
//         {
//            error(validation_errors(), TRUE);
//            redirect("shipment/pickupRequest");
//         }
//         // get posted data
//         $fh = $this->input->post('fh', TRUE);
//         $fm = $this->input->post('fm', TRUE);
//         $fap = $this->input->post('fap', TRUE);
//
//         $th = $this->input->post('th', TRUE);
//         $tm = $this->input->post('tm', TRUE);
//         $tap = $this->input->post('tap', TRUE);
//
//
//         $from_time = $fh . ":" . $fm . " " . $fap;
//
//         $to_time = $th . ":" . $tm . " " . $tap;
//         $contact_name = $this->input->post('contact_name', TRUE);
//         $account_type = $this->input->post('account_type', TRUE);
//         if($account_type == "accountnumber")
//         {
//            $account_number = $this->input->post('account_number', TRUE);
//         }
//         $company = $this->input->post('company', TRUE);
//         $email = $this->input->post('email', TRUE);
//         $content = $this->input->post('content', TRUE);
//         $weight_type = $this->input->post('weight_type', TRUE);
//         $weight = $this->input->post('weight', TRUE);
//         $source_pickup_address = $this->input->post('source_pickup_address', TRUE);
//         $source_pickup_city = $this->input->post('source_pickup_city', TRUE);
//         $source_governorate = $this->input->post('source_governorate', TRUE);
//         $destination_pickup_address = $this->input->post('destination_pickup_address', TRUE);
//         $destination_pickup_city = $this->input->post('destination_pickup_city', TRUE);
//         $destination_governorate = $this->input->post('destination_governorate', TRUE);
//         $no_of_pieces = $this->input->post('no_of_pieces', TRUE);
//         $pickup_date = $this->input->post('pickup_date', TRUE);
//         $product_type = $this->input->post('product_type', TRUE);
//         $contact_phone = $this->input->post('contact_phone', TRUE);
//
//         if($weight_type == 1)
//         {
//            $weight = $weight / 1000;
//         }
//
//         $data = array(
//                        "from_time"                  => $from_time,
//                        "to_time"                    => $to_time,
//                        "contact_name"               => $contact_name,
//                        "account_type"               => $account_type,
//                        "company"                    => $company,
//                        "email"                      => $email,
//                        "content"                    => $content,
//                        "weight"                     => $weight,
//                        "source_pickup_address"      => $source_pickup_address,
//                        "source_pickup_city"         => $source_pickup_city,
//                        "source_governorate"         => $source_governorate,
//                        "destination_pickup_address" => $destination_pickup_address,
//                        "destination_pickup_city"    => $destination_pickup_city,
//                        "destination_governorate"    => $destination_governorate,
//                        "no_of_pieces"               => $no_of_pieces,
//                        "pickup_date"                => $pickup_date,
//                        "product_type"               => $product_type,
//                        "contact_phone"              => $contact_phone,
//                        "status"                     => "pending"
//         );
//         if($this->auth->isLogin())
//         {
//            $data["account_id"] = $this->auth->user("id");
//         }
//
//         if($account_type == "accountnumber")
//         {
//            $data["account_number"] = $account_number;
//         }
//         else
//         {
//            $data["account_number"] = 526940067;
//         }
//         $pickup_request_emails = $this->app->get_emailAddress(2);
//
//         if($id)
//         {
//            $edit = $this->app->edit_pickup_request($id, $data);
//            if($edit > 0)
//            {
//               /**
//                * send Email
//                */
//               $this->load->helper('send_email');
//               $data["update"] = "update";
//               $message = $this->load->view("emails/pickup_request", $data, TRUE);
//
//               $send = sendEmail($pickup_request_emails, lang('Egyptexpress.com.eg Support Center (Update Pickup Request)'), $message);
//               success("pickuprequest_submitted_successfully");
//            }
//            else
//            {
//               error("error_data_insertion");
//            }
//            redirect("shipment/myRequests/");
//         }
//         else
//         {
//            $insertid = $this->app->add_pickup_request($data);
//            if($insertid)
//            {
//               // save in integra
//               if($fap == 'pm')
//                  $fh = $fh = 12;
//               $EnqTime = $fh . ":" . $fm;
//               $EnqMast = array(
//                              'EnqRefNo'   => 'EE' . $insertid,
//                              'EnqDate'    => date('d M Y', strtotime($data['pickup_date'])),
//                              'EnqTime'    => $EnqTime,
//                              'CustName'   => $data['contact_name'],
//                              'ClientCode' => $data['account_number'],
//               );
//
//               $EnqBookingRequest = array(
//                              'EnqRefNo'         => $EnqMast['EnqRefNo'],
//                              'BookType'         => $data['product_type'],
//                              'PCS'              => $data['no_of_pieces'],
//                              'Weight'           => $data['weight'],
//                              'BOrigin'          => $data['source_pickup_city'],
//                              'BDest'            => $data['destination_pickup_city'],
//                              'PickupDate1'      => $data['pickup_date'],
//                              'PickUpTime1'      => $EnqMast['EnqTime'],
//                              'PickupDate2'      => date('d/m/Y'),
//                              'PickUpTime2'      => date('H:i'),
//                              'SendersName'      => $data['contact_name'],
//                              'SendersAddress1'  => $data['source_pickup_address'],
//                              'SendersAddress2'  => $data['source_governorate'],
//                              'RecieverAddress1' => $data['destination_pickup_address'],
//                              'RecieverAddress2' => $data['destination_governorate'],
//               );
//
//               $this->load->model("integra_model");
//               $result = $this->integra_model->save_pickupRequest($EnqMast, $EnqBookingRequest);
//               /**
//                * send Email
//                */
//               $this->load->helper('send_email');
//               $message = $this->load->view("emails/pickup_request", $data, TRUE);
//               $send = sendEmail($pickup_request_emails, lang('Egyptexpress.com.eg Support Center (New Pickup Request)'), $message);
////               success("pickuprequest_submitted_successfully");
//               redirect('shipment/pickupNumber/' . $EnqMast['EnqRefNo']);
//            }
//            else
//            {
//               error("error_data_insertion");
//            }
//            redirect("shipment/pickupRequest/");
//         }
//      }
//      $info = NULL;
//      if($id)
//      {
//         $info["request"] = $editone = $this->app->get_pickup_request($id);
//         if(!$editone)
//         {
//            show_404();
//         }
//         if($info["request"]->status == "picked" || $info["request"]->status == "cancelled")
//         {
//            redirect("shipment/myRequests");
//         }
//      }
//      $this->loadview->view("pickupnow", $info);
//   }

   public function pickupNumber($pnumber)
   {
      $number['puNo'] = $pnumber;
      $this->loadview->view("pickup_request_number", $number);
   }

   public function shipmentHistory()
   {
      $awb_nos = FALSE;
      if($this->input->post("type", TRUE) == 'single')
      {
         $awb_nos = array(trim($this->input->post("awb_no", TRUE)));
      }
      elseif($this->input->post("type", TRUE) == 'bulk')
      {
         if($_FILES['bulk_awb']['name'])
         {
            $config['upload_path'] = './webroot/uploads/excel/';
            $config['allowed_types'] = 'xlx|xlsx';
            $config['max_size'] = '5120';
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('bulk_awb'))
            {
               error($this->upload->display_errors(), TRUE);
               redirect($_SERVER['HTTP_REFERER']);
            }
            else
            {
               $filedata = $this->upload->data();
               $xlx_file = $filedata['file_name'];
               require_once(APPPATH . 'third_party/simplexlsx.class.php');
               $xlsx = new SimpleXLSX($config['upload_path'] . $xlx_file);
               foreach($xlsx->rows() as $key => $row)
               {
                  if($row)
                  {
                     /** Process addition here * */
                     $awb_nos[] = addslashes($row[0]);
                  }
               }
            }
         }
         else
         {
            error('must_upload_excel_file');
            redirect($_SERVER['HTTP_REFERER']);
         }
      }

      if($awb_nos)
      {
         $lastScan = $this->input->post("lastScan", TRUE);
         $this->load->model("integra_model");
         $result = $this->integra_model->getAWBhistory($awb_nos);
         $history = array();
         if($result)
         {
            $code_name = lang_db('code_name');
            $integraStatus = $this->app->getIntegraStatus();
            foreach($result as $awb)
            {
               $add = TRUE;
               foreach($integraStatus as $IntSta)
               {
                  if(trim($IntSta->code_id) == trim($awb->CodeID))
                  {
                     if($IntSta->status == 'enable')
                     {
                        $awb->status_text = $IntSta->$code_name;
                     }
                     else
                     {
                        $add = FALSE;
                     }
                     break;
                  }
               }

               if($add)
                  $history[$awb->AWBNo][] = $awb;

               $history[$awb->AWBNo]['AWBinfo'] = $this->integra_model->getAWB($awb->AWBNo);
            }
         }
         $info["history"] = $history;
         $info["original"] = $awb_nos;
         $info['last'] = $lastScan;
         $this->loadview->view("shipment_history", $info);
      }
      else
      {
         error('invalid_awb_no');
         redirect($_SERVER['HTTP_REFERER']);
      }
   }

   public function my_shipments()
   {
      // configure pagination
      $this->load->model("user");
      $user_info = $this->user->get_userInfo($this->auth->user("id"));
      if($user_info->integra_account_id)
      {
         $this->load->model("integra_model");
         $this->load->library('pagination');
         $this->load->helper('functions');
         $per_page = ifempty($this->input->get("per_page", TRUE), 1);
         $config['base_url'] = current_url() . "?";
         $config['per_page'] = $this->config->item('per_page');
         $start_position = ($per_page - 1) * $config['per_page'];

         $info["user_shipments"] = $this->integra_model->getMyShipments($user_info->integra_account_id, $this->config->item('per_page'), $start_position);
         $config['total_rows'] = $this->integra_model->shipments_counter;
         $this->pagination->initialize($config);
      }
      else
      {
         redirect("account/profile");
      }
      $this->loadview->view("users/my_shipments", $info);
   }

   public function myRequests()
   {
      // configure pagination
      $this->load->library('pagination');
      $this->load->helper('functions');
      $per_page = ifempty($this->input->get("per_page", TRUE), 1);
      $config['base_url'] = current_url() . "?";
      $config['per_page'] = $this->config->item('per_page');
      $start_position = ($per_page - 1) * $config['per_page'];

      $info["requests"] = $this->app->get_my_pickup_request($this->config->item('per_page'), $start_position);
      $config['total_rows'] = $this->app->requests_counter;
      $this->pagination->initialize($config);
      $this->loadview->view("users/pickup_requests", $info);
   }

   public function cancelrequest($id)
   {
      $data["status"] = "cancelled";
      $edit = $this->app->edit_pickup_request($id, $data);
      success("pickup_request_cancelled");
      redirect("shipment/myRequests");
   }

   public function calculator()
   {
      $info['final_cost'] = FALSE;
      if($this->input->post())
      {
         $this->load->library('form_validation');
//         $this->form_validation->set_rules('width', 'Width', 'trim|required');
//         $this->form_validation->set_rules('height', 'Height', 'trim|required');
//         $this->form_validation->set_rules('length', 'Length', 'trim|required');
         $this->form_validation->set_rules('source', 'Source', 'trim|required');
         $this->form_validation->set_rules('destination', 'Destination', 'trim|required');
         $this->form_validation->set_rules('weight_unit', 'Weight Unit', 'trim|required');
         $this->form_validation->set_rules('weight', 'Weight', 'trim|required');


         if($this->form_validation->run() == FALSE)
         {
            error(validation_errors(), TRUE);
            redirect("shipment/calculator");
         }
         $this->load->helper("functions");
         $width = ifempty($this->input->post("width", TRUE), 0);
         $height = ifempty($this->input->post("height", TRUE), 0);
         $length = ifempty($this->input->post("length", TRUE), 0);
         $weight_unit = $this->input->post("weight_unit", TRUE);
         $weight = $this->input->post("weight", TRUE);
         $source = $this->input->post("source", TRUE);
         $destination = $this->input->post("destination", TRUE);

         $info['user_data'] = array(
                        'width'       => $width,
                        'height'      => $height,
                        'length'      => $length,
                        'weight_unit' => $weight_unit,
                        'weight'      => $weight,
                        'source'      => $source,
                        'destination' => $destination,
         );
         $dimenstions = ($width * $height * $length) / 5000;

         // convert to KG
         if($weight_unit == 1)
            $weight = $weight / 1000;

         // select bigger
         if($dimenstions > $weight)
            $weight = $dimenstions;

         $sourec_zone = $this->app->get_cityZones($source);
         $sourec_zone = $sourec_zone->zone;
         $destination_zone = $this->app->get_cityZones($destination);
         $destination_zone = $destination_zone->zone;

         $weight = ceil($weight);
         if($weight > 100)
         {
            $cost_for_kilo = $this->app->get_zoneCost(101);
            $add_cost = $cost_for_kilo[$sourec_zone];
            if($cost_for_kilo[$destination_zone] > $cost_for_kilo[$sourec_zone])
               $add_cost = $cost_for_kilo[$destination_zone];

            $extra_weight = $weight - 100;
            $extra_cost = $add_cost * $extra_weight;
            $weight = 100;
         }
         else
         {
            $extra_cost = 0;
         }

         $cost_for_kilo = $this->app->get_zoneCost($weight);
         $final_cost = $cost_for_kilo[$sourec_zone];
         if($cost_for_kilo[$destination_zone] > $cost_for_kilo[$sourec_zone])
            $final_cost = $cost_for_kilo[$destination_zone];

         $info['final_cost'] = $final_cost + $extra_cost;
      }
      $info['cities'] = $this->app->get_cityZones();
      $this->loadview->view("calculator", $info);
   }

   public function createAWB()
   {
      // custom save autocomplete
      $user_id = '';
      if($this->auth->user("id")){
         $user_id = $this->auth->user("id");
      }
      
      $awb_auto = array(
         
         'user_id' => $user_id,
         'name' => $this->input->post('recipient_name'),
         'phone' => $this->input->post('recipient_phone'),
         'city' => $this->input->post('recipient_city'),
         'address1'=> $this->input->post('recipient_address1'),
         'address2'=> $this->input->post('recipient_address2'),
      );
      $this->load->model('pickup_requests');
      if (!empty($this->input->post('recipient_name'))){
         $this->pickup_requests->save_awd_auto($awb_auto);
      }

      // end custom save autocomplete

      $this->auth->forceLogin();
      if($this->input->post())
      {
         $this->load->library('form_validation');
         $this->form_validation->set_rules('no_of_pieces', 'Number Of Pieces', 'trim|required');
         $this->form_validation->set_rules('recipient_name', 'Reciepient Name', 'trim|required');
         $this->form_validation->set_rules('recipient_phone', 'Reciepient Phone', 'trim|required');
         $this->form_validation->set_rules('recipient_city', 'Reciepient City', 'trim|required');
         $this->form_validation->set_rules('recipient_address1', 'Reciepient Address 1', 'trim|required');
         $this->form_validation->set_rules('recipient_address2', 'Reciepient Address 2', 'trim|required');
//            $this->form_validation->set_rules('COD_amount', 'Code amount', 'trim|required');
         $this->form_validation->set_rules('weight', 'Weight', 'trim|required');
//            $this->form_validation->set_rules('width', 'Width', 'trim|required');
         $this->form_validation->set_rules('goods_description', 'goods description', 'trim|required');
//            $this->form_validation->set_rules('height', 'Height', 'trim|required');
//            $this->form_validation->set_rules('length', 'Length', 'trim|required');
//            $this->form_validation->set_rules('notes', 'Notes', 'trim|required');


         if($this->form_validation->run() == FALSE)
         {
            error(validation_errors(), TRUE);
            redirect("shipment/createAWB");
         }

         $no_of_pieces = $this->input->post("no_of_pieces", TRUE);
         $recipient_name = $this->input->post("recipient_name", TRUE);
         $recipient_phone = $this->input->post("recipient_phone", TRUE);
         $recipient_city = $this->input->post("recipient_city", TRUE);
         $recipient_address1 = $this->input->post("recipient_address1", TRUE);
         $recipient_address2 = $this->input->post("recipient_address2", TRUE);
         $COD_amount = $this->input->post("COD_amount", TRUE);
         $weight = $this->input->post("weight", TRUE);
         $goods_description = $this->input->post("goods_description", TRUE);
         $width = $this->input->post("width", TRUE);
         $height = $this->input->post("height", TRUE);
         $length = $this->input->post("length", TRUE);
         $notes = $this->input->post("notes", TRUE);
         if(!$width && !$height && !$length)
            $dimensions = '';
         else
            $dimensions = $width . "X" . $height . "X" . $length;

         $our_AWB = array(
                        'account_id'         => $this->auth->user('id'),
                        'bill_date'          => date("Y-m-d H:i:s"),
                        'no_of_pieces'       => $no_of_pieces,
                        'recipient_name'     => $recipient_name,
                        'recipient_address1' => $recipient_address1,
                        'recipient_address2' => $recipient_address2,
                        'recipient_city'     => $recipient_city,
                        'recipient_phone'    => $recipient_phone,
                        'dimensions'         => $dimensions,
                        'goods_description'  => $goods_description,
                        'notes'              => $notes,
                        'weight'             => $weight,
                        'COD_amount'         => $COD_amount,
                        'status'             => 1
         );

         $valid = $this->validateABW($our_AWB);
         if($valid == -1)
         {
            redirect("account/profile");
         }
         elseif($valid == -2)
         {
            error("Something Wrong happened . Please try again .");
            redirect("shipment/createAWB");
         }
      }

      $info["cities"] = $this->app->getCities();
      $this->loadview->view("createAwb", $info);
   }


   public function bulkAWB()
   {
      if($_FILES['bulk_awb']['name'])
      {
         $config['upload_path'] = './webroot/uploads/excel/';
         $config['allowed_types'] = 'xlx|xlsx';
         $config['max_size'] = '16384';
         $this->load->library('upload', $config);
         if(!$this->upload->do_upload('bulk_awb'))
         {
            error($this->upload->display_errors(), TRUE);
            redirect($_SERVER['HTTP_REFERER']);
         }
         else
         {
            $filedata = $this->upload->data();
            $xlx_file = $filedata['file_name'];
            require_once(APPPATH . 'third_party/simplexlsx.class.php');
            $xlsx = new SimpleXLSX($config['upload_path'] . $xlx_file);

            if(!$xlsx->rows())
            {
               error('Invaild Excel File');
               redirect('shipment/createAWB');
            }

            foreach($xlsx->rows() as $key => $row)
            {
               if($row)
               {
                  /** Process addition here * */
                  $width = trim(addslashes($row[8]));
                  $height = trim(addslashes($row[9]));
                  $length = trim(addslashes($row[10]));
                  if(!$width && !$height && !$length)
                     $dimensions = '';
                  else
                     $dimensions = $width . "X" . $height . "X" . $length;

                  $our_AWB = array(
                                 'account_id'         => $this->auth->user('id'),
                                 'bill_date'          => date("Y-m-d H:i:s"),
                                 'no_of_pieces'       => trim(addslashes($row[6])),
                                 'recipient_name'     => trim(addslashes($row[0])),
                                 'recipient_address1' => trim(addslashes($row[3])),
                                 'recipient_address2' => trim(addslashes($row[4])),
                                 'recipient_city'     => trim(addslashes($row[2])),
                                 'recipient_phone'    => trim(addslashes($row[1])),
                                 'dimensions'         => $dimensions,
                                 'goods_description'  => trim(addslashes($row[11])),
                                 'notes'              => trim(addslashes($row[12])),
                                 'weight'             => trim(addslashes($row[7])),
                                 'COD_amount'         => trim(addslashes($row[5])),
                                 'status'             => 1
                  );

                  if(empty($our_AWB['no_of_pieces']) || empty($our_AWB['recipient_name']) || empty($our_AWB['recipient_address1']) || empty($our_AWB['recipient_address2']) ||
                          empty($our_AWB['recipient_city']) || empty($our_AWB['recipient_phone']) || empty($our_AWB['weight']) || empty($our_AWB['goods_description']))
                  {
                     error('Invalid AWB Information');
                     redirect('shipment/createAWB');
                  }
                  $our_AWB_array[] = $our_AWB;
               }
            }
            $this->validateABW($our_AWB_array, TRUE);
         }
      }
   }

   private function validateABW($our_AWB, $multiable = FALSE)
   {
      $this->load->model("user");
      $user_info = $this->user->get_userInfo($this->auth->user("id"));
      if($user_info->integra_account_id)
      {
         if($multiable === FALSE)
            $AWB_array = array($our_AWB);
         else
            $AWB_array = $our_AWB;

         $pdfarray = array();
         foreach($AWB_array as $our_AWB)
         {
            $inserted_id = $this->app->saveAWBLog($our_AWB);
            $AWBNo = 'EE' . sprintf("%08d", $inserted_id);
            $integra_AWB = array(
                           'ddd_api_sno'       => $AWBNo,
                           'AWBNo'             => $AWBNo,
                           'AccountNo'         => $user_info->integra_account_id,
                           'BillDate'          => $our_AWB['bill_date'],
                           'NoofPieces'        => $our_AWB['no_of_pieces'],
                           'ShipperRef'        => $user_info->first_name . " " . $user_info->last_name,
                           'Consignee'         => $our_AWB['recipient_name'],
                           'ConsigneeName'     => $our_AWB['recipient_name'],
                           'ConsigneeAddress1' => $our_AWB['recipient_address1'],
                           'ConsigneeAddress2' => $our_AWB['recipient_address2'],
                           'ConsigneeCity'     => $our_AWB['recipient_city'],
                           'ConsigneePhone'    => $our_AWB['recipient_phone'],
                           'ConsigneeFax'      => '',
                           'GoodsDesc'         => $our_AWB['goods_description'],
                           'SpecialInstruct'   => $our_AWB['notes'],
                           'Wght'              => $our_AWB['weight'],
                           'Amount'            => $our_AWB['COD_amount']
            );
            $this->load->model("integra_model");
            $iinput = array();
            foreach($integra_AWB as $ikey => $iitem)
            {
               $iinput[$ikey] = str_replace("'", "", $iitem);
            }
            $integra_AWB_created = $this->integra_model->addAWB($iinput);
            $integra_AWB_created = TRUE;

            if($integra_AWB_created == FALSE)
            {
               $this->app->updateAWBLog($inserted_id);
               return -2;
               break;
            }
            else
            {
               $this->session->set_userdata('AWBNo', $AWBNo);
               // create AWB as PDF
               $barcode = $this->barcode($AWBNo);
               $pdf = array(
                              'bill_date'          => $our_AWB['bill_date'],
                              'barcode'            => $barcode,
                              'AWBNO'              => $AWBNo,
                              'fedex_account_no'   => $integra_AWB['AccountNo'],
                              'weight'             => $integra_AWB['Wght'],
                              'name'               => $integra_AWB['ShipperRef'],
                              'dimensions'         => $our_AWB['dimensions'],
                              'COD_amount'         => $our_AWB['COD_amount'],
                              'shipper_name'       => $integra_AWB['ShipperRef'],
                              'shipper_address1'   => $user_info->address,
                              'shipper_phone'      => $user_info->phone,
                              'recipient_name'     => $our_AWB['recipient_name'],
                              'recipient_address1' => $our_AWB['recipient_address1'],
                              'recipient_address2' => $our_AWB['recipient_address2'],
                              'recipient_city'     => $this->app->get_cityByCode($our_AWB['recipient_city']),
                              'recipient_phone'    => $our_AWB['recipient_phone'],
                              'no_of_pieces'       => $our_AWB['no_of_pieces'],
                              'goods_description'  => $our_AWB['goods_description'],
               );
               $pdfarray[] = $pdf;
            }
         }

         $this->AWBpdf($pdfarray);
      }
      else
      {

         return -1;  // do not have integra id
      }
   }

   public function downloadPDF($AWBNO)
   {

      $this->load->
              helper('generate_pdf');
      pdf_create($AWBNO);
   }

   public function barcode($code)
   {
      $this->load->library('zend');
      $this->zend->load('Zend/Barcode');
      $options = array('text' => $code, 'barHeight' => 60);
      $resource = Zend_Barcode::draw(
                      'code39', 'image', $options, array());
      ob_start();
      imagejpeg($resource);
      $data = ob_get_clean();

      return base64_encode($data);
   }

   public function AWBpdf($AWB_info_array)
   {
      $html = '';
      foreach($AWB_info_array as $AWB_info)
      {
         $info['AWB'] = (object) $AWB_info;

         $html .= $this->load->view("awb_html", $info, TRUE);
         $AWBNos[] = $AWB_info['AWBNO'];
      }
      $file_name = 'AWB-' . time();
      file_put_contents('./' . APPPATH . 'cache/' . $file_name . '.html', $html);

      $view['filename'] = $file_name;
      $view['AWBNos'] = $AWBNos;
      $this->loadview->view("users/awbnumber", $view);
   }

   public function export()
   {
      $awb_nos = unserialize($this->input->post("AWBs", TRUE));
      if($awb_nos)
      {
         $this->load->model("integra_model");
         $result = $this->integra_model->getAWBhistory($awb_nos);
         $history = array(array(
                                       'AWBNo', 'Date', 'Time', 'Status', 'Remarks',
                                       'Description'
         ));
         if($result)
         {
            $code_name = lang_db('code_name');
            $integraStatus = $this->app->getIntegraStatus();
            $served = array();
            foreach($result as $awb)
            {
               foreach($integraStatus as $IntSta)
               {
                  if(trim($IntSta->code_id) == trim($awb->CodeID))
                  {
                     if($IntSta->status == 'enable')
                     {
                        $awb->status_text = $IntSta->$code_name;
                        unset($awb->CodeID);
                     }
                     break;
                  }
               }
               if(in_array($awb->AWBNo, $served))
                  continue;

               $date = date('M d Y', strtotime($awb->TransDate));
               $export_array = array($awb->AWBNo, $date, $awb->TransTime,
                              $awb->status, $awb->Remarks, $awb->status_text);
               $history[] = $export_array;
               array_push($served, $awb->AWBNo);
            }
         }

         $this->load->helper('csv');
         $time = time();
         $path = "webroot/uploads/excel/AWBnos-{$time}.csv";
         array_to_csv($history, $path);
         echo $path;
      }
   }

   /*
   * return getshipersnames json
   *
   */
   public function getshipersnames(){
      if($this->auth->isLogin())
         {
              $user_id = $this->auth->user("id");
              $this->db->distinct();
              $this->db->select('name, cperson, address1, address2, tel, fax, mobile, city');
              $this->db->from('shipper_details');
              $this->db->where('user_id', $user_id);
              $this->db->where('type', '1');
              $query = $this->db->get();

                  $data = array();
                  foreach ($query->result() as $row)
                  {
                      $data[] = array(
                                 'value' => $row->name,
                                 'cperson'=> $row->cperson,
                                 'address1'=> $row->address1,
                                 'address2'=> $row->address2,
                                 'tel'=> $row->tel,
                                 'fax'=> $row->fax,
                                 'mobile'=> $row->mobile,
                                 'city'=> $row->city,
                                 );
                  }

        echo json_encode($data);
        }
   }

   /*
   * return getconsigneesnames json
   *
   */
   public function getconsigneesnames(){
      if($this->auth->isLogin())
         {
              $user_id = $this->auth->user("id");
              $this->db->distinct();
              $this->db->select('name, cperson, address1, address2, tel, fax, mobile, city');
              $this->db->from('shipper_details');
              $this->db->where('user_id', $user_id);
              $this->db->where('type', '2');
              $query = $this->db->get();

                  $data = array();
                  foreach ($query->result() as $row)
                  {
                      $data[] = array(
                                 'value' => $row->name,
                                 'cperson'=> $row->cperson,
                                 'address1'=> $row->address1,
                                 'address2'=> $row->address2,
                                 'tel'=> $row->tel,
                                 'fax'=> $row->fax,
                                 'mobile'=> $row->mobile,
                                 'city'=> $row->city,
                                 );
                  }

        echo json_encode($data);
        }
   }

   /*
   * return getconsigneesnames json
   *
   */
   public function getawpauto(){
      if($this->auth->isLogin())
         {
              $user_id = $this->auth->user("id");
              $this->db->distinct();
              $this->db->select('name, phone, address1, address2, city');
              $this->db->from('awd_auto');
              $this->db->where('user_id', $user_id);
              $query = $this->db->get();

                  $data = array();
                  foreach ($query->result() as $row)
                  {
                      $data[] = array(
                                 'value' => $row->name,
                                 'address1'=> $row->address1,
                                 'address2'=> $row->address2,
                                 'phone'=> $row->phone,
                                 'city'=> $row->city,
                                 );
                  }

        echo json_encode($data);
        }
   }


}

/* End of file shipment.php */

/* Location: ./application/controllers/shipment.php */
