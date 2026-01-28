<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang['receivables:page']= "Piutang";



/* TYPES 
-------------------------------------------------------------------------------*/
// titles
$lang['types:page']           		= 'Tipe Piutang';
$lang['types:breadcrumb']         	= 'Tipe Piutang';
$lang['types:modal_heading']      	= 'Rincian';
$lang['types:list_heading']       	= 'Daftar Tipe Piutang';
$lang['types:create_heading']     	= 'Buat Tipe Piutang Baru';
$lang['types:edit_heading']       	= 'Edit Tipe Piutang';

// labels
$lang['types:code_label']			= 'Kode';
$lang['types:type_label']			= 'Nama Tipe';
$lang['types:account_label']		= 'Rekening';
$lang['types:account_number_label']	= 'Nomor Rekening';
$lang['types:account_name_label']	= 'Nama Rekening';
$lang['types:default_label']		= 'Default';
$lang['types:state_label']			= 'Status';
$lang['types:updated_label']		= 'Diupdate';

/* TYPES 
-------------------------------------------------------------------------------*/
// titles
$lang['invoices:page']           		= 'Invoice';
$lang['invoices:breadcrumb']           	= 'Invoice';
$lang['invoices:modal_heading']        	= 'Detail';
$lang['invoices:list_heading']       	= 'Daftar Invoice';
$lang['invoices:create_heading']       	= 'Buat Invoice';
$lang['invoices:edit_heading']         	= 'Edit Invoice';
$lang['invoices:cancel_title']      	= 'Batal Invoice';

// subtitle
$lang['invoices:invoice_detail_subtitle'] 	= "Detail Invoice";
$lang['invoices:mutation_history_subtitle'] = "Riwayat Mutasi";

// labels
$lang['invoices:invoice_number_label']	= 'Nomor Invoice';
$lang['invoices:factur_number_label']	= 'Nomor Faktur';
$lang['invoices:customer_label']		= 'Customer';
$lang['invoices:date_label']			= 'Tanggal';
$lang['invoices:due_date_label']		= 'Tanggal Jatuh Tempo';
$lang['invoices:value_label']			= 'Nilai';
$lang['invoices:remain_label']			= 'Sisa';
$lang['invoices:description_label']		= 'Deskripsi';
$lang['invoices:debit_label']			= 'Debit';
$lang['invoices:credit_label']			= 'Kredit';
$lang['invoices:state_label']			= 'Status';
$lang['invoices:updated_label']			= 'Updated';
$lang['invoices:mutation_total_label']	= 'Total Mutasi';
$lang['invoices:mutation_remain_label']	= 'Sisa Mutasi';
$lang['invoices:username_label']		= 'Username';
$lang['invoices:password_label']		= 'Password';

$lang['invoices:periode_label']			= 'Periode';
$lang['invoices:till_label']			= 's/d';
$lang['invoices:view_label']			= 'Lihat';
$lang['invoices:cancel_invoice_label']	= 'Invoice Dibatalkan';
$lang['invoices:find_invoice_list_label']= 'Cari Daftar Invoice';
$lang['invoices:customer_lookup_title'] = 'Lookup Data Customer';
$lang['invoices:factur_lookup_title'] = 'Lookup Data Faktur';

$lang['invoices:close_book_data']			= 'Data ini sudah Tutup Buku!';
$lang['invoices:posted_data']				= 'Data ini sudah di Posting!';
$lang['invoices:cancel_data']				= 'Data ini sudah di Batalkan!';

