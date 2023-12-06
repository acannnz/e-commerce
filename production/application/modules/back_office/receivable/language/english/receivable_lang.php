<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang['receivables:page']= "Receivable";

/* TYPES 
-------------------------------------------------------------------------------*/
// titles
$lang['types:page']           			= 'Receivable Types';
$lang['types:breadcrumb']           	= 'Receivable Types';
$lang['types:modal_heading']        	= 'Details';
$lang['types:list_heading']       		= 'List of Receivable Types';
$lang['types:create_heading']       	= 'Create a New Receivable Type';
$lang['types:edit_heading']         	= 'Editing Receivable Type';

// labels
$lang['types:code_label']				= 'Code';
$lang['types:type_label']				= 'Type Name';
$lang['types:account_label']			= 'Account';
$lang['types:account_number_label']		= 'Account Number';
$lang['types:account_name_label']		= 'Account Name';
$lang['types:default_label']			= 'Default';
$lang['types:state_label']				= 'Status';
$lang['types:updated_label']			= 'Updated';

/* Invoices
-------------------------------------------------------------------------------*/
// titles
$lang['invoices:page']           		= 'Invoices';
$lang['invoices:breadcrumb']           	= 'Invoices';
$lang['invoices:modal_heading']        	= 'Details';
$lang['invoices:list_heading']       	= 'List of Invoices';
$lang['invoices:create_heading']       	= 'Create a New Invoice';
$lang['invoices:edit_heading']         	= 'Editing Invoice';
$lang['invoices:cancel_title']      		= 'Cancel Invoice';

// subtitle
$lang['invoices:invoice_detail_subtitle'] 	= "Invoice Details";
$lang['invoices:mutation_history_subtitle'] = "Mutation History";

// labels
$lang['invoices:invoice_number_label']	= 'Invoice Number';
$lang['invoices:factur_number_label']	= 'Factur Number';
$lang['invoices:customer_label']		= 'Customer';
$lang['invoices:date_label']			= 'Date';
$lang['invoices:due_date_label']		= 'Due Date';
$lang['invoices:value_label']			= 'Value';
$lang['invoices:remain_label']			= 'Remain';
$lang['invoices:description_label']		= 'Description';
$lang['invoices:debit_label']			= 'Debit';
$lang['invoices:credit_label']			= 'Credit';
$lang['invoices:state_label']			= 'State';
$lang['invoices:updated_label']			= 'Updated';
$lang['invoices:mutation_total_label']	= 'Mutation Total';
$lang['invoices:mutation_remain_label']	= 'Mutation Remain';
$lang['invoices:username_label']		= 'Username';
$lang['invoices:password_label']		= 'Password';

$lang['invoices:periode_label']			= 'Periode';
$lang['invoices:till_label']			= 'Till';
$lang['invoices:view_label']			= 'View';
$lang['invoices:cancel_invoice_label']	= 'Cancel Invoice';
$lang['invoices:find_invoice_list_label']= 'Find Invoice List';
$lang['invoices:customer_lookup_title'] = 'Lookup Data Customer';
$lang['invoices:factur_lookup_title'] 	= 'Lookup Data Factur';

$lang['invoices:close_book_data']			= 'This data is already Close Book!';
$lang['invoices:posted_data']				= 'This data is already Posted!';
$lang['invoices:cancel_data']				= 'This data is already Cancelled!';

$lang['invoices:customer_not_selected']		= 'Customer not selected! \n Please select the Customer first.';
$lang['invoices:factur_already_selected']	= 'This Factur: %s is already selected!';
$lang['invoices:details_cannot_empty']		= 'Details Invoice cannot empty!';
$lang['invoices:transaction_date_incorret']	= 'Invoice Date (%s) must not be before Factur Date (%s)!';
$lang['invoices:already_closing_period']	= 'The transaction can not continue. Because there is already Closing in the period: %s';
$lang['invoices:already_mutation']			= 'The Invoice can not be Canceled because it already exists Mutation!';
$lang['invoices:cancel_invoice']			= 'Are you sure to cancel this Invoice ?';


/* Facturs
-------------------------------------------------------------------------------*/
// titles
$lang['facturs:page']           			= 'Factur Receivable';
$lang['facturs:breadcrumb']        	 		= 'Factur Receivable';
$lang['facturs:modal_heading']     		  	= 'Details Factur';
$lang['facturs:create_heading']      		= 'Factur Receivable';
$lang['facturs:edit_heading']        		= 'Editing Factur Receivable';
$lang['facturs:widget_heading']      		= 'List of Factur Receivable';

$lang['facturs:cancel_title']      			= 'Cancel Factur';

//subtitles
$lang['facturs:accounts_details_heading']   = 'Accounts Details';
$lang['facturs:total_summary_sub']      	= 'Total Summary';

// labels
$lang['facturs:factur_number_label']		= 'Factor Number';
$lang['facturs:invoice_number_label']		= 'Voucher Number';
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
$lang['facturs:customer_label']				= 'Customer';
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
$lang['facturs:already_created_invoices']	= 'The Factur can not be edited because an Invoice has been created!';
$lang['facturs:already_posted']				= 'The Factur can not be edited, because it\'s already posted to GL!';

