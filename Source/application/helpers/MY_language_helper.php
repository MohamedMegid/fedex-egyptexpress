<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

function lang($line, $id = '')
{
   $CI = & get_instance();
   $line_exist = $CI->lang->line($line);
   if($line_exist)
      $line = $line_exist;

   $args = func_get_args();

   if(is_array($args))
      array_shift($args);

   if(is_array($args) && count($args))
   {
      foreach($args as $arg)
      {
         $line = str_replace_first('%s', $arg, $line);
      }
   }

   if($id != '')
   {
      $line = '<label for="' . $id . '">' . htmlentities($line) . "</label>";
   }

   return $line;
}

function str_replace_first($search_for, $replace_with, $in)
{
   $pos = strpos($in, $search_for);
   if($pos === false)
   {
      return $in;
   }
   else
   {
      return substr($in, 0, $pos) . $replace_with . substr($in, $pos + strlen($search_for), strlen($in));
   }
}

function lang_db($variable)
{
   $CI = & get_instance();
   $variable = $variable . "_" . $CI->lang->lang();
   return $variable;
}

/* End of file MY_language_helper.php */
/* Location: ./application/helpers/MY_language_helper */