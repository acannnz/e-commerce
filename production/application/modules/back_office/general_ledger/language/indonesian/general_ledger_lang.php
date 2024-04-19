<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang['accounting:page']				= "Data Rekening";
$lang['accounting:state']				= "Status";


/* accounts 
-------------------------------------------------------------------------------*/
// titles
$lang['accounts:page']           		= 'Master Rekening';
$lang['accounts:breadcrumb']           	= 'Rekening';
$lang['accounts:list_heading'] 			= 'Daftar Master Rekening';
$lang['accounts:modal_heading']         = 'Rincian';
$lang['accounts:create_heading']        = 'Setup Rekening Baru';
$lang['accounts:edit_heading']          = 'Edit Rekening';
$lang['accounts:widget_heading']       	= 'Daftar Rekening';
$lang['accounts:tree_heading']       	= 'Struktur Rekening';

//lookup
$lang['accounts:account_lookup_title']  = 'Lookup Rekening';
$lang['accounts:account_lookup_helper'] = 'Anda dapat memilih 1 Rekening dan klik TERAPKAN untuk memilih';

// labels
$lang['accounts:component_label']		= 'Komponen';
$lang['accounts:group_label']			= 'Group';
$lang['accounts:sub_group_label']		= 'Sub Grup';
$lang['accounts:normal_pos_label']		= 'Normal Pos';
$lang['accounts:level_label']			= 'Level';
$lang['accounts:account_number_label']	= 'No Rekening';
$lang['accounts:account_name_label']	= 'Nama Rekening';
$lang['accounts:account_description_label']	= 'Deskripsi Rekening';
$lang['accounts:currency_label']			= 'Mata uang';
$lang['accounts:convert_permanent_label']	= 'Convert Permanent';
$lang['accounts:integration_label']			= 'Integrasi';
$lang['accounts:integration_source_label']	= 'Sumber Integrasi';

$lang['accounts:cannot_delete']				= 'Rekening ini tidak dapat dihapus, karena sudah melakukan Transaksi!';
$lang['accounts:cannot_add_child']			= 'Tidak dapat menambah Anak untuk Rekening ini, karena sudah berada di Level terakhir!';

/* Account Concepts
-------------------------------------------------------------------------------*/
// titles
$lang['concepts:page']           		= 'Konsep Rekening';
$lang['concepts:breadcrumb']         	= 'Konsep Rekening';
$lang['concepts:list_heading'] 			= 'Daftar Konsep Rekening';
$lang['concepts:modal_heading']       	= 'Form Konsep Rekening';
$lang['concepts:create_heading']      	= 'Buat Konsep Rekening Baru';
$lang['concepts:edit_heading']        	= 'Edit Konsep Rekening';

// labels
$lang['concepts:level_label']			= 'Level';
$lang['concepts:digit_label']			= 'Digit';
$lang['concepts:description_label']		= 'Keterangan';
$lang['concepts:max_level_label']		= 'Level Maksimal';
$lang['concepts:max_digit_label']		= 'Digit Maksimal';
$lang['concepts:level_to_label']		= 'Level Ke';
$lang['concepts:digit_number_label']	= 'Jumlah Digit';
$lang['concepts:updated_label']			= 'Diupdate';

$lang['concepts:level_digit_exceed_max']= 'Terjadi Kesalahan! Level atau Digit Detail melebihi';

/* Account Structure
-------------------------------------------------------------------------------*/
// titles
$lang['structures:page']           		= 'Struktur Rekening';
$lang['structures:breadcrumb']         	= 'Struktur Rekening';
$lang['structures:list_heading'] 		= 'Daftar Struktur Rekening';
$lang['structures:modal_heading']       = 'Form Struktur Rekening';
$lang['structures:create_heading']      = 'Buat Struktur Rekening Baru';
$lang['structures:edit_heading']        = 'Edit Struktur Rekening';

// labels
$lang['structures:group_name_label']	= 'Nama Group';
$lang['structures:group_account_detail_label']	= 'Group Detail Rekening';
$lang['structures:description_label']	= 'Description';
$lang['structures:cash_label']			= 'Cash';
$lang['structures:bank_label']			= 'Bank';

