<?php

if(!defined('BASEPATH'))
   exit('No direct script access allowed');

//The following is a list of all the preferences you can pass to the initialization function to tailor the display.
$config['uri_segment'] = 4;

//The pagination function automatically determines which segment of your URI contains the page number. If you need something different you can specify it.
$config['num_links'] = 3;

//The number of "digit" links you would like before and after the selected page number. For example, the number 2 will place two digits on either side, as in the example links at the very top of this page.
$config['use_page_numbers'] = TRUE;

//By default, the URI segment will use the starting index for the items you are paginating. If you prefer to show the the actual page number, set this to TRUE.
$config['page_query_string'] = TRUE;

//By default, the pagination library assume you are using URI Segments, and constructs your links something like
//http://example.com/index.php/test/page/20
//If you have $config['enable_query_strings'] set to TRUE your links will automatically be re-written using Query Strings. This option can also be explictly set. Using $config['page_query_string'] set to TRUE, the pagination link will become.
//http://example.com/index.php?c=test&m=page&per_page=20
//Note that "per_page" is the default query string passed, however can be configured using $config['query_string_segment'] = 'your_string'
//If you would like to surround the entire pagination with some markup you can do it with these two prefs:
$config['full_tag_open'] = '';

//The opening tag placed on the left side of the entire result.
$config['full_tag_close'] = '';

//The closing tag placed on the right side of the entire result.

$config['first_link'] = 'First';

//The text you would like shown in the "first" link on the left. If you do not want this link rendered, you can set its value to FALSE.
$config['first_tag_open'] = '<li>';

//The opening tag for the "first" link.
$config['first_tag_close'] = '</li>';

//The closing tag for the "first" link.

$config['last_link'] = 'Last';

//The text you would like shown in the "last" link on the right. If you do not want this link rendered, you can set its value to FALSE.
$config['last_tag_open'] = '<li>';

//The opening tag for the "last" link.
$config['last_tag_close'] = '</li>';

//The closing tag for the "last" link.

$config['next_link'] = "&#10095;";

//The text you would like shown in the "next" page link. If you do not want this link rendered, you can set its value to FALSE.
$config['next_tag_open'] = '<li>';

//The opening tag for the "next" link.
$config['next_tag_close'] = '</li>';

//The closing tag for the "next" link.


$config['prev_link'] = "&#10094;";

//The text you would like shown in the "previous" page link. If you do not want this link rendered, you can set its value to FALSE.
$config['prev_tag_open'] = '<li>';

//The opening tag for the "previous" link.
$config['prev_tag_close'] = '</li>';

//The closing tag for the "previous" link.

$config['cur_tag_open'] = '<li class="active"><a>';

//The opening tag for the "current" link.
$config['cur_tag_close'] = '</a></li>';

//The closing tag for the "current" link.

$config['num_tag_open'] = '<li>';

//The opening tag for the "digit" link.
$config['num_tag_close'] = '</li>';

//The closing tag for the "digit" link.
//If you wanted to not list the specific pages (for example, you only want "next" and "previous" links), you can suppress their rendering by adding:
$config['display_pages'] = TRUE;

//If you want to add a class attribute to every link rendered by the pagination class, you can set the config "anchor_class" equal to the classname you want.
$config['anchor_class'] = "";


/* End of file pagination.php */
/* Location: ./application/config/pagination.php */