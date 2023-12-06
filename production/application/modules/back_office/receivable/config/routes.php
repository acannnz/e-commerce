<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['receivable/beginning-balance(/:any)?'] = 'beginning_balance$1';
$route['receivable/beginning-balance(/:any)?(/:any)?'] = 'beginning_balance$1$2';
$route['receivable/beginning-balance(/:any)?(/:any)?(/:any)?'] = 'beginning_balance$1$2$3';

$route['receivable/credit-debit-note(/:any)?'] = 'credit_debit_note$1';
$route['receivable/credit-debit-note(/:any)?(/:any)?'] = 'credit_debit_note$1$2';
$route['receivable/credit-debit-note(/:any)?(/:any)?(/:any)?'] = 'credit_debit_note$1$2$3';

$route['receivable/reports/card-receivable(/:any)?'] = 'reports/card_receivable$1';
$route['receivable/reports/card-receivable(/:any)?(/:any)?'] = 'reports/card_receivable$1$2';
$route['receivable/reports/card-receivable(/:any)?(/:any)?(/:any)?'] = 'reports/card_receivable$1$2$3';

$route['receivable/reports/recap-receivable(/:any)?'] = 'reports/recap_receivable$1';
$route['receivable/reports/recap-receivable(/:any)?(/:any)?'] = 'reports/recap_receivable$1$2';
$route['receivable/reports/recap-receivable(/:any)?(/:any)?(/:any)?'] = 'reports/recap_receivable$1$2$3';