/* Journals
-------------------------------------------------------------------------------*/
// titles
$lang['journals:page']           			= 'Transaksi Jurnal';
$lang['journals:breadcrumb']        	 	= 'Transaksi Jurnal';
$lang['journals:list_heading'] 				= 'Daftar Transaksi Jurnal';
$lang['journals:modal_heading']     		= 'Rincian';
$lang['journals:create_heading']      		= 'Buat Transaksi Jurnal';
$lang['journals:edit_heading']        		= 'Edit Transaksi Jurnal';
$lang['journals:widget_heading']      		= 'Daftar Transaksi Jurnal';

//subtitles
$lang['journals:accounts_details_sub']     	= 'Detail Rekening';
$lang['journals:total_summary_sub']      		= 'Total Keseluruhan';

// labels
$lang['journals:journal_number_label']		= 'Nomer Jurnal';
$lang['journals:date_label']				= 'Tanggal';
$lang['journals:notes_label']				= 'Catatan';
$lang['journals:currency_label']			= 'Mata Uang';
$lang['journals:section_label']				= 'Section';
$lang['journals:account_number_label']		= 'Nomer Rekening';
$lang['journals:account_name_label']		= 'Nama Rekening';
$lang['journals:debit_label']				= 'Debit';
$lang['journals:credit_label']				= 'Kredit';
$lang['journals:balance_label']				= 'Balance';
$lang['journals:project_label']				= 'Proyek';
$lang['journals:division_label']			= 'Divisi';

$lang['journals:save_recurring_label']		= 'Simpan Recurring';
$lang['journals:use_recurring_label']		= 'Gunakan Recurring';

/* General Ledger 
-------------------------------------------------------------------------------*/
// titles
$lang['general_ledger:page']           				= 'Buku Besar';
$lang['general_ledger:breadcrumb']        	 		= 'Buku Besar';
$lang['general_ledger:list_heading'] 				= 'Daftar Buku Besar';
$lang['general_ledger:modal_heading']     		  	= 'Rincian';
$lang['general_ledger:create_heading']      		= 'Buku Besar';
$lang['general_ledger:edit_heading']        		= 'Edit Buku Besar';
$lang['general_ledger:widget_heading']      		= 'Daftar Buku Besar';

//subtitles
$lang['general_ledger:accounts_details_sub']     	= 'Detail Rekening';
$lang['general_ledger:total_summary_sub']      		= 'Total Keseluruhan';
$lang['general_ledger:not_found_journal'] 			= 'Data Jurnal tidak ditemukan!';

// labels
$lang['general_ledger:from_date_label']				= 'Dari';
$lang['general_ledger:till_date_label']				= 's/d';
$lang['general_ledger:account_label']				= 'Rekening';
$lang['general_ledger:account_number_label']		= 'No Rekening';
$lang['general_ledger:account_name_label']			= 'Nama Rekening';
$lang['general_ledger:currency_label']				= 'Mata Uang';
$lang['general_ledger:convert_label']				= 'Convert';
$lang['general_ledger:journal_number_label']		= 'Nomor Transaksi';
$lang['general_ledger:journal_date_label']			= 'Tgl Transaksi';
$lang['general_ledger:journal_type_label']			= 'Tipe Jurnal';
$lang['general_ledger:beginning_balance_label']		= 'Saldo Awal';
$lang['general_ledger:ending_balance_label']		= 'Saldo Akhir';
$lang['general_ledger:notes_label']					= 'Keterangan';
$lang['general_ledger:debit_label']					= 'Debit';
$lang['general_ledger:credit_label']				= 'Kredit';
$lang['general_ledger:balance_label']				= 'Saldo';

$lang['general_ledger:empty_currency_alert']		= 'Rekening --> %d - %s Belum diisi mata uang';
$lang['general_ledger:empty_rate_currency']			= 'Rate currency belum ada!  \n Silahkan input rate currency';
$lang['general_ledger:proceed_confirm']				= 'Proses saldo awal ini akan memerlukan waktu beberapa saat. \n Anda yakin ingin melanjutkan ?';

/* General 
-------------------------------------------------------------------------------*/
// titles
$lang['general:page']           			= 'Journal Umum';
$lang['general:breadcrumb']        	 		= 'Journal Umum';
$lang['general:list_heading'] 				= 'Daftar Journal Umum';
$lang['general:modal_heading']     		  	= 'Rincian';
$lang['general:create_heading']      		= 'Journal Umum';
$lang['general:edit_heading']        		= 'Edit Journal Umum';
$lang['general:widget_heading']      		= 'Daftar Journal Umum';

//subtitles
$lang['general:accounts_details_sub']     	= 'Detail Rekening';
$lang['general:total_summary_sub']      	= 'Total Keseluruhan';
$lang['general:not_found_journal'] 			= 'Data Jurnal tidak ditemukan!';

