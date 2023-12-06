<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//warehouse cards
$route['inventory/reports/warehouse-cards'] = "reports/warehouse_cards";
$route['inventory/reports/warehouse-cards(/:any)'] = "reports/warehouse_cards$1";
$route['inventory/reports/warehouse-cards(/:any)(/:any)'] = "reports/warehouse_cards$1$2";

// recap stock
$route['inventory/reports/recap-stocks'] = "reports/recap_stocks";
$route['inventory/reports/recap-stocks(/:any)'] = "reports/recap_stocks$1";
$route['inventory/reports/recap-stocks(/:any)(/:any)'] = "reports/recap_stocks$1$2";

// stock opname
$route['inventory/reports/stock-opname'] = "reports/stock_opname";
$route['inventory/reports/stock-opname(/:any)'] = "reports/stock_opname$1";
$route['inventory/reports/stock-opname(/:any)(/:any)'] = "reports/stock_opname$1$2";

// recap transactions
$route['inventory/reports/recap-transactions'] = "reports/recap_transactions";
$route['inventory/reports/recap-transactions(/:any)'] = "reports/recap_transactions$1";
$route['inventory/reports/recap-transactions(/:any)(/:any)'] = "reports/recap_transactions$1$2";


