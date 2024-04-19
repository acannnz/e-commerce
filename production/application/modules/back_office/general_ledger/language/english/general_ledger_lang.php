<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang['accounting:page']				= "Accounting Data";
$lang['accounting:state_label']			= 'state';

/* ACCOUNTS 
-------------------------------------------------------------------------------*/
// titles
$lang['accounts:page']           		= 'Accounts Master';
$lang['accounts:breadcrumb']           	= 'accounts';
$lang['accounts:list_heading'] 			= 'List of accounts';
$lang['accounts:modal_heading']         = 'Details';
$lang['accounts:create_heading']        = 'Setup a New accounts';
$lang['accounts:edit_heading']          = 'Editing account';
$lang['accounts:widget_heading']       	= 'List of accounts';
$lang['accounts:tree_heading']       	= 'Accounts Structure';

//lookup
$lang['accounts:account_lookup_title']  = 'Lookup Accounts';
$lang['accounts:account_lookup_helper'] = 'You can select one of account and click Apply to make changes';

// labels
$lang['accounts:component_label']		= 'Component';
$lang['accounts:group_label']			= 'Group';
$lang['accounts:sub_group_label']		= 'Sub Grup';
$lang['accounts:normal_pos_label']		= 'Normal Pos';
$lang['accounts:level_label']			= 'Level';
$lang['accounts:account_number_label']	= 'Account Number';
$lang['accounts:account_name_label']	= 'Account Name';
$lang['accounts:account_description_label']	= 'Account Desription';
$lang['accounts:currency_label']			= 'Currency';
$lang['accounts:convert_permanent_label']	= 'Convert Permanent';
$lang['accounts:integration_label']			= 'Integration';
$lang['accounts:integration_source_label']	= 'Source Integration';

$lang['accounts:cannot_delete']				= 'Cannot Delete this Account, because it has done Transaction!';
$lang['accounts:cannot_add_child']			= 'Cannot Add Child for this Account, because it already in last Level!';

/* Account Concepts
-------------------------------------------------------------------------------*/
// titles
$lang['concepts:page']           		= 'Account Concepts';
$lang['concepts:breadcrumb']          	= 'Account Concepts';
$lang['concepts:list_heading'] 			= 'List of Account Concepts';
$lang['concepts:modal_heading']       	= 'Form Account Concepts';
$lang['concepts:create_heading']      	= 'Create a New Account Concepts';
$lang['concepts:edit_heading']        	= 'Editing Account Concepts';

// labels
$lang['concepts:level_label']			= 'Level';
$lang['concepts:digit_label']			= 'Digit';
$lang['concepts:description_label']		= 'Description';
$lang['concepts:max_level_label']		= 'Max Level';
$lang['concepts:max_digit_label']		= 'Max Digit';
$lang['concepts:level_to_label']		= 'Level To';
$lang['concepts:digit_number_label']	= 'Digit Number';
$lang['concepts:updated_label']			= 'Updated';

$lang['concepts:level_digit_exceed_max']= 'Error! Details Level or Digit exceeds Maximun';

/* Account Structur
-------------------------------------------------------------------------------*/
// titles
$lang['structures:page']           		= 'Account Structures';
$lang['structures:breadcrumb']          = 'Account Structures';
$lang['structures:list_heading'] 		= 'List of Account Structures';
$lang['structures:modal_heading']       = 'Form Account Structures';
$lang['structures:create_heading']      = 'Create a New Account Structures';
$lang['structures:edit_heading']        = 'Editing Account Structures';

// labels
$lang['structures:group_name_label']	= 'Group Name';
$lang['structures:group_account_detail_label']	= 'Group Account Detail';
$lang['structures:description_label']	= 'Description';
$lang['structures:cash_label']			= 'Cash';
$lang['structures:bank_label']			= 'Bank';


