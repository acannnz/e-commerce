<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
//$route['translate_uri_dashes'] = TRUE;
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['set-medics(/:any)?'] = 'auth/medics$1';
$route['set-pharmacy(/:any)?'] = 'auth/pharmacy$1';
$route['landing(/:any)?'] = 'welcome/landing/index$1';

$route['general-ledger'] = 'general_ledger';

$route['general-ledger/beginning-balance'] = 'general_ledger/beginning_balance';
$route['general-ledger/beginning-balance(/:any)?'] = 'general_ledger/beginning_balance$1';
$route['general-ledger/beginning-balance(/:any)?(/:any)?'] = 'general_ledger/beginning_balance$1$2';
$route['general-ledger/beginning-balance(/:any)?(/:any)?(/:any)?'] = 'general_ledger/beginning_balance$1$2$3';

$route['general-ledger/cash-flow'] = 'general_ledger/cash_flow/setup';
$route['general-ledger/cash-flow(/:any)?'] = 'general_ledger/cash_flow$1';
$route['general-ledger/cash-flow(/:any)?(/:any)?'] = 'general_ledger/cash_flow$1$2';
$route['general-ledger/cash-flow(/:any)?(/:any)?(/:any)?'] = 'general_ledger/cash_flow$1$2$3';

$route['general-ledger/account/income-loss-setup'] = 'general_ledger/account/income_loss_setup';
$route['general-ledger/account/income-loss-setup(/:any)?'] = 'general_ledger/account/income_loss_setup$1';
$route['general-ledger/account/income-loss-setup(/:any)?(/:any)?'] = 'general_ledger/account/income_loss_setup$1$2';
$route['general-ledger/account/income-loss-setup(/:any)?(/:any)?(/:any)?'] = 'general_ledger/account/income_loss_setup$1$2$3';

$route['general-ledger/income-loss'] = 'general_ledger/income_loss';
$route['general-ledger/income-loss(/:any)?'] = 'general_ledger/income_loss$1';
$route['general-ledger/income-loss(/:any)?(/:any)?'] = 'general_ledger/income_loss$1$2';
$route['general-ledger/income-loss(/:any)?(/:any)?(/:any)?'] = 'general_ledger/income_loss$1$2$3';

$route['general-ledger/balance-sheet'] = 'general_ledger/balance_sheet';
$route['general-ledger/balance-sheet(/:any)?'] = 'general_ledger/balance_sheet$1';
$route['general-ledger/balance-sheet(/:any)?(/:any)?'] = 'general_ledger/balance_sheet$1$2';
$route['general-ledger/balance-sheet(/:any)?(/:any)?(/:any)?'] = 'general_ledger/balance_sheet$1$2$3';

$route['general-ledger/close-book'] = 'general_ledger/close_book';
$route['general-ledger/close-book(/:any)?'] = 'general_ledger/close_book$1';
$route['general-ledger/close-book(/:any)?(/:any)?'] = 'general_ledger/close_book$1$2';
$route['general-ledger/close-book(/:any)?(/:any)?(/:any)?'] = 'general_ledger/close_book$1$2$3';

$route['general-ledger(/:any)?'] = 'general_ledger$1';
$route['general-ledger(/:any)?(/:any)?'] = 'general_ledger$1$2';
$route['general-ledger(/:any)?(/:any)?(/:any)?'] = 'general_ledger$1$2$3';
$route['general-ledger(/:any)?(/:any)?(/:any)?(/:any)?'] = 'general_ledger$1$2$3$4';


# General Cashier
$route['general-cashier/cash-bank-income/non-invoices'] = 'general_cashier/cash_bank_income/non_invoices';
$route['general-cashier/cash-bank-income/non-invoices(/:any)?'] = 'general_cashier/cash_bank_income/non_invoices$1';
$route['general-cashier/cash-bank-income/non-invoices(/:any)?(/:any)?'] = 'general_cashier/cash_bank_income/non_invoices$1$2';
$route['general-cashier/cash-bank-income/non-invoices(/:any)?(/:any)?(/:any)?'] = 'general_cashier/cash_bank_income/non_invoices$1$2$3';

$route['general-cashier/cash-bank-income(/:any)?'] = 'general_cashier/cash_bank_income$1';
$route['general-cashier/cash-bank-income(/:any)?(/:any)?'] = 'general_cashier/cash_bank_income$1$2';
$route['general-cashier/cash-bank-income(/:any)?(/:any)?(/:any)?'] = 'general_cashier/cash_bank_income$1$2$3';

$route['general-cashier/cash-bank-expense/non-vouchers'] = 'general_cashier/cash_bank_expense/non_vouchers';
$route['general-cashier/cash-bank-expense/non-vouchers(/:any)?'] = 'general_cashier/cash_bank_expense/non_vouchers$1';
$route['general-cashier/cash-bank-expense/non-vouchers(/:any)?(/:any)?'] = 'general_cashier/cash_bank_expense/non_vouchers$1$2';
$route['general-cashier/cash-bank-expense/non-vouchers(/:any)?(/:any)?(/:any)?'] = 'general_cashier/cash_bank_expense/non_vouchers$1$2$3';

$route['general-cashier/cash-bank-expense(/:any)?'] = 'general_cashier/cash_bank_expense$1';
$route['general-cashier/cash-bank-expense(/:any)?(/:any)?'] = 'general_cashier/cash_bank_expense$1$2';
$route['general-cashier/cash-bank-expense(/:any)?(/:any)?(/:any)?'] = 'general_cashier/cash_bank_expense$1$2$3';

$route['general-cashier/cash-bank-mutation?'] = 'general_cashier/cash_bank_mutation';
$route['general-cashier/cash-bank-mutation(/:any)?'] = 'general_cashier/cash_bank_mutation$1';
$route['general-cashier/cash-bank-mutation(/:any)?(/:any)?'] = 'general_cashier/cash_bank_mutation$1$2';
$route['general-cashier/cash-bank-mutation(/:any)?(/:any)?(/:any)?'] = 'general_cashier/cash_bank_mutation$1$2$3';

$route['general-cashier(/:any)?'] = 'general_cashier$1';
$route['general-cashier(/:any)?(/:any)?'] = 'general_cashier$1$2';
$route['general-cashier(/:any)?(/:any)?(/:any)?'] = 'general_cashier$1$2$3';
$route['general-cashier(/:any)?(/:any)?(/:any)?(/:any)?'] = 'general_cashier$1$2$3$4';



/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
//$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
//$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
