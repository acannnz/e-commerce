<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang['payable:page']= "Payable";

/* TYPES 
-------------------------------------------------------------------------------*/
// titles
$lang['types:page']           			= 'Payable Types';
$lang['types:breadcrumb']           	= 'Payable Types';
$lang['types:modal_heading']        	= 'Details';
$lang['types:list_heading']       		= 'List of Payable Types';
$lang['types:create_heading']       	= 'Create a New Payable Type';
$lang['types:edit_heading']         	= 'Editing Payable Type';

// labels
$lang['types:code_label']				= 'Code';
$lang['types:type_label']				= 'Type Name';
$lang['types:account_label']			= 'Account';
$lang['types:account_number_label']		= 'Account Number';
$lang['types:account_name_label']		= 'Account Name';
$lang['types:default_label']			= 'Default';
$lang['types:state_label']				= 'Status';
$lang['types:updated_label']			= 'Updated';

/* Vouchers
-------------------------------------------------------------------------------*/
// titles
$lang['vouchers:page']           		= 'Vouchers';
$lang['vouchers:breadcrumb']           	= 'Vouchers';
$lang['vouchers:modal_heading']        	= 'Details';
$lang['vouchers:list_heading']       	= 'List of Vouchers';
$lang['vouchers:create_heading']       	= 'Create a New Voucher';
$lang['vouchers:edit_heading']         	= 'Editing Voucher';
$lang['vouchers:cancel_title']      		= 'Cancel Voucher';

// subtitle
$lang['vouchers:voucher_detail_subtitle'] 	= "Voucher Details";
$lang['vouchers:mutation_history_subtitle'] = "Mutation History";

// labels
$lang['vouchers:voucher_number_label']	= 'Voucher Number';
$lang['vouchers:factur_number_label']	= 'Factur Number';
$lang['vouchers:supplier_label']		= 'Supplier';
$lang['vouchers:date_label']			= 'Date';
$lang['vouchers:due_date_label']		= 'Due Date';
$lang['vouchers:value_label']			= 'Value';
$lang['vouchers:remain_label']			= 'Remain';
$lang['vouchers:description_label']		= 'Description';
$lang['vouchers:debit_label']			= 'Debit';
$lang['vouchers:credit_label']			= 'Credit';
$lang['vouchers:state_label']			= 'State';
$lang['vouchers:updated_label']			= 'Updated';
$lang['vouchers:mutation_total_label']	= 'Mutation Total';
$lang['vouchers:mutation_remain_label']	= 'Mutation Remain';
$lang['vouchers:username_label']		= 'Username';
$lang['vouchers:password_label']		= 'Password';
$lang['vouchers:no_label']				= 'No';
$lang['vouchers:to_label']				= 'To';

$lang['vouchers:periode_label']			= 'Periode';
$lang['vouchers:till_label']			= 'Till';
$lang['vouchers:view_label']			= 'View';
$lang['vouchers:cancel_voucher_label']	= 'Cancel Voucher';
$lang['vouchers:find_voucher_list_label']= 'Find Voucher List';
$lang['vouchers:supplier_lookup_title'] = 'Lookup Data Supplier';
$lang['vouchers:factur_lookup_title'] 	= 'Lookup Data Factur';

$lang['vouchers:close_book_data']			= 'This data is already Close Book!';
$lang['vouchers:posted_data']				= 'This data is already Posted!';
$lang['vouchers:cancel_data']				= 'This data is already Cancelled!';

$lang['vouchers:supplier_not_selected']		= 'Supplier not selected! \n Please select the Supplier first.';
$lang['vouchers:factur_already_selected']	= 'This Factur: %s is already selected!';
$lang['vouchers:details_cannot_empty']		= 'Details Voucher cannot empty!';
$lang['vouchers:transaction_date_incorret']	= 'Voucher Date (%s) must not be before Factur Date (%s)!';
$lang['vouchers:already_closing_period']	= 'The transaction can not continue. Because there is already Closing in the period: %s';
$lang['vouchers:already_mutation']			= 'The Voucher can not be Canceled because it already exists Mutation!';
$lang['vouchers:cancel_voucher']			= 'Are you sure to cancel this Voucher ?';