/* Journal
-------------------------------------------------------------------------------*/
// titles
$lang['journals:page']           				= 'Journal Transactions';
$lang['journals:breadcrumb']        	 		= 'Journal Transactions';
$lang['journals:list_heading'] 					= 'List of Journal';
$lang['journals:modal_heading']     		  	= 'Details';
$lang['journals:create_heading']      		= 'Create Journal Transaction';
$lang['journals:edit_heading']        		= 'Editing Journal Transaction';
$lang['journals:widget_heading']      		= 'List of Journal Transaction';

//subtitles
$lang['journals:accounts_details_heading']     = 'Accounts Details';
$lang['journals:total_summary_sub']      		= 'Total Summary';

// labels
$lang['journals:journal_number_label']		= 'Journal Number';
$lang['journals:date_label']					= 'Date';
$lang['journals:notes_label']					= 'Notes';
$lang['journals:currency_label']				= 'Currency';
$lang['journals:section_label']				= 'Section';
$lang['journals:account_number_label']		= 'Account Number';
$lang['journals:account_name_label']			= 'Account Name';
$lang['journals:debit_label']					= 'Debit';
$lang['journals:credit_label']				= 'Credit';
$lang['journals:balance_label']				= 'Balance';
$lang['journals:project_label']				= 'Project';
$lang['journals:division_label']			= 'Division';

$lang['journals:save_recurring_label']		= 'Save Recurring';
$lang['journals:use_recurring_label']		= 'Use Recurring';

/* General Ledger 
-------------------------------------------------------------------------------*/
// titles
$lang['general_ledger:page']           				= 'Journal Transactions';
$lang['general_ledger:breadcrumb']        	 		= 'Journal Transactions';
$lang['general_ledger:modal_heading']     		  	= 'Details';
$lang['general_ledger:list_heading'] 				= 'List of Journal Transactions';
$lang['general_ledger:create_heading']      		= 'Journal Transaction';
$lang['general_ledger:edit_heading']        		= 'Editing Journal Transaction';
$lang['general_ledger:widget_heading']      		= 'List of Journal Transaction';

//subtitles
$lang['general_ledger:accounts_details_heading']    = 'Accounts Details';
$lang['general_ledger:total_summary_sub']      		= 'Total Summary';
$lang['general_ledger:not_found_journal'] 			= 'Journal Data Not Found !';

// labels
$lang['general_ledger:form_date_label']				= 'From';
$lang['general_ledger:till_date_label']				= 'Till';
$lang['general_ledger:account_label']				= 'Account';
$lang['general_ledger:account_number_label']		= 'Account Number';
$lang['general_ledger:account_name_label']			= 'Account Name';
$lang['general_ledger:currency_label']				= 'Currency';
$lang['general_ledger:convert_label']				= 'Convert';
$lang['general_ledger:journal_number_label']		= 'Transaction Number';
$lang['general_ledger:journal_date_label']			= 'Transaction Date';
$lang['general_ledger:journal_type_label']			= 'Journal Type';
$lang['general_ledger:beginning_balance_label']		= 'Beginning Balance';
$lang['general_ledger:ending_balance_label']		= 'Ending Balance';
$lang['general_ledger:notes_label']					= 'Description';
$lang['general_ledger:debit_label']					= 'Debit';
$lang['general_ledger:credit_label']				= 'Credit';
$lang['general_ledger:balance_label']				= 'Balance';

$lang['general_ledger:empty_currency_alert']		= 'Account --> %d - %s Not has Currency';
$lang['general_ledger:empty_rate_currency']			= 'Rate Currency not available!  \n Input rate Currency first!';
$lang['general_ledger:proceed_confirm']				= 'This initial balance process will take a while.  \n Are you sure you want to continue?';

/* General 
-------------------------------------------------------------------------------*/
// titles
$lang['general:page']           			= 'Journal Transactions';
$lang['general:breadcrumb']        	 		= 'Journal Transactions';
$lang['general:modal_heading']     		  	= 'Details';
$lang['general:list_heading'] 				= 'List of Journal Transactions';
$lang['general:create_heading']      		= 'Journal Transaction';
$lang['general:edit_heading']        		= 'Editing Journal Transaction';
$lang['general:widget_heading']      		= 'List of Journal Transaction';

