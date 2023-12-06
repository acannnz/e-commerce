<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Pembayaran Umum
$route['cashier/general-payment'] = "general_payment";
$route['cashier/general-payment(/:any)'] = "general_payment$1";
$route['cashier/general-payment(/:any)(/:any)'] = "general_payment$1$2";

$route['cashier/general-payments/print'] = "general_payments/print_out";
$route['cashier/general-payments/print(/:any)'] = "general_payments/print_out$1";
$route['cashier/general-payments/print(/:any)(/:any)'] = "general_payments/print_out$1$2";

$route['cashier/general-payments(/:any)'] = "general_payments$1";
$route['cashier/general-payments(/:any)(/:any)'] = "general_payments$1$2";
$route['cashier/general-payments(/:any)(/:any)(/:any)'] = "general_payments$1$2$3";

//Pembayaran Obat
$route['cashier/drug-payment'] = "drug_payment";
$route['cashier/drug-payment(/:any)'] = "drug_payment$1";
$route['cashier/drug-payment(/:any)(/:any)'] = "drug_payment$1$2";

$route['cashier/drug-payments(/:any)'] = "drug_payments$1";
$route['cashier/drug-payments(/:any)(/:any)'] = "drug_payments$1$2";

// Pembayaran Outstanding
$route['cashier/outstanding-payment'] = "outstanding_payment";
$route['cashier/outstanding-payment(/:any)'] = "outstanding_payment$1";
$route['cashier/outstanding-payment(/:any)(/:any)'] = "outstanding_payment$1$2";

// Petty Cash
$route['cashier/petty-cash'] = "petty_cash";
$route['cashier/petty-cash(/:any)'] = "petty_cash$1";
$route['cashier/petty-cash(/:any)(/:any)'] = "petty_cash$1$2";

// Penerimaan Non Invoice
$route['cashier/non-invoice-receipt'] = "non_invoice_receipt";
$route['cashier/non-invoice-receipt(/:any)'] = "non_invoice_receipt$1";
$route['cashier/non-invoice-receipt(/:any)(/:any)'] = "non_invoice_receipt$1$2";

// Pengeluaran Kas Non Invoice
$route['cashier/non-invoice-cash-expense'] = "non_invoice_cash_expense";
$route['cashier/non-invoice-cash-expense(/:any)'] = "non_invoice_cash_expense$1";
$route['cashier/non-invoice-cash-expense(/:any)(/:any)'] = "non_invoice_cash_expense$1$2";

// Setoran Kas Ke Bank
$route['cashier/bank-cash-deposit'] = "bank_cash_deposit";
$route['cashier/bank-cash-deposit(/:any)'] = "bank_cash_deposit$1";
$route['cashier/bank-cash-deposit(/:any)(/:any)'] = "bank_cash_deposit$1$2";