/* Facturs
-------------------------------------------------------------------------------*/
// titles
$lang['facturs:page']           			= 'Factur Payable';
$lang['facturs:breadcrumb']        	 		= 'Factur Payable';
$lang['facturs:modal_heading']     		  	= 'Details Factur';
$lang['facturs:create_heading']      		= 'Create Factur Payable';
$lang['facturs:edit_heading']        		= 'Editing Factur Payable';
$lang['facturs:widget_heading']      		= 'List of Factur Payable';

$lang['facturs:cancel_title']      			= 'Cancel Factur';

//subtitles
$lang['facturs:accounts_details_heading']   = 'Accounts Details';
$lang['facturs:total_summary_sub']      	= 'Total Summary';

// labels
$lang['facturs:factur_number_label']		= 'Factor Number';
$lang['facturs:voucher_number_label']		= 'Voucher Number';
$lang['facturs:date_label']					= 'Date';
$lang['facturs:due_date_label']				= 'Due Date';
$lang['facturs:description_label']			= 'Description';
$lang['facturs:currency_label']				= 'Currency';
$lang['facturs:section_label']				= 'Section';
$lang['facturs:account_number_label']		= 'Account Number';
$lang['facturs:account_name_label']			= 'Account Name';
$lang['facturs:normal_pos_label']			= 'Normal Pos';
$lang['facturs:value_label']				= 'Value';
$lang['facturs:remain_label']				= 'Remain';
$lang['facturs:qty_label']					= 'Qty';
$lang['facturs:supplier_label']				= 'Supplier';
$lang['facturs:card_number_label']			= 'Card Number';
$lang['facturs:diagnosis_label']			= 'Diagnosis';
$lang['facturs:username_label']				= 'Username';
$lang['facturs:password_label']				= 'Password';

$lang['facturs:periode_label']				= 'Periode';
$lang['facturs:till_label']					= 'Till';
$lang['facturs:cancel_factur_label']		= 'Cancel Factur';
$lang['facturs:find_factur_list_label']		= 'Find Factur List';
$lang['facturs:factur_label']				= 'Factur';
$lang['facturs:to_label']					= 'To';
$lang['facturs:number_label']				= 'Number';
$lang['facturs:currency_label']				= 'Currency';
$lang['facturs:no_label']					= 'No';
$lang['facturs:transaction_description_label'] = 'Transaction Description';
$lang['facturs:amount_label']				= 'Amount';
$lang['facturs:total_label']				= 'Total';
$lang['facturs:note_label']					= 'Note';
$lang['facturs:spelled_label']				= 'Spelled';

$lang['facturs:close_book_data']			= 'This data is already Close Book!';
$lang['facturs:posted_data']				= 'This data is already Posted!';
$lang['facturs:cancel_data']				= 'This data is already Cancelled!';
$lang['facturs:has_debit_credit_note']		= 'Cancel Factur failed! This Factur has Credit Debit Notes!';
$lang['facturs:already_closing_period']		= 'The transaction can not be continued, Because Closing has been done in the period: %s!';
$lang['facturs:already_created_vouchers']	= 'The Factur can not be edited because an Voucher has been created!';
$lang['facturs:already_posted']				= 'The Factur can not be edited, because it\'s already posted to GL!';

$lang['facturs:value_not_match']			= 'Factur value cannot be 0 or minus!';
$lang['facturs:details_cannot_empty']		= 'Details account cannot empty!';
$lang['facturs:section_details_cannot_empty']= 'Section Details account cannot empty!';
$lang['facturs:cancel_factur']				= 'Are you sure to cancel this factur ?';
$lang['facturs:cannot_delete_close_data']	= 'Cannot cancel close book data!';