$lang['invoices:customer_not_selected']		= 'Customer belum dipilih ! \n Silahkan pilih Customer terlebih dahulu.';
$lang['invoices:factur_already_selected']	= 'Faktur ini: %s sudah  dipilih!';
$lang['invoices:details_cannot_empty']		= 'Detail Invoice tidak boleh kosong!';
$lang['invoices:transaction_date_incorret']	= 'Tanggal Invoice (%s) tidak boleh sebelum Tanggal Faktur (%s)!';
$lang['invoices:already_closing_period']	= 'Transaksi tidak dapat dilanjutkan. Karena sudah ada Tutup Buku pada periode: %s';
$lang['invoices:already_mutation']			= 'Invoice tersebut tidak bisa diBatalkan karena sudah ada Mutasi!';
$lang['invoices:cancel_invoice']			= 'Apa Anda yakin ingin membatalkan Invoice ini ?';


/* Facturs
-------------------------------------------------------------------------------*/
// titles
$lang['facturs:page']           			= 'Faktur Piutang';
$lang['facturs:breadcrumb']        	 		= 'Faktur Piutang';
$lang['facturs:modal_heading']     		  	= 'Rincian Faktur';
$lang['facturs:create_heading']      		= 'Faktur Piutang';
$lang['facturs:edit_heading']        		= 'Edit Faktur Piutang';
$lang['facturs:widget_heading']      		= 'Daftar Faktur Piutang';

$lang['facturs:cancel_title']      			= 'Batal Faktur';

//subtitles
$lang['facturs:accounts_details_sub']     	= 'Detail Akun';
$lang['facturs:total_summary_sub']      	= 'Total Keseluruhan';

// labels
$lang['facturs:invoice_number_label']		= 'Nomor Voucher';
$lang['facturs:factur_number_label']		= 'Nomor Faktur';
$lang['facturs:date_label']					= 'Tanggal';
$lang['facturs:due_date_label']				= 'Jatuh Tempo';
$lang['facturs:description_label']			= 'Deskripsi';
$lang['facturs:currency_label']				= 'Mata Uang';
$lang['facturs:section_label']				= 'Section';
$lang['facturs:account_number_label']		= 'Nomer Akun';
$lang['facturs:account_name_label']			= 'Nama Akun';
$lang['facturs:normal_pos_label']			= 'Normal Pos';
$lang['facturs:value_label']				= 'Nilai';
$lang['facturs:remain_label']				= 'Sisa';
$lang['facturs:qty_label']					= 'Qty';
$lang['facturs:customer_label']				= 'Customer';
$lang['facturs:card_number_label']			= 'Nomor Kartu';
$lang['facturs:diagnosis_label']			= 'Diagnosa';
$lang['facturs:username_label']				= 'Username';
$lang['facturs:password_label']				= 'Password';

$lang['facturs:periode_label']				= 'Periode';
$lang['facturs:till_label']					= 's/d';
$lang['facturs:cancel_factur_label']		= 'Faktur Dibatalkan';
$lang['facturs:find_factur_list_label']		= 'Cari Daftar Faktur';
$lang['facturs:factur_label']				= 'Faktur';
$lang['facturs:to_label']					= 'Kepada';
$lang['facturs:number_label']				= 'Nomor';
$lang['facturs:currency_label']				= 'Mata Uang';
$lang['facturs:no_label']					= 'No';
$lang['facturs:transaction_description_label'] = 'Deskripsi Transaksi';
$lang['facturs:amount_label']				= 'Jumlah';
$lang['facturs:total_label']				= 'Total';
$lang['facturs:note_label']					= 'Note';
$lang['facturs:spelled_label']				= 'Terbilang';

$lang['facturs:close_book_data']			= 'Data ini sudah Tutup Buku!';
$lang['facturs:posted_data']				= 'Data ini sudah di Posting!';
$lang['facturs:cancel_data']				= 'Data ini sudah di Batalkan!';
$lang['facturs:has_debit_credit_note']		= 'Proses batal Faktur gagal! Faktur ini memiliki Nota Debit Kredit!';
$lang['facturs:already_closing_period']		= 'Transaksi tidak dapat dilanjutkan, Karena sudah dilakukan Tutup Buku pada periode: %s!';
$lang['facturs:already_created_invoices']	= 'Faktur tersebut tidak bisa diedit karena telah dibuatkan Invoice!';
$lang['facturs:already_posted']				= 'Faktur tersebut tidak dapat diedit, Karena sudah diposting ke GL!';