$lang['facturs:value_not_match']			= 'Factur value cannot be 0 or minus!';
$lang['facturs:details_cannot_empty']		= 'Details account cannot empty!';
$lang['facturs:cancel_factur']				= 'Are you sure to cancel this factur ?';
$lang['facturs:cannot_delete_close_data']	= 'Cannot cancel close book data!';

/* Credit Debit Notes
-------------------------------------------------------------------------------*/
// titles
$lang['credit_debit_notes:page']           			= 'Credit Debit Note Receivable';
$lang['credit_debit_notes:breadcrumb']        	 	= 'Credit Debit Note Receivable';
$lang['credit_debit_notes:modal_heading']     		= 'Details Credit Debit Note';
$lang['credit_debit_notes:create_heading']      	= 'Create Credit Debit Note Receivable';
$lang['credit_debit_notes:edit_heading']        	= 'Editing Credit Debit Note Receivable';
$lang['credit_debit_notes:widget_heading']      	= 'List of Credit Debit Note Receivable';

$lang['credit_debit_notes:delete_title']      		= 'Delete Credit Debit Note';

//subtitles
$lang['credit_debit_notes:accounts_details_heading']= 'Accounts Details';
$lang['credit_debit_notes:total_summary_sub']      	= 'Total Summary';

// labels
$lang['credit_debit_notes:evidence_number_label']	= 'Evidence Number';
$lang['credit_debit_notes:invoice_number_label']	= 'Invoice Number';
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
$lang['credit_debit_notes:customer_label']			= 'Customer';
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

$lang['credit_debit_notes:customer_not_selected']	= 'Customer not selected! \n Please select the Customer first.';
$lang['credit_debit_notes:value_not_match']			= 'Credit Debit Note value cannot be 0 or minus!';
$lang['credit_debit_notes:details_cannot_empty']	= 'Details account cannot empty!';
$lang['credit_debit_notes:delete_credit_debit_note']= 'Are you sure to delete this Credit Debit Note ?';
$lang['credit_debit_notes:cannot_delete_close_data']= 'Cannot cancel close book data!';
$lang['credit_debit_notes:transaction_date_incorret']	= 'Nota Date must not be before Invoice Date, Invoice: %s!';
$lang['credit_debit_notes:invoice_not_selected']	= 'The transaction can not be continued, Because There is no selected Invoice!';
$lang['credit_debit_notes:already_closing_period']	= 'The transaction can not be continued, Because Closing has been done in the period: %s!';
$lang['credit_debit_notes:empty_increase_decrease']	= 'The transaction can not be continued, Because the Decrease or Increase value has not been inputted in Invoice: %s!';
$lang['credit_debit_notes:decrease_value_exceed']	= 'The transaction can not proceed, Because the Decrease Value exceeds the Remain Value in Invoice: %s!';
$lang['credit_debit_notes:simultaneously_increase_decrease']= 'The transaction can not be continued, Because it can not do Increase and Decrease simultaneously!';


/* Beginning Balance
-------------------------------------------------------------------------------*/
// titles
$lang['beginning_balances:page']           				= 'Beginning Balances Receivable';
$lang['beginning_balances:list_heading']       			= 'List of Beginning Balances Receivable ';
$lang['beginning_balances:breadcrumb']        	 		= 'Beginning Balances Receivable';
$lang['beginning_balances:modal_heading']     		  	= 'Details';
$lang['beginning_balances:create_heading']      		= 'Beginning Balances Receivable';

//subtitles
$lang['beginning_balances:total_summary_sub']      		= 'Total Summary';

// labels
$lang['beginning_balances:date_label']					= 'Date';
$lang['beginning_balances:currency_label']				= 'Currency';
$lang['beginning_balances:customer_label']				= 'Customer';
$lang['beginning_balances:customer_code_label']			= 'Customer Code';
$lang['beginning_balances:customer_name_label']			= 'Customer Name';
$lang['beginning_balances:value_label']					= 'Value';

$lang['beginning_balances:paid_receivable']				= 'This Receivable is already paid! please cancel the payment if you want continue this proses!';
$lang['beginning_balances:close_book']					= 'This Receivable is already Close Book! please cancel close book if you want continue this proses!';
$lang['beginning_balances:identical_posted']			= 'This Customer and Type is Already in entry. Please entry other data!';

/* Postings
-------------------------------------------------------------------------------*/
// titles
$lang['postings:page']           			= 'Posting Receivable';
$lang['postings:cancel_page']           	= 'Cancel Posting Receivable';
$lang['postings:breadcrumb']        	 	= 'Posting Receivable';
$lang['postings:modal_heading']     		= 'Details Receivable';
$lang['postings:create_heading']      		= 'Posting Receivable';
$lang['postings:widget_heading']      		= 'List of Factur Receivable';

$lang['postings:cancel_title']      		= 'Cancel Posting';

