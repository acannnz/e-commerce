<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// All Poli || UGD
// Warehouse card
$route['poly/reports/warehouse-cards/(inpatient|outpatient)'] = "reports/warehouse_cards/dialog/$1";
$route['poly/reports/warehouse-cards(/:any)'] = "reports/warehouse_cards$1";
$route['poly/reports/warehouse-cards(/:any)(/:any)'] = "reports/warehouse_cards$1$2";

// Recap stock
$route['poly/reports/recap-stocks/(inpatient|outpatient)'] = "reports/recap_stocks/dialog/$1";
$route['poly/reports/recap-stocks(/:any)'] = "reports/recap_stocks$1";
$route['poly/reports/recap-stocks(/:any)(/:any)'] = "reports/recap_stocks$1$2";

// Stock opname
$route['poly/reports/stock-opname/(inpatient|outpatient)'] = "reports/stock_opname/dialog/$1";
$route['poly/reports/stock-opname(/:any)'] = "reports/stock_opname$1";
$route['poly/reports/stock-opname(/:any)(/:any)'] = "reports/stock_opname$1$2";

// Unit Performance
$route['poly/reports/unit-performance/(inpatient|outpatient)'] = "reports/unit_performance/dialog/$1";
$route['poly/reports/unit-performance(/:any)'] = "reports/unit_performance$1";
$route['poly/reports/unit-performance(/:any)(/:any)'] = "reports/unit_performance$1$2";

// Patient Symptom Therapi
$route['poly/reports/patient-symptom-therapi/(inpatient|outpatient)'] = "reports/patient_symptom_therapi/dialog/$1";
$route['poly/reports/patient-symptom-therapi(/:any)'] = "reports/patient_symptom_therapi$1";
$route['poly/reports/patient-symptom-therapi(/:any)(/:any)'] = "reports/patient_symptom_therapi$1$2";

// Item Usages
$route['poly/reports/medical-records/(inpatient|outpatient)'] = "reports/medical_records/dialog/$1";
$route['poly/reports/medical-records(/:any)'] = "reports/medical_records$1";
$route['poly/reports/medical-records(/:any)(/:any)'] = "reports/medical_records$1$2";

// Item Usages
$route['poly/item-usage'] = "item_usage";
$route['poly/item-usage/(outpatient|inpatient)'] = 'item_usage/index/$1';
$route['poly/item-usage(/:any)'] = "item_usage$1";
$route['poly/item-usage(/:any)(/:any)'] = "item_usage$1$2";