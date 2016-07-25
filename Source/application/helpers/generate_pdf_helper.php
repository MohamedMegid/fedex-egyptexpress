<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

function pdf_create($filename = '')
{
   $basepath = str_replace('system/', '', BASEPATH);
   $path = $basepath . APPPATH . "cache/" . $filename;
   exec("xvfb-run -a wkhtmltopdf " . $path . ".html  -O landscape  " . $path . ".pdf " . " 2>&1", $output2, $output);

   $CI = & get_instance();
   $CI->load->helper("download");

   $output = file_get_contents($path . ".pdf");

   unlink($path . ".html");
   unlink($path . ".pdf");

   force_download($filename . '.pdf', $output);

   exit();
}

?>