//subtitles
$lang['postings:accounts_details_heading']  = 'Accounts Details';
$lang['postings:total_summary_sub']      	= 'Total Summary';

// labels
$lang['postings:posting_number_label']		= 'Evidence Number';
$lang['postings:invoice_number_label']		= 'Voucher Number';
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
$lang['postings:customer_label']			= 'Customer';

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

$lang['closing:closing_confirm']			= 'You sure want to Closing in the period : %s. After Closing, the transaction in that period can not be edited again. Are you sure you want to continue?';
$lang['closing:un_posting_data']			= 'Closing failed! There are still an factur or invoice not posted';
$lang['closing:un_posting_mutation_data']	= 'There are still MUTATION transactions (WAREHOUSES) that have not been posted. First post all transactions before Closing!';
$lang['closing:invoice_incorrect_transaction'] = 'There was an incorrect Invoice transaction. The transaction with No Invoice %s is inputted in %s, In that month has been Closing!';
$lang['closing:unclosing_previous_month']	= "You can not Closing for this period. Because the previous period has not done Closing.";
$lang['closing:period_empty_transaction']	= "You can not Closing in this period. Because this period there is no transaction.";
$lang['closing:not_accordance_with_gl']		= "Sorry the system can not continue the transaction. Because the Accounts Receivable balance does not match the Balance in General Ledger. Please contact the vendor !!!";
$lang['closing:trouble_ar_tipe_not_macth']	= "Sorry the system can not continue the transaction. Because there are different types of header accounts with Factur details. Please contact the vendor !!!";
$lang['closing:trouble_ar_balance_not_macth']	= "Sorry the system can not continue the transaction. Because the Accounts Receivable balance does not match the Balance in General Ledger (Detail Per Account). Please contact the vendor !!!";
$lang['closing:check_cancelled_card_receivable']= "Sorry the system can not continue the transaction. Because there are cards with Facturs that have been canceled or not recognized receivables. Please contact the vendor !!!";
$lang['closing:check_not_related_card_receivable']= "Sorry the system can not continue the transaction. Because there is a card with an FACTUR that is NOT IN TRANSACTION OF FACTUR. Please contact the vendor!!!";
$lang['closing:recap_receivable_not_macth']	= "Sorry the system can not continue the transaction. Because the Accounts Receivable balance (TRANSACTION-Not yet closed Book) does not match the Balance in General Ledger (TRANSACTION-Not Closed Book). Please contact the vendor !!!";
$lang['closing:card_receivable_not_macth_aging']= "Sorry the system can not continue the transaction. Because there is a discrepancy between the Card and the Debt Aging. Please contact the vendor !!!";
$lang['closing:card_type_receivable_not_macth_aging']= "Sorry the system can not continue the transaction. Because there is % between cards with aging payable. Please contact the vendor !!!";
$lang['closing:success_status']				= 'Closing Proces Success!';
$lang['closing:failure_status']				= 'Closing Proces Failure!';

$lang['closing:check_general_ledger_closing_period']= "Closing can not be performed. Because at General Ledger still Closing. Cancel first Closing on General Ledger!";
$lang['closing:check_next_closing_period']	= "You can not un-close for that period. Because there is already a closing process for the next period!";
$lang['closing:cancel_success_status']		= 'Cancel Closing Proces Success!';
$lang['closing:cancel_failure_status']		= 'Cancel Closing Proces Failure!';

/* Aging
-------------------------------------------------------------------------------*/
// titles
$lang['aging:page']           				= 'Aging Receivable';
$lang['aging:list_heading']       			= 'Aging Receivable List';
$lang['aging:breadcrumb']        	 		= 'Aging Receivable';
$lang['aging:modal_heading']     		  	= 'Details';
$lang['aging:create_heading']      			= 'Aging Receivable';

//subtitles
$lang['aging:total_summary_sub']      		= 'Total Summary';
$lang['aging:customer_lookup_title']  		= 'Select Customer';

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
$lang['reports:page']           		= 'Reports Receivable';
$lang['reports:breadcrumb']        	 	= 'Reports Receivable';
$lang['reports:modal_heading']     		= 'Reports Note';
$lang['reports:create_heading']      	= 'Reports Receivable';
$lang['reports:edit_heading']        	= 'Reports Receivable';
$lang['reports:widget_heading']      	= 'Reports Receivable';

//subtitles
$lang['reports:report_type_sub']     	= 'Select Report Type ';
$lang['reports:customer_lookup_title'] 	= 'Select Customer';

//label
$lang['reports:card_receivable_label']	= 'Card Receivable';
$lang['reports:recap_receivable_label']	= 'Recap Receivable';
$lang['reports:card_receivable_report']	= 'Card Receivable Report';
$lang['reports:recap_receivable_report']= 'Recap Receivable Report';

$lang['reports:periode_label']			= 'Periode';
$lang['reports:customer_label']			= 'Customer';
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

$lang['reports:card_receivable_filename']	= 'Card-Receivable-%s-%s.pdf';
$lang['reports:recap_receivable_filename']	= 'Recap-Receivable-%s-%s.pdf';








