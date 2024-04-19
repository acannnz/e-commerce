<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//$route['general-ledger'] = 'general_ledger';
/*$route['general-ledger/beginning-balance'] = 'beginning_balance';
$route['general-ledger/beginning-balance(/:any)?'] = 'beginning_balance$1';
$route['general-ledger/beginning-balance(/:any)?(/:any)?'] = 'beginning_balance$1$2';
$route['general-ledger/beginning-balance(/:any)?(/:any)?(/:any)?'] = 'beginning_balance$1$2$3';
*/
// General Ledger - Journal Details
$route['accounting/general_ledger_journals'] = 'general_ledger/journals/index';
$route['accounting/general_ledger_journals(/:any)?(/:any)?'] = 'general_ledger/journals$1$2';
