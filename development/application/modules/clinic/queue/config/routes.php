<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['queue/display'] = "queue/queue_view";
$route['queue/display(/:any)'] = "queue/queue_view$1";
$route['queue/display(/:any)(/:any)'] = "queue/queue_view$1$2";

