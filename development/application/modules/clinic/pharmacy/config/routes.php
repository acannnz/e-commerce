<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$route['pharmacy/selling-view(/:any)?'] = 'pharmacy/selling_view$1';
$route['pharmacy/selling-return(/:any)?'] = 'pharmacy/selling_return$1';
$route['pharmacy/stock-out(/:any)?'] = 'stock/stock_out$1';

// Drug Payment
$route['pharmacy/drug-payment'] = "drug_payment";
$route['pharmacy/drug-payment(/:any)'] = "drug_payment$1";
$route['pharmacy/drug-payment(/:any)(/:any)'] = "drug_payment$1$2";
$route['pharmacy/drug-payments(/:any)'] = "drug_payments$1";
$route['pharmacy/drug-payments(/:any)(/:any)'] = "drug_payments$1$2";

// item usage
$route['pharmacy/item-usage'] = "item_usage";
$route['pharmacy/item-usage(/:any)'] = "item_usage$1";
$route['pharmacy/item-usage(/:any)(/:any)'] = "item_usage$1$2";

// drug usage
$route['pharmacy/reports/used-drugs'] = "reports/used_drugs";
$route['pharmacy/reports/used-drugs(/:any)'] = "reports/used_drugs$1";
$route['pharmacy/reports/used-drugs(/:any)(/:any)'] = "reports/used_drugs$1$2";

//warehouse cards
$route['pharmacy/reports/warehouse-cards'] = "reports/warehouse_cards";
$route['pharmacy/reports/warehouse-cards(/:any)'] = "reports/warehouse_cards$1";
$route['pharmacy/reports/warehouse-cards(/:any)(/:any)'] = "reports/warehouse_cards$1$2";

// recap stock
$route['pharmacy/reports/recap-stocks'] = "reports/recap_stocks";
$route['pharmacy/reports/recap-stocks(/:any)'] = "reports/recap_stocks$1";
$route['pharmacy/reports/recap-stocks(/:any)(/:any)'] = "reports/recap_stocks$1$2";

// retur penjualan
$route['pharmacy/reports/retur-penjualan'] = "reports/retur_penjualan";
$route['pharmacy/reports/retur-penjualan(/:any)'] = "reports/retur_penjualan$1";
$route['pharmacy/reports/retur-penjualan(/:any)(/:any)'] = "reports/retur_penjualan$1$2";

// stock opname
$route['pharmacy/reports/stock-opname'] = "reports/stock_opname";
$route['pharmacy/reports/stock-opname(/:any)'] = "reports/stock_opname$1";
$route['pharmacy/reports/stock-opname(/:any)(/:any)'] = "reports/stock_opname$1$2";

// recap transactions
$route['pharmacy/reports/recap-transactions'] = "reports/recap_transactions";
$route['pharmacy/reports/recap-transactions(/:any)'] = "reports/recap_transactions$1";
$route['pharmacy/reports/recap-transactions(/:any)(/:any)'] = "reports/recap_transactions$1$2";

// total sales
$route['pharmacy/reports/total-sales'] = "reports/total_sales";
$route['pharmacy/reports/total-sales(/:any)'] = "reports/total_sales$1";
$route['pharmacy/reports/total-sales(/:any)(/:any)'] = "reports/total_sales$1$2";

// drug sales
$route['pharmacy/reports/drug-sales'] = "reports/drug_sales";
$route['pharmacy/reports/drug-sales(/:any)'] = "reports/drug_sales$1";
$route['pharmacy/reports/drug-sales(/:any)(/:any)'] = "reports/drug_sales$1$2";

// drug sale patient type
$route['pharmacy/reports/drug-sale-patient-types'] = "reports/drug_sale_patient_types";
$route['pharmacy/reports/drug-sale-patient-types(/:any)'] = "reports/drug_sale_patient_types$1";
$route['pharmacy/reports/drug-sale-patient-types(/:any)(/:any)'] = "reports/drug_sale_patient_types$1$2";

// drug sale supplier
$route['pharmacy/reports/drug-sale-suppliers'] = "reports/drug_sale_suppliers";
$route['pharmacy/reports/drug-sale-suppliers(/:any)'] = "reports/drug_sale_suppliers$1";
$route['pharmacy/reports/drug-sale-suppliers(/:any)(/:any)'] = "reports/drug_sale_suppliers$1$2";

// doctor drug incentives
$route['pharmacy/reports/doctor-drug-incentives'] = "reports/doctor_drug_incentives";
$route['pharmacy/reports/doctor-drug-incentives(/:any)'] = "reports/doctor_drug_incentives$1";
$route['pharmacy/reports/doctor-drug-incentives(/:any)(/:any)'] = "reports/doctor_drug_incentives$1$2";
