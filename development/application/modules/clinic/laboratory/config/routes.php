<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['laboratory/test-category'] = "test_category/index"; 
$route['laboratory/test-category(/:any)?'] = "test_category$1"; 
$route['laboratory/test-category(/:any)?(/:any)?'] = "test_category$1$2"; 
$route['laboratory/test-category(/:any)?(/:any)?(/:any)?'] = "test_category$1$2$3"; 

$route['laboratory/test-type'] = "test_type/index"; 
$route['laboratory/test-type(/:any)?'] = "test_type$1"; 
$route['laboratory/test-type(/:any)?(/:any)?'] = "test_type$1$2"; 
$route['laboratory/test-type(/:any)?(/:any)?(/:any)?'] = "test_type$1$2$3"; 

$route['laboratory/test-technique'] = "test_technique/index"; 
$route['laboratory/test-technique(/:any)?'] = "test_technique$1"; 
$route['laboratory/test-technique(/:any)?(/:any)?'] = "test_technique$1$2"; 
$route['laboratory/test-technique(/:any)?(/:any)?(/:any)?'] = "test_technique$1$2$3"; 