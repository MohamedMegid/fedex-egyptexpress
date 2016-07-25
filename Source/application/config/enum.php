<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

$config['payment_methods'] = array('prepaid' => 'prepaid', 'COD' => 'COD');

$config['AWB_status'] = array('processing' => 'processing', 'delivered'  => 'delivered',
               'failed'     => 'failed');


$config['product_type'] = array(
               'en' => array(
                              'Box'      => 'Box',
                              'Document' => 'Document',
                              'Freight'  => 'Freight',
               ),
               'ar' => array(
                              'Box'      => 'صندوق',
                              'Document' => 'وثائق',
                              'Freight'  => 'شحن',
               )
);

/* End of file enum.php */
/* Location: ./application/config/enum.php */