/* Credit Debit Notes
-------------------------------------------------------------------------------*/
// titles
$lang['credit_debit_notes:page']           			= 'Credit Debit Note Payable';
$lang['credit_debit_notes:breadcrumb']        	 	= 'Credit Debit Note Payable';
$lang['credit_debit_notes:modal_heading']     		= 'Details Credit Debit Note';
$lang['credit_debit_notes:create_heading']      	= 'Create Credit Debit Note Payable';
$lang['credit_debit_notes:edit_heading']        	= 'Editing Credit Debit Note Payable';
$lang['credit_debit_notes:widget_heading']      	= 'List of Credit Debit Note Payable';

$lang['credit_debit_notes:delete_title']      		= 'Delete Credit Debit Note';

//subtitles
$lang['credit_debit_notes:accounts_details_heading']= 'Accounts Details';
$lang['credit_debit_notes:total_summary_sub']      	= 'Total Summary';

// labels
$lang['credit_debit_notes:evidence_number_label']	= 'Evidence Number';
$lang['credit_debit_notes:voucher_number_label']	= 'Voucher Number';
$lang['credit_debit_notes:factur_number_label']		= 'Factur Number';
$lang['credit_debit_notes:date_label']				= 'Date';
$lang['credit_debit_notes:due_date_label']			= 'Due Date';
$lang['credit_debit_notes:description_label']		= 'Description';
$lang['credit_debit_notes:currency_label']			= 'Currency';
$lang['credit_debit_notes:section_label']			= 'Section';
$lang['credit_debit_notes:account_label']			= 'Account';
$lang['credit_debit_notes:account_number_label']	= 'Account Number';
$lang['credit_debit_notes:account_name_label']		= 'Account Name';
$lang['credit_debit_notes:normal_pos_label']		= 'Normal Pos';
$lang['credit_debit_notes:original_value_label']	= 'Original Value';
$lang['credit_debit_notes:balance_label']			= 'Balance';
$lang['credit_debit_notes:increase_label']			= 'Increase';
$lang['credit_debit_notes:decrease_label']			= 'Decrease';
$lang['credit_debit_notes:value_label']				= 'Value';
$lang['credit_debit_notes:remain_label']			= 'Remain';
$lang['credit_debit_notes:qty_label']				= 'Qty';
$lang['credit_debit_notes:supplier_label']			= 'Supplier';
$lang['credit_debit_notes:project_label']			= 'Project';
$lang['credit_debit_notes:division_label']			= 'Division';

$lang['credit_debit_notes:periode_label']			= 'Periode';
$lang['credit_debit_notes:till_label']				= 'Till';
$lang['credit_debit_notes:cancel_factur_label']		= 'Cancel Credit Debit Note';
$lang['credit_debit_notes:find_credit_debit_note_list_label']	= 'Find Credit Debit Note List';
$lang['credit_debit_notes:search_transactions_label']= 'Search Transactions';
$lang['credit_debit_notes:delete_reasons_label']	= 'Delete Reasons';

$lang['credit_debit_notes:close_book_data']			= 'This data is already Close Book!';
$lang['credit_debit_notes:posted_data']				= 'This data is already Posted!';
$lang['credit_debit_notes:cancel_data']				= 'This data is already Cancelled!';

$lang['credit_debit_notes:supplier_not_selected']	= 'Supplier not selected! \n Please select the Supplier first.';
$lang['credit_debit_notes:value_not_match']			= 'Credit Debit Note value cannot be 0 or minus!';
$lang['credit_debit_notes:details_cannot_empty']	= 'Details account cannot empty!';
$lang['credit_debit_notes:delete_credit_debit_note']= 'Are you sure to delete this Credit Debit Note ?';
$lang['credit_debit_notes:cannot_delete_close_data']= 'Cannot cancel close book data!';
$lang['credit_debit_notes:transaction_date_incorret']	= 'Nota Date must not be before Voucher Date, Voucher: %s!';
$lang['credit_debit_notes:voucher_not_selected']	= 'The transaction can not be continued, Because There is no selected Voucher!';
$lang['credit_debit_notes:already_closing_period']	= 'The transaction can not be continued, Because Closing has been done in the period: %s!';
$lang['credit_debit_notes:empty_increase_decrease']	= 'The transaction can not be continued, Because the Decrease or Increase value has not been inputted in Voucher: %s!';
$lang['credit_debit_notes:decrease_value_exceed']	= 'The transaction can not proceed, Because the Decrease Value exceeds the Remain Value in Voucher: %s!';
$lang['credit_debit_notes:simultaneously_increase_decrease']= 'The transaction can not be continued, Because it can not do Increase and Decrease simultaneously!';