//subtitles
$lang['general:accounts_details_heading']   = 'Accounts Details';
$lang['general:total_summary_sub']      	= 'Total Summary';
$lang['general:not_found_journal'] 			= 'Journal Data Not Found !';

// labels
$lang['general:form_date_label']				= 'From';
$lang['general:till_date_label']				= 'Till';
$lang['general:account_label']				= 'Account';
$lang['general:account_number_label']		= 'Account Number';
$lang['general:account_name_label']			= 'Account Name';
$lang['general:currency_label']				= 'Currency';
$lang['general:convert_label']				= 'Convert';
$lang['general:journal_number_label']		= 'Transaction Number';
$lang['general:journal_date_label']			= 'Transaction Date';
$lang['general:journal_type_label']			= 'Journal Type';
$lang['general:beginning_balance_label']		= 'Beginning Balance';
$lang['general:ending_balance_label']		= 'Ending Balance';
$lang['general:notes_label']					= 'Description';
$lang['general:debit_label']					= 'Debit';
$lang['general:credit_label']				= 'Credit';
$lang['general:balance_label']				= 'Balance';

$lang['general:empty_currency_alert']		= 'Account --> %d - %s Not has Currency';
$lang['general:empty_rate_currency']			= 'Rate Currency not available!  \n Input rate Currency first!';
$lang['general:proceed_confirm']				= 'This initial balance process will take a while.  \n Are you sure you want to continue?';


/* Beginning Balance
-------------------------------------------------------------------------------*/
// titles
$lang['beginning_balances:page']           				= 'Beginning Balances System';
$lang['beginning_balances:breadcrumb']        	 		= 'Beginning Balances System';
$lang['beginning_balances:list_heading'] 				= 'List of Beginning Balances System';
$lang['beginning_balances:setup_rate_currency_heading'] = 'Setup Rate Currency';
$lang['beginning_balances:create_heading']      		= 'Beginning Balances System';

//subtitles
$lang['beginning_balances:total_summary_sub']      		= 'Total Summary';
$lang['beginning_balances:aktiva_sub']      			= 'Aktiva';
$lang['beginning_balances:pasiva_sub']      			= 'Pasiva';

// labels
$lang['beginning_balances:date_label']					= 'Date';
$lang['beginning_balances:currency_label']				= 'Currency';
$lang['beginning_balances:account_number_label']		= 'Account Number';
$lang['beginning_balances:account_name_label']			= 'Account Name';
$lang['beginning_balances:value_label']					= 'Value';
$lang['beginning_balances:balance_label']				= 'Balance';
$lang['beginning_balances:not_balance_label']			= 'Not Balance';
$lang['beginning_balances:description_label']			= 'Description';

$lang['beginning_balances:setup_rate_currency'] 		= 'Setup Rate Currency';
$lang['beginning_balances:empty_currency_alert']		= 'Account --> %d - %s Not has Currency';
$lang['beginning_balances:empty_rate_currency']			= 'Rate Currency not available!  \n Input rate Currency first!';
$lang['beginning_balances:proceed_confirm']				= 'This initial balance process will take a while.  \n Are you sure you want to continue?';
$lang['beginning_balances:existing_next_monthly_posted']= 'You can not continue the initial balance process for %s, Since the transaction for %s period has closed the book.';
$lang['beginning_balances:existing_transaction'] 		= 'Initial balance can not be inputted with %s Date, because there is already a transaction before that date.';

/* Cash Flow
-------------------------------------------------------------------------------*/
// titles
$lang['cash_flow:page']           		= 'Cash Flow';
$lang['cash_flow:breadcrumb']         	= 'Cash Flow';
$lang['cash_flow:list_heading'] 		= 'List Cash Flow';
$lang['cash_flow:modal_heading']       = 'Form Cash Flow';
$lang['cash_flow:setup_heading']      = 'Setup Cash Flow';
$lang['cash_flow:account_heading']      = 'Cash Flow Account';
$lang['cash_flow:report_heading']      = 'Cash Flow Report';