// labels
$lang['general:from_date_label']			= 'Dari';
$lang['general:till_date_label']			= 's/d';
$lang['general:account_label']				= 'Rekening';
$lang['general:account_number_label']		= 'No Rekening';
$lang['general:account_name_label']			= 'Nama Rekening';
$lang['general:currency_label']				= 'Mata Uang';
$lang['general:convert_label']				= 'Convert';
$lang['general:journal_number_label']		= 'Nomor Transaksi';
$lang['general:journal_date_label']			= 'Tgl Transaksi';
$lang['general:journal_type_label']			= 'Tipe Jurnal';
$lang['general:beginning_balance_label']	= 'Saldo Awal';
$lang['general:ending_balance_label']		= 'Saldo Akhir';
$lang['general:notes_label']				= 'Keterangan';
$lang['general:debit_label']				= 'Debit';
$lang['general:credit_label']				= 'Kredit';
$lang['general:balance_label']				= 'Saldo';

$lang['general:empty_currency_alert']		= 'Rekening --> %d - %s Belum diisi mata uang';
$lang['general:empty_rate_currency']			= 'Rate currency belum ada!  \n Silahkan input rate currency';
$lang['general:proceed_confirm']				= 'Proses saldo awal ini akan memerlukan waktu beberapa saat. \n Anda yakin ingin melanjutkan ?';


/* Beginning Balance
-------------------------------------------------------------------------------*/
// titles
$lang['beginning_balances:page']           				= 'Saldo Awal Sistem';
$lang['beginning_balances:breadcrumb']        	 		= 'Saldo Awal Sistem';
$lang['beginning_balances:list_heading'] 				= 'Daftar Saldo Awal Sistem';
$lang['beginning_balances:setup_rate_currency_heading'] = 'Setup Rate Currency';
$lang['beginning_balances:create_heading']      		= 'Saldo Awal Sistem';

//subtitles
$lang['beginning_balances:total_summary_sub']      		= 'Total Summary';
$lang['beginning_balances:aktiva_sub']      			= 'Aktiva';
$lang['beginning_balances:pasiva_sub']      			= 'Pasiva';

// labels
$lang['beginning_balances:date_label']					= 'Tanggal';
$lang['beginning_balances:currency_label']				= 'Curr';
$lang['beginning_balances:account_number_label']		= 'No Rekening';
$lang['beginning_balances:account_name_label']			= 'Nama Rekening';
$lang['beginning_balances:value_label']					= 'Nilai';
$lang['beginning_balances:balance_label']				= 'Balance';
$lang['beginning_balances:not_balance_label']			= 'Tidak Balance';
$lang['beginning_balances:description_label']			= 'Keterangan';

$lang['beginning_balances:setup_rate_currency'] 		= 'Setup Rate Currency';
$lang['beginning_balances:empty_currency_alert']		= 'Rekening --> %d - %s Belum diisi mata uang';
$lang['beginning_balances:empty_rate_currency']			= 'Rate currency belum ada!  \n Silahkan input rate currency';
$lang['beginning_balances:proceed_confirm']				= 'Proses saldo awal ini akan memerlukan waktu beberapa saat. \n Anda yakin ingin melanjutkan ?';
$lang['beginning_balances:existing_next_monthly_posted']= 'Anda tidak dapat melanjutkan proses saldo awal untuk tanggal %s, Karena transaksi untuk periode %s telah tutup buku.';
$lang['beginning_balances:existing_transaction'] 		= 'Saldo Awal tidak bisa diinput dengan Tanggal %s, Karena sudah ada transaksi sebelum tanggal tersebut';

/* Cash Flow
-------------------------------------------------------------------------------*/
// titles
$lang['cash_flow:page']           		= 'Cash Flow';
$lang['cash_flow:breadcrumb']         	= 'Cash Flow';
$lang['cash_flow:list_heading'] 		= 'Daftar Cash Flow';
$lang['cash_flow:modal_heading']       = 'Form Cash Flow';
$lang['cash_flow:setup_heading']      = 'Setup Cash Flow';
$lang['cash_flow:account_heading']      = 'Rekening Cash Flow';
$lang['cash_flow:report_heading']      = 'Laporan Cash Flow';