$lang['facturs:value_not_match']			= 'Nilai Faktur tidak boleh 0 atau minus !';
$lang['facturs:details_cannot_empty']		= 'Detail Akun tidak boleh kosong!';
$lang['facturs:cancel_factur']				= 'Apa Anda yakin ingin membatalkan Faktur ini ?';
$lang['facturs:cannot_cancel_close_data']	= 'Tidak dapat membatalkan data yang sudah Tutup Buku!';

/* Note Debit Kredit
-------------------------------------------------------------------------------*/
// titles
$lang['credit_debit_notes:page']           			= 'Nota Debit Kredit Piutang';
$lang['credit_debit_notes:breadcrumb']        	 	= 'Nota Debit Kredit Piutang';
$lang['credit_debit_notes:modal_heading']     		= 'Rincian Nota Debit Kredit';
$lang['credit_debit_notes:create_heading']      	= 'Buat Nota Debit Kredit Piutang';
$lang['credit_debit_notes:edit_heading']        	= 'Edit Nota Debit Kredit Piutang';
$lang['credit_debit_notes:widget_heading']      	= 'Daftar Nota Debit Kredit Piutang';

$lang['credit_debit_notes:delete_title']      		= 'Hapus Nota Debit Kredit';

//subtitles
$lang['credit_debit_notes:accounts_details_sub']    = 'Detail Akun';
$lang['credit_debit_notes:total_summary_sub']      	= 'Total Keseluruhan';

// labels
$lang['credit_debit_notes:evidence_number_label']	= 'Nomor Bukti';
$lang['credit_debit_notes:invoice_number_label']	= 'Nomor Invoice';
$lang['credit_debit_notes:factur_number_label']		= 'Nomor Faktur';
$lang['credit_debit_notes:date_label']				= 'Tanggal';
$lang['credit_debit_notes:due_date_label']			= 'Jatuh Tempo';
$lang['credit_debit_notes:description_label']		= 'Deskripsi';
$lang['credit_debit_notes:currency_label']			= 'Mata Uang';
$lang['credit_debit_notes:section_label']			= 'Section';
$lang['credit_debit_notes:account_label']			= 'Akun';
$lang['credit_debit_notes:account_number_label']	= 'Nomer Akun';
$lang['credit_debit_notes:account_name_label']		= 'Nama Akun';
$lang['credit_debit_notes:normal_pos_label']		= 'Normal Pos';
$lang['credit_debit_notes:increase_label']			= 'Penambahan';
$lang['credit_debit_notes:decrease_label']			= 'Pengurangan';
$lang['credit_debit_notes:original_value_label']	= 'Nilai Asal';
$lang['credit_debit_notes:balance_label']			= 'Saldo';
$lang['credit_debit_notes:value_label']				= 'Nilai';
$lang['credit_debit_notes:remain_label']			= 'Sisa';
$lang['credit_debit_notes:qty_label']				= 'Qty';
$lang['credit_debit_notes:customer_label']			= 'Customer';
$lang['credit_debit_notes:project_label']			= 'Proyek';
$lang['credit_debit_notes:division_label']			= 'Divisi';

$lang['credit_debit_notes:periode_label']			= 'Periode';
$lang['credit_debit_notes:till_label']				= 's/d';
$lang['credit_debit_notes:cancel_factur_label']		= 'Nota Debit Kredit Dibatalkan';
$lang['credit_debit_notes:find_credit_debit_note_list_label']	= 'Cari Daftar Nota Debit Kredit';
$lang['credit_debit_notes:search_transactions_label']= 'Cari Transaksi';
$lang['credit_debit_notes:delete_reasons_label']	= 'Alasan Hapus';

