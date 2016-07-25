<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

class Integra_model extends CI_Model {

   private $integra;
   private $table_name;
   public $shipments_counter = 0;

   function __construct()
   {
      parent::__construct();
      $this->integra = $this->load->database('integra', TRUE);
//     var_dump($this->integra->initialize());
      if(!$this->integra->initialize())
         return FALSE;
      else
         $this->table_name = $this->config->item('integra_table_name');
   }

   public function addAWB($data)
   {
      $cust = $this->load->database('cust', TRUE);
      if(!$cust->initialize())
         return FALSE;

      $inserted = $cust->query("INSERT INTO OnlineShipmentBatch (ddd_api_sno, AWBNo, AccountNo, BillDate, NoofPieces, ShipperRef, Consignee,
         ConsigneeName, ConsigneeAddress1, ConsigneeAddress2, ConsigneeCity, ConsigneePhone, ConsigneeFax, GoodsDesc, SpecialInstruct, Wght, Amount)
         VALUES ('{$data['ddd_api_sno']}', '{$data['AWBNo']}', '{$data['AccountNo']}', '{$data['BillDate']}', '{$data['NoofPieces']}',
            N'{$data['ShipperRef']}', N'{$data['Consignee']}', N'{$data['ConsigneeName']}', N'{$data['ConsigneeAddress1']}', N'{$data['ConsigneeAddress2']}', '{$data['ConsigneeCity']}',
               '{$data['ConsigneePhone']}', '{$data['ConsigneeFax']}', N'{$data['GoodsDesc']}', N'{$data['SpecialInstruct']}', '{$data['Wght']}', '{$data['Amount']}')");
      return $inserted;
   }

   public function getAWBhistory($AWB_no)
   {
      if(!is_array($AWB_no))
         $AWB_no = array($AWB_no);

      array_walk($AWB_no, function (&$val)
      {
         $val = "'" . $val . "'";
      });
      $AWB_no = implode(',', $AWB_no);

      $query = $this->integra->query("SELECT AWBNo, LogFile.Status as status, Remarks, TransDate, TransTime, CodeMast.CodeID ,CodeMast.CodeName as status_text FROM LogFile"
              . " LEFT JOIN CodeMast ON LogFile.Status = CodeMast.CodeID WHERE AWBNo IN ($AWB_no) ORDER BY TransDate DESC,TransTime DESC");

      return $query->result();
   }

   public function getMyShipments($account_no, $limit = 1, $start_paination = 0)
   {
      $q = $this->integra->query("SELECT COUNT(*) as counter FROM " . "$this->table_name" . " WHERE AccountNo = " . "'$account_no'" . "");
      $this->shipments_counter = $q->row()->counter;
      $this->integra->select("AWBNo,DelivStatus as status , NoofPieces , Weight , GoodsDesc , PickupDate");
      $this->integra->where('AccountNo', $account_no);
      $this->integra->order_by('PickupDate', "DESC"); //under testing
      $this->integra->limit($limit, $start_paination);
      $query = $this->integra->get($this->table_name);
      return $query->result();
   }

   public function getAWB($awb_no)
   {
      $this->integra->where('AWB.AWBNo', $awb_no);
      $this->integra->join('AWBARABICADD', 'AWB.AWBNo = AWBARABICADD.AWBNo');

      $query = $this->integra->get($this->table_name);
      return $query->row();
   }

   public function save_pickupRequest($EnqMast, $EnqBookingRequest)
   {
      $extrafields = 'Tel, Fax, City, Country, cPerson, BookerContact, MainContact,Email, Mobile, Details, Address, Type';
      $extravlues = "'01150321000', '023256865', 'CAI', 'EG', 'WEBSITE', 'email', 'admin', 'info@egyptexpress.com.eg', '01150321000', N'None', N'Sheraton Heliopolis', 'B'";
      $EnqMast_inserted = $this->integra->query("INSERT INTO EnqMast (EnqRefNo, EnqDate, EnqTime, CustName, ClientCode, {$extrafields}) VALUES "
              . "('{$EnqMast['EnqRefNo']}', '{$EnqMast['EnqDate']}', '{$EnqMast['EnqTime']}', N'{$EnqMast['CustName']}', '{$EnqMast['ClientCode']}', {$extravlues} )");

// insert inEnqBooking
      if($EnqMast_inserted)
      {
         $query = "INSERT INTO EnqBookingRequest (EnqRefNo, BookType, PCS, Weight, BOrigin, BDest, PickupDate1, PickUpTime1, PickupDate2, PickUpTime2, SendersName, SendersAddress1, SendersAddress2, RecieverAddress1, RecieverAddress2, LandMark,BService,BComments,ReceiversName,SendersTel,ReceiversTel,SendersFax,ReceiversFax,SendersCPerson, ReceiversCPerson,SendersMobile,SendersCity,ReceiversMobile,RecieverCity,BpickupLoc,Amount,OtherCharges,ValueofGoods,InsCharges,CODAmount,vBookType,
            ReceiverCountry,InsuranceRef,Provider,Route,Courier,Dimension,ClosingTime,SMSNo,RouteAllocatedDate,Branch,SendersEMail,SendersCoutry,ReceiversEmail,PickupSheetNo,CreatedBy)
         VALUES ('{$EnqBookingRequest['EnqRefNo']}', '{$EnqBookingRequest['BookType']}', '{$EnqBookingRequest['PCS']}', '{$EnqBookingRequest['Weight']}', '{$EnqBookingRequest['BOrigin']}','{$EnqBookingRequest['BDest']}', '{$EnqBookingRequest['PickupDate1']}', '{$EnqBookingRequest['PickUpTime1']}', '{$EnqBookingRequest['PickupDate2']}', '{$EnqBookingRequest['PickUpTime2']}', N'{$EnqBookingRequest['SendersName']}', N'{$EnqBookingRequest['SendersAddress1']}', N'{$EnqBookingRequest['SendersAddress2']}', N'{$EnqBookingRequest['RecieverAddress1']}',"
                 . " N'{$EnqBookingRequest['RecieverAddress2']}', N'{$EnqBookingRequest['LandMark']}', '{$EnqBookingRequest['BService']}', N'{$EnqBookingRequest['BComments']}',"
                 . " N'{$EnqBookingRequest['ReceiversName']}', '{$EnqBookingRequest['SendersTel']}', '{$EnqBookingRequest['ReceiversTel']}', '{$EnqBookingRequest['SendersFax']}',"
                 . " '{$EnqBookingRequest['ReceiversFax']}', N'{$EnqBookingRequest['SendersCPerson']}', N'{$EnqBookingRequest['ReceiversCPerson']}', '{$EnqBookingRequest['SendersMobile']}',"
                 . " N'{$EnqBookingRequest['SendersCity']}', '{$EnqBookingRequest['ReceiversMobile']}', N'{$EnqBookingRequest['RecieverCity']}', N'{$EnqBookingRequest['BpickupLoc']}',0,0,0,0,0,'D','EGYPT','','','','','','{$EnqBookingRequest['PickUpTime2']}','','','CAI','','EGYPT','',0,'Website')";

         $EnqBookingRequest_inserted = $this->integra->query($query);
      }
      else
      {
         return $EnqMast_inserted;
      }
      return $EnqBookingRequest_inserted;
   }

}

/* End of file integra_model.php */

/* Location: ./application/models/integra_model.php */