/* Beginning Balance
-------------------------------------------------------------------------------*/
// titles
$lang['beginning_balances:page']           				= 'Beginning Balances Payable';
$lang['beginning_balances:list_heading']       			= 'List of Beginning Balances Payable ';
$lang['beginning_balances:breadcrumb']        	 		= 'Beginning Balances Payable';
$lang['beginning_balances:modal_heading']     		  	= 'Details';
$lang['beginning_balances:create_heading']      		= 'Beginning Balances Payable';

//subtitles
$lang['beginning_balances:total_summary_sub']      		= 'Total Summary';

// labels
$lang['beginning_balances:payable_type_label']			= 'Payable Type';
$lang['beginning_balances:date_label']					= 'Date';
$lang['beginning_balances:currency_label']				= 'Currency';
$lang['beginning_balances:supplier_label']				= 'Supplier';
$lang['beginning_balances:supplier_code_label']			= 'Supplier Code';
$lang['beginning_balances:supplier_name_label']			= 'Supplier Name';
$lang['beginning_balances:value_label']					= 'Value';
$lang['beginning_balances:division_name_label']			= 'Division';
$lang['beginning_balances:project_name_label']			= 'Project';

$lang['beginning_balances:paid_payable']				= 'This Payable is already paid! please cancel the payment if you want continue this proses!';
$lang['beginning_balances:close_book']					= 'This Payable is already Close Book! please cancel close book if you want continue this proses!';
$lang['beginning_balances:identical_posted']			= 'This Supplier and Type is Already in entry. Please entry other data!';

/* Postings
-------------------------------------------------------------------------------*/
// titles
$lang['postings:page']           			= 'Posting Payable';
$lang['postings:cancel_page']           	= 'Cancel Posting Payable';
$lang['postings:breadcrumb']        	 	= 'Posting Payable';
$lang['postings:modal_heading']     		= 'Details Payable';
$lang['postings:create_heading']      		= 'Posting Payable';
$lang['postings:widget_heading']      		= 'List of Factur Payable';

$lang['postings:cancel_title']      		= 'Cancel Posting';

//subtitles
$lang['postings:accounts_details_heading']  = 'Accounts Details';
$lang['postings:total_summary_sub']      	= 'Total Summary';

// labels
$lang['postings:posting_number_label']		= 'Evidence Number';
$lang['postings:voucher_number_label']		= 'Voucher Number';
$lang['postings:date_label']				= 'Date';
$lang['postings:due_date_label']			= 'Due Date';
$lang['postings:description_label']			= 'Description';
$lang['postings:currency_label']			= 'Currency';
$lang['postings:section_label']				= 'Section';
$lang['postings:account_number_label']		= 'Account Number';
$lang['postings:account_name_label']		= 'Account Name';
$lang['postings:normal_pos_label']			= 'Normal Pos';
$lang['postings:value_label']				= 'Value';
$lang['postings:remain_label']				= 'Remain';
$lang['postings:qty_label']					= 'Qty';
$lang['postings:supplier_label']			= 'Supplier';
$lang['postings:username_label']			= 'Username';
$lang['postings:password_label']			= 'Password';

$lang['postings:periode_label']				= 'Periode';
$lang['postings:till_label']				= 'Till';
$lang['postings:cancel_posting_label']		= 'Cancel Factur';
$lang['postings:find_posting_list_label']	= 'Find Data';

