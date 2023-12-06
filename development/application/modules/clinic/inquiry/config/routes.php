<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['inquiry/request-list/(pharmacy|outpatient|inpatient)'] = 'inquiry/request_list/$1';
$route['inquiry/request/(pharmacy|outpatient|inpatient)'] = 'inquiry/request/$1';
$route['inquiry/request_cancel/(pharmacy|outpatient|inpatient)'] = 'inquiry/request_cancel/$1';

$route['inquiry/mutation-list/(pharmacy|outpatient|inpatient)'] = 'inquiry/mutation_list/$1';
$route['inquiry/mutation-view/(pharmacy|outpatient|inpatient)'] = 'inquiry/mutation_view/$1';
$route['inquiry/mutation-view/(pharmacy|outpatient|inpatient)(/:any)?'] = 'inquiry/mutation_view/$1$2';

$route['inquiry/mutation-return-list/(pharmacy|outpatient|inpatient)'] = 'inquiry/mutation_return_list/$1';
$route['inquiry/mutation-return-view/(pharmacy|outpatient|inpatient)'] = 'inquiry/mutation_return_view/$1';
$route['inquiry/mutation-return-view/(pharmacy|outpatient|inpatient)(/:any)?'] = 'inquiry/mutation_return_view/$1$2';

$route['inquiry/stock-opname/(pharmacy|outpatient|inpatient)'] = 'inquiry/stock_opname/$1';
$route['inquiry/stock-opname-list/(pharmacy|outpatient|inpatient)'] = 'inquiry/stock_opname_list/$1';
$route['inquiry/stock-opname-view/(pharmacy|outpatient|inpatient)'] = 'inquiry/stock_opname_view/$1';
$route['inquiry/stock-opname-view/(pharmacy|outpatient|inpatient)(/:any)?'] = 'inquiry/stock_opname_view/$1$2';

$route['inquiry/pharmacy/request-list(/:any)?'] = 'pharmacy/request_list$1';
$route['inquiry/pharmacy/mutation-list(/:any)?'] = 'pharmacy/mutation_list$1';
$route['inquiry/pharmacy/mutation-return-list(/:any)?'] = 'pharmacy/mutation_return_list$1';
$route['inquiry/pharmacy/stock-opname(/:any)?'] = 'pharmacy/stock_opname$1';
$route['inquiry/pharmacy/stock-opname-list(/:any)?'] = 'pharmacy/stock_opname_list$1';
$route['inquiry/pharmacy/stock-opname-view(/:any)?'] = 'pharmacy/stock_opname_view$1';


$route['inquiry/helper/request-list(/:any)?'] = 'helper/request_list$1';
$route['inquiry/helper/mutation-list(/:any)?'] = 'helper/mutation_list$1';
$route['inquiry/helper/mutation-return-list(/:any)?'] = 'helper/mutation_return_list$1';
$route['inquiry/helper/stock-opname(/:any)?'] = 'helper/stock_opname$1';
$route['inquiry/helper/stock-opname-list(/:any)?'] = 'helper/stock_opname_list$1';
$route['inquiry/helper/stock-opname-view(/:any)?'] = 'helper/stock_opname_view$1';