// labels
$lang['cash_flow:group_label']	= 'Group';
$lang['cash_flow:subgroup_label'] = 'Sub Group';
$lang['cash_flow:account_label']	= 'Account';
$lang['cash_flow:account_number_label']	= 'Account Number';
$lang['cash_flow:account_name_label'] = 'Account Name';
$lang['cash_flow:debt_label']	= 'Debit';
$lang['cash_flow:credit_label'] = 'Credit';
$lang['cash_flow:Normal_pos_label'] = 'Normal Pos';
$lang['cash_flow:date_label'] = 'Date';
$lang['cash_flow:priod_label'] = 'Priod';

$lang['cash_flow:cash_flow_report'] = 'Cash Flow Report';
$lang['cash_flow:cash_flow_detail_report'] = 'Cash Flow Detail Report';
$lang['cash_flow:cash_flow_transaction_report'] = 'Cash Flow Transaction Report';

/* Closing
-------------------------------------------------------------------------------*/
// titles
$lang['closing:page']           			= 'Closing';
$lang['closing:breadcrumb']        	 		= 'Closing';
$lang['closing:cancel_page']           		= 'Cancel Closing';
$lang['closing:cancel_breadcrumb']        	= 'Cancel Closing';
$lang['closing:list_heading'] 				= 'List of Closing';
$lang['closing:modal_heading']     		  	= 'Details';
$lang['closing:create_heading']      		= 'Closing';

// labels
$lang['closing:date_label']					= 'Date';
$lang['closing:username_label']				= 'Username';
$lang['closing:password_label']				= 'Password';
$lang['closing:last_closing_label']			= 'Last Closing';

$lang['closing:payable_module']				= 'Payable Module';
$lang['closing:receivable_module']			= 'Receivable Module';

$lang['closing:process_success']			= 'Close Book Process Success!!';
$lang['closing:prosess_failure']			= 'Close Book Proces Failure!';
$lang['closing:cancel_process_success']		= 'Cancel Close Book Process Success!!';
$lang['closing:cancel_prosess_failure']		= 'Cancel Close Book Proces Failure!';
$lang['closing:closing_confirm']			= 'You sure want to Closing in the period : %s ? After Closing, the transaction in that period can not be edited again. Are you sure you want to continue?';
$lang['closing:closing_cancel_confirm']		= 'Are You sure want to Cancelling Closing in the period : %s ?';
$lang['closing:unsetup_cash_flow']			= 'The close book process can not be performed. Because there is still cash flow that has not been set up! Complete the cash flow setup first.';
$lang['closing:setup_cash_flow_confirm']	= 'Do you want to see account details that have not been setup yet?';
$lang['closing:unclosing_payabale_receivable'] = 'has not closed the book. Perform a Close Book on each module first.';
$lang['closing:unposted_general_cashier'] 	= "There is still an unposted General Cashier data. Please do post data from General Cashier.";		
$lang['closing:transaction_already_posted'] = "There is an incorrect transaction on Transaction with No Evidence %s inputted on at %s it has been done close the book.";
$lang['closing:already_closing']			= "Already been done closing the books for the period: %s.";
$lang['closing:unclosing_previous_month']= "You can not close books for this period. Because the previous period has not done close book.";
$lang['closing:consolidation_cooperation_data'] = 'You can not cancel close book for that period. Because there is already a consolidation process with Cooperation';
$lang['closing:already_closing_next_month'] = 'You can not cancel close book for that period. Because there is already a closing process for the next period.';

/* Income Loss Setup
-------------------------------------------------------------------------------*/
// titles
$lang['income_loss_setup:page']           		= 'Income Loss Setup';
$lang['income_loss_setup:breadcrumb']          	= 'Income Loss Setup';
$lang['income_loss_setup:list_heading'] 		= 'List of Income Loss Setup';
$lang['income_loss_setup:modal_heading']       	= 'Details';
$lang['income_loss_setup:setup_heading']      	= 'Setup a New Income Loss';
$lang['income_loss_setup:edit_heading']        	= 'Editing Income Loss';

