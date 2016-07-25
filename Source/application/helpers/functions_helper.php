<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

if(!function_exists('empty2false'))
{

   // any empty changed to be false
   function empty2false($var)
   {
      if(empty($var))
         return false;
      else
         return $var;
   }

}


if(!function_exists('ifempty'))
{

   // check empty
   function ifempty($var, $empty_value, $not_empty_value = 'x.x')
   {
      if(empty($var))
         return $empty_value;
      else
         return ($not_empty_value != 'x.x') ? $not_empty_value : $var;
   }

}



if(!function_exists('array_column'))
{

   /**
    * Returns the values from a single column of the input array, identified by
    * the $columnKey.
    *
    * Optionally, you may provide an $indexKey to index the values in the returned
    * array by the values from the $indexKey column in the input array.
    *
    * @param array $input A multi-dimensional array (record set) from which to pull
    *                     a column of values.
    * @param mixed $columnKey The column of values to return. This value may be the
    *                         integer key of the column you wish to retrieve, or it
    *                         may be the string key name for an associative array.
    * @param mixed $indexKey (Optional.) The column to use as the index/keys for
    *                        the returned array. This value may be the integer key
    *                        of the column, or it may be the string key name.
    * @return array
    */
   function array_column($input = null, $columnKey = null, $indexKey = null)
   {
      // Using func_get_args() in order to check for proper number of
      // parameters and trigger errors exactly as the built-in array_column()
      // does in PHP 5.5.
      $argc = func_num_args();
      $params = func_get_args();
      if($argc < 2)
      {
         trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
         return null;
      }
      if(!is_array($params[0]))
      {
         trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
         return null;
      }
      if(!is_int($params[1]) && !is_float($params[1]) && !is_string($params[1]) && $params[1] !== null && !(is_object($params[1]) && method_exists($params[1], '__toString'))
      )
      {
         trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
         return false;
      }
      if(isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2]) && !(is_object($params[2]) && method_exists($params[2], '__toString'))
      )
      {
         trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
         return false;
      }
      $paramsInput = $params[0];
      $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
      $paramsIndexKey = null;
      if(isset($params[2]))
      {
         if(is_float($params[2]) || is_int($params[2]))
         {
            $paramsIndexKey = (int) $params[2];
         }
         else
         {
            $paramsIndexKey = (string) $params[2];
         }
      }
      $resultArray = array();
      foreach($paramsInput as $row)
      {
         $key = $value = null;
         $keySet = $valueSet = false;
         if($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row))
         {
            $keySet = true;
            $key = (string) $row[$paramsIndexKey];
         }
         if($paramsColumnKey === null)
         {
            $valueSet = true;
            $value = $row;
         }
         elseif(is_array($row) && array_key_exists($paramsColumnKey, $row))
         {
            $valueSet = true;
            $value = $row[$paramsColumnKey];
         }
         if($valueSet)
         {
            if($keySet)
            {
               $resultArray[$key] = $value;
            }
            else
            {
               $resultArray[] = $value;
            }
         }
      }
      return $resultArray;
   }

}



if(!function_exists('generate_select_options'))
{

   function generate_select_options($data, $selected_item = FALSE, $options = FALSE, $lang = FALSE)
   {
      $html = '';
      foreach($data as $index => $item)
      {

         if(is_object($item))
            $item = get_object_vars($item);
         if($options)
         {
            $index = $item[$options[0]];
            $selected = ($item[$options[0]] == $selected_item) ? 'selected="selected"' : "";
         }
         else
         {
            $selected = ($index == $selected_item) ? 'selected="selected"' : "";
         }


         if($lang && $options)
         {
            $name = lang_db($options[1]);
            $display_item = $item[$name];
         }
         else
         {
            $display_item = ($options) ? $item[$options[1]] : $item;
            $display_item = lang(trim($display_item));
         }

         $html .= "<option value=\"{$index}\" {$selected}>" . $display_item . "</option>\n";
      }

      return $html;
   }

}
// ------------------------------------------------------------------------

/* End of file notification_helper.php */
/* Location: ./system/heleprs/notification_helper.php */