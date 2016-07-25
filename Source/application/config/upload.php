<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');





$config['upload_path'] = './webroot/uploads/'; //	The path to the folder where the upload should be placed. The folder must be writable and the path can be absolute or relative.



$config['allowed_types'] = 'gif|jpg|png'; //	The mime types corresponding to the types of files you allow to be uploaded. Usually the file extension can be used as the mime type. Separate multiple types with a pipe.



$config['file_name'] = '';   //	Desired file name	If set CodeIgniter will rename the uploaded file to this name. The extension provided in the file name must also be an allowed file type.



$config['overwrite'] = FALSE;  //	TRUE/FALSE 	If set to true, if a file with the same name as the one you are uploading exists, it will be overwritten. If set to false, a number will be appended to the filename if another with the same name exists.



$config['max_size'] = '8192';  //  The maximum size (in kilobytes) that the file can be. Set to zero for no limit. Note: Most PHP installations have their own limit, as specified in the php.ini file. Usually 2 MB (or 2048 KB) by default



$config['max_width'] = '1024';  //	The maximum width (in pixels) that the file can be. Set to zero for no limit.



$config['max_height'] = '768';  //	The maximum height (in pixels) that the file can be. Set to zero for no limit.



$config['max_filename'] = '0';  //		The maximum length that a file name can be. Set to zero for no limit.



$config['encrypt_name'] = TRUE; //TRUE/FALSE  If set to TRUE the file name will be converted to a random encrypted string. This can be useful if you would like the file saved with a name that can not be discerned by the person uploading it.



$config['remove_spaces'] = TRUE; // TRUE/FALSE  If set to TRUE, any spaces in the file name will be converted to underscores. This is recommended.







/* End of file upload.php */

/* Location: ./application/config/upload.php */