// labels
$lang['income_loss_setup:account_label']		= 'Account';
$lang['income_loss_setup:account_number_label']	= 'Account Number';
$lang['income_loss_setup:account_name_label']	= 'Account Name';
$lang['income_loss_setup:type_label']			= 'Type';
$lang['income_loss:quarterly']			= 'Quarterly';
$lang['income_loss_setup:used_warning']			= 'This type of Income and Loss has been used by the Account: %s - %s';

/* Income Loss
-------------------------------------------------------------------------------*/
// titles
$lang['income_loss:page']           		= 'Income Loss';
$lang['income_loss:breadcrumb']         	= 'Income Loss';
$lang['income_loss:list_heading'] 			= 'Income Loss List';
$lang['income_loss:modal_heading']       = 'Income Loss Form';
$lang['income_loss:create_heading']      = 'Create a New Income Loss Baru';
$lang['income_loss:edit_heading']        = 'Edit Income Loss';
$lang['income_loss:monthly_page']      	= 'Monthly Income Loss';

// labels
$lang['income_loss:excel_title']			= 'Explanation of Balance Period %s';
$lang['income_loss:explanation_balance_label'] = 'Explanation of Balance';
$lang['income_loss:period']				= 'Period';
$lang['income_loss:period_label']		= 'Per: %s';
$lang['income_loss:in_rupiah_label']		= '(In Rupiah)';
$lang['income_loss:description_label']		= 'Description';
$lang['income_loss:value_label']			= 'Value';


/* Balance Sheets
-------------------------------------------------------------------------------*/
// titles
$lang['balance_sheets:page']           		= 'Balance Sheets';
$lang['balance_sheets:breadcrumb']         	= 'Balance Sheets';
$lang['balance_sheets:list_heading'] 		= 'Balance Sheets List';
$lang['balance_sheets:modal_heading']       = 'Balance Sheets Form';
$lang['balance_sheets:create_heading']      = 'Create a New Balance Sheets Baru';
$lang['balance_sheets:edit_heading']        = 'Edit Balance Sheets';
$lang['balance_sheets:trial_balance_heading']= 'Trial Balance';

// labels
$lang['balance_sheets:excel_title']			= 'Explanation of Balance Period %s';
$lang['balance_sheets:explanation_balance_label'] = 'Explanation of Balance';
$lang['balance_sheets:period']				= 'Period';
$lang['balance_sheets:period_label']		= 'Per: %s';
$lang['balance_sheets:in_rupiah_label']		= '(In Rupiah)';
$lang['balance_sheets:activa_label']		= 'Activa';
$lang['balance_sheets:pasiva_label']		= 'Pasiva';
$lang['balance_sheets:activa_total_label']		= 'Total Activa';
$lang['balance_sheets:pasiva_total_label']		= 'Total Pasiva';


// Gender
$lang['gender:male']			= 'Male';
$lang['gender:female']			= 'Female';
// Age
$lang['age:years']				= '%s year(s)';
$lang['age:months']				= '%d month(s)';
$lang['age:days']				= '%d day(s)';
$lang['age:details']			= '%d year(s) %d month(s) %d day(s)';
// Religion
$lang['relegion:hindu']			= 'Hindu';
$lang['relegion:budha']			= 'Budha';
// Profile
$lang['profile:completed']		= 'Profile complete on <strong>%d%%</strong>';

// ID Type
$lang['id_type:ktp']				= 'Kartu Tanda Penduduk';
$lang['id_type:sim']				= 'Surat Izin Mengemudi';
$lang['id_type:kartu_pelajar']		= 'Kartu Mahasiswa';
$lang['id_type:kartu_mahasiswa']	= 'Kartu Mahasiswa';

// Referer
$lang['referer:self']				= 'By Self';
$lang['referer:vendor']				= 'Reference by Vendor';
$lang['referer:patient_hc']			= 'Reference by HC Patient';
$lang['referer:patient_iks']		= 'Reference by IKS Patient';
$lang['referer:patient_general']	= 'Reference by General Patient';
$lang['referer:patient_family']		= 'Reference by Family';
$lang['referer:patient_friend']		= 'Reference by Friend';








