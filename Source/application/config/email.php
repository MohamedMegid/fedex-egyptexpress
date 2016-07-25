<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

$config['protocol'] = 'smtp';    // mail, sendmail, or smtp

$config['smtp_host'] = 'ssl://email-smtp.us-east-1.amazonaws.com';

$config['smtp_port'] = '465';

$config['smtp_user'] = 'AKIAJQHY4NVRRQXNTUAQ';

$config['smtp_pass'] = 'AiFixxZzOOk/SY77no+H1TmrWOwmW4c2QLPZOxVA1FU9';

$config['newline'] = "\r\n";

$config['charset'] = 'utf-8';  // Character set (utf-8, iso-8859-1, etc.).

$config['wordwrap'] = TRUE;   // TRUE or FALSE (boolean)	Enable word-wrap.

$config['wrapchars'] = 76;   // Character count to wrap at.

$config['mailtype'] = 'html';  // text or html	Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.

$config['priority'] = 3;   // 3	1, 2, 3, 4, 5	Email Priority. 1 = highest. 5 = lowest. 3 = normal.

$config['validate'] = TRUE;   // TRUE or FALSE (boolean)	Whether to validate the email address.





/* End of file email.php */

/* Location: ./application/config/email.php */