$lang['credit_debit_notes:close_book_data']			= 'Data ini sudah Tutup Buku!!';
$lang['credit_debit_notes:posted_data']				= 'Data ini sudah di Posting!';
$lang['credit_debit_notes:cancel_data']				= 'Data ini sudah di Hapus!';

$lang['credit_debit_notes:customer_not_selected']		= 'Customer belum dipilih ! \n Silahkan pilih Customer terlebih dahulu.';
$lang['credit_debit_notes:value_not_match']			= 'Nilai Nota Debit Kredit tidak boleh 0 atau minus !';
$lang['credit_debit_notes:details_cannot_empty']	= 'Detail Akun tidak boleh kosong!';
$lang['credit_debit_notes:delete_credit_debit_note']= 'Apa Anda yakin ingin menghapus Nota Debit Kredit ini ?';
$lang['credit_debit_notes:cannot_cancel_close_data']= 'Tidak dapat membatalkan data yang sudah Tutup Buku!';
$lang['credit_debit_notes:transaction_date_incorret']	= 'Tanggal Nota tidak boleh sebelum Tanggal Invoice: %s!';
$lang['credit_debit_notes:invoice_not_selected']	= 'Transaksi tidak dapat dilanjutkan, Karena tidak ada Invoice yang dipilih!';
$lang['credit_debit_notes:already_closing_period']	= 'Transaksi tidak dapat dilanjutkan, Karena sudah dilakukan Tutup Buku pada periode: %s!';
$lang['credit_debit_notes:empty_increase_decrease']	= 'Transaksi tidak dapat dilanjutkan, Karena Nilai Pengurangan atau Penambahan belum diinput pada Invoice: %s!';
$lang['credit_debit_notes:decrease_value_exceed']	= 'Transaksi tidak dapat dilanjutkan, Karena Nilai Pengurangan melebihi Nilai Sisa pada Invoice: %s!';
$lang['credit_debit_notes:simultaneously_increase_decrease']= 'Transaksi tidak dapat dilanjutkan, Karena tidak bisa melakukan Penambahan dan Pengurangan secara bersamaan!';


/* Beginning Balance
-------------------------------------------------------------------------------*/
// titles
$lang['beginning_balances:page']           				= 'Saldo Awal Piutang';
$lang['beginning_balances:list_heading']       			= 'Daftar Saldo Awal Piutang';
$lang['beginning_balances:breadcrumb']        	 		= 'Saldo Awal Piutang';
$lang['beginning_balances:modal_heading']     		  	= 'Details';
$lang['beginning_balances:create_heading']      		= 'Saldo Awal Piutang';

//subtitles
$lang['beginning_balances:total_summary_sub']      		= 'Total Summary';

// labels
$lang['beginning_balances:date_label']					= 'Tanggal';
$lang['beginning_balances:currency_label']				= 'Mata uang';
$lang['beginning_balances:customer_label']				= 'Customer';
$lang['beginning_balances:customer_code_label']			= 'Kode Customer';
$lang['beginning_balances:customer_name_label']			= 'Nama Customer';
$lang['beginning_balances:value_label']					= 'Nilai';



$lang['beginning_balances:paid_receivable']				= 'Piutang ini sudah dibayar! Batalkan pembayaran jika anda ingin melanjutkan proses ini!';
$lang['beginning_balances:close_book']					= 'Piutang ini sudah Tutup Buku! Batalkan Tutup Buku jika anda ingin melanjutkan proses ini!';
$lang['beginning_balances:identical_posted']			= 'Data Customer dan Tipe Piutang ini sudah ada. Silahkan masukan data lain!';

/* Postings
-------------------------------------------------------------------------------*/
// titles
$lang['postings:page']           			= 'Posting Piutang';
$lang['postings:cancel_page']           	= 'Batal Posting Piutang';
$lang['postings:breadcrumb']        	 	= 'Posting Piutang';
$lang['postings:modal_heading']     		= 'Rincian Piutang';
$lang['postings:create_heading']      		= 'Posting Piutang';
$lang['postings:widget_heading']      		= 'Daftar Posting Piutang';