$lang['postings:posting_successfully']		= 'Posting data successfull!';
$lang['postings:posting_cancel_successfully']= 'Cancel Posting data successfully!';
$lang['postings:value_not_match']			= 'Factur value cannot be 0 or minus!';
$lang['postings:no_data_selected']			= 'No data selected. Please select at least one data!';
$lang['postings:empty_posting_data']		= 'The transaction can not be continued, Because no Posting data is selected!';
$lang['postings:posting_confirm']			= 'Are you sure to posting this data?';
$lang['postings:cancel_posting_confirm']	= 'Are you sure to cancel this posting data?';
$lang['postings:cannot_delete_close_data']	= 'Cannot cancel close book data!';
$lang['postings:posting_failed']			= 'Error Occurred! Failed posting data!';
$lang['postings:journal_close_book']		= 'Failed Cancel Posting! Evidence Number <b>%s</b> already closed book in Journal GL!';

/* Closing
-------------------------------------------------------------------------------*/
// titles
$lang['closing:page']           			= 'Closing';
$lang['closing:breadcrumb']        	 		= 'Closing';
$lang['closing:cancel_page']           		= 'Cancel Closing';
$lang['closing:cancel_breadcrumb']        	= 'Cancel Closing';
$lang['closing:modal_heading']     		  	= 'Details';
$lang['closing:create_heading']      		= 'Closing';

// labels
$lang['closing:date_label']					= 'Date';
$lang['closing:last_closing_label']		= 'Last Closing';
$lang['closing:username_label']				= 'Username';
$lang['closing:password_label']				= 'Password';

$lang['closing:closing_confirm']			= 'Are You sure want to Closing in the period : %s ? After Closing, the transaction in that period can not be edited again. Are you sure you want to continue?';
$lang['closing:closing_cancel_confirm']		= 'Are You sure want to Cancelling Closing in the period : %s ?';
$lang['closing:un_posting_data']			= 'Closing failed! There are still an factur or voucher not posted';
$lang['closing:un_posting_logistic_data']	= 'The Closing can not be done, as there are still transactions on LOGISTIC that have not been Posted yet!';
$lang['closing:voucher_incorrect_transaction'] = 'There was an incorrect Voucher transaction. The transaction with No Voucher %s is inputted in %s, In that month has been Closing!';
$lang['closing:unclosing_previous_month']	= "You can not Closing for this period. Because the previous period has not done Closing.";
$lang['closing:period_empty_transaction']	= "You can not Closing in this period. Because this period there is no transaction.";
$lang['closing:not_accordance_with_gl']		= "Sorry the system can not continue the transaction. Because the Accounts Payable balance does not match the Balance in General Ledger. Please contact the vendor !!!";
$lang['closing:trouble_ap_tipe_not_macth']	= "Sorry the system can not continue the transaction. Because there are different types of header accounts with Factur details. Please contact the vendor !!!";
$lang['closing:trouble_ap_balance_not_macth']	= "Sorry the system can not continue the transaction. Because the Accounts Payable balance does not match the Balance in General Ledger (Detail Per Account). Please contact the vendor !!!";
$lang['closing:check_cancelled_card_payable']= "Sorry the system can not continue the transaction. Because there are cards with Facturs that have been canceled or not recognized payable. Please contact the vendor !!!";
$lang['closing:check_not_related_card_payable']= "Sorry the system can not continue the transaction. Because there is a card with an FACTUR that is NOT IN TRANSACTION OF FACTUR. Please contact the vendor!!!";
$lang['closing:recap_payable_not_macth']	= "Sorry the system can not continue the transaction. Because the Accounts Payable balance (TRANSACTION-Not yet closed Book) does not match the Balance in General Ledger (TRANSACTION-Not Closed Book). Please contact the vendor !!!";
$lang['closing:card_payable_not_macth_aging']= "Sorry the system can not continue the transaction. Because there is a discrepancy between the Card and the Debt Aging. Please contact the vendor !!!";
$lang['closing:card_type_payable_not_macth_aging']= "Sorry the system can not continue the transaction. Because there is % between cards with aging payable. Please contact the vendor !!!";
$lang['closing:success_status']				= 'Closing Proces Success!';
$lang['closing:failure_status']				= 'Closing Proces Failure!';