// labels
$lang['cash_flow:group_label']	= 'Group';
$lang['cash_flow:subgroup_label'] = 'Sub Group';
$lang['cash_flow:account_label']	= 'Rekening';
$lang['cash_flow:account_number_label']	= 'No Rekening';
$lang['cash_flow:account_name_label'] = 'Nama Rekening';
$lang['cash_flow:debt_label']	= 'Debit';
$lang['cash_flow:credit_label'] = 'Kredit';
$lang['cash_flow:Normal_pos_label'] = 'Normal Pos';
$lang['cash_flow:date_label'] = 'Tanggal';
$lang['cash_flow:priod_label'] = 'Periode';

$lang['cash_flow:cash_flow_report'] = 'Laporan Cash Flow';
$lang['cash_flow:cash_flow_detail_report'] = 'Laporan Cash Flow Detail';
$lang['cash_flow:cash_flow_transaction_report'] = 'Laporan Transaksi Cash Flow';


/* Close Books
-------------------------------------------------------------------------------*/
// titles
$lang['closing:page']           			= 'Tutup Buku';
$lang['closing:breadcrumb']        	 		= 'Tutup Buku';
$lang['closing:cancel_page']           		= 'Batal Tutup Buku';
$lang['closing:cancel_breadcrumb']        	= 'Batal Tutup Buku';
$lang['closing:list_heading'] 				= 'Daftar Tutup Buku';
$lang['closing:modal_heading']     		  	= 'Details';
$lang['closing:create_heading']      		= 'Tutup Buku';

// labels
$lang['closing:date_label']					= 'Tanggal';
$lang['closing:username_label']				= 'Username';
$lang['closing:password_label']				= 'Password';
$lang['closing:last_closing_label']			= 'Tutup buku terakhir';

$lang['closing:payable_module']				= 'Module Hutang';
$lang['closing:receivable_module']			= 'Module Piutang';

$lang['closing:process_success']			= 'Proses Tutup Buku Sukses!';
$lang['closing:prosess_failure']			= 'Proses Tutup Buku Gagal!';
$lang['closing:cancel_process_success']		= 'Pembatalan Proses Tutup Buku Sukses!';
$lang['closing:cancel_prosess_failure']		= 'Pembatalan Proses Tutup Buku Gagal!';
$lang['closing:closing_confirm']			= 'Anda yakin ingin melakukan Tutup Buku pada periode: %s ? Setelah anda melakukan Tutup Buku, maka transaksi pada periode tersebut tidak dapat diedit lagi. Anda yakin ingin melanjutkan ?';
$lang['closing:closing_cancel_confirm']		= 'Anda yakin ingin melakukan Pembatalan Tutup Buku pada periode: %s';
$lang['closing:unsetup_cash_flow']			= 'Proses tutup buku tidak dapat dilakukan. Karena masih adanya cash flow yang belum disetup! Lengkapi setup cash flow terlebih.';
$lang['closing:setup_cash_flow_confirm']	= 'Apakah anda ingin melihat detail rekening yang belum di-setup?';
$lang['closing:unclosing_payabale_receivable']	= 'belum tutup buku. Lakukan tutup buku pada masing-masing Module terlebih dahulu';
$lang['closing:unposted_general_cashier'] 	= "Masih terdapat data General Cashier yang belum terposting. Silahkan lakukan posting data dari General Cashier.";		
$lang['closing:transaction_already_posted'] = "Terdapat transaksi yang salah pada Transaksi dengan No Bukti %s terinput pada %s tersebut telah dilakukan tutup buku";
$lang['closing:already_closing']			= "Sudah pernah dilakukan tutup buku untuk periode : %s.";
$lang['closing:unclosing_previous_month']= "Anda tidak dapat melakukan tutup buku untuk periode ini. Karena periode sebelumya belum dilakukan tutup buku.";
$lang['closing:consolidation_cooperation_data'] = 'Anda tidak dapat membatalkan tutup buku pada periode tersebut. Karena sudah ada proses Konsolidasi dengan Corporate';
$lang['closing:already_closing_next_month'] = 'Anda tidak dapat membatalkan tutup buku pada periode tersebut. Karena sudah ada proses tutup buku untuk periode selanjutnya.';
	
/* Setup Laba Rugi
-------------------------------------------------------------------------------*/
// titles
$lang['income_loss_setup:page']           		= 'Setup Laba Rugi';
$lang['income_loss_setup:breadcrumb']          	= 'Setup Laba Rugi';
$lang['income_loss_setup:list_heading'] 		= 'Daftar Setup Laba Rugi';
$lang['income_loss_setup:modal_heading']       	= 'Detail';
$lang['income_loss_setup:setup_heading']      	= 'Setup Rekening Laba Rugi';
$lang['income_loss_setup:edit_heading']        	= 'Edit Rekening Laba Rugi';