$lang['postings:cancel_title']      			= 'Batal Faktur';

//subtitles
$lang['postings:accounts_details_sub']     	= 'Detail Akun';
$lang['postings:total_summary_sub']      	= 'Total Keseluruhan';

// labels
$lang['postings:invoice_number_label']		= 'Nomor Voucher';
$lang['postings:posting_number_label']		= 'Nomor Bukti';
$lang['postings:date_label']				= 'Tanggal';
$lang['postings:due_date_label']			= 'Jatuh Tempo';
$lang['postings:description_label']			= 'Deskripsi';
$lang['postings:currency_label']			= 'Mata Uang';
$lang['postings:section_label']				= 'Section';
$lang['postings:account_number_label']		= 'Nomer Akun';
$lang['postings:account_name_label']		= 'Nama Akun';
$lang['postings:normal_pos_label']			= 'Normal Pos';
$lang['postings:value_label']				= 'Nilai';
$lang['postings:remain_label']				= 'Sisa';
$lang['postings:qty_label']					= 'Qty';
$lang['postings:customer_label']			= 'Customer';
$lang['postings:username_label']			= 'Username';
$lang['postings:password_label']			= 'Password';

$lang['postings:periode_label']				= 'Periode';
$lang['postings:till_label']				= 's/d';
$lang['postings:cancel_posting_label']		= 'Faktur Dibatalkan';
$lang['postings:find_posting_list_label']	= 'Cari Data';

$lang['postings:posting_successfully']		= 'Posting data berhasil!';
$lang['postings:posting_cancel_successfully']= 'Batal Posting data berhasil!';
$lang['postings:value_not_match']			= 'Nilai Faktur tidak boleh 0 atau minus !';
$lang['postings:no_data_selected']			= 'Tidak ada data yang dipilih. Harap pilih setidaknya satu data!';
$lang['postings:empty_posting_data']		= 'Transaksi tidak dapat dilanjutkan, Karena tidak ada data Posting yang dipilih!';
$lang['postings:posting_confirm']			= 'Apa Anda yakin ingin memPosting data ini ?';
$lang['postings:cancel_posting_confirm']	= 'Apa Anda yakin ingin membatalkan data Posting ini ?';
$lang['postings:cannot_cancel_close_data']	= 'Tidak dapat membatalkan data yang sudah Tutup Buku!';
$lang['postings:posting_failed']			= 'Terjadi kesalahan! Gagal memposting data!';
$lang['postings:journal_close_book']		= 'Gagal Batal Posting! Nomor Bukti <b>%s</b> sudah ditutup buku pada Journal GL!';

/* Close Books
-------------------------------------------------------------------------------*/
// titles
$lang['closing:page']           			= 'Tutup Buku';
$lang['closing:breadcrumb']        	 		= 'Tutup Buku';
$lang['closing:cancel_page']           		= 'Batal Tutup Buku';
$lang['closing:cancel_breadcrumb']        	= 'Batal Tutup Buku';
$lang['closing:modal_heading']     		  	= 'Details';
$lang['closing:create_heading']      		= 'Tutup Buku';

// labels
$lang['closing:date_label']					= 'Tanggal';
$lang['closing:last_closing_label']			= 'Tutup buku terakhir';
$lang['closing:username_label']				= 'Username';
$lang['closing:password_label']				= 'Password';