$lang['closing:check_general_ledger_closing_period']= "Closing can not be performed. Because at General Ledger still Closing. Cancel first Closing on General Ledger!";
$lang['closing:check_next_closing_period']	= "You can not un-close for that period. Because there is already a closing process for the next period!";
$lang['closing:cancel_success_status']		= 'Cancel Closing Proces Success!';
$lang['closing:cancel_failure_status']		= 'Cancel Closing Proces Failure!';


/* Aging
-------------------------------------------------------------------------------*/
// titles
$lang['aging:page']           				= 'Aging Payable';
$lang['aging:list_heading']       			= 'Aging Payable List';
$lang['aging:breadcrumb']        	 		= 'Aging Payable';
$lang['aging:modal_heading']     		  	= 'Details';
$lang['aging:create_heading']      			= 'Aging Payable';

//subtitles
$lang['aging:total_summary_sub']      		= 'Total Summary';
$lang['aging:supplier_lookup_title']  		= 'Select Supplier';

// labels
$lang['aging:date_label']					= 'Date';
$lang['aging:currency_label']				= 'Currency';
$lang['aging:customer_label']				= 'Customer';
$lang['aging:customer_code_label']			= 'Customer Code';
$lang['aging:customer_name_label']			= 'Customer Name';
$lang['aging:amount_label']					= 'Amount';
$lang['aging:type_label']					= 'Receivable Type';
$lang['aging:not_due_label']				= 'Not Due';
$lang['aging:due_label']					= 'Due';
$lang['aging:1_30_label']					= '1-30days';
$lang['aging:31_60_label']					= '31-60days';
$lang['aging:61_90_label']					= '61-90days';
$lang['aging:91_180_label']					= '91-180days';
$lang['aging:181_365_label']				= '181-365days';
$lang['aging:1_year_label']					= '>1year';
$lang['aging:grand_total_label']			= 'Grand Total';

/* Reports
-------------------------------------------------------------------------------*/
// titles
$lang['reports:page']           		= 'Reports Payable';
$lang['reports:breadcrumb']        	 	= 'Reports Payable';
$lang['reports:modal_heading']     		= 'Reports Note';
$lang['reports:create_heading']      	= 'Reports Payable';
$lang['reports:edit_heading']        	= 'Reports Payable';
$lang['reports:widget_heading']      	= 'Reports Payable';

//subtitles
$lang['reports:report_type_sub']     	= 'Select Report Type ';
$lang['reports:supplier_lookup_title']  = 'Select Supplier';

//label
$lang['reports:card_payable_label']	= 'Card Payable';
$lang['reports:recap_payable_label']	= 'Recap Payable';
$lang['reports:card_payable_report']	= 'Card Payable Report';
$lang['reports:recap_payable_report']	= 'Recap Payable Report';

$lang['reports:periode_label']			= 'Periode';
$lang['reports:supplier_label']			= 'Supplier';
$lang['reports:periode_label']			= 'Periode';
$lang['reports:till_label']				= 'Till';

$lang['reports:number_label'] 			= 'No.';
$lang['reports:date_label'] 			= 'Date';
$lang['reports:evidence_number_label'] 	= 'Evidance Number';
$lang['reports:code_label'] 			= 'Code';
$lang['reports:description_label'] 		= 'Description';
$lang['reports:beginning_balance_label']= 'Beginning Balance';
$lang['reports:debit_label'] 			= 'Debit';
$lang['reports:credit_label'] 			= 'Credit';
$lang['reports:ending_balance_label'] 	= 'Ending Balance';
$lang['reports:sub_total_label'] 		= 'Sub Total';
$lang['reports:grand_total_label'] 		= 'Grand Total';

$lang['reports:button_pdf']				= 'PDF';
$lang['reports:button_excel']			= 'Excel';

$lang['reports:madeby_label']			= 'Made by';
$lang['reports:approvedby_label']		= 'Approved by';

$lang['reports:card_payable_filename']	= 'Card-Payable-%s-%s.pdf';
$lang['reports:recap_payable_filename']	= 'Recap-Payable-%s-%s.pdf';

$lang['accounts:account_lookup_title']	= 'Lookup Account';
$lang['suppliers:supplier_lookup_title']	= 'Lookup Supplier';



