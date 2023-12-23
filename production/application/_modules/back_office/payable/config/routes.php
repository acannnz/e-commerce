<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['payable/beginning-balance(/:any)?'] = 'beginning_balance$1';
$route['payable/beginning-balance(/:any)?(/:any)?'] = 'beginning_balance$1$2';
$route['payable/beginning-balance(/:any)?(/:any)?(/:any)?'] = 'beginning_balance$1$2$3';

$route['payable/credit-debit-note(/:any)?'] = 'credit_debit_note$1';
$route['payable/credit-debit-note(/:any)?(/:any)?'] = 'credit_debit_note$1$2';
$route['payable/credit-debit-note(/:any)?(/:any)?(/:any)?'] = 'credit_debit_note$1$2$3';

$route['payable/reports/card-payable(/:any)?'] = 'reports/card_payable$1';
$route['payable/reports/card-payable(/:any)?(/:any)?'] = 'reports/card_payable$1$2';
$route['payable/reports/card-payable(/:any)?(/:any)?(/:any)?'] = 'reports/card_payable$1$2$3';

$route['payable/reports/recap-payable(/:any)?'] = 'reports/recap_payable$1';
$route['payable/reports/recap-payable(/:any)?(/:any)?'] = 'reports/recap_payable$1$2';
$route['payable/reports/recap-payable(/:any)?(/:any)?(/:any)?'] = 'reports/recap_payable$1$2$3';