$lang['closing:closing_confirm']			= 'Anda yakin ingin melakukan Tutup Buku pada periode: %s. Setelah anda melakukan Tutup Buku, maka transaksi pada periode tersebut tidak dapat diedit lagi. Anda yakin ingin melanjutkan ?';
$lang['closing:closing_cancel_confirm']		= 'Anda yakin ingin melakukan pembatalan tutup buku pada periode: %s';
$lang['closing:un_posting_data']			= 'Tutup Buku gagal! Terdapa Faktur atau Invoice yang belum di Posting';
$lang['closing:un_posting_mutation_data']	= 'Masih terdapat transaksi MUTASI (GUDANG) yang belum di posting. Posting terlebih dahulu semua transaksi sebelum tutup buku!';
$lang['closing:invoice_incorrect_transaction'] = 'Terdapat transaksi Invoice yang salah. Transaksi dengan No Invoice %s terinput pada bulan %s, Pada bulan tersebut telah dilakukan tutup buku!';
$lang['closing:unclosing_previous_month']	= "Anda tidak dapat melakukan Tutup Buku untuk periode ini. Karena periode sebelumya belum dilakukan Tutup Buku.";
$lang['closing:period_empty_transaction']	= "Anda tidak dapat melakukan tutup buku pada periode ini. Karena periode ini belum ada transaksi.";
$lang['closing:not_accordance_with_gl']		= "Maaf sistem tidak dapat melanjutkan transaksi. Karena saldo Rekap Piutang tidak sesuai dengan Saldo di General Ledger. Silahkan hubungi vendor!!!";
$lang['closing:trouble_ar_tipe_not_macth']	= 'Maaf sistem tidak dapat melanjutkan transaksi. Karena terdapat perbedaan Akun Tipe Header dengan detail Faktur. Silahkan hubungi vendor!!!';
$lang['closing:trouble_ar_balance_not_macth']	= "Maaf sistem tidak dapat melanjutkan transaksi. Karena saldo Rekap Piutang tidak sesuai dengan Saldo di General Ledger (Detail Per Akun). Silahkan hubungi vendor!!!";
$lang['closing:check_cancelled_card_receivable']= "Maaf sistem tidak dapat melanjutkan transaksi. Karena Terdapat kartu dengan faktur yang sudah di cancel atau belum diakui piutang. Silahkan hubungi vendor!!!";
$lang['closing:check_not_related_card_receivable']= "Maaf sistem tidak dapat melanjutkan transaksi. Karena Terdapat kartu dengan faktur yang sudah TIDAK ADA DI TRANSAKSI FAKTUR. Silahkan hubungi vendor!!!";
$lang['closing:recap_receivable_not_macth']	= "Maaf sistem tidak dapat melanjutkan transaksi. Karena saldo Rekap Piutang (TRANSAKSI-Belum Tutup buku) tidak sesuai dengan Saldo di General Ledger (TRANSAKSI-Belum Tutup Buku). Silahkan hubungi vendor!!!";
$lang['closing:card_receivable_not_macth_aging']= "Maaf sistem tidak dapat melanjutkan transaksi. Karena terdapat ketidakcocokan antara Kartu dengan Aging Hutang. Silahkan hubungi vendor!!!";
$lang['closing:card_type_receivable_not_macth_aging']= "Maaf sistem tidak dapat melanjutkan transaksi. Karena terdapat %s antara kartu dengan aging hutang. Silahkan hubungi vendor!!!";
$lang['closing:success_status']				= 'Proses Tutup Buku Berhasil!';
$lang['closing:failure_status']				= 'Proses Tutup Buku Gagal!';

$lang['closing:check_general_ledger_closing_period']= "Pembatalan Tutup Buku tidak dapat dilakukan. Karena pada General Ledger masih Tutup Buku. Batalkan terlebih dahulu Tutup Buku pada General Ledger!";
$lang['closing:check_next_closing_period']	= "Anda tidak dapat membatalkan Tutup Buku pada periode tersebut. Karena sudah ada proses Tutup Buku untuk periode selanjutnya!";
$lang['closing:cancel_success_status']		= 'Proses Batal Tutup Buku Berhasil!';
$lang['closing:cancel_failure_status']		= 'Proses Batal Tutup Buku Gagal!';