// labels
$lang['income_loss_setup:account_label']		= 'Rekening';
$lang['income_loss_setup:account_number_label']	= 'Nomor Rekening';
$lang['income_loss_setup:account_name_label']	= 'Nama Rekening';
$lang['income_loss_setup:type_label']			= 'Tipe';

$lang['income_loss_setup:used_warning']			= 'Jenis Laba Rugi ini telah digunakan oleh Rekening: %s - %s';


/* Income Loss
-------------------------------------------------------------------------------*/
// titles
$lang['income_loss:page']           		= 'Laba Rugi';
$lang['income_loss:breadcrumb']         	= 'Laba Rugi';
$lang['income_loss:list_heading'] 		= 'Daftar Laba Rugi';
$lang['income_loss:modal_heading']       = 'Form Laba Rugi';
$lang['income_loss:create_heading']      = 'Buat Laba Rugi Baru';
$lang['income_loss:edit_heading']        = 'Edit Laba Rugi';
$lang['income_loss:monthly_page']      = 'Laba Rugi Bulanan';

// labels
$lang['income_loss:excel_title']			= 'Penjelasan Laba Rugi Periode %s';
$lang['income_loss:explanation_balance_label'] = 'Penjelasan Laba Rugi';
$lang['income_loss:period']				= 'Periode';
$lang['income_loss:period_label']		= 'Per: %s';
$lang['income_loss:in_rupiah_label']		= '(Dalam Rupiah)';
$lang['income_loss:description_label']		= 'Deskripsi';
$lang['income_loss:value_label']			= 'Nilai';
$lang['income_loss:quarterly']			= 'Triwulan';


/* Balance Sheets
-------------------------------------------------------------------------------*/
// titles
$lang['balance_sheets:page']           		= 'Neraca';
$lang['balance_sheets:breadcrumb']         	= 'Neraca';
$lang['balance_sheets:list_heading'] 		= 'Daftar Neraca';
$lang['balance_sheets:modal_heading']       = 'Form Neraca';
$lang['balance_sheets:create_heading']      = 'Buat Neraca Baru';
$lang['balance_sheets:edit_heading']        = 'Edit Neraca';
$lang['balance_sheets:trial_balance_heading']= 'Neraca Saldo';

// labels
$lang['balance_sheets:excel_title']			= 'Penjelasan Neraca Periode %s';
$lang['balance_sheets:explanation_balance_label'] = 'Penjelasan Neraca';
$lang['balance_sheets:period']				= 'Periode';
$lang['balance_sheets:period_label']		= 'Per: %s';
$lang['balance_sheets:in_rupiah_label']		= '(Dalam Rupiah)';
$lang['balance_sheets:activa_label']		= 'Aktiva';
$lang['balance_sheets:pasiva_label']		= 'Pasiva';
$lang['balance_sheets:activa_total_label']		= 'Total Activa';
$lang['balance_sheets:pasiva_total_label']		= 'Total Pasiva';


// Gender
$lang['gender:male']				= 'Laki-laki';
$lang['gender:female']				= 'Perempuan';

// Age
$lang['age:years']					= '%s tahun';
$lang['age:months']					= '%d bulan';
$lang['age:days']					= '%d hari';
$lang['age:details']				= '%d tahun %d bulan %d hari';

// Religion
$lang['relegion:hindu']				= 'Hindu';
$lang['relegion:budha']				= 'Budha';

// ID Type
$lang['id_type:ktp']				= 'Kartu Tanda Penduduk';
$lang['id_type:sim']				= 'Surat Izin Mengemudi';
$lang['id_type:kartu_pelajar']		= 'Kartu Mahasiswa';
$lang['id_type:kartu_mahasiswa']	= 'Kartu Mahasiswa';

// Referer
$lang['referer:self']				= 'Sendiri';
$lang['referer:vendor']				= 'Referensi dari vendor';
$lang['referer:patient_hc']			= 'Reference by HC Patient';
$lang['referer:patient_iks']		= 'Reference by IKS Patient';
$lang['referer:patient_general']	= 'Reference by General Patient';
$lang['referer:patient_family']		= 'Reference by Family';
$lang['referer:patient_friend']		= 'Reference by Friend';