/* Aging
-------------------------------------------------------------------------------*/
// titles
$lang['aging:page']           				= 'Umur Piutang';
$lang['aging:list_heading']       			= 'Daftar Umur Piutang';
$lang['aging:breadcrumb']        	 		= 'Umur Piutang';
$lang['aging:modal_heading']     		  	= 'Details';
$lang['aging:create_heading']      			= 'Umur Piutang';

//subtitles
$lang['aging:total_summary_sub']      		= 'Total Summary';
$lang['aging:customer_lookup_title']  		= 'Pilih Customer';

// labels
$lang['aging:date_label']					= 'Tanggal';
$lang['aging:currency_label']				= 'Mata uang';
$lang['aging:customer_label']				= 'Customer';
$lang['aging:customer_code_label']			= 'Kode Customer';
$lang['aging:customer_name_label']			= 'Nama Customer';
$lang['aging:amount_label']					= 'Jumlah';
$lang['aging:type_label']					= 'Tipe Piutang';
$lang['aging:currency_label']				= 'Mata Uang';
$lang['aging:not_due_label']				= 'Belum Tempo';
$lang['aging:due_label']					= 'Jatuh tempo';
$lang['aging:1_30_label']					= '1-30Hari';
$lang['aging:31_60_label']					= '31-60Hari';
$lang['aging:61_90_label']					= '61-90Hari';
$lang['aging:91_180_label']					= '91-180Hari';
$lang['aging:181_365_label']				= '181-365Hari';
$lang['aging:1_year_label']					= '>1Tahun';
$lang['aging:grand_total_label']			= 'Grand Total';


/* Reports
-------------------------------------------------------------------------------*/
// titles
$lang['reports:page']           		= 'Laporan Piutang';
$lang['reports:breadcrumb']        	 	= 'Laporan Piutang';
$lang['reports:modal_heading']     		= 'Laporan Piutang';
$lang['reports:create_heading']      	= 'Laporan Piutang';
$lang['reports:edit_heading']        	= 'Laporan Piutang';
$lang['reports:widget_heading']      	= 'Laporan Piutang';

//subtitles
$lang['reports:report_type_sub']     	= 'Pilih Jenis Reports';
$lang['reports:customer_lookup_title'] 	= 'Pilih Customer';

//label
$lang['reports:card_receivable_label']	= 'Kartu Piutang';
$lang['reports:recap_receivable_label']	= 'Rekap Piutang';
$lang['reports:card_receivable_report']	= 'Laporan Kartu Piutang';
$lang['reports:recap_receivable_report']= 'Laporan Rekap Piutang';

$lang['reports:customer_label']			= 'Customer';
$lang['reports:periode_label']			= 'Periode';
$lang['reports:till_label']				= 's/d';

$lang['reports:number_label'] 			= 'No.';
$lang['reports:date_label'] 			= 'Tanggal';
$lang['reports:evidence_number_label'] 	= 'Nomor Bukti';
$lang['reports:code_label'] 			= 'Kode';
$lang['reports:description_label'] 		= 'Deskripsi';
$lang['reports:beginning_balance_label']= 'Saldo Awal';
$lang['reports:debit_label'] 			= 'Debit';
$lang['reports:credit_label'] 			= 'Kredit';
$lang['reports:ending_balance_label'] 	= 'Saldo Akhir';
$lang['reports:sub_total_label'] 		= 'Sub Total';
$lang['reports:grand_total_label'] 		= 'Grand Total';

$lang['reports:button_pdf']				= 'PDF';
$lang['reports:button_excel']			= 'Excel';

$lang['reports:madeby_label']			= 'Dibuat Oleh';
$lang['reports:approvedby_label']		= 'Disetujui Oleh';

$lang['reports:card_receivable_filename']	= 'Kartu-Piutang-%s-%s.pdf';
$lang['reports:recap_receivable_filename']	= 'Rekap-Piutang-%s-%s.